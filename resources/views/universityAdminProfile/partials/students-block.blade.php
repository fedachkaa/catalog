<div class="pl-48">
    <div class="search-tool">
        <div class="search-tool-title">Шукати студента</div>
        <div class="search-tool-body">
            <div class="search-field">
                <label for="surname">Прізвище:</label>
                <input type="text" id="surname" name="surname">
            </div>

            <div class="search-field">
                <label for="faculty">Факультет:</label>
                <input type="text" id="faculty" name="faculty">
            </div>

            <div class="search-field">
                <label for="course">Курс:</label>
                <input type="text" id="course" name="course">
            </div>

            <div class="search-field">
                <label for="group">Група:</label>
                <input type="text" id="group" name="group">
            </div>

            <div class="search-field">
                <label for="email">Пошта:</label>
                <input type="email" id="email" name="email">
            </div>
        </div>

        <div class="search-tool-button">
            <button type="button" class="save-btn js-search-students">Пошук</button>
        </div>
    </div>

    <div>
        <table id="students-table" class="table-block">
            <thead>
            <tr>
                <th>№</th>
                <th>ПІБ</th>
                <th>Факультет</th>
                <th>Курс</th>
                <th>Група</th>
            </tr>
            </thead>
            <tbody>
            <!-- Table body will be populated dynamically -->
            </tbody>
        </table>
    </div>
</div>
