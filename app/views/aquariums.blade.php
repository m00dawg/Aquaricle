@extends('layout')

@section('content')

	Forms:

	{{ Form::model($aquarium, array('route' => 'aquariums', $aquarium->aquariumID)) }}
	{{ Form::close() }}

@stop