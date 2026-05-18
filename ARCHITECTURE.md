# 🏗️ Authentication System - Architecture & Flow Diagrams

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     CryptoLibraryCenter                          │
│                                                                   │
│  ┌───────────────────┐      ┌──────────────────┐               │
│  │   Users Browser   │◄────►│  Laravel Routes  │               │
│  │   (Frontend)      │      │  (routes/web.php)│               │
│  └───────────────────┘      └────────┬─────────┘               │
│                                      │                          │
│                            ┌─────────▼──────────┐              │
│                            │ AuthController     │              │
│                            │ (Login/Register)   │              │
│                            └─────────┬──────────┘              │
│                                      │                          │
│                      ┌───────────────┴────────────────┐         │
│                      │                                 │         │
│            ┌─────────▼─────────┐         ┌──────────▼────────┐│
│            │   User Model      │         │   Middleware      ││
│            │  (Authentication) │         │   (auth, guest)   ││
│            └─────────┬─────────┘         └──────────────────┘│
│                      │                                         │
│            ┌─────────▼──────────────┐                         │
│            │   Database (MySQL)     │                         │
│            │   - users table        │                         │
│            │   - sessions table     │                         │
│            │   - cache table        │                         │
│            └───────────────────────┘                         │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

## Authentication Flow

### User Registration Flow
```
┌──────────┐
│ Visitor  │
└─────┬────┘
      │
      ▼
  ┌─────────────────┐
  │ GET /register   │
  │ (Show Form)     │
  └─────┬───────────┘
        │
        ▼
  ┌─────────────────────────┐
  │ Display Registration    │
  │ Form                    │
  │ - Name                  │
  │ - Email                 │
  │ - Password              │
  │ - Expertise Level       │
  └────────┬────────────────┘
           │ (User fills form)
           ▼
    ┌──────────────────┐
    │ POST /register   │
    │ (Submit Form)    │
    └────────┬─────────┘
             │
      ┌──────▼──────┐
      │  Validate   │
      │  Input      │
      └──────┬──────┘
             │
      ┌──────▼──────────┐
      │ Valid? ────No──►│ Show Errors
      └──┬───────────────┘ Redirect
         │Yes
         ▼
   ┌──────────────────┐
   │ Hash Password    │
   │ (Bcrypt)         │
   └────────┬─────────┘
            │
            ▼
   ┌──────────────────┐
   │ Create User      │
   │ in Database      │
   └────────┬─────────┘
            │
            ▼
   ┌──────────────────┐
   │ Log User In      │
   │ (Create Session) │
   └────────┬─────────┘
            │
            ▼
   ┌──────────────────┐
   │ Redirect to      │
   │ Homepage         │
   └──────────────────┘
```

### User Login Flow
```
┌──────────┐
│ Visitor  │
└─────┬────┘
      │
      ▼
  ┌─────────────────┐
  │ GET /login      │
  │ (Show Form)     │
  └─────┬───────────┘
        │
        ▼
  ┌──────────────────────────┐
  │ Display Login Form       │
  │ - Email                  │
  │ - Password               │
  │ - Remember Me (checkbox) │
  └────────┬─────────────────┘
           │ (User enters credentials)
           ▼
    ┌──────────────────┐
    │ POST /login      │
    │ (Submit Form)    │
    └────────┬─────────┘
             │
      ┌──────▼──────────┐
      │ Find User by    │
      │ Email in DB     │
      └──────┬──────────┘
             │
      ┌──────▼──────────┐
      │ User Found? ┐
      └──┬──────┬───┘
         │Yes   │No
         │      └──► Error: "Credentials not found"
         │          Redirect with Error
         ▼
   ┌──────────────────┐
   │ Hash Input       │
   │ Password         │
   └────────┬─────────┘
            │
            ▼
   ┌──────────────────┐
   │ Match with       │
   │ DB Hash?         │
   └──┬──────┬────────┘
      │Yes   │No
      │      └──► Error: "Wrong Password"
      │          Redirect with Error
      ▼
   ┌──────────────────────┐
   │ Create Session       │
   │ Set Remember Cookie? │
   │ (if checked)         │
   └────────┬─────────────┘
            │
            ▼
   ┌──────────────────┐
   │ Redirect to      │
   │ Originally       │
   │ Requested Page   │
   │ (or Homepage)    │
   └──────────────────┘
```

### Logout Flow
```
┌──────────────┐
│ Logged In    │
│ User         │
└──────┬───────┘
       │
       ▼
  ┌────────────────┐
  │ Click Logout   │
  │ (Navbar Menu)  │
  └────────┬───────┘
           │
           ▼
    ┌──────────────────────────┐
    │ POST /logout             │
    │ (Submit Form with CSRF)  │
    └────────┬─────────────────┘
             │
       ┌─────▼──────┐
       │ Clear       │
       │ Session     │
       └─────┬───────┘
             │
       ┌─────▼──────────┐
       │ Invalidate     │
       │ Session Token  │
       └─────┬──────────┘
             │
       ┌─────▼──────────┐
       │ Clear Auth     │
       │ Cookie         │
       └─────┬──────────┘
             │
       ┌─────▼──────────┐
       │ Regenerate     │
       │ CSRF Token     │
       └─────┬──────────┘
             │
             ▼
      ┌──────────────────┐
      │ Redirect to      │
      │ Homepage         │
      │ (Now logged out) │
      └──────────────────┘
```

---

## Database Schema Diagram

```
┌─────────────────────────────────────────────────┐
│              USERS TABLE                        │
├─────────────────────────────────────────────────┤
│ id (BIGINT, PK)                                 │
│ name (VARCHAR 255)                              │
│ email (VARCHAR 255, UNIQUE)                     │
│ password (VARCHAR 255, HASHED)                  │
│ profile_picture (VARCHAR 255, NULLABLE)         │
│ bio (TEXT, NULLABLE)                            │
│ expertise_level (VARCHAR 50)                    │
│ ├─ 'beginner'                                   │
│ ├─ 'intermediate'                               │
│ └─ 'advanced'                                   │
│ interests (JSON, NULLABLE)                      │
│ email_verified_at (TIMESTAMP, NULLABLE)         │
│ remember_token (VARCHAR 100, NULLABLE)          │
│ created_at (TIMESTAMP)                          │
│ updated_at (TIMESTAMP)                          │
└─────────────────────────────────────────────────┘
         │
         │ (Future Relations)
         │
    ┌────┴────┬────────┬──────────┐
    │          │        │          │
    ▼          ▼        ▼          ▼
┌────────┐ ┌───────┐ ┌──────┐ ┌────────┐
│Bookmarks│Bookmarks│Comments│Profile  │
└────────┘ └───────┘ └──────┘ └────────┘
```

---

## Request/Response Cycle

### Registration Request Cycle
```
HTTP Request
    ↓
┌───────────────────────────┐
│ POST /register            │
│ Headers:                  │
│ - Content-Type: form-data │
│ - X-CSRF-TOKEN: xxxxx     │
│                           │
│ Body:                     │
│ - name                    │
│ - email                   │
│ - password                │
│ - password_confirmation   │
│ - expertise_level         │
└───────────────────────────┘
    ↓
┌───────────────────────────┐
│ Laravel Router            │
│ (routes/web.php)          │
└───────────────────────────┘
    ↓
┌───────────────────────────┐
│ AuthController::register()│
│ 1. Validate Input         │
│ 2. Check Email Unique     │
│ 3. Hash Password          │
│ 4. Create User Record     │
│ 5. Log User In            │
└───────────────────────────┘
    ↓
┌───────────────────────────┐
│ Database Update           │
│ INSERT INTO users...      │
└───────────────────────────┘
    ↓
┌───────────────────────────┐
│ HTTP Response             │
│ Status: 302 (Redirect)    │
│ Location: /               │
│ Set-Cookie: LARAVEL_...   │
└───────────────────────────┘
    ↓
Browser Redirect to Home
```

---

## Middleware Stack

```
User Request
    ↓
┌─────────────────────────────┐
│ Middleware Stack            │
├─────────────────────────────┤
│ 1. VerifyCsrfToken         │
│    (Check CSRF Token)      │
├─────────────────────────────┤
│ 2. StartSession            │
│    (Create/Resume Session) │
├─────────────────────────────┤
│ 3. AuthenticateSession     │
│    (Check User Auth)       │
├─────────────────────────────┤
│ 4. guest (if applied)      │
│    (Verify NOT logged in)  │
├─────────────────────────────┤
│ 5. auth (if applied)       │
│    (Verify IS logged in)   │
└─────────────────────────────┘
    ↓
Route Handler
```

---

## Session Lifecycle

```
┌────────────────────────────────────────────────┐
│          SESSION LIFECYCLE                     │
├────────────────────────────────────────────────┤
│                                                │
│ User Visits Site                               │
│     ↓                                          │
│ Session Created                                │
│ └─ Session ID generated                       │
│ └─ Session file/DB record created             │
│     ↓                                          │
│ User Logs In                                   │
│ └─ auth.user_id set in session                │
│ └─ Session cookie sent to browser             │
│     ↓                                          │
│ User Makes Requests                            │
│ └─ Browser sends session cookie               │
│ └─ Laravel loads session data                 │
│ └─ auth()->user() returns logged in user      │
│     ↓                                          │
│ User Logs Out                                  │
│ └─ auth.user_id removed from session          │
│ └─ Session invalidated                        │
│ └─ New CSRF token generated                   │
│     ↓                                          │
│ Session Expires (usually 2 hours)              │
│ └─ Session data deleted                       │
│ └─ Session cookie becomes invalid             │
│                                                │
└────────────────────────────────────────────────┘
```

---

## User Navigation Flow

### Unauthenticated User
```
Home
  ↓
[Sign In] ← ← ← ← ← → [Register]
  ↓                      ↓
Login Page          Registration Page
  ↓                      ↓
(Email/Pass)        (Name/Email/Pass)
  ↓                      ↓
[Sign In]            [Create Account]
  ↓                      ↓
Libraries            Redirect to Home
About                (Now Logged In)
```

### Authenticated User
```
Home
  ↓
Libraries ← → [👤 User Name ▼]
About           ├─ My Profile
                │   └─ Edit Info
                │   └─ Upload Picture
                │   └─ Save Changes
                │   └─ Logout
                │
                └─ Logout
                   └─ Redirect to Home
                     (Now Logged Out)
```

---

## Error Handling Flow

```
┌─────────────────────────┐
│ User Submits Form       │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│ Validation Rules        │
│ - Required fields       │
│ - Email format          │
│ - Password strength     │
│ - Unique email          │
└────────┬────────────────┘
         │
    ┌────┴──────┐
    │            │
    │ Fails      │ Passes
    ▼            ▼
┌──────────┐    ┌─────────┐
│ Return   │    │ Process │
│ Form     │    │ Request │
│ +Errors  │    └─────────┘
└──────────┘

Error Display:
┌──────────────────────────────┐
│ ALERT: Registration Error    │
├──────────────────────────────┤
│ ✗ Email already exists       │
│ ✗ Password too weak          │
│ ✗ Name is required           │
└──────────────────────────────┘
```

---

## Security Layers

```
┌────────────────────────────────────────────┐
│          SECURITY LAYERS                   │
├────────────────────────────────────────────┤
│                                            │
│ Layer 1: Client Side                       │
│ └─ HTML5 form validation                   │
│ └─ Password strength indicator             │
│                                            │
│ Layer 2: CSRF Protection                   │
│ └─ CSRF tokens in all forms               │
│ └─ Token validation on submission          │
│                                            │
│ Layer 3: Input Validation                  │
│ └─ Email format validation                 │
│ └─ Password requirement validation         │
│ └─ Unique email check in database          │
│                                            │
│ Layer 4: Password Security                 │
│ └─ Bcrypt hashing algorithm               │
│ └─ Automatic salt generation               │
│ └─ Hash comparison (timing-safe)           │
│                                            │
│ Layer 5: Session Security                  │
│ └─ Session ID regeneration                 │
│ └─ HTTPS recommended                       │
│ └─ Secure/httpOnly cookies                 │
│                                            │
│ Layer 6: Database Security                 │
│ └─ Prepared statements (ORM)              │
│ └─ No SQL injection possible               │
│ └─ Output escaping in views                │
│                                            │
└────────────────────────────────────────────┘
```

---

## File Upload Flow

```
┌──────────────────────┐
│ User Selects Image   │
│ (Profile Picture)    │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────┐
│ Form Submitted       │
│ multipart/form-data  │
└────────┬─────────────┘
         │
         ▼
┌──────────────────────────────┐
│ Server Receives File         │
│ - Validate mime type         │
│ - Check file size (max 2MB)  │
│ - Generate unique filename   │
└────────┬─────────────────────┘
         │
    ┌────┴──────┐
    │            │
    │ Valid      │ Invalid
    ▼            ▼
┌──────────┐   ┌─────────┐
│ Store    │   │ Return  │
│ File     │   │ Error   │
└────┬─────┘   └─────────┘
     │
     ▼
┌──────────────────────────────┐
│ Save Path to Database        │
│ storage/profiles/image.jpg   │
└────────┬─────────────────────┘
     │
     ▼
┌──────────────────────────────┐
│ Display on Profile           │
│ <img src="/storage/...">     │
└──────────────────────────────┘
```

---

## Route Middleware Application

```
Route Definition          Middleware Applied
──────────────────────────────────────────────

GET /login               guest
├─ Can only access if not logged in

POST /login              guest
├─ Can only access if not logged in

GET /register            guest
├─ Can only access if not logged in

POST /register           guest
├─ Can only access if not logged in

POST /logout             auth
├─ Can only access if logged in

GET /profile             auth
├─ Can only access if logged in

PUT /profile             auth
├─ Can only access if logged in
```

---

## Summary

This authentication system provides:
- ✅ Complete user lifecycle management
- ✅ Secure password handling
- ✅ Session management
- ✅ CSRF protection
- ✅ Input validation
- ✅ File upload handling
- ✅ Error handling
- ✅ Professional UI/UX

**Status**: Production Ready ✅
