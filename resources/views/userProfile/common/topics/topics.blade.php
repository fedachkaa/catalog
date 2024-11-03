<?php
/**
 * @var array $topicsData
 */
?>
@extends('layouts.main')

@section('title', 'Topics | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        var mostPopularAnalyticsType = `{{ \App\Services\TopicAnalyticsService::ANALYTICS_TYPE_MOST_POPULAR }}`;
        var leastPopularAnalyticsType = `{{ \App\Services\TopicAnalyticsService::ANALYTICS_TYPE_LEAST_POPULAR }}`;
        var predictedPopularTopicsAnalyticsType = `{{ \App\Services\TopicAnalyticsService::ANALYTICS_TYPE_PREDICTED_POPULAR_TOPICS }}`;
    </script>

    <div class="pl-60">
        <ol>
            <li>Популярні/непопулярні +</li>
            <li>Простіші/складніші</li>
            <li>Відслідковування частоти запитів за ключовими словами і на їх основі прогнозування популярних тем в майбутньому +</li>
            <li>Відслідковування часу виконання роботи - середній час, необхідний для виконання різних тем, що може допомогти в оцінці їх складності</li>
        </ol>

        <button class="js-get-analytics">Статистика</button>
        <div class="js-analytics hidden">
            <div style="width:40%; display: flex; flex-direction: row; justify-content: space-around;">
                <canvas id="mostPopular" width="400" height="400"></canvas>
                <canvas id="leastPopular" width="400" height="400"></canvas>
            </div>

            <div class="m-5">
                <h1 class="title">Прогнозовані популярні теми</h1>
                <div class="js-predicted-topics-container"></div>
            </div>
        </div>

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

    @push('scripts')
        <script src="{{ asset('js/universityAdminProfile/topics-analytics.js')}}"></script>
    @endpush
@endsection
