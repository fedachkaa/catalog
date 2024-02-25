<?php
/**
 * @var string|null $email
 * @var string|null $phoneNumber
 * @var string|null $website
 */
?>

<div class="contact-info">
    <div>Контактна інформація</div>
    <div>Електронна пошта: <span class="js-email">{{ $email ?? '' }}</span></div>
    <div>Номер телефону: <span class="js-phone-number">{{ $phoneNumber ?? '' }}</span></div>
    @if(!empty($website))
        <div>Веб-сайт: <span class="js-website">{{ $website }}</span></div>
    @endif
</div>
