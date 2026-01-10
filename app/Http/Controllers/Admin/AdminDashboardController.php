<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CCARegistration;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with registrations
     */
    public function index(Request $request)
    {
        $query = CCARegistration::query();

        // Search by Register ID, Full Name, Email, NIC, or WhatsApp Number
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('register_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('full_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email_address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nic_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('whatsapp_number', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by Program Category
        if ($request->filled('program_filter')) {
            $query->where('program_id', $request->program_filter);
        }

        // Filter by Tag (General Rate or Special Offer)
        if ($request->filled('tag_filter')) {
            $query->whereJsonContains('tags', $request->tag_filter);
        }

        // Get all unique program IDs for filter dropdown
        $programs = CCARegistration::select('program_id', 'program_name')
            ->distinct()
            ->orderBy('program_name')
            ->get();

        // Calculate statistics
        $totalRegistrations = CCARegistration::count();
        
        // Count General Rate registrations (those with "General Rate" tag)
        $generalRateCount = CCARegistration::whereJsonContains('tags', 'General Rate')->count();
        
        // Count Special Offer registrations (those with "Special 50% Offer" tag)
        $specialOfferCount = CCARegistration::whereJsonContains('tags', 'Special 50% Offer')->count();
        
        // Get most registered program
        $mostRegisteredProgram = CCARegistration::select('program_id', 'program_name', DB::raw('count(*) as total'))
            ->groupBy('program_id', 'program_name')
            ->orderByDesc('total')
            ->first();

        // Paginate results (25 per page) and append query string
        $registrations = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends($request->query());

        return view('admin.dashboard', compact(
            'registrations', 
            'programs',
            'totalRegistrations',
            'generalRateCount',
            'specialOfferCount',
            'mostRegisteredProgram'
        ));
    }

    /**
     * Show details of a specific registration
     */
    public function show($id)
    {
        $registration = CCARegistration::findOrFail($id);
        return view('admin.show', compact('registration'));
    }

    /**
     * Show edit form for a registration
     */
    public function edit($id)
    {
        $registration = CCARegistration::findOrFail($id);
        $programs = config('programs.programs');
        $countries = config('programs.countries');
        $sriLankaDistricts = config('programs.sri_lanka_districts');
        
        return view('admin.edit', compact('registration', 'programs', 'countries', 'sriLankaDistricts'));
    }

    /**
     * Update a registration
     */
    public function update(Request $request, $id)
    {
        $registration = CCARegistration::findOrFail($id);
        
        $validated = $request->validate([
            'program_id' => 'required|string|max:20',
            'full_name' => 'required|string|max:255',
            'name_with_initials' => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'nic_number' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string|max:255',
            'permanent_address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'home_contact_number' => 'nullable|string|max:20',
            'guardian_contact_name' => 'required|string|max:255',
            'guardian_contact_number' => 'required|string|max:20',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'current_paid_amount' => 'nullable|numeric|min:0',
        ]);

        // Update program_name if program_id changed
        $programs = config('programs.programs');
        if (isset($programs[$validated['program_id']])) {
            $validated['program_name'] = $programs[$validated['program_id']]['name'];
        }

        $registration->update($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Registration updated successfully!');
    }

    /**
     * Delete a registration
     */
    public function destroy($id)
    {
        $registration = CCARegistration::findOrFail($id);
        
        // Delete associated files from Cloudflare R2 storage
        $this->deleteRegistrationFiles($registration);
        
        $registration->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Registration and all associated files deleted successfully!');
    }

    /**
     * Export registrations to Excel
     */
    public function export(Request $request)
    {
        $query = CCARegistration::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('register_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('full_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email_address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nic_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('whatsapp_number', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('program_filter')) {
            $query->where('program_id', $request->program_filter);
        }

        // Filter by Tag (General Rate or Special Offer)
        if ($request->filled('tag_filter')) {
            $query->whereJsonContains('tags', $request->tag_filter);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'cca_registrations_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Register ID',
                'Program ID',
                'Program Name',
                'Program Year',
                'Program Duration',
                'Full Name',
                'Name with Initials',
                'Gender',
                'Date of Birth',
                'NIC',
                'Passport Number',
                'Nationality',
                'Country of Birth',
                'Country of Residence',
                'Email',
                'WhatsApp Number',
                'Home Contact',
                'Permanent Address',
                'Postal Code',
                'Country',
                'District',
                'Province',
                'Guardian Name',
                'Guardian Contact',
                'Highest Qualification',
                'Qualification Other Details',
                'Qualification Status',
                'Qualification Completed Date',
                'Qualification Expected Completion Date',
                'Tags',
                'Current Paid Amount',
                'Registration Date',
                'Academic Qualification Documents',
                'NIC Documents',
                'Passport Documents',
                'Passport Photo',
                'Payment Slip',
            ]);

            // Add data rows
            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->register_id ?? 'cca-A' . str_pad($reg->id, 5, '0', STR_PAD_LEFT),
                    $reg->program_id,
                    $reg->program_name,
                    $reg->program_year ?? 'N/A',
                    $reg->program_duration ?? 'N/A',
                    $reg->full_name,
                    $reg->name_with_initials ?? 'N/A',
                    ucfirst($reg->gender),
                    $reg->date_of_birth->format('Y-m-d'),
                    $reg->nic_number ?? 'N/A',
                    $reg->passport_number ?? 'N/A',
                    $reg->nationality,
                    $reg->country_of_birth ?? 'N/A',
                    $reg->country_of_residence ?? $reg->country,
                    $reg->email_address,
                    $reg->whatsapp_number,
                    $reg->home_contact_number ?? 'N/A',
                    $reg->permanent_address,
                    $reg->postal_code ?? 'N/A',
                    $reg->country,
                    $reg->district ?? 'N/A',
                    $reg->province ?? 'N/A',
                    $reg->guardian_contact_name,
                    $reg->guardian_contact_number,
                    ucfirst(str_replace('_', ' ', $reg->highest_qualification)),
                    $reg->qualification_other_details ?? 'N/A',
                    ucfirst($reg->qualification_status ?? 'N/A'),
                    $reg->qualification_completed_date ? $reg->qualification_completed_date->format('Y-m-d') : 'N/A',
                    $reg->qualification_expected_completion_date ? $reg->qualification_expected_completion_date->format('Y-m-d') : 'N/A',
                    !empty($reg->tags) ? implode(', ', $reg->tags) : 'N/A',
                    $reg->current_paid_amount ? number_format($reg->current_paid_amount, 2) : 'N/A',
                    $reg->created_at->format('Y-m-d H:i:s'),
                    $this->getFileUrls($reg->academic_qualification_documents),
                    $this->getFileUrls($reg->nic_documents),
                    $this->getFileUrls($reg->passport_documents),
                    $this->getFileUrls($reg->passport_photo),
                    $this->getFileUrls($reg->payment_slip),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete all files associated with a registration from Cloudflare R2 storage
     */
    private function deleteRegistrationFiles(CCARegistration $registration)
    {
        $fileFields = [
            'academic_qualification_documents',
            'nic_documents', 
            'passport_documents',
            'passport_photo',
            'payment_slip',
        ];

        $deletedFiles = 0;
        $totalFiles = 0;

        foreach ($fileFields as $field) {
            $files = $registration->$field;
            
            if (is_array($files)) {
                foreach ($files as $file) {
                    $totalFiles++;
                    if (isset($file['path'])) {
                        try {
                            if (Storage::disk('r2')->exists($file['path'])) {
                                Storage::disk('r2')->delete($file['path']);
                                $deletedFiles++;
                            }
                        } catch (\Exception $e) {
                            Log::warning("Failed to delete file from R2: {$file['path']}", [
                                'error' => $e->getMessage(),
                                'registration_id' => $registration->id
                            ]);
                        }
                    }
                }
            } elseif (is_string($files) && !empty($files)) { 
                // Handle old format where files might be stored as strings
                $totalFiles++;
                try {
                    // Extract path from URL if it's a full URL
                    $path = $files;
                    if (str_contains($files, 'r2.dev/')) {
                        $urlParts = parse_url($files);
                        $path = ltrim($urlParts['path'], '/');
                    }
                    
                    if (Storage::disk('r2')->exists($path)) {
                        Storage::disk('r2')->delete($path);
                        $deletedFiles++;
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to delete file from R2: {$files}", [
                        'error' => $e->getMessage(),
                        'registration_id' => $registration->id
                    ]);
                }
            }
        }

        Log::info("Deleted {$deletedFiles} out of {$totalFiles} files for registration {$registration->id}");
    }

    /**
     * Extract file URLs from array and format them for Excel export
     * 
     * @param array|null $files
     * @return string
     */
    private function getFileUrls($files): string
    {
        if (empty($files)) {
            return 'N/A';
        }

        /** @var FilesystemAdapter $r2Disk */
        $r2Disk = Storage::disk('r2');

        // If files is an array of file objects
        if (is_array($files)) {
            $urls = [];
            foreach ($files as $file) {
                if (isset($file['url'])) {
                    $urls[] = $file['url'];
                } elseif (isset($file['path'])) {
                    // Generate URL from path if URL is not stored
                    try {
                        $urls[] = $r2Disk->url($file['path']);
                    } catch (\Exception $e) {
                        $urls[] = 'Error generating URL';
                    }
                }
            }
            // Join multiple URLs with newline for better readability in Excel
            return !empty($urls) ? implode("\n", $urls) : 'N/A';
        }

        return 'N/A';
    }
}
