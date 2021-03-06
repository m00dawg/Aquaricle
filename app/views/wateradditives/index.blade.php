@extends('layout')
@section('content')

<h2>Water Additives</h2>

<table>
	<tr><th>Additive</th><th>Last Added</th><th>Days Since</th><th>Amount (mL)</th></tr>

@if (count($waterAdditives) > 0)
	@foreach($waterAdditives as $additive)
		<tr>
			<td>
				{{ link_to_route('aquariums.wateradditives.show', 
					$additive->name, 
					array($aquariumID, $additive->waterAdditiveID),
					array('class' => 'waterAdditives')) }}
			</td>
			<td>{{ $additive->lastAdded }}</td>
			<td>{{ $additive->daysSince }}</td>
			<td>{{ $additive->amount }}</td>
		</tr>
	@endforeach
@else
	<tr><td colspan="4">No Additives</td></tr>
@endif

</table>

@stop