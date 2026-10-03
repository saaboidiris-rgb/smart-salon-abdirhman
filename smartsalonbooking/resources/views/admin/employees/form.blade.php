@extends('layouts.admin')

@section('title', $employee->exists ? 'Edit Employee' : 'Add Employee')
@section('page_title', $employee->exists ? 'Edit Employee' : 'Add Employee')

@section('content')
<div class="glass-card" style="max-width:720px;">
    <form method="POST"
          action="{{ $employee->exists ? route('admin.employees.update', $employee) : route('admin.employees.store') }}"
          enctype="multipart/form-data"
          id="employee-form">
        @csrf
        @if ($employee->exists) @method('PUT') @endif

        {{-- Name + Specialization --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="name"><i class="fa-solid fa-user"></i> Full Name</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $employee->name) }}" required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="specialization"><i class="fa-solid fa-star"></i> Specialization</label>
                <input type="text" id="specialization" name="specialization" class="form-control"
                       value="{{ old('specialization', $employee->specialization) }}"
                       placeholder="e.g. Hair Stylist">
            </div>
        </div>

        {{-- Email + Phone --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="email"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $employee->email) }}">
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="phone"><i class="fa-solid fa-phone"></i> Phone</label>
                <input type="text" id="phone" name="phone" class="form-control"
                       value="{{ old('phone', $employee->phone) }}">
            </div>
        </div>

        {{-- Bio --}}
        <div class="form-group">
            <label class="form-label" for="bio"><i class="fa-regular fa-note-sticky"></i> Short Bio</label>
            <textarea id="bio" name="bio" class="form-control" rows="3">{{ old('bio', $employee->bio) }}</textarea>
        </div>

        {{-- Working Days --}}
        <div class="form-group">
            <label class="form-label"><i class="fa-regular fa-calendar"></i> Working Days</label>
            <div class="flex gap-sm" style="flex-wrap:wrap;">
                @php $days = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun']; @endphp
                @foreach ($days as $value => $label)
                    <label class="form-check glass-card" style="padding:8px 16px; cursor:pointer;">
                        <input type="checkbox" name="working_days[]" value="{{ $value }}"
                            @checked(in_array($value, old('working_days', $employee->working_days ?? ['mon','tue','wed','thu','fri'])))>
                        {{ $label }}
                    </label>
                @endforeach
            </div>
            @error('working_days')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        {{-- Working Hours --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="working_hours_start"><i class="fa-regular fa-clock"></i> Hours Start</label>
                <input type="time" id="working_hours_start" name="working_hours_start"
                       class="form-control @error('working_hours_start') is-invalid @enderror"
                       value="{{ old('working_hours_start', $employee->working_hours_start ? $employee->working_hours_start->format('H:i') : '09:00') }}" required>
                @error('working_hours_start')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="working_hours_end"><i class="fa-regular fa-clock"></i> Hours End</label>
                <input type="time" id="working_hours_end" name="working_hours_end"
                       class="form-control @error('working_hours_end') is-invalid @enderror"
                       value="{{ old('working_hours_end', $employee->working_hours_end ? $employee->working_hours_end->format('H:i') : '18:00') }}" required>
                @error('working_hours_end')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Status + Photo --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="status"><i class="fa-solid fa-toggle-on"></i> Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active"   @selected(old('status', $employee->status ?? 'active') === 'active')>Active</option>
                    <option value="inactive" @selected(old('status', $employee->status) === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="photo"><i class="fa-solid fa-camera"></i> Photo <small class="text-muted">(max 5 MB · JPG/PNG/WebP)</small></label>
                <input type="file" id="photo" name="photo"
                       class="form-control @error('photo') is-invalid @enderror"
                       accept="image/png,image/jpeg,image/webp">
                @error('photo')<span class="form-error">{{ $message }}</span>@enderror
                <span class="form-error" id="photo-size-error" style="display:none;">
                    <i class="fa-solid fa-triangle-exclamation"></i> File exceeds 5 MB. Please choose a smaller image.
                </span>
            </div>
        </div>

        {{-- Photo preview --}}
        <div id="photo-preview-wrap" style="margin-bottom:18px; display:{{ $employee->photo ? 'flex' : 'none' }}; align-items:center; gap:14px;">
            <img id="photo-preview"
                 src="{{ $employee->photo ? asset('storage/'.$employee->photo) : '' }}"
                 alt="Photo preview"
                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--color-secondary);">
            <div style="font-size:.85rem; color:var(--color-muted);" id="photo-preview-label">Current photo</div>
        </div>

        {{-- Services --}}
        <div class="form-group">
            <label class="form-label"><i class="fa-solid fa-spa"></i> Services This Employee Can Perform</label>
            <div class="option-grid" style="grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:10px;">
                @foreach ($services as $service)
                    <label class="form-check glass-card" style="padding:10px 14px; cursor:pointer;">
                        <input type="checkbox" name="services[]" value="{{ $service->id }}"
                            @checked(in_array($service->id, old('services', $employee->services->pluck('id')->toArray() ?? [])))>
                        {{ $service->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex-between mt-2">
            <a href="{{ route('admin.employees.index') }}" class="btn btn--ghost">
                <i class="fa-solid fa-arrow-left"></i> Cancel
            </a>
            <button type="submit" class="btn btn--primary" id="submit-btn">
                <i class="fa-solid fa-{{ $employee->exists ? 'floppy-disk' : 'plus' }}"></i>
                {{ $employee->exists ? 'Save Changes' : 'Add Employee' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
(function () {
    var MAX_BYTES = 5 * 1024 * 1024; // 5 MB
    var photoInput   = document.getElementById('photo');
    var preview      = document.getElementById('photo-preview');
    var previewWrap  = document.getElementById('photo-preview-wrap');
    var previewLabel = document.getElementById('photo-preview-label');
    var sizeError    = document.getElementById('photo-size-error');
    var submitBtn    = document.getElementById('submit-btn');

    if (!photoInput) return;

    photoInput.addEventListener('change', function () {
        var file = photoInput.files[0];
        sizeError.style.display = 'none';
        submitBtn.disabled = false;

        if (!file) {
            previewWrap.style.display = 'none';
            return;
        }

        // Client-side size guard
        if (file.size > MAX_BYTES) {
            sizeError.style.display = 'block';
            submitBtn.disabled = true;
            photoInput.value = '';
            previewWrap.style.display = 'none';
            return;
        }

        // Live preview
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            previewLabel.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            previewWrap.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    });
})();
</script>
@endpush
@endsection
