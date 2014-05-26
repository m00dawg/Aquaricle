@extends('layout')

@section('content')

<h2>{{ $aquarium->name }}</h2>

<ul>
	<li><strong>Location:</strong> {{ $aquarium->location }}</li>
	<li><strong>Capacity:</strong> {{ $aquarium->capacity }} {{ $volumeUnits }}
		({{ $aquarium->length }} {{ $lengthUnits }} x 
		 {{ $aquarium->width }} {{ $lengthUnits }}  x 
		 {{ $aquarium->height }} {{ $lengthUnits }})</li>
	<li><strong>Active Since:</strong> {{ $aquarium->createdAt }}</li>
</ul>
	
@stop