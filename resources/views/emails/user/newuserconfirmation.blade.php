@component('mail::message')
# Welcome {{ $user->first_name }} {{ $user->last_name }}.

Thanks for signing up on LN Group. **We really appreciate** your membership.<br><br>

	
Your signup details are as follows: <br><br>

Email: **{{ $user->email }}** <br><br>

Phone: **{{ $user->phone }}** <br><br>


Your account confirmation code: <br><br>

**{{ $user->confirm_code }}**<br><br>

Use this code to confirm your account<br>


@component('mail::button', ['url' => config('constants.site.url')])
View config('app.name') Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
