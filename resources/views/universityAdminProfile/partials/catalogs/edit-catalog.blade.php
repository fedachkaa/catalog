<?php
/**
 * @var array $user
 * @var array $catalogData
 */
?>

@extends('layouts.main')

@section('title', 'Edit catalog | UniSpace')

@section('content')
    @include('universityAdminProfile.partials.sidebar-template')

    <div class="pl-56 js-edit-catalog-block" data-catalogid="<?= $catalogData['id']; ?>">
        <a href="/university/<?= $user['university']['id']; ?>/catalogs">
            <i class="fa fa-circle-arrow-left text-3xl mt-4 action-icon" title="Повернутись"></i>
        </a>

        <h2 class="title">Редагування каталогу</h2>
        <div class="flex flex-row w-full items-center mb-4">
            <div class="flex flex-col w-full">
                <h1>Тип каталогу: <span
                        class="font-bold"><?= \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$catalogData['type']]; ?></span>
                </h1>
            </div>
            <div class="flex flex-row w-full">
                <input type="checkbox"
                       class="js-is-active form-checkbox" <?= $catalogData['is_active'] ? 'checked' : ''; ?>>
                <label>Активувати</label>
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

        <div class="flex flex-row w-full mb-4">
            <div class="flex flex-col w-full">
                <div class="flex flex-col w-full">
                    <label>Групи:</label>
                    <select class="form-control js-groups"></select>
                </div>
                <div class="js-groups-list">
                    <ul>
                        <?php foreach ($catalogData['groups'] as $group) : ?>
                        <li data-groupid="<?= $group['id']; ?>">
                                <?= $group['title']; ?>
                            <i class="fas fa-times js-delete-group"></i>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col w-full">
                <div class="flex flex-col w-full">
                    <label>Наукові керівники:</label>
                    <select class="form-control js-teachers-select"></select>
                </div>
                <div class="js-teachers-list">
                    <ul>
                        <?php foreach ($catalogData['supervisors'] as $supervisor) : ?>
                        <li data-teacherid="<?= $supervisor['user_id']; ?>">
                                <?= $supervisor['user']['full_name']; ?>
                            <i class="fas fa-times js-delete-teacher"></i>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        @include('general.catalogs.topics-table', ['topics' => $catalogData['topics']])

        <div class="flex justify-end mt-2 me-4 mb-4">
            <button class="add-user-btn js-update-catalog" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>

    @include('general.catalogs.add-topic-modal', ['catalogData' => $catalogData])
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/edit-catalog.js')}}"></script>
@endpush


