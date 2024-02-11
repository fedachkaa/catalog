@extends('layouts.main')

@section('title', 'Forget password | UniSpace')

@section('content')
    <div class="reset-password-block">
        <form method="POST" action="{{ route('forget.password.post') }}">
            @csrf
            <div class="forget-password-form">
                <div class="">
                    <label for="email">Електронна пошта</label>
                    <input type="email" name="email" class="form-control">
                    <p class="error-message email-message">
                </div>
                <button type="submit" class="save-btn">Надіслати посилання для скидання паролю</button>
            </div>
        </form>
    </div>
@endsection
