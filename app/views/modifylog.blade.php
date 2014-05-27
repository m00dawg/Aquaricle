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
		
		@if (isset($log))
			{{ Form::submit('Update') }}
		@else
			{{ Form::submit('Add') }}
		@endif	
	{{ Form::close() }}
</div>

@stop