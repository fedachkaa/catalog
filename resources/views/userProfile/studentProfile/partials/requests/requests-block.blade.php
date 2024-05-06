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
        @if(!empty($topicRequests))
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
                    @foreach($topicRequests as $request)
                        <tr>
                            <td>{{ $request['id'] }}</td>
                            <td>{{ \App\Models\Catalog::AVAILABLE_CATALOG_TYPES[$request['topic']['catalog']['type']] }}</td>
                            <td>{{ $request['topic']['topic'] }}</td>
                            <td>{{ $request['topic']['teacher']['user']['full_name'] }}</td>
                            <td>{{ $request['status_text'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Ще немає запитів.</p>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/student/topic-requests.js')}}"></script>
@endpush


