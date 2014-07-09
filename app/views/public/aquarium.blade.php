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
						({{ $lastWaterChange->changePct }}% / 
						{{ $lastWaterChange->amountExchanged }} {{ $measurementUnits['Volume'] }}) 
					@endif
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
				<td>{{ $equip->name }}</td>
				<td>{{ $equip->lastMaint }}</td>
				@if (isset($equip->daysSinceMaint))
					<td>{{ $equip->daysSinceMaint }}</td>
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

<h3>Latest Logs</h3>
<table>
	<tr><th class="logDate">Date</th><th>Summary</th></tr>
	@if (count($logs) > 0)
		@foreach($logs as $log)
			<tr>
				<td>{{ $log->logDate }}</td>
				<td>
					@if ($log->summary)
						{{ $log->summary }}
					@endif
					@if ($log->comments)
						@if($log->summary)
							<br />
						@endif
						<b>Comments</b>: {{ $log->comments }}
					@endif
				</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No Logs Have Been Added Yet</td></tr>
	@endif
</table>
@stop
