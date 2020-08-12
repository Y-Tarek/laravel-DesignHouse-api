@component('mail::message')
# Hi

you have been invited to join the team
 **{{$invitation->team->name}}**.
 Because you are already registered to the platform,
 You can accept or reject the invitation.

@component('mail::button', ['url' => ''])
Go to dashboard.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent