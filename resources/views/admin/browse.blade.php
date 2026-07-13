@extends('layouts.admin')

@section('title', 'Browse Libraries — Admin')

@section('page-heading')
    {{-- blank; search bar is in content --}}
@endsection

@section('content')

{{-- Search bar --}}
<form method="GET" action="{{ route('admin.browse') }}" class="browse-search-form">
    <div class="browse-search-wrap">
        <i class="fas fa-search browse-search-icon"></i>
        <input type="text" name="search" value="{{ $search }}"
               placeholder="SEARCH ..." class="browse-search-input">
        @if($search)
        <a href="{{ route('admin.browse') }}" class="browse-clear-btn" title="Clear search">
            <i class="fas fa-times"></i>
        </a>
        @endif
        <button type="submit" class="browse-search-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>

{{-- Table --}}
<div class="data-card">
    <div class="table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Language</th>
                    <th>Category</th>
                    <th>Version</th>
                    <th>Date</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse($libraries as $lib)
                <tr>
                    <td>{{ $lib->name }}</td>
                    <td>{{ $lib->language ?? '—' }}</td>
                    <td>{{ $lib->category ?? '—' }}</td>
                    <td>{{ $lib->latest_version ?? '—' }}</td>
                    <td>{{ $lib->created_at ? $lib->created_at->format('Y-m-d') : '—' }}</td>
                    <td class="table-action">
                        <a href="{{ route('admin.edit', $lib->id) }}" class="action-btn action-btn--edit"
                           title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                    </td>
                    <td class="table-action">
                        <form method="POST"
                              action="{{ route('admin.destroy', $lib->id) }}"
                              onsubmit="return confirm('Delete {{ addslashes($lib->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn--delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="table-empty">No libraries found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($libraries->hasPages())
    <div class="pagination-wrap">
        {{ $libraries->links('vendor.pagination.simple-bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
