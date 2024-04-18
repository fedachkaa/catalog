const { toggleTabsSideBar, showModal, hideModal, clearModal } = require('./../general.js');
const { getCatalogs, drawCatalogCommonDataRow, addTopic, saveTopic, editTopic} = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs({teacherId: teacherId }, displayCatalogsData);

    $(document).on('click', '.js-view-catalog', viewCatalog);

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editTopic);
    $(document).on('click', '.js-view-requests', showTopicRequets);
    $(document).on('click', '.js-approve-request', approveRequest);
    $(document).on('click', '.js-reject-request', rejectRequest);
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
    const viewActionIcon = $('<i>').addClass('fas fa-eye action-icon js-view-catalog')
        .attr('title', 'Переглянути');
    addActionCell.append(viewActionIcon);
    row.append(addActionCell);
    tbody.append(row);
}

const viewCatalog = function (e) {
    window.open('/university/' + universityId + '/catalogs/' + $(e.target).closest('tr').data('catalogid'), '_blank');
}

const showTopicRequets = function (e) {
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
                        li.append($('<span>').addClass('span-approved').text('APPROVED'));
                    } else if (topicRequest.status === 'rejected') {
                        li.append($('<span>').addClass('span-rejected').text('REJECTED'));
                    } else if (response.data.student !== 'undefined' && response.data.student.length === 0) {
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

const approveRequest = function (e) {
    const requestId = $(e.target).closest('li').attr('data-requestid');

    let confirmed = confirm('Ви впевнені, що хочете схвалити цей запит?');

    if (confirmed) {
        $.ajax({
            url: '/api/topic-requests/' + requestId + '/approve',
            method: 'POST',
            data: {
                _token: $('#topicRequestsModal').data('token'),
            },
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.error('Помилка:', error);
            }
        });
    }
}

const rejectRequest = function (e) {
    const requestId = $(e.target).closest('li').attr('data-requestid');

    let confirmed = confirm('Ви впевнені, що хочете відхилити цей запит?');

    if (confirmed) {
        $.ajax({
            url: '/api/topic-requests/' + requestId + '/reject',
            method: 'POST',
            data: {
                _token: $('#topicRequestsModal').data('token'),
            },
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.error('Помилка:', error);
            }
        });
    }
}

