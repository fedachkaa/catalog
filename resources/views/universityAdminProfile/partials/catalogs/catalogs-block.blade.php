@extends('layouts.main')

@section('title', 'Catalogs | UniSpace')

@section('content')
    @include('universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block js-catalogs-container">
            <button class="add-user-btn js-add-catalog">Додати каталог</button>

            <div>
                <table id="catalogs-table" class="table-block">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Тип</th>
                        <th>Факультет</th>
                        <th>Курс</th>
                        <th>Групи</th>
                        <th>Наукові керівники</th>
                        <th>Створено</th>
                        <th>Дії</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Table body will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('universityAdminProfile.partials.catalogs.add-catalog-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/catalogs.js')}}"></script>
@endpush


