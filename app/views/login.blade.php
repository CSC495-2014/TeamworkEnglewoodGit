@extends('layout')

@section('content')
	{{ HTML::style('css/login.css') }}
	<body background="{{ URL::asset('css/images/background.png') }}">
		
        <div class="container">
		<BR/><BR/><BR/><BR/><BR/><BR/><BR/>
		<div class="panel panel-success">
			<!-- <div class="panel-heading">Teamwork Englewood</div> -->
			<div class="panel-body">
				<form class="form-signin" role="form">
					<h3 class="form-signin-heading">Englewood Codes</h3>
						<button id = "submit" class="btn btn-lg btn-signIn btn-block" onClick="login">Login with GitHub</button>
					<hr>
					<a href ="https://github.com/" class="btn btn-med btn-GitHub btn-block" type="button">Create GitHub Account</a>
				</form>
			</div>
		</div> <!-- /container -->
	</body>
@endsection
