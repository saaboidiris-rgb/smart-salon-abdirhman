@extends('layouts.app')

@section('title', 'Book an Appointment')

@section('content')
<section class="booking-page">
    <div class="container" style="max-width:900px;">

        {{-- Page Header --}}
        <div class="booking-page__header">
            <div class="booking-page__eyebrow"><i class="fa-solid fa-scissors"></i> Smart Salon</div>
            <h1 class="booking-page__title">Book Your Appointment</h1>
            <p class="booking-page__sub">Walk through 5 simple steps and secure your slot in seconds.</p>
        </div>

        {{-- Horizontal Step Progress Tracker --}}
        <div class="booking-tracker" id="booking-tracker">
            <div class="booking-tracker__line"><div class="booking-tracker__progress" id="tracker-progress"></div></div>
            <div class="booking-step-pill" data-step-indicator="1">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-scissors"></i></div>
                <span class="booking-step-pill__label">Service</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="2">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-user-tie"></i></div>
                <span class="booking-step-pill__label">Specialist</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="3">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-calendar-days"></i></div>
                <span class="booking-step-pill__label">Date & Time</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="4">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-user-pen"></i></div>
                <span class="booking-step-pill__label">Details</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="5">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-clipboard-check"></i></div>
                <span class="booking-step-pill__label">Confirm</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert--danger mb-3">
                <strong><i class="fa-solid fa-circle-exclamation"></i> Please fix the following:</strong>
                <ul class="mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store') }}" id="booking-form" data-slots-url="{{ route('booking.slots') }}">
            @csrf
            <input type="hidden" name="appointment_date" id="appointment_date_hidden" value="{{ old('appointment_date', $minDate) }}">

            <div class="booking-slides-container">

                {{-- ===================== STEP 1: Service ===================== --}}
                <div class="booking-slide glass-card" data-step="1">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 1 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-scissors"></i> Choose a Service</h2>
                        <p class="booking-slide__desc text-muted">Select the treatment you'd like to book.</p>
                    </div>

                    @foreach ($categories as $category)
                        @if ($category->services->count())
                            <div class="service-category-label">{{ $category->name }}</div>
                            <div class="option-grid mb-3">
                                @foreach ($category->services as $service)
                                    <label class="option-card" for="service_{{ $service->id }}">
                                        <input type="radio" id="service_{{ $service->id }}" name="service_id" value="{{ $service->id }}"
                                               data-duration="{{ $service->duration_minutes }}"
                                               data-price="{{ $service->price }}"
                                               data-name="{{ $service->name }}"
                                               @checked(old('service_id') == $service->id)>
                                        <div class="option-card__select-dot"></div>
                                        <div class="option-card__service-icon"><i class="fa-solid fa-spa"></i></div>
                                        <strong class="option-card__name">{{ $service->name }}</strong>
                                        <div class="option-card__meta">
                                            <span><i class="fa-regular fa-clock"></i> {{ $service->formattedDuration() }}</span>
                                            <span class="option-card__price">${{ number_format($service->price, 2) }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    @endforeach

                    <div class="booking-slide__nav">
                        <span></span>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Specialist <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ===================== STEP 2: Specialist ===================== --}}
                <div class="booking-slide glass-card" data-step="2">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 2 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-user-tie"></i> Pick Your Specialist</h2>
                        <p class="booking-slide__desc text-muted">Only specialists who offer your selected service are shown.</p>
                    </div>

                    <div class="option-grid option-grid--specialists" id="employee-options">
                        @foreach ($employees as $employee)
                            <label class="option-card option-card--specialist" for="employee_{{ $employee->id }}"
                                   data-services="{{ $employee->services->pluck('id')->implode(',') }}">
                                <input type="radio" id="employee_{{ $employee->id }}" name="employee_id"
                                       value="{{ $employee->id }}" data-name="{{ $employee->name }}"
                                       @checked(old('employee_id') == $employee->id)>
                                <div class="option-card__select-dot"></div>
                                <div class="option-card__avatar-wrapper">
                                    <img src="{{ $employee->photo ? asset('storage/'.$employee->photo) : 'https://i.pravatar.cc/100?u='.$employee->id }}"
                                         alt="{{ $employee->name }}" class="option-card__avatar">
                                </div>
                                <strong class="option-card__name">{{ $employee->name }}</strong>
                                <div class="text-muted" style="font-size:.8rem;">{{ $employee->specialization }}</div>
                            </label>
                        @endforeach
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Date & Time <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ===================== STEP 3: Date & Time ===================== --}}
                <div class="booking-slide glass-card" data-step="3">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 3 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-calendar-days"></i> Pick a Date & Time</h2>
                        <p class="booking-slide__desc text-muted">Select your preferred date and available time slot.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="appointment_date">
                            <i class="fa-regular fa-calendar"></i> Appointment Date
                        </label>
                        <div class="input-with-icon">
                            <i class="fa-regular fa-calendar-check input-icon"></i>
                            <input type="date" id="appointment_date" class="form-control"
                                   min="{{ $minDate }}" value="{{ old('appointment_date', $minDate) }}"
                                   onchange="document.getElementById('appointment_date_hidden').value = this.value">
                        </div>
                    </div>

                    <div class="slot-section-heading">
                        <i class="fa-regular fa-clock"></i> Available Times
                    </div>
                    <div id="slot-grid" class="slot-container">
                        <div class="slot-empty-state">
                            <i class="fa-regular fa-calendar-xmark"></i>
                            <p>Choose a service, a specialist and a date to see open times.</p>
                        </div>
                    </div>
                    <input type="hidden" name="start_time" id="start_time_input" value="{{ old('start_time') }}">

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Your Details <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ===================== STEP 4: Personal Details ===================== --}}
                <div class="booking-slide glass-card" data-step="4">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 4 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-user-pen"></i> Your Details</h2>
                        <p class="booking-slide__desc text-muted">We need a few details to complete your booking.</p>
                    </div>

                    @auth
                        <div class="auth-notice">
                            <i class="fa-solid fa-circle-user"></i>
                            <div>
                                <strong>Booking as {{ auth()->user()->name }}</strong>
                                <span class="text-muted">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-3" style="font-size:.9rem;">
                            <i class="fa-solid fa-shield-halved"></i>
                            Creating an account lets you view, cancel or reschedule this booking anytime.
                        </p>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" id="name" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Jane Doe" required>
                                </div>
                                @error('name')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-envelope input-icon"></i>
                                    <input type="email" id="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" placeholder="jane@email.com" required>
                                </div>
                                @error('email')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="text" id="phone" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}" placeholder="+1 555 000 0000" required>
                                </div>
                                @error('phone')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Create a Password</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-lock input-icon"></i>
                                    <input type="password" id="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="At least 8 characters" required>
                                </div>
                                @error('password')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock-open input-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" placeholder="Re-enter your password" required>
                            </div>
                        </div>
                    @endauth

                    <div class="form-group">
                        <label class="form-label" for="notes">
                            <i class="fa-regular fa-note-sticky"></i> Notes (optional)
                        </label>
                        <textarea id="notes" name="notes" class="form-control"
                                  rows="3" placeholder="Any special requests or things we should know…">{{ old('notes') }}</textarea>
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step>
                            Review Booking <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- ===================== STEP 5: Summary & Confirm ===================== --}}
                <div class="booking-slide glass-card" data-step="5">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 5 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-clipboard-check"></i> Review & Confirm</h2>
                        <p class="booking-slide__desc text-muted">Everything look good? Confirm to lock in your appointment.</p>
                    </div>

                    <div id="booking-summary-box">
                        <p class="text-muted">Complete the previous steps to see your summary.</p>
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn--primary btn--lg confirm-btn">
                            <i class="fa-solid fa-check-double"></i> Confirm & Book
                        </button>
                    </div>
                </div>

            </div>{{-- end .booking-slides-container --}}
        </form>

    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('js/booking.js') }}"></script>
@endpush
