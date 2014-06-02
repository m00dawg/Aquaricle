@extends('layout')
@section('content')

<h2>Login</h2>

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

<div class="formBox">
	
{{ Form::open(array('url' => 'login')) }}
	{{ Form::label('username', 'Username') }}: {{ Form::text('username') }}<br />
	{{ Form::label('password', 'Password') }}: {{ Form::password('password') }}<br />
	{{ Form::submit('Login') }}
{{ Form::close() }}
</div>

@stop