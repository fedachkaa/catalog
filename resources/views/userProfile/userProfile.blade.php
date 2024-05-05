<?php
/**
 * @var array $user
 */
?>
@extends('layouts.main')

@section('title', 'Profile | UniSpace')

@section('content')
    @switch(auth()->user()->getRoleId())
        @case(\App\Models\UserRole::USER_ROLE_UNIVERSITY_ADMIN)
            @include('userProfile.universityAdminProfile.profile', ['userData' => $user])
            @break

        @case(\App\Models\UserRole::USER_ROLE_STUDENT)
            @include('userProfile.studentProfile.profile')
            @break

        @case(\App\Models\UserRole::USER_ROLE_TEACHER)
            @include('userProfile.teacherProfile.profile', ['userData' => $user])
            @break

        @case(\App\Models\UserRole::USER_ROLE_ADMIN)
            @include('userProfile.partials.adminProfile')
            @break

        @default
            @include('404NotFound')
    @endswitch
@endsection

@push('scripts')
    <script src="{{ asset('js/common/profile.js')}}"></script>
@endpush
