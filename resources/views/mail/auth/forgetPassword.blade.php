<x-mail::message>
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <h1 style="font-size: 20px; color: #333;">Привіт!</h1>
        <p style="font-size: 14px; color: #333;">
            Ви отримали цей лист, тому що ми отримали запит на скидання паролю з вашого акаунту.
        </p>
        <x-mail::button :url="$link" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; font-size: 14px; margin: 10px 0; cursor: pointer;">
            Скинути пароль
        </x-mail::button>
        <p style="font-size: 14px; color: #333;">
            Якщо ви не надсилали запит на скидання паролю, не переходіть по посиланню.
        </p>
        <p style="font-size: 14px; color: #333;">
            З повагою,<br>
            {{ config('app.name') }}
        </p>
    </div>
</x-mail::message>
