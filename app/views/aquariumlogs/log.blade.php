@extends('layout')
@section('content')

<h2>Log Details</h2>

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

@if (isset($log))
	<table>
		<tr><th>Date</th><td>{{ $log->logDate }}</td></tr>
		<tr><th>Summary</th><td>{{ $log->summary }}</td></tr>
		@if (!empty($log->comments))
			<tr><th colspan="2">Comments</th></tr>
			<tr><td colspan="2">{{ $log->comments }}</td></tr>
		@endif
	</table>

	@if (count($files) > 0)
		<br />
		<table>
			<tr><th colspan="2">Photos</th></tr>
		@foreach ($files as $file)
			<tr>
				<td class="image">
					<a href="/files/{{ $log->userID }}/{{ $file->fileID }}-full.{{ $file->fileType }}">
						<img src="/files/{{ $log->userID }}/{{ $file->fileID }}-thumb.{{ $file->fileType }}" />
					</a>
				</td>
				<td>
					Title: {{ $file->title }}<br />
					Caption: {{ $file->caption }}<br />
					Uploaded On: {{ $file->createdAt }}<br />
				</td>

		</tr>
		@endforeach
		</table>
	@endif

	@if (isset($log->temperature) ||
		 isset($log->ammonia) ||
		 isset($log->nitrites) ||
		 isset($log->nitrates) ||
		 isset($log->phosphates) ||
		 isset($log->pH) ||
		 isset($log->KH) ||
		 isset($log->amountExchanged))
		<br />
		<table>
			<tr>
				<th>Temperature</th>
				<th>Ammonia</th>
				<th>Nitrites</th>
				<th>Nitrates</th>
				<th>Phosphates</th>
				<th>pH</th>
				<th>KH</th>
				<th>GH</th>
				<th>TDS</th>
				<th>Water Exchanged</th>
			</tr>
			<tr>
				<td>{{ $log->temperature }}</td>
				<td>{{ $log->ammonia }}</td>
				<td>{{ $log->nitrites }}</td>
				<td>{{ $log->nitrates }}</td>
				<td>{{ $log->phosphates }}</td>
				<td>{{ $log->pH }}</td>
				<td>{{ $log->KH }}</td>
				<td>{{ $log->GH }}</td>
				<td>{{ $log->TDS }}</td>
				<td>{{ $log->amountExchanged }}</td>
			</tr>
		</table>
	@endif
	<br />

	@if (count($waterAdditiveLogs) > 0)
		<table>
			<tr><th>Additive</th><th>Amount</th></tr>
			@foreach($waterAdditiveLogs as $additiveLog)
				<tr>
					<td>{{ $additiveLog->name }}</td>
					<td>{{ $additiveLog->amount }}</td>
				</tr>
			@endforeach
		</table>
		<br />
	@endif

	@if (count($equipmentLogs) > 0)
		<table>
			<tr><th>Equipment</th><th>Maintenance</th></tr>
			@foreach($equipmentLogs as $equipmentItem)
				<tr>
					<td>{{ $equipmentItem->name }}</td>
					<td>{{ $equipmentItem->maintenance }}</td>
				</tr>
			@endforeach
		</table>
		<br />
	@endif

	@if (count($foodLogs) > 0)
		<table>
			<tr><th>Food</th></tr>
			@foreach($foodLogs as $foodItem)
				<tr><td>{{ $foodItem->name }}</td></tr>
			@endforeach
		</table>
	@endif
@endif
</div>
<br />

{{ link_to_route('aquariums.show', 'Go Back', array($aquariumID)) }} :
{{ link_to("aquariums/$aquariumID/logs/$log->aquariumLogID/edit",
	'Edit Log') }}


@stop
