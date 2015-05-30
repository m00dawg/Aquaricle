@extends('layout')
@section('content')

<h2>Water Logs</h2>

@include('waterlogs.graphs')

<h3>Data</h3>

<table>	
	<tr>
		<th>Date</th>
		<th>Temp ({{ $measurementUnits['Temperature'] }})</th>
		<th>Ammonia</th>
		<th>Nitrites</th>
		<th>Nitrates</th>
		<th>Phosphates</th>
		<th>pH</th>
		<th>KH</th>
		<th>GH</th>
		<th>TDS</th>
		<th>Water Exchanged</th>
	</tr>
	
	@if (count($waterLogs) > 0)
		@foreach($waterLogs as $log)
			<tr>
				<td>{{ link_to("aquariums/$aquariumID/logs/$log->aquariumLogID/edit", 
					$log->logDate, array('class'=>'logs')) }}</td>
				<td>{{ $log->temperature }}</td>
				<td style="background-color: {{ $log->ammoniaBackgroundColor() }}">{{ $log->ammonia }}</td>
				<td style="background-color: {{ $log->nitriteBackgroundColor() }}">{{ $log->nitrites }}</td>
				<td style="background-color: {{ $log->nitrateBackgroundColor() }}">{{ $log->nitrates }}</td>
				<td style="background-color: {{ $log->phosphateBackgroundColor()}}">{{ $log->phosphates }}</td>
				<td>{{ $log->pH }}</td>
				<td>{{ $log->KH }}</td>
				<td>{{ $log->GH }}</td>
				<td>{{ $log->TDS }}</td>
				<td>
					@if ( $log->amountExchanged )
						{{ $log->amountExchanged }} {{ $measurementUnits['Volume'] }} ({{ $log->changePct }}%)
					@endif
				</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="9">No Water Tests Have Been Logged Yet.</td></tr>
	@endif
</table>
<br />
<br />
<div class="helpBox">
	<p>The cycling graph will only graph entries which include both ammonia, nitriates, nitrates.
		If you omit one of these in a water test, that entry will not be used in the graph.</p>
</div>

@stop

@section('footer')
	@parent
	<script src="/js/vendor/chart.js"></script>
@stop