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

//                    var gitUntracked = "??",
//                        gitModified = " M",
//                        gitAdded = "A ";
//
//                    var gitStatus = {
//                        "/artisan": " M",
//                        "/composer.json": "??",
//                        "/readme.md": "A "
//                    };

                    $('#filesystem').fileTree({ script: "{{URL::action('FileController@indexPost', [$user, $project])}}", onLoad: applyGitStatus }, function(filepath) {
                        var filename = filepath.substring(filepath.lastIndexOf("/") + 1);

                        $.get('{{ URL::to("/user/$user/project/$project/file") }}' + filepath, function (data) {
                            window.addTab(filename, data);
                        });

                    });
                });

                RegExp.escape = function(s) {
                    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                };

                function basename(path) {

                    if (path.charAt(path.length - 1) == '/') {
                        path = path.substring(0, path.length - 1);
                    }

                    return path.replace(/^.*[\/\\]/g, '');
                }

                function dirname(path) {
                    return path.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');
                }

                /**
                 * Given jqueryFileTree a element, sort the folder.
                 * @param $folder
                 */
                function sortFolder($folder) {
                    console.log('In sortFolder');
                    var $contents = $folder.children('li').get();

                    $contents.sort(function (a, b) {
                        var $a = $(a), $b = $(b);

                        if ($a.hasClass('directory') && $b.hasClass('file')) {
                            return -1;
                        } else if ($a.hasClass('file') && $b.hasClass('directory')) {
                            return 1;
                        } else {
                            return $a.children('a').first().text().localeCompare($b.children('a').first().text());
                        }
                    });

                    $.each($contents, function(index, item) {
                        $folder.append(item);
                    });
                }

                /* ********************
                 * Filesystem actions.
                 **********************/

                function applyGitStatus() {
                    console.log('Entering applyGitStatus()');

                    // remove git formatting from all git elements
                    var $files = $('.git');
                    $files.attr('class', 'git');

                    $.ajax({
                        // TODO: fix this
                        url: '{{ URL::to("/user/$user/projects") }}',
                        type: 'GET',
//                        dataType: 'json',
                        success: function (result) {
                            console.log('Returned from server');

                            result = {
                                "/artisan": " M",
                                "/composer.json": "??",
                                "/readme.md": "A ",
                                "/bootstrap/": "A ",
                                "/app/filters.php": " M",
                                "/app/start/": " M"
                            };

                            var rel = null;

                            $files.each(function(idx) {
                                rel = $(this).attr('rel');

                                if (result.hasOwnProperty(rel)) {

                                    switch (result[rel]) {
                                        case 'A ':
                                            console.log('git-added: ' + rel);
                                            $(this).addClass('git-added'); break;
                                        case ' M':
                                            console.log('git-modified: ' + rel);
                                            $(this).addClass('git-modified'); break;
                                        case '??':
                                            console.log('git-untracked: ' + rel);
                                            $(this).addClass('git-untracked'); break;
                                    }

                                }
                            });

                        }
                    });
                }

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
                 * Rename a file/directory to a target file/directory.
                 *
                 * @param source
                 * @param destination
                 */
                function fsRename(source, destination) {
                    // If renaming directory, make sure to refresh directory.

                    var $file = $("a[rel='" + source + "']");

                    $file.text(basename(destination));
                    $file.attr('rel', destination);

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/move") }}',
                        type: 'POST',
                        data: JSON.stringify({
                            src: source,
                            dest: destination
                        }),
                        contentType: 'application/json; charset=utf-8',
                        failure: function(data) {
                            alert('Unable to rename file.');
                        }
                    });

                    var $item = $file.parent();
                    var $containingFolder = $file.parent().parent();

                    // If we just renamed a directory we need to fix the links of all expanded subitems.
                    if ($item.hasClass('directory')) {
                        var $subitems = $item.find('a');
                        var $regex = new RegExp('^' + RegExp.escape(source));
                        $.each($subitems, function() {
                            $(this).attr('rel', $(this).attr('rel').replace($regex, destination));
                        });
                    }

                    // After renaming, we don't know whether the containing folder is in alpha order,
                    // so we sort it.
                    sortFolder($containingFolder);
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

                    $("li[rel='" + path + "']").remove();

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/file") }}' + path,
                        type: 'DELETE'
                    })
                }

                /**
                 * Remove a directory at the given path.
                 *
                 * @param {string} path Path of directory to delete.
                 */
                function fsRmdir(path) {
                    console.log('Removing ' + path);
                    $("li[rel='" + path + "']").remove();
                }

                function fsRefresh(path) {
                    console.log('In fsRefresh(' + path + ')');

                    $dir = $("a[rel='" + path + "']");
                    $parent = $dir.parent();

                    if ($parent.hasClass('directory') && $parent.hasClass('expanded')) {
                        console.log('Expanded directory found');
                        $dir.click();
                        $dir.click();
                    }
                }



            </script>
		</body>
@endsection
