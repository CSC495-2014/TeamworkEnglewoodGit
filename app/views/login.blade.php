@extends('layout')

@section('content')
	{{ HTML::style('css/login.css') }}
	<body background="css/images/background.png"> 
        <div class="container">
		<BR/><BR/><BR/><BR/>
		<div class="panel panel-success">
			<!-- <div class="panel-heading">Teamwork Englewood</div> -->
			<div class="panel-body">
				<form class="form-signin" role="form">
					<h3 class="form-signin-heading">Sign In</h3>
					<input type="text" class="form-control" placeholder="GitHub Username" required>
					<input type="password" class="form-control" placeholder="GitHub Password" required>
					<button class="btn btn-lg btn-signIn btn-block" type="submit">Sign in</button>
					<hr>
					<h4 class="form-signin-heading">Create GitHub Account</h4>
					<a href ="https://github.com/" class="btn btn-med btn-GitHub btn-block" type="button">GitHub</a>
				</form>
			</div>
		</div>
	
    </div> <!-- /container -->
@endsection
