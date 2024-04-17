@extends('layouts.main')

@section('title', 'Catalogs | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-56">
        <div class="faculties-block js-catalogs-container">
            <button class="add-user-btn js-add-catalog">Додати каталог</button>

            <div>
                @include('general.catalogs.catalogs-table')
            </div>
        </div>
    </div>

    @include('userProfile.universityAdminProfile.partials.catalogs.add-catalog-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/catalogs.js')}}"></script>
@endpush


