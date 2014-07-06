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
	
	@if(count($favorites) > 0)
		@foreach($favorites as $favorite)
			<tr>
				<td>{{ $favorite->name }}</td>
				<td>{{ $favorite->summary }}</td>
				<td>{{ link_to("aquariums/$aquariumID/logs/$favorite->aquariumLogID/",
					'View Log', array('class'=>'logs')) }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="3">No Favorite Actions Added. Add them by giving a name to a log entry.</td></tr>
	@endif
</table>

@endif

@stop