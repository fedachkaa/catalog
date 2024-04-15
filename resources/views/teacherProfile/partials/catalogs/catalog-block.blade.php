@extends('layouts.main')

@section('title', 'Catalogs | UniSpace')

@section('content')
    @include('teacherProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block js-catalogs-container">
            <div>
                @include('general.catalogs.catalogs-table')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/teacher/catalogs.js')}}"></script>
@endpush
