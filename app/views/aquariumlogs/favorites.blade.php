@extends('layout')
@section('content')

<h2>Favorite Log Entries</h2>

@if (isset($favorites))

<table>
	<tr>
		<th>Name</th>
		<th>Summary</th>
		<th></th>
	</tr>
	
	@foreach($favorites as $favorite)
		<tr>
			<td>{{ $favorite->name }}</td>
			<td>{{ $favorite->summary }}</td>
			<td>{{ link_to("aquariums/$aquariumID/logs/$favorite->aquariumLogID/",
				'View Log', array('class'=>'logs')) }}</td>
		</tr>
	@endforeach
	
</table>

@endif

@stop