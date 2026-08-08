@extends('user.layout')

@section('account-title', 'Change Password')
@section('account-subtitle', 'Keep your account secure with a strong password.')

@section('account-content')

<div class="user-card" style="max-width:560px;">
    <div class="user-card-header">Change Password</div>
    <div class="user-card-body">

        <form method="POST" action="{{ route('account.password.update') }}">
            @csrf
            @method('PUT')

            <div class="user-field">
                <label>Current Password</label>
                <div class="user-input-wrap">
                    <i class="far fa-lock"></i>
                    <input type="password" name="current_password" class="user-input" required>
                </div>
            </div>
            <div class="user-field">
                <label>New Password</label>
                <div class="user-input-wrap">
                    <i class="far fa-key"></i>
                    <input type="password" name="password" class="user-input" minlength="8" required>
                </div>
                <span class="user-field-hint">Minimum 8 characters.</span>
            </div>
            <div class="user-field">
                <label>Confirm New Password</label>
                <div class="user-input-wrap">
                    <i class="far fa-key"></i>
                    <input type="password" name="password_confirmation" class="user-input" minlength="8" required>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="user-btn">
                    <i class="far fa-lock"></i> Update Password
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
