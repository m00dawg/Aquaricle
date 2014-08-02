@extends('layout')
@section('content')

<h2>Water Logs</h2>

<div id="graphs">
	{{-- Set to > 1 since we need at least 2 data points to make a useful graph --}}
	@if (count($cycleLogDateList) > 1)
		<div class="graph">
			<h4>Tank Cycling</h4>
			<canvas id="waterCycleGraph"></canvas>
		</div>
	@endif
	
	@if (count($phoshateDataList) > 1)
		<div class="graph">
			<h4>Phosphates</h4>
			<canvas id="waterPhosphatesGraph"></canvas>
		</div>
	@endif

	@if (count($waterChangeDataList) > 1)
		<div class="graph">
			<h4>Water Changes</h4>
			<canvas id="waterChangeGraph"></canvas>
		</div>
	@endif
</div>

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

@section('footer')
	<script src="/js/vendor/chart.js"></script>
	
	<script>
		(function()
		{
			Chart.defaults.global.responsive = true;
			Chart.defaults.global.maintainAspectRatio = false;
			Chart.defaults.global.tooltipTitleFontSize = 12;
			Chart.defaults.global.tooltipFontSize = 10;
			Chart.defaults.global.scaleFontColor = "#eeeeff";
			Chart.defaults.global.scaleLineColor = "#ddddff";
			Chart.defaults.global.scaleGridLineColor = "#ccccff";
			
			@if (count($cycleLogDateList) > 1)
				var waterCycleGraph = document.getElementById('waterCycleGraph').getContext('2d');
				var waterCycleData = 
				{
					labels: {{ json_encode($cycleLogDateList) }},
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
				
				basicTestsChart = new Chart(waterCycleGraph).Line(waterCycleData, { 
					bezierCurve: false
				}); 

			@endif

			@if (count($phoshateDataList) > 1)
				var phosphatesGraph =
					 document.getElementById('waterPhosphatesGraph').getContext('2d');
				var phosphatesData = 
				{
					labels: {{ json_encode($phoshateLogDateList) }},
					datasets: [
						{
							label: "Phosphates",
							data: {{ json_encode($phoshateDataList) }},
							fillColor: "rgba(250, 250, 00, 0.75)",
							strokeColor: "#ffcc00",
							pointColor: "#ffff00",
						},
					]
				};
				phosphatesChart = new Chart(phosphatesGraph).Line(phosphatesData, { 
					bezierCurve: false
				}); 
			@endif
	
			@if (count($waterChangeDataList) > 1)
				var waterChangeGraph = document.getElementById('waterChangeGraph').getContext('2d');
				var waterChangeData = 
				{
					labels: {{ json_encode($waterChangeDateList) }},
					datasets: [
						{
							label: "Water Changes",
							data: {{ json_encode($waterChangeDataList) }},
							fillColor: "rgba(0, 0, 250, 0.25)",
							strokeColor: "#0000bb",
							pointColor: "#4444ff",
						},
					]
				};
			
				waterChangeChart = new Chart(waterChangeGraph).Line(waterChangeData, { 
					bezierCurve: false
				}); 
			@endif
		})();	
	</script>
@stop