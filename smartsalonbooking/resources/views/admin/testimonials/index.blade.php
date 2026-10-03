@extends('layouts.admin')

@section('title', 'Testimonials')
@section('page_title', 'Testimonials')

@section('content')
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Shown on the homepage and testimonials page.</p>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn--primary">➕ Add Testimonial</a>
</div>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Customer</th><th>Rating</th><th>Message</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($testimonials as $t)
                <tr>
                    <td>{{ $t->customer_name }}</td>
                    <td>{{ str_repeat('★', $t->rating) }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($t->message, 50) }}</td>
                    <td><span class="badge badge--{{ $t->status }}">{{ ucfirst($t->status) }}</span></td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}"
                                  data-confirm-submit data-confirm-title="Delete this testimonial?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted">No testimonials yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $testimonials->links() }}
@endsection
