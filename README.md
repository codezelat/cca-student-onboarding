# 🎓 Codezela Career Accelerator (CCA) - Student Onboarding Portal

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A comprehensive, enterprise-grade student registration and management system for Career Accelerator Programs**

[Features](#-features) •
[Installation](#-installation) •
[Configuration](#%EF%B8%8F-configuration) •
[Usage](#-usage) •
[Admin Panel](#-admin-panel) •
[API](#-api-documentation) •
[Support](#-support)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [System Requirements](#-system-requirements)
- [Installation](#-installation)
- [Configuration](#%EF%B8%8F-configuration)
- [Usage](#-usage)
- [Admin Panel](#-admin-panel)
- [Database Schema](#-database-schema)
- [File Structure](#-file-structure)
- [Security](#-security)
- [API Documentation](#-api-documentation)
- [Best Practices](#-best-practices-implemented)
- [Deployment](#-deployment)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)
- [Support](#-support)

---

## 🌟 Overview

The **Codezela Career Accelerator (CCA) Student Onboarding Portal** is a full-featured, production-ready web application designed to streamline the registration process for career development programs. Built with modern web technologies and following industry best practices, this system provides a seamless experience for both students and administrators.

### Key Highlights

- 🎯 **18 Career Programs** - Full Stack, Frontend, Backend, Mobile App, Data Science, AI/ML, and more
- 🌍 **Multi-country Support** - Sri Lanka districts + 195 countries
- 📁 **Cloud Storage** - Cloudflare R2 integration for secure document storage
- 🔒 **Enterprise Security** - reCAPTCHA v3, CSRF protection, XSS prevention
- 📊 **Advanced Admin Dashboard** - Real-time statistics, filtering, Excel export
- 💰 **Payment Management** - Tags system, payment tracking, special offers
- 🎨 **Modern UI/UX** - Responsive design, animations, dark/light themes
- ♿ **Accessibility** - WCAG 2.1 compliant, keyboard navigation, ARIA labels
- 📱 **Mobile-First** - Fully responsive across all devices

---

## ✨ Features

### 🎓 Student Features

#### Registration System
- **Smart Program Selection** - Real-time program validation with active/inactive status
- **Multi-document Upload** - Academic qualifications, ID documents, passport, photos
- **Progress Tracking** - Visual progress indicators during upload and submission
- **Duplicate Prevention** - Automatic detection of existing registrations
- **Form Validation** - Client-side and server-side validation with helpful error messages
- **Auto-fill Support** - Browser autofill compatibility for faster form completion

#### Document Management
- **Multiple File Types** - Support for PDF, JPG, PNG, HEIC, and more
- **File Size Validation** - 10MB per file limit with user-friendly error messages
- **Secure Upload** - Direct upload to Cloudflare R2 with encryption
- **Preview Support** - View uploaded documents before submission

#### User Experience
- **3D Hero Section** - Interactive Three.js animation on landing page
- **Animated Backgrounds** - Liquid gradient blob animations
- **Glassmorphism UI** - Modern frosted glass design elements
- **Real-time Feedback** - Instant validation and status updates
- **Multi-step Forms** - Organized sections with clear navigation
- **Helpful Tooltips** - Contextual help throughout the form

### 👨‍💼 Admin Features

#### Dashboard
- **Real-time Statistics**
  - Total registrations count
  - General rate registrations
  - Special offer registrations
  - Most registered program
- **Advanced Filtering** - Search by name, email, phone, program
- **Pagination** - Efficient data loading for large datasets
- **Export to Excel** - One-click export of filtered results

#### Registration Management
- **Detailed View** - Complete student information with all documents
- **Edit Capabilities** - Update student information and program assignments
- **Payment Management**
  - Multi-tag system (Full Payment, Partial, Special Offers, etc.)
  - Current paid amount tracking
  - Visual tag display with color coding
- **Delete Functionality** - Safe deletion with file cleanup
- **Bulk Actions** - Process multiple registrations efficiently

#### Document Viewer
- **Unified Viewer** - View all student documents in one modal
- **Navigation Controls** - Previous/Next buttons and keyboard shortcuts
- **Image Zoom & Pan** - Zoom in/out with mouse wheel, drag to pan
- **PDF Support** - Embedded PDF viewer with controls
- **Download Options** - Direct download and open in new tab
- **Category Badges** - Personal, Payment, Academic, Identity labels

#### User Management
- **Role-based Access** - Admin roles with Spatie Permissions
- **Profile Management** - Update admin profiles and passwords
- **Secure Authentication** - Laravel Breeze with admin guard
- **Session Management** - Remember me, timeout controls

### 🔒 Security Features

#### Protection Layers
- **Google reCAPTCHA v3** - Invisible bot protection on registration
- **CSRF Protection** - Laravel's built-in CSRF tokens on all forms
- **XSS Prevention** - Input sanitization and output escaping
- **SQL Injection Prevention** - Eloquent ORM and prepared statements
- **File Upload Security** - MIME type validation, size limits, virus scanning ready
- **Rate Limiting** - Prevent brute force attacks
- **Security Headers** - X-Frame-Options, X-Content-Type-Options, XSS-Protection

#### Data Privacy
- **Encrypted Storage** - All files stored with encryption on Cloudflare R2
- **Secure URLs** - Pre-signed URLs with expiration for document access
- **HTTPS Enforcement** - Force HTTPS in production
- **No Index for Admin** - Admin pages excluded from search engines
- **Audit Logging Ready** - Database structure supports activity logging

---

## 🛠 Tech Stack

### Backend
- **Laravel 12.x** - PHP framework for web artisans
- **PHP 8.2+** - Modern PHP with type safety and performance
- **MySQL 8.0+** - Relational database (also supports PostgreSQL, SQLite)
- **Laravel Breeze** - Authentication scaffolding
- **Spatie Laravel Permission** - Role and permission management
- **Guzzle HTTP** - API client for external services

### Frontend
- **Blade Templates** - Laravel's templating engine
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js 3.x** - Lightweight JavaScript framework
- **Vite** - Next-generation frontend tooling
- **Three.js** - 3D graphics and animations
- **Axios** - Promise-based HTTP client

### Cloud Services
- **Cloudflare R2** - S3-compatible object storage
- **Google reCAPTCHA v3** - Bot protection
- **Google Analytics** - Web analytics (production only)

### Development Tools
- **Laravel Pint** - PHP code style fixer
- **Laravel Pail** - Real-time log viewer
- **Laravel Sail** - Docker development environment
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager

---

## 💻 System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.35+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **Composer**: 2.5+

### Recommended Server Specifications
- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 10GB minimum (+ space for uploaded documents)
- **CPU**: 2 cores minimum, 4 cores recommended
- **SSL Certificate**: Required for production

### PHP Extensions Required
```
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick
```

---

## 🚀 Installation

### Quick Start

```bash
# Clone the repository
git clone https://github.com/yourusername/cca-student-onboarding.git
cd cca-student-onboarding

# Install dependencies and setup
composer setup

# Start development servers
composer dev
```

### Detailed Installation

#### 1. Clone Repository
```bash
git clone https://github.com/yourusername/cca-student-onboarding.git
cd cca-student-onboarding
```

#### 2. Install PHP Dependencies
```bash
composer install
```

#### 3. Install Node Dependencies
```bash
npm install
```

#### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 5. Configure Environment Variables
Edit `.env` file with your settings:

```env
# Application
APP_NAME=CCA
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cloudflare R2 Storage
FILESYSTEM_DISK=r2
AWS_ACCESS_KEY_ID=your_r2_access_key
AWS_SECRET_ACCESS_KEY=your_r2_secret_key
AWS_DEFAULT_REGION=auto
AWS_BUCKET=your_bucket_name
AWS_ENDPOINT=https://your_account_id.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=false
AWS_URL=https://your_public_url.com

# Google reCAPTCHA v3
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
RECAPTCHA_MINIMUM_SCORE=0.5

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### 6. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed admin user and roles
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=AdminUserSeeder
```

#### 7. Storage Setup
```bash
# Create storage link (if using local storage)
php artisan storage:link
```

#### 8. Build Assets
```bash
# For development
npm run dev

# For production
npm run build
```

#### 9. Set Permissions
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Or for development
chmod -R 777 storage bootstrap/cache
```

#### 10. Start Application
```bash
# Development server
php artisan serve

# With queue worker
php artisan serve & php artisan queue:work

# Or use composer script
composer dev
```

---

## ⚙️ Configuration

### Programs Configuration

Edit `config/programs.php` to manage available programs:

```php
'programs' => [
    'CCA-FS25' => [
        'name' => 'Full Stack Developer Career Accelerator',
        'year' => '2025',
        'duration' => '6 Months',
        'active' => true,  // Set to false to close registrations
    ],
    // Add more programs...
],
```

**Program Status Management:**
- Set `'active' => false` to close registrations for a program
- System automatically prevents new registrations for inactive programs
- Visual indicators appear on registration form
- Backend validation ensures no bypass

### Cloudflare R2 Setup

1. **Create R2 Bucket:**
   - Login to Cloudflare dashboard
   - Navigate to R2 → Create bucket
   - Note your Account ID and bucket name

2. **Generate API Credentials:**
   - R2 → Manage R2 API Tokens
   - Create API token with read/write permissions
   - Copy Access Key ID and Secret Access Key

3. **Configure Public Access (Optional):**
   - Enable custom domain for public access
   - Configure CORS if needed

4. **Update `.env`:**
   ```env
   AWS_ACCESS_KEY_ID=your_key
   AWS_SECRET_ACCESS_KEY=your_secret
   AWS_BUCKET=your_bucket
   AWS_ENDPOINT=https://account_id.r2.cloudflarestorage.com
   AWS_URL=https://your_public_url.com
   ```

### Google reCAPTCHA Setup

1. **Register Site:**
   - Visit [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
   - Register a new site with reCAPTCHA v3
   - Add your domain

2. **Get Keys:**
   - Copy Site Key and Secret Key

3. **Configure `.env`:**
   ```env
   RECAPTCHA_SITE_KEY=your_site_key
   RECAPTCHA_SECRET_KEY=your_secret_key
   RECAPTCHA_MINIMUM_SCORE=0.5
   ```

### Admin User Creation

**Default Admin Credentials:**
```
Email: admin@cca.it
Password: password
```

**Create Additional Admins:**
```bash
php artisan tinker

# Then run:
$user = User::create([
    'name' => 'Admin Name',
    'email' => 'admin@example.com',
    'password' => bcrypt('secure_password'),
]);
$user->assignRole('admin');
```

### Email Configuration

For sending notifications (optional):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="CCA Portal"
```

---

## 📖 Usage

### For Students

#### 1. Access Registration Form
Navigate to `https://your-domain.com/register`

#### 2. Complete Form Sections

**Program Information:**
- Enter your Program ID (e.g., CCA-PM25)
- System validates and displays program details
- Red warning appears if program is full

**Personal Information:**
- Full name, date of birth
- Gender selection
- NIC or Passport number (at least one required)

**Contact Information:**
- Permanent address
- Country and district (Sri Lanka specific)
- Email address
- Phone number (with validation)
- WhatsApp number

**Guardian Information:**
- Guardian's name
- Occupation
- Contact number

**Academic Qualifications:**
- Academic qualification level
- Upload documents (PDF/Images, max 10MB each)

**Identity Documents:**
- Upload NIC or Passport documents
- Multiple files supported

**Photo & Payment:**
- Upload passport-sized photo
- Upload payment slip
- Agree to terms and conditions

#### 3. Submit Registration
- Click "Submit Registration" button
- Real-time progress indicator shows upload status
- Success confirmation with registration ID
- Automatic redirect to confirmation page

### For Administrators

#### 1. Login
Navigate to `https://your-domain.com/admin/login`

**Default Credentials:**
```
Email: admin@cca.it
Password: password
```

#### 2. Dashboard Overview
- View registration statistics
- Filter by program, search students
- Export data to Excel
- Access individual registrations

#### 3. View Registration Details
- Click "View" button on any registration
- See complete student information
- View all uploaded documents
- Check payment status and tags

#### 4. Document Viewing
- Click "View All" button in Documents section
- Navigate through all documents with arrow keys
- Zoom images with mouse wheel
- Download or open in new tab

#### 5. Edit Registration
- Click "Edit" button on registration detail page
- Update program assignment
- Modify contact information
- Add payment tags (Full Payment, Special Offer, etc.)
- Set current paid amount
- Save changes

#### 6. Delete Registration
- Click "Delete" button
- Confirm deletion
- System automatically removes all associated files

#### 7. Manage Profile
- Click profile dropdown in navigation
- Select "Profile Settings"
- Update name, email, password
- Logout securely

---

## 🎛 Admin Panel

### Dashboard Features

#### Statistics Cards
1. **Total Registrations** - Overall count with blue gradient
2. **General Rate Registrations** - Count of general rate students (green)
3. **Special Offer Registrations** - Count of special offer students (purple)
4. **Most Registered Program** - Program with highest registrations (orange)

#### Filtering & Search
- **Search Bar** - Search by name, email, phone, or NIC
- **Program Filter** - Filter by specific program
- **Clear Filters** - Reset all filters
- **Export Button** - Export filtered results to Excel

#### Registration Table
- **Registration ID** - Auto-generated unique ID
- **Student Name** - Full name with email
- **Program** - Program code with badge
- **Contact** - Phone and WhatsApp
- **Submitted Date** - Registration timestamp
- **Actions** - View, Edit, Delete buttons

### Payment Management

#### Available Tags
- **Full Payment** (Green)
- **Special 50% Offer** (Purple)
- **Registration Fee** (Blue)
- **Partial Registration Fee** (Yellow)
- **General Rate** (Indigo)
- **125000** (Red)
- **105000** (Orange)
- **62500** (Pink)

#### Tag Management
- Add multiple tags per registration
- Visual color-coded badges
- Easy removal with click
- Saved automatically

#### Payment Tracking
- **Current Paid Amount** - Input field for amount paid
- **Payment Slip Viewer** - Quick link to view payment proof
- **Payment History Ready** - Database supports payment logging

### Document Management

#### Document Viewer Features
- **Unified Interface** - All documents in one modal
- **Navigation** - Previous/Next buttons, arrow keys, document counter
- **Image Controls** - Zoom in/out, pan, reset zoom
- **PDF Support** - Embedded viewer with toolbar
- **Download** - Direct download button
- **Open in Tab** - View in separate browser tab
- **Error Handling** - Graceful fallback for unsupported formats

#### Supported File Types
- **Images**: JPG, JPEG, PNG, GIF, WebP, HEIC, SVG, BMP
- **Documents**: PDF
- **Error Display** - User-friendly message for unsupported files

---

## 🗄 Database Schema

### `users` Table
```sql
- id: bigint (PK)
- name: varchar(255)
- email: varchar(255) UNIQUE
- email_verified_at: timestamp (nullable)
- password: varchar(255)
- remember_token: varchar(100) (nullable)
- created_at: timestamp
- updated_at: timestamp
```

### `cca_registrations` Table
```sql
- id: bigint (PK, auto-increment)
- register_id: varchar(20) UNIQUE (auto-generated)
- program_id: varchar(20)
- program_name: varchar(255)
- program_year: varchar(10)
- program_duration: varchar(50)
- full_name: varchar(255)
- date_of_birth: date
- gender: varchar(20)
- nic_number: varchar(20) (nullable)
- passport_number: varchar(50) (nullable)
- permanent_address: text
- country: varchar(100)
- province: varchar(100) (nullable)
- district: varchar(100) (nullable)
- email: varchar(255)
- phone_number: varchar(20)
- whatsapp_number: varchar(20)
- guardian_name: varchar(255)
- guardian_occupation: varchar(255)
- guardian_contact: varchar(20)
- academic_qualification: varchar(255)
- academic_qualification_documents: json (R2 URLs array)
- nic_documents: json (R2 URLs array)
- passport_documents: json (R2 URLs array)
- passport_photo: json (R2 URL object)
- payment_slip: json (R2 URL object)
- tags: json (nullable, payment tags array)
- current_paid_amount: decimal(10,2) (nullable)
- terms_accepted: boolean
- created_at: timestamp
- updated_at: timestamp

Indexes:
- program_id
- email
- nic_number
- passport_number
- register_id (unique)
```

### `roles` & `permissions` Tables
Managed by Spatie Laravel Permission package:
- roles: id, name, guard_name, created_at, updated_at
- permissions: id, name, guard_name, created_at, updated_at
- model_has_roles: Pivot table
- role_has_permissions: Pivot table

### `jobs` Table
```sql
- id: bigint (PK)
- queue: varchar(255)
- payload: longtext
- attempts: tinyint
- reserved_at: int (nullable)
- available_at: int
- created_at: int
```

### `cache` Table
```sql
- key: varchar(255) (PK)
- value: mediumtext
- expiration: int
```

---

## 📁 File Structure

```
cca-student-onboarding/
├── app/
│   ├── Console/
│   │   └── Commands/           # Artisan commands
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminDashboardController.php  # Admin CRUD operations
│   │   │   ├── Auth/                             # Authentication controllers
│   │   │   ├── CCARegistrationController.php     # Public registration
│   │   │   └── ProfileController.php             # User profile
│   │   ├── Middleware/
│   │   │   └── AdminAuthenticate.php             # Admin auth middleware
│   │   └── Requests/                             # Form requests
│   ├── Models/
│   │   ├── CCARegistration.php                   # Registration model
│   │   └── User.php                              # User model
│   ├── Providers/
│   │   └── AppServiceProvider.php                # Service providers
│   ├── Services/
│   │   ├── FileUploadService.php                 # R2 upload service
│   │   └── RecaptchaService.php                  # reCAPTCHA verification
│   └── View/
│       └── Components/                           # Blade components
├── bootstrap/
│   ├── app.php                                   # Application bootstrap
│   └── providers.php                             # Service providers
├── config/
│   ├── app.php                                   # App configuration
│   ├── database.php                              # Database config
│   ├── filesystems.php                           # R2 configuration
│   ├── programs.php                              # Programs & countries
│   ├── services.php                              # reCAPTCHA config
│   └── permission.php                            # Spatie permissions
├── database/
│   ├── factories/
│   │   └── UserFactory.php                       # Model factories
│   ├── migrations/                               # Database migrations
│   │   ├── 2025_11_09_213023_create_cca_registrations_table.php
│   │   └── 2026_01_11_005325_add_tags_and_paid_amount_to_cca_registrations_table.php
│   └── seeders/
│       ├── AdminUserSeeder.php                   # Default admin
│       ├── RoleSeeder.php                        # Admin role
│       └── DatabaseSeeder.php                    # Master seeder
├── public/
│   ├── images/
│   │   ├── icon.png                              # App icon
│   │   └── logo-wide.png                         # Logo
│   ├── build/                                    # Compiled assets
│   └── index.php                                 # Entry point
├── resources/
│   ├── css/
│   │   └── app.css                               # Main stylesheet
│   ├── js/
│   │   ├── app.js                                # Main JavaScript
│   │   ├── bootstrap.js                          # Bootstrap
│   │   └── file-upload.js                        # File upload handler
│   └── views/
│       ├── cca-register.blade.php                # Registration form
│       ├── welcome.blade.php                     # Landing page
│       ├── admin/
│       │   ├── dashboard.blade.php               # Admin dashboard
│       │   ├── show.blade.php                    # View registration
│       │   ├── edit.blade.php                    # Edit registration
│       │   ├── login.blade.php                   # Admin login
│       │   ├── profile.blade.php                 # Admin profile
│       │   ├── layouts/
│       │   │   └── app.blade.php                 # Admin layout
│       │   └── partials/
│       │       └── navigation.blade.php          # Admin nav
│       ├── auth/                                 # Auth views
│       ├── layouts/
│       │   ├── app.blade.php                     # Public layout
│       │   ├── guest.blade.php                   # Guest layout
│       │   └── navigation.blade.php              # Public nav
│       └── profile/                              # Profile views
├── routes/
│   ├── web.php                                   # Web routes
│   ├── auth.php                                  # Auth routes
│   └── console.php                               # Artisan commands
├── storage/
│   ├── app/                                      # Application files
│   ├── framework/                                # Framework files
│   └── logs/                                     # Log files
├── tests/                                        # PHPUnit tests
├── .env.example                                  # Environment template
├── artisan                                       # Artisan CLI
├── composer.json                                 # PHP dependencies
├── package.json                                  # JS dependencies
├── phpunit.xml                                   # PHPUnit config
├── tailwind.config.js                            # Tailwind config
├── vite.config.js                                # Vite config
└── README.md                                     # This file
```

---

## 🔐 Security

### Authentication & Authorization
- **Laravel Breeze** - Modern authentication with admin guard separation
- **Spatie Permissions** - Role-based access control (RBAC)
- **Session Management** - Secure session handling with database driver
- **Remember Me** - Optional persistent login with encrypted tokens

### Input Validation
- **Form Requests** - Centralized validation rules in `CCARegistration` model
- **Custom Rules** - NIC/Passport validation, program status check
- **File Validation** - MIME type checking, size limits, extension whitelist
- **Sanitization** - HTML purification, SQL injection prevention

### Data Protection
- **Encryption** - Sensitive data encrypted at rest
- **HTTPS Enforcement** - Force SSL in production
- **Password Hashing** - BCrypt with configurable rounds
- **API Token Security** - Sanctum-ready for API authentication

### File Security
- **Cloudflare R2** - Encrypted storage with access controls
- **Pre-signed URLs** - Temporary access with expiration
- **File Scanning** - Ready for antivirus integration
- **Upload Limits** - 10MB per file, configurable

### Headers & CORS
```php
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Strict-Transport-Security: max-age=31536000
```

### Rate Limiting
- **API Rate Limiting** - 60 requests per minute per IP
- **Login Throttling** - Protection against brute force attacks
- **Registration Throttling** - Prevent spam submissions

### Security Best Practices Implemented
✅ SQL Injection Prevention (Eloquent ORM)
✅ XSS Protection (Blade escaping)
✅ CSRF Protection (Laravel tokens)
✅ Clickjacking Prevention (X-Frame-Options)
✅ MIME Sniffing Prevention
✅ Input Sanitization
✅ Output Encoding
✅ Secure Password Storage
✅ Session Fixation Prevention
✅ File Upload Security

---

## 📡 API Documentation

### Public Endpoints

#### Register Student
```http
POST /register
Content-Type: multipart/form-data
```

**Request Body:**
```json
{
  "program_id": "CCA-PM25",
  "full_name": "John Doe",
  "date_of_birth": "1995-01-15",
  "gender": "Male",
  "nic_number": "950151234V",
  "email": "john@example.com",
  "phone_number": "+94771234567",
  "whatsapp_number": "+94771234567",
  "permanent_address": "123 Main St, Colombo",
  "country": "Sri Lanka",
  "province": "Western",
  "district": "Colombo",
  "guardian_name": "Jane Doe",
  "guardian_occupation": "Teacher",
  "guardian_contact": "+94771234568",
  "academic_qualification": "Bachelor's Degree",
  "academic_qualification_documents[]": [File],
  "nic_documents[]": [File],
  "passport_photo": File,
  "payment_slip": File,
  "terms_accepted": true,
  "recaptcha_token": "token_here"
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Registration submitted successfully!",
  "registration_id": "REG-2025-001234",
  "redirect_url": "/registration-success"
}
```

**Response (Error):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "program_id": [
      "Registration for Frontend Developer Career Accelerator is currently closed..."
    ]
  }
}
```

### Admin Endpoints

#### List Registrations
```http
GET /admin/registrations
Authorization: Session Cookie

Query Parameters:
- search: string (optional)
- program_filter: string (optional)
- page: integer (default: 1)
```

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "register_id": "REG-2025-001234",
      "full_name": "John Doe",
      "email": "john@example.com",
      "program_id": "CCA-PM25",
      "program_name": "Project Manager Career Accelerator",
      "phone_number": "+94771234567",
      "created_at": "2025-01-11T10:30:00.000000Z"
    }
  ],
  "total": 150,
  "per_page": 20,
  "last_page": 8
}
```

#### View Registration
```http
GET /admin/registrations/{id}
Authorization: Session Cookie
```

**Response:**
```json
{
  "id": 1,
  "register_id": "REG-2025-001234",
  "program": {
    "id": "CCA-PM25",
    "name": "Project Manager Career Accelerator",
    "year": "2025",
    "duration": "6 Months"
  },
  "student": {
    "full_name": "John Doe",
    "date_of_birth": "1995-01-15",
    "gender": "Male",
    "nic_number": "950151234V",
    "email": "john@example.com",
    "phone_number": "+94771234567",
    "whatsapp_number": "+94771234567"
  },
  "documents": {
    "passport_photo": {
      "url": "https://r2.../passport_photo.jpg",
      "name": "passport_photo.jpg"
    },
    "payment_slip": {
      "url": "https://r2.../payment_slip.pdf",
      "name": "payment_slip.pdf"
    },
    "academic_documents": [...],
    "nic_documents": [...]
  },
  "payment": {
    "tags": ["General Rate", "Partial Payment"],
    "current_paid_amount": 62500.00
  },
  "created_at": "2025-01-11T10:30:00.000000Z"
}
```

#### Update Registration
```http
PUT /admin/registrations/{id}
Authorization: Session Cookie
Content-Type: application/json
```

**Request Body:**
```json
{
  "program_id": "CCA-PM25",
  "full_name": "John Doe",
  "email": "john@example.com",
  "phone_number": "+94771234567",
  "tags": ["Full Payment", "General Rate"],
  "current_paid_amount": 125000.00
}
```

#### Delete Registration
```http
DELETE /admin/registrations/{id}
Authorization: Session Cookie
```

**Response:**
```json
{
  "success": true,
  "message": "Registration deleted successfully"
}
```

#### Export to Excel
```http
GET /admin/registrations/export
Authorization: Session Cookie

Query Parameters:
- search: string (optional)
- program_filter: string (optional)
```

**Response:** Binary Excel file download

---

## 🏆 Best Practices Implemented

### Code Quality
✅ **PSR-12 Coding Standards** - Laravel Pint enforcement
✅ **Type Hints** - Strict typing throughout
✅ **Dependency Injection** - Service container usage
✅ **Single Responsibility** - One purpose per class
✅ **DRY Principle** - No code duplication
✅ **SOLID Principles** - Object-oriented best practices
✅ **Meaningful Names** - Self-documenting code

### Database
✅ **Migrations** - Version-controlled schema
✅ **Seeders** - Repeatable data population
✅ **Eloquent ORM** - Query builder with relationships
✅ **Indexes** - Optimized queries
✅ **JSON Columns** - Flexible data storage
✅ **Soft Deletes Ready** - Recoverable deletions

### Frontend
✅ **Responsive Design** - Mobile-first approach
✅ **Progressive Enhancement** - Works without JavaScript
✅ **Accessibility** - WCAG 2.1 Level AA compliant
✅ **Performance** - Lazy loading, code splitting
✅ **SEO Friendly** - Semantic HTML, meta tags
✅ **Cross-browser** - IE11+, Chrome, Firefox, Safari, Edge

### Security
✅ **Least Privilege** - Minimal permissions
✅ **Defense in Depth** - Multiple security layers
✅ **Input Validation** - Never trust user input
✅ **Output Encoding** - Prevent XSS
✅ **Error Handling** - No sensitive data in errors
✅ **Audit Logging Ready** - Track admin actions

### Performance
✅ **Query Optimization** - Eager loading, indexes
✅ **Caching** - Database cache driver
✅ **Asset Optimization** - Vite bundling, minification
✅ **CDN Ready** - Static assets servable from CDN
✅ **Database Pooling** - Connection reuse
✅ **Queue System** - Background job processing

### Testing
✅ **Unit Tests Ready** - PHPUnit configured
✅ **Feature Tests Ready** - HTTP testing
✅ **Factory Pattern** - Test data generation
✅ **Continuous Integration Ready** - CI/CD compatible

### Documentation
✅ **Inline Comments** - Complex logic explained
✅ **PHPDoc Blocks** - All classes and methods
✅ **README.md** - Comprehensive documentation
✅ **API Documentation** - Endpoint specifications
✅ **Changelog Ready** - Version tracking

---

## 🚀 Deployment

### Production Checklist

#### Pre-deployment
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate secure `APP_KEY`
- [ ] Configure production database
- [ ] Set up Cloudflare R2 bucket
- [ ] Register Google reCAPTCHA domain
- [ ] Configure email provider
- [ ] Set up SSL certificate

#### Optimization
```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build production assets
npm run build

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev
```

#### Server Configuration

**Nginx Example:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com;
    root /var/www/cca-student-onboarding/public;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 20M;
}
```

**Apache Example (.htaccess):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

<IfModule mod_headers.c>
    Header set X-Frame-Options "DENY"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

php_value upload_max_filesize 20M
php_value post_max_size 20M
php_value max_execution_time 300
```

#### Queue Worker Setup

**Systemd Service (`/etc/systemd/system/cca-queue.service`):**
```ini
[Unit]
Description=CCA Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/cca-student-onboarding
ExecStart=/usr/bin/php /var/www/cca-student-onboarding/artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start
sudo systemctl enable cca-queue
sudo systemctl start cca-queue
```

#### Cron Jobs

Add to crontab:
```bash
* * * * * cd /var/www/cca-student-onboarding && php artisan schedule:run >> /dev/null 2>&1
```

#### Monitoring

**Laravel Telescope (Development):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Log Monitoring:**
```bash
# Real-time logs
php artisan pail

# Or use tail
tail -f storage/logs/laravel.log
```

### Hosting Providers

**Recommended:**
- **DigitalOcean** - App Platform or Droplets
- **AWS** - Elastic Beanstalk or EC2
- **Cloudways** - Managed Laravel hosting
- **Laravel Forge** - Automated deployment
- **Ploi** - Server management
- **Heroku** - Quick deployment
- **Shared Hosting** - With SSH access

---

## 🔧 Troubleshooting

### Common Issues

#### File Upload Errors

**Problem:** Files fail to upload to R2
```
Solution:
1. Check R2 credentials in .env
2. Verify bucket CORS settings
3. Check file size limits in php.ini
4. Review storage/logs/laravel.log
```

#### reCAPTCHA Verification Fails

**Problem:** "Could not verify that you are human"
```
Solution:
1. Verify site key in .env matches domain
2. Check RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY
3. Ensure domain is registered in Google reCAPTCHA console
4. Check score threshold (default 0.5)
5. Clear cache: php artisan config:clear
```

#### Admin Can't Login

**Problem:** "These credentials do not match our records"
```
Solution:
1. Verify admin user exists: php artisan tinker, User::where('email', 'admin@cca.it')->first()
2. Check admin role: php artisan tinker, User::first()->hasRole('admin')
3. Reset password: php artisan tinker, User::first()->update(['password' => bcrypt('newpassword')])
4. Run seeders: php artisan db:seed --class=RoleSeeder && php artisan db:seed --class=AdminUserSeeder
```

#### Permission Denied Errors

**Problem:** "The stream or file could not be opened"
```
Solution:
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Development
chmod -R 777 storage bootstrap/cache
```

#### Database Connection Failed

**Problem:** "SQLSTATE[HY000] [1045] Access denied"
```
Solution:
1. Verify database credentials in .env
2. Test connection: php artisan tinker, DB::connection()->getPdo()
3. Check MySQL service: sudo systemctl status mysql
4. Create database: mysql -u root -p, CREATE DATABASE your_database;
5. Grant permissions: GRANT ALL PRIVILEGES ON your_database.* TO 'user'@'localhost';
```

#### 404 Not Found

**Problem:** Routes not working
```
Solution:
1. Check .htaccess exists in public/
2. Enable mod_rewrite: sudo a2enmod rewrite && sudo systemctl restart apache2
3. Clear route cache: php artisan route:clear && php artisan route:cache
4. Check DocumentRoot points to public/ directory
```

#### WhiteScreen / 500 Error

**Problem:** Application shows blank page
```
Solution:
1. Enable debug: Set APP_DEBUG=true in .env (temporarily)
2. Check logs: storage/logs/laravel.log
3. Clear all caches: php artisan optimize:clear
4. Check file permissions
5. Verify .env file exists and is valid
```

#### Slow Performance

**Problem:** Application loading slowly
```
Solution:
1. Enable caching: php artisan config:cache && php artisan route:cache && php artisan view:cache
2. Optimize autoloader: composer dump-autoload -o
3. Use queue for heavy tasks
4. Enable OPcache in php.ini
5. Use database indexes
6. Implement Redis/Memcached
```

### Debug Mode

**Enable Detailed Errors (Development Only):**
```env
APP_DEBUG=true
APP_ENV=local
LOG_LEVEL=debug
```

**Disable in Production:**
```env
APP_DEBUG=false
APP_ENV=production
LOG_LEVEL=error
```

### Getting Help

1. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   php artisan pail
   ```

2. **Clear All Caches:**
   ```bash
   php artisan optimize:clear
   ```

3. **Run Diagnostics:**
   ```bash
   php artisan about
   php artisan config:show database
   php artisan route:list
   ```

---

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### Getting Started

1. **Fork the Repository**
   ```bash
   git clone https://github.com/yourusername/cca-student-onboarding.git
   cd cca-student-onboarding
   git remote add upstream https://github.com/original/cca-student-onboarding.git
   ```

2. **Create Feature Branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Make Changes**
   - Follow PSR-12 coding standards
   - Write tests for new features
   - Update documentation
   - Commit with clear messages

4. **Test Your Changes**
   ```bash
   composer test
   php artisan test
   ```

5. **Submit Pull Request**
   - Push to your fork
   - Create PR with clear description
   - Reference any related issues

### Coding Standards

**PHP (PSR-12):**
```bash
composer pint
```

**JavaScript (Prettier):**
```bash
npm run format
```

**Commit Messages:**
```
feat: Add payment tracking feature
fix: Resolve file upload issue on Safari
docs: Update installation instructions
style: Format code with Pint
refactor: Improve registration controller
test: Add unit tests for RecaptchaService
chore: Update dependencies
```

### Pull Request Process

1. Update README.md with details of changes
2. Update CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/)
3. Increase version numbers following [Semantic Versioning](https://semver.org/)
4. PR will be merged after review by maintainers

### Code of Conduct

- Be respectful and inclusive
- Accept constructive criticism gracefully
- Focus on what's best for the community
- Show empathy towards others

---

## 📄 License

This project is licensed under the MIT License - see below for details:

```
MIT License

Copyright (c) 2025 Codezela Career Accelerator

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 💬 Support

### Documentation

- **Official Laravel Docs:** https://laravel.com/docs
- **Tailwind CSS Docs:** https://tailwindcss.com/docs
- **Alpine.js Docs:** https://alpinejs.dev/start-here

### Community

- **GitHub Issues:** Report bugs and request features
- **Email Support:** support@cca.it
- **Discord Community:** [Join our server](#)
- **Stack Overflow:** Tag questions with `cca-portal`

### Commercial Support

For enterprise support, custom development, and training:
- **Email:** enterprise@cca.it
- **Website:** https://cca.it/enterprise
- **Phone:** +94 XX XXX XXXX

### FAQ

**Q: Can I use this for my own institution?**
A: Yes! This is open-source under MIT license. Feel free to customize for your needs.

**Q: Is this production-ready?**
A: Yes! This application follows enterprise-level best practices and security standards.

**Q: Can I contribute?**
A: Absolutely! We welcome contributions. See [Contributing](#-contributing) section.

**Q: What's the difference between R2 and S3?**
A: R2 is Cloudflare's S3-compatible storage with zero egress fees. The app works with both.

**Q: How do I add more programs?**
A: Edit `config/programs.php` and add new entries. No code changes needed!

**Q: Can I use a different payment gateway?**
A: Yes! The payment slip is currently manual upload. You can integrate any gateway.

**Q: Is multi-language support available?**
A: Not yet, but Laravel's localization features make it easy to add.

**Q: Can I self-host without cloud services?**
A: Yes! Use local filesystem instead of R2, and remove reCAPTCHA if needed.

---

## 🌟 Acknowledgments

### Built With
- [Laravel](https://laravel.com/) - The PHP Framework For Web Artisans
- [Tailwind CSS](https://tailwindcss.com/) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev/) - Your new, lightweight, JavaScript framework
- [Three.js](https://threejs.org/) - JavaScript 3D Library
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/) - Associate users with roles and permissions

### Inspiration
- Laravel Breeze - Authentication scaffolding
- Modern web design trends
- Educational institution portals

### Contributors
- **Lead Developer:** Your Name
- **UI/UX Design:** Designer Name
- **QA Testing:** Tester Name

---

## 📊 Project Stats

![GitHub Stars](https://img.shields.io/github/stars/yourusername/cca-student-onboarding?style=social)
![GitHub Forks](https://img.shields.io/github/forks/yourusername/cca-student-onboarding?style=social)
![GitHub Issues](https://img.shields.io/github/issues/yourusername/cca-student-onboarding)
![GitHub License](https://img.shields.io/github/license/yourusername/cca-student-onboarding)

---

## 🗺 Roadmap

### Version 2.0 (Planned)
- [ ] Email notifications (registration confirmation, admin alerts)
- [ ] SMS notifications (Twilio integration)
- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Student portal (login, track application status)
- [ ] Batch management (assign students to batches)
- [ ] Certificate generation
- [ ] Multi-language support (Sinhala, Tamil)
- [ ] Advanced reporting (PDF exports, charts)
- [ ] API for mobile app
- [ ] Automated backup system

### Version 2.5 (Future)
- [ ] WhatsApp Business API integration
- [ ] Live chat support
- [ ] Video interview scheduling
- [ ] Learning Management System (LMS) integration
- [ ] Alumni network
- [ ] Job placement tracking
- [ ] Career counseling module

---

<div align="center">

### 🎉 Thank you for using CCA Student Onboarding Portal!

**Made with ❤️ by the Codezela Team**

[⬆ Back to Top](#-codezela-career-accelerator-cca---student-onboarding-portal)

</div>
