@extends('layouts.main')

@section('title', 'UniSpace | Profile')

@section('content')
    <div id="nav">
        <ul class="sidebar_nav">
            <li class="sidebar-menu-title active-tab">
                <a>
                    <i class="fas fa-user"></i>
                    <span>Особисті дані</span>
                </a>
            </li>
            <li class="sidebar-menu-title">
                <a>
                    <i class="fas fa-book"></i>
                    <span>Навчання</span>
                </a>
            </li>
            <li class="sidebar-menu-title">
                <a>
                    <i class="fas fa-square-check"></i>
                    <span>Запити</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="pl-52">
        <div class="name-info">
            <div class="text-3xl">{{ \App\Models\UserRole::AVAILABLE_USER_ROLES[$user['role_id']] }}</div>
            <div class="text-3xl">{{$user['last_name'] . ' ' . $user['first_name'] }} </div>
        </div>
        <div class="contact-info">
            <div>Контактна інформація</div>
            <div>Електронна пошта: {{ $user['email'] }}</div>
            <div>Номер телефону: {{$user['phone_number']}}</div>
        </div>

        <div class="password-block">
            <div>Пароль</div>
            <div class="password-form-group">
                <label for="old_password">Старий пароль</label>
                <input type="password" name="old_password" class="js-old-password form-control">
                <p class="error-message old_password-message">
            </div>
            <div class="password-form-group">
                <label for="password">Новий пароль</label>
                <input type="password" name="password" class="js-password form-control">
                <p class="error-message password-message">
            </div>
            <div class="password-form-group">
                <label for="old_password">Підтвердження паролю</label>
                <input type="password" name="password_confirmation" class="js-password-confirm form-control">
                <p class="error-message password-confirm-message">
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button class="js-change-password save-btn">Оновити пароль</button>
        </div>

    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initClearErrors();

        $('.js-change-password').on('click', function () {
            if (!validatePasswordFields()) {
                return;
            }

            $.ajax({
                url: '/user/api/change-password',
                method: 'PUT',
                data: getFormData(),
                success: function (response) {
                    console.log('Успішно!', response);
                },
                error: function (xhr, status, error) {
                    console.error('Помилка:', error);
                }
            });

        });

        function validatePasswordFields() {
            const oldPasswordEl = $('.js-old-password');
            const newPasswordEl = $('.js-password');
            const passwordConfirmEl = $('.js-password-confirm');

            const fields = [oldPasswordEl, newPasswordEl, passwordConfirmEl];
            const errorMessage = 'Пароль має складатись з 8 символів та містити мінімуму 1 цифру та 1 букву латинського алфавіту';

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
    });
</script>
