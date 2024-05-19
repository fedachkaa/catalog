const { showModal, showSpinner, hideSpinner, showErrors, initPagination } = require("../general");

const getCatalogs = function (searchParams ={}, callback = () => {}) {
    showSpinner();

    const queryString = Object.keys(searchParams).map(key => key + '=' + encodeURIComponent(searchParams[key])).join('&');

    $.ajax({
        url: '/api/university/' + universityId + '/catalogs?' + queryString,
        method: 'GET',
        success: function (response) {
            if (response.data.catalogs.length) {
                callback(response.data.catalogs);

                prepareCatalogsTable(true);

                initPagination(response.data.pagination);
                $('.js-pagination .pagination-first').off().on('click', function () {
                    getCatalogs({}, callback);
                });
                $('.js-pagination .pagination-next').off().on('click', function () {
                    getCatalogs({page: response.data.pagination.next}, callback);
                });
                $('.js-pagination .pagination-previous').off().on('click', function () {
                    getCatalogs({page: response.data.pagination.before}, callback);
                });
                $('.js-pagination .pagination-last').off().on('click', function () {
                    getCatalogs({page: response.data.pagination.last}, callback);
                });
            } else {
                prepareCatalogsTable();
            }
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const prepareCatalogsTable = function (isShow = false) {
    if (isShow) {
        $('#catalogs-table').removeClass('hidden');
        $('.js-catalogs-message').text('');
    } else {
        $('#catalogs-table').addClass('hidden');
        $('.js-pagination').addClass('hidden');
        $('.js-catalogs-message').append('<p>Ще немає каталогів</p>');
    }
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
    let url = '/api/university/' + universityId + '/catalogs/' + catalogId + '/topics';
    if (topicId) {
        method = 'PUT';
        url = '/api/university/' + universityId + '/catalogs/' + catalogId + '/topics/' + topicId;
    }
    $.ajax({
        url: url,
        method: method,
        data: {
            topic: $('#addTopicModal .js-topic').val(),
            teacher_id: $('#addTopicModal .js-teacher').val(),
            _token: $(e.target).data('token'),
        },
        success: function () {
            window.location.reload();
        },
        error: function (response) {
            showErrors(response.responseJSON.errors, '#addTopicModal');
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

const showTopicRequests = function (e) {
    const topicId = $(e.target).closest('tr').data('topicid');

    $.ajax({
        url: '/api/topic/' + topicId + '/topic-requests',
        method: 'GET',
        success: function (response) {
            if (response.data.length !== 0) {
                $('#topicRequestsModal').data('topicid', topicId);
                const requestsList = $('#topicRequestsModal').find('.js-list-requests');
                requestsList.empty();
                let counter = 1;
                response.data.requests.forEach(topicRequest => {
                    let li = $('<li>').attr('data-requestid', topicRequest.id);
                    li.text(counter + ') ' + topicRequest.student.user.full_name + ' (' +  topicRequest.created_at +')');
                    if (topicRequest.status === 'approved') {
                        li.append($('<span>').addClass('span-approved').text('СХВАЛЕНО'));
                    } else if (topicRequest.status === 'rejected') {
                        li.append($('<span>').addClass('span-rejected').text('ВІДХИЛЕНО'));
                    } else if (response.data.student !== 'undefined' && response.data.student.length === 0 && typeof teacherId !== 'undefined') {
                        li.append($('<i>').addClass('fa-regular fa-square-check action-icon js-approve-request').attr('title', 'Схвалити запит'));
                        li.append($('<i>').addClass('fa-regular fa-circle-xmark action-icon js-reject-request').attr('title', 'Відхилити запит'));
                    }
                    requestsList.append(li);
                    counter++;
                });

                showModal('topicRequestsModal');
            }
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

module.exports = {
    getCatalogs,
    drawCatalogCommonDataRow,
    addTopic,
    saveTopic,
    editTopic,
    prepareCatalogsTable,
    showTopicRequests,
};
