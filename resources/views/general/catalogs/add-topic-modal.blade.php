<?php
/**
 * @var array $user
 * @var array $catalogData
 */
?>

<div id="addTopicModal" class="modal" data-catalogid="<?= $catalogData['id']; ?>">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати тему</h2>
        <div class="js-form-fields add-teacher-modal-content">
            <label>Тема:</label>
            <input type="text" class="form-control js-topic">
            <p class="error-message topic-error-message"></p>
            <label>Науковий керівник</label>
            <select class="form-control js-teacher" <?= $user['role_id'] === \App\Models\UserRole::USER_ROLE_TEACHER ? 'disabled' : ''; ?>>
                <?php foreach ($catalogData['supervisors'] as $supervisor) : ?>
                    <option value="<?= $supervisor['user_id']; ?>" <?= $user['id'] === $supervisor['user_id'] ? 'selected' : ''; ?>><?= $supervisor['user']['full_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="error-message teacher_id-error-message"></p>
            <button class="add-user-btn js-save-topic" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
