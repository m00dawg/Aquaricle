@extends('layout')
@section('content')

@if (isset($aquarium))
	<h2>Modify Aquarium</h2>
@else
	<h2>Add New Aquarium</h2>
@endif

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

<div class="formBox">
	@if (isset($aquarium))
		{{ Form::model($aquarium, array('route' => array('aquariums.update', $aquarium->aquariumID), 'method' => 'PUT')) }}
	@else
		{{ Form::open(array('url' => 'aquariums')) }}
	@endif
	
		{{ Form::label('name', 'Name') }}: {{ Form::text('name') }}<br />
		{{ Form::label('location', 'Location') }}: {{ Form::text('location') }}<br />

		{{ Form::label('measurementUnits', 'Measurement Units') }}:
		{{ Form::select('measurementUnits', array('Metric' => 'Metric', 'Imperial' => 'Imperial'), 'Metric') }}<br />

		{{ Form::label('capacity', 'Capacity') }}: {{ Form::text('capacity') }}<br />
		{{ Form::label('length', 'Length') }}: {{ Form::text('length') }}<br />
		{{ Form::label('width', 'Width') }}: {{ Form::text('width') }}<br />
		{{ Form::label('height', 'Height') }}: {{ Form::text('height') }}<br />

		@if (isset($aquarium))
			{{ Form::submit('Update') }}
		@else
			{{ Form::submit('Add') }}
		@endif	
	{{ Form::close() }}
</div>

@stop