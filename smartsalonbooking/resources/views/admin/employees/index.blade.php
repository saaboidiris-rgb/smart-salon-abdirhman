@extends('layouts.admin')

@section('title', 'Employees')
@section('page_title', 'Employees')

@section('content')
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Your team of specialists.</p>
    <a href="{{ route('admin.employees.create') }}" class="btn btn--primary">➕ Add Employee</a>
</div>

<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search employees…" value="{{ request('search') }}">
    <select name="status" class="form-control">
        <option value="">All Statuses</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
    </select>
    <button type="submit" class="btn btn--primary btn--sm">Filter</button>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Name</th><th>Specialization</th><th>Phone</th><th>Bookings</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->specialization }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->appointments_count }}</td>
                    <td><span class="badge badge--{{ $employee->status }}">{{ ucfirst($employee->status) }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                                  data-confirm-submit data-confirm-title="Remove this employee?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No employees yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $employees->links() }}
@endsection
