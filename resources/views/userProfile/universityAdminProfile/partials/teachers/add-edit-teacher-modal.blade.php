<div id="addTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати/редагувати викладача</h2>
        <div class="js-form-fields add-teacher-modal-content">
            <input type="text" class="form-control js-first-name" placeholder="Введіть ім'я">
            <p class="error-message first_name-error-message"></p>

            <input type="text" class="form-control js-last-name" placeholder="Введіть прізвище">
            <p class="error-message last_name-error-message"></p>

            <input type="email" class="form-control js-email" placeholder="Введіть електронну пошту">
            <p class="error-message email-error-message"></p>

            <input type="text" class="form-control js-phone-number" placeholder="Введіть номер телефону">
            <p class="error-message phone_number-error-message"></p>

            <select class="form-control js-faculty"></select>
            <div class="flex flex-row">
                <input type="text" class="form-control js-subject-search" placeholder="Пошук предмету">
                <i class="fa fa-search action-icon js-search-subject-btn"></i>
            </div>
            <p class="error-message faculty_id-error-message"></p>

            <select class="form-control js-subjects-select hidden"></select>
            <div class="js-subject-list">
                <ul></ul>
            </div>
            <p class="error-message subjectsIds-error-message"></p>
            <button class="add-user-btn js-save-teacher" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
