@component('mail::message')
# Hi

you have been invited to join the team
 **{{$invitation->team->name}}**.
 Because you are not signed up to the paltform, [it is free]({{$url}}),
 then you can accept or reject the invitation.

@component('mail::button', ['url' => ''])
Register for free.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
