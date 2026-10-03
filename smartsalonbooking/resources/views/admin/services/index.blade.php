@extends('layouts.admin')

@section('title', 'Services')
@section('page_title', 'Services')

@section('content')
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Manage everything customers can book.</p>
    <a href="{{ route('admin.services.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Add Service</a>
</div>

<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search services…" value="{{ request('search') }}">
    <select name="category" class="form-control">
        <option value="">All Categories</option>
        @foreach ($categories as $c)
            <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    <select name="status" class="form-control">
        <option value="">All Statuses</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
    </select>
    <button type="submit" class="btn btn--primary btn--sm">Filter</button>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Service</th><th>Category</th><th>Duration</th><th>Price</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->category->name }}</td>
                    <td>{{ $service->formattedDuration() }}</td>
                    <td>${{ number_format($service->price) }}</td>
                    <td><span class="badge badge--{{ $service->status }}">{{ ucfirst($service->status) }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                  data-confirm-submit data-confirm-title="Delete this service?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No services found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $services->links() }}
@endsection
