require('./bootstrap');
window.$ = window.jQuery = require('jquery');
require('datatables.net');

document.addEventListener("DOMContentLoaded", function() {
    $('.js-user-profile').on('click', function () {
        general.toggleTabsSideBar('js-user-profile');
        getUserData();
    });
});

const toggleTabsSideBar = function(activeTabClass) {
    $('.sidebar-menu-title').each(function () {
        if ($(this).hasClass(activeTabClass)) {
            $(this).addClass('active-tab');
        } else {
            $(this).removeClass('active-tab');
        }
    });
}

const getUserData = function() {
    showSpinner();

    $.ajax({
        url: '/api/profile',
        method: 'GET',
        success: function (response) {
            displayUserProfileData(response.data);
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const getUserBaseInfo = function (userId) {
    showSpinner();

    $.ajax({
        url: '/api/user/' + userId,
        method: 'GET',
        success: function (response) {
            $('#userInfoModal').find('.js-name').text(response.data.full_name);
            $('#userInfoModal').find('.js-role').text(response.data.role_text);
            $('#userInfoModal').find('.js-email').text(response.data.email);
            showModal('userInfoModal');
            hideSpinner();
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
            hideSpinner();
        }
    });
}

const displayUserProfileData = function(data) {
    const userBlock = $('.js-user-info');

    userBlock.find('.js-user-role').text(data.role_id);
    userBlock.find('.js-user-name').text(data.full_name);
    userBlock.find('.js-phone-number').text(data.phone_number);
    userBlock.find('.js-email').text(data.email);
}

const showModal = function (id) {
    const modal = $('#' + id);
    modal.css('display', 'block');

    const closeBtn = modal.find('.close');

    closeBtn.on('click', function() {
        modal.css('display', 'none');
    });

    $(window).on('click', function(event) {
        if (event.target === modal[0]) {
            modal.css('display', 'none');
        }
    });
}

const hideModal = function (id) {
    const modal = $('#' + id);
    modal.css('display', 'none');
}

const clearModal = function (id, attributesToRemove = []) {
    const modal = $('#' + id);

    modal.find('input').val('');
    modal.find('select').val('');
    modal.find('p.error-message').empty();
    attributesToRemove.forEach(attr => {
        modal.removeAttr('data-' + attr);
    });
}

const showSpinner = function () {
    $('#spinner').removeClass('hidden');
}

const hideSpinner = function () {
    $('#spinner').addClass('hidden');
}

const showErrors = function (errors, selectorBlock) {
    Object.entries(errors).forEach(function([key, errorMessage]) {
        const errorParagraph = $(selectorBlock).find(`p.error-message.${key}-error-message`);
        errorParagraph.text(errorMessage);
    });
}

const initPagination = function (pagination) {
    const paginationBlock = $('.js-pagination');
    paginationBlock.find('.pagination-first').attr('data-page', 1);
    paginationBlock.find('.pagination-previous').attr('data-page', pagination.before);
    paginationBlock.find('.pagination-next').attr('data-page', pagination.next);
    paginationBlock.find('.pagination-last').attr('data-page', pagination.last);
    paginationBlock.find('.pagination-message').text(`You are on the page ${pagination.current} of ${pagination.totalPages}`);
    paginationBlock.removeClass('hidden');
}

module.exports = {
    toggleTabsSideBar,
    getUserData,
    displayUserProfileData,
    showModal,
    hideModal,
    clearModal,
    showSpinner,
    hideSpinner,
    showErrors,
    getUserBaseInfo,
    initPagination,
}
