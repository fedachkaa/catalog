<?php
/**
 * @var array $subjectsData
 */
?>
@extends('layouts.main')

@section('title', 'Subjects | UniSpace')

@section('content')
    @include('userProfile.teacherProfile.partials.sidebar-template')

    <div class="pl-60">
        <h1 class="title">Предмети</h1>
        <ol class="list-decimal text-xl flex flex-col gap-2 items-center m-5">
            @foreach($subjectsData as $subject)
                <ul class="list-item subject-item">{{ $subject['title'] }}</ul>
            @endforeach
        </ol>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/teacher/subjects.js')}}"></script>
@endpush
