@extends('layout')
@section('content')

<h2>{{ $aquarium->name }}

@if (!Request::is('public/*') && $aquarium->visibility == 'Public')
	({{ link_to_route('aquariums.edit', 'Edit', array($aquarium->aquariumID)) }} /
	{{ link_to_route('public.aquariums', 'Public Link', array($aquarium->aquariumID)) }})
@elseif (!Request::is('public/*'))
	({{ link_to_route('aquariums.edit', 'Edit', array($aquarium->aquariumID)) }})
@endif


</h2>

<ul>
	<li><strong>Location:</strong> {{ $aquarium->location }}</li>
	<li><strong>Capacity:</strong> {{ $aquarium->capacity }} {{ $measurementUnits['Volume'] }}
		({{ $aquarium->length }} {{ $measurementUnits['Length'] }} x 
		 {{ $aquarium->width }} {{ $measurementUnits['Length'] }}  x 
		 {{ $aquarium->height }} {{ $measurementUnits['Length'] }})</li>
	<li><strong>Active Since:</strong> {{ $aquarium->createdAt }}</li>
	<li><strong>Water Changes:</strong>
		<ul>
			<li>
				<strong>Last Change:</strong>
				@if ($lastWaterChange)
					@if ($lastWaterChange->daysSince == 0)
						Today
					@else
						{{ $lastWaterChange->daysSince }} 
						@if ($lastWaterChange->daysSince > 1)
							Days
						@else
							Day
						@endif
						Ago
					@endif
					({{ $lastWaterChange->changePct }}% / 
					{{ $lastWaterChange->amountExchanged }} {{ $measurementUnits['Volume'] }}) 
				@else
					Water Never Changed
				@endif
			</li>
			<li>
			<strong>Next Due In:</strong>
			@if ($lastWaterChange)
				<span class="{{ $lastWaterChange->nextWaterChangeClass() }}">
				@if ($lastWaterChange->daysRemaining == 0)
					Today
				@else
					{{ $lastWaterChange->daysRemaining }}
					@if ($lastWaterChange->daysRemaining > 1 || $lastWaterChange->daysRemaining < -1)
						Days
					@else
						Day
					@endif
				@endif
				</span>
			@else
				<span class="waterNeverChanged">Water Never Changed</span>
			@endif
			</li>
		</ul>
	</li>
	@if ($aquarium->sparkID && $aquarium->sparkToken)
		<li><strong>Current Temperature:</strong> 
			<span id="temperature">Updating</spam></li>
	@endif
</ul>

@if ($aquarium->sparkID && $aquarium->sparkToken)
	<h3>Graphs</h3>
	<div id="graph">
	
	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-daily-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-daily-small.png">
	</a>
		
	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-daily-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-daily-small.png">
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
				<td class="lastMaintenance"> {{ $equip->lastMaint }}</td>
				@if (isset($equip->daysSinceMaint))
					<td class="equipmentDaysSince">{{ $equip->daysSinceMaint }}</td>
					<td class="{{ $equip->nextMaintClass() }}">{{ $equip->nextMaintDays }}</td>
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

@if (count($favorites) > 0)
	<h3>Favorite Actions</h3>
	{{ Form::open(array('url' => "aquariums/$aquariumID/logs/favorites")) }}
	@foreach($favorites as $favorite)
		{{ Form::radio('favoriteLog', $favorite->aquariumLogID) }} {{ $favorite->name }} <br />
	@endforeach
	<br />
	{{ Form::submit('Process') }}<br />
	{{ Form::close() }}
@endif	


<h3>Latest Logs</h3>
@include('aquariumlogs.logsummary')

{{ link_to_route('aquariums.logs.create', 'Log New Entry', array($aquarium->aquariumID)) }}

@stop

@section('footer')
	<script>
		$.ajax({
		url: "/api/aquarium/{{ $aquariumID }}/temperature",
		success: function( data ) {
		$( "#temperature" ).html( data );
		}
		});
	</script>
@stop
