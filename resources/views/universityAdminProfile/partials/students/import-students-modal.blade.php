<div id="importStudentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Імпорт студентів</h2>
        <div class="js-form-fields flex flex-row justify-evenly	items-stretch">
            <div class="form-group">
                <label>Факультет</label>
                <select class="form-control-st js-faculty">
                    <option value="">Оберіть факультет</option>
                </select>
                <p class="error-message faculty_id-error-message"></p>
            </div>
            <div class="form-group">
                <label>Курс</label>
                <select class="form-control-st js-course">
                    <option value="">Оберіть курс</option>
                </select>
                <p class="error-message course_id-error-message"></p>
            </div>
            <div class="form-group">
                <label>Група</label>
                <select class="form-control-st js-group">
                    <option value="">Оберіть групу</option>
                </select>
                <p class="error-message group_id-error-message"></p>
            </div>

            <div class="js-form-import">
                <input type="file" class="form-control js-students-file">
            </div>
        </div>
        <button class="add-user-btn text-center js-import-students-save" data-token="{{ csrf_token() }}">Зберегти</button>
    </div>
</div>
