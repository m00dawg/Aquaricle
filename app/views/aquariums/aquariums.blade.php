@extends('layout')
@section('content')

<h2>Your Aquariums</h2>

<div id="auth"></div>
<div id="aquariums"></div>


<script type="text/javascript">
function listAquariums(data, status, jqXHR)
{
	$.each(data, function()
	{
		$("#aquariums").append("<li><a href='/aquariums/" + this.aquariumID + "''>" + this.name + "</a></li>");
	});
}

function error_callback(jqXHR, status)
{
		alert(status);
}

jQuery.ajax({
    type: "GET",
    url: "/api/v1/aquariums",
		contentType: "application/json",
   	dataType: "json",
    success: listAquariums,
    error: error_callback
});
</script>


{{ link_to_route('aquariums.create', 'Add New Aquarium') }}


@stop
