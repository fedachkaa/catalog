<table id="students-table" class="table-block" data-token="{{ csrf_token() }}">
    <thead>
    <tr>
        <th>№</th>
        <th>ПІБ</th>
        <th>Факультет</th>
        <th>Курс</th>
        <th>Група</th>
        <th>Дії</th>
    </tr>
    </thead>
    <tbody>
    <!-- Table body will be populated dynamically -->
    </tbody>
</table>

<div class="js-students-message"></div>


@include('general.pagination')
