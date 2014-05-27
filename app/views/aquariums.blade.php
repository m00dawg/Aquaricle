@extends('layout')

@section('content')

<h2>Your Aquariums</h2>
<ul>
	@foreach($aquariums as $aquarium)
		<li>{{ link_to_route('aquariums.show', $aquarium->name, array($aquarium->aquariumID)) }}</li>
	@endforeach
</ul>
	
@stop