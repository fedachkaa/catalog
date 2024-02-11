@extends('layouts.main')

@section('title', 'Profile | UniSpace')

@section('content')
    @switch(auth()->user()->getRoleId())
        @case(\App\Models\UserRole::USER_ROLE_UNIVERSITY_ADMIN)
            @include('userProfile.partials.universityAdminProfile')
            @break

        @case(\App\Models\UserRole::USER_ROLE_STUDENT)
            @include('userProfile.partials.studentProfile')
            @break

        @case(\App\Models\UserRole::USER_ROLE_TEACHER)
            @include('userProfile.partials.teacherProfile')
            @break

        @case(\App\Models\UserRole::USER_ROLE_ADMIN)
            @include('userProfile.partials.adminProfile')
            @break

        @default
            @include('404NotFound')
    @endswitch

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

        <i class="fa-solid fa-lock js-lock-icon"></i>
        <div class="password-block locked">
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
            <div class="reset-link">
                    <a href="{{ route('forget.password.get') }}" target="_blank" class="js-reset-password">Забули пароль?</a>
                </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        initClearErrors();
        initLockPasswordBlock();

        $('.js-change-password').on('click', function () {
            if (!validatePasswordFields()) {
                return;
            }

            $.ajax({
                url: '/user/api/change-password',
                method: 'PUT',
                data: getFormData(),
                success: function (response) {
                    general.showResponse(response.status, response.message);
                    console.log('Успішно!', response);
                },
                error: function (xhr, status, error) {
                    general.showResponse(response.status, response.message);
                    console.error('Помилка:', error);
                }
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
    });
</script>
