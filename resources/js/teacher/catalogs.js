const { toggleTabsSideBar } = require('./../general.js');
const { getCatalogs, drawCatalogCommonDataRow, addTopic, saveTopic, editTopic} = require('../common/catalogs.js');

document.addEventListener('DOMContentLoaded', function () {
    toggleTabsSideBar('js-catalogs');

    getCatalogs({teacherId: teacherId }, displayCatalogsData);

    $(document).on('click', '.js-view-catalog', viewCatalog);

    $(document).on('click', '.js-add-topic', addTopic);
    $(document).on('click', '.js-save-topic', saveTopic);
    $(document).on('click', '.js-edit-topic', editTopic);

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
