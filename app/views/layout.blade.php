<html>
<head>
<title>Aquaricle</title>
<link rel="stylesheet" type="text/css" media="all" href="/static/style.css" />
</head>
<body>

@if ( Auth::user())
	<div id="login">Logged In As {{ Auth::user()->username }} ({{ link_to('/logout', 'Logout') }} / User Perferences)</div>
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
				<li>Water Logs</li>
				<li>Feedings</li>
				<li>Life</li>
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
