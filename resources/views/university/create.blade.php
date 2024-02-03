@extends('layouts.main')

@section('title', 'UniSpace | Registration')

@section('content')
    <div class="container">
        <form action="{{ route('university.store') }}" method="post">
            <h2 class="title">Реєстрація ВНЗ</h2>

            @csrf
            <div class="registration-form">
                <div class="reg-step-first">
                    <h3 class="sub-title">Інформація про ВНЗ</h3>

                    <div class="form-group">
                        <label for="name">Повна назва навчального закладу:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">

                        @if($errors->has('name'))
                            <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="city">Місто:</label>
                        <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}">

                        @if($errors->has('city'))
                            <div class="alert alert-danger">{{ $errors->first('city') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="address">Адреса:</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">

                        @if($errors->has('address'))
                            <div class="alert alert-danger">{{ $errors->first('address') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Номер телефону:</label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">

                        @if($errors->has('phone_number'))
                            <div class="alert alert-danger">{{ $errors->first('phone_number') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="email">Електронна пошта:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">

                        @if($errors->has('email'))
                            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="accreditation_level">Рівень акредитації:</label>
                        <select name="accreditation_level" id="accreditation_level" class="form-control">
                            @foreach($accreditationLevels as $level => $levelName)
                                <option
                                    value="{{ $level }}" {{ old('accreditation_level') == $level ? 'selected' : '' }}>{{ $levelName }}</option>
                            @endforeach
                        </select>

                        @if($errors->has('accreditation_level'))
                            <div class="alert alert-danger">{{ $errors->first('accreditation_level') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="founded_at">Заснований:</label>
                        <input type="date" name="founded_at" id="founded_at" class="form-control" value="{{ old('founded_at') }}">

                        @if($errors->has('founded_at'))
                            <div class="alert alert-danger">{{ $errors->first('founded_at') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="website">Веб-сайт:</label>
                        <input type="url" name="website" id="website" class="form-control" value="{{ old('website') }}">

                        @if($errors->has('website'))
                            <div class="alert alert-danger">{{ $errors->first('website') }}</div>
                        @endif
                    </div>
                </div>

                <div class="reg-step-second">
                    <h3 class="sub-title">Інформація про адміністратора ВНЗ</h3>

                    <div class="form-group">
                        <label for="first_name">Ім'я:</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}">

                        @if($errors->has('first_name'))
                            <div class="alert alert-danger">{{ $errors->first('first_name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="last_name">Прізвище:</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}">

                        @if($errors->has('last_name'))
                            <div class="alert alert-danger">{{ $errors->first('last_name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="user_phone_number">Номер телефону:</label>
                        <input type="tel" name="user_phone_number" id="user_phone_number" class="form-control" value="{{ old('user_phone_number') }}">

                        @if($errors->has('user_phone_number'))
                            <div class="alert alert-danger">{{ $errors->first('user_phone_number') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="user_email">Електронна пошта:</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" value="{{ old('user_email') }}">

                        @if($errors->has('user_email'))
                            <div class="alert alert-danger">{{ $errors->first('user_email') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn save-btn">Надіслати заяву на реєстрацію</button>
            </div>
        </form>
    </div>
@endsection
