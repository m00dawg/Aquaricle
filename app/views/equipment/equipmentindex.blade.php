@extends('layout')
@section('content')

<h1>Equipment</h1>

<table>
	<tr>
		<th>Equipment</th>
		<th>Installed On</th>
		<th>Date Removed</th>
		<th>Maintenance Interval</th>
		<th>Comments</th>
	</tr>

	@if (count($equipment) > 0)
		@foreach ($equipment as $equip)
			<tr>
				<td>{{ link_to_route('aquariums.equipment.show', $equip->name, array($aquariumID,$equip->equipmentID)) }}</td>
				<td>{{ $equip->installedOn }}</td>
				<td>{{ $equip->removedOn }}</td>
				<td>{{ $equip->maintInterval }}</td>
				<td>{{ $equip->comments }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="4">No Equipment</td></tr>
	@endif
	
</table>
<br />
	{{ link_to_route('aquariums.show', 'Go Back', array($aquariumID)) }} : 
	{{ link_to_route('aquariums.equipment.create', 'Add New', array($aquariumID)) }}
@stop