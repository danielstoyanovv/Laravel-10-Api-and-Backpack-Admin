<x-mail::message>
    {{ __('New user is registered') }}

    {{ $name }}
    {{ $email }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
