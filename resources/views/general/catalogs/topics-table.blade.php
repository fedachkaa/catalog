<?php
/**
 * @var array $user
 * @var array $topics
 */

$hasStudentTopic = false;

\logger($topics);

if ($user['role_id'] === \App\Models\UserRole::USER_ROLE_STUDENT) {
    $hasStudentTopic = !empty(array_filter($topics, function($topic) use ($user) {
        return $topic['student_id'] === $user['id'];
    }));
}
?>
<div class="flex flex-col mb-4">
    @if (!empty($topics))
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
                @foreach($topics as $topic)
                    <tr data-topicid="{{ $topic['id'] }}">
                        <td>{{ $topic['id'] }}</td>
                        <td class="js-single-topic-topic">{{ $topic['topic'] }}</td>
                        <td class="js-single-topic-teacher" data-teacherid="{{ $topic['teacher']['user_id'] }}">{{ $topic['teacher']['user']['full_name'] }}</td>
                        @if (!empty($topic['student']))
                            <td class="js-single-topic-student" data-studentid="{{ $topic['student']['user_id'] }}">{{ $topic['student']['user']['full_name'] }}</td>
                        @else
                            <td class="js-single-topic-student">-</td>
                        @endif
                        <td>
                            @if ($topic['teacher']['user_id'] === $user['id'] || $user['role_id'] === \App\Models\UserRole::USER_ROLE_UNIVERSITY_ADMIN)
                                <i class="fas fa-edit action-icon js-edit-topic" title="Редагувати"></i>
                                <i class="fa-solid fa-person-circle-question js-view-requests action-icon" title="Переглянути запити"></i>
                            @elseif ($user['role_id'] === \App\Models\UserRole::USER_ROLE_STUDENT)
                                @if (in_array($user['id'], array_column($topic['requests'], 'student_id')))
                                    <i class="fas fa-envelope-circle-check action-icon" title="Запит надіслано"></i>
                                @elseif (!$hasStudentTopic)
                                    <i class="fas fa-paper-plane action-icon js-send-request" title="Надіслати запит" data-token="{{ csrf_token() }}"></i>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        Тем ще немає.
    @endif

    @if ($user['role_id'] !== \App\Models\UserRole::USER_ROLE_STUDENT)
        <div class="mt-2">
            <i class="fas fa-circle-plus action-icon text-3xl js-add-topic" title="Додати тему"></i>
        </div>
    @endif
</div>
