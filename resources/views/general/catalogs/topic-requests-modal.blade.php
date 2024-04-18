<?php
/**
 * @var array $user
 */
?>

<div id="topicRequestsModal" class="modal" data-token="{{ csrf_token() }}">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Запити</h2>
        <div class="add-teacher-modal-content">
            <ul class="js-list-requests text-xl"></ul>
        </div>
    </div>
</div>
