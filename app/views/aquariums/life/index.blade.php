@extends('layout')
@section('content')

<h2>Aquarium Life</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<table>
	<tr><th>Nickname</th><th>Common Name</th><th>Date Added</th><th>Qty</th><th>Price Paid</th></tr>
	@if (count($life) > 0)
		@foreach ($life as $item)
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
	@else
		<tr><td colspan="3">Your Aquarium is Lifeless. How Sad.</td></tr>
	@endif
</table>
<br />

{{ link_to_route('aquariums.life.create', 'Add Life To Aquarium', array('aquariumID' => $aquariumID)) }}

@stop
