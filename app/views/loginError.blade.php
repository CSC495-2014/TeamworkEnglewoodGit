@extends('layout')

@section('content')
    {{ HTML::style('css/login.css') }}
	<body>
    There was a problem logging you in! You need a valid email associated with your Github account! Please refer to the documentation for instructions.
		<a href ="{{ URL::to("/") }}" type="button">Return to Login Page</a>
	</body>
@endsection