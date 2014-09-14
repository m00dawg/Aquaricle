@extends('layout')
@section('content')

<h2>Log Details</h2>

@if (isset($status))
<h4>{{ $status }}</h4>
@endif

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

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
					<a href="/files/{{ $file->userID }}/{{ $file->fileID }}-full.{{ $file->fileType }}">
						<img src="/files/{{ $file->userID }}/{{ $file->fileID }}-thumb.{{ $file->fileType }}" />
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
		 isset($log->GH) ||
  		 isset($log->TDS) ||
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
		<br />
	@endif

	@if (count($aquariumLifeLogs) > 0)
		<table>
			<tr><th>Aquarium Life</th></tr>
			@foreach($aquariumLifeLogs as $life)
				<tr><td>
				@if ($life->nickname)
					{{ $life->nickname }}
				@else
					{{ $life->commonName }}
				@endif
				</td></tr>
			@endforeach
		</table>
		<br />
	@endif

	<h3>Modify Log Entry</h3>
@else
	<h3>Add New Log Entry</h3>
@endif

<div class="formBox">
	@if (isset($log))
		{{ Form::model($log,
			array('route' => array("aquariums.logs.update", $aquariumID, $log->aquariumLogID), 'method' => 'post', 'files' => true)) }}
	@else
		{{ Form::open(array('url' => "/aquariums/$aquariumID/logs/create", 'method' => 'post', 'files' => true)) }}
	@endif

	<table>
		<tr><th colspan="2">Water Logs</th></tr>
		<tr>
			<th>Temperature</th>
			<td>
				{{ Form::text('temperature', null, array('size' => '8')) }} &deg;
				{{ $measurementUnits['Temperature'] }}
			</td>
		</tr>
		<tr>
			<th>Ammonia</th>
			<td>{{ Form::text('ammonia', null, array('size' => '8')) }} ppm</td>
		</tr>
		<tr>
			<th>Nitrites</th>
			<td>{{ Form::text('nitrites', null, array('size' => '8')) }} ppm</td>
		</tr>
		<tr>
			<th>Nitrates</th>
			<td>{{ Form::text('nitrates', null, array('size' => '8')) }} ppm</td>
		</tr>
		<tr><th>Phosphates</th><td>{{ Form::text('phosphates', null, array('size' => '8')) }} ppm</td></tr>
		<tr><th>pH</th><td>{{ Form::text('pH', null, array('size' => '8')) }}</td></tr>
		<tr><th>KH</th><td>{{ Form::text('KH', null, array('size' => '8')) }} &deg;</td></tr>
		<tr><th>GH</th><td>{{ Form::text('GH', null, array('size' => '8')) }} &deg;</td></tr>
		<tr><th>TDS</th><td>{{ Form::text('TDS', null, array('size' => '8')) }} ppm</td></tr>
		<tr>
			<th>Water Exchanged</th>
			<td>{{ Form::text('amountExchanged', null, array('size' => '8')) }}
				{{ $measurementUnits['Volume'] }}
			</td>
		</tr>
	</table>
	<br />

	<table>
		<tr><th>Food</th></tr>
		@foreach($food as $item)
			<tr><td>
				{{ Form::checkbox('food[]',
					$item->foodID, $item->selected) }}
				{{ $item->name }}
			</td></tr>
		@endforeach
	</table>
	<br />

	<table>
		<tr><th>Aquarium Life</th></tr>
		@foreach($aquariumLife as $life)
			<tr><td>
				{{ Form::checkbox('aquariumLife[]',
					$life->aquariumLifeID, $life->selected) }}
				@if ($life->nickname)
					{{ $life->nickname }}
				@else
					{{ $life->commonName }}
				@endif
			</td></tr>
		@endforeach
	</table>
	<br />

	<table>
		<tr>
			<th>Water Additive</th>
			<td>{{ Form::select('waterAdditive', $waterAdditives) }}</td>
			<td>{{ Form::text('waterAdditiveAmount', null, array('size' => '8')) }} mL </td>
		</tr>

		<tr>
			<th>Equipment</th>
			<td>{{ Form::select('equipment', $equipment) }}</td>
			<td>{{ Form::checkbox('equipmentMaintenance') }} Maintenance</td>
		</tr>
	</table>
	<br />

	<table>
		<tr><th colspan="2">Upload File</th></tr>
		<tr>
			<th>File</th>
			<td>{{ Form::file('file') }} (Currently JPEG and PNG supported)</td>
		</tr>
		<tr>
			<th>Title</th>
			<td>{{ Form::text('fileTitle', null, array('size' => '48')) }}</td>
		</tr>
		<tr>
			<th>Caption</th>
			<td>{{ Form::textarea('fileCaption') }}</td>
		</tr>
	</table>

	<br />

    <div style="text-align: center;">
	{{ Form::label('logDate', 'Date') }}: {{ Form::text('logDate') }}

	@if(!isset($log))
		(Leave Blank for Current Time)
	@endif

	<br />
	{{ Form::label('comments', 'Comments') }}: {{ Form::textarea('comments') }}<br />

	@if (isset($log))
		<br />
		{{ Form::label('setFavorite', 'Add As a Favorite') }}: {{ Form::text('name') }}<br />
		<br />
		{{ Form::submit('Update') }}
		<input type="submit" class="delete" data-confirm="Are you sure to delete this log?" value="Delete" />

		{{--- <input type="submit" name="delete" value="Delete"> ---}}
	@else
		{{ Form::submit('Add') }}
	@endif

	{{ Form::close() }}

    </div>

</div>
<br />

<div class="helpBox">
	<strong>Wondering where the life section is? It's not implemented just yet. It will be soon!</strong>
	<p>The log page is currently a bit complicated - this will be fixed in the future. For now, be aware
		that you can add multiple additives and equipment to a log entry, but you must first create the
		base entry and then add each item one at a time (pressing the Update button each time).</p>
</div>
<br />

{{ link_to("aquariums/$aquariumID/", "Go Back") }}</td>
@stop

@section('footer')
	@parent
	<script>
		var deleteButton = document.querySelector('.delete');
		deleteButton.addEventListener('click', function(event)
		{
			event.preventDefault();
			if (confirm(this.getAttribute('data-confirm'))) {
				$("form").append('<input type="hidden" name="delete" value="true" />');
    		$("form").submit();
			}
		});

	</script>
@stop
