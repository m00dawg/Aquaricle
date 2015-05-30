@extends('layout')
@section('content')

@if (isset($aquarium))
	<h2>Modify Aquarium</h2>
@else
	<h2>Add New Aquarium</h2>
@endif

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

<div class="formBox">
	@if (isset($aquarium))
		{{ Form::model($aquarium, array('route' => array('aquariums.update', $aquarium->aquariumID), 'method' => 'POST')) }}
	@else
		{{ Form::open(array('url' => 'aquarium/create', 'method' => 'POST')) }}
	@endif
	<table>
		<tr><th>{{ Form::label('name', 'Name') }}</th><td>{{ Form::text('name') }}</td></tr>
		<tr><th>{{ Form::label('location', 'Location') }}</th><td>{{ Form::text('location') }}</td></tr>
		<tr>
			<th>{{ Form::label('visibility', 'Visibility') }}</th>
			<td>		
			@if (isset($aquarium))
				{{ Form::select('visibility', array('Public' => 'Public', 'Private' => 'Private'), 					$aquarium->visibility) }}
			@else
				{{ Form::select('visibility', array('Public' => 'Public', 'Private' => 'Private'), 'Public') }}
			@endif
			</td>
		</tr>	
	
		<tr>
			<th>{{ Form::label('measurementUnits', 'Measurement Units') }}</th>
			<td>		
			@if (isset($aquarium))
				{{ Form::select('measurementUnits', array('Metric' => 'Metric', 'Imperial' => 'Imperial'), $aquarium->measurementUnits) }}
			@else
				{{ Form::select('measurementUnits', array('Metric' => 'Metric', 'Imperial' => 'Imperial'), 'Metric') }}
			@endif
			</td>
		</tr>

		<tr><th>{{ Form::label('capacity', 'Capacity') }}</th><td>{{ Form::text('capacity') }}</td></tr>
		<tr><th>{{ Form::label('length', 'Length') }}</th><td>{{ Form::text('length') }}</td></tr>
		<tr><th>{{ Form::label('width', 'Width') }}</th><td>{{ Form::text('width') }}</td></tr>
		<tr><th>{{ Form::label('height', 'Height') }}</th><td>{{ Form::text('height') }}</td></tr>
		<tr>
			<th>{{ Form::label('waterchange', 'Water Change Interval (Days)') }}</th>
			<td>{{ Form::text('waterChangeInterval') }}</td>
		</tr>
		<tr>
			<th>{{ Form::label('temperature', 'Target Temperature') }}</th>
			<td>{{ Form::text('targetTemperature') }}</td>
		</tr>
		<tr>
			<th>{{ Form::label('pH', 'Target pH') }}</th>
			<td>{{ Form::text('targetPH') }}</td>
		</tr>
		<tr>
			<th>{{ Form::label('KH', 'Target KH') }}</th>
			<td>{{ Form::text('targetKH') }}</td>
		</tr>
		<tr>	
			<th>{{ Form::label('Spark Device ID') }}</th>
			<td>{{ Form::text('sparkID', null, array('size' => '24')) }}
				Leave blank if not using the hardware device
			</td>
		</tr>
		<tr>	
			<th>{{ Form::label('Spark Token') }}</th>
			<td>{{ Form::text('sparkToken', null, array('size' => '40')) }}
				Leave blank if not using the hardware device
			</td>
		</tr>
</table>
<br />
	@if (isset($aquarium))
		{{ Form::submit('Update') }}
	@else
		{{ Form::submit('Add') }}
	@endif	
{{ Form::close() }}
</div>
<br />
@if (isset($aquarium))
	{{ link_to_route('aquariums.show', 'Go Back', array($aquarium->aquariumID)) }}
@else
	{{ link_to_route('aquariums.index', 'Go Back') }}
@endif


@stop