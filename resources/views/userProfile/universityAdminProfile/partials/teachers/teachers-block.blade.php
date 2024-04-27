@extends('layouts.main')

@section('title', 'Teachers | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block js-faculties-container">
            <button class="add-user-btn js-add-teacher">Додати викладача</button>
        </div>
        <div>
            <table id="teachers-table" class="table-block">
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
        </div>
    </div>

    @include('userProfile.universityAdminProfile.partials.teachers.add-teacher-modal')
    @include('general.user-info-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/teachers.js')}}"></script>
@endpush
