@extends('layout')
@section('content')

<h2>Life</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<div class="formBox">
	@if (isset($life))
		{{ Form::model($life,
			array('route' => array("life.edit", $life->lifeID), 'method' => 'post')) }}		
	@else
		{{ Form::open(array('url' => "life/create", 'method' => 'post')) }}
	@endif

	<table>
		<tr>
			<th>Common Name</th>
			<td>{{ Form::text('commonName', null, array('size' => 32)) }}</td>
		</tr>
		<tr>	
			<th>Scientific Name</th>
			<td>{{ Form::text('scientificName', null, array('size' => 48)) }}</td>
		</tr>
		<tr>
			<th>Type</th>
			<td>
				@if (isset($life))
					{{ Form::select('lifeType', $lifeTypes, $life->lifeTypeID) }}
				@else
					{{ Form::select('lifeType', $lifeTypes, 1) }}
				@endif
			</td>
		</tr>
		<tr>
			<th>Description</th>
			<td>{{ Form::textarea('description', null) }}</td>
		</tr>
	</table>
	<br />
	
	@if (isset($life))
		{{ Form::submit('Update') }}
		<input type="submit" name="delete" value="Delete">
	@else
		{{ Form::submit('Add') }}
	@endif	

	{{ Form::close() }}
</div>

<a href="/life">Go Back</a>

@stop