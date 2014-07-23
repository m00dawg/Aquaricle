@extends('layout')
@section('content')

<h2>Life : {{ $life->commonName}} 

@if ( $life->userID == Auth::id())
	(<a href="/life/{{ $life->lifeID }}/edit">Edit</a>)
@endif

</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<table>
	<tr>
		<th>Common Name</th>
		<td>{{ $life->commonName }}</td>
	</tr>
	<tr>	
		<th>Scientific Name</th>
		<td>{{ $life->scientificName }}</td>
	</tr>
	<tr>
		<th>Type</th>
		<td>{{ $life->lifeType->lifeTypeName }}</td>
	</tr>
	<tr>
		<th>Description</th>
		<td>{{ $life->description }}</td>
	</tr>
</table>
<br />

<a href="/life">Go Back</a>

@stop