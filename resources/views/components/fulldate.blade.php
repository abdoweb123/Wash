@props(['date'])

<span class="bg-primary-300 px-1 rounded">{{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</span>
<span class="bg-primary-200 px-1 rounded">{{ \Carbon\Carbon::parse($date)->format('h:i A') }}</span>
<span class="bg-primary-100 px-1 rounded">{{ \Carbon\Carbon::parse($date)->format('l') }}</span>

