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
            'nic_number' => 'required|string|max:255',
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
            'qualification_other_details' => 'nullable|required_if:highest_qualification,other|string|max:255',
            'qualification_status' => 'required|in:completed,ongoing',
            'qualification_completed_date' => 'nullable|required_if:qualification_status,completed|date',
            'qualification_expected_completion_date' => 'nullable|required_if:qualification_status,ongoing|date|after:today',
            'academic_qualification_documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'nic_documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'passport_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'passport_photo' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'payment_slip' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'terms_accepted' => 'required|accepted',
        ];
    }

    /**
     * Custom validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'program_id.required' => 'Program ID is required. Contact our support team if you don\'t have one.',
            'academic_qualification_documents.*.max' => 'Each qualification document must not exceed 10MB.',
            'nic_documents.*.max' => 'Each NIC document must not exceed 10MB.',
            'passport_documents.*.max' => 'Each passport document must not exceed 10MB.',
            'passport_photo.max' => 'Passport photo must not exceed 10MB.',
            'payment_slip.max' => 'Payment slip must not exceed 10MB.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions to proceed.',
            'qualification_expected_completion_date.after' => 'Expected completion date must be a future date.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ];
    }
}
