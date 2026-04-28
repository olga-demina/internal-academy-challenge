<p>Hi {{ $user->name }},</p>

<p>
  This is a reminder that you are registered for
  <strong>{{ $workshop->title }}</strong> tomorrow.
</p>

<ul>
  <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($workshop->starts_at)->format('l, F j, Y') }}</li>
  <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($workshop->starts_at)->format('H:i') }} –
    {{ \Carbon\Carbon::parse($workshop->ends_at)->format('H:i') }}</li>
</ul>

<p>We look forward to seeing you there!</p>
