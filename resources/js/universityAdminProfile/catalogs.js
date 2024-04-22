const { showModal, hideModal, clearModal, toggleTabsSideBar, showErrors } = require('./../general.js');
const { searchGroups, searchFaculties, searchCourses, searchTeachers } = require('./common.js');
const { getCatalogs, drawCatalogCommonDataRow } = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs({}, displayCatalogsData);

    $(document).on('click', '.js-add-catalog', addCatalog);
    $(document).on('click', '.js-save-catalog', saveCatalog);
    $(document).on('click', '.js-edit-catalog', editCatalog);
});

const displayCatalogsData = function(data) {
    const tbody = $('#catalogs-table tbody');
    tbody.empty();

    data.forEach(catalog => {
        drawSingleCatalog(catalog);
    });
}

const drawSingleCatalog = function (catalog) {
    const tbody = $('#catalogs-table tbody');
    const row = drawCatalogCommonDataRow(catalog);
    const addActionCell = $('<td>');
    const editActionIcon = $('<i>').addClass('fas fa-edit action-icon js-edit-catalog')
        .attr('title', 'Редагувати');
    addActionCell.append(editActionIcon);
    row.append(addActionCell);
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
        return $(this).data('groupid');
    }).get();

    const teacherIds = $('#addCatalogModal .js-teachers-list li').map(function() {
        return $(this).data('teacherid');
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
            showErrors(response.responseJSON.errors, '#addCatalogModal');
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
