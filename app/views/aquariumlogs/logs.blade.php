@extends('layout')
@section('content')

<h2>Aquarium Logs</h2>

@if (isset($logs))

@include('aquariumlogs.logsummary')

@endif

{{ $logs->links() }}

{{ link_to_route('aquariums.show', 'Go Back', array($aquariumID)) }}


@stop