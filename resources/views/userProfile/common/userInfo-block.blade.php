<?php
/**
 * @var array $userData
 */
?>

<div class="pl-56">
    <div class="name-info">
        <div class="text-3xl js-user-role"><?= \App\Models\UserRole::AVAILABLE_USER_ROLES[$userData['role_id']]; ?></div>
        <div class="text-3xl js-user-name"><?= $userData['full_name']; ?></div>
    </div>

    <div class="contact-info">
        <div>Контактна інформація</div>
        <div>Електронна пошта: <span class="js-email">{{ $userData['email'] ?? '' }}</span></div>
        <div>Номер телефону: <span class="js-phone-number">{{ $userData['phone_number'] ?? '' }}</span></div>
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

