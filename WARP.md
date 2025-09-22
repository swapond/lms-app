# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a Laravel + React starter application built on Laravel 12 with Inertia.js. It appears to be a **Learning Management System (LMS)** with course management, user authentication, enrollment tracking, and certificate generation capabilities.

**Technology Stack:**
- **Backend**: Laravel 12 with PHP 8.2+
- **Frontend**: React 19 + TypeScript + Inertia.js
- **Build Tools**: Vite + Laravel Vite Plugin
- **Styling**: Tailwind CSS 4.0 + Radix UI components
- **Database**: SQLite (development) with UUID primary keys
- **Authentication**: Laravel Fortify with 2FA support
- **Testing**: Pest PHP + PHPUnit

## Development Commands

### Primary Development Commands

```bash
# Start full development environment (recommended)
composer run dev
# This runs: server + queue + logs + frontend build concurrently

# Individual services
php artisan serve              # Start Laravel server
npm run dev                    # Start Vite dev server for frontend
php artisan queue:listen       # Process background jobs
php artisan pail --timeout=0  # Real-time log monitoring
```

### Build & Asset Management

```bash
# Frontend builds
npm run build                  # Production build
npm run build:ssr             # Build with SSR support
composer run dev:ssr          # Development with SSR

# Code quality
npm run lint                   # ESLint with auto-fix
npm run format                 # Prettier formatting
npm run format:check          # Check formatting
npm run types                  # TypeScript type checking
./vendor/bin/pint              # PHP formatting (Laravel Pint)
```

### Testing

```bash
composer run test              # Run PHP tests
# Equivalent to: php artisan config:clear && php artisan test

# Run specific tests
php artisan test --filter=UserTest
./vendor/bin/pest              # Run Pest tests directly
```

### Database Management

```bash
# Database setup and seeding
php artisan migrate            # Run migrations
php artisan db:seed           # Seed database with sample data
php artisan migrate:fresh --seed  # Fresh migration + seeding

# Generate test data (from DatabaseSeeder)
# Creates: users, categories, courses, enrollments, certificates
```

## Architecture Overview

### Domain Model (LMS Core Entities)

The application centers around a **course management system** with the following core entities:

- **Users**: Instructors and Students (with 2FA support)
- **Courses**: UUID-based with slugs, pricing, sections, and publishing states
- **Categories**: Hierarchical course categorization
- **Enrollments**: Student course registrations with progress tracking
- **CourseSection**: Course content organized in ordered sections
- **Certificates**: Generated upon course completion

**Key Relationships:**
- Users can be instructors (create courses) or students (enroll in courses)
- Courses have many sections and belong to categories (many-to-many)
- Enrollments track student progress and payment information
- Certificates are issued per course completion

### Laravel Application Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/              # Authentication controllers
│   │   └── Settings/          # User settings management
│   ├── Middleware/            # Custom middleware (Inertia, Appearance)
│   └── Requests/              # Form request validation
├── Models/                    # Eloquent models with UUID support
└── Providers/                 # Service providers

resources/
├── js/
│   ├── components/            # Reusable React components
│   ├── pages/                 # Inertia page components
│   ├── hooks/                 # Custom React hooks
│   └── wayfinder/             # Laravel Wayfinder integration
└── css/                       # Tailwind styles

database/
├── migrations/                # Database schema
├── seeders/                   # Database seeding
└── factories/                 # Model factories
```

### Frontend Architecture (React + Inertia)

- **Inertia.js**: Bridges Laravel backend with React frontend (SPA-like experience)
- **React 19**: Latest React with automatic JSX runtime
- **TypeScript**: Full type safety across frontend
- **Tailwind CSS 4**: Utility-first styling with custom components
- **Radix UI**: Accessible headless UI components
- **Laravel Wayfinder**: Form generation and validation

## Important Development Notes

### UUID Primary Keys

**CRITICAL**: All main models use UUID primary keys. When creating new models that reference core entities (User, Course, etc.), ensure proper UUID foreign key setup:

```php
// In migrations
$table->foreignUuid('course_id')->constrained()->onDelete('cascade');

// In models  
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class YourModel extends Model
{
    use HasUuids; // Essential for UUID support
}
```

### Authentication & Security

- **Laravel Fortify**: Handles authentication with 2FA capability
- **Password Confirmation**: Required for sensitive operations (configurable)
- **Email Verification**: Available but not enabled by default
- **Rate Limiting**: Applied to auth endpoints
- Default redirects to `/dashboard` after authentication

### Database Seeding Strategy

The `DatabaseSeeder` creates a comprehensive test dataset:
- Admin user: `admin@example.com` / `password`
- Test user: `test@example.com` / `password`  
- Hierarchical course categories
- Courses with instructors and course sections
- Student enrollments with progress tracking
- Certificates for completed courses

### Styling & Components

- **Tailwind CSS 4.0**: Latest version with new features
- **Component Library**: Radix UI primitives with Tailwind styling
- **Theme Support**: Light/dark mode with appearance settings
- **Responsive Design**: Mobile-first approach

### Development Workflow

1. **Environment Setup**: Copy `.env.example` to `.env` and configure
2. **Dependencies**: `composer install && npm install`
3. **Database**: `php artisan migrate --seed`
4. **Development**: `composer run dev` for full stack
5. **Type Checking**: `npm run types` before commits

### Testing Strategy

- **Pest PHP**: Modern testing framework for backend
- **Feature Tests**: Test HTTP endpoints and business logic
- **Architecture Tests**: Validate code structure and dependencies
- **Frontend**: No test framework configured (potential addition)