@extends('layout')
@section('content')

<h2>Feedings</h2>

<h3>Food Variety Over Last {{ $days }} Days</h3>

@if (isset($food))
<table>
	<tr><th>Food</th><th>Count</th></tr>	
	@if(count($food) > 0)
		@foreach ($food as $item)
			<tr><td>{{ $item->name }}</td><td>{{ $item->count }}</td></tr>
		@endforeach
	@else
		<tr><td colspan="2">No Feedings Found</td</tr>
	@endif
</table>
@endif

<h3>Food Logs</h3>
@include('aquariumlogs.logsummary')

{{ $logs->links() }}

@stop