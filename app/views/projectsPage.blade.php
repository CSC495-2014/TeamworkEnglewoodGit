@extends('layout')

@section('content')
        {{ HTML::style('/css/bootstrap.css') }}
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
        {{ HTML::style('css/editor.css') }}
        {{ HTML::script('/js/jquery-1.10.2.js') }}
        {{ HTML::script('/js/jqueryFileTree.js') }}
        {{ HTML::script('/js/jquery.ui.position.js') }}
        {{ HTML::script('/js/jquery.contextMenu.js') }}
        {{ HTML::script('/js/bootstrap.js') }}
		{{ HTML::script('/js/ui/jquery.ui.position.js') }}
		{{ HTML::script('/js/ui/jquery.ui.core.js') }}
		{{ HTML::script('/js/ui/jquery.ui.widget.js') }}
		{{ HTML::script('/js/ui/jquery.ui.button.js') }}
		{{ HTML::script('/js/ui/jquery.ui.tabs.js') }}
		{{ HTML::script('/js/ui/jquery.ui.dialog.js') }}
		{{ HTML::script('/js/mainTabbedInterface.js') }}
		{{ HTML::script('/js/ace.js') }}
		<style>
		#tabs { margin-top: 0em; }
		#tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
		#add_tab { cursor: pointer; }
		</style>
		<link href="../../public/css/projectsPage.css" rel="stylesheet" type="text/css">
		<body background="{{ URL::asset('css/images/adjbackground.png') }}">
			<div id="topLeft">
            
			</div>
			<div id="header">
				<h1 style="color:#FFFFFF; text-align: center; padding-top:20px;">{{ $project }}</h1>
				<h4 style="color:#FFFFFF; text-align: center; padding-top:10px;">{{ $user }}</h4>
			</div>
			<div id="topRight">
            	<center>
					<ul class="nav nav-pills-square nav-stacked">
                       <!-- <a href ="{{ URL::to("user/$user/projects") }}" class="btn btn-lgr btn-account btn-block" type="button">My Projects</a> -->
						<a href ="https://github.com/{{ $user }}/{{ $project }}" class="btn btn-lgr btn-account btn-block" type="button">GitHub</a>
						<button class="btn btn-lgr btn-account btn-block" type="button" >Logout</button>
					</ul>
                </center>
			</div>.
          	<center>
			<div id="projectsPage">
				
                <table width="100%">
                	<tr>
                    <th width="50%" align="left"> <h1>Project Name</h1> </th>
                    <th width="50%" align="right"> <h1>Date Last Saved</h1> </th>
                    </tr>
                </table>
                <h1 align="left"> Project 1 </h1>
           	  <h3 align="right"> 10/22/2013 </h3><br/>
                <h1 align="left"> Project 2 </h1>
           	  <h3 align="right"> 10/31/2013 </h3><br/>
                <h1 align="left"> Project 3 </h1>
           	  <h3 align="right"> 12/22/2013 </h3><br/>
                <h1 align="left"> Project 4 </h1>
           	  <h3 align="right"> 01/26/2014 </h3><br/>
            </div>
            </center>
<!-- 		<div id="optionSideBar">
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
-->
		</body>
@endsection
