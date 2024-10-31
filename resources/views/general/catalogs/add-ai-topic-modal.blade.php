<?php
/**
 * @var array $user
 * @var array $catalogData
 */
?>

<div id="addAiTopicModal" class="modal" data-catalogid="{{ $catalogData['id'] }}">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Згенерувати тему</h2>
        <div class="js-form-fields add-teacher-modal-content">
            <div style="display: flex; flex-direction: row;">
                <div style="display: flex; flex-direction: column;">
                    <label>Ключове слово:</label>
                    <input type="text" class="form-control js-keyword">
                    <p class="error-message keyword-error-message"></p>
                </div>
                <button class="add-user-btn js-generate-topics">Згенерувати</button>
            </div>
            <div class="ai-results-hidden js-results">
                <div>
                    <p>Згенеровані результати</p>
                    <ul class="js-generated-results"></ul>
                </div>
                <div>
                    <p>Закріплені результати</p>
                    <ul class="js-pinned-results"></ul>
                </div>
            </div>
            <label>Науковий керівник</label>
            <select class="form-control js-teacher" {{ $user['role_id'] === \App\Models\UserRole::USER_ROLE_TEACHER ? 'disabled' : '' }}>
                @foreach($catalogData['supervisors'] as $supervisor)
                    <option value="{{ $supervisor['user_id'] }}" {{ $user['id'] === $supervisor['user_id'] ? 'selected' : '' }}>{{ $supervisor['user']['full_name'] }}</option>
                @endforeach
            </select>
            <p class="error-message teacher_id-error-message"></p>
            <button class="add-user-btn js-save-ai-topics" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
