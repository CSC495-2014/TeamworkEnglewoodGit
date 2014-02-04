<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8 without BOM">
    <title>Teamwork Englewood</title>
      {{ HTML::style('css/bootstrap.min.css') }}
	  {{ HTML::style('/css/bootstrap.css') }}
	  {{ HTML::script('/js/jquery-1.10.2.js') }}
	  {{ HTML::script('/js/bootstrap.js') }}
</head>
<body>
        @yield('content')
</body>
</html>

