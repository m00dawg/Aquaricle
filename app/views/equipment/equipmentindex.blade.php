@extends('layout')
@section('content')

<h1>Equipment</h1>

@if (count($activeEquipment) > 0)
<h2>Active Equipment</h2>
<table>
	<tr>
		<th>Equipment</th>
		<th>Type</th>
		<th>Installed On</th>
		<th>Maintenance Interval</th>
	</tr>


	@foreach ($activeEquipment as $equip)
		<tr>					
			<td>{{ link_to_route('aquariums.equipment.show', 
				$equip->name, 
				array($aquariumID, $equip->equipmentID),
				array('class'=>'equipment')) }}</td>
			<td>{{ $equip->typeName }}</td>	
			<td>{{ $equip->createdAt }}</td>
			<td>{{ $equip->maintInterval }}</td>
		</tr>
	@endforeach
</table>
@else
	<h4>No Active Equipment</h4>
@endif

@if (count($inactiveEquipment) > 0)
<h2>Inactive Equipment</h2>
<table>
	<tr>
		<th>Equipment</th>
		<th>Type</th>
		<th>Installed On</th>
		<th>Removed</th>
		<th>Maintenance Interval</th>
	</tr>
	@foreach ($inactiveEquipment as $equip)
		<tr>					
			<td>{{ link_to_route('aquariums.equipment.show', 
				$equip->name, 
				array($aquariumID, $equip->equipmentID),
				array('class'=>'equipment')) }}</td>
			<td>{{ $equip->typeName }}</td>	
			<td>{{ $equip->createdAt }}</td>
			<td>{{ $equip->deletedAt }}</td>
			<td>{{ $equip->maintInterval }}</td>
		</tr>
	@endforeach
</table>
@endif

<br />
	{{ link_to_route('aquariums.show', 'Go Back', array($aquariumID)) }} : 
	{{ link_to_route('aquariums.equipment.create', 'Add New', array($aquariumID)) }}
@stop