@extends('layout')
@section('content')

<h2>{{ $aquarium->name }}</h2>

<ul>
	<li><strong>Location:</strong> {{ $aquarium->location }}</li>
	<li><strong>Capacity:</strong> {{ $aquarium->capacity }} {{ $measurementUnits['Volume'] }}
		({{ $aquarium->length }} {{ $measurementUnits['Length'] }} x 
		 {{ $aquarium->width }} {{ $measurementUnits['Length'] }}  x 
		 {{ $aquarium->height }} {{ $measurementUnits['Length'] }})</li>
	<li><strong>Active Since:</strong> {{ $aquarium->createdAt }}</li>
</ul>
	
<h3>Latest Logs</h3>

<table>
	<tr><th>Date</th><th>Summary</th></tr>
	@foreach($logs as $log)
		<tr><td>{{ link_to("aquariums/$aquarium->aquariumID/logs/$log->aquariumLogID/edit", $log->logDate) }}</td><td>{{ $log->summary }}</td></tr>
		@if (isset($log->waterTestLogs))
			<tr><td>{{ $log->waterTestLogs->temperature}}</td></tr>
		@endif
	@endforeach
</table>

<br />
<li>{{ link_to("aquariums/$aquarium->aquariumID/logs/create", 'Add New Entry') }}</li>

@stop