require('./bootstrap');
window.$ = window.jQuery = require('jquery');

document.addEventListener("DOMContentLoaded", function() {
    $('.js-user-profile').on('click', function () {
        general.toggleTabsSideBar('js-user-profile');
        getUserData();
    });
});


const showResponse = function (status, message) {
    const newAlert = $('<div>').addClass('alert');

    if (status === 'success') {
        newAlert.addClass('alert-success');
    } else if (status === 'error') {
        newAlert.addClass('alert-danger');
    }
    newAlert.text(message);
    $('.js-flash-messages').append(newAlert);
}

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
            // general.showResponse(response.status, response.message);
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

module.exports = {
    showResponse,
    toggleTabsSideBar,
    toggleContentBlock,
    getUserData,
    displayUserProfileData,
}
