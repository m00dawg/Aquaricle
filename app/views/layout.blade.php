<html>
<head>
<title>Aquaricle</title>
<link rel="stylesheet" type="text/css" media="all" href="/static/style.css" />
</head>
<body>

@if ( Auth::user())
	<div id="login">Logged In As {{ Auth::user()->username }} 
		({{ link_to('/logout', 'Logout') }} / 
		{{ link_to_route('user.profile', 'User Perferences') }})</div>
@else
	<div id="login">{{ link_to('/login', 'Login') }}</div>
@endif


<div id="frame">

<div id="banner">
	<div id="title">
		<h1>Aquaricle</h1>
	</div>
</div>
<div id="navbar">
	<ul>
		@if ( Auth::user())
			<li>{{ link_to_route('aquariums.index', 'Aquariums') }}</li>
			@if (isset($aquariumID))
				<li>{{ link_to_route('aquariums.equipment.index', 'Equipment', array($aquariumID)) }}</li>
				<li>{{ link_to_route('aquariums.logs.favorites', 'Favorite Actions', array($aquariumID)) }}</li>
				<li>{{ link_to_route('aquariums.logs.index', 'Logs', array($aquariumID)) }}</li>
				<li>{{ link_to_route('aquariums.logs.waterlogs', 'Water Tests', array($aquariumID)) }}</li>
				<li>{{ link_to_route('aquariums.logs.feedings', 'Feedings', array($aquariumID)) }}</li>
			@endif
		@else

		@endif

	</ul>
</div>

<div id="content">
@yield('content')
</div>

<br />
<br />

</div>

<div id="footer">
Aquaricle by Tim Soderstrom
</div>

</body>
</html>
