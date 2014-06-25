@extends('layout')
@section('content')

<h2>Log Details</h2>

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

@if (isset($log))
	<table>
		<tr><th>Date</th><td>{{ $log->logDate }}</td></tr>
		<tr><th>Summary</th><td>{{ $log->summary }}</td></tr>
		@if (!empty($log->comments))
			<tr><th colspan="2">Comments</th></tr>
			<tr><td colspan="2">{{ $log->comments }}</td></tr>
		@endif
	</table>

	@if (isset($log->temperature) || 
		 isset($log->ammonia) ||
		 isset($log->nitrites) ||
		 isset($log->nitrates) ||
		 isset($log->phosphates) ||
		 isset($log->pH) ||
		 isset($log->KH) ||
		 isset($log->amountExchanged))
		<br />
		<table>	
			<tr>
				<th>Temperature</th>
				<th>Ammonia</th>
				<th>Nitrites</th>
				<th>Nitrates</th>
				<th>Phosphates</th>
				<th>pH</th>
				<th>KH</th>
				<th>Water Exchanged</th>
			</tr>
			<tr>
				<td>{{ $log->temperature }}</td>
				<td>{{ $log->ammonia }}</td>
				<td>{{ $log->nitrites }}</td>
				<td>{{ $log->nitrates }}</td>
				<td>{{ $log->phosphates }}</td>
				<td>{{ $log->pH }}</td>
				<td>{{ $log->KH }}</td>
				<td>{{ $log->amountExchanged }}</td>
			</tr>
		</table>
	@endif
	<br />
	
	@if (count($waterAdditiveLogs) > 0)
		<table>
			<tr><th>Additive</th><th>Amount</th></tr>
			@foreach($waterAdditiveLogs as $additiveLog)
				<tr>
					<td>{{ $additiveLog->name }}</td>
					<td>{{ $additiveLog->amount }}</td>
				</tr>	
			@endforeach
		</table>
		<br />
	@endif
	
	@if (count($equipmentLogs) > 0)
		<table>
			<tr><th>Equipment</th><th>Maintenance</th></tr>
			@foreach($equipmentLogs as $equipmentItem)
				<tr>
					<td>{{ $equipmentItem->name }}</td>
					<td>{{ $equipmentItem->maintenance }}</td>
				</tr>
			@endforeach
		</table>
		<br />
	@endif
	
	@if (count($foodLogs) > 0)
		<table>
			<tr><th>Food</th></tr>
			@foreach($foodLogs as $foodItem)
				<tr><td>{{ $foodItem->name }}</td></tr>
			@endforeach
		</table>
	@endif

	<h3>Modify Log Entry</h3>
@else
	<h3>Add New Log Entry</h3>
@endif


<div class="formBox">
	@if (isset($log))
		{{ Form::model($log, 
			array('route' => array("aquariums.logs.update", $aquariumID, $log->aquariumLogID), 'method' => 'put')) }}		
	@else
		{{ Form::open(array('url' => "aquariums/$aquariumID/logs")) }}
	@endif
	
	<table>
		<tr><th colspan="2">Water Logs</th></tr>
		<tr>
			<th>Temperature</th>
			<td>{{ Form::text('temperature', null, array('size' => '8')) }} &deg;
				@if ($measurementUnits == 'Metric')
					C
				@else
					F
				@endif
			</td>
		</tr>
		<tr><th>Ammonia</th><td>{{ Form::text('ammonia', null, array('size' => '8')) }} ppm</td></tr>
		<tr><th>Nitrites</th><td>{{ Form::text('nitrites', null, array('size' => '8')) }} ppm</td></tr>
		<tr><th>Nitrates</th><td>{{ Form::text('nitrates', null, array('size' => '8')) }} ppm</td></tr>
		<tr><th>Phosphates</th><td>{{ Form::text('phosphates', null, array('size' => '8')) }} ppm</td></tr>
		<tr><th>pH</th><td>{{ Form::text('pH', null, array('size' => '8')) }}</td></tr>
		<tr><th>KH</th><td>{{ Form::text('KH', null, array('size' => '8')) }} &deg;</td></tr>
		<tr>
			<th>Water Exchanged</th>
			<td>{{ Form::text('amountExchanged', null, array('size' => '8')) }}
				@if ($measurementUnits == 'Metric')
					L
				@else
					Gal
				@endif
			</td>
		</tr>
	</table>
	<br />
	<table>
		<tr>
			<th>Food</th>
			<td colspan="2">
				@foreach($food as $item)
					{{ Form::checkbox('food[]', $item->foodID, $item->selected) }} {{ $item->name }}<br />
				@endforeach
			</td>
		</tr>

		<tr>
			<th>Water Additive</th>
			<td>{{ Form::select('waterAdditive', $waterAdditives) }}</td>
			<td>{{ Form::text('waterAdditiveAmount', null, array('size' => '8')) }} mL </td>
		</tr>
	
		<tr>
			<th>Equipment</th>
			<td>{{ Form::select('equipment', $equipment) }}</td>
			<td>{{ Form::checkbox('equipmentMaintenance') }} Maintenance</td>
		</tr>

	</table>

	<br />
	
	{{ Form::label('logDate', 'Date') }}: {{ Form::text('logDate') }}
	
	@if(!isset($log))
		(Leave Blank for Current Time)
	@endif
	
	<br />
	{{ Form::label('comments', 'Comments') }}: {{ Form::textarea('comments') }}<br />
			
	@if (isset($log))
		{{ Form::submit('Update') }}
		<input type="submit" name="delete" value="Delete">
	@else
		{{ Form::submit('Add') }}
	@endif	
	
	
	{{ Form::close() }}

</div>

{{ link_to("aquariums/$aquariumID/", "Go Back") }}</td>


@stop