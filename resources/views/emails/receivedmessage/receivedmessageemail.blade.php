@component('mail::message')

# Hello admin.

You have received a new message from **{{ $message->user->email }}**.<br><br>
	
The message details are as follows: <br><br>

Email: **{{ $message->user->email }}** <br><br>

Phone: **{{ $message->user->phone }}** <br><br>

Country: **{{ $message->user->phone_country }}** <br><br>


##Message

Sent at: **{{ $message->created_at }}** <br><br>

Subject: **{{ $message->subject }}** <br><br>

Message: **{{ $message->message }}** <br><br>

@component('mail::button', ['url' => config('constants.site.url')])
Go To config('app.name') Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent
