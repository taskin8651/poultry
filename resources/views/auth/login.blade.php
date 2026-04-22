@extends('custom.master')

@section('content')


<!-- login area -->
<div class="login-area py-120">
    <div class="container">
        <div class="col-md-5 mx-auto">
            <div class="login-form">

                {{-- Header --}}
                <div class="login-header text-center mb-4">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" alt="logo" width="120">
                    <p>Login with your account</p>
                </div>

                {{-- Success / Error --}}
                @if(session('message'))
                    <div class="alert alert-info">{{ session('message') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

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
                               placeholder="Enter your password"
                               required>
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="d-flex justify-content-between mb-4">

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="remember"
                                   id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-pass">
                                Forgot Password?
                            </a>
                        @endif

                    </div>

                    {{-- Button --}}
                    <div class="d-flex align-items-center">
                        <button type="submit" class="theme-btn w-100">
                            <i class="far fa-sign-in"></i> Login
                        </button>
                    </div>

                </form>

                {{-- Footer --}}
                <div class="login-footer text-center mt-4">
                    <p>
                        Don't have an account?
                        <a href="{{ route('register') }}">Register</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection