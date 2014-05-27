@extends('layout')
@section('content')

@if (isset($log))
	<h2>Modify Log Entry</h2>
@else
	<h2>Add New Log Entry</h2>
@endif

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

<div class="formBox">
	Aquarium: {{ $aquariumID }}
	
	@if (isset($log))
		{{ Form::model($log, array('route' => array("aquariums.logs.update", $aquariumID, $log->aquariumLogID), 'method' => 'PUT')) }}		
		
	@else
		{{ Form::open(array('url' => "aquariums/$aquariumID/logs")) }}
	@endif
	
		{{ Form::label('logDate', 'Date') }}: {{ Form::text('logDate') }}<br />
		{{ Form::label('comments', 'Comments') }}: {{ Form::textarea('comments') }}<br />
		
		<h4>Water Logs</h4>
		
		<table>
			<tr>
				<th>Temperature</th>
				<th>Ammonia</th>
				<th>Nitrites</th>
				<th>Nitrates</th>
				<th>Phosphates</th>
				<th>pH</th>
				<th>KH</th>
			</tr>
			<tr>
				<td>{{ Form::text('temperature', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('ammonia', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('nitrites', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('nitrates', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('phosphates', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('pH', null, array('size' => '8')) }}</td>
				<td>{{ Form::text('kH', null, array('size' => '8')) }}</td>
			</tr>
			
		</table>
		
		@if (isset($log))
			{{ Form::submit('Update') }}
		@else
			{{ Form::submit('Add') }}
		@endif	
	{{ Form::close() }}
</div>

@stop