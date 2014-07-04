@extends('layout')
@section('content')

<h2>Feedings</h2>

<h3>Food Variety Over Last {{ $days }} Days</h3>

@if (isset($food))
<table>
	<tr><th>Food</th><th>Count</th></tr>	
	@if(count($food) > 0)
		@foreach ($food as $item)
			<tr><td>{{ $item->name }}</td><td>{{ $item->count }}</td></tr>
		@endforeach
	@else
		<tr><td colspan="2">No Feedings Found</td</tr>
	@endif
</table>
<br />

<div id="feedingsDaysForm">
{{ Form::open(array('url' => "aquariums/$aquariumID/logs/feedings", 'method' => 'get')) }}

{{ Form::model($days, 
	array('route' => array("aquariums.logs.feedings", $aquariumID), 'method' => 'get')) }}		
	Days: {{ Form::text('days', $days, array('size' => '4')) }} {{ Form::submit('Update') }}
{{ Form::close() }}	
@endif
</div>

<h3>Food Logs</h3>
@include('aquariumlogs.logsummary')

{{ $logs->links() }}

@stop