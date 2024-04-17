<div id="courseInfo" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Інформація про курс</h2>
        <div class="course-info-modal-content">
            <div class="js-groups-info">
                <button class="add-user-btn js-add-group">Додати групу</button>
                <button class="add-user-btn js-save-group hidden" data-token="{{ csrf_token() }}">Зберегти</button>
                <div class="js-groups">
                    <h2>Групи</h2>
                </div>
            </div>
            <div class="js-group-info hidden">
                <div class="flex flex-row">
                    <div class="js-manual-add">
                        <button class="add-user-btn js-add-student">Додати студента</button>
                    </div>
                    <div class="js-import-add">
                        <button class="add-user-btn js-import-students">Імпорт з файлу</button>
                    </div>
                </div>
                <div class="js-students-content"></div>
            </div>
            <div class="js-new-student-form">
                <button class="add-user-btn js-save-student hidden" data-token="{{ csrf_token() }}">Зберегти</button>
                <button class="add-user-btn js-import-students-save hidden" data-token="{{ csrf_token() }}">Зберегти</button>
            </div>
        </div>

    </div>
</div>
