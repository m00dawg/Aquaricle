<table>
	<tr><th class="logDate">Date</th><th>Summary</th></tr>
	@if (count($logs) > 0)
		@foreach($logs as $log)
			<tr>
				<td>{{ link_to("aquariums/$aquariumID/logs/$log->aquariumLogID/edit", 
					$log->logDate, array('class'=>'logs')) }}</td>
				<td>
					@if ($log->summary)
						{{ $log->summary }}
					@endif
					@if ($log->comments)
						@if($log->summary)
							<br />
						@endif
						<b>Comments</b>: {{ $log->comments }}
					@endif
				</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No Logs Have Been Added Yet</td></tr>
	@endif
</table>
