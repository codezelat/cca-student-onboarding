<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cca_registrations', function (Blueprint $table) {
            $table->id();
            
            // Program Information
            $table->string('program_id', 10); // e.g., PM25
            $table->string('program_name');
            $table->string('program_year');
            $table->string('program_duration');
            
            // Personal Information
            $table->string('full_name');
            $table->string('name_with_initials');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->string('nic_number')->nullable(); // Optional for international students
            $table->string('passport_number')->nullable();
            $table->string('nationality');
            $table->string('country_of_birth');
            $table->string('country_of_residence');
            
            // Contact Information
            $table->text('permanent_address');
            $table->string('postal_code');
            $table->string('country');
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('email_address');
            $table->string('whatsapp_number');
            $table->string('home_contact_number')->nullable();
            $table->string('guardian_contact_name');
            $table->string('guardian_contact_number');
            
            // Qualification Information
            $table->enum('highest_qualification', ['degree', 'diploma', 'postgraduate', 'msc', 'phd', 'work_experience', 'other']);
            $table->string('qualification_other_details')->nullable();
            $table->enum('qualification_status', ['completed', 'ongoing']);
            $table->date('qualification_completed_date')->nullable();
            $table->date('qualification_expected_completion_date')->nullable();
            
            // Document Paths (JSON for multiple files)
            $table->json('academic_qualification_documents'); // up to 2 files
            $table->json('nic_documents')->nullable(); // up to 2 files (optional for international students)
            $table->json('passport_documents')->nullable(); // up to 2 files
            $table->string('passport_photo'); // single file
            $table->string('payment_slip'); // single file
            
            // Agreement
            $table->boolean('terms_accepted')->default(false);
            
            $table->timestamps();
            
            // Indexes for duplicate checking
            $table->index(['program_id', 'nic_number']);
            $table->index(['program_id', 'passport_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cca_registrations');
    }
};
