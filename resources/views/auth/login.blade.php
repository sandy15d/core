
@extends('layouts.guest_layout')
@section('content')
    <div class="auth-container">
        <h2>{{ __('Login') }}</h2>
        <form method="POST" action="{{ route('login') }}" class="form-page validate-form" novalidate>
            <div hidden>
                @csrf   
            </div>
            @if(session()->has('error'))
                <div class="danger-text">{{ session()->get('error') }}</div>
            @endif
            <div class="input-container">
                <div class="input-label">
                    <label for="email">{{ __('Email Address') }}</label>
                </div>
                <div class="input-data">
                    <input type="email" required id="email" name="email" class="form-input" placeholder="Enter Email" value="{{ old('email') }}" autofocus>
                    <div class="error-message">Email is required</div>
                     @error('email')
                                    <span class="danger-text" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
            </div>
            <div class="input-container">
                <div class="input-label">
                    <label for="password">{{ __('Password') }}</label>
                </div>
                <div class="input-data">
                    <input type="password" required id="password" name="password" class="form-input" placeholder="Enter Password">
                    <div class="error-message">
                       Password is required
                    </div>
                </div>
            </div>
            <div class="auth-remember">
                <div class="checkbox-container">
                    <label class="checkbox-input" for="remember">
                        <input type="checkbox" class="form-checkbox" value="1" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                       {{ __('Remember Me') }}
                    </label>
                </div>
                <div>
                    @if(config('admin.settings.allow_login_password_reset'))
                        <a href="{{ route("admin.login.email") }}"> {{ __('Forgot Your Password?') }}</a>
                    @endif
                </div>
            </div>
            <div class="auth-submit">
                <button type="submit" class="button primary-button js-ak-submit-button">
                    <span>
                        @includeIf("admin.admin_layout/partials/misc/loading")
                      {{ __('Login') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
@endsection
