<html>
<head>
<title>Aquaricle</title>
<link rel="stylesheet" type="text/css" media="all" href="/static/style.css" />
</head>
<body>

<div id="frame">

<div id="banner">
	<div id="title">
		<h1>Aquaricle</h1>
	</div>
</div>
<div id="navbar">
	<ul>
		<li>{{ link_to_route('aquariums.index', 'Aquariums') }}</li>
		<li id="logout">Logged In As {{ Auth::user()->username }} {{ link_to('/logout', 'Logout') }}</li>
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