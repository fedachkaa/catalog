const { showModal, hideModal, clearModal, toggleTabsSideBar } = require('./../general.js');
const { searchGroups, searchFaculties, searchCourses, searchTeachers } = require('./common.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs();

    $(document).on('click', '.js-add-catalog', addCatalog);
    $(document).on('click', '.js-save-catalog', saveCatalog);
    $(document).on('click', '.js-edit-catalog', editCatalog);

});

const getCatalogs = function () {
    $.ajax({
        url: '/api/university/' + universityId + '/catalogs',
        method: 'GET',
        success: function (response) {
            if (response.data.length !== 0) {
                displayCatalogsData(response.data);
            }
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const displayCatalogsData = function(data) {
    const tbody = $('#catalogs-table tbody');
    tbody.empty();

    data.forEach(catalog => {
        drawSingleCatalog(catalog);
    });
}

const drawSingleCatalog = function (catalog) {
    const tbody = $('#catalogs-table tbody');
    const row = $(`<tr data-catalogid=` + catalog.id + `>`);

    row.append($('<td>').text(catalog.id));
    row.append($('<td class="js-single-catalog-type">').text(catalog.typeTitle));
    row.append($('<td class="js-single-catalog-faculty">').text(catalog.faculty.title));
    row.append($('<td class="js-single-catalog-course">').text(catalog.course.course + ' курс'));

    const groupsList = $('<ul class="js-list-groups">');
    catalog.groups.forEach(group => {
        const listItem = $(`<li class="list-group-item" data-id="` + group.id + `">`).text(group.title);
        groupsList.append(listItem);
    });
    row.append($('<td>').append(groupsList));

    const teachersList = $('<ul class="js-list-teachers">');
    catalog.supervisors.forEach(supervisor => {
        const listItem = $(`<li class="list-supervisor-item" data-id="` + supervisor.user_id + `">`).text(supervisor.user.full_name);
        teachersList.append(listItem);
    });
    row.append($('<td>').append(teachersList));

    row.append($('<td class="js-single-catalog-created-at">').text(catalog.created_at));

    const addActionCell = $('<td>');
    const editActionIcon = $('<i>').addClass('fas fa-edit action-icon js-edit-catalog')
        .attr('title', 'Редагувати');

    addActionCell.append(editActionIcon);

    row.append(addActionCell);

    row.addClass(($('#catalogs-table tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    tbody.append(row);
}

const addCatalog = function () {
    searchFaculties('addCatalogModal');

    $('#addCatalogModal .js-faculty').on('change', function() {
        const selectedFacultyId = $(this).val();
        searchCourses(selectedFacultyId, 'addCatalogModal');
    });

    $('#addCatalogModal .js-course').on('change', function () {
        searchGroups({ courseId: $(this).val() }, 'addCatalogModal', fillGroupSelect);
    });

    $('#addCatalogModal .js-search-supervisor-btn').on('click', function () {
        searchTeachers('#addCatalogModal', { facultyId:  $('#addCatalogModal .js-faculty').val(), searchText: $('#addCatalogModal .js-teacher-search').val()});
    });

    showModal('addCatalogModal');
}

const saveCatalog = function (e) {
    const groupsIds = $('#addCatalogModal .js-groups-list li').map(function() {
        return $(this).data('id');
    }).get();

    const teacherIds = $('#addCatalogModal .js-teachers-list li').map(function() {
        return $(this).data('id');
    }).get();

    $.ajax({
        url: '/api/university/' + universityId + '/catalogs/create',
        method: 'POST',
        data: {
            type: $('#addCatalogModal .js-catalog-type').val(),
            faculty_id: $('#addCatalogModal .js-faculty').val(),
            course_id: $('#addCatalogModal .js-course').val(),
            groupsIds: groupsIds,
            teachersIds: teacherIds,
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleCatalog(response.data);
            clearModal('addCatalogModal')
            hideModal('addCatalogModal');
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addCatalogModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const fillGroupSelect = function (groups, block) {
    const groupsSelect = $('#' + block).find('.js-group');
    groupsSelect.empty();
    groupsSelect.append($('<option>').attr('value', '').text('Виберіть групу'));

    groups.forEach(group => {
        groupsSelect.append($('<option>').attr('value', group.id).text(group.title));
    });

    initGroupSelectClick(groupsSelect, $('#addCatalogModal .js-groups-list ul'));
    initRemoveGroupClick(groupsSelect, $('#addCatalogModal .js-groups-list'));
}

const initGroupSelectClick = function (groupsSelect, groupsList) {
    groupsSelect.on('change', function() {
        const selectedGroupId = $(this).val();

        if (selectedGroupId === '') {
            return;
        }

        const selectedGroupTitle = $(this).find('option:selected').text();

        const listItem = $(`<li data-groupid="` + selectedGroupId + `">`).text(selectedGroupTitle);
        const deleteIcon = $('<i>').addClass('fas fa-times js-delete-group');
        listItem.append(deleteIcon);
        groupsList.append(listItem);

        $(this).find('option:selected').hide();
        $(this).val('');
    });
}

const initRemoveGroupClick = function (groupsSelect, groupsList) {
    groupsList.on('click', '.js-delete-group', function () {
        const groupId = parseInt($(this).parent().data('groupid'), 10);
        groupsSelect.find('option').each(function () {
            if (parseInt($(this).val(), 10) === groupId) {
                $(this).show();
            }
        });
        $(this).parent().remove();
    });
}

const editCatalog = function (e) {
    window.open('/university/' + universityId + '/catalogs/' + $(e.target).closest('tr').data('catalogid'), '_blank');
}

module.exports = {
    initGroupSelectClick,
    initRemoveGroupClick,
};
