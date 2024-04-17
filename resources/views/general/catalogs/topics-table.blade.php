<?php
/**
 * @var array $user
 * @var array $topics
 */
?>
<div class="flex flex-col mb-4">
    <?php if (!empty($topics)) : ?>
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
            <?php foreach ($topics as $topic) : ?>
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
                        <?php if ($topic['teacher']['user_id'] === $user['id']): ?>
                            <i class="fas fa-edit action-icon js-edit-topic" title="Редагувати"></i>
                            <i class="fa-solid fa-person-circle-question" title="Переглянути запити"></i>
                        <?php elseif ($user['role_id'] === \App\Models\UserRole::USER_ROLE_STUDENT): ?>
                            <?php if (in_array($user['id'], array_column($topic['requests'], 'student_id'))): ?>
                                <i class="fas fa-envelope-circle-check action-icon" title="Запит надіслано"></i>
                            <?php else: ?>
                                <i class="fas fa-paper-plane action-icon js-send-request" title="Надіслати запит" data-token="{{ csrf_token() }}"></i>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        Тем ще немає.
    <?php endif; ?>

    <?php if ($user['role_id'] !== \App\Models\UserRole::USER_ROLE_STUDENT): ?>
        <div class="mt-2">
            <i class="fas fa-circle-plus action-icon text-3xl js-add-topic" title="Додати тему"></i>
        </div>
    <?php endif; ?>
</div>
