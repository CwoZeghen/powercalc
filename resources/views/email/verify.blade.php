@component('mail::message')

    Email verification.

    Dear {{ ucfirst($data->name) }}, please clic the link below to verify your email

    {{route('mail.verify', $data->token)}}

@endcomponent
