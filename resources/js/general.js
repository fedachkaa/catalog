require('./bootstrap');
window.$ = window.jQuery = require('jquery');

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

const toggleContentBlock = function(containerClass, selectorClass, activeBlockClass) {
    $(`.${containerClass}`).find(`.${selectorClass}`).each(function () {
        if ($(this).hasClass(activeBlockClass)) {
            $(this).removeClass('hidden');
        } else {
            $(this).addClass('hidden');
        }
    })
}

const getUserData = function() {
    $.ajax({
        url: '/api/profile',
        method: 'GET',
        success: function (response) {
            displayUserProfileData(response.data);
            console.log('Успішно!', response);
        },
        error: function (xhr, status, error) {
            console.error('Помилка:', error);
        }
    });
}

const displayUserProfileData = function(data) {
    const userBlock = $('.js-user-info');

    userBlock.find('.js-user-role').text(data.role_id);
    userBlock.find('.js-user-name').text(data.full_name);
    userBlock.find('.js-phone-number').text(data.phone_number);
    userBlock.find('.js-email').text(data.email);

    userBlock.removeClass('hidden');
    $('.js-university-info').addClass('hidden');
}

const showModal = function (id) {
    var modal = $('#' + id);
    modal.css('display', 'block');

    var closeBtn = modal.find('.close');

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
    var modal = $('#' + id);
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
module.exports = {
    toggleTabsSideBar,
    toggleContentBlock,
    getUserData,
    displayUserProfileData,
    showModal,
    hideModal,
    clearModal,
    showSpinner,
    hideSpinner,
}
