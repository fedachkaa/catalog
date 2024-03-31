const { showModal, hideModal, clearModal, toggleTabsSideBar } = require('./../general.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editCatalog);

});

const addTopic = function () {
    showModal('addTopicModal');
}

const saveTopic = function (e) {
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
        },
        error: function (response) {
            if (response.responseJSON.errors) {
                Object.entries(response.responseJSON.errors).forEach(function([key, errorMessage]) {
                    const errorParagraph = $('#addTopicModal').find(`p.error-message.${key}-error-message`);
                    errorParagraph.text(errorMessage);
                });
            }
        }
    });
}

const editCatalog = function (e) {
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

