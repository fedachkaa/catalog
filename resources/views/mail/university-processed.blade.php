<x-mail::message>
    # Hi, {{ $universityAdminName }}!

    University "{{ $universityName }}" has been {{ $status }}.
    Use the "Reset Password" feature to create a password and begin filling out your university information.
    <x-mail::button :url="$url">
        Log in
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
