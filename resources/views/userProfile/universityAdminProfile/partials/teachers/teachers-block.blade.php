@extends('layouts.main')

@section('title', 'Teachers | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block">
            <button class="add-user-btn js-add-teacher">Додати викладача</button>
        </div>
        <div>
            <table id="teachers-table" class="table-block" data-token="{{ csrf_token() }}">
                <thead>
                <tr>
                    <th>№</th>
                    <th>ПІБ</th>
                    <th>Факультет</th>
                    <th>Предмети</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                <!-- Table body will be populated dynamically -->
                </tbody>
            </table>
            @include('general.pagination')
        </div>
        <div class="js-teachers-message"></div>
    </div>

    @include('userProfile.universityAdminProfile.partials.teachers.add-edit-teacher-modal')
    @include('general.user-info-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/teachers.js')}}"></script>
@endpush
