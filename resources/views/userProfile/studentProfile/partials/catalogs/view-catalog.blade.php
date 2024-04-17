<?php
/**
 * @var array $user
 * @var array $catalogData
 */
?>

@extends('layouts.main')

@section('title', 'View catalog | UniSpace')

@section('content')
    @include('userProfile.studentProfile.partials.sidebar-template')

    <div class="pl-56 js-edit-catalog-block" data-catalogid="<?= $catalogData['id']; ?>">
        <a href="/university/<?= $user['university']['id']; ?>/catalogs">
            <i class="fa fa-circle-arrow-left text-3xl mt-4 action-icon" title="Повернутись"></i>
        </a>

        <h2 class="title">Перегляд каталогу</h2>
        <div class="flex flex-row w-full items-center mb-4">
            <div class="flex flex-col w-full">
                <h1>Тип каталогу: <span
                        class="font-bold"><?= \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$catalogData['type']]; ?></span>
                </h1>
            </div>
        </div>

        <div class="flex flex-row w-full mb-4">
            <div class="flex flex-col w-full js-faculty" data-facultyid="<?= $catalogData['faculty']['id']; ?>">
                <h1>Факультет: <span class="font-bold"><?= $catalogData['faculty']['title']; ?></span></h1>
            </div>
        </div>
        <div class="flex flex-row w-full mb-4">
            <div class="flex flex-col w-full js-course" data-courseid="<?= $catalogData['course']['id']; ?>">
                <h1>Курс: <span class="font-bold"><?= $catalogData['course']['course'] . ' курс'; ?></span></h1>
            </div>
        </div>

        @include('general.catalogs.topics-table', ['topics' => $catalogData['topics']])
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/student/catalogs.js')}}"></script>
@endpush


