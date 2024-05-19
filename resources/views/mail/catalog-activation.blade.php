<x-mail::message>
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <h1>Привіт, {{ $studentFullName }}!</h1>
        <p>Каталог {{ $catalogType }} був активований. Перейдіть за посиланням нижче, щоб переглянути актуальні теми.</p>
        <x-mail::button :url="$url">
            Перейти до каталогу
        </x-mail::button>
        <p>З повагою,<br>
            {{ config('app.name') }}</p>
    </div>
</x-mail::message>
