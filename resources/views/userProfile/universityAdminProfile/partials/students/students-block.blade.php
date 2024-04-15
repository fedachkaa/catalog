@extends('layouts.main')

@section('title', 'Students | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-52">
        <div class="flex flex-row gap-1">
            <div class="flex flex-col mx-2">
                <div class="js-manual-add">
                    <button class="add-user-btn js-add-student">Додати студента</button>
                </div>
                <div class="js-import-add">
                    <button class="add-user-btn js-import-students">Імпорт з файлу</button>
                </div>
            </div>
            @include('general.students.students-search-tool')
        </div>

        <div>
            @include('general.students.students-table')
        </div>
    </div>

    @include('userProfile.universityAdminProfile.partials.students.add-students-modal')
    @include('userProfile.universityAdminProfile.partials.students.import-students-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/students.js')}}"></script>
@endpush


