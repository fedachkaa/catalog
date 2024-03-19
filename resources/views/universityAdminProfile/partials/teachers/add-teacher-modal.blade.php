<div id="addTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати викладача</h2>
        <div class="js-form-fields add-teacher-modal-content">
            <input type="text" class="form-control js-first-name" placeholder="Введіть ім'я">
            <input type="text" class="form-control js-last-name" placeholder="Введіть прізвище">
            <input type="email" class="form-control js-email" placeholder="Введіть електронну пошту">
            <input type="text" class="form-control js-phone-number" placeholder="Введіть номер телефону">
            <input type="text" class="form-control js-subject" placeholder="Введіть назву предмета">
            <select class="form-control js-faculty"></select>
            <button class="add-user-btn js-save-teacher" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
