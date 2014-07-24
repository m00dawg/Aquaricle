@extends('layout')
@section('content')

<h1>
@if ( $life->nickname )
	{{ $life->nickname }}
@else
	Unnamed Creatures
@endif

({{ link_to_route('aquariums.life.edit', 'Edit', 
	array('aquariumID' => $aquariumID, 'aquriumLifeID' => $life->aquariumLifeID)
)}})
</h1>

<table>
	<tr><th>Common Name</th><td>{{ $life->life->commonName }}</td></tr>
	<tr><th>Qty</th><td>{{ $life->qty }}</td></tr>

	@if ($life->purchasePrice)
		<tr><th>Purchase Price</th><td>${{ $life->purchasePrice }}</td></tr>
	@endif
	
	@if ($life->purchasedAt)
		<tr><th>Purchased At</th><td>{{ $life->purchasedAt }}</td></tr>
	@endif
	
	<tr><th>Date Added</th><td>{{ $life->createdAt }}</td></tr>

	@if ($life->deletedAt)
		<tr><th>Date Removed</th><td>{{ $life->deletedAt }}</td></tr>
	@endif

	@if ($life->comments)
		<tr><th colspan="2">Comments</th></tr>
		<tr><td colspan="2">{{ $life->comments }}</td></tr>
	@endif
</table>
<br />

{{ link_to_route('aquariums.life', 'Go Back', array('aquariumID' => $aquariumID )) }}

@stop
