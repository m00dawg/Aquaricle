@extends('layout')
@section('content')

<h1>{{ $equipment->name }}</h1>

<table>
	<tr>
		<th>Name</th>
		<td>
			@if ($equipment->url)
				<a class="equipment" href="{{ $equipment->url }}">{{ $equipment->name }}</a>
			@else
				{{ $equipment->name }}
			@endif
		</td>
	</tr>
	<tr><th>Type</th><td>{{ $equipment->typeName }}</td></tr>

	@if ($equipment->purchasePrice)
		<tr><th>Purchase Price</th><td>${{ $equipment->purchasePrice }}</td></tr>
	@endif
	
	<tr>
		<th>Maintenance Interval</th>
		<td>
			@if ($equipment->maintInterval)
				{{ $equipment->maintInterval }}
			@else
				None
			@endif
		</td>
	</tr>

	<tr><th>Installed On</th><td>{{ $equipment->createdAt }}</td></tr>

	@if ($equipment->deletedAt)
		<tr><th>Date Removed</th><td>{{ $equipment->deletedAt }}</td></tr>
	@endif


	@if ($equipment->comments)
		<tr><th colspan="2">Comments</th></tr>
		<tr><td colspan="2">{{ $equipment->comments }}</td></tr>
	@endif

</table>

@if (count($logs) > 0)
	<h2>Equipment Logs</h2>
	<table>
		<tr>
			<th>Date</th>
			<th>Maintenance</th>
			<th>Summary</th>
		</tr>
	@foreach ($logs as $log)
		<tr>
			<td>{{ link_to("aquariums/$aquariumID/logs/$log->aquariumLogID/edit", 
				$log->logDate, array('class'=>'logs')) }}</td>
			<td>{{ $log->maintenance }}</td>
			<td>{{ $log->summary }}
				@if($log->comments)
					@if($log->summary)
						<br />
					@endif
					<strong>Comments:</strong> {{ $log->comments }}
				@endif
			</td>
		</tr>
	@endforeach
	</table>
	
	{{ $logs->links() }}
@endif



<br />
{{ link_to_route('aquariums.equipment.show', 'Go Back', array($aquariumID)) }} :
{{ link_to_route('aquariums.equipment.edit', 'Edit', array($aquariumID, $equipment->equipmentID)) }}

@stop