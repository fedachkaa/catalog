const { toggleTabsSideBar, showSpinner, hideSpinner } = require('./../general.js');
const { searchGroups, searchTeachers} = require('./common.js');
const { initGroupSelectClick, initRemoveGroupClick } = require('./catalogs.js');
const {  addTopic, editTopic, saveTopic } = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editTopic);
    $(document).on('click', '.js-update-catalog', updateCatalog)

    searchGroups({ courseId: $('.js-course').data('courseid') }, 'js-edit-catalog-block', initGroups);
    searchTeachers('.js-edit-catalog-block', { facultyId:  $('.js-faculty').data('facultyid') }, initTeachers);
});

const updateCatalog = function (e) {
    showSpinner();

    const groupsIds = $('.js-edit-catalog-block .js-groups-list li').map(function() {
        return $(this).data('groupid');
    }).get();

    const teacherIds = $('.js-edit-catalog-block .js-teachers-list li').map(function() {
        return $(this).data('teacherid');
    }).get();

    $.ajax({
        url: '/api/university/' + universityId + '/catalogs/' + $('.js-edit-catalog-block').data('catalogid'),
        method: 'PUT',
        data: {
            is_active: $('.js-edit-catalog-block .js-is-active').prop('checked') ? 1 : 0,
            groupsIds: groupsIds,
            teachersIds: teacherIds,
            _token: $(e.target).data('token'),
        },
        success: function () {
            hideSpinner();
            window.location.reload();
        },
        error: function (response) {
            hideSpinner();

            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addTopicModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const initGroups = function (groups) {
    const groupsSelect =  $('.js-groups');
    groupsSelect.empty();
    groupsSelect.append($('<option>').attr('value', '').text('Виберіть групу'));

    groups.forEach(group => {
        groupsSelect.append($('<option>').attr('value', group.id).text(group.title));
    });

    $('.js-groups-list').find('li').each(function () {
        const groupId = $(this).data('groupid');
        $('.js-groups').find('option[value="' + groupId + '"]').hide();
    });

    initGroupSelectClick(groupsSelect, $('.js-groups-list ul'));
    initRemoveGroupClick(groupsSelect, $('.js-groups-list ul'));
}

const initTeachers = function () {
    $('.js-teachers-list').find('li').each(function () {
        const teacherId = $(this).data('teacherid');
        $('.js-teachers-select').find('option[value="' + teacherId + '"]').hide();
    })
}
