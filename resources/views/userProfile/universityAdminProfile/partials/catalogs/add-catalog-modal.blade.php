<div id="addCatalogModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 class="modal-title">Додати каталог</h2>
        <div class="js-form-fields add-teacher-modal-content">

            <label>Тип каталогу:</label>
            <select class="form-control js-catalog-type">
                @foreach(\App\Models\Catalog::AVAILABLE_CATALOG_TYPES as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <p class="error-message type-error-message"></p>

            <div class="flex flex-row w-full">
                <div class="flex flex-col w-full">
                    <label>Факультет:</label>
                    <select class="form-control js-faculty"></select>
                    <p class="error-message faculty-error-message"></p>
                </div>
                <div class="flex flex-col w-full">
                    <label>Курс:</label>
                    <select class="form-control js-course"></select>
                    <p class="error-message course-error-message"></p>
                </div>
            </div>

            <div class="flex flex-row w-full">
                <div class="flex flex-col w-full">
                    <label>Група:</label>
                    <select class="form-control js-group"></select>
                    <div class="js-groups-list">
                        <ul></ul>
                    </div>
                    <p class="error-message groupsIds-error-message"></p>
                </div>
                <div class="flex flex-col w-full">
                    <label>Наукові керівники:</label>
                    <div class="flex flex-row">
                        <input type="text" class="form-control js-teacher-search" placeholder="Пошук викладача">
                        <i class="fa fa-search action-icon js-search-supervisor-btn"></i>
                    </div>
                    <select class="form-control js-teachers-select hidden"></select>
                    <div class="js-teachers-list">
                        <ul></ul>
                    </div>
                    <p class="error-message supervisorsIds-error-message"></p>
                </div>
            </div>

            <button class="add-user-btn js-save-catalog" data-token="{{ csrf_token() }}">Зберегти</button>
        </div>
    </div>
</div>
