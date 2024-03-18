<div id="addEditSubjectModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати/редагувати предмет</h2>
        <div class="js-form-fields add-teacher-modal-content">
            <label>Назва предмету</label>
            <input type="text" class="form-control js-subject-title" placeholder="Назва предмету">
            <label>Викладач</label>
            <div class="flex flex-row">
                <input type="text" class="form-control js-teacher-search" placeholder="Пошук викладача">
                <i class="fa fa-search action-icon js-search-teacher-btn"></i>
            </div>
            <select class="form-control js-teachers-select hidden"></select>
            <div class="js-teachers-list">
                <ul></ul>
            </div>
            <button class="add-user-btn js-save-subject" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
