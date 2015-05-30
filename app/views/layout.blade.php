<!DOCTYPE html>
<html>
<head>
<title>Aquaricle</title>
<link rel="stylesheet" type="text/css" media="all" href="/static/style.css" />
<script src="/js/vendor/jquery-2.1.3.min.js"></script>
</head>

<body>

<div id="topLinks">
	<a href="/news">Latest News</a> :
	<a href="https://github.com/m00dawg/Aquaricle/issues">Bugs / Features</a>
</div>

@if ( Auth::user() && !Request::is('public/*'))
	<div id="login">Logged In As {{ Auth::user()->username }}
		({{ link_to('/logout', 'Logout') }} /
		{{ link_to_route('user.profile', 'User Perferences') }})</div>
@elseif (!Request::is('public/*'))
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
		@if ( Auth::user() && !Request::is('public/*') && !isset($aquariumID))
			<li>{{ link_to_route('aquariums.index', 'Aquariums') }}</li>
			<li>{{ link_to_route('life', 'Life') }}</li>
			<li>{{ link_to_route('food', 'Food') }}</li>
		@elseif ( Auth::user() && !Request::is('public/*') && isset($aquariumID))
			<li>{{ link_to_route('aquariums.index', 'Home') }}</li>
			<li>{{ link_to_route('aquariums.show', 'Summary', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.graphs', 'Graphs', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquarium.equipment.index', 'Equipment', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.logs.favorites', 'Actions', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.logs.index', 'Logs', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.waterlogs', 'Tests', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.wateradditives', 'Additives', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.feedings', 'Feedings', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.life', 'Life', array($aquariumID)) }}</li>
			<li>{{ link_to_route('aquariums.photos', 'Photos', array($aquariumID)) }}</li>
		@elseif (Request::is('public/*') && isset($aquariumID))
			<li>{{ link_to_route('public.aquariums', 'Aquarium', array($aquariumID)) }}</li>
			<li>{{ link_to_route('public.aquariums.graphs', 'Graphs', array($aquariumID)) }}</li>
			<li>{{ link_to_route('public.aquariums.waterlogs', 'Water Tests', array($aquariumID)) }}</li>
			<li>{{ link_to_route('public.aquariums.photos', 'Photos', array($aquariumID)) }}</li>




		@else
			<li>{{ link_to('/', 'News') }}</li>
			<li>{{ link_to('https://github.com/m00dawg/Aquaricle/issues', 'Bugs/Features') }}</li>
			<li>{{ link_to('http://demo.aquaricle.com/', 'Demo') }}
			<li>{{ link_to('/login', 'Login') }}</li>
			<li>{{ link_to('/signup', 'Signup') }}</li>
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
@yield('footer')
</div>

</body>
</html>
