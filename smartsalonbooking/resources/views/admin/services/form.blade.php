@extends('layouts.admin')

@section('title', $service->exists ? 'Edit Service' : 'Add Service')
@section('page_title', $service->exists ? 'Edit Service' : 'Add Service')

@section('content')
<div class="glass-card" style="max-width:680px;">
    <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($service->exists) @method('PUT') @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="name">Service name</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $service->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $service->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="duration_minutes">Duration (minutes)</label>
                <input type="number" id="duration_minutes" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}" min="5" required>
                @error('duration_minutes')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="price">Price ($)</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" min="0" required>
                @error('price')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $service->description) }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" @selected(old('status', $service->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $service->status) === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="image">Photo</label>
                <input type="file" id="image" name="image" class="form-control input-file @error('image') is-invalid @enderror" accept="image/png, image/jpeg">
                <span class="form-hint">JPG or PNG, max 2MB.</span>
                @error('image')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        @if ($service->image)
            <img src="{{ asset('storage/'.$service->image) }}" alt="Current photo" style="width:120px;border-radius:var(--radius-sm);margin-bottom:16px;">
        @endif

        <div class="form-group">
            <label class="form-label">Specialists who can perform this service</label>
            <div class="option-grid">
                @foreach ($employees as $employee)
                    <label class="form-check glass-card" style="padding:12px;">
                        <input type="checkbox" name="employees[]" value="{{ $employee->id }}"
                            @checked(in_array($employee->id, old('employees', $service->employees->pluck('id')->toArray() ?? [])))>
                        {{ $employee->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex-between mt-2">
            <a href="{{ route('admin.services.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">{{ $service->exists ? 'Save Changes' : 'Create Service' }}</button>
        </div>
    </form>
</div>
@endsection
