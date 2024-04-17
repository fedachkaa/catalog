const { showModal, showSpinner, clearModal, hideModal, hideSpinner } = require("../general");

const getCatalogs = function (searchParams ={}, callback = () => {}) {
    const queryString = Object.keys(searchParams).map(key => key + '=' + encodeURIComponent(searchParams[key])).join('&');

    $.ajax({
        url: '/api/university/' + universityId + '/catalogs?' + queryString,
        method: 'GET',
        success: function (response) {
            if (response.data.length !== 0) {
                callback(response.data);
            }
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const drawCatalogCommonDataRow = function (catalog) {
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

    row.addClass(($('#catalogs-table tr').length + 1) % 2 === 0 ? 'row-gray' : 'row-beige');

    return row;
}

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

module.exports = {
    getCatalogs,
    drawCatalogCommonDataRow,
    addTopic,
    saveTopic,
    editTopic,
};
