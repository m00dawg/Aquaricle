@extends('layout')
@section('content')

@if (isset($user))

<h2>Your Profile</h2>

@if(isset($status))
	<h3>{{ $status }}</h3>
@endif

<table>
	<tr><th>Username</th><td>{{ $user->username }}</td></tr>
	<tr><th>E-Mail</th><td>{{ $user->email }}</td></tr>
	<tr><th>Timezone</th><td>{{ $user->timezone }}</td></tr>
	<tr><th>Active Since</th><td>{{ $user->createdAt}}</td></tr>
	<tr><th>Last Changed</th><td>{{ $user->updatedAt}}</td></tr>
</table>
<br />

{{ link_to_route('user.editprofile', 'Edit Profile') }} :
{{ link_to_route('user.password', 'Change Password') }}


@endif

@stop