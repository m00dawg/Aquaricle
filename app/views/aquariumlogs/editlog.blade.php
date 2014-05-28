@extends('layout')
@section('content')

<h2>Log Details</h2>

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

@if (isset($log))
	<table>
		<tr><th>Date</th><td>{{ $log->logDate }}</td></tr>
		<tr><th>Summary</th><td>{{ $log->summary }}</td></tr>
		
		@if (isset($waterTestLog))
			<tr>
				<th>Temperature</th>
				<th>Ammonia</th>
				<th>Nitrites</th>
				<th>Nitrates</th>
				<th>Phosphates</th>
				<th>pH</th>
				<th>KH</th>
			</tr>	
		@endif
		
		<tr><th colspan="2">Comments</th></tr>
		<tr><td>{{ $log->comments }}</td></tr>
	</table>
	<br />
	


	<h3>Modify Log Entry</h3>
@else
	<h3>Add New Log Entry</h3>
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
			
		</table>
		
		@if (isset($log))
			{{ Form::submit('Update') }}
		@else
			{{ Form::submit('Add') }}
		@endif	
		
	<table>
		<tr><th colspan="2">Water Logs</th></tr>
		<tr><th>Temperature</th><td>{{ Form::text('temperature', null, array('size' => '8')) }}</td></tr>
		<tr><th>Ammonia</th><td>{{ Form::text('ammonia', null, array('size' => '8')) }}</td></tr>
		<tr><th>Nitrites</th><td>{{ Form::text('nitrites', null, array('size' => '8')) }}</td></tr>
		<tr><th>Nitrates</th><td>{{ Form::text('nitrates', null, array('size' => '8')) }}</td></tr>
		<tr><th>Phosphates</th><td>{{ Form::text('phosphates', null, array('size' => '8')) }}</td></tr>
		<tr><th>pH</th><td>{{ Form::text('pH', null, array('size' => '8')) }}</td></tr>
		<tr><th>KH</th><td>{{ Form::text('kH', null, array('size' => '8')) }}</td></tr>
		<tr><th>Water Exchanged</th><td>{{ Form::text('ammountExchanged', null, array('size' => '8')) }}</td></tr>
	</table>
	<br />
	<table>
		<tr>
			<th>Food</th>
			<td colspan="2">
				{{ Form::select('animal', array(
				    'Cats' => array('leopard' => 'Leopard'),
				    'Dogs' => array('spaniel' => 'Spaniel'),
				)) }}	
			</td>
		</tr>

		<tr>
			<th>Water Additive</th>
			<td>
			{{ Form::select('animal', array(
			    'Cats' => array('leopard' => 'Leopard'),
			    'Dogs' => array('spaniel' => 'Spaniel'),
			)) }}	
			</td>
			<td>{{ Form::text('ammount', null, array('size' => '8')) }} mL </td>
		</tr>
	
		<tr>
			<th>Equipment</th>
			<td colspan="2">
				{{ Form::select('animal', array(
				    'Cats' => array('leopard' => 'Leopard'),
				    'Dogs' => array('spaniel' => 'Spaniel'),
				)) }}	
			</td>
		</tr>

	</table>

	<br />
	{{ Form::submit('Add') }}

	{{ Form::close() }}


</div>

@stop