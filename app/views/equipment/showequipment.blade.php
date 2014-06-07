@extends('layout')
@section('content')

<h1>{{ $equipment->name }}</h1>

<table>
	<tr><th>Name</th><td>{{ $equipment->name }}</td></tr>
	<tr><th>Installed On</th><td>{{ $equipment->installedOn }}</td></tr>
	<tr><th>Date Removed</th><td>{{ $equipment->removedOn }}</td></tr>
	<tr><th>Maintenance Interval</th><td>{{ $equipment->maintInterval }}</td></tr>
	@if ($equipment->comments)
		<tr><th colspan="2">Comments</th></tr>
		<tr><td colspan="2">{{ $equipment->comments }}</td></tr>
	@endif
</table>

@if (count($logs) > 0)
	<h2>Equipment Logs</h2>
	<table>
		<tr>
			<th>Date</th>
			<th>Maintenance</th>
			<th>Summary</th>
			<th>Comments</th>
		</tr>
	@foreach ($logs as $log)
		<tr>
			<td>{{ $log->logDate }}</td>
			<td>{{ $log->maintenance }}</td>
			<td>{{ $log->summary }}</td>
			<td>{{ $log->comments }}</td>
		</tr>
	@endforeach
	</table>
@endif

<br />
{{ link_to_route('aquariums.equipment.show', 'Go Back', array($aquariumID)) }} :
{{ link_to_route('aquariums.equipment.edit', 'Edit', array($aquariumID, $equipment->equipmentID)) }}

@stop