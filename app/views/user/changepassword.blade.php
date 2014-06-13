@extends('layout')
@section('content')

<h2>Change Your Password</h2>

@if(isset($status))
	<h3>{{ $status }}</h3>
@endif

<div class="formBox">
	
{{ Form::open(array('route' => 'user.password')) }}
	
<table>
	<tr><th>Current Password</th><td>{{ Form::password('currentPassword', null, array('size' => '32')) }}</td></tr>
	<tr><th>New Password</th><td>{{ Form::password('newPassword1', null, array('size' => '32')) }}</td></tr>
	<tr><th>Re-type New Password</th><td>{{ Form::password('newPassword2', null, array('size' => '32')) }}</td></tr>
</table>
<br />
{{ Form::submit('Update') }}

{{ Form::close() }}

</div>

@stop