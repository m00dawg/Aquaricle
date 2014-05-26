@extends('layout')

@section('content')

<h2>Your Aquariums</h2>
<ul>
	@foreach($aquariums as $aquarium)
       	<li>{{ link_to("aquariums/aquarium/{$aquarium->aquariumID}", $aquarium->name) }}</li>
		
		
	@endforeach
</ul>
	
@stop