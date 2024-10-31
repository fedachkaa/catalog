<?php
/**
 * @var array $topicsData
 */
?>
@extends('layouts.main')

@section('title', 'Topics | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')
    <div class="pl-60">
        <ol>
            <li>Популярні/непопулярні</li>
            <li>Простіші/складніші</li>
            <li>Відслідковування частоти запитів за ключовими словами і на їх основі прогнозування популярних тем в майбутньому</li>
            <li>Відслідковування часу виконання роботи - середній час, необхідний для виконання різних тем, що може допомогти в оцінці їх складності</li>
        </ol>

        <h1 class="title">Теми</h1>
        <table class="table-block">
            <thead>
                <tr>
                    <th>Тема</th>
                    <th>Викладач</th>
                    <th>Згенеровано ШІ</th>
                    <th>Ключове слово</th>
                    <th>К-ть запитів</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topicsData as $topic)
                    <tr>
                        <td>{{ $topic['topic'] }}</td>
                        <td>{{ $topic['teacher']['user']['full_name'] }}</td>
                        <td>{{ $topic['is_ai_generated'] ? '+' : '' }}</td>
                        <td>{{ $topic['keyword'] }}</td>
                        <td>{{ count($topic['requests']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
