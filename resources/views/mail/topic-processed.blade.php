<x-mail::message>
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <h1>Привіт, {{ $studentFullName }}!</h1>
        <p>Ваш запит на тему "{{ $topic }}" в каталозі {{ $catalogType }} було {{ $status }}.</p>
        <br>
        <p>З повагою,<br>
            {{ config('app.name') }}
        </p>
    </div>
</x-mail::message>
