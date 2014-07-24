@extends('layout')
@section('content')

<h2>Aquarium Life</h2>
<h3>(Still In Development)</h3>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

@if (count($formerLife) > 0)
	<h3>Current Life</h3>
@endif

<table>
	<tr><th>Nickname</th><th>Common Name</th><th>Date Added</th><th>Qty</th><th>Price Paid</th></tr>
	@if (count($currentLife) > 0)
		@foreach ($currentLife as $item)
			<tr>
				<td>
					<a class="aquariumLife" href="/aquariums/{{ $aquariumID }}/life/{{ $item->aquariumLifeID }}">
						@if ( $item->nickname)
							{{ $item->nickname }}
						@else
							Unnamed
						@endif
					</a>
				</td>
				<td>{{ $item->commonName }}</td>
				<td>{{ $item->createdAt }}</td>
				<td>{{ $item->qty }}</td>
				<td>
					@if ($item->purchasePrice)
						${{ $item->purchasePrice }}
					@endif
				</td>
			</tr>
		@endforeach
		<tr class="totals">
			<td colspan="3">Totals</td>
			<td>{{ $currentSummary->totalQty }}</td>
			<td>{{ $currentSummary->totalPrice }}</td>
		</tr>
	@else
		<tr><td colspan="3">Your Aquarium is Lifeless. How Sad.</td></tr>
	@endif
</table>
<br />

@if (count($formerLife) > 0)
	<h3>Former Life</h3>
	<table>
		<tr><th>Nickname</th><th>Common Name</th><th>Date Removed</th><th>Qty</th><th>Price Paid</th></tr>
		@foreach ($formerLife as $item)
			<tr>
				<td>
					<a class="aquariumLife" href="/aquariums/{{ $aquariumID }}/life/{{ $item->aquariumLifeID }}">
						@if ( $item->nickname)
							{{ $item->nickname }}
						@else
							Unnamed
						@endif
					</a>
				</td>
				<td>{{ $item->commonName }}</td>
				<td>{{ $item->deletedAt }}</td>
				<td>{{ $item->qty }}</td>
				<td>
					@if ($item->purchasePrice)
						${{ $item->purchasePrice }}
					@endif
				</td>
			</tr>
		@endforeach		
		<tr class="totals">
			<td colspan="3">Totals</td>
			<td>{{ $formerSummary->totalQty }}</td>
			<td>{{ $formerSummary->totalPrice }}</td>
		</tr>
	</table>
@endif
<br />

<div class="helpBox">
	<p>This is the main area that you handle stocking of your current tank. If you find a species does not
		exists that you have, you can add it by creating a Life definition over 
		{{ link_to_route('life', 'here') }}.</p>
</div>
<br />

{{ link_to_route('aquariums.life.create', 'Add Life To Aquarium', array('aquariumID' => $aquariumID)) }}

@stop
