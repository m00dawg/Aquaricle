@if ($foodGraphData)
	<div id="pieChart">
		<canvas id="foodGraph"></canvas>
	</div>
	<br />
	<br />
@endif

@section('footer')
	@parent

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