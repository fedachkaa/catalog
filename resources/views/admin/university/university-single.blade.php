@extends('layouts.admin')

@section('title', 'UniSpace | AdminPanel')

@section('content')
<div class="">
    <div>Назва: {{ $university['name'] }} </div>
    <div>Місто: {{ $university['city'] }} </div>
    <div>Адреса: {{ $university['address'] }} </div>
    <div>Номер телефону: {{ $university['phone_number'] }} </div>
    <div>Електронна пошта: {{ $university['email'] }} </div>
    <div>Заснований: {{ $university['founded_at'] }} </div>
    <div>Веб-сайт: {{ $university['website'] }} </div>

    Заява надіслана від:
    <div>{{ $universityAdmin['first_name'] }} {{ $universityAdmin['last_name'] }}</div>
    <div>{{ $universityAdmin['email'] }}</div>
    <div>{{ $universityAdmin['phone_number'] }}</div>

    <form action="{{ route('university.activation', ['id' => $university['id']]) }}" method="POST">
        <button type="submit" class="save-btn">Активувати</button>
    </form>
    <a href="#" class="save-btn">Відхилити</a>
</div>
@endsection
