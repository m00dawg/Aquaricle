@extends('layout')
@section('content')

<h2>Feedings</h2>

<h3>Food Variety Over Last {{ $days }} Days</h3>

@if (isset($food))
<table>
	
	@if (count($food) > 1)
		<div id="pieChart">
			<canvas id="foodGraph"></canvas>
		</div>
		<br />
		<br />
	@endif

	
	
	<tr><th>Food</th><th>Count</th></tr>	
	@if(count($food) > 0)
		@foreach ($food as $item)
			<tr><td>{{ $item->label }}</td><td>{{ $item->value }}</td></tr>
		@endforeach
	@else
		<tr><td colspan="2">No Feedings Found</td</tr>
	@endif
</table>
<br />

<div id="feedingsDaysForm">
{{ Form::open(array('url' => "aquariums/$aquariumID/feedings", 'method' => 'get')) }}

{{ Form::model($days, 
	array('route' => array("aquariums.feedings", $aquariumID), 'method' => 'get')) }}		
	Days: {{ Form::text('days', $days, array('size' => '4')) }} {{ Form::submit('Update') }}
{{ Form::close() }}	
@endif
</div>

<h3>Food Logs</h3>
@include('aquariumlogs.logsummary')

{{ $logs->links() }}

@stop

@section('footer')
	<script src="/js/vendor/chart.js"></script>
	
	<script>
		(function()
		{
			Chart.defaults.global.responsive = true;
			Chart.defaults.global.maintainAspectRatio = true;
			Chart.defaults.global.tooltipTitleFontSize = 14;
			Chart.defaults.global.tooltipFontSize = 12;
			Chart.defaults.global.scaleFontColor = "#eeeeff";
			Chart.defaults.global.scaleLineColor = "#ddddff";
			Chart.defaults.global.scaleGridLineColor = "#ccccff";
			
			var foodGraph = document.getElementById('foodGraph').getContext('2d');
			var foodData = {{ $foodGraphData }}

			foodChart = new Chart(foodGraph).Pie(foodData, { 
			}); 

		})();	
	</script>
@stop