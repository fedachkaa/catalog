const {toggleTabsSideBar, showSpinner, hideSpinner} = require("../general");

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-topics');

    $(document).on('click', '.js-get-analytics', getTopicAnalytics);
});

function getTopicAnalytics() {
    showSpinner();

    $.ajax({
        url: '/api/university/' + universityId +'/topic-analytics',
        method: 'GET',
        success: function (response) {
            displayAnalytics(response.data);
            $(document).find('.js-analytics').removeClass('hidden');
            hideSpinner();
        },
        error: function (xhr, status, error) {
            hideSpinner();
            console.error('Помилка:', error);
        }
    });
}

function displayAnalytics(analytics) {
    const mostPopularTopicsData = analytics[mostPopularAnalyticsType];
    const leastPopularTopicsData = analytics[leastPopularAnalyticsType];
    const predictedPopularTopicsData = analytics[predictedPopularTopicsAnalyticsType];

    initChart(
        'mostPopular',
        mostPopularTopicsData.map(topic => topic.topic),
        mostPopularTopicsData.map(topic => topic.requestCount),
        'Найбільш популярні теми'
    );

    initChart(
        'leastPopular',
        leastPopularTopicsData.map(topic => topic.topic),
        leastPopularTopicsData.map(topic => topic.requestCount === 0 ? 0.01 : topic.requestCount),
        'Найменш популярні теми'
    );

    const container = $('.js-predicted-topics-container');
    container.empty();

    $.each(predictedPopularTopicsData, function(index, topicData) {
        const keywordTitle = $('<h3 class="text-center">').html(`Ключове слово: <strong>${topicData.keyword}</strong>`);
        container.append(keywordTitle);

        const topicList = $('<ol class="list-decimal"></ol>');
        $.each(topicData.topics, function(i, topic) {
            const listItem = $('<li>').text(topic);
            topicList.append(listItem);
        });

        container.append(topicList);
    });
}

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
                        label: function (tooltipItem) {
                            return 'Кількість запитів: ' + (tooltipItem.raw === 0.01 ? 0 : tooltipItem.raw);
                        }
                    }
                }
            }
        }
    });
}
