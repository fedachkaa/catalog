@extends('layouts.main')

@section('title', 'Reset password | UniSpace')

@section('content')
    <div class="reset-password-block">
        <form method="POST" action="{{ route('reset.password.post') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="reset-password-form">
                <div class="">
                    <label for="email">Електронна пошта</label>
                    <input type="email" name="email" class="form-control-reset">
                    @if ($errors->has('email'))
                        <span class="error-message">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" class="form-control-reset">
                    @if ($errors->has('password'))
                        <span class="error-message">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="">
                    <label for="password_confirmation">Підтвердження паролю</label>
                    <input type="password" name="password_confirmation" class="form-control-reset">
                    @if ($errors->has('password_confirmation'))
                        <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
                <button type="submit" class="save-btn">Зберегти</button>
            </div>
        </form>
    </div>
@endsection
