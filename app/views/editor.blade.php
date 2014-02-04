@extends('layout')

@section('content')
		{{ HTML::style('css/editor.css') }}
		<body>
			<div id="topLeft">
				<img src="css/images/logo.png">
			</div>
			<div id="header">
				<h1 style="color:#FFFFFF; text-align: center; padding-top:20px;">Project Name</h1>
				<h4 style="color:#FFFFFF; text-align: center; padding-top:10px;">Username</h4>
			</div>
			<div id="topRight">
					<ul class="nav nav-pills-square nav-stacked">
						<li><a href="#">My Projects</a></li>
						<li><a href="https://github.com/" target="blank">GitHub</a></li>
						<li><a href="#">Logout</a></li>
					</ul>
			</div>
			<div id="fileSystem">
				<!-- Div Section for Mike's Code -->
			</div>
			<div id="editor">
				<!-- Div Section for Xiao's Code -->
			</div>
			<div id="optionSideBar">
				<div class="panel panel-default">
				  <div class="panel-body">
					<h4>File Options</h4>
					<button class="btn btn-lg btn-file btn-block" type="button">Save</button>
					<hr/>
					<h4>Project Options</h4>
					<button class="btn btn-lg btn-project btn-block" type="button">Commit</button>
					<button class="btn btn-lg btn-project btn-block" type="button">Push</button>
				  </div>
				</div>
			</div>
		</body>
@endsection
