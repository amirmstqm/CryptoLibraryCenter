# CryptoLibraryCenter — Laravel Migration Guide

## Project Structure

```
CryptoLibraryCenter/
├── app/
│   └── Http/
│       └── Controllers/
│           └── PageController.php       ← Handles all page routes
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php            ← Shared layout (nav + footer)
│   │   └── pages/
│   │       ├── home.blade.php
│   │       ├── libraries.blade.php
│   │       ├── details.blade.php
│   │       └── about.blade.php
│   ├── js/
│   │   ├── main.js
│   │   ├── filter.js
│   │   ├── data-fetching.js
│   │   ├── firebase-config.js
│   │   └── library-details.js
│   └── css/                             ← (copy your CSS files here)
│       ├── home.css
│       ├── about.css
│       ├── libraries.css
│       ├── details.css
│       └── prism-custom-theme.css
├── public/
│   ├── js/                              ← COPY JS files here after build
│   └── css/                             ← COPY CSS files here after build
└── routes/
    └── web.php                          ← All routes defined here
```

---

## Setup Steps

### 1. Create a new Laravel project

```bash
composer create-project laravel/laravel CryptoLibraryCenter
cd CryptoLibraryCenter
```

### 2. Copy the provided files into your Laravel project

| Source file                              | Laravel destination                              |
|------------------------------------------|--------------------------------------------------|
| `app/Http/Controllers/PageController.php`| `app/Http/Controllers/PageController.php`        |
| `routes/web.php`                         | `routes/web.php`                                 |
| `resources/views/layouts/app.blade.php`  | `resources/views/layouts/app.blade.php`          |
| `resources/views/pages/*.blade.php`      | `resources/views/pages/`                         |
| `resources/js/*.js`                      | `resources/js/` (and also `public/js/`)          |
| Your CSS files                           | `public/css/`                                    |

### 3. Copy assets to public/

Since Firebase JS modules are loaded via CDN and your JS uses ES modules,
copy the JS files directly to `public/js/`:

```bash
cp resources/js/*.js public/js/
```

Copy your CSS files to `public/css/`:

```bash
cp resources/css/*.css public/css/
```

### 4. (Optional) Use Vite for asset bundling

If you prefer Vite (Laravel's default bundler):

```bash
npm install
npm run dev   # development
npm run build # production
```

Update `resources/views/layouts/app.blade.php` to use `@vite` directives:

```blade
@vite(['resources/css/home.css', 'resources/js/main.js'])
```

### 5. Serve the app

```bash
php artisan serve
```

Visit: http://localhost:8000

---

## Routes

| URL                          | View                      | Route Name  |
|------------------------------|---------------------------|-------------|
| `/`                          | pages.home                | home        |
| `/libraries`                 | pages.libraries           | libraries   |
| `/libraries/details?id=xxx`  | pages.details             | details     |
| `/about`                     | pages.about               | about       |

---

## Firebase

Firebase remains **client-side** (JavaScript). The `firebase-config.js` file
contains your project credentials and is loaded as an ES module in the browser.

No changes to Firebase are needed — data fetching, filtering, and library
details all work the same as before, just served through Laravel routes.

---

## Key Changes from Plain HTML

| Before (HTML)                        | After (Laravel)                          |
|--------------------------------------|------------------------------------------|
| `href="../home.html"`                | `{{ route('home') }}`                    |
| `href="libraries.html"`             | `{{ route('libraries') }}`               |
| `href="details.html?id=xxx"`        | `/libraries/details?id=xxx`              |
| `href="about.html"`                  | `{{ route('about') }}`                   |
| `href="../styles/about.css"`         | `{{ asset('css/about.css') }}`           |
| `src="../js/main.js"`                | `{{ asset('js/main.js') }}`              |
| Nav active class set manually in HTML| Set dynamically via `request()->routeIs()`|
