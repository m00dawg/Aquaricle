@extends('layout')
@section('content')

<h1>Equipment</h1>

@if (count($activeEquipment) > 0)
<h2>Active Equipment</h2>
<table>
	<tr>
		<th class="equipmentName">Equipment</th>
		<th class="equipmentType">Type</th>
		<th class="equipmentPrice">Price</th>
		<th class="equipmentMaintInterval">Maintenance Interval</th>
		<th class="equipmentActiveInstalledOn">Installed On</th>
	</tr>


	@foreach ($activeEquipment as $equip)
		<tr>					
			<td class="equipmentName">{{ link_to_route('aquariums.equipment.show', 
				$equip->name, 
				array($aquariumID, $equip->equipmentID),
				array('class'=>'equipment')) }}</td>
			<td class="equipmentType">{{ $equip->typeName }}</td>
			@if ($equip->purchasePrice)
				<td class="equipmentPrice">${{ money_format('%i', $equip->purchasePrice) }}</td>
			@else
				<td class="blank"></td>
			@endif
			@if ($equip->maintInterval)
				<td class="equipmentMaintInterval">{{ $equip->maintInterval }}</td>
			@else
				<td class="blank"></td>
			@endif
			<td>{{ $equip->createdAt }}</td>
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
		
		<th class="equipmentName">Equipment</th>
		<th class="equipmentType">Type</th>
		<th class="equipmentPrice">Price</th>
		<th class="equipmentMaintInterval">Maintenance Interval</th>
		<th class="equipmentInactiveInstalledOn">Installed On</th>
		<th class="equipmentInactiveRemovedOn">Removed On</th>
	</tr>
	@foreach ($inactiveEquipment as $equip)
		<tr>					
			<td>{{ link_to_route('aquariums.equipment.show', 
				$equip->name, 
				array($aquariumID, $equip->equipmentID),
				array('class'=>'equipment')) }}</td>
			<td class="equipmentType">{{ $equip->typeName }}</td>
			@if ($equip->purchasePrice)
				<td class="equipmentPrice">${{ money_format('%i', $equip->purchasePrice) }}</td>
			@else
				<td class="blank"></td>
			@endif
			@if ($equip->maintInterval)
				<td class="equipmentMaintInterval">{{ $equip->maintInterval }}</td>
			@else
				<td class="blank"></td>
			@endif
			<td>{{ $equip->createdAt }}</td>
			<td>{{ $equip->deletedAt }}</td>
		</tr>
	@endforeach
</table>
@endif

@if ($activeCost || $inactiveCost)
	<br /><br />
	<table class="equipmentCosts">
		<tr class="equipmentCosts">
			<th class="equipmentCosts">Active Equipment:</th>
			<td class="equipmentCosts">${{ money_format('%i', $activeCost) }}</td>
		</tr>
		<tr class="equipmentCosts">
			<th class="equipmentCosts">Inactive Equipment:</th>
			<td class="equipmentCosts">${{ money_format('%i', $inactiveCost) }}</td>
		</tr>
		<tr class="equipmentCosts">
			<th class="equipmentCosts">Total:</th>
			<td class="equipmentCostsTotal">${{ money_format('%i', $totalCost) }}</td>
		</tr>
	</table>
@endif

<br />
	{{ link_to_route('aquariums.show', 'Go Back', array($aquariumID)) }} : 
	{{ link_to_route('aquariums.equipment.create', 'Add New', array($aquariumID)) }}
@stop