@extends('layout')

@section('content')
        {{ HTML::style('/css/bootstrap.css') }}
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
        {{ HTML::style('/css/jquery.ui.all.css') }}
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
		<body background="{{ URL::asset('css/images/adjbackground.png') }} ">
			<div id="container">
				<div id="topContent">
					<div id="topLeft">
					
					</div>
					<div id="header">
						<h1 style="color:#FFFFFF; text-align: center; padding-top:20px;">{{ $project }}</h1>
						<h4 style="color:#FFFFFF; text-align: center; padding-top:10px;">{{ $user }}</h4>
					</div>
					<div id="topRight">
						<a href ="{{ URL::to("user/$user/projects") }}" class="btn btn-lgr btn-account btn-block" type="button">My Projects</a>
						<a href ="https://github.com/{{ $user }}/{{ $project }}" class="btn btn-lgr btn-account btn-block" type="button">GitHub</a>
						<button class="btn btn-lgr btn-account btn-block" type="button">Logout</button>
					</div>
				</div>
				<div id="mainContent"> 		  
					<div id="filesystem">
						
					</div>
					<div id="editor">
						<div id="tabs">
							<ul>
							</ul>
						</div>
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
				</div>	
			</div>	
            @include('modals')
            {{ HTML::script('/js/filesystem.js') }}
            <script>
                $(document).ready( function() {
                    $('#filesystem').fileTree({ script: "{{URL::action('FileController@indexPost', [$user, $project])}}" }, function(filepath) {
                        var filename = filepath.substring(filepath.lastIndexOf("/") + 1);

                    $.get("{{ URL::to('/user/username/project/projectname/file') }}" + filepath, function (data) {
                        window.addTab(filename, data);
                    });

                    });
                })

                /* ********************
                 * Filesystem actions.
                 **********************/

                /**
                 * Move a file/directory to a target directory.
                 *
                 * @param {string} source       File/directory path.
                 * @param {string} destination  Directory path.
                 */
                function fsMv(source, destination) {
                    if (source == destination) { return; }
                    console.log('Moving ' + source + ' to ' + destination);
                }

                /**
                 * Copy a file/directory to a target directory.
                 *
                 * @param {string} source       File/directory path.
                 * @param {string} destination  Directory path.
                 */
                function fsCp(source, destination) {
                    if (source == destination) { return; }
                    console.log('Copying ' + source + ' to ' + destination);
                }

                /**
                 * Create a directory at the given path.
                 *
                 * @param {string} path Path of directory to create.
                 */
                function fsMkdir(path) {
                    console.log('Creating ' + path);
                }

                /**
                 * Create a file at the given path.
                 *
                 * @param {string} path Path of file to create.
                 */
                function fsTouch(path) {
                    console.log('Creating ' + path);
                }

                /**
                 * Remove a file at the given path.
                 *
                 * @param {string} path Path of file to delete.
                 */
                function fsRm(path) {
                    console.log('Removing ' + path);
                }

                /**
                 * Remove a directory at the given path.
                 *
                 * @param {string} path Path of directory to delete.
                 */
                function fsRmdir(path) {
                    console.log('Removing ' + path);
                }


            </script>
		</body>
@endsection
