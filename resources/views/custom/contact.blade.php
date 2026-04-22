@extends('custom.master')

@section('content')

@php
    $setting = \App\Models\Setting::first();
@endphp

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">
        <h2 class="breadcrumb-title">Contact Us</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Contact</li>
        </ul>
    </div>
</div>

<!-- contact area -->
<div class="contact-area py-120">
    <div class="container">
        <div class="contact-wrapper">
            <div class="row">

                {{-- 🔵 LEFT FORM --}}
                <div class="col-lg-8 align-self-center">
                    <div class="contact-form">

                        <div class="contact-form-header">
                            <h2>Get In Touch</h2>
                            <p>Feel free to contact us anytime. We will get back to you as soon as possible.</p>
                        </div>

                        {{-- SUCCESS MESSAGE --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ERROR MESSAGE --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="name"
                                               class="form-control"
                                               placeholder="Your Name"
                                               value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" name="email"
                                               class="form-control"
                                               placeholder="Your Email"
                                               value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="phone"
                                       class="form-control"
                                       placeholder="Your Phone"
                                       value="{{ old('phone') }}">
                            </div>

                            <div class="form-group">
                                <textarea name="message"
                                          class="form-control"
                                          rows="5"
                                          placeholder="Write Your Message"
                                          required>{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="theme-btn">
                                <i class="far fa-paper-plane"></i> Send Message
                            </button>

                        </form>
                    </div>
                </div>

                {{-- 🟢 RIGHT CONTACT INFO --}}
                <div class="col-lg-4">
                    <div class="contact-content">

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Address</h5>
                                <p>{{ $setting->address ?? 'No address available' }}</p>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Call Us</h5>
                                <p>{{ $setting->phone ?? 'No phone' }}</p>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Email</h5>
                                <p>{{ $setting->email ?? 'No email' }}</p>
                            </div>
                        </div>

                        {{-- Social Links --}}
                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-share-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Follow Us</h5>

                               <div class="social-icons mt-3">

    @if($setting->facebook)
        <a href="{{ $setting->facebook }}" target="_blank" class="social-icon fb">
            <i class="fab fa-facebook-f"></i>
        </a>
    @endif

    @if($setting->instagram)
        <a href="{{ $setting->instagram }}" target="_blank" class="social-icon ig">
            <i class="fab fa-instagram"></i>
        </a>
    @endif

    @if($setting->twitter)
        <a href="{{ $setting->twitter }}" target="_blank" class="social-icon tw">
            <i class="fab fa-twitter"></i>
        </a>
    @endif

    @if($setting->youtube)
        <a href="{{ $setting->youtube }}" target="_blank" class="social-icon yt">
            <i class="fab fa-youtube"></i>
        </a>
    @endif

</div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- map -->
<div class="contact-map">
    <iframe 
        src="https://maps.google.com/maps?q={{ urlencode($setting->address ?? 'India') }}&output=embed"
        style="border:0; width:100%; height:400px;" 
        allowfullscreen="" loading="lazy">
    </iframe>
</div>

@endsection