@extends('user.layout')

@section('account-title', 'Edit Profile')
@section('account-subtitle', 'Update your personal information and delivery address.')

@section('account-content')

<div class="user-card">
    <div class="user-card-header">Profile Information</div>
    <div class="user-card-body">

        <form method="POST" action="{{ route('account.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="user-field">
                        <label>Full Name</label>
                        <div class="user-input-wrap">
                            <i class="far fa-user"></i>
                            <input type="text" name="name" class="user-input" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="user-field">
                        <label>Email Address</label>
                        <div class="user-input-wrap">
                            <i class="far fa-envelope"></i>
                            <input type="email" name="email" class="user-input" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="user-field">
                        <label>Phone Number</label>
                        <div class="user-input-wrap">
                            <i class="far fa-phone"></i>
                            <input type="text" name="phone" class="user-input" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +91 98765 43210">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="user-field">
                        <label>Referral Code</label>
                        <div class="user-input-wrap">
                            <i class="far fa-gift"></i>
                            <input type="text" class="user-input text-uppercase" value="{{ $user->referral_code }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="user-field mb-0">
                        <label>Delivery Address</label>
                        <textarea name="address" rows="3" class="user-textarea" placeholder="Your default delivery address">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="user-btn">
                    <i class="far fa-check"></i> Save Changes
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
