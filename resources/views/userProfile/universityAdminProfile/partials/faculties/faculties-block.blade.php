@extends('layouts.main')

@section('title', 'Faculties | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block js-faculties-container">
            <button class="add-user-btn js-add-faculty">Додати факультет</button>
            <button class="add-user-btn js-save-faculty hidden" data-token="{{ csrf_token() }}">Зберегти</button>

            <div>
                <table id="faculties-table" class="table-block">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Факультет</th>
                            <th>Курси</th>
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- Table body will be populated dynamically -->
                    </tbody>
                </table>

                @include('general.pagination')
            </div>
        </div>
    </div>
    @include('userProfile.universityAdminProfile.partials.faculties.add-edit-faculty-modal')
    @include('userProfile.universityAdminProfile.partials.faculties.add-course-modal')
    @include('userProfile.universityAdminProfile.partials.faculties.course-info-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/faculties.js')}}"></script>
@endpush


