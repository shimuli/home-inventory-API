@component('mail::message')
# Introduction

Hello {{$user->name}}
We received a request to reset your {{ config('app.name') }} password, use the code below to reset the password

@component('mail::panel')
code : {{ $user->verify_code }}
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
