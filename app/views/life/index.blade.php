@extends('layout')
@section('content')

<h2>Life (In Active Development)</h2>

@foreach ($errors->all() as $message)
	<h4>{{ $message }}</h4>
@endforeach

<h3>Your Life</h3>
<table>
	<tr><th>Name</th><th>Scientific Name</th><th>Type</th></tr>
	@if (count($userLife) > 0)
		@foreach ($userLife as $life)
			<tr>
				<td>
					<a class="life" href="life/{{ $life->lifeID }}">
						{{ $life->commonName }}
					</a>
				</td>
				<td>{{ $life->scientificName }}</td>
				<td>{{ $life->lifeTypeName }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="3">No User Life Defined</td></tr>
	@endif
</table>
<br />
<h3>Global Life</h3>
<table>
	<tr><th>Name</th><th>Scientific Name</th><th>Type</th></tr>
	@if (count($globalLife) > 0)
		@foreach ($globalLife as $life)
			<tr>
				<td>
                    <a class="life" href="life/{{ $life->lifeID }}">
					{{ $life->commonName }}
				    </a>
                </td>
				<td>{{ $life->scientificName }}</td>
				<td>{{ $life->lifeTypeName }}</td>
			</tr>
		@endforeach
	@else
		<tr><td colspan="3">No Global Life Defined</td></tr>
	@endif
</table>
<br />

<div class="helpBox">
	<p>Here is where you define your living creatures that have in any of your aquariums. 
	<strong>Your Life</strong> are creatures you define that might not yet be part of the global database.
	Note that good life definitions can get migrated to the global database so everyone else using
	Aquaricle can benefit.</p>
</div>
<br />

{{ link_to_route('life.create', 'Add Life') }}


@stop
