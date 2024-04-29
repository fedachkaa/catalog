@extends('layouts.main')

@section('title', 'Subjects | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block">
            <button class="add-user-btn js-add-subject">Додати предмет</button>
        </div>
        <div>
            <table id="subjects-table" class="table-block">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Предмет</th>
                    <th>Викладачі</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                <!-- Table body will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    @include('userProfile.universityAdminProfile.partials.subjects.add-edit-subject-modal');
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/subjects.js')}}"></script>
@endpush


