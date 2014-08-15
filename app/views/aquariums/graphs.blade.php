@extends('layout')

@section('content')

<h2>Graphs</h2>

@if ($aquarium->sparkID && $aquarium->sparkToken)
	<div id="graph">

	<h3>Aquarispark</h3>
	<h4>Daily</h4>

	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-daily-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-daily-small.png">
	</a>
	
	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-daily-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-daily-small.png">
	</a>

	<h4>Weekly</h4>

	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-weekly-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/temp-weekly-small.png">
	</a>
	
	<a href="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-weekly-large.png">
		<img src="{{ Config::get('spark.uriGraphPath') }}{{ $aquarium->aquariumID }}/relays-weekly-small.png">
	</a>
	</div>
@endif

<div id="columnWrap">
	<div id="leftColumn">
		<h3>Water Logs</h3>
		@include('waterlogs.graphs')
	</div>
	<div id="rightColumn">
		@if ($foodCount > 1)
			<h3>Recent Feedings</h3>
			@include('food.graphs')
		@endif
		@if ($fishCount > 1)
			<h3>Life</h3>
			@include('aquariums.life.graphs')
		@endif
	</div>
<div id="clear"></div>

@stop

@section('footer')
	@parent
	<script src="/js/vendor/chart.js"></script>
@stop