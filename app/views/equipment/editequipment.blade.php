@extends('layout')
@section('content')

@if (isset($equipment))
	<h1>{{ $equipment->name }}</h1>
	{{ Form::model($equipment, 
		array('route' => array('aquariums.equipment.update', 
			$aquariumID, $equipment->equipmentID), 'method' => 'put')) }}
@else
	<h1>Add New Equipment</h1>
	{{ Form::open(array('url' => "aquariums/$aquariumID/equipment")) }}
@endif

<div class="formBox">

<table>
	<tr>
		<th>Name</th>
		<td>{{ Form::text('name', null, array('size' => '32')) }}</td>
	</tr>
	<tr>
		<th>Installed On</th>
		<td>{{ Form::text('installedOn', null, array('size' => '32')) }}</td>
	</tr>
	@if (isset($equipment))	
		<tr>
			<th>Date Removed</th>
			<td>{{ Form::text('removedOn', null, array('size' => '32')) }}</td>
		</tr>
	@endif
	<tr>
		<th>Maintenance Interval</th>
		<td>{{ Form::text('maintInterval', null, array('size' => '32')) }} Days</td>
	</tr>
	<tr>
		<th>Comments</th>
		<td>{{ Form::textarea('comments') }}</td>
	</tr>
</table>

<br />

@if (isset($equipment))
	{{ Form::submit('Update') }}
	<input type="submit" name="delete" value="Delete">
	
@else
	{{ Form::submit('Add') }}
@endif

{{ Form::close() }}

</div>

{{ link_to_route('aquariums.equipment.show', 'Go Back', array($aquariumID)) }}

@stop