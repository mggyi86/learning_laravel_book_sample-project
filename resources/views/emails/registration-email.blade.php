@component('mail::message')
# Introduction
# Registration Confirmation
## Congratulations {{ $user->name }}!
The body of your message.
You have joined our site and now have access to the following benefits:
* instant access
* 24/7 support
* updated daily content
@component('mail::button', ['url' => 'http://localhost:8000/'])
    Visit Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@component('mail::panel', ['url' => ''])
    You are receiving this email because you subscribed to Sample Project.
    You may Unsubscribe by clicking <a href="/unsubscribe">here</a>.
@endcomponent
@endcomponent
