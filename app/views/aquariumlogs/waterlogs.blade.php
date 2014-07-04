@extends('layout')
@section('content')

<h2>Water Logs</h2>

<table>	
	<tr>
		<th>Date</th>
		<th>Temperature 
		@if ($measurementUnits == 'Metric')
			(C)
		@else
			(F)
		@endif
		</th>
		<th>Ammonia</th>
		<th>Nitrites</th>
		<th>Nitrates</th>
		<th>Phosphates</th>
		<th>pH</th>
		<th>KH</th>
		<th>Water Exchanged
			@if ($measurementUnits == 'Metric')
				(Liters)
			@else
				(Gallons)
			@endif
		</th>
	</tr>
	
	@if (count($waterLogs) > 0)
		@foreach($waterLogs as $log)
			<tr>
				<td>{{ $log->logDate }}</td>
				<td>{{ $log->temperature }}</td>
				<td>{{ $log->ammonia }}</td>
				<td>{{ $log->nitrites }}</td>
				<td style="background-color: {{ $log->nitrateBackgroundColor() }}">{{ $log->nitrates }}</td>
				<td>{{ $log->phosphates }}</td>
				<td>{{ $log->pH }}</td>
				<td>{{ $log->KH }}</td>
				<td>{{ $log->amountExchanged }}</td>
			</tr>
		@endforeach
	@endif
</table>

@stop