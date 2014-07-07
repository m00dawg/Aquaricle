@extends('layout')
@section('content')

<h2>Water Logs</h2>

<table>	
	<tr>
		<th>Date</th>
		<th>Temperature ({{ $measurementUnits['Temperature'] }})</th>
		<th>Ammonia</th>
		<th>Nitrites</th>
		<th>Nitrates</th>
		<th>Phosphates</th>
		<th>pH</th>
		<th>KH</th>
		<th>Water Exchanged ({{ $measurementUnits['Volume'] }})</th>
	</tr>
	
	@if (count($waterLogs) > 0)
		@foreach($waterLogs as $log)
			<tr>
				<td>{{ $log->logDate }}</td>
				<td>{{ $log->temperature }}</td>
				<td style="background-color: {{ $log->ammoniaBackgroundColor() }}">{{ $log->ammonia }}</td>
				<td style="background-color: {{ $log->nitriteBackgroundColor() }}">{{ $log->nitrites }}</td>
				<td style="background-color: {{ $log->nitrateBackgroundColor() }}">{{ $log->nitrates }}</td>
				<td style="background-color: {{ $log->phosphateBackgroundColor()}}">{{ $log->phosphates }}</td>
				<td>{{ $log->pH }}</td>
				<td>{{ $log->KH }}</td>
				<td>{{ $log->amountExchanged }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="9">No Water Tests Have Been Logged Yet.</td></tr>
	@endif
</table>

@stop