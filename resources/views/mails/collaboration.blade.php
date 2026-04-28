<p>Hello {{ explode(' ',$user->name)[0] }},</p>
<p>You have been invited to collaborate on a project. Please click the link below to accept the invitation:</p>
<a href="{{ $accept_link }}">Accept</a>
<p>Alternatively, you can decline the invitation by clicking the link below:</p>
<a href="{{ $decline_link }}">Decline</a>
<p>Thank you for your attention.</p>
<p>Best regards,</p>
<p>{{ $sender}}</p>