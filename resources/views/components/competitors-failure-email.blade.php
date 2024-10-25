Dear {{ $user->name }},
<br>
<br>
I am sorry but we couldn't search competitors related to your project and add them to it.
<br>
The reason is that you don't have enough credits for that.
<br>
You need at least {{$nbCreditsRequired}} credits to run one project competitors auto-population.
<br>
you might consider buying more credits from your <a href="">{{route('dashboard')}} dashboard</a>.
<br>
<br>
<br>
<br>
Thank you for choosing <a href="{{config('app.url')}}">{{config('app.name')}}</a>, and happy building!
<br>
<br>
Best regards,
<br>
Didier
<br>
Founder of {{config('app.name')}}