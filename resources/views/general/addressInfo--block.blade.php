<?php
/**
 * @var string|null $city
 * @var string|null $address
 */
?>

<div class="address-info">
    <div>Адреса</div>
    <div>Місто: <span class="js-city">{{ $city ?? '' }}</span></div>
    <div>Адреса: <span class="js--address">{{ $address ?? '' }}</span></div>
</div>
