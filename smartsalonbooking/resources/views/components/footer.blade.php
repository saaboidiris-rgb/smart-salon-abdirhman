<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <div class="footer__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> {{ config('app.salon_name') }}</div>
                <p>A modern beauty salon booking experience - browse services, pick your favourite specialist, and book in under a minute.</p>
                <div class="footer__social">
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
            <div>
                <h4>Explore</h4>
                <ul>
                    <li><a href="{{ route('services.index') }}">Services</a></li>
                    <li><a href="{{ route('team.index') }}">Our Team</a></li>
                    <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                    <li><a href="{{ route('testimonials.index') }}">Testimonials</a></li>
                </ul>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('pricing.index') }}">Pricing</a></li>
                    <li><a href="{{ route('contact.index') }}">Contact</a></li>
                    <li><a href="{{ route('booking.create') }}">Book Appointment</a></li>
                </ul>
            </div>
            <div>
                <h4>Visit Us</h4>
                <p class="mb-1">{{ \App\Models\Setting::get('address', '123 Blossom Avenue, Nairobi') }}</p>
                <p class="mb-1">{{ \App\Models\Setting::get('contact_phone', '+254 700 000 000') }}</p>
                <p>{{ \App\Models\Setting::get('contact_email', 'hello@smartsalon.test') }}</p>
                <p class="mb-0"><strong>Hours:</strong> {{ \App\Models\Setting::get('opening_hours', 'Mon - Sat, 9:00 AM - 7:00 PM') }}</p>
            </div>
        </div>
        <div class="footer__bottom">
            &copy; {{ now()->year }} {{ config('app.salon_name') }}. All rights reserved.
        </div>
    </div>
</footer>
