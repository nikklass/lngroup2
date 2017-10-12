@component('mail::message')

#Hi {{ $chat_message_email->recipient_first_name }},

**{{ $chat_message_email->sender_full_name }}** contributed to a discussion you are in.<br><br>

Topic: **{{ $chat_message_email->thread_title }}**<br><br>

@component('mail::panel')
"{{ $chat_message_email->sender_message }}"
@endcomponent
	
{{ config('app.name') }}

@endcomponent
