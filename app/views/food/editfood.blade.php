@extends('layout')
@section('content')

<h2>Food</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<div class="formBox">
	@if (isset($food))
		{{ Form::model($food, 
			array('route' => array("food.edit", $food->foodID), 'method' => 'post')) }}		
	@else
		{{ Form::open(array('url' => "food/create", 'method' => 'post')) }}
	@endif

	<table>
		<tr>
			<th>Name</th>
			<td>{{ Form::text('name', null) }}</td>
		</tr>
		<tr>	
			<th>Description</th>
			<td>{{ Form::textarea('description', null) }}</td>
		</tr>
	</table>
	<br />
	@if (isset($food))
		{{ Form::submit('Update') }}
		<input type="submit" name="delete" value="Delete">
	@else
		{{ Form::submit('Add') }}
	@endif	

	{{ Form::close() }}
</div>


@stop