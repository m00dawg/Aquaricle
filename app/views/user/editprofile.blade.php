@extends('layout')
@section('content')

@if (isset($user))

<h2>Your Profile</h2>

@if(isset($status))
	<h3>{{ $status }}</h3>
@endif

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<div class="formBox">
{{ Form::model($user, 
	array('route' => array("user.editprofile"), 'method' => 'post')) }}	
	
<table>
	<tr><th>Username</th><td>{{ $user->username }}</td></tr>
	<tr><th>E-Mail</th><td>{{ Form::text('email', null, array('size' => '32')) }}</td></tr>
	<tr>
		<th>Receive E-mail Reminders?</th>
		<td>{{ Form::select('emailReminders', 
				array('Yes' => 'Yes', 'No' => 'No'), $user->emailReminders) }}</td>
	</tr>
	<tr>
		<th>Timezone</th>
		<td>{{ Form::select('timezone', $timezones, $user->timezoneID) }}</td>
	</tr>
	
	<tr><th>Active Since</th><td>{{ $user->createdAt}}</td></tr>
</table>
<br />
{{ Form::submit('Update') }}

{{ Form::close() }}

</div>


@endif

@stop