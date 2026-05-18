# Authentication Integration Guide

## Integrating Auth with Your Crypto Library System

This guide shows how to integrate the authentication system with your existing library features.

## 1. Require Authentication for Specific Routes

### Protect Profile-Related Routes
```php
// In routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [LibraryController::class, 'favorites'])->name('favorites');
    Route::post('/libraries/{id}/bookmark', [LibraryController::class, 'bookmark'])->name('bookmark');
    Route::delete('/libraries/{id}/bookmark', [LibraryController::class, 'unbookmark'])->name('unbookmark');
});
```

## 2. Create Bookmarking System

### Migration for Bookmarks
```php
// database/migrations/2025_01_03_000000_create_bookmarks_table.php
Schema::create('bookmarks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('library_id'); // Firebase library ID
    $table->string('library_name');
    $table->timestamps();
    
    $table->unique(['user_id', 'library_id']);
});
```

### Model
```php
// app/Models/Bookmark.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'library_id', 'library_name'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Controller
```php
// app/Http/Controllers/BookmarkController.php
public function toggle($libraryId, $libraryName)
{
    $bookmark = auth()->user()->bookmarks()
        ->where('library_id', $libraryId)
        ->first();
    
    if ($bookmark) {
        $bookmark->delete();
        return response()->json(['bookmarked' => false]);
    } else {
        auth()->user()->bookmarks()->create([
            'library_id' => $libraryId,
            'library_name' => $libraryName,
        ]);
        return response()->json(['bookmarked' => true]);
    }
}
```

## 3. Show User Stats on Profile

### Update AuthController
```php
public function showProfile()
{
    $user = Auth::user();
    $stats = [
        'bookmarked' => $user->bookmarks()->count(),
        'viewed' => $user->views()->count(),
        'reviews' => $user->reviews()->count(),
    ];
    
    return view('auth.profile', [
        'user' => $user,
        'stats' => $stats
    ]);
}
```

## 4. Customize Library Recommendations

### In Blade Views
```blade
@auth
    @if(auth()->user()->expertise_level === 'beginner')
        <!-- Show beginner-friendly libraries -->
        <div class="recommended-beginner">
            <!-- Beginner libraries -->
        </div>
    @endif
@endauth
```

### In Controllers
```php
public function getRecommendedLibraries()
{
    $user = auth()->user();
    $expertise = $user->expertise_level;
    
    return Library::where('recommended_for', $expertise)
        ->orWhere('difficulty', '<=', $expertise)
        ->get();
}
```

## 5. Track Library Views

### Create ViewLog Model
```php
// app/Models/ViewLog.php
protected $fillable = ['user_id', 'library_id'];

public function user()
{
    return $this->belongsTo(User::class);
}
```

### Log Views in Controller
```php
// When displaying library details
if (auth()->check()) {
    ViewLog::create([
        'user_id' => auth()->id(),
        'library_id' => $libraryId,
    ]);
}
```

## 6. Add Comments System

### Migration
```php
Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('library_id');
    $table->text('content');
    $table->unsignedTinyInteger('rating')->nullable();
    $table->timestamps();
});
```

### Show Comments in Library Detail
```blade
@foreach($comments as $comment)
    <div class="comment">
        <div class="comment-author">
            <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                 alt="{{ $comment->user->name }}">
            <strong>{{ $comment->user->name }}</strong>
        </div>
        <p>{{ $comment->content }}</p>
        @if($comment->rating)
            <span class="rating">★ {{ $comment->rating }}/5</span>
        @endif
    </div>
@endforeach
```

## 7. Show User Info in Library Cards

### Update Library Card Template
```blade
<div class="library-card">
    <h3>{{ $library->name }}</h3>
    
    @auth
    <div class="card-actions">
        <button onclick="toggleBookmark('{{ $library->id }}')">
            ♥ Bookmark
        </button>
    </div>
    @endauth
</div>
```

## 8. User Profile Integration in Library System

### Show Who's Viewing
```blade
@if($library->recent_viewers)
    <div class="recent-viewers">
        <p>Recently viewed by:</p>
        @foreach($library->recent_viewers as $viewer)
            <img title="{{ $viewer->user->name }}" 
                 src="{{ asset('storage/' . $viewer->user->profile_picture) }}" 
                 class="viewer-avatar">
        @endforeach
    </div>
@endif
```

## 9. Permissions and Authorization

### Create Gates
```php
// In AuthServiceProvider
Gate::define('edit-library', function (User $user) {
    return $user->role === 'admin';
});

Gate::define('moderate-comments', function (User $user) {
    return $user->role === 'moderator' || $user->role === 'admin';
});
```

### Use in Views
```blade
@can('moderate-comments')
    <button onclick="deleteComment({{ $comment->id }})">Delete</button>
@endcan
```

## 10. Email Notifications

### When User Bookmarks
```php
// Send notification
Mail::send('emails.bookmark-added', [
    'user' => $user,
    'library' => $library
], function ($mail) use ($user) {
    $mail->to($user->email);
});
```

## 11. User Search Filter

### Let Users Filter by Expertise
```blade
<div class="filter-expertise">
    <label>Expertise Level</label>
    <select name="expertise">
        <option value="">All Levels</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
    </select>
</div>
```

## 12. Add User to Libraries API Response

### Update SyncController
```php
public function libraries(Request $request)
{
    $libraries = Library::query();
    
    if (auth()->check()) {
        $bookmarkedIds = auth()->user()->bookmarks()
            ->pluck('library_id')
            ->toArray();
        
        $libraries->addSelect(
            \DB::raw("'" . json_encode($bookmarkedIds) . "' as user_bookmarks")
        );
    }
    
    return $libraries->get();
}
```

## 13. JavaScript Integration

### Bookmark Toggle Function
```javascript
async function toggleBookmark(libraryId, libraryName) {
    const response = await fetch(`/api/bookmarks/${libraryId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ library_name: libraryName })
    });
    
    const data = await response.json();
    
    // Update UI
    const btn = document.querySelector(`[data-library-id="${libraryId}"]`);
    btn.classList.toggle('bookmarked', data.bookmarked);
}
```

## 14. User Dashboard

### Create Dashboard Controller
```php
// app/Http/Controllers/DashboardController.php
public function index()
{
    $user = auth()->user();
    
    return view('dashboard.index', [
        'recentlyViewed' => $user->viewLogs()
            ->latest()
            ->limit(10)
            ->get(),
        'bookmarks' => $user->bookmarks()
            ->latest()
            ->limit(10)
            ->get(),
        'stats' => [
            'bookmarks_count' => $user->bookmarks()->count(),
            'views_count' => $user->viewLogs()->count(),
            'comments_count' => $user->comments()->count(),
        ]
    ]);
}
```

## Blade Helper Functions

Add to `app/Helpers/AuthHelper.php`:
```php
<?php

namespace App\Helpers;

class AuthHelper {
    public static function isBookmarked($libraryId)
    {
        return auth()->check() && 
            auth()->user()->bookmarks()
                ->where('library_id', $libraryId)
                ->exists();
    }
    
    public static function getUserExpertise()
    {
        return auth()->user()->expertise_level ?? 'guest';
    }
}
```

Use in views:
```blade
@if(isBookmarked($library->id))
    <span class="bookmarked">★ Bookmarked</span>
@endif
```

## Next: Create Relationships

In your User model:
```php
public function bookmarks()
{
    return $this->hasMany(Bookmark::class);
}

public function viewLogs()
{
    return $this->hasMany(ViewLog::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}
```

This integration provides a foundation for building a complete user engagement system!
