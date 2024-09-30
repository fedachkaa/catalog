<x-mail::message>
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        <h1 style="font-size: 20px; color: #333;">Привіт, {{ $universityAdminName }}!</h1>
        <p style="font-size: 14px; color: #333;">
            {{ $message }}
        </p>
        @if($status == 'approved')
            <x-mail::button :url="$url" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; font-size: 14px; margin: 10px 0; cursor: pointer;">
                Log in
            </x-mail::button>
        @endif
        <p style="font-size: 14px; color: #333;">
            З повагою,<br>
            {{ config('app.name') }}
        </p>
    </div>
</x-mail::message>
