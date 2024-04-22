<?php
/**
 * @var array $universityData
 */
?>


@extends('layouts.admin')

@section('title', 'UniSpace | AdminPanel')

@section('content')
<div class="js-university-single" data-universityid="{{ $universityData['id'] }}">
    <div class="flex flex-row text-xl p-4">
        <div class="flex flex-col gap-3 w-1/2">
            <div><span class="font-bold">Назва: </span>{{ $universityData['name'] }}</div>
            <div><span class="font-bold">Місто: </span>{{ $universityData['city'] }} </div>
            <div><span class="font-bold">Адреса: </span>{{ $universityData['address'] }} </div>
            <div><span class="font-bold">Номер телефону: </span>{{ $universityData['phone_number'] }} </div>
            <div><span class="font-bold">Електронна пошта: </span><a href="mailto:{{ $universityData['email'] }}">{{ $universityData['email'] }}</a></div>
            <div><span class="font-bold">Заснований: </span>{{ date('d M Y', strtotime($universityData['founded_at'])) }} </div>
            <div><span class="font-bold">Веб-сайт: </span><a href="{{ $universityData['website'] }}" target="_blank">{{ $universityData['website'] }}</a></div>
        </div>
        <div class="flex flex-col gap-3 w-1/2">
            Заява надіслана від:
            <div><span class="font-bold">ПІБ: </span>{{ $universityData['universityAdmin']['full_name'] }}</div>
            <div><span class="font-bold">Електронна пошта: </span><a href="mailto:{{ $universityData['universityAdmin']['email'] }}">{{ $universityData['universityAdmin']['email'] }}</a></div>
            <div><span class="font-bold">Номер телефону: </span>{{ $universityData['universityAdmin']['phone_number'] }}</div>
        </div>
    </div>
    <div class="flex flex-row justify-end gap-4 m-4" data-token="{{ csrf_token() }}">
        <a class="save-btn js-approve-university" data-approved="1">Approve</a>
        <a class="remove-btn js-reject-university" data-approved="0">Decline</a>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/universitySingle.js')}}"></script>
@endpush
