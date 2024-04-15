<?php
/**
 * @var array $university
 */
?>
@extends('layouts.main')

@section('title', 'University | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-52">
        <div class="js-university-name text-3xl"><?= $university['name']; ?></div>
        <div class="js-university-acc-level text-3xl"><?= \App\Models\University::AVAILABLE_ACCREDITATION_LEVELS[$university['accreditation_level']]; ?></div>
        <div class="js-university-founded-at text-3xl"></div>

        @include('general.addressInfo--block', [
            'city' => $university['city'],
            'address' => $university['address'],
        ])

        @include('general.contactInfo--block', [
            'email' => $university['email'],
            'phoneNumber' => $university['phone_number'],
            'website' => $university['website'],
        ])
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/university.js')}}"></script>
@endpush
