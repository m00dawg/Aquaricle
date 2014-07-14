@extends('layout')
@section('content')

<h2>Signup</h2>

@foreach ($errors->all() as $message)
	{{ $message }}<br />
@endforeach

<div class="formBox">
{{ Form::open(array('url' => 'signup')) }}
	{{ Form::label('username', 'Username') }}: {{ Form::text('username') }}<br />
	{{ Form::label('email', 'E-Mail') }}: {{ Form::text('email') }}<br />	
	{{ Form::label('password', 'Password') }}: {{ Form::password('password') }}<br />
	{{ Form::label('password_confirmation', 'Confirm Password') }}: 
		{{ Form::password('password_confirmation') }}<br />
	{{ Form::label('timezone', 'Timezone')}}: 
		{{ Form::select('timezone', $timezones, 94) }}<br />
	<br />
	{{ Form::submit('Signup') }}
{{ Form::close() }}
</div>

@stop