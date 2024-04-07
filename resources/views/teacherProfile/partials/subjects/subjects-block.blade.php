<?php
/**
 * @var array $subjectsData
 */
?>
@extends('layouts.main')

@section('title', 'Subjects | UniSpace')

@section('content')
    @include('teacherProfile.partials.sidebar-template')

    <div class="pl-48">
        <div>
            <table id="subjects-table" class="table-block">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Предмет</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjectsData as $key => $subject): ?>
                        <tr data-subjectid="<?= $subject['id']; ?>" class="<?= $key % 2 === 0 ? 'row-gray' : 'row-beige'?>">
                            <td><?= $subject['id']; ?></td>
                            <td><?= $subject['title']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/subjects/subjects.js')}}"></script>
@endpush
