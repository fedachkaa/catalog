<div class="pl-52">
    <div class="faculties-block js-faculties-container">
        <button class="add-user-btn js-add-faculty">Додати факультет</button>
        <button class="add-user-btn js-save-faculty hidden" data-token="{{ csrf_token() }}">Зберегти</button>

        <div>
            <table id="faculties-table" class="table-block">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Факультет</th>
                        <th>Курси</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Table body will be populated dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>
