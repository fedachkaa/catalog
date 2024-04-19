<x-mail::message>
    # Hi, {{ $studentFullName }}!

    Catalog {{ $catalogType }} has been activated.

    Follow the link to view actual topics.

    <x-mail::button :url="$url">
        See catalog
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
