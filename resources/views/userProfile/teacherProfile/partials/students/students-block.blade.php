@extends('layouts.main')

@section('title', 'Students | UniSpace')

@section('content')
    @include('userProfile.teacherProfile.partials.sidebar-template')

    <div class="pl-52">
        <div class="flex flex-row gap-1">
            @include('general.students.students-search-tool')
        </div>

        <div>
            @include('general.students.students-table')
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/teacher/students.js')}}"></script>
@endpush


