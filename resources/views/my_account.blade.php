@extends('layouts.app')
@push('breadcrumb')
    <li class="breadcrumb-item active">My Account</li>
@endpush
@push('page-title')
    My Account
@endpush

@section('content')
    <div class="form-container content-width-small">
        <form method="POST" action="{{ route('my-account.update') }}" enctype="multipart/form-data"
            class="form-page validate-form" novalidate>
            <div hidden>
                @method('PUT')
                @csrf
            </div>
            <div class="form-header">
                <h3>Update your account</h3>
            </div>
            <div class="form-content">
                <div class="row-100">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="name">Name<span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="text" class="form-input" id="name" name="name" required
                                autocomplete="off" placeholder="Name" value="{{ old('name', auth()->user()->name) }}">
                            <div class="error-message @if ($errors->has('name')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
                <div class="row-100">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="email">Email<span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="email" autocomplete="off" class="form-input" id="email" name="email"
                                required placeholder="Email" value="{{ old('email', auth()->user()->email) }}">
                            <div class="error-message @if ($errors->has('email')) show @endif">Required!</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <div class="form-buttons-container">
                    <div>
                        <button type="submit" class="button primary-button submit-button js-ak-submit-button">
                            <span>
                                <span class="loading-indicator">
                                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                                Save
                            </span>
                        </button>
                    </div>

                    <div>
                        <a href="" class="button cancel-button">Cancle</a>
                    </div>

                </div>
            </div>

        </form>
    </div>
    <div class="form-container content-width-small">
        <form method="POST" action="{{ route('my-account.update_password') }}" enctype="multipart/form-data"
            class="form-page validate-form" novalidate>
            <div hidden>
                @method('PUT')
                @csrf
            </div>
            <div class="form-header">
                <h3>Change Password</h3>
            </div>
            @if ($errors->any())
                <div class="alert-danger-container">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-content">
                <div class="row-100 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="password">Password<span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="password" class="form-input" autocomplete="new-password" id="password"
                                name="password" required placeholder="Password" value="{{ old('password' ?? '') }}">
                            <div class="error-message">
                                Required!
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-100 el-box-text">
                    <div class="input-container">
                        <div class="input-label">
                            <label for="password_confirmation">Confirm Password<span class="required">*</span></label>
                        </div>
                        <div class="input-data">
                            <input type="password" class="form-input" autocomplete="new-password" id="password_confirmation"
                                required name="password_confirmation" placeholder="Confirm Password"
                                value="{{ old('password_confirmation' ?? '') }}">
                            <div class="error-message">
                                Required!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <div class="form-buttons-container">
                    <div>
                        <button type="submit" class="button primary-button submit-button js-ak-submit-button">
                            <span>
                                <span class="loading-indicator">
                                    <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                                Save
                            </span>
                        </button>
                    </div>
                    <div>
                        <a href="" class="button cancel-button">Cancle</a>
                    </div>

                </div>
            </div>

        </form>
    </div>
@endsection
