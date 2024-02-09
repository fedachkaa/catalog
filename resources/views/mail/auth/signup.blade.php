<x-mail::message>
# Hi, {{ $user['first_name'] }}!

    You are successfully registered at UniSpace!
    To log in your account use email you enter during registration and password {{ $password }}.
    After log in you can change yor password in profile setting.

<x-mail::button :url="$url">
Log in
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
