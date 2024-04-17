<div id="addStudentModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати студента</h2>
        <div class="js-form-fields flex flex-row justify-evenly	items-stretch">
            <div>
                <div class="form-group">
                    <label>Ім'я</label>
                    <input type="text" class="form-control-st js-first-name">
                    <p class="error-message first_name-error-message"></p>
                </div>
                <div class="form-group">
                    <label>Прізвище</label>
                    <input type="text" class="form-control-st js-last-name">
                    <p class="error-message last_name-error-message"></p>
                </div>
                <div class="form-group">
                    <label>Електронна пошта</label>
                    <input type="email" class="form-control-st js-email">
                    <p class="error-message email-error-message"></p>
                </div>
                <div class="form-group">
                    <label>Номер телефону</label>
                    <input type="text" class="form-control-st js-phone-number">
                    <p class="error-message phone_number-error-message"></p>
                </div>
            </div>
            <div>
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
            </div>
        </div>
        <button class="add-user-btn text-center js-save-student" data-token="{{ csrf_token() }}">Зберегти</button>
    </div>
</div>
