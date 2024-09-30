<x-mail::message>
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <h1>Привіт, {{ $user['first_name'] }}!</h1>
        <p>Вас було успішно зареєстровано в UniSpace!</p>
        <br>
        <p>Скористайтесь скиданням паролю для створення нового та авторизації в системі.</p>
        <x-mail::button :url="$url">
            Увійти
        </x-mail::button>
        <br>
        <p>З повагою,<br>
            {{ config('app.name') }}
        </p>
    </div>
</x-mail::message>
