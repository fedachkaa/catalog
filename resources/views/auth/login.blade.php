@extends('layouts.main')

@section('title', 'UniSpace | Login')

@section('content')
    <form action="{{ route('authenticate') }}" method="post">
        <h2 class="title">Логін</h2>

        @csrf
        <div class="form-group">
            <label for="email">Електронна пошта:</label>
            <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}">

            @if($errors->has('email'))
                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" id="password" class="form-control" value="{{ old('password') }}">

            @if($errors->has('password'))
                <div class="alert alert-danger">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn save-btn">Увійти</button>
        </div>

        <div class="reset-link">
            <a href="{{ route('forget.password.get') }}" target="_blank" class="js-reset-password">Забули пароль?</a>
        </div>
    </form>
@endsection
