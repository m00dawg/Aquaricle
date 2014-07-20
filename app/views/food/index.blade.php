@extends('layout')
@section('content')

<h2>Food</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<h3>Your Food</h3>
<table>
	<tr><th>Food</th><th>Description</th></tr>
	@if (count($userFood) > 0)
		@foreach ($userFood as $item)
			<tr>
				<td><a class="food" href="food/{{ $item->foodID }}/edit">{{ $item->name }}</a></td>
				<td>{{ $item->description }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="2">No User Food Defined</td></tr>
	@endif
</table>
<br />
{{ link_to_route('food.create', 'Add Food') }}



@if (count($globalFood) > 0)
<h3>System Food</h3>
<table>
	<tr><th>Food</th><th>Description</th></tr>
	@if(count($globalFood) > 0)
		@foreach ($globalFood as $item)
			<tr>
				<td>{{ $item->name }}</td>
				<td>{{ $item->description }}</td>
			</tr>
		@endforeach
	@endif
</table>
<br />
@endif

@stop