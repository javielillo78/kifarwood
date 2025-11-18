@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@php
    $passResetUrl = trim($__env->yieldContent('password_reset_url')) ?: config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.password_reset_message'))

@section('auth_body')
    <form action="{{ $passResetUrl }}" method="post">
        @csrf

        {{-- Token field --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            {{-- <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div> --}}
            <div class="input-group-append">
                <div class="input-group-text" id="togglePassword" style="cursor: pointer;">
                    <span class="fas fa-eye"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password confirmation field --}}
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="{{ trans('adminlte::adminlte.retype_password') }}">

            {{-- <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div> --}}
            <div class="input-group-append">
                <div class="input-group-text" id="togglePasswordConfirm" style="cursor: pointer;">
                    <span class="fas fa-eye"></span>
                </div> 
            </div>

            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Confirm password reset button --}}
        <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
            <span class="fas fa-sync-alt"></span>
            {{ __('adminlte::adminlte.reset_password') }}
        </button>
    </form>
@stop
@section('adminlte_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.querySelector('input[name="password"]');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';

                    const icon = this.querySelector('span');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirmInput = document.querySelector('input[name="password_confirmation"]');
            if (togglePasswordConfirm && passwordConfirmInput) {
                togglePasswordConfirm.addEventListener('click', function () {
                    const isPassword = passwordConfirmInput.type === 'password';
                    passwordConfirmInput.type = isPassword ? 'text' : 'password';

                    const icon = this.querySelector('span');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            }
        });
    </script>
@endsection