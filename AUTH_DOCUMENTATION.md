# Authentication System Documentation

## Overview
CryptoLibraryCenter now includes a complete authentication system with user registration, login, logout, and profile management features.

## Features

### 1. **User Registration**
- Create new account with email and password
- Password confirmation required
- Expertise level selection (Beginner, Intermediate, Advanced)
- Password validation (minimum 8 characters with uppercase, lowercase, numbers, symbols)

**Route:** `/register`

### 2. **User Login**
- Sign in with email and password
- "Remember me" functionality
- Session management
- Error handling for invalid credentials

**Route:** `/login`

### 3. **User Logout**
- Secure logout with session invalidation
- Token regeneration for security

**Route:** `POST /logout`

### 4. **User Profile**
- View and edit user information
- Upload profile picture
- Edit bio
- Update expertise level
- View user statistics (saved libraries, viewed libraries)

**Route:** `/profile`

## Database Schema

### Users Table
```sql
- id (Primary Key)
- name (string)
- email (string, unique)
- password (string, hashed)
- profile_picture (string, nullable) - stores path to uploaded image
- bio (text, nullable) - user biography
- expertise_level (string) - 'beginner', 'intermediate', or 'advanced'
- interests (json, nullable) - array of user interests
- email_verified_at (timestamp, nullable) - for future email verification
- remember_token (string, nullable) - for "remember me" functionality
- timestamps (created_at, updated_at)
```

## File Structure

### Controllers
- `app/Http/Controllers/AuthController.php` - Handles all authentication logic

### Views
- `resources/views/auth/layout.blade.php` - Base layout for auth pages
- `resources/views/auth/login.blade.php` - Login form
- `resources/views/auth/register.blade.php` - Registration form
- `resources/views/auth/profile.blade.php` - Profile management

### Styles
- `public/css/auth.css` - Authentication-specific styles
- `public/css/common.css` - Updated with navbar user menu styles

### Routes
- `routes/web.php` - All authentication routes defined

### Database
- `database/migrations/2025_01_02_000000_add_auth_fields_to_users_table.php` - Users table migration

## Routes

### Authentication Routes
```
GET    /login              - Show login form
POST   /login              - Handle login
GET    /register           - Show registration form
POST   /register           - Handle registration
POST   /logout             - Handle logout (requires auth)
GET    /profile            - Show user profile (requires auth)
PUT    /profile            - Update user profile (requires auth)
```

## Usage Examples

### Navigation Menu
The navigation automatically displays:
- **Not Authenticated:** "Sign In" and "Register" buttons
- **Authenticated:** User avatar/name dropdown with "My Profile" and "Logout" options

### Protected Routes
To protect routes that require authentication, use the `auth` middleware:

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show']);
});
```

### Check Authentication in Views
```blade
@auth
    <p>Welcome, {{ auth()->user()->name }}!</p>
    <a href="{{ route('profile') }}">View Profile</a>
@else
    <a href="{{ route('login') }}">Sign In</a>
@endauth
```

### Get Current User
```php
$user = auth()->user();
// or
$user = Auth::user();
```

## Security Features

1. **Password Hashing:** Bcrypt algorithm (Laravel default)
2. **CSRF Protection:** All forms include CSRF tokens
3. **Session Management:** Secure session handling with token regeneration
4. **Input Validation:** All inputs validated server-side
5. **Email Validation:** Unique email enforcement in database
6. **File Upload:** Profile pictures validated (image type, max 2MB)

## UI/UX Features

1. **Professional Design**
   - Clean, modern interface
   - Gradient backgrounds
   - Smooth animations
   - Responsive design for all devices

2. **User Feedback**
   - Success/error messages
   - Form validation feedback
   - Loading states

3. **Accessibility**
   - Proper form labels
   - Clear error messages
   - Keyboard navigation support

4. **Mobile Responsive**
   - Optimized for tablets and phones
   - Touch-friendly buttons
   - Mobile-friendly font sizes

## Future Enhancements

1. **Email Verification** - Confirm email addresses
2. **Password Reset** - Forgot password functionality
3. **Social Login** - Google, GitHub OAuth
4. **Two-Factor Authentication** - Enhanced security
5. **User Preferences** - Theme selection, notification settings
6. **Bookmarking** - Save favorite libraries
7. **User Activity** - Track viewed/saved libraries

## Troubleshooting

### Users not saving
- Ensure migrations have run: `php artisan migrate`
- Check database connection in `.env`

### Profile picture not uploading
- Ensure `storage/app/public` directory exists
- Run `php artisan storage:link` to create symlink
- Check file permissions

### Session issues
- Clear sessions: `php artisan cache:clear`
- Regenerate app key: `php artisan key:generate`

## Integration with Libraries

The authentication system integrates seamlessly with the existing library system:
- User expertise level can be used to recommend appropriate libraries
- Bookmarking system can be added to save favorite libraries
- User activity can track viewed and saved libraries
- Personalized recommendations based on interests
