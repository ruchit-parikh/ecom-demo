@component('mail::message')
    # Your password is changed

    You can login to system with your new credentials using below link
    @component('mail::button', ['url' => url('/login')])
        Login
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
