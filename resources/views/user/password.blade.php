@extends('user.layout')

@section('account-title', 'Change Password')

@section('account-content')

<div class="card border-0 shadow-sm" style="border-radius:14px;max-width:520px;">
    <div class="card-header bg-white fw-bold">Change Password</div>
    <div class="card-body p-4">

        <form method="POST" action="{{ route('account.password.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Current Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="far fa-lock"></i></span>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="far fa-key"></i></span>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
                <small class="text-muted">Minimum 8 characters.</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Confirm New Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="far fa-key"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="theme-btn">
                    <i class="far fa-lock"></i> Update Password
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
