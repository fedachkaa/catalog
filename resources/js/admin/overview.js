import { hideSpinner, showSpinner } from "../general.js";

document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.js-search-university', searchUniversity);
});

const searchUniversity = function () {
    let query = '';

    const titleInput = $('.university-search-tool input[name="title"]').val();
    if (titleInput) {
        query += '&title=' + titleInput;
    }

    const cityInput = $('.university-search-tool input[name="city"]').val();
    if (cityInput) {
        query += '&city=' + cityInput;
    }

    const createdAtInput = $('.university-search-tool input[name="createdAt"]').val();
    if (createdAtInput) {
        query += '&createdAt=' + createdAtInput;
    }

    const accLevelInput = $('.university-search-tool select[name="accLevel"]').val();
    if (accLevelInput) {
        query += '&accLevel=' + accLevelInput;
    }

    const emailInput = $('.university-search-tool input[name="email"]').val();
    if (emailInput) {
        query += '&email=' + emailInput;
    }

    const statusInput = $('.university-search-tool select[name="status"]').val();
    if (statusInput) {
        query += '&status=' + statusInput;
    }

    showSpinner();

    $.ajax({
        url: '/api/admin/universities?' + query,
        method: 'GET',
        success: function (response) {
            $('.universities-content').children(':not(.university-block-template)').remove();
            if (response.data.length !== 0) {
                displayUniversitiesData(response.data);
            }
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const displayUniversitiesData = function (data) {
    data.forEach(function (university) {
        const template = $('.university-block-template').first().clone();
        template.find('.js-title').text(university.name);
        template.find('.js-created-at').text(university.created_at_formatted);
        template.find('.js-edit-link').attr('href', '/admin/university/' + university.id);
        template.removeClass('hidden').removeClass('university-block-template').addClass('university-block');
        $('.universities-content').append($(template));
    });
}
