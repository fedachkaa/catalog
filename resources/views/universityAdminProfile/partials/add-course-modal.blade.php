<div id="addCourseModal" class="modal">
    <div class="add-course-modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати курс</h2>
        <div class="flex flex-col justify-center">
            <label>Курс:</label>
            <input type="number" class="form-control js-course-number" min="1" max="6">
            <button class="add-user-btn js-save-course" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
