<div class="pl-48">
    <div class="faculties-block">
        <button class="add-user-btn js-add-subject">Додати предмет</button>
    </div>
    <div>
        <table id="subjects-table" class="table-block">
            <thead>
            <tr>
                <th>№</th>
                <th>Предмет</th>
                <th>Викладачі</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            <!-- Table body will be populated dynamically -->
            </tbody>
        </table>
    </div>
</div>

@include('universityAdminProfile.partials.subjects.add-edit-subject-modal');

