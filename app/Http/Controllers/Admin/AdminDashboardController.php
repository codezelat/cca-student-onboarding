<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CCARegistration;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with registrations.
     */
    public function index(Request $request)
    {
        $query = CCARegistration::query();

        // Search by Register ID, Full Name, Email, NIC, or WhatsApp Number
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
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
        $generalRateCount = CCARegistration::whereJsonContains('tags', 'General Rate')->count();
        $specialOfferCount = CCARegistration::whereJsonContains('tags', 'Special 50% Offer')->count();
        $mostRegisteredProgram = CCARegistration::select('program_id', 'program_name', DB::raw('count(*) as total'))
            ->groupBy('program_id', 'program_name')
            ->orderByDesc('total')
            ->first();

        // Paginate results (25 per page) and append query string
        $registrations = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->appends($request->query());

        $registrations->getCollection()->transform(function (CCARegistration $registration) {
            $registration->setAttribute(
                'payment_slip',
                $this->normalizeFilesForDisplay($registration->payment_slip)
            );

            return $registration;
        });

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
     * Show details of a specific registration.
     */
    public function show($id)
    {
        $registration = CCARegistration::with('payments')->findOrFail($id);

        $registration->setAttribute('academic_qualification_documents', $this->normalizeFilesForDisplay($registration->academic_qualification_documents));
        $registration->setAttribute('nic_documents', $this->normalizeFilesForDisplay($registration->nic_documents));
        $registration->setAttribute('passport_documents', $this->normalizeFilesForDisplay($registration->passport_documents));
        $registration->setAttribute('passport_photo', $this->normalizeFilesForDisplay($registration->passport_photo));
        $registration->setAttribute('payment_slip', $this->normalizeFilesForDisplay($registration->payment_slip));

        return view('admin.show', compact('registration'));
    }

    /**
     * Show edit form for a registration.
     */
    public function edit($id)
    {
        $registration = CCARegistration::with('payments')->findOrFail($id);
        $programs = config('programs.programs');
        $countries = config('programs.countries');
        $sriLankaDistricts = config('programs.sri_lanka_districts');

        $registration->setAttribute('payment_slip', $this->normalizeFilesForDisplay($registration->payment_slip));

        return view('admin.edit', compact('registration', 'programs', 'countries', 'sriLankaDistricts'));
    }

    /**
     * Update a registration.
     */
    public function update(Request $request, $id)
    {
        $registration = CCARegistration::findOrFail($id);
        $programs = config('programs.programs', []);

        $validated = $request->validate([
            'program_id' => ['required', 'string', 'max:20', Rule::in(array_keys($programs))],
            'full_name' => 'required|string|max:255',
            'name_with_initials' => 'required|string|max:255',
            'email_address' => 'required|email|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'nic_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cca_registrations', 'nic_number')
                    ->where(fn ($query) => $query->where('program_id', $request->input('program_id')))
                    ->ignore($registration->id),
            ],
            'passport_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cca_registrations', 'passport_number')
                    ->where(fn ($query) => $query->where('program_id', $request->input('program_id')))
                    ->ignore($registration->id),
            ],
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
            'full_amount' => 'nullable|numeric|min:0',
        ]);

        // Keep program fields in sync with configured program ID.
        if (isset($programs[$validated['program_id']])) {
            $validated['program_name'] = $programs[$validated['program_id']]['name'];
            $validated['program_year'] = $programs[$validated['program_id']]['year'];
            $validated['program_duration'] = $programs[$validated['program_id']]['duration'];
        }

        try {
            $registration->update($validated);
        } catch (QueryException $e) {
            if ($this->isDuplicateConstraintViolation($e)) {
                return back()
                    ->withErrors([
                        'nic_number' => 'Another registration with this NIC or passport number already exists for the selected program.',
                    ])
                    ->withInput();
            }

            throw $e;
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Registration updated successfully!');
    }

    /**
     * Delete a registration.
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
     * Export registrations to CSV.
     */
    public function export(Request $request)
    {
        $query = CCARegistration::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
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

        if ($request->filled('tag_filter')) {
            $query->whereJsonContains('tags', $request->tag_filter);
        }

        $registrations = $query->with('payments')->orderBy('created_at', 'desc')->get();

        $filename = 'cca_registrations_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $this->sanitizeCsvRow([
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
                'Full Amount',
                'Current Paid Amount',
                'Registration Date',
                'Academic Qualification Documents',
                'NIC Documents',
                'Passport Documents',
                'Passport Photo',
                'Payment Slip',
                'Payment Entry Count',
                'Payment Ledger',
            ]));

            foreach ($registrations as $reg) {
                fputcsv($file, $this->sanitizeCsvRow([
                    $reg->register_id ?? 'cca-A' . str_pad((string) $reg->id, 5, '0', STR_PAD_LEFT),
                    $reg->program_id,
                    $reg->program_name,
                    $reg->program_year ?? 'N/A',
                    $reg->program_duration ?? 'N/A',
                    $reg->full_name,
                    $reg->name_with_initials ?? 'N/A',
                    ucfirst($reg->gender),
                    $reg->date_of_birth?->format('Y-m-d') ?? 'N/A',
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
                    ucfirst(str_replace('_', ' ', (string) $reg->highest_qualification)),
                    $reg->qualification_other_details ?? 'N/A',
                    ucfirst((string) ($reg->qualification_status ?? 'N/A')),
                    $reg->qualification_completed_date?->format('Y-m-d') ?? 'N/A',
                    $reg->qualification_expected_completion_date?->format('Y-m-d') ?? 'N/A',
                    ! empty($reg->tags) ? implode(', ', $reg->tags) : 'N/A',
                    $reg->full_amount ? number_format((float) $reg->full_amount, 2) : 'N/A',
                    $reg->current_paid_amount ? number_format((float) $reg->current_paid_amount, 2) : 'N/A',
                    $reg->created_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $this->getFileUrls($reg->academic_qualification_documents),
                    $this->getFileUrls($reg->nic_documents),
                    $this->getFileUrls($reg->passport_documents),
                    $this->getFileUrls($reg->passport_photo),
                    $this->getFileUrls($reg->payment_slip),
                    (string) $reg->payments->count(),
                    $this->formatPaymentLedgerForExport($reg),
                ]));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete all files associated with a registration from Cloudflare R2 storage.
     */
    private function deleteRegistrationFiles(CCARegistration $registration): void
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
            foreach ($this->extractFilePaths($registration->$field) as $path) {
                $totalFiles++;

                try {
                    if (Storage::disk('r2')->exists($path)) {
                        Storage::disk('r2')->delete($path);
                        $deletedFiles++;
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to delete file from R2: {$path}", [
                        'error' => $e->getMessage(),
                        'registration_id' => $registration->id,
                    ]);
                }
            }
        }

        Log::info("Deleted {$deletedFiles} out of {$totalFiles} files for registration {$registration->id}");
    }

    /**
     * Normalize file data to a consistent array shape with secure temporary URLs.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeFilesForDisplay(mixed $files): array
    {
        $normalized = [];

        foreach ($this->asFileItems($files) as $item) {
            $normalizedItem = $this->normalizeFileItem($item);
            if ($normalizedItem !== null) {
                $normalized[] = $normalizedItem;
            }
        }

        return $normalized;
    }

    /**
     * Normalize a single file item for display.
     *
     * @return array<string, mixed>|null
     */
    private function normalizeFileItem(mixed $item): ?array
    {
        if (is_array($item)) {
            $path = $this->extractStoragePath($item['path'] ?? $item['url'] ?? null);
            $url = $path ? $this->generateTemporaryFileUrl($path) : null;

            if (! $url && isset($item['url']) && is_string($item['url'])) {
                $url = $item['url'];
            }

            return array_merge($item, [
                'path' => $path,
                'url' => $url,
            ]);
        }

        if (is_string($item)) {
            $path = $this->extractStoragePath($item);
            $url = $path ? $this->generateTemporaryFileUrl($path) : null;

            if (! $url && filter_var($item, FILTER_VALIDATE_URL)) {
                $url = $item;
            }

            if (! $path && ! $url) {
                return null;
            }

            return [
                'path' => $path,
                'url' => $url,
            ];
        }

        return null;
    }

    /**
     * Convert stored file value to iterable file items.
     *
     * @return array<int, mixed>
     */
    private function asFileItems(mixed $files): array
    {
        if (empty($files)) {
            return [];
        }

        if (is_string($files)) {
            return [$files];
        }

        if (! is_array($files)) {
            return [];
        }

        if (array_is_list($files)) {
            return $files;
        }

        // Associative array = single file object.
        return [$files];
    }

    /**
     * Extract storage paths from a file field value.
     *
     * @return array<int, string>
     */
    private function extractFilePaths(mixed $files): array
    {
        $paths = [];

        foreach ($this->asFileItems($files) as $item) {
            if (is_array($item)) {
                $path = $this->extractStoragePath($item['path'] ?? $item['url'] ?? null);
            } elseif (is_string($item)) {
                $path = $this->extractStoragePath($item);
            } else {
                $path = null;
            }

            if ($path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Extract R2 object path from stored path/URL value.
     */
    private function extractStoragePath(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $path = parse_url($value, PHP_URL_PATH);
            if (! is_string($path) || $path === '') {
                return null;
            }

            $path = ltrim($path, '/');
            $bucket = trim((string) config('filesystems.disks.r2.bucket'), '/');
            if ($bucket !== '' && str_starts_with($path, $bucket . '/')) {
                $path = substr($path, strlen($bucket) + 1);
            }

            return $path !== '' ? $path : null;
        }

        $path = ltrim($value, '/');
        return $path !== '' ? $path : null;
    }

    /**
     * Generate a temporary URL for secure file access.
     */
    private function generateTemporaryFileUrl(string $path): ?string
    {
        try {
            return Storage::disk('r2')->temporaryUrl(
                $path,
                now()->addMinutes((int) config('filesystems.temporary_url_ttl', 20))
            );
        } catch (\Exception $e) {
            try {
                return Storage::disk('r2')->url($path);
            } catch (\Exception $fallbackException) {
                return null;
            }
        }
    }

    /**
     * Extract secure file URLs and format them for export.
     */
    private function getFileUrls(mixed $files): string
    {
        $normalized = $this->normalizeFilesForDisplay($files);
        if (empty($normalized)) {
            return 'N/A';
        }

        $urls = array_values(array_filter(array_map(
            fn (array $file) => $file['url'] ?? null,
            $normalized
        )));

        return ! empty($urls) ? implode("\n", $urls) : 'N/A';
    }

    /**
     * Format payment rows in a single text cell for export.
     */
    private function formatPaymentLedgerForExport(CCARegistration $registration): string
    {
        $rows = $registration->payments->sortBy('payment_no');

        if ($rows->isEmpty()) {
            return 'N/A';
        }

        return $rows->map(function ($payment): string {
            return sprintf(
                '#%d | %s | %s | LKR %s | %s%s',
                $payment->payment_no,
                $payment->payment_date?->format('Y-m-d') ?? 'N/A',
                ucwords(str_replace('_', ' ', (string) $payment->payment_method)),
                number_format((float) $payment->amount, 2),
                strtoupper((string) $payment->status),
                $payment->receipt_reference ? ' | Ref: ' . $payment->receipt_reference : ''
            );
        })->implode("\n");
    }

    /**
     * Escape CSV cells to prevent spreadsheet formula injection.
     *
     * @param  array<int, mixed>  $row
     * @return array<int, string>
     */
    private function sanitizeCsvRow(array $row): array
    {
        return array_map(fn ($value) => $this->sanitizeCsvValue($value), $row);
    }

    /**
     * Escape potentially dangerous CSV values.
     */
    private function sanitizeCsvValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $string = (string) $value;

        if (
            preg_match('/^[=\+\-@]/', $string) === 1
            || str_starts_with($string, "\t")
            || str_starts_with($string, "\r")
        ) {
            return "'" . $string;
        }

        return $string;
    }

    /**
     * Determine if exception came from a duplicate key violation.
     */
    private function isDuplicateConstraintViolation(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? null;
        $driverCode = $e->errorInfo[1] ?? null;

        return $sqlState === '23000' || $driverCode === 1062 || $driverCode === 19;
    }
}
