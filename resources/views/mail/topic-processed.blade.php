<x-mail::message>
    # Hi, {{ $studentFullName }}!

    Your topic request "{{ $topic }}" in catalog {{ $catalogType }} has been {{ $status }}.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
