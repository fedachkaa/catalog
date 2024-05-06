const {toggleTabsSideBar, showSpinner, hideSpinner} = require("../general");

document.addEventListener("DOMContentLoaded", function () {
    toggleTabsSideBar('js-user-profile');

    initClearErrors();
    initLockPasswordBlock();

    $('.js-change-password').on('click', function () {
        if (!validatePasswordFields()) {
            return;
        }

        showSpinner();

        $.ajax({
            url: '/user/api/change-password',
            method: 'PUT',
            data: getFormData(),
            success: function (response) {
                $('.password-block').addClass('locked');
                $(this).removeClass('fa-unlock').addClass('fa-lock');
                hideSpinner();
                console.log('Успішно!');
            },
            error: function (xhr, status, error) {
                hideSpinner();
                console.error('Помилка:', error);
            }
        });

    });
});

function initLockPasswordBlock() {
    $('.js-lock-icon').on('click', function () {
        if ($(this).hasClass('fa-lock')) {
            $('.password-block').removeClass('locked');
            $(this).removeClass('fa-lock').addClass('fa-unlock');
        } else {
            $('.password-block').addClass('locked');
            $(this).removeClass('fa-unlock').addClass('fa-lock');
        }
    });
}

function validatePasswordFields() {
    const oldPasswordEl = $('.js-old-password');
    const newPasswordEl = $('.js-password');
    const passwordConfirmEl = $('.js-password-confirm');

    const fields = [newPasswordEl, passwordConfirmEl];
    const errorMessage = 'Пароль має складатись з 8 символів та містити мінімуму 1 цифру та 1 букву латинського алфавіту';

    if (oldPasswordEl.val().length === 0) {
        oldPasswordEl.parent().find('.error-message').text('Старий пароль не може бути пустим');
        oldPasswordEl.css('border-color', 'red');
        return false;
    }

    for (const field of fields) {
        const value = field.val();

        if (!validatePasswordField(value)) {
            field.parent().find('.error-message').text(errorMessage);
            field.css('border-color', 'red');
            return false;
        }
    }

    if (newPasswordEl.val() !== passwordConfirmEl.val()) {
        passwordConfirmEl.parent().find('.error-message').text('Паролі не співпадають');
        passwordConfirmEl.css('border-color', 'red');
        return false;
    }

    return true;
}

function validatePasswordField(value) {
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    return passwordRegex.test(value);
}

function getFormData() {
    return {
        '_token': $('input[name="_token"]').val(),
        'old_password': $('.js-old-password').val(),
        'password': $('.js-password').val(),
        'password_confirmation': $('.js-password-confirm').val(),
    };
}

function initClearErrors() {
    $('input').on('focus', function () {
        $(this).parent().find('.error-message').text('');
        $(this).css('border-color', 'rgba(17, 24, 39, var(--tw-border-opacity))')
    })
}
