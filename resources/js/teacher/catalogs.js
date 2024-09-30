const { toggleTabsSideBar } = require('./../general.js');
const { getCatalogs, drawCatalogCommonDataRow, addTopic, saveTopic, editTopic, showTopicRequests } = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs({teacherId: teacherId }, displayCatalogsData);

    $(document).on('click', '.js-view-catalog', viewCatalog);

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editTopic);
    $(document).on('click', '.js-view-requests', showTopicRequests);
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

