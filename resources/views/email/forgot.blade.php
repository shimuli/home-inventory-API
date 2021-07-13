@component('mail::message')
Hello {{$user->name}}
We received a request to reset your {{ config('app.name') }} password, use the code below to reset the password

@component('mail::panel')
code : {{ $user->verify_code }}

@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
