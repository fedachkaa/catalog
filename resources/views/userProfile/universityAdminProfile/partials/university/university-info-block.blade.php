<?php
/**
 * @var array $university
 */
?>
@extends('layouts.main')

@section('title', 'University | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="js-university-name text-3xl">{{ $university['name'] }}</div>
        <div class="js-university-acc-level text-3xl">{{ \App\Models\University::AVAILABLE_ACCREDITATION_LEVELS[$university['accreditation_level']] }}</div>
        <div class="js-university-founded-at text-3xl"></div>

        <div class="address-info">
            <div>Адреса</div>
            <div>Місто: <span class="js-city">{{ $university['city'] ?? '' }}</span></div>
            <div>Адреса: <span class="js-address">{{ $university['address'] ?? '' }}</span></div>
        </div>

        <div class="contact-info">
            <div>Контактна інформація</div>
            <div>Електронна пошта: <span class="js-email">{{ $university['email'] ?? '' }}</span></div>
            <div>Номер телефону: <span class="js-phone-number">{{ $university['phone_number'] ?? '' }}</span></div>
            <div>Веб-сайт: <span class="js-website"><a href="{{ $university['website'] }}" target="_blank">{{ $university['website'] }}</a></span></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/university.js')}}"></script>
@endpush
