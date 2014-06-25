@extends('layout')
@section('content')

<h2>{{ $aquarium->name }} ({{ link_to_route('aquariums.edit', 'Edit', array($aquarium->aquariumID)) }})</h2>


<ul>
	<li><strong>Location:</strong> {{ $aquarium->location }}</li>
	<li><strong>Capacity:</strong> {{ $aquarium->capacity }} {{ $measurementUnits['Volume'] }}
		({{ $aquarium->length }} {{ $measurementUnits['Length'] }} x 
		 {{ $aquarium->width }} {{ $measurementUnits['Length'] }}  x 
		 {{ $aquarium->height }} {{ $measurementUnits['Length'] }})</li>
	<li><strong>Active Since:</strong> {{ $aquarium->createdAt }}</li>
	<li><strong>Water Changes:</strong>
		<ul><li>
			<strong>Next Due In:</strong>
			@if ($lastWaterChange)
				{{ $lastWaterChange->daysRemaining }}
					@if ($lastWaterChange->daysRemaining > 1 || $lastWaterChange->daysRemaining < -1)
						Days
					@else
						Day
					@endif
			@else
				Water Never Changed
			@endif
		</li><li>
			<strong>Last Change:</strong>
			@if ($lastWaterChange)
				{{ $lastWaterChange->daysSince }} 
				@if ($lastWaterChange->daysSince > 1)
					Days
				@else
					Day
				@endif
				Ago
				({{ $lastWaterChange->changePct }}% / {{ $lastWaterChange->amountExchanged }} {{ $measurementUnits['Volume'] }}) 
			@else
				Water Never Changed
			@endif
		</li></ul>
	</li>
</ul>

@if ($aquarium->aquariduinoHostname)
	<h3>Graphs</h3>
	<div id="graph">
	<a href="/static/graphs/{{ $aquarium->aquariumID }}-temps-full.png">
	    <img src="/static/graphs/{{ $aquarium->aquariumID }}-temps-thumb.png" />
	</a>
	<a href="/static/graphs/{{ $aquarium->aquariumID }}-relays-full.png">
	    <img src="/static/graphs/{{ $aquarium->aquariumID }}-relays-thumb.png" />
	</a>
	</div>
@endif

<h3>Active Equipment</h3>

<table>
	<tr><th>Equipment</th><th>Last Maintenance</th><th>Days Since</th><th>Next Due</th></tr>
	@if (count($equipment) > 0)
		@foreach($equipment as $equip)
			<tr>
				
				<td>{{ link_to_route('aquariums.equipment.show', 
					$equip->name, 
					array($aquarium->aquariumID, $equip->equipmentID),
					array('class'=>'logs')) }}</td>
				<td>{{ $equip->lastMaint }}</td>
				@if (isset($equip->daysSinceMaint))
					<td>{{ $equip->daysSinceMaint }}</td>
					<td>{{ $equip->nextMaintDays }}</td>
				@else
					<td colspan="2" class="blank"></td>
				@endif
			</tr>
		@endforeach
	@else
		<tr><td colspan="4">No Equipment Has Been Added Yet</td></tr>
	@endif
</table>
<br />

@if (isset($favorites))
<h3>Favorite Actions</h3>

{{ Form::open(array('url' => "aquariums/$aquariumID/logs/favorites")) }}

@foreach($favorites as $favorite)
	{{ Form::submit($favorite->name, array('id' => 5)) }}<br />
@endforeach

@endif	

<h3>Latest Logs</h3>

<table>
	<tr><th>Date</th><th>Summary</th></tr>
	@if (count($logs) > 0)
		@foreach($logs as $log)
			<tr>
				<td>{{ link_to("aquariums/$aquarium->aquariumID/logs/$log->aquariumLogID/edit", 
					$log->logDate, array('class'=>'logs')) }}</td>
				<td>{{ $log->summary }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No Logs Have Been Added Yet</td></tr>
	@endif
</table>

<br />
{{ link_to_route('aquariums.logs.create', 'Log New Entry', array($aquarium->aquariumID)) }}

@stop
