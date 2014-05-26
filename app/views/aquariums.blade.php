@extends('layout')

@section('content')

<h2>Your Aquariums</h2>
<ul>
	@foreach($aquariums as $aquarium)
       	<li>{{ link_to("aquarium/{$aquarium->aquairumID}", $aquarium->name) }}</li>
		
		
	@endforeach
</ul>
	
@stop