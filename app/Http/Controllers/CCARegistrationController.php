<?php

namespace App\Http\Controllers;

use App\Models\CCARegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CCARegistrationController extends Controller
{
    /**
     * Show the registration form
     */
    public function create()
    {
        $programs = config('programs.programs');
        $countries = config('programs.countries');
        $sriLankaDistricts = config('programs.sri_lanka_districts');

        return view('cca-register', compact('programs', 'countries', 'sriLankaDistricts'));
    }

    /**
     * Store the registration
     */
    public function store(Request $request)
    {
        // Validate program ID exists
        $programs = config('programs.programs');
        $programId = strtoupper($request->program_id);
        
        if (!isset($programs[$programId])) {
            throw ValidationException::withMessages([
                'program_id' => 'This Program ID is not recognized. Please contact our support team to verify your Program ID.',
            ]);
        }

        // Custom validation: require either NIC or Passport
        if (empty($request->nic_number) && empty($request->passport_number)) {
            throw ValidationException::withMessages([
                'nic_number' => 'Please provide either your National ID/NIC or Passport number for identification.',
            ]);
        }

        // Check for duplicate registration using available ID
        $identificationField = !empty($request->nic_number) ? 'nic_number' : 'passport_number';
        $identificationValue = !empty($request->nic_number) ? $request->nic_number : $request->passport_number;
        
        $existingRegistration = CCARegistration::where('program_id', $programId)
            ->where(function($query) use ($identificationValue) {
                $query->where('nic_number', $identificationValue)
                      ->orWhere('passport_number', $identificationValue);
            })
            ->first();

        if ($existingRegistration) {
            $programName = $programs[$programId]['name'];
            throw ValidationException::withMessages([
                $identificationField => "You have already registered for {$programName}. If you believe this is an error, please contact our support team.",
            ]);
        }

        // Validate all fields
        $validated = $request->validate(
            CCARegistration::validationRules(),
            CCARegistration::validationMessages()
        );

        try {
            DB::beginTransaction();

            // Get program details
            $programInfo = $programs[$programId];

            // Handle file uploads
            $academicDocs = $this->uploadMultipleFiles($request, 'academic_qualification_documents', 'registrations/academic');
            
            // NIC/ID documents (optional for international students without NIC)
            $nicDocs = $request->hasFile('nic_documents') 
                ? $this->uploadMultipleFiles($request, 'nic_documents', 'registrations/identification')
                : [];
                
            $passportDocs = $request->hasFile('passport_documents') 
                ? $this->uploadMultipleFiles($request, 'passport_documents', 'registrations/passport')
                : [];
            
            $passportPhoto = $request->file('passport_photo')->store('registrations/photos', 'public');
            $paymentSlip = $request->file('payment_slip')->store('registrations/payments', 'public');

            // Create registration
            $registration = CCARegistration::create([
                'program_id' => $programId,
                'program_name' => $programInfo['name'],
                'program_year' => $programInfo['year'],
                'program_duration' => $programInfo['duration'],
                'full_name' => $validated['full_name'],
                'name_with_initials' => $validated['name_with_initials'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'nic_number' => $validated['nic_number'] ?? null,
                'passport_number' => $validated['passport_number'] ?? null,
                'nationality' => $validated['nationality'],
                'country_of_birth' => $validated['country_of_birth'],
                'country_of_residence' => $validated['country_of_residence'],
                'permanent_address' => $validated['permanent_address'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'],
                'district' => $validated['district'] ?? null,
                'province' => $validated['province'] ?? null,
                'email_address' => $validated['email_address'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'home_contact_number' => $validated['home_contact_number'] ?? null,
                'guardian_contact_name' => $validated['guardian_contact_name'],
                'guardian_contact_number' => $validated['guardian_contact_number'],
                'highest_qualification' => $validated['highest_qualification'],
                'qualification_other_details' => $validated['qualification_other_details'] ?? null,
                'qualification_status' => $validated['qualification_status'],
                'qualification_completed_date' => $validated['qualification_completed_date'] ?? null,
                'qualification_expected_completion_date' => $validated['qualification_expected_completion_date'] ?? null,
                'academic_qualification_documents' => $academicDocs,
                'nic_documents' => !empty($nicDocs) ? $nicDocs : null,
                'passport_documents' => !empty($passportDocs) ? $passportDocs : null,
                'passport_photo' => $passportPhoto,
                'payment_slip' => $paymentSlip,
                'terms_accepted' => true,
            ]);

            DB::commit();

            return redirect()
                ->route('cca.register')
                ->with('success', "Registration successful! Your application for {$programInfo['name']} has been submitted. We'll contact you at {$validated['email_address']} soon.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded files on error
            if (isset($academicDocs)) $this->deleteFiles($academicDocs);
            if (isset($nicDocs)) $this->deleteFiles($nicDocs);
            if (isset($passportDocs)) $this->deleteFiles($passportDocs);
            if (isset($passportPhoto)) Storage::disk('public')->delete($passportPhoto);
            if (isset($paymentSlip)) Storage::disk('public')->delete($paymentSlip);

            throw $e;
        }
    }

    /**
     * Upload multiple files and return their paths
     */
    private function uploadMultipleFiles(Request $request, string $fieldName, string $path): array
    {
        $files = $request->file($fieldName);
        $paths = [];

        if (is_array($files)) {
            foreach ($files as $file) {
                $paths[] = $file->store($path, 'public');
            }
        }

        return $paths;
    }

    /**
     * Delete multiple files
     */
    private function deleteFiles(?array $files): void
    {
        if ($files) {
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
