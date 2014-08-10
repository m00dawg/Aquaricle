@extends('layout')
@section('content')

<p>Welcome to Aquaricle, an aquarium manager designed to help make maintaining your aquairums easier!
Aquaricle is currently being fairly aggressively developed (hence this kinda lame page). It is 
not the only aquarium manager out there, but has a number of features that might make it compelling
for you to try.</p>

<ul>
	<li>Wanna give it a go? <a href="/signup">Signup</a> for an account!</li>
	<li>Not sure about it? Try out the <a href="http://demo.aquaricle.com">Demo</a> site!</li>
	<li>Already have an account? <a href="/login">Login</a>!</li>
	<li>Find a bug? Go <a href="https://github.com/m00dawg/Aquaricle/issues">here</a></li>
</ul>

@foreach ($news as $item)

	<h1>{{ $item->title }}</h1>

@endforeach


@stop