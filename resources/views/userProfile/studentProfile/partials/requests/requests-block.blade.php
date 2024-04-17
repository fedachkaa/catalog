<?php
/**
 * @var array $user
 * @var array $topicRequests
 */
?>


@extends('layouts.main')

@section('title', 'Requests | UniSpace')

@section('content')
    @include('userProfile.studentProfile.partials.sidebar-template')
    <div class="pl-56">
        <table id="topics-table" class="table-block">
            <thead>
                <tr>
                    <th>№</th>
                    <th>Каталог</th>
                    <th>Тема</th>
                    <th>Науковий керівник</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topicRequests as $request) : ?>
                    <tr>
                        <td><?= $request['id']; ?></td>
                        <td><?= \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$request['topic']['catalog']['type']]; ?></td>
                        <td><?= $request['topic']['topic']; ?></td>
                        <td><?= $request['topic']['teacher']['user']['full_name']; ?></td>
                        <td><?= $request['status_text']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/student/topic-requests.js')}}"></script>
@endpush


