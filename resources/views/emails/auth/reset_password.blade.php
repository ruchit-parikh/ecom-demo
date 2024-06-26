@component('mail::message')
    # Reset your password

    Please click on below link to reset your password
    @component('mail::button', ['url' => url('/reset-password?token=' . $token)])
        Reset Password
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
