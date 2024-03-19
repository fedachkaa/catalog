<div id="groupStudents" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title"></h2>
        <div class="js-manual-add">
            <button class="save-btn js-add-student">Додати студента</button>
            <button class="save-btn js-save-student hidden" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
        <div class="js-import-add">
            <button class="save-btn js-import-students">Імпорт з файлу</button>
            <button class="save-btn js-import-students-save hidden" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
        <div class="js-students-content">
        </div>
    </div>
</div>
