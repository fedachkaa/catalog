<?php
/**
 * @var array $catalogData
 * @var array $faculties
 * @var array $courses
 * @var array $groups
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
                <label>Тип каталогу:</label>
                <select class="form-control js-catalog-type">
                    <?php foreach (\App\Models\Catalog::AVAILABLE_CATALOG_TYPES as $key => $value): ?>
                    <option value="<?= $key; ?>" <?= $catalogData['type'] === $key ? 'selected' : ''; ?>><?= $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex flex-row w-full">
                <input type="checkbox" class="js-is-active form-checkbox" <?= $catalogData['is_active'] ? 'checked' : ''; ?>>
                <label>Активувати</label>
            </div>
        </div>

        <div class="flex flex-row w-full mb-4">
            <div class="flex flex-col w-full">
                <label>Факультет:</label>
                <select class="form-control js-faculty">
                    <?php foreach ($faculties as $faculty): ?>
                        <option value="<?= $faculty['id']; ?>" <?= $catalogData['faculty']['id'] === $faculty['id'] ? 'selected' : ''; ?>><?= $faculty['title']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex flex-col w-full">
                <label>Курс:</label>
                <select class="form-control js-course">
                    <option value="<?= $catalogData['course']['id']; ?>"><?= $catalogData['course']['course'] . ' курс'; ?></option>
                </select>
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
                    <select class="form-control js-teachers"></select>
                </div>
                <div class="js-teachers-list">
                    <ul>
                        <?php foreach ($catalogData['supervisors'] as $supervisor) : ?>
                        <li data-userid="<?= $supervisor['user_id']; ?>">
                            <?= $supervisor['user']['full_name']; ?>
                            <i class="fas fa-times js-delete-teacher"></i>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex flex-col mb-4">
            <?php if (!empty($catalogData['topics'])) : ?>
                <table id="topics-table" class="table-block">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Тема</th>
                    <th>Науковий керівник</th>
                    <th>Студент</th>
                    <th>Дії</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($catalogData['topics'] as $topic) : ?>
                        <tr data-topicid="<?= $topic['id']; ?>">
                            <td><?= $topic['id']; ?></td>
                            <td class="js-single-topic-topic"><?= $topic['topic']; ?></td>
                            <td class="js-single-topic-teacher" data-teacherid="<?= $topic['teacher']['user_id']; ?>"><?= $topic['teacher']['user']['full_name']; ?></td>
                            <?php if(!empty($topic['student'])) : ?>
                                <td class="js-single-topic-student" data-studentid="<?= $topic['student']['user_id']; ?>"><?= $topic['student']['user']['full_name']; ?></td>
                            <?php else: ?>
                                <td class="js-single-topic-student">-</td>
                            <?php endif; ?>
                            <td>
                                <i class="fas fa-edit action-icon js-edit-topic" title="Редагувати"></i>
                                <i class="fa-solid fa-person-circle-question" title="Переглянути запити"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                Тем ще немає.
            <?php endif; ?>

            <div class="mt-2">
                <i class="fas fa-circle-plus action-icon text-3xl js-add-topic" title="Додати тему"></i>
            </div>
        </div>

        <div class="flex justify-end mt-2 me-4 mb-4">
            <button class="add-user-btn js-update-catalog" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>

    @include('universityAdminProfile.partials.catalogs.add-topic-modal', ['catalogData' => $catalogData])
@endsection

@push('scripts')
    <script src="{{ asset('js/universityAdminProfile/edit-catalog.js')}}"></script>
@endpush


