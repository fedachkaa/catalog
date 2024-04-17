<div id="addEditFacultyModal" class="modal">
    <div class="add-course-modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати/редагувати факультет</h2>
        <div class="flex flex-col justify-center">
            <label>Назва факультету:</label>
            <input type="text" class="form-control js-faculty-title">
            <p class="error-message title-error-message"></p>
            <button class="add-user-btn js-save-faculty" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
