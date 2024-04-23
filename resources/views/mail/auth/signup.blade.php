<x-mail::message>
# Hi, {{ $user['first_name'] }}!

    You are successfully registered at UniSpace!
    Use the "Reset Password" feature to create a password and login.

<x-mail::button :url="$url">
Log in
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
