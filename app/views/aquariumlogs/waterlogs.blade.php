@extends('layout')
@section('content')

<h2>Water Logs</h2>

<h3>Graphs</h3>

<div id="graphs">
	<h4>Basic Tests</h4>
	<canvas id="waterTestsGraph"></canvas>
	<script>basicTestsChart.generateLegend();</script>
</div>


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

@stop

@section('footer')
	<script src="/js/vendor/chart.js"></script>
	
	<script>
		(function()
		{
			var ctx = document.getElementById('waterTestsGraph').getContext('2d');
			var data = 
			{
				labels: {{ json_encode($logDateList) }},
				datasets: [
					{
						label: "Nitrates",
						data: {{ json_encode($nitrateList) }},
						fillColor: "rgba(250, 100, 00, 0.75)",
						strokeColor: "#ff1100",
						pointColor: "#ffff00",
					},
					{
						label: "Nitrites",
						data: {{ json_encode($nitriteList) }},
						fillColor: "rgba(150, 0, 150, 0.5)",
						strokeColor: "#cc00cc",
						pointColor: "#ff00ff",
					},
					{
						label: "Ammonia",
						data: {{ json_encode($ammoniaList) }},
						fillColor: "rgba(0, 50, 0, 0.5)",
						strokeColor: "#00bb00",
						pointColor: "#00ff00",
					},
				
				]
			};
			
			Chart.defaults.global.responsive = true;
			Chart.defaults.global.maintainAspectRatio = false;
			Chart.defaults.global.tooltipTitleFontSize = 12;
			Chart.defaults.global.tooltipFontSize = 10;
			
			basicTestsChart = new Chart(ctx).Line(data, { 
				bezierCurve: false,  
				scaleFontColor: "#eeeeff",
				scaleLineColor: "#ddddff",
				scaleGridLineColor: "#ccccff"
			}); 
		})();	
	</script>
@stop