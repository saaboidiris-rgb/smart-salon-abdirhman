@extends('layouts.admin')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')
<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search by name or email…" value="{{ request('search') }}">
    <button type="submit" class="btn btn--primary btn--sm">Search</button>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Bookings</th><th>Joined</th><th></th></tr></thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->user->name }}</td>
                    <td>{{ $customer->user->email }}</td>
                    <td>{{ $customer->user->phone }}</td>
                    <td>{{ $customer->appointments_count }}</td>
                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn--ghost btn--sm">View</a>
                            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn--ghost btn--sm">Edit</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No customers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $customers->links() }}
@endsection
