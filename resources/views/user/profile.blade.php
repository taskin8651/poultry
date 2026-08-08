@extends('user.layout')

@section('account-title', 'Edit Profile')

@section('account-content')

<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-white fw-bold">Profile Information</div>
    <div class="card-body p-4">

        <form method="POST" action="{{ route('account.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="far fa-user"></i></span>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="far fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="far fa-phone"></i></span>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +91 98765 43210">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Referral Code</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="far fa-gift"></i></span>
                        <input type="text" class="form-control text-uppercase" value="{{ $user->referral_code }}" disabled>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Delivery Address</label>
                    <textarea name="address" rows="3" class="form-control" placeholder="Your default delivery address">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="theme-btn">
                    <i class="far fa-check"></i> Save Changes
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
