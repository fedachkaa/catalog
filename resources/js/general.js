require('./bootstrap');
window.$ = window.jQuery = require('jquery');

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

module.exports = {
    showResponse,
}
