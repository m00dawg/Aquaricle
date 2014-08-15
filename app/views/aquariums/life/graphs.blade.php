<div id="pieChart">
	<h4>Fish</h4>
	<canvas id="fishGraph"></canvas>
</div>

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
			
			var fishGraph = document.getElementById('fishGraph').getContext('2d');
			var fishData = {{ $fishGraphData }}

			fishChart = new Chart(fishGraph).Pie(fishData, { 
			}); 

		})();	
	</script>
@stop