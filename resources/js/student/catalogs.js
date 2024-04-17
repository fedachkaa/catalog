const { toggleTabsSideBar } = require('./../general.js');
const { getCatalogs, drawCatalogCommonDataRow} = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs({'studentId' : studentId }, displayCatalogsData);

    $(document).on('click', '.js-view-catalog', viewCatalog);
    $(document).on('click', '.js-send-request', sendRequestTopic);
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

const sendRequestTopic = function (e) {
    const topicId = $(e.target).closest('tr').data('topicid');
    const catalogId = $(document).find('.js-edit-catalog-block').data('catalogid');
    $.ajax({
        url: '/api/university/' + universityId + '/catalog/' + catalogId + '/topic/' + topicId +'/send-request',
        method: 'POST',
        data: {
            _token: $(e.target).data('token'),
        },
        success: function (response) {
            $(e.target).removeClass('fa-paper-plane').addClass('fa-envelope-circle-check').attr('title', 'Запит надіслано')
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}
