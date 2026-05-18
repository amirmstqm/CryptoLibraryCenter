# 🎯 Authentication System - Quick Reference Card

## URLs

| Action | URL |
|--------|-----|
| Register | `/register` |
| Login | `/login` |
| Profile | `/profile` |
| Logout | `POST /logout` |

## Default Credentials (For Testing)

After registering, you can use those credentials to log in.

```
Email: your-email@example.com
Password: YourPassword123!
```

---

## File Locations

### Controller Logic
📍 `app/Http/Controllers/AuthController.php`
- `showLogin()` - Display login form
- `login()` - Handle login
- `showRegister()` - Display registration form
- `register()` - Handle registration
- `logout()` - Handle logout
- `showProfile()` - Display profile
- `updateProfile()` - Update profile

### Views
📍 `resources/views/auth/`
- `login.blade.php` - Login page
- `register.blade.php` - Registration page
- `profile.blade.php` - Profile page
- `layout.blade.php` - Auth base layout

### Styles
📍 `public/css/auth.css` - Authentication styles
📍 `public/css/common.css` - Updated navbar styles

### Database
📍 `database/migrations/2025_01_02_000000_add_auth_fields_to_users_table.php`

---

## In Your Blade Templates

### Check if Logged In
```blade
@auth
    <!-- Show logged in content -->
@else
    <!-- Show login prompt -->
@endauth
```

### Get Current User
```blade
{{ auth()->user()->name }}
{{ auth()->user()->email }}
{{ auth()->user()->expertise_level }}
```

### Create Logout Form
```blade
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

---

## In Your Controllers

### Protect a Route
```php
Route::middleware('auth')->get('/bookmarks', function () {
    // Only logged in users
});
```

### Get Current User
```php
$user = auth()->user();
$id = auth()->id();
```

### Check Authentication
```php
if (auth()->check()) {
    // User is logged in
}
```

---

## Password Requirements

✅ Minimum 8 characters
✅ At least one uppercase letter (A-Z)
✅ At least one lowercase letter (a-z)
✅ At least one number (0-9)
✅ At least one special character (!@#$%^&*)

**Example**: `CryptoPass123!`

---

## User Fields

```php
$user->id              // User ID
$user->name            // Full name
$user->email           // Email (unique)
$user->password        // Hashed password
$user->profile_picture // Path to image
$user->bio             // User biography
$user->expertise_level // beginner|intermediate|advanced
$user->interests       // JSON array
$user->created_at      // Registration date
$user->updated_at      // Last update date
```

---

## Navbar Display

### When Logged Out
```
Home | Library | About     [Sign In] [Register]
```

### When Logged In
```
Home | Library | About     [👤 John Doe ▼]
                            ├─ My Profile
                            └─ Logout
```

---

## Common Tasks

### Add Profile Picture
1. Go to `/profile`
2. Click "Choose File" under "Profile Picture"
3. Select an image (max 2MB)
4. Click "Save Changes"

### Update Bio
1. Go to `/profile`
2. Edit text in "Bio" field
3. Click "Save Changes"

### Change Expertise Level
1. Go to `/profile`
2. Select new level from dropdown
3. Click "Save Changes"

### Log Out
1. Click your name in top right
2. Click "Logout"
3. You'll be redirected to home page

---

## Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    profile_picture VARCHAR(255) NULL,
    bio TEXT NULL,
    expertise_level VARCHAR(50),
    interests JSON NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

---

## Security Notes

🔒 All passwords are hashed (Bcrypt)
🔒 CSRF tokens protect all forms
🔒 Sessions regenerate after logout
🔒 Email addresses must be unique
🔒 File uploads are validated
🔒 Input is automatically escaped

---

## Environment Variables (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crypto_library
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
HASH_DRIVER=bcrypt
```

---

## API Integration Examples

### Check if Library is Bookmarked
```javascript
const isBookmarked = async (libraryId) => {
    const response = await fetch(`/api/bookmarks/${libraryId}`);
    return response.json();
};
```

### Get User Profile via API
```javascript
const getProfile = async () => {
    const response = await fetch('/api/user/profile');
    return response.json();
};
```

---

## Troubleshooting

### Can't access `/profile`
→ Must be logged in. Redirects to login if not.

### Profile picture not saving
→ Run `php artisan storage:link`
→ Check `storage/app/public` permissions

### Getting CSRF token mismatch
→ Ensure `{{ csrf_token() }}` in forms
→ Check session configuration

### Password validation failing
→ Must meet all requirements (see above)
→ Check for special characters

### Can't login with correct email/password
→ Verify email exists in database
→ Check password is correct
→ Try resetting password (future feature)

---

## Support Resources

📖 Full Documentation: `AUTH_DOCUMENTATION.md`
🚀 Setup Guide: `AUTHENTICATION_SETUP.md`
🔗 Integration Guide: `INTEGRATION_GUIDE.md`
📋 This Guide: `IMPLEMENTATION_SUMMARY.md`

---

## Quick Start

```bash
# 1. Register
Navigate to: /register
Fill form and click "Create Account"

# 2. Login
Navigate to: /login
Enter email and password

# 3. View Profile
Click your name in navbar → "My Profile"

# 4. Edit Profile
Update fields and click "Save Changes"

# 5. Logout
Click your name → "Logout"
```

---

## Database Commands

```bash
# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Roll back migrations
php artisan migrate:rollback

# Fresh install (reset database)
php artisan migrate:fresh
```

---

## Useful Artisan Commands

```bash
# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName

# List all routes
php artisan route:list

# Clear all caches
php artisan cache:clear
```

---

**Last Updated**: May 18, 2025  
**Version**: 1.0  
**Status**: ✅ Production Ready
