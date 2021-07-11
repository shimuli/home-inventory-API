@component('mail::message')
# Introduction

Hello {{$user->name}}
Thank you for creating an account. Please verify your email using the button below:

@component('mail::button', ['url' => route('api.v1.verify', $user->verification_token) ])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
