@extends('custom.master')

@section('content')


<!-- register area -->
<div class="login-area py-120">
    <div class="container">
        <div class="col-md-5 mx-auto">
            <div class="login-form">

                {{-- Header --}}
                <div class="login-header text-center mb-4">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="logo" width="120">
                    <p>Create your account</p>
                </div>

                {{-- Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name --}}
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control"
                               placeholder="Enter your name"
                               required>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control"
                               placeholder="Enter your email"
                               required>
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Enter password"
                               required>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm password"
                               required>
                    </div>

                    {{-- Terms --}}
                    <div class="form-check form-group">
                        <input class="form-check-input" type="checkbox" required id="agree">
                        <label class="form-check-label" for="agree">
                            I agree with the <a href="#">Terms Of Service</a>
                        </label>
                    </div>

                    {{-- Button --}}
                    <div class="d-flex align-items-center">
                        <button type="submit" class="theme-btn w-100">
                            <i class="far fa-paper-plane"></i> Register
                        </button>
                    </div>

                </form>

                {{-- Footer --}}
                <div class="login-footer text-center mt-4">
                    <p>
                        Already have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection