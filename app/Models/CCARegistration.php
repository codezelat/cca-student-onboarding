<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CCARegistration extends Model
{
    protected $table = 'cca_registrations';

    protected $fillable = [
        'program_id',
        'program_name',
        'program_year',
        'program_duration',
        'full_name',
        'name_with_initials',
        'gender',
        'date_of_birth',
        'nic_number',
        'passport_number',
        'nationality',
        'country_of_birth',
        'country_of_residence',
        'permanent_address',
        'postal_code',
        'country',
        'district',
        'province',
        'email_address',
        'whatsapp_number',
        'home_contact_number',
        'guardian_contact_name',
        'guardian_contact_number',
        'highest_qualification',
        'qualification_other_details',
        'qualification_status',
        'qualification_completed_date',
        'qualification_expected_completion_date',
        'academic_qualification_documents',
        'nic_documents',
        'passport_documents',
        'passport_photo',
        'payment_slip',
        'terms_accepted',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'qualification_completed_date' => 'date',
        'qualification_expected_completion_date' => 'date',
        'academic_qualification_documents' => 'array',
        'nic_documents' => 'array',
        'passport_documents' => 'array',
        'passport_photo' => 'array',
        'payment_slip' => 'array',
        'terms_accepted' => 'boolean',
    ];

    /**
     * Validation rules for registration
     */
    public static function validationRules(): array
    {
        return [
            'program_id' => 'required|string|max:10',
            'full_name' => 'required|string|max:255',
            'name_with_initials' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'nic_number' => 'nullable|string|max:255', // Optional for international students
            'passport_number' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'country_of_birth' => 'required|string|max:255',
            'country_of_residence' => 'required|string|max:255',
            'permanent_address' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'district' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'email_address' => 'required|email|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'home_contact_number' => 'nullable|string|max:20',
            'guardian_contact_name' => 'required|string|max:255',
            'guardian_contact_number' => 'required|string|max:20',
            'highest_qualification' => 'required|in:degree,diploma,postgraduate,msc,phd,work_experience,other',
            'qualification_other_details' => 'nullable|required_if:highest_qualification,other|required_if:highest_qualification,work_experience|string|max:255',
            'qualification_status' => 'required|in:completed,ongoing',
            'qualification_completed_date' => 'nullable|required_if:qualification_status,completed|date|before_or_equal:today',
            'qualification_expected_completion_date' => 'nullable|required_if:qualification_status,ongoing|date',
            // Accept more file types including iPhone formats
            'academic_qualification_documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,heic,webp|max:10240',
            'nic_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,webp|max:10240',
            'passport_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,heic,webp|max:10240',
            'passport_photo' => 'required|file|mimes:jpg,jpeg,png,heic,webp|max:10240',
            'payment_slip' => 'required|file|mimes:pdf,jpg,jpeg,png,heic,webp|max:10240',
            'terms_accepted' => 'required|accepted',
        ];
    }

    /**
     * Custom validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'program_id.required' => 'Program ID is required to continue your registration.',
            'full_name.required' => 'Please enter your full name as it appears on official documents.',
            'email_address.required' => 'We need your email address to send you updates about your application.',
            'email_address.email' => 'Please enter a valid email address (e.g., name@example.com).',
            'whatsapp_number.required' => 'WhatsApp number is required so we can contact you quickly.',
            
            // File upload validation messages
            'academic_qualification_documents.*.required' => 'Please upload at least one qualification document.',
            'academic_qualification_documents.*.mimes' => 'Qualification documents must be PDF, DOC, DOCX, JPG, JPEG, PNG, HEIC, or WEBP format.',
            'academic_qualification_documents.*.max' => 'Each document must be 10MB or smaller. Please compress large files.',
            
            'nic_documents.*.mimes' => 'ID documents must be PDF, JPG, JPEG, PNG, HEIC, or WEBP format.',
            'nic_documents.*.max' => 'Each ID document must be 10MB or smaller.',
            
            'passport_documents.*.mimes' => 'Passport documents must be PDF, JPG, JPEG, PNG, HEIC, or WEBP format.',
            'passport_documents.*.max' => 'Each passport document must be 10MB or smaller.',
            
            'passport_photo.required' => 'Please upload a passport-size photo (2x2 inch) for your student ID card.',
            'passport_photo.mimes' => 'Photo must be JPG, JPEG, PNG, HEIC, or WEBP format.',
            'passport_photo.max' => 'Photo must be 10MB or smaller.',
            
            'payment_slip.required' => 'Please upload your payment confirmation slip or bank receipt.',
            'payment_slip.mimes' => 'Payment slip must be PDF, JPG, JPEG, PNG, HEIC, or WEBP format.',
            'payment_slip.max' => 'Payment slip must be 10MB or smaller.',
            
            'terms_accepted.accepted' => 'Please read and accept the terms to complete your registration.',
            'qualification_completed_date.before_or_equal' => 'Completion date cannot be in the future.',
            'date_of_birth.before' => 'Please enter a valid date of birth.',
        ];
    }
}
