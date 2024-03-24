@extends('layouts.main')

@section('title', 'Students | UniSpace')

@section('content')
    @include('universityAdminProfile.partials.sidebar-template')

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
            <div class="search-tool">
                <div class="search-tool-title">Шукати студента</div>
                <div class="search-tool-body">
                    <div class="search-field">
                        <label for="surname">Прізвище:</label>
                        <input type="text" id="surname" name="surname">
                    </div>

                    <div class="search-field">
                        <label for="faculty">Факультет:</label>
                        <input type="text" id="faculty" name="faculty">
                    </div>

                    <div class="search-field">
                        <label for="course">Курс:</label>
                        <input type="text" id="course" name="course">
                    </div>

                    <div class="search-field">
                        <label for="group">Група:</label>
                        <input type="text" id="group" name="group">
                    </div>

                    <div class="search-field">
                        <label for="email">Пошта:</label>
                        <input type="email" id="email" name="email">
                    </div>
                </div>

                <div class="search-tool-button">
                    <button type="button" class="save-btn js-search-students">Пошук</button>
                </div>
            </div>
        </div>

        <div>
            <table id="students-table" class="table-block">
                <thead>
                <tr>
                    <th>№</th>
                    <th>ПІБ</th>
                    <th>Факультет</th>
                    <th>Курс</th>
                    <th>Група</th>
                </tr>
                </thead>
                <tbody>
                <!-- Table body will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    @include('universityAdminProfile.partials.students.add-students-modal')
    @include('universityAdminProfile.partials.students.import-students-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/students.js')}}"></script>
@endpush


