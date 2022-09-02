@component('mail::message')

Welcome {{$user->name}}

Thanks for Sign Up on {{ config('app.name') }}

<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->

Thanks,<br>
{{ config('app.name') }}
@endcomponent
