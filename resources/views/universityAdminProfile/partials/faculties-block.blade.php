<div class="pl-52">

    <div class="faculties-block js-faculties-container">
        <button class="save-btn js-add-faculty">Додати факультет</button>
        <button class="save-btn js-save-faculty hidden">Зберегти</button>

        <div class="js-faculties-list"></div>

        <div class="js-faculty-item faculty-item">

            <div class="js-faculty-info hidden faculty-info">
                <h3 class="js-title"></h3>
                <div class="js-courses">
                    <h2>Курси</h2>
                </div>
                <button class="save-btn js-add-course">Додати курс</button>
                <button class="save-btn js-save-course hidden" data-token="{{ csrf_token() }}">Зберегти</button>
            </div>

            <div class="js-groups-info hidden faculty-info">
                <div class="js-groups">
                    <h2>Групи</h2>
                </div>
                <button class="save-btn js-add-group">Додати групу</button>
                <button class="save-btn js-save-group hidden" data-token="{{ csrf_token() }}">Зберегти</button>
            </div>
        </div>

    </div>
</div>