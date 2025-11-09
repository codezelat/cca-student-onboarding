# CCA Student Registration System

## Overview

A world-class student registration system built with Laravel 11, featuring a beautiful glassmorphic UI design with purple gradients, comprehensive validation, and robust file upload handling.

## Features

### ✨ Design Excellence

-   **Purple Glassmorphism Theme**: Professional, modern design perfect for IT enthusiasts
-   **Responsive Layout**: Works seamlessly on all devices
-   **Dynamic Form Sections**: Organized into 5 logical sections for optimal UX
-   **Real-time Validation**: Client-side validation with Alpine.js
-   **Accessible**: WCAG compliant with proper labels and error messages

### 🎯 Form Sections

#### 1. Program Information

-   Program ID with real-time validation
-   Auto-displays program name, year, and duration
-   Prevents invalid program codes

#### 2. Personal Information

-   Full name and name with initials
-   Gender selection
-   Date of birth (with past date validation)
-   NIC number
-   Passport number (optional)
-   Nationality
-   Country of birth
-   Country of permanent residence

#### 3. Contact Information

-   Permanent address
-   Country-based dynamic fields (province/district for Sri Lanka)
-   Postal code
-   Email address
-   WhatsApp number
-   Home contact (optional)
-   Guardian/emergency contact details

#### 4. Qualification Information

-   Highest qualification dropdown (PhD, MSc, Postgraduate, Degree, Diploma, Work Experience, Other)
-   Dynamic "Other" specification field
-   Status selection (Completed/Ongoing)
-   Conditional date fields based on status
-   Completion date for completed qualifications
-   Expected completion date for ongoing studies

#### 5. Required Documents

-   Academic/Work qualification proof (up to 2 files)
-   NIC documents (up to 2 files - front/back)
-   Passport copy (optional, up to 2 files)
-   Passport-size photo (2x2 inch, required)
-   Payment slip (required)
-   **File validation**: Max 10MB per file
-   **Accepted formats**: PDF, DOC, DOCX, JPG, PNG

### 🔒 Security & Validation

#### Backend Validation

-   All fields validated with Laravel's validation rules
-   Program ID checked against config array
-   Duplicate prevention (same NIC + Program ID)
-   File type and size validation
-   Required field enforcement

#### Frontend Validation

-   Real-time file size checking (10MB limit)
-   Program ID lookup and display
-   Dynamic field requirements based on selections
-   Date validation (past for DOB, future for expected completion)

### 🗄️ Database Structure

**Table**: `cca_registrations`

**Key Fields**:

-   Program info (id, name, year, duration)
-   Personal info (all identity details)
-   Contact info (with optional district/province)
-   Qualification info (with conditional dates)
-   Document paths (JSON arrays for multiple files)
-   Unique constraint on `program_id` + `nic_number`

### 📁 File Organization

```
app/
├── Http/Controllers/
│   └── CCARegistrationController.php    # Main controller
├── Models/
│   └── CCARegistration.php              # Model with validation rules

config/
└── programs.php                         # Program codes configuration

database/migrations/
└── 2025_11_09_213023_create_cca_registrations_table.php

resources/views/
└── cca-register.blade.php               # Registration form

routes/
└── web.php                              # Registration routes

storage/app/public/registrations/
├── academic/                            # Academic documents
├── nic/                                 # NIC documents
├── passport/                            # Passport documents
├── photos/                              # Passport photos
└── payments/                            # Payment slips
```

### 🎓 Available Programs (Default Configuration)

| Code | Program Name                               | Year | Duration  |
| ---- | ------------------------------------------ | ---- | --------- |
| PM25 | Project Management Professional            | 2025 | 6 Months  |
| DM25 | Digital Marketing Excellence               | 2025 | 4 Months  |
| DA25 | Data Analytics & Business Intelligence     | 2025 | 8 Months  |
| WD25 | Full Stack Web Development                 | 2025 | 10 Months |
| CS25 | Cyber Security Fundamentals                | 2025 | 6 Months  |
| AI25 | Artificial Intelligence & Machine Learning | 2025 | 12 Months |
| UX25 | UI/UX Design Mastery                       | 2025 | 5 Months  |
| CC25 | Cloud Computing with AWS                   | 2025 | 7 Months  |

### 🔧 Adding New Programs

Edit `config/programs.php`:

```php
'programs' => [
    'CODE' => [
        'name' => 'Program Name',
        'year' => '2025',
        'duration' => 'X Months',
    ],
    // Add more programs...
],
```

### 🌍 Geographic Data

**Countries**: 35+ countries pre-configured
**Sri Lanka**: 9 provinces with districts mapped

### 🚀 Usage

#### Access the Form

```
http://your-domain.com/cca-register
```

#### Submit Registration

1. Enter valid Program ID (e.g., PM25)
2. Fill all required personal information
3. Provide contact details
4. Select qualification details
5. Upload required documents (max 10MB each)
6. Accept terms and conditions
7. Submit

#### Success Response

-   Redirects back to form with success message
-   Displays submitted program details and email

#### Error Handling

-   Invalid Program ID: Displays error message
-   Duplicate NIC: Shows which program already registered
-   File size exceeded: Client-side alert before upload
-   Validation errors: Highlighted per field with messages
-   Database error: Transaction rollback with file cleanup

### 🎨 Design Specifications

**Color Palette**:

-   Primary: Purple (#a855f7 - #9333ea)
-   Secondary: Purple (#8b5cf6 - #7c3aed)
-   Background: Violet-Purple-Indigo gradient blobs
-   Glass: White with 40-60% opacity + backdrop blur

**Typography**:

-   Font: Inter (300, 400, 500, 600, 700)
-   Headings: Bold gradient text
-   Body: Gray-700/600 for readability

**Components**:

-   Glassmorphic cards with blur effects
-   Purple gradient buttons
-   Smooth transitions and hover effects
-   Responsive grid layouts

### 🔍 Key Features Implemented

✅ **Program Validation**: Real-time check against config array
✅ **Duplicate Prevention**: Unique constraint on program + NIC
✅ **Smart Fields**: Dynamic show/hide based on country, qualification
✅ **File Management**: Multiple file uploads with size validation
✅ **Error Recovery**: Transaction rollback on failure
✅ **Success Feedback**: Clear confirmation messages
✅ **Mobile Optimized**: Fully responsive design
✅ **Accessibility**: Proper labels, ARIA attributes
✅ **Best Practices**:

-   Laravel 11 conventions
-   RESTful routing
-   Model-based validation
-   Secure file uploads
-   Database transactions

### 📊 Validation Rules Summary

| Field                                  | Rules                          | Notes               |
| -------------------------------------- | ------------------------------ | ------------------- |
| program_id                             | required, exists in config     | Max 10 chars        |
| nic_number                             | required, unique per program   | Prevents duplicates |
| email_address                          | required, email format         |                     |
| date_of_birth                          | required, before today         |                     |
| qualification_status                   | required, in:completed,ongoing |                     |
| qualification_completed_date           | required if status=completed   | Must be past        |
| qualification_expected_completion_date | required if status=ongoing     | Must be future      |
| academic_qualification_documents       | required, 2 files max          | 10MB each           |
| passport_photo                         | required, image                | 10MB max            |
| terms_accepted                         | required, accepted             | Must check          |

### 🎯 User Experience Highlights

1. **Progressive Disclosure**: Shows only relevant fields
2. **Inline Help**: Tooltips and helper text throughout
3. **Visual Feedback**: Program info card appears when valid ID entered
4. **Error Prevention**: Client-side checks before submission
5. **Clear CTAs**: Prominent "Apply Now" buttons on homepage
6. **Confirmation**: Success message with details

### 🛠️ Technical Stack

-   **Backend**: Laravel 11
-   **Frontend**: Blade, Alpine.js, Tailwind CSS
-   **Database**: MySQL (via migrations)
-   **File Storage**: Laravel Storage (public disk)
-   **Validation**: Server-side + Client-side
-   **UI Framework**: Custom glassmorphic theme

### 📝 Notes for Administrators

1. **Storage**: Run `php artisan storage:link` to enable uploads
2. **Programs**: Update `config/programs.php` to add new programs
3. **Countries**: Edit `config/programs.php` for country list
4. **Districts**: Modify Sri Lanka districts in same config file
5. **File Limits**: Adjust in model validation rules if needed
6. **Database**: Migration includes unique constraint on program_id + nic_number

### 🔐 Security Considerations

-   CSRF protection on all forms
-   File type validation (whitelist approach)
-   File size limits enforced
-   SQL injection prevention via Eloquent
-   No authentication required (public registration)
-   Input sanitization via Laravel validation

---

## Quick Start

```bash
# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Access form
Visit: http://your-domain.com/cca-register
```

**Form URL**: `/cca-register`  
**Submission**: POST to `/cca-register`

---

Built with ❤️ following world-class UI/UX principles and Laravel best practices.
