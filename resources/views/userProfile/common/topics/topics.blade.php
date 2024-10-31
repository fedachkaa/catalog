<?php
/**
 * @var array $topicsData
 * @var array $analytics
 */
?>
@extends('layouts.main')

@section('title', 'Topics | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="pl-60">
        <ol>
            <li>Популярні/непопулярні +</li>
            <li>Простіші/складніші</li>
            <li>Відслідковування частоти запитів за ключовими словами і на їх основі прогнозування популярних тем в майбутньому</li>
            <li>Відслідковування часу виконання роботи - середній час, необхідний для виконання різних тем, що може допомогти в оцінці їх складності</li>
        </ol>

        <div style="width:40%; display: flex; flex-direction: row; justify-content: space-around;">
            <canvas id="mostPopular" width="400" height="400"></canvas>
            <canvas id="leastPopular" width="400" height="400"></canvas>
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

    <script>
        const mostPopularTopicsData = <?= json_encode($analytics[\App\Services\TopicAnalyticsService::ANALYTICS_TYPE_MOST_POPULAR]); ?>;
        const leastPopularTopicsData = <?= json_encode($analytics[\App\Services\TopicAnalyticsService::ANALYTICS_TYPE_LEAST_POPULAR]); ?>;

        initChart(
            'mostPopular',
            mostPopularTopicsData.map(topic => topic.topic),
            mostPopularTopicsData.map(topic => topic.requestCount),
            `<?= \App\Services\TopicAnalyticsService::AVAILABLE_ANALYTICS[\App\Services\TopicAnalyticsService::ANALYTICS_TYPE_MOST_POPULAR]; ?>`
        );
        initChart(
            'leastPopular',
            leastPopularTopicsData.map(topic => topic.topic),
            leastPopularTopicsData.map(topic => topic.requestCount === 0 ? 0.01 : topic.requestCount),
            `<?= \App\Services\TopicAnalyticsService::AVAILABLE_ANALYTICS[\App\Services\TopicAnalyticsService::ANALYTICS_TYPE_LEAST_POPULAR]; ?>`
        );

        function initChart(chartId, labels, data, title) {
            const ctx = document.getElementById(chartId).getContext('2d');
            const myPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Кількість запитів',
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: title,
                            font: {
                                size: 18,
                                weight: 'bold',
                            },
                            padding: {
                                top: 10,
                                bottom: 30,
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return 'Кількість запитів: ' + (tooltipItem.raw === 0.01 ? 0 : tooltipItem.raw);
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
