@extends('layout')
@section('content')

<h2>Water Additive: {{ $waterAdditive->name }}</h2>

<h3>Latest Additions</h3>

<table>
	<tr><th class="logDate">Date</th><th>Amount (mL)</th><th>Summary</th></tr>
	@if(count($logs) > 0)
		@foreach($logs as $log)
			<tr>
				<td>{{ link_to("aquariums/$aquariumID/logs/$log->aquariumLogID/edit", 
					$log->logDate, array('class'=>'logs')) }}</td>
				<td>{{ $log->amount }}</td>
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
	@else
		<tr><td colspan="2">No Additives Added To Tank</td></tr>
	@endif
</table>

{{ $logs->links() }}


<br />
{{ link_to_route('aquariums.wateradditives', 'Go Back', array($aquariumID)) }}


@stop