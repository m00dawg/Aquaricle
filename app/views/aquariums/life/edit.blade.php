@extends('layout')
@section('content')

@if (isset($life))
	<h1>Edit
		@if ($life->nickname)
			{{ $life->nickname }}
		@else
			Unnamed Creatures
		@endif
	</h1>
	{{ Form::model($life, 
		array('route' => array('aquariums.life.update', 
			$aquariumID, $life->aquariumLifeID), 'method' => 'post')) }}
@else
	<h2>Add Life to Aquarium</h2>
	{{ Form::open(array('route' => array('aquariums.life.create',
			$aquariumID), 'method' => 'post')) }}
@endif

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<div class="formBox">
	<table>
		<tr>
			<th>Nickname</th>
			<td>{{ Form::text('nickname', null, array('size' => 32)) }}</td>
		</tr>
		<tr>
			<th>Life</th>
			<td>
				@if (isset($life))
					{{ Form::select('lifeID', $lifeList, $life->lifeID) }}
				@else
					{{ Form::select('lifeID', $lifeList) }}
				@endif
			</td>
		</tr>
		<tr>
			<th>Date Added</th>
			<td>
				{{ Form::text('createdAt', null, array('size' => 16)) }}
				@if (!isset($life))
					 (Leave Blank For Current Time)
				@endif
			 </td>
		</tr>
		@if (isset($life))		
			<tr>
				<th>Date Removed</th>
				<td>{{ Form::text('deletedAt', null, array('size' => 16)) }}</td>
			</tr>
		@endif
		<tr>
			<th>Qty</th>
			<td>{{ Form::text('qty', null, array('size' => 8)) }}</td>
		</tr>
		<tr>
			<th>Purchase Price</th>
			<td>$ {{ Form::text('purchasePrice', null, array('size' => 8)) }}</td>
		</tr>
		<tr>
			<th>Purchase Location</th>
			<td>{{ Form::text('purchasedAt', null, array('size' => 32)) }}</td>
		</tr>
		<tr>
			<th>Comments</th>
			<td>{{ Form::textarea('comments', null) }}</td>
		</tr>
	</table>
	<br />
	@if (isset($life))
		{{ Form::submit('Update') }}
		<input type="submit" name="delete" value="Permanently Delete">
	@else
		{{ Form::submit('Add') }}
	@endif	

	{{ Form::close() }}
</div>

<br />

{{ link_to_route('aquariums.life', 'Go Back', array('aquariumID' => $aquariumID )) }}

@stop
