const { showModal, hideModal, clearModal, toggleTabsSideBar, showSpinner, hideSpinner } = require('./../general.js');
const { searchGroups, searchTeachers} = require('./common.js');
const { initGroupSelectClick, initRemoveGroupClick } = require('./catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editTopic);
    $(document).on('click', '.js-update-catalog', updateCatalog)

    searchGroups({ courseId: $('.js-course').data('courseid') }, 'js-edit-catalog-block', initGroups);
    searchTeachers('.js-edit-catalog-block', { facultyId:  $('.js-faculty').data('facultyid') }, initTeachers);
});

const addTopic = function () {
    showModal('addTopicModal');
}

const saveTopic = function (e) {
    showSpinner();
    const catalogId = $('#addTopicModal').data('catalogid');
    const topicId = $('#addTopicModal').data('topicid');

    let method = 'POST';
    let url = '/api/university/' + universityId + '/catalogs/' + catalogId + '/topic';
    let attrToRemove = [];
    if (topicId) {
        method = 'PUT';
        url = '/api/university/' + universityId + '/catalogs/' + catalogId + '/topic/' + topicId;
        attrToRemove = ['topicid'];
    }
    $.ajax({
        url: url,
        method: method,
        data: {
            topic: $('#addTopicModal .js-topic').val(),
            teacher_id: $('#addTopicModal .js-teacher').val(),
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            drawSingleTopic(response.data);
            clearModal('addTopicModal', attrToRemove)
            hideModal('addTopicModal');
            hideSpinner();
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addTopicModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
            hideSpinner();
        }
    });
}

const editTopic = function (e) {
    const topicRow = $(e.target).closest('tr');
    const topicId = topicRow.data('topicid');

    $('#addTopicModal').attr('data-topicid', topicId);
    $('#addTopicModal .js-topic').val(topicRow.find('.js-single-topic-topic').text());
    $('#addTopicModal .js-teacher').val(topicRow.find('.js-single-topic-teacher').data('teacherid'))
    showModal('addTopicModal');
}

const drawSingleTopic = function (topic) {
    const existingRow = $('#topics-table tbody tr[data-topicid="' + topic.id + '"]');
    if (existingRow.length > 0) {
        existingRow.find('.js-single-topic-topic').text(topic.topic);
        existingRow.find('.js-single-topic-teacher').text(topic.teacher.user.full_name)
            .attr('data-teacherid', topic.teacher.user_id);
        if (topic.student.length > 0) {
            existingRow.find('.js-single-topic-student').text(topic.student.user.full_name)
                .attr('data-studentid', topic.student.user_id);
        } else {
            existingRow.find('.js-single-topic-student').text('-').removeAttr('data-studentid');
        }
    } else {
        const newRow = $('<tr>').attr('data-topicid', topic.id);
        newRow.append($('<td>').text(topic.id));
        newRow.append($('<td>').addClass('js-single-topic-topic').text(topic.topic));
        newRow.append($('<td>').addClass('js-single-topic-teacher').text(topic.teacher.user.full_name)
            .attr('data-teacherid', topic.teacher.user_id));
        if (topic.student.length > 0) {
            newRow.append($('<td>').addClass('js-single-topic-student').text(topic.student.user.full_name)
                .attr('data-studentid', topic.student.user_id));
        } else {
            newRow.append($('<td>').addClass('js-single-topic-student').text('-'));
        }

        const addActionCell = $('<td>');
        const addActionIcon = $('<i>').addClass('fas fa-edit action-icon js-edit-topic').attr('title', 'Редагувати');
        addActionCell.append(addActionIcon);
        newRow.append(addActionCell);
        newRow.addClass(($('#topics-table tbody tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');
        $('#topics-table tbody').append(newRow);
    }
}

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
