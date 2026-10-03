@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Transparent pricing</span>
            <h2 class="section__title">No Surprises, Ever</h2>
            <p>Every price you see is exactly what you pay at checkout.</p>
        </div>

        @foreach ($categories as $category)
            @if ($category->services->count())
                <h3 class="mt-4">{{ $category->name }}</h3>
                <div class="table-wrapper glass-card mb-3" style="padding:0;">
                    <table class="table">
                        <thead>
                            <tr><th>Service</th><th>Duration</th><th>Price</th><th></th></tr>
                        </thead>
                        <tbody>
                            @foreach ($category->services as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->formattedDuration() }}</td>
                                    <td class="text-primary" style="font-weight:700;">${{ number_format($service->price) }}</td>
                                    <td><a href="{{ route('booking.create') }}" class="btn btn--secondary btn--sm">Book</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    </div>
</section>
@endsection
