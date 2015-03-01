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
	<li><strong>Location:</strong> <span id="location"></span></li>
	<li><strong>Capacity:</strong> <span id="capacity"></span> {{ $measurementUnits['Volume'] }}
		(<span id="length"></span> {{ $measurementUnits['Length'] }} x
		 <span id="width"></span> {{ $measurementUnits['Length'] }}  x
		 <span id="height"></span> {{ $measurementUnits['Length'] }})</li>
	<li><strong>Active Since:</strong> <span id="createdAt"></span></li>
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

<h3>Equipment Maintenance</h3>

<table id="activeEquipment">
<tr><th>Equipment</th><th>Last Maintenance</th><th>Days Since</th><th>Next Due</th></tr>
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


<h3>Latest Logs (Last {{ $lastDays}} Days)</h3>

<table id="logs">
	<tr><th class="logDate">Date</th><th>Summary</th></tr>
</table>

<br />


{{ link_to_route('aquariums.logs.create', 'Log New Entry', array($aquarium->aquariumID)) }}

@stop

@section('footer')
	<script type="text/javascript">
		$.ajax({
		url: "/api/aquarium/{{ $aquariumID }}/temperature",
		success: function( data ) {
		$( "#temperature" ).html( data );
		}
		});


		function displayAquarium(data, status, jqXHR)
		{
			$("#location").append(data.location);
			$("#capacity").append(data.capacity);
			$("#length").append(data.length);
			$("#width").append(data.width);
			$("#height").append(data.height);
			$("#createdAt").append(data.createdAt);
		}

		function displayEquipment(data, status, jqXHR)
		{
			if(data.length == 0)
				$("#activeEquipment").append("<tr><td colspan='4'>No Equipment Found</td></tr>");
			else
			{
				$.each(data, function()
				{
					$("#activeEquipment").append(
						"<tr><td><a class='logs' href='/aquarium/{{ $aquariumID }}/equipment/" + this.equipmentID + "'>" + this.name + "</a>" +
						"</td><td class='lastMaintenance'>" + this.lastMaint +
						"</td><td class='equipmentDaysSince'>" + this.daysSinceMaint +
						"</td><td>" + this.nextMaintDays + "</td></tr>");
				});
			}
		}

		function displayLogs(data, status, jqXHR)
		{
			if(data.length == 0)
				$("#logs").append("<tr><td colspan='2'>No Logs Found</td></tr>");
			else
			{
				$.each(data.data, function()
				{
					$("#logs").append(
						"<tr><td><a class='logs' href='/aquarium/{{ $aquariumID }}/log/" + this.aquariumLogID + "'>" + this.logDate + "</a>" +
						"</td><td>" + this.summary + "</td></tr>");
				});
			}
		}

		function errorCallback(jqXHR, status)
		{
				alert(status);
		}

		jQuery.ajax({
		    type: "GET",
		    url: "/api/v1/aquarium/{{ $aquariumID }}",
				contentType: "application/json",
		   	dataType: "json",
		    success: displayAquarium,
		    error: errorCallback
		});

		jQuery.ajax({
				type: "GET",
				url: "/api/v1/aquarium/{{ $aquariumID }}/equipment/maintenance",
				contentType: "application/json",
				dataType: "json",
				success: displayEquipment,
				error: errorCallback
		});

		jQuery.ajax({
				type: "GET",
				url: "/api/v1/aquarium/{{ $aquariumID }}/logs",
				contentType: "application/json",
				dataType: "json",
				success: displayLogs,
				error: errorCallback
		});


	</script>
@stop
