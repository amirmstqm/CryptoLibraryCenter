# 🧪 Authentication System - Testing Guide

## Pre-Testing Checklist

- ✅ Database migrations have run
- ✅ Laravel routes are configured
- ✅ Views are in place
- ✅ CSS files are created
- ✅ Controllers are implemented

## Test Scenarios

### Scenario 1: User Registration

#### Test Case 1.1: Valid Registration
```
Steps:
1. Navigate to: http://localhost/CryptoLibraryCenter/public/register
2. Fill form:
   - Name: John Doe
   - Email: john@example.com
   - Password: SecurePass123!
   - Confirm Password: SecurePass123!
   - Expertise Level: Intermediate
3. Click "Create Account"

Expected Results:
✓ Account created successfully
✓ User automatically logged in
✓ Redirected to home page
✓ User name appears in navbar
```

#### Test Case 1.2: Invalid Email (Missing @)
```
Steps:
1. Go to register
2. Enter: "invalidemail"
3. Try to submit

Expected Results:
✓ Form shows error: "Must be a valid email"
✗ Form does not submit
```

#### Test Case 1.3: Weak Password
```
Steps:
1. Go to register
2. Enter password: "weak"
3. Try to submit

Expected Results:
✓ Form shows error about password requirements
✗ Form does not submit
```

#### Test Case 1.4: Existing Email
```
Steps:
1. Register with: john@example.com (first time)
2. Try to register with same email again

Expected Results:
✓ Form shows error: "Email already exists"
✗ Duplicate account not created
```

#### Test Case 1.5: Password Mismatch
```
Steps:
1. Go to register
2. Password: SecurePass123!
3. Confirm: DifferentPass123!
4. Try to submit

Expected Results:
✓ Form shows error: "Passwords do not match"
✗ Form does not submit
```

### Scenario 2: User Login

#### Test Case 2.1: Valid Login
```
Steps:
1. Navigate to: /login
2. Enter:
   - Email: john@example.com
   - Password: SecurePass123!
3. Click "Sign In"

Expected Results:
✓ Successfully logged in
✓ Redirected to home page
✓ User info appears in navbar
✓ "My Profile" dropdown available
```

#### Test Case 2.2: Wrong Password
```
Steps:
1. Go to login
2. Email: john@example.com
3. Password: WrongPassword123!
4. Click "Sign In"

Expected Results:
✓ Shows error: "Credentials do not match"
✗ Not logged in
✓ Redirected back to login
```

#### Test Case 2.3: Non-existent Email
```
Steps:
1. Go to login
2. Email: nonexistent@example.com
3. Password: AnyPassword123!
4. Click "Sign In"

Expected Results:
✓ Shows error: "Credentials do not match"
✗ Not logged in
```

#### Test Case 2.4: Remember Me
```
Steps:
1. Go to login
2. Enter valid credentials
3. Check "Remember me"
4. Click "Sign In"
5. Close browser
6. Return to site

Expected Results:
✓ User still logged in
✓ Session persists
```

### Scenario 3: User Profile

#### Test Case 3.1: Access Profile (Logged In)
```
Steps:
1. Log in with valid credentials
2. Click user name in navbar
3. Click "My Profile"

Expected Results:
✓ Profile page loads
✓ All user info displayed
✓ Can edit fields
```

#### Test Case 3.2: Access Profile (Logged Out)
```
Steps:
1. Ensure logged out
2. Try to access: /profile directly

Expected Results:
✗ Redirected to login page
✓ Cannot access without authentication
```

#### Test Case 3.3: Update Profile Info
```
Steps:
1. Go to profile
2. Change name to: "Jane Doe"
3. Change bio to: "Cryptography enthusiast"
4. Change expertise to: "Advanced"
5. Click "Save Changes"

Expected Results:
✓ Changes saved
✓ Success message shown
✓ Info updated in navbar
```

#### Test Case 3.4: Upload Profile Picture
```
Steps:
1. Go to profile
2. Click "Choose File" under Profile Picture
3. Select an image (JPG, PNG, or GIF)
4. Click "Save Changes"

Expected Results:
✓ Image uploaded
✓ Image displays as avatar
✓ Success message shown
```

#### Test Case 3.5: Profile Picture Size Limit
```
Steps:
1. Go to profile
2. Try to upload image > 2MB

Expected Results:
✓ Shows error: "File must be less than 2MB"
✗ File not uploaded
```

### Scenario 4: Navigation & UI

#### Test Case 4.1: Navbar When Logged Out
```
Expected Display:
┌─────────────────────────────────────┐
│ Logo | Home | Library | About | [Sign In] [Register]
└─────────────────────────────────────┘
```

#### Test Case 4.2: Navbar When Logged In
```
Expected Display:
┌─────────────────────────────────────────────┐
│ Logo | Home | Library | About | [👤 John Doe ▼]
└─────────────────────────────────────────────┘
```

#### Test Case 4.3: User Menu Dropdown
```
Steps:
1. Log in
2. Click user name in navbar

Expected Results:
✓ Dropdown appears
✓ Shows "My Profile" option
✓ Shows "Logout" option
✓ Can click each option
```

#### Test Case 4.4: Close Menu on Outside Click
```
Steps:
1. Open user menu
2. Click anywhere else on page

Expected Results:
✓ Menu closes automatically
```

### Scenario 5: Logout

#### Test Case 5.1: Logout from Dropdown
```
Steps:
1. Log in
2. Click user name
3. Click "Logout"

Expected Results:
✓ Successfully logged out
✓ Redirected to home
✓ Session cleared
✓ Navbar shows [Sign In] [Register]
```

#### Test Case 5.2: Session Invalidation
```
Steps:
1. Log in
2. Click logout
3. Check browser storage/cookies

Expected Results:
✓ Session cookie deleted
✓ Cannot access protected routes
```

### Scenario 6: Responsive Design

#### Test Case 6.1: Desktop (1920px)
```
Steps:
1. Resize browser to 1920px width
2. Visit all auth pages

Expected Results:
✓ Forms centered on page
✓ Proper spacing
✓ All elements visible
✓ Navbar layout horizontal
```

#### Test Case 6.2: Tablet (768px)
```
Steps:
1. Resize browser to 768px width
2. Visit all auth pages

Expected Results:
✓ Forms still readable
✓ Buttons appropriately sized
✓ No horizontal scrolling
✓ Touch-friendly elements
```

#### Test Case 6.3: Mobile (375px)
```
Steps:
1. Resize browser to 375px width
2. Visit all auth pages

Expected Results:
✓ Full-width forms
✓ Large touch targets
✓ Dropdown menu appears at bottom
✓ No horizontal scrolling
```

### Scenario 7: Form Validation

#### Test Case 7.1: Required Fields
```
Steps:
1. Go to register
2. Leave fields empty
3. Try to submit

Expected Results:
✓ Browser shows validation message
✗ Form does not submit
```

#### Test Case 7.2: Password Strength Indicator
```
Steps:
1. Go to register
2. Look at password field hint

Expected Results:
✓ Shows requirements
✓ Lists password rules
```

#### Test Case 7.3: Error Messages
```
Expected:
✓ Clear, specific error messages
✓ Red text color
✓ Messages appear near fields
```

### Scenario 8: Security

#### Test Case 8.1: CSRF Protection
```
Steps:
1. Inspect form HTML
2. Look for csrf_token field

Expected Results:
✓ CSRF token present
✓ Hidden input field exists
```

#### Test Case 8.2: Password Hashing
```
Steps:
1. Register with password
2. Check database users table

Expected Results:
✓ Password is hashed (not plain text)
✓ Hashes don't match passwords
```

#### Test Case 8.3: Direct Profile Access (Logged Out)
```
Steps:
1. Ensure logged out
2. Access: /profile

Expected Results:
✗ Redirected to login
✓ Cannot access without auth
```

## Testing Tools

### Browser Developer Tools
```
- Check console for errors
- Check network tab for requests
- Check storage for sessions/cookies
```

### Database Tools (PhpMyAdmin)
```
- Check users table created
- Verify user data saved
- Check password is hashed
```

### Laravel Artisan Commands
```bash
php artisan tinker
>>> User::all()
>>> User::where('email', 'john@example.com')->first()
```

## Common Issues & Solutions

### Issue: Profile picture not uploading
**Solution:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### Issue: Sessions not persisting
**Solution:**
```bash
php artisan cache:clear
php artisan session:table  // if using database
php artisan migrate
```

### Issue: CSRF token mismatch
**Solution:**
- Ensure `{{ csrf_field() }}` in all forms
- Check `.env` APP_KEY is set
- Clear sessions: `php artisan cache:clear`

### Issue: Password validation failing
**Solution:**
- Password must be 8+ characters
- Must have uppercase letter (A-Z)
- Must have lowercase letter (a-z)
- Must have number (0-9)
- Must have special character (!@#$%^&*)

## Performance Testing

### Load Test
```bash
# Load test with Apache Bench
ab -n 100 -c 10 http://localhost/CryptoLibraryCenter/public/login
```

### Response Times
```
Target: < 200ms
- Login: ~50-100ms
- Register: ~100-150ms
- Profile update: ~80-120ms
```

## Browser Testing

Test on these browsers:
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile Safari
- ✅ Chrome Mobile

## Test Execution Checklist

- [ ] All 8 scenarios tested
- [ ] All 40+ test cases passed
- [ ] Responsive design verified
- [ ] Security checks passed
- [ ] Error messages clear
- [ ] UI looks professional
- [ ] Forms work correctly
- [ ] Navigation works
- [ ] Profile management works
- [ ] Logout works properly

## Sign-Off

Once all tests pass:

```
Date: _____________
Tester: _____________
Status: ✅ APPROVED FOR PRODUCTION
```

## Next Steps After Testing

1. ✅ Run final security audit
2. ✅ Get stakeholder approval
3. ✅ Deploy to production
4. ✅ Monitor error logs
5. ✅ Gather user feedback
6. ✅ Plan future enhancements

---

**Happy Testing!** 🚀
