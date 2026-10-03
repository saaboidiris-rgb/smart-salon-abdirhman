@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('content')
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Group your services so customers can browse them more easily.</p>
    <a href="{{ route('admin.categories.create') }}" class="btn btn--primary">➕ Add Category</a>
</div>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Name</th><th>Slug</th><th>Services</th><th></th></tr></thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="text-muted">{{ $category->slug }}</td>
                    <td>{{ $category->services_count }}</td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  data-confirm-submit data-confirm-title="Delete this category?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $categories->links() }}
@endsection
