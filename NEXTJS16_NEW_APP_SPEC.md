# Student Onboarding Portal - Next.js 16 New Application Specification

> **Complete Technical Specification for Building a New Student Registration & Management System**
> 
> **Tech Stack:** Next.js 16 + React 19 + TypeScript + Tailwind CSS 4 + Prisma + NextAuth.js + PostgreSQL/MySQL

---

## 📋 Table of Contents

1. [Project Overview](#1-project-overview)
2. [System Architecture](#2-system-architecture)
3. [Database Design](#3-database-design)
4. [Prisma Schema](#4-prisma-schema)
5. [API Specification](#5-api-specification)
6. [Page Routes & Components](#6-page-routes--components)
7. [Authentication System](#7-authentication-system)
8. [Business Logic Services](#8-business-logic-services)
9. [File Upload Architecture](#9-file-upload-architecture)
10. [UI/UX Specifications](#10-uiux-specifications)
11. [Third-Party Integrations](#11-third-party-integrations)
12. [Security Requirements](#12-security-requirements)
13. [Environment Configuration](#13-environment-configuration)
14. [Implementation Roadmap](#14-implementation-roadmap)
15. [Appendices](#15-appendices)

---

## 1. Project Overview

### 1.1 Application Purpose
A comprehensive **Student Registration & Management System** for educational institutions or training programs. The system enables students to register for programs online with document uploads, while administrators can manage registrations, track payments, and monitor all activities through an audit trail.

### 1.2 Core Modules

| Module | Description |
|--------|-------------|
| **Public Portal** | Landing page, student registration form with document uploads |
| **Admin Dashboard** | Registration management, statistics, filtering, exports |
| **Payment Ledger** | Multi-payment tracking per registration with void capability |
| **Program Catalog** | Program management with intake windows and pricing |
| **Activity Audit** | Complete action logging for compliance |
| **Admin Accounts** | User management with soft delete protection |

### 1.3 Key Features

#### Student Features
- 📝 Multi-step registration form (Program, Personal, Contact, Qualification, Documents, Terms)
- 📁 Document uploads: Academic certificates, NIC/Passport, Photo, Payment slip
- 🔍 Real-time program validation with intake window checking
- 🤖 Google reCAPTCHA v3 protection
- 📱 Mobile-responsive design

#### Admin Features
- 📊 Dashboard with statistics (registrations, payment categories, top programs)
- 🔍 Advanced filtering (search, program, tags, scope)
- 📤 CSV export functionality
- 💰 Payment ledger (add, edit, void payments)
- 🗑️ Soft delete with trash recovery
- 📋 Activity timeline with CSV export
- 👥 Admin account management

### 1.4 Technology Stack

```
Frontend:
├── Next.js 16 (App Router)
├── React 19
├── TypeScript 5.x
├── Tailwind CSS 4.x
├── shadcn/ui components
├── React Hook Form + Zod
├── Framer Motion (animations)
├── React Three Fiber (3D graphics)
└── @react-pdf/renderer (PDF preview)

Backend:
├── Next.js API Routes
├── Server Actions
├── Prisma ORM
├── NextAuth.js 5
├── Zod validation
└── AWS SDK (R2 storage)

Database:
├── PostgreSQL 15+ or MySQL 8+
└── Redis (optional, for rate limiting)

Storage:
└── Cloudflare R2 (S3-compatible)

External Services:
├── Google reCAPTCHA v3
├── Google Analytics (production)
└── Sentry (error tracking, optional)
```

---

## 2. System Architecture

### 2.1 Project Structure

```
student-portal/
├── app/                              # Next.js App Router
│   ├── (public)/                     # Public route group
│   │   ├── page.tsx                  # Landing page
│   │   ├── layout.tsx                # Public layout
│   │   └── register/
│   │       ├── page.tsx              # Registration form
│   │       └── actions.ts            # Server actions
│   │
│   ├── (admin)/                      # Admin route group (protected)
│   │   ├── admin/
│   │   │   ├── page.tsx              # Dashboard
│   │   │   ├── layout.tsx            # Admin layout
│   │   │   ├── registrations/
│   │   │   │   ├── page.tsx          # List view
│   │   │   │   ├── [id]/
│   │   │   │   │   ├── page.tsx      # Detail view
│   │   │   │   │   └── edit/
│   │   │   │   │       └── page.tsx
│   │   │   │   └── actions.ts
│   │   │   │
│   │   │   ├── payments/
│   │   │   │   └── [registrationId]/
│   │   │   │       └── page.tsx
│   │   │   │
│   │   │   ├── programs/
│   │   │   │   ├── page.tsx
│   │   │   │   └── [id]/
│   │   │   │       └── page.tsx
│   │   │   │
│   │   │   ├── activity/
│   │   │   │   ├── page.tsx
│   │   │   │   └── [id]/
│   │   │   │       └── page.tsx
│   │   │   │
│   │   │   ├── accounts/
│   │   │   │   └── page.tsx
│   │   │   │
│   │   │   └── profile/
│   │   │       └── page.tsx
│   │
│   ├── api/                          # API Routes
│   │   ├── auth/[...nextauth]/
│   │   │   └── route.ts
│   │   ├── registrations/
│   │   │   └── route.ts
│   │   ├── programs/
│   │   │   └── route.ts
│   │   ├── uploads/
│   │   │   └── presigned/
│   │   │       └── route.ts
│   │   └── export/
│   │       └── route.ts
│   │
│   ├── layout.tsx                    # Root layout
│   └── globals.css                   # Global styles
│
├── components/                       # React Components
│   ├── ui/                          # shadcn/ui components
│   ├── forms/                       # Form-specific components
│   ├── admin/                       # Admin-specific components
│   ├── shared/                      # Shared components
│   └── three/                       # 3D components
│
├── hooks/                           # Custom React hooks
│   ├── use-file-upload.ts
│   ├── use-recaptcha.ts
│   └── use-program-validation.ts
│
├── lib/                             # Utilities & Services
│   ├── prisma.ts                    # Prisma client
│   ├── auth.ts                      # NextAuth config
│   ├── validations/                 # Zod schemas
│   │   ├── registration.ts
│   │   ├── program.ts
│   │   └── payment.ts
│   ├── services/
│   │   ├── program-catalog.ts
│   │   ├── payment-ledger.ts
│   │   ├── file-upload.ts
│   │   ├── recaptcha.ts
│   │   └── activity-logger.ts
│   └── utils.ts
│
├── types/                           # TypeScript types
│   └── index.ts
│
├── prisma/
│   └── schema.prisma                # Database schema
│
├── public/                          # Static assets
│   ├── images/
│   └── fonts/
│
└── next.config.js
```

### 2.2 Data Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                          CLIENT                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │  Public Form │  │ Admin Panel  │  │  Document Viewer     │  │
│  └──────┬───────┘  └──────┬───────┘  └──────────┬───────────┘  │
└─────────┼─────────────────┼─────────────────────┼──────────────┘
          │                 │                     │
          │  Server Actions │    Server Actions   │
          ▼                 ▼                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                        NEXT.JS SERVER                            │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │  API Routes  │  │    Auth      │  │  Business Logic      │  │
│  │              │  │  (NextAuth)  │  │      Services        │  │
│  └──────┬───────┘  └──────┬───────┘  └──────────┬───────────┘  │
└─────────┼─────────────────┼─────────────────────┼──────────────┘
          │                 │                     │
          ▼                 ▼                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATA LAYER                                  │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │   Prisma     │  │    R2/S3     │  │   Redis (opt)        │  │
│  │   ORM        │  │   Storage    │  │   Rate Limiting      │  │
│  └──────┬───────┘  └──────┬───────┘  └──────────┬───────────┘  │
└─────────┼─────────────────┼─────────────────────┼──────────────┘
          │                 │
          ▼                 ▼
┌─────────────────┐  ┌─────────────────┐
│   PostgreSQL    │  │  Cloudflare R2  │
│   or MySQL      │  │  (Documents)    │
└─────────────────┘  └─────────────────┘
```

---

## 3. Database Design

### 3.1 Entity Relationship Diagram

```
┌────────────────────────────────────────────────────────────────────┐
│                           ENTITY MODEL                             │
└────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐         ┌──────────────────┐         ┌──────────────┐
    │    User      │         │ CCARegistration  │         │   Program    │
    ├──────────────┤         ├──────────────────┤         ├──────────────┤
    │ id (PK)      │         │ id (PK)          │◄───────│ code (PK)    │
    │ name         │◄────────│ userId (FK, opt) │         │ name         │
    │ email (UQ)   │         │ programId (FK)   │         │ yearLabel    │
    │ password     │         │ registerId (UQ)  │         │ duration     │
    │ deletedAt    │         │ fullName         │         │ basePrice    │
    │ createdAt    │         │ emailAddress     │         │ isActive     │
    └──────────────┘         │ documents (JSON) │         └──────────────┘
            │                │ deletedAt        │                │
            │                └──────────────────┘                │
            │                         │                          │
            │                         │                          │
            │                ┌──────────────────┐                │
            │                │RegistrationPayment│               │
            │                ├──────────────────┤                │
            └───────────────►│ id (PK)          │                │
                             │ registrationId   │                │
                             │ paymentNo        │                │
                             │ amount           │                │
                             │ status           │        ┌───────┴──────┐
                             └──────────────────┘        │ProgramIntake │
                                                         │   Window     │
            ┌──────────────┐                             ├──────────────┤
            │ActivityLog   │                             │ id (PK)      │
            ├──────────────┤                             │ programId    │◄──┘
            │ id (PK)      │                             │ windowName   │
            │ actorUserId  │◄────────────────────────────│ opensAt      │
            │ action       │                             │ closesAt     │
            │ subjectType  │                             │ priceOverride│
            │ beforeData   │                             │ isActive     │
            └──────────────┘                             └──────────────┘
```

### 3.2 Table Specifications

#### users
Admin user accounts with soft delete support.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique identifier |
| name | String | Required | Full name |
| email | String | Unique, Required | Login email |
| password | String | Required | bcrypt hashed |
| emailVerifiedAt | DateTime | Nullable | Verification timestamp |
| rememberToken | String | Nullable | Remember me token |
| deletedAt | DateTime | Nullable | Soft delete timestamp |
| createdAt | DateTime | Default now | Creation timestamp |
| updatedAt | DateTime | Auto update | Last update timestamp |

#### cca_registrations
Main student registration records.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique identifier |
| registerId | String | Unique, Required | Formatted ID (e.g., cca-A00001) |
| programId | String | FK → programs | Program enrolled |
| programName | String | Required | Snapshot at registration |
| programYear | String | Required | Year label snapshot |
| programDuration | String | Required | Duration snapshot |
| fullName | String | Required | Student full name |
| nameWithInitials | String | Required | Name with initials |
| gender | Enum | male, female | Gender |
| dateOfBirth | Date | Required | Birth date |
| nicNumber | String | Nullable | Sri Lankan NIC |
| passportNumber | String | Nullable | Passport number |
| nationality | String | Required | Nationality |
| countryOfBirth | String | Required | Birth country |
| countryOfResidence | String | Required | Residence country |
| permanentAddress | String | Required | Full address |
| postalCode | String | Required | Postal/ZIP code |
| country | String | Required | Country |
| district | String | Nullable | District (SL only) |
| province | String | Nullable | Province (SL only) |
| emailAddress | String | Required | Contact email |
| whatsappNumber | String | Required | WhatsApp number |
| homeContactNumber | String | Nullable | Home phone |
| guardianContactName | String | Required | Guardian name |
| guardianContactNumber | String | Required | Guardian phone |
| highestQualification | Enum | See below | Education level |
| qualificationOtherDetails | String | Nullable | Other qualification details |
| qualificationStatus | Enum | completed, ongoing | Status |
| qualificationCompletedDate | Date | Nullable | Completion date |
| qualificationExpectedCompletionDate | Date | Nullable | Expected completion |
| academicQualificationDocuments | JSON | Required | Array of file objects |
| nicDocuments | JSON | Nullable | NIC file objects |
| passportDocuments | JSON | Nullable | Passport file objects |
| passportPhoto | JSON | Required | Photo file object |
| paymentSlip | JSON | Required | Payment proof |
| tags | JSON | Nullable | Array of tags |
| fullAmount | Decimal | Nullable | Total fee amount |
| currentPaidAmount | Decimal | Nullable | Calculated from payments |
| termsAccepted | Boolean | Default false | Terms agreement |
| deletedAt | DateTime | Nullable | Soft delete |
| createdAt | DateTime | Default now | Created |
| updatedAt | DateTime | Auto update | Updated |

#### programs
Program catalog with pricing and activation.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique ID |
| code | String | Unique, Required | Program code (e.g., CCA-PM25) |
| name | String | Required | Program name |
| yearLabel | String | Required | Year (e.g., 2025) |
| durationLabel | String | Required | Duration (e.g., 6 Months) |
| basePrice | Decimal | Default 0 | Base price in LKR |
| currency | String | Default LKR | Currency code |
| isActive | Boolean | Default true | Active status |
| displayOrder | Int | Default 0 | Sorting order |
| createdBy | BigInt | Nullable | FK → users |
| updatedBy | BigInt | Nullable | FK → users |
| createdAt | DateTime | Default now | Created |
| updatedAt | DateTime | Auto update | Updated |

#### program_intake_windows
Registration windows for programs.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique ID |
| programId | BigInt | FK → programs | Parent program |
| windowName | String | Required | Intake name |
| opensAt | DateTime | Required | Opening datetime |
| closesAt | DateTime | Required | Closing datetime |
| priceOverride | Decimal | Nullable | Special price |
| isActive | Boolean | Default true | Active status |
| createdBy | BigInt | Nullable | FK → users |
| updatedBy | BigInt | Nullable | FK → users |
| createdAt | DateTime | Default now | Created |
| updatedAt | DateTime | Auto update | Updated |

#### registration_payments
Payment ledger with audit trail.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique ID |
| ccaRegistrationId | BigInt | FK → registrations | Parent registration |
| paymentNo | Int | Required | Sequential number |
| paymentDate | Date | Required | Payment date |
| amount | Decimal | Required | Payment amount |
| paymentMethod | String | Required | Method used |
| receiptReference | String | Nullable | Receipt number |
| note | String | Nullable | Notes |
| status | Enum | active, void | Payment status |
| voidReason | String | Nullable | Void reason |
| voidedAt | DateTime | Nullable | Void timestamp |
| createdBy | BigInt | Nullable | FK → users |
| updatedBy | BigInt | Nullable | FK → users |
| createdAt | DateTime | Default now | Created |
| updatedAt | DateTime | Auto update | Updated |

#### admin_activity_logs
Complete audit trail.

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| id | BigInt | PK, Auto | Unique ID |
| actorUserId | BigInt | Nullable | FK → users |
| actorNameSnapshot | String | Nullable | Actor name at time |
| actorEmailSnapshot | String | Nullable | Actor email at time |
| category | String | Default general | Action category |
| action | String | Required | Action identifier |
| status | String | Default success | success/failed |
| subjectType | String | Nullable | Entity type |
| subjectId | BigInt | Nullable | Entity ID |
| subjectLabel | String | Nullable | Human-readable label |
| message | String | Nullable | Description |
| routeName | String | Nullable | Route |
| httpMethod | String | Nullable | HTTP method |
| ipAddress | String | Nullable | Client IP |
| userAgent | String | Nullable | Browser UA |
| requestId | String | Nullable | Trace ID |
| beforeData | JSON | Nullable | Previous state |
| afterData | JSON | Nullable | New state |
| meta | JSON | Nullable | Additional data |
| createdAt | DateTime | Default now | Created |

---

## 4. Prisma Schema

```prisma
// prisma/schema.prisma

generator client {
  provider = "prisma-client-js"
}

datasource db {
  provider = "postgresql" // or "mysql"
  url      = env("DATABASE_URL")
}

model User {
  id                BigInt    @id @default(autoincrement())
  name              String
  email             String    @unique
  emailVerifiedAt   DateTime? @map("email_verified_at")
  password          String
  rememberToken     String?   @map("remember_token")
  deletedAt         DateTime? @map("deleted_at")
  createdAt         DateTime  @default(now()) @map("created_at")
  updatedAt         DateTime  @updatedAt @map("updated_at")

  // Relations
  createdPrograms   Program[] @relation("ProgramCreatedBy")
  updatedPrograms   Program[] @relation("ProgramUpdatedBy")
  createdIntakes    ProgramIntakeWindow[] @relation("IntakeCreatedBy")
  updatedIntakes    ProgramIntakeWindow[] @relation("IntakeUpdatedBy")
  createdPayments   RegistrationPayment[] @relation("PaymentCreatedBy")
  updatedPayments   RegistrationPayment[] @relation("PaymentUpdatedBy")
  activities        AdminActivityLog[]

  @@map("users")
}

model CCARegistration {
  id                                 BigInt    @id @default(autoincrement())
  registerId                         String    @unique @map("register_id")
  programId                          String    @map("program_id")
  programName                        String    @map("program_name")
  programYear                        String    @map("program_year")
  programDuration                    String    @map("program_duration")
  fullName                           String    @map("full_name")
  nameWithInitials                   String    @map("name_with_initials")
  gender                             Gender
  dateOfBirth                        DateTime  @map("date_of_birth") @db.Date
  nicNumber                          String?   @map("nic_number")
  passportNumber                     String?   @map("passport_number")
  nationality                        String
  countryOfBirth                     String    @map("country_of_birth")
  countryOfResidence                 String    @map("country_of_residence")
  permanentAddress                   String    @map("permanent_address")
  postalCode                         String    @map("postal_code")
  country                            String
  district                           String?
  province                           String?
  emailAddress                       String    @map("email_address")
  whatsappNumber                     String    @map("whatsapp_number")
  homeContactNumber                  String?   @map("home_contact_number")
  guardianContactName                String    @map("guardian_contact_name")
  guardianContactNumber              String    @map("guardian_contact_number")
  highestQualification               Qualification @map("highest_qualification")
  qualificationOtherDetails          String?   @map("qualification_other_details")
  qualificationStatus                QualificationStatus @map("qualification_status")
  qualificationCompletedDate         DateTime? @map("qualification_completed_date") @db.Date
  qualificationExpectedCompletionDate DateTime? @map("qualification_expected_completion_date") @db.Date
  academicQualificationDocuments     Json      @map("academic_qualification_documents")
  nicDocuments                       Json?     @map("nic_documents")
  passportDocuments                  Json?     @map("passport_documents")
  passportPhoto                      Json      @map("passport_photo")
  paymentSlip                        Json      @map("payment_slip")
  tags                               Json?
  fullAmount                         Decimal?  @map("full_amount") @db.Decimal(12, 2)
  currentPaidAmount                  Decimal?  @map("current_paid_amount") @db.Decimal(12, 2)
  termsAccepted                      Boolean   @default(false) @map("terms_accepted")
  deletedAt                          DateTime? @map("deleted_at")
  createdAt                          DateTime  @default(now()) @map("created_at")
  updatedAt                          DateTime  @updatedAt @map("updated_at")

  // Relations
  payments                           RegistrationPayment[]
  program                            Program?  @relation(fields: [programId], references: [code])

  @@index([programId])
  @@index([emailAddress])
  @@index([nicNumber])
  @@index([programId, nicNumber])
  @@index([programId, passportNumber])
  @@map("cca_registrations")
}

model Program {
  id               BigInt    @id @default(autoincrement())
  code             String    @unique
  name             String
  yearLabel        String    @map("year_label")
  durationLabel    String    @map("duration_label")
  basePrice        Decimal   @default(0) @map("base_price") @db.Decimal(12, 2)
  currency         String    @default("LKR")
  isActive         Boolean   @default(true) @map("is_active")
  displayOrder     Int       @default(0) @map("display_order")
  createdBy        BigInt?   @map("created_by")
  updatedBy        BigInt?   @map("updated_by")
  createdAt        DateTime  @default(now()) @map("created_at")
  updatedAt        DateTime  @updatedAt @map("updated_at")

  // Relations
  intakeWindows    ProgramIntakeWindow[]
  registrations    CCARegistration[]
  creator          User?     @relation("ProgramCreatedBy", fields: [createdBy], references: [id])
  updater          User?     @relation("ProgramUpdatedBy", fields: [updatedBy], references: [id])

  @@index([isActive, displayOrder])
  @@map("programs")
}

model ProgramIntakeWindow {
  id             BigInt    @id @default(autoincrement())
  programId      BigInt    @map("program_id")
  windowName     String    @map("window_name")
  opensAt        DateTime  @map("opens_at")
  closesAt       DateTime  @map("closes_at")
  priceOverride  Decimal?  @map("price_override") @db.Decimal(12, 2)
  isActive       Boolean   @default(true) @map("is_active")
  createdBy      BigInt?   @map("created_by")
  updatedBy      BigInt?   @map("updated_by")
  createdAt      DateTime  @default(now()) @map("created_at")
  updatedAt      DateTime  @updatedAt @map("updated_at")

  // Relations
  program        Program   @relation(fields: [programId], references: [id], onDelete: Cascade)
  creator        User?     @relation("IntakeCreatedBy", fields: [createdBy], references: [id])
  updater        User?     @relation("IntakeUpdatedBy", fields: [updatedBy], references: [id])

  @@index([programId, isActive])
  @@index([opensAt, closesAt])
  @@map("program_intake_windows")
}

model RegistrationPayment {
  id                BigInt          @id @default(autoincrement())
  ccaRegistrationId BigInt          @map("cca_registration_id")
  paymentNo         Int             @map("payment_no")
  paymentDate       DateTime        @map("payment_date") @db.Date
  amount            Decimal         @db.Decimal(12, 2)
  paymentMethod     String          @map("payment_method")
  receiptReference  String?         @map("receipt_reference")
  note              String?
  status            PaymentStatus   @default(active)
  voidReason        String?         @map("void_reason")
  voidedAt          DateTime?       @map("voided_at")
  createdBy         BigInt?         @map("created_by")
  updatedBy         BigInt?         @map("updated_by")
  createdAt         DateTime        @default(now()) @map("created_at")
  updatedAt         DateTime        @updatedAt @map("updated_at")

  // Relations
  registration      CCARegistration @relation(fields: [ccaRegistrationId], references: [id], onDelete: Cascade)
  creator           User?           @relation("PaymentCreatedBy", fields: [createdBy], references: [id])
  updater           User?           @relation("PaymentUpdatedBy", fields: [updatedBy], references: [id])

  @@unique([ccaRegistrationId, paymentNo])
  @@index([ccaRegistrationId, status])
  @@index([paymentDate])
  @@map("registration_payments")
}

model AdminActivityLog {
  id                BigInt    @id @default(autoincrement())
  actorUserId       BigInt?   @map("actor_user_id")
  actorNameSnapshot String?   @map("actor_name_snapshot")
  actorEmailSnapshot String?  @map("actor_email_snapshot")
  category          String    @default("general")
  action            String
  status            String    @default("success")
  subjectType       String?   @map("subject_type")
  subjectId         BigInt?   @map("subject_id")
  subjectLabel      String?   @map("subject_label")
  message           String?
  routeName         String?   @map("route_name")
  httpMethod        String?   @map("http_method")
  ipAddress         String?   @map("ip_address")
  userAgent         String?   @map("user_agent")
  requestId         String?   @map("request_id")
  beforeData        Json?     @map("before_data")
  afterData         Json?     @map("after_data")
  meta              Json?
  createdAt         DateTime  @default(now()) @map("created_at")

  // Relations
  actor             User?     @relation(fields: [actorUserId], references: [id])

  @@index([createdAt])
  @@index([actorUserId])
  @@index([category])
  @@index([action])
  @@index([status])
  @@index([subjectType, subjectId])
  @@index([requestId])
  @@map("admin_activity_logs")
}

// Enums
enum Gender {
  male
  female
}

enum Qualification {
  degree
  diploma
  postgraduate
  msc
  phd
  work_experience
  other
}

enum QualificationStatus {
  completed
  ongoing
}

enum PaymentStatus {
  active
  void
}
```

---

## 5. API Specification

### 5.1 Public Endpoints

#### POST `/api/registrations`
Create new student registration.

**Request Body:**
```typescript
{
  program_id: string;           // e.g., "CCA-PM25"
  full_name: string;
  name_with_initials: string;
  gender: "male" | "female";
  date_of_birth: string;        // YYYY-MM-DD
  nic_number?: string;
  passport_number?: string;
  nationality: string;
  country_of_birth: string;
  country_of_residence: string;
  permanent_address: string;
  postal_code: string;
  country: string;
  district?: string;
  province?: string;
  email_address: string;
  whatsapp_number: string;
  home_contact_number?: string;
  guardian_contact_name: string;
  guardian_contact_number: string;
  highest_qualification: Qualification;
  qualification_other_details?: string;
  qualification_status: QualificationStatus;
  qualification_completed_date?: string;
  qualification_expected_completion_date?: string;
  academic_documents: FileData[];    // Uploaded file refs
  nic_documents?: FileData[];
  passport_documents?: FileData[];
  passport_photo: FileData;
  payment_slip: FileData;
  terms_accepted: boolean;
  recaptcha_token: string;
}
```

**Response 201:**
```typescript
{
  success: true;
  message: "Registration successful";
  data: {
    id: string;
    register_id: string;
    program_name: string;
  };
}
```

#### GET `/api/programs/catalog`
Get active programs available for registration.

**Query:** `?at=2026-02-20T00:00:00Z` (optional, defaults to now)

**Response 200:**
```typescript
{
  [programCode: string]: {
    name: string;
    year: string;
    duration: string;
    active: boolean;
    price: number;
    currency: string;
  }
}
```

#### POST `/api/uploads/presigned`
Get presigned URL for direct-to-R2 file upload.

**Request:**
```typescript
{
  filename: string;
  contentType: string;
  directory: "registrations/academic" | "registrations/identification" | "registrations/photos" | "registrations/payments";
}
```

**Response:**
```typescript
{
  presignedUrl: string;    // PUT URL valid for 5 minutes
  key: string;             // Storage path
  publicUrl: string;       // Public access URL
}
```

### 5.2 Admin Endpoints (Require Authentication)

#### GET `/api/admin/registrations`
List registrations with filtering.

**Query Parameters:**
- `scope`: "active" | "trashed" | "all" (default: active)
- `search`: string (searches register_id, full_name, email, nic, whatsapp)
- `program_filter`: string (program code)
- `tag_filter`: string
- `page`: number (default: 1)
- `per_page`: number (default: 25, max: 100)

**Response:**
```typescript
{
  data: Registration[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    total_active: number;
    total_trashed: number;
    general_rate_count: number;
    special_offer_count: number;
  };
}
```

#### GET `/api/admin/registrations/:id`
Get single registration details.

**Response:** Full registration object with payments array.

#### PUT `/api/admin/registrations/:id`
Update registration.

**Body:** Partial registration data (program_id, full_name, email, etc.)

#### DELETE `/api/admin/registrations/:id`
Soft delete registration.

#### PATCH `/api/admin/registrations/:id/restore`
Restore soft-deleted registration.

#### DELETE `/api/admin/registrations/:id/permanent`
Permanently delete and remove files.

#### GET `/api/admin/registrations/export`
Export CSV of filtered registrations.

**Query:** Same filter params as list endpoint.
**Response:** CSV file download.

### 5.3 Payment Endpoints

#### GET `/api/admin/registrations/:id/payments`
Get payment ledger.

**Response:**
```typescript
{
  registration: {
    id: string;
    register_id: string;
    full_amount: number | null;
    current_paid_amount: number | null;
  };
  payments: Payment[];
  summary: {
    full_amount: number;
    paid_total: number;
    balance: number;
    status: "unpaid" | "partial" | "paid" | "overpaid";
  };
}
```

#### POST `/api/admin/registrations/:id/payments`
Add payment.

**Body:**
```typescript
{
  payment_date: string;      // YYYY-MM-DD
  amount: number;
  payment_method: "bank_transfer" | "cash" | "card" | "online_gateway" | "cheque" | "other";
  receipt_reference?: string;
  note?: string;
}
```

#### PUT `/api/admin/registrations/:registrationId/payments/:paymentId`
Update payment (only if status is "active").

#### PATCH `/api/admin/registrations/:registrationId/payments/:paymentId/void`
Void a payment.

**Body:** `{ void_reason: string }`

### 5.4 Program Management

#### GET `/api/admin/programs`
List all programs with intake windows.

#### POST `/api/admin/programs`
Create program.

**Body:**
```typescript
{
  code: string;              // Format: XXX-XXX99
  name: string;
  year_label: string;
  duration_label: string;
  base_price?: number;
  currency?: string;
  display_order?: number;
  is_active?: boolean;
}
```

#### PUT `/api/admin/programs/:id`
Update program.

#### PATCH `/api/admin/programs/:id/toggle`
Toggle active status.

#### POST `/api/admin/programs/:id/intakes`
Create intake window.

**Body:**
```typescript
{
  window_name: string;
  opens_at: string;          // ISO datetime
  closes_at: string;
  price_override?: number;
  is_active?: boolean;
}
```

#### PUT `/api/admin/programs/:programId/intakes/:intakeId`
Update intake window.

#### PATCH `/api/admin/programs/:programId/intakes/:intakeId/toggle`
Toggle intake status.

### 5.5 Activity & Accounts

#### GET `/api/admin/activity`
List activity logs with filters.

**Query:** date range, actor, category, action, status, search

#### GET `/api/admin/activity/export`
Export activity to CSV.

#### GET `/api/admin/accounts`
List admin accounts (active and deleted).

#### POST `/api/admin/accounts`
Create admin account.

**Body:** `{ name, email, password, password_confirmation }`

#### DELETE `/api/admin/accounts/:id`
Deactivate account (prevents deleting last admin).

#### PATCH `/api/admin/accounts/:id/restore`
Restore deactivated account.

---

## 6. Page Routes & Components

### 6.1 Public Pages

#### `/` - Landing Page
**Sections:**
1. Navigation with Register CTA
2. Hero with 3D animated background (Three.js)
3. Stats cards (Students enrolled, Success rate, Support)
4. Feature highlights
5. Contact information
6. Social links
7. Footer

**Key Components:**
- `ThreeBackground` - Animated 3D geometric shapes
- `StatsCard` - Animated counters
- `FeatureCard` - Glassmorphism cards
- `SocialLinks` - Social media icons

---

#### `/register` - Registration Form
**6 Sections with progress indicator:**

1. **Program Information**
   - Program ID input with real-time validation
   - Program details display (name, year, duration, price)
   - "Registration Closed" warning if not open

2. **Personal Information**
   - Full name, Name with initials
   - Gender select
   - Date of birth
   - NIC number (conditional)
   - Passport number (conditional)
   - Nationality, Country of birth/residence

3. **Contact Information**
   - Permanent address
   - Country select
   - Province/District (conditional for Sri Lanka)
   - Postal code
   - Email, WhatsApp, Home contact
   - Guardian name and contact

4. **Qualification Information**
   - Highest qualification select
   - Other details (conditional)
   - Status (completed/ongoing)
   - Completion/Expected dates (conditional)

5. **Documents**
   - Academic documents (1-2 files)
   - NIC documents (conditional, 2 files)
   - Passport documents (conditional, 2 files)
   - Passport photo (1 file)
   - Payment slip (1 file)
   - Drag & drop upload with preview

6. **Terms & Submit**
   - Terms checkbox
   - Submit button with loading state
   - Upload progress modal

**Key Components:**
- `RegistrationForm` - Main form with React Hook Form
- `ProgramValidator` - Real-time program checking
- `FileUpload` - Drag & drop with progress
- `SriLankaLocation` - Province/District cascade
- `UploadProgressModal` - Progress indicator

---

### 6.2 Admin Pages

#### `/admin/login` - Admin Login
- Email/password form
- Remember me checkbox
- Rate limiting

**Components:** `LoginForm`

---

#### `/admin` - Dashboard
**Layout:** Sidebar navigation + Main content

**Content:**
1. **Stats Row**
   - Active registrations count
   - General Rate count (click to filter)
   - Special Offer count (click to filter)
   - Top program by registrations

2. **Scope Tabs**
   - Active / Trash / All

3. **Filters Panel**
   - Search input
   - Program dropdown
   - Apply/Clear buttons
   - Export CSV button

4. **Registrations Table**
   - Columns: Register ID, Program, Name, NIC/Passport, Email, WhatsApp, Payment Slip, Actions
   - Pagination
   - Row actions: View, Edit, Delete/Restore

**Components:**
- `StatsCards` - 4 stat cards
- `RegistrationFilters` - Filter form
- `RegistrationsTable` - Data table with pagination
- `ScopeTabs` - Active/Trash/All tabs

---

#### `/admin/registrations/[id]` - Registration Detail
**3-Column Layout:**

**Column 1 (Main):**
1. Payment Information Card
   - Tags badges
   - Full amount / Paid amount / Balance
   - Manage Ledger button

2. Program Information Card

3. Personal Information Card

4. Contact Information Card

5. Guardian Information Card

6. Qualification Information Card

7. Documents Section with "View All" button

**Column 2 & 3:** Additional info or empty for spacing

**Components:**
- `PaymentSummaryCard`
- `InfoCard` - Reusable info display
- `DocumentGallery`
- `DocumentViewerModal` - Full document viewer

---

#### `/admin/registrations/[id]/edit` - Edit Registration
Form with:
- Program selector
- Editable fields (name, email, contact, etc.)
- Tags multi-select
- Full amount input

---

#### `/admin/registrations/[id]/payments` - Payment Ledger
**Content:**
1. Summary card (Full/Paid/Balance/Status)
2. Add Payment form
3. Payments table with Edit/Void actions

**Components:**
- `PaymentSummary`
- `PaymentForm`
- `PaymentsTable`
- `VoidPaymentModal`

---

#### `/admin/programs` - Program List
**Content:**
- Create Program button
- Program cards grid showing:
  - Code, name, year, duration
  - Status badge
  - Price
  - Intake windows list
  - Actions

---

#### `/admin/programs/[id]` - Program Edit
**Content:**
1. Program edit form
2. Intake windows list
3. Add intake form

**Components:**
- `ProgramForm`
- `IntakeWindowsList`
- `IntakeForm`

---

#### `/admin/activity` - Activity Timeline
**Content:**
- Filters (date range, actor, category, action, status, search)
- Activity table
- Export CSV button

**Components:**
- `ActivityFilters`
- `ActivityTable`

---

#### `/admin/accounts` - Admin Accounts
**Content:**
- Create account form
- Active admins table
- Deactivated admins table

**Components:**
- `AdminAccountForm`
- `AccountsTable`

---

## 7. Authentication System

### 7.1 NextAuth.js Configuration

```typescript
// lib/auth.ts
import NextAuth from "next-auth";
import Credentials from "next-auth/providers/credentials";
import { PrismaAdapter } from "@auth/prisma-adapter";
import { prisma } from "./prisma";
import bcrypt from "bcryptjs";

export const { handlers, signIn, signOut, auth } = NextAuth({
  adapter: PrismaAdapter(prisma),
  session: { strategy: "jwt" },
  pages: {
    signIn: "/admin/login",
    error: "/admin/login",
  },
  providers: [
    Credentials({
      name: "credentials",
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" },
      },
      async authorize(credentials) {
        if (!credentials?.email || !credentials?.password) return null;

        const user = await prisma.user.findUnique({
          where: { email: credentials.email },
        });

        if (!user || user.deletedAt) return null;

        const isValid = await bcrypt.compare(
          credentials.password,
          user.password
        );

        if (!isValid) return null;

        return {
          id: user.id.toString(),
          email: user.email,
          name: user.name,
        };
      },
    }),
  ],
  callbacks: {
    async jwt({ token, user }) {
      if (user) token.id = user.id;
      return token;
    },
    async session({ session, token }) {
      if (token) session.user.id = token.id;
      return session;
    },
  },
});
```

### 7.2 Route Protection

```typescript
// middleware.ts
import { auth } from "@/lib/auth";
import { NextResponse } from "next/server";

export default auth((req) => {
  const { nextUrl } = req;
  const isLoggedIn = !!req.auth;
  const isAdminRoute = nextUrl.pathname.startsWith("/admin");
  const isLoginPage = nextUrl.pathname === "/admin/login";

  if (isAdminRoute && !isLoggedIn && !isLoginPage) {
    return NextResponse.redirect(new URL("/admin/login", nextUrl));
  }

  if (isLoginPage && isLoggedIn) {
    return NextResponse.redirect(new URL("/admin", nextUrl));
  }

  return NextResponse.next();
});

export const config = {
  matcher: ["/admin/:path*"],
};
```

### 7.3 Server Action Auth Helper

```typescript
// lib/auth-guard.ts
import { auth } from "@/lib/auth";
import { redirect } from "next/navigation";

export async function requireAuth() {
  const session = await auth();
  if (!session?.user) redirect("/admin/login");
  return session;
}

export async function getCurrentUser() {
  const session = await auth();
  return session?.user;
}
```

---

## 8. Business Logic Services

### 8.1 Program Catalog Service

```typescript
// lib/services/program-catalog.ts
import { prisma } from "@/lib/prisma";

export class ProgramCatalogService {
  /**
   * Get programs available for registration
   */
  async getCatalog(at: Date = new Date()) {
    const programs = await prisma.program.findMany({
      include: {
        intakeWindows: {
          where: {
            isActive: true,
            opensAt: { lte: at },
            closesAt: { gte: at },
          },
          orderBy: { opensAt: "asc" },
        },
      },
      orderBy: [{ displayOrder: "asc" }, { code: "asc" }],
    });

    return programs.reduce((catalog, program) => {
      const window = program.intakeWindows[0];
      catalog[program.code] = {
        name: program.name,
        year: program.yearLabel,
        duration: program.durationLabel,
        active: program.isActive && !!window,
        price: Number(window?.priceOverride ?? program.basePrice),
        currency: program.currency,
      };
      return catalog;
    }, {} as Record<string, ProgramCatalogItem>);
  }

  /**
   * Check if program is open for registration
   */
  async isOpen(code: string, at: Date = new Date()): Promise<boolean> {
    const program = await prisma.program.findUnique({
      where: { code },
      include: {
        intakeWindows: {
          where: {
            isActive: true,
            opensAt: { lte: at },
            closesAt: { gte: at },
          },
        },
      },
    });

    return program?.isActive === true && program.intakeWindows.length > 0;
  }

  /**
   * Check for overlapping intake windows
   */
  async checkOverlap(
    programId: bigint,
    opensAt: Date,
    closesAt: Date,
    excludeId?: bigint
  ): Promise<boolean> {
    const overlapping = await prisma.programIntakeWindow.findFirst({
      where: {
        programId,
        isActive: true,
        id: excludeId ? { not: excludeId } : undefined,
        AND: [{ opensAt: { lt: closesAt } }, { closesAt: { gt: opensAt } }],
      },
    });
    return !!overlapping;
  }
}

export const programCatalog = new ProgramCatalogService();
```

### 8.2 Payment Ledger Service

```typescript
// lib/services/payment-ledger.ts
import { prisma } from "@/lib/prisma";
import { PaymentStatus } from "@prisma/client";

export class PaymentLedgerService {
  /**
   * Calculate total paid from active payments
   */
  async calculatePaid(registrationId: bigint): Promise<number> {
    const result = await prisma.registrationPayment.aggregate({
      where: {
        ccaRegistrationId: registrationId,
        status: PaymentStatus.active,
      },
      _sum: { amount: true },
    });
    return Number(result._sum.amount ?? 0);
  }

  /**
   * Sync current_paid_amount from payments
   */
  async sync(registrationId: bigint): Promise<void> {
    const paid = await this.calculatePaid(registrationId);
    await prisma.cCARegistration.update({
      where: { id: registrationId },
      data: { currentPaidAmount: paid },
    });
  }

  /**
   * Get payment summary
   */
  async getSummary(
    registrationId: bigint,
    fullAmount?: number | null
  ): Promise<PaymentSummary> {
    const paid = await this.calculatePaid(registrationId);
    const full = fullAmount ?? 0;
    const balance = full - paid;

    let status: PaymentSummary["status"] = "unpaid";
    if (full <= 0) {
      status = paid > 0 ? "paid" : "unpaid";
    } else if (paid <= 0) {
      status = "unpaid";
    } else if (paid < full) {
      status = "partial";
    } else if (paid === full) {
      status = "paid";
    } else {
      status = "overpaid";
    }

    return { fullAmount: full, paidTotal: paid, balance, status };
  }

  /**
   * Get next payment number
   */
  async getNextNumber(registrationId: bigint): Promise<number> {
    const result = await prisma.registrationPayment.aggregate({
      where: { ccaRegistrationId: registrationId },
      _max: { paymentNo: true },
    });
    return (result._max.paymentNo ?? 0) + 1;
  }
}

export const paymentLedger = new PaymentLedgerService();
```

### 8.3 File Upload Service

```typescript
// lib/services/file-upload.ts
import { S3Client, PutObjectCommand, DeleteObjectCommand } from "@aws-sdk/client-s3";
import { getSignedUrl } from "@aws-sdk/s3-request-presigner";
import { v4 as uuidv4 } from "uuid";
import slugify from "slugify";

export class FileUploadService {
  private client: S3Client;
  private bucket: string;
  private publicUrl: string;

  constructor() {
    this.client = new S3Client({
      region: "auto",
      endpoint: process.env.R2_ENDPOINT,
      credentials: {
        accessKeyId: process.env.R2_ACCESS_KEY_ID!,
        secretAccessKey: process.env.R2_SECRET_ACCESS_KEY!,
      },
    });
    this.bucket = process.env.R2_BUCKET_NAME!;
    this.publicUrl = process.env.R2_PUBLIC_URL!;
  }

  /**
   * Generate presigned URL for upload
   */
  async getPresignedUrl(
    filename: string,
    contentType: string,
    directory: string
  ): Promise<{ url: string; key: string; publicUrl: string }> {
    const ext = filename.split(".").pop();
    const base = slugify(filename.substring(0, 50), { lower: true });
    const key = `${directory}/${base}_${Date.now()}_${uuidv4().slice(0, 8)}.${ext}`;

    const command = new PutObjectCommand({
      Bucket: this.bucket,
      Key: key,
      ContentType: contentType,
    });

    const url = await getSignedUrl(this.client, command, { expiresIn: 300 });

    return {
      url,
      key,
      publicUrl: `${this.publicUrl}/${key}`,
    };
  }

  /**
   * Delete file from storage
   */
  async delete(key: string): Promise<void> {
    await this.client.send(
      new DeleteObjectCommand({ Bucket: this.bucket, Key: key })
    );
  }

  /**
   * Delete multiple files
   */
  async deleteMany(keys: string[]): Promise<void> {
    await Promise.all(keys.map((k) => this.delete(k)));
  }
}

export const fileUpload = new FileUploadService();
```

### 8.4 Activity Logger

```typescript
// lib/services/activity-logger.ts
import { prisma } from "@/lib/prisma";
import { headers } from "next/headers";
import { v4 as uuidv4 } from "uuid";

interface LogOptions {
  actor?: { id: bigint; name: string; email: string };
  category?: string;
  status?: "success" | "failed";
  subject?: { type: string; id: bigint; label?: string };
  message?: string;
  before?: Record<string, unknown>;
  after?: Record<string, unknown>;
  meta?: Record<string, unknown>;
}

export class ActivityLogger {
  async log(action: string, options: LogOptions = {}): Promise<void> {
    try {
      const headersList = await headers();
      
      await prisma.adminActivityLog.create({
        data: {
          actorUserId: options.actor?.id,
          actorNameSnapshot: options.actor?.name,
          actorEmailSnapshot: options.actor?.email,
          category: options.category || "general",
          action,
          status: options.status || "success",
          subjectType: options.subject?.type,
          subjectId: options.subject?.id,
          subjectLabel: options.subject?.label,
          message: options.message,
          ipAddress: headersList.get("x-forwarded-for") || undefined,
          userAgent: headersList.get("user-agent") || undefined,
          requestId: uuidv4(),
          beforeData: options.before,
          afterData: options.after,
          meta: options.meta,
        },
      });
    } catch (err) {
      console.error("Activity log failed:", err);
    }
  }
}

export const logger = new ActivityLogger();
```

### 8.5 reCAPTCHA Service

```typescript
// lib/services/recaptcha.ts
interface RecaptchaResult {
  success: boolean;
  score: number | null;
  errors: string[];
}

export class RecaptchaService {
  async verify(token: string): Promise<RecaptchaResult> {
    const secret = process.env.RECAPTCHA_SECRET_KEY;
    if (!token || !secret) {
      return { success: false, score: null, errors: ["missing"] };
    }

    const res = await fetch(
      "https://www.google.com/recaptcha/api/siteverify",
      {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ secret, response: token }),
      }
    );

    if (!res.ok) {
      return { success: false, score: null, errors: ["request-failed"] };
    }

    const data = await res.json();
    const threshold = parseFloat(process.env.RECAPTCHA_MINIMUM_SCORE || "0.5");

    return {
      success: data.success && (data.score ?? 0) >= threshold,
      score: data.score ?? null,
      errors: data["error-codes"] || [],
    };
  }
}

export const recaptcha = new RecaptchaService();
```

---

## 9. File Upload Architecture

### 9.1 Upload Flow

```
1. Client selects file
2. Client requests presigned URL from /api/uploads/presigned
3. Server validates and generates presigned R2 URL
4. Client uploads directly to R2 using presigned URL
5. On success, client stores file reference
6. On form submit, file references sent with registration data
```

### 9.2 Client Upload Hook

```typescript
// hooks/use-file-upload.ts
import { useState, useCallback } from "react";

interface FileUploadResult {
  path: string;
  url: string;
  size: number;
  originalName: string;
  mimeType: string;
}

export function useFileUpload() {
  const [progress, setProgress] = useState<number>(0);
  const [isUploading, setIsUploading] = useState(false);

  const upload = useCallback(
    async (file: File, directory: string): Promise<FileUploadResult> => {
      setIsUploading(true);
      setProgress(0);

      try {
        // 1. Get presigned URL
        const presignedRes = await fetch("/api/uploads/presigned", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            filename: file.name,
            contentType: file.type,
            directory,
          }),
        });

        if (!presignedRes.ok) throw new Error("Failed to get upload URL");
        const { presignedUrl, key, publicUrl } = await presignedRes.json();

        // 2. Upload to R2 with progress
        const xhr = new XMLHttpRequest();
        
        await new Promise<void>((resolve, reject) => {
          xhr.upload.addEventListener("progress", (e) => {
            if (e.lengthComputable) {
              setProgress(Math.round((e.loaded / e.total) * 100));
            }
          });

          xhr.addEventListener("load", () => {
            xhr.status === 200 ? resolve() : reject(new Error("Upload failed"));
          });

          xhr.addEventListener("error", () => reject(new Error("Upload error")));
          xhr.open("PUT", presignedUrl);
          xhr.setRequestHeader("Content-Type", file.type);
          xhr.send(file);
        });

        return {
          path: key,
          url: publicUrl,
          size: file.size,
          originalName: file.name,
          mimeType: file.type,
        };
      } finally {
        setIsUploading(false);
      }
    },
    []
  );

  return { upload, progress, isUploading };
}
```

---

## 10. UI/UX Specifications

### 10.1 Design System

**Color Palette:**
```css
/* Primary: Violet */
--primary-50: #f5f3ff;
--primary-100: #ede9fe;
--primary-500: #8b5cf6;
--primary-600: #7c3aed;
--primary-700: #6d28d9;

/* Secondary: Purple */
--secondary-500: #a855f7;
--secondary-600: #9333ea;

/* Semantic */
--success: #22c55e;
--warning: #f59e0b;
--error: #ef4444;
--info: #3b82f6;
```

**Typography:**
- Primary: Inter (Google Fonts)
- Fallback: system-ui, sans-serif

**Spacing Scale:**
- xs: 0.25rem (4px)
- sm: 0.5rem (8px)
- md: 1rem (16px)
- lg: 1.5rem (24px)
- xl: 2rem (32px)
- 2xl: 3rem (48px)

### 10.2 Component Patterns

**Glass Card:**
```tsx
<div className="bg-white/60 backdrop-blur-xl border border-white/60 rounded-2xl shadow-lg">
  {children}
</div>
```

**Gradient Button:**
```tsx
<button className="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-secondary-600 transition-all shadow-lg hover:shadow-xl">
  {label}
</button>
```

**Form Input:**
```tsx
<input className="w-full px-4 py-3 bg-white/50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" />
```

### 10.3 Responsive Breakpoints

- **Mobile:** < 640px
- **Tablet:** 640px - 1024px
- **Desktop:** > 1024px
- **Large:** > 1280px

### 10.4 Animation Standards

- **Duration:** 200-300ms
- **Easing:** ease-out (enter), ease-in (exit)
- **Hover scale:** 1.02
- **Page transitions:** Fade + slide

---

## 11. Third-Party Integrations

### 11.1 Google reCAPTCHA v3

**Setup:**
```env
NEXT_PUBLIC_RECAPTCHA_SITE_KEY=your-site-key
RECAPTCHA_SECRET_KEY=your-secret-key
RECAPTCHA_MINIMUM_SCORE=0.5
```

**Usage:**
```tsx
// Load script in layout
<Script src={`https://www.google.com/recaptcha/api.js?render=${siteKey}`} />

// Execute before form submit
const token = await window.grecaptcha.execute(siteKey, { action: "registration" });
```

### 11.2 Cloudflare R2

**Configuration:**
```env
R2_ENDPOINT=https://<account>.r2.cloudflarestorage.com
R2_ACCESS_KEY_ID=...
R2_SECRET_ACCESS_KEY=...
R2_BUCKET_NAME=documents
R2_PUBLIC_URL=https://pub-<hash>.r2.dev
```

### 11.3 Google Analytics

```tsx
// app/layout.tsx (production only)
{process.env.NODE_ENV === "production" && (
  <>
    <Script src={`https://www.googletagmanager.com/gtag/js?id=${GA_ID}`} />
    <Script id="ga">
      {`window.dataLayer=window.dataLayer||[];
        function gtag(){dataLayer.push(arguments);}
        gtag('js',new Date());
        gtag('config','${GA_ID}');`}
    </Script>
  </>
)}
```

---

## 12. Security Requirements

### 12.1 Input Validation

All inputs validated with Zod:

```typescript
import { z } from "zod";

export const registrationSchema = z.object({
  program_id: z.string().min(1),
  full_name: z.string().min(1),
  email_address: z.string().email(),
  // ... all fields
  terms_accepted: z.literal(true),
  recaptcha_token: z.string().min(1),
});
```

### 12.2 Rate Limiting

```typescript
// lib/rate-limit.ts
import { LRUCache } from "lru-cache";

const cache = new LRUCache({ max: 500, ttl: 60000 });

export function rateLimit(token: string, limit: number = 5): boolean {
  const count = (cache.get(token) as number) || 0;
  if (count >= limit) return false;
  cache.set(token, count + 1);
  return true;
}
```

### 12.3 Security Headers

```javascript
// next.config.js
module.exports = {
  async headers() {
    return [{
      source: "/:path*",
      headers: [
        { key: "X-Frame-Options", value: "DENY" },
        { key: "X-Content-Type-Options", value: "nosniff" },
        { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
      ],
    }];
  },
};
```

### 12.4 File Upload Security

- Max size: 10MB
- Allowed types: image/*, application/pdf, application/msword
- File extension whitelist
- Virus scanning (optional)

---

## 13. Environment Configuration

### 13.1 Required Variables

```bash
# App
NEXT_PUBLIC_APP_URL=http://localhost:3000
NODE_ENV=development

# Database
DATABASE_URL="postgresql://user:pass@localhost:5432/portal"

# Auth
NEXTAUTH_URL=http://localhost:3000
NEXTAUTH_SECRET=your-secret-min-32-characters

# Storage
R2_ENDPOINT=https://<account>.r2.cloudflarestorage.com
R2_ACCESS_KEY_ID=...
R2_SECRET_ACCESS_KEY=...
R2_BUCKET_NAME=documents
R2_PUBLIC_URL=https://pub-<hash>.r2.dev

# reCAPTCHA
NEXT_PUBLIC_RECAPTCHA_SITE_KEY=...
RECAPTCHA_SECRET_KEY=...
RECAPTCHA_MINIMUM_SCORE=0.5
```

### 13.2 Optional Variables

```bash
# Monitoring
NEXT_PUBLIC_GA_ID=G-XXXXXXXXXX
SENTRY_DSN=...

# Email
SMTP_HOST=...
SMTP_PORT=587
SMTP_USER=...
SMTP_PASSWORD=...

# Cache
REDIS_URL=redis://localhost:6379
```

---

## 14. Implementation Roadmap

### Phase 1: Foundation (Week 1)
- [ ] Initialize Next.js 16 + TypeScript
- [ ] Configure Tailwind CSS 4
- [ ] Setup Prisma + Database
- [ ] Configure NextAuth.js
- [ ] Setup R2 storage
- [ ] Create base layout components

### Phase 2: Public Portal (Week 2)
- [ ] Landing page with 3D background
- [ ] Registration form (all 6 sections)
- [ ] File upload with progress
- [ ] reCAPTCHA integration
- [ ] Form validation
- [ ] Success/failure handling

### Phase 3: Admin Core (Week 3)
- [ ] Admin login
- [ ] Dashboard with stats
- [ ] Registrations list + filters
- [ ] Registration detail view
- [ ] Soft delete/restore

### Phase 4: Advanced Features (Week 4)
- [ ] Payment ledger
- [ ] Program management
- [ ] Activity timeline
- [ ] CSV exports
- [ ] Admin accounts

### Phase 5: Polish & Deploy (Week 5)
- [ ] Loading states
- [ ] Error handling
- [ ] Responsive testing
- [ ] Performance optimization
- [ ] Security audit
- [ ] Production deployment

---

## 15. Appendices

### Appendix A: TypeScript Types

```typescript
// types/index.ts
export type Gender = "male" | "female";

export type Qualification = 
  | "degree" 
  | "diploma" 
  | "postgraduate" 
  | "msc" 
  | "phd" 
  | "work_experience" 
  | "other";

export type QualificationStatus = "completed" | "ongoing";

export type PaymentMethod = 
  | "bank_transfer" 
  | "cash" 
  | "card" 
  | "online_gateway" 
  | "cheque" 
  | "other";

export type PaymentStatus = "active" | "void";

export interface FileData {
  path: string;
  url: string;
  size: number;
  originalName: string;
  mimeType: string;
}

export interface PaymentSummary {
  fullAmount: number;
  paidTotal: number;
  balance: number;
  status: "unpaid" | "partial" | "paid" | "overpaid";
}

export interface ProgramCatalogItem {
  name: string;
  year: string;
  duration: string;
  active: boolean;
  price: number;
  currency: string;
}
```

### Appendix B: Sri Lanka Data

```typescript
// lib/data/locations.ts
export const sriLankaDistricts: Record<string, string[]> = {
  Western: ["Colombo", "Gampaha", "Kalutara"],
  Central: ["Kandy", "Matale", "Nuwara Eliya"],
  Southern: ["Galle", "Matara", "Hambantota"],
  Northern: ["Jaffna", "Kilinochchi", "Mannar", "Vavuniya", "Mullaitivu"],
  Eastern: ["Batticaloa", "Ampara", "Trincomalee"],
  "North Western": ["Kurunegala", "Puttalam"],
  "North Central": ["Anuradhapura", "Polonnaruwa"],
  Uva: ["Badulla", "Moneragala"],
  Sabaragamuwa: ["Ratnapura", "Kegalle"],
};

export const countries = [
  "Sri Lanka",
  "---",
  "Afghanistan",
  "Albania",
  // ... all countries
];
```

---

**End of Specification**

*This specification provides a complete blueprint for building a new Student Onboarding Portal using Next.js 16. Implement features in the order specified in the roadmap for optimal development flow.*
