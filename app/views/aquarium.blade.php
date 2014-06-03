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
	<li><strong>Last Water Change:</strong>
		@if ($lastWaterChange->logDate)
			{{ $lastWaterChange->changePct }}% {{ $lastWaterChange->daysSince }} Days Ago</li>
		@else
			Water Never Changed
		@endif
</ul>

<h3>Equipment Maintenance</h3>

<table>
	<tr><th>Equipment</th><th>Last Maintenance</th><th>Days Since</th><th>Next Due</th></tr>
	@if (count($equipment) > 0)
		@foreach($equipment as $equip)
			<tr>
				<td>{{ link_to("aquariums/$aquarium->aquariumID/equipment/$equip->equipmentID/edit", $equip->name) }}</td>
				<td>{{ $equip->lastMaint }}</td>
				<td>{{ $equip->daysSinceMaint }}</td>
				<td>{{ $equip->nextMaintDays }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No Equipment Has Been Added Yet</td></tr>
	@endif
</table>
	
<h3>Latest Logs</h3>

<table>
	<tr><th>Date</th><th>Summary</th></tr>
	@if (count($logs) > 0)
		@foreach($logs as $log)
			<tr>
				<td>{{ link_to("aquariums/$aquarium->aquariumID/logs/$log->aquariumLogID/edit", $log->logDate) }}</td>
				<td>{{ $log->summary }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No Logs Have Been Added Yet</td></tr>
	@endif
</table>


<br />
<li>{{ link_to("aquariums/$aquarium->aquariumID/logs/create", 'Log New Entry') }}</li>

@stop