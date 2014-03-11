@extends('layout')

@section('content')
        {{ HTML::style('/css/bootstrap.css') }}
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
        {{ HTML::style('/css/jquery.ui.all.css') }}
        {{ HTML::style('/css/demos.css')}}
        {{ HTML::style('css/editor.css') }}

        {{ HTML::script('/js/jquery-1.10.2.js') }}
        {{ HTML::script('/js/jqueryFileTree.js') }}
        {{ HTML::script('/js/jquery.ui.position.js') }}
        {{ HTML::script('/js/jquery.contextMenu.js') }}
        {{ HTML::script('/js/helpers.js') }}
        {{ HTML::script('/js/bootstrap.js') }}
        {{ HTML::script('/js/mainTabbedInterface.js') }}
        {{ HTML::script('/js/ace.js') }}

        {{ HTML::script('/js/ui/jquery.ui.position.js') }}
        {{ HTML::script('/js/ui/jquery.ui.core.js') }}
        {{ HTML::script('/js/ui/jquery.ui.widget.js') }}
        {{ HTML::script('/js/ui/jquery.ui.button.js') }}
        {{ HTML::script('/js/ui/jquery.ui.tabs.js') }}

        {{ HTML::script('/js/handlebars-1.0.rc.1.min.js') }}

        <script id="h-git-custom-prompt-modal" type="text/x-handlebars-template">@include('handlebars/git-custom-prompt-modal')</script>
        <script id="h-git-custom-confirm-modal" type="text/x-handlebars-template">@include('handlebars/git-custom-confirm-modal')</script>
        <script id="h-git-commit-modal" type="text/x-handlebars-template">@include('handlebars/git-commit-modal')</script>
        <script id="h-git-push-modal" type="text/x-handlebars-template">@include('handlebars/git-push-modal')</script>

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

                    <!--Tabbed Interface-->
                    </div>
                    <div id="editor">
                        <div id="tabs">
                            <ul>
                            </ul>
                        </div>
                    </div>
                    <!--End of Tabbed Interface-->

                    <div id="optionSideBar">
                        <div class="panel panel-default">
                          <div class="panel-body">
                            <h4>File Options</h4>
                            <button class="btn btn-lg btn-file btn-block" type="button" onclick="saveFile()">Save</button>
                            <div id="saveAlert"></div>
                            <hr/>
                            <h4>Git Options</h4>
                            <button id="git-commit" class="btn btn-lg btn-project btn-block" type="button">Commit</button>
                            <button id="git-push" class="btn btn-lg btn-project btn-block" type="button">Push</button>
                            <button id="git-custom" class="btn btn-lg btn-project btn-block" type="button">Custom</button>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('modals')
            {{ HTML::script('/js/filesystem.js') }}
            <script>
                $(document).ready( function() {
                    $('#filesystem').fileTree({ script: "{{URL::action('FileController@indexPost', [$user, $project])}}", onLoad: applyGitStatus }, function(filepath) {
                        var filename = filepath.substring(filepath.lastIndexOf("/") + 1);

                        $.get('{{ URL::to("/user/$user/project/$project/file") }}' + filepath, function (data) {
                            window.addTab(filepath, data);
                        });
                    });

                    var $gitCommit = $('#git-commit');
                    var $gitPush = $('#git-push');
                    var $gitCustom = $('#git-custom');

                    $gitCommit.bind('click', function(event){
                        console.log('git-commit clicked');

                        // Load the modal from the handlebars template.
                        var commitSource = $('#h-git-commit-modal').html();
                        var commitTemplate = Handlebars.compile(commitSource);
                        var commitData = {placeholder: 'Sample git commit message.' };
                        var commitView = commitTemplate(commitData);

                        $(document.body).append(commitView);
                        var $commitModal = $('#git-commit-modal');

                        // Destroy modal after it becomes hidden.
                        $commitModal.bind('hidden.bs.modal', function(event){ $commitModal.remove(); });

                        // Execute a request and show results modal.
                        $commitModal.find('.positive').bind('click', function(executeEvent){
                            var message = $('#git-commit-modal-message').val();

                            if (!message) {
                                alert('Commit message required.');
                                return;
                            }

                            var $commitButton = $(executeEvent.target);
                            // Remove callbacks so user does not make multiple requests with one modal.
                            $commitButton.unbind();

                            // Change button text when it is clicked while the AJAX call is being made.
                            $commitButton.text('Executing...');

                            $.ajax({
                                url: '{{ URL::to("/user/$user/project/$project/git-commit") }}',
                                type: 'POST',
                                data: JSON.stringify({
                                    message: message
                                }),
                                contentType: 'application/json; charset=utf-8',
                                statusCode: {
                                    500: function() {
                                        alert('Not yet implemented on server.');
                                        $commitButton.text('Commit');
                                    },
                                    400:function(data) {
                                        alert('Nothing to commit, working directory clean.');
                                        $commitButton.text('Commit');
                                        $commitModal.modal('hide');
                                    }
                                },
                                success: function(data) {
                                    console.log('Git commit: ' + message);
                                    $commitModal.modal('hide');
                                    applyGitStatus();
                                },
                                failure: function(data) {
                                    alert('Unable to commit. Error: ' + data ? data : 1);
                                    $commitButton.text('Commit');
                                }
                            });
                        });

                        // Make the commit modal visible.
                        $commitModal.modal();
                    });

                    $gitPush.bind('click', function(event){
                        console.log('git-push clicked');

                        // Load the modal from the handlebars template.
                        var commitSource = $('#h-git-push-modal').html();
                        var commitTemplate = Handlebars.compile(commitSource);
                        var commitData = {
                            remotePlaceholder: 'remote (example: origin)',
                            branchPlaceholder: 'branch (example: master)'
                        };
                        var commitView = commitTemplate(commitData);

                        $(document.body).append(commitView);
                        var $commitModal = $('#git-push-modal');

                        // Destroy modal after it becomes hidden.
                        $commitModal.bind('hidden.bs.modal', function(event){ $commitModal.remove(); });

                        // Execute a request and show results modal.
                        $commitModal.find('.positive').bind('click', function(executeEvent){
                            var remote = $('#git-push-modal-remote').val();
                            var branch = $('#git-push-modal-branch').val();

                            if (!remote && !branch) {
                                alert('Must provide remote and branch.');
                                return;
                            }

                            var $commitButton = $(executeEvent.target);
                            // Remove callbacks so user does not make multiple requests with one modal.
                            $commitButton.unbind();

                            // Change button text when it is clicked while the AJAX call is being made.
                            $commitButton.text('Executing...');

                            $.ajax({
                                url: '{{ URL::to("/user/$user/project/$project/git-push") }}',
                                type: 'POST',
                                data: JSON.stringify({
                                    remote: remote,
                                    branch: branch
                                }),
                                contentType: 'application/json; charset=utf-8',
                                statusCode: {
                                    500: function(data) {
                                        alert(data.responseText);
                                        $commitButton.text('Push');
                                    }
                                },
                                success: function(data) {
                                    console.log('git push ' + remote + ' ' + branch);
                                    $commitModal.modal('hide');
                                    applyGitStatus();
                                },
                                failure: function(data) {
                                    alert('Unable to push. Error: ' + data ? data : 1);
                                    $commitButton.text('Push');
                                }
                            });
                        });

                        // Make the commit modal visible.
                        $commitModal.modal();
                    });

                    $gitCustom.bind('click', function(event){
                        console.log('git-custom clicked');

                        // Load the modal from the handlebars template.
                        var promptSource = $('#h-git-custom-prompt-modal').html();
                        var promptTemplate = Handlebars.compile(promptSource);
                        var promptData = {placeholder: 'git add /path/to/file' };
                        var promptView = promptTemplate(promptData);

                        $(document.body).append(promptView);
                        var $promptModal = $('#git-custom-prompt-modal');

                        // Destroy modal after it becomes hidden.
                        $promptModal.bind('hidden.bs.modal', function(event){ $promptModal.remove(); });

                        // Execute a request and show results modal.
                        $promptModal.find('.positive').bind('click', function(executeEvent){
                            var $promptButton = $(executeEvent.target);
                            // Remove callbacks so user does not make multiple requests with one modal.
                            $promptButton.unbind();
                            // Change button text when it is clicked while the AJAX call is being made.
                            $promptButton.text('Executing...');

                            // TODO: do some ajax call here...
                            setTimeout(function(){
                                var confirmSource = $('#h-git-custom-confirm-modal').html();
                                var confirmTemplate = Handlebars.compile(confirmSource);
                                var confirmData = {
                                    output: 'Successful command\nwith\nsome\nnewlines\nand a really long line that bleeds off of the side of the modal, boy do I hope this is scrollable'
                                };
                                var confirmView = confirmTemplate(confirmData);

                                $(document.body).append(confirmView);
                                var $confirmModal = $('#git-custom-confirm-modal');

                                // Destroy modal after it becomes hidden.
                                $confirmModal.bind('hidden.bs.modal', function(event){ $confirmModal.remove(); });
                                $confirmModal.find('.positive').bind('click', function(dismissEvent){
                                    $confirmModal.modal('hide');
                                });

                                $confirmModal.modal();

                                $promptModal.modal('hide');
                                applyGitStatus();
                            }, 1000); // end setTimeout

                        });

                        // Make the prompt modal visible.
                        $promptModal.modal();
                    });
                });



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
                        url: '{{ URL::to("/user/$user/project/$project/git-status") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function (result) {
                            var rel = null;

                            $files.each(function(idx) {
                                rel = $(this).attr('rel');

                                if (result.hasOwnProperty(rel.substr(1))) {

                                    switch (result[rel.substr(1)]) {
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
                    // TODO: CHANGE EXTENSION.

                    var $file = $("a[rel='" + source + "']");

                    var filename = basename(destination);
                    $file.text(filename);
                    $file.attr('rel', destination);
                    var $container = $file.parent();

                    // Remove old extension and add new one.
                    if ($container.hasClass('file')) {
                        $container.removeClass(function(i, c) {
                            var matches = c.match(/ext_\w+/g);

                            if (matches) {
                                return matches.join(" ");
                            } else {
                                return '';
                            }
                        }).addClass(getFileExtension(filename));
                    }

//                    alert('Broken on server @jkwendt #94');
                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/move") }}',
                        type: 'POST',
                        data: JSON.stringify({
                            src: source,
                            dest: destination
                        }),
                        statusCode: {
                            500: function() {
                                alert('Not yet implemented on server.');
                            }
                        },
                        contentType: 'application/json; charset=utf-8',
                        failure: function(data) {
                            alert('Unable to rename file. Error: ' + data ? data : 1);
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
                    var parentPath;
                    if (path.indexOf('null') == 0) {
                        path = '/' + path.substring('null'.length);
                        parentPath = 'null';
                    } else {
                        parentPath = dirname(path);
                    }

                    console.log('Creating ' + path);

                    var filename = basename(path);

                    var html = $.parseHTML('<li class="directory collapsed"><a href="#" class="git" rel="' + path + '">' + filename + '</a></li>')[0];

                    var $parent = $("a[rel='" + parentPath + "']");
                    var $parentLi = $parent.parent();
                    var $parentJqueryFileTree = $parentLi.children('ul.jqueryFileTree');

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/mkdir") }}',
                        type: 'POST',
                        data: JSON.stringify({
                            path: path
                        }),
                        statusCode: {
                            400: function() {
                                alert('Unable to create directory.');
                            }
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function(data) {
                            $parentJqueryFileTree.append(html);
                            $(html).children().bind(window.jqueryFileTree.folderEvent, window.jqueryFileTree.handler);

                            sortFolder($parentJqueryFileTree);
                            applyGitStatus();
                            console.log('Created: ' + path);
                        }
                    });
                }

                /**
                 * Create a file at the given path.
                 *
                 * @param {string} path Path of file to create.
                 */
                function fsTouch(path) {
                    var parentPath;
                    if (path.indexOf('null') == 0) {
                        path = '/' + path.substring('null'.length);
                        parentPath = 'null';
                    } else {
                        parentPath = dirname(path);
                    }

                    console.log('Creating: ' + path);

//                    var parentPath = dirname(path);
                    var filename = basename(path);
                    var ext = getFileExtension(filename);
                    var html = $.parseHTML('<li class="file ' + ext + '"><a href="#" class="git" rel="' + path + '">' + filename + '</a></li>')[0];


                    var $parent = $("a[rel='" + parentPath + "']");
                    var $parentLi = $parent.parent();
                    var $parentJqueryFileTree = $parentLi.children('ul.jqueryFileTree');

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/file") }}' + path,
                        type: 'PUT',
                        statusCode: {
                            400: function() {
                                alert('Unable to create file.');
                            }
                        },
                        success: function(data) {
                            $parentJqueryFileTree.append(html);
                            $(html).children().bind(window.jqueryFileTree.folderEvent, window.jqueryFileTree.handler);

                            sortFolder($parentJqueryFileTree);
                            applyGitStatus();
                            window.addTab(path, '');

                            console.log('Created: ' + path);
                        }
                    });
                }

                /**
                 * Remove a file at the given path.
                 *
                 * @param {string} path Path of file to delete.
                 */
                function fsRm(path) {
                    console.log('Removing ' + path);

                    $("a[rel='" + path + "']").parent().remove();

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/file") }}' + path,
                        type: 'DELETE'
                    });
                }

                /**
                 * Remove a directory at the given path.
                 *
                 * @param {string} path Path of directory to delete.
                 */
                function fsRmdir(path) {
                    console.log('In fsRmdir');
                    console.log('Removing ' + path);

                    $("a[rel='" + path + "']").parent().remove();

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/file") }}' + path,
                        type: 'DELETE'
                    });
                }

                function fsRefresh(path) {
                    console.log('In fsRefresh(' + path + ')');

                    var $dir = $("a[rel='" + path + "']");
                    var $parent = $dir.parent();

                    if ($parent.hasClass('directory') && $parent.hasClass('expanded')) {
                        console.log('Expanded directory found');
                        $dir.click();
                        $dir.click();
                    }
                }

                function fsGitAdd(path) {
                    if (path == null || path == 'null') { path = '/'; }
                    console.log('In fsGitAdd(' + path + ')');

                    var $item = $("a[rel='" + path + "']");

                    if (!$item.hasClass('git-modified') && !$item.hasClass('git-untracked')) {
                        console.log("Won't change color if added.");
                    } else {
                        $item.attr('class', 'git');
                        $item.addClass('git-added');
                    }

                    $.ajax({
                        url: '{{ URL::to("/user/$user/project/$project/git-add") }}',
                        type: 'POST',
                        data: JSON.stringify({
                            item: path.substr(1)
                        }),
                        contentType: 'application/json; charset=utf-8',
                        statusCode: {
                            500: function() {
                                alert('Not yet implemented on server.');
                            }
                        },
                        success: function(data) {
                            console.log('git-added: ' + path);
//                            $("a[rel='" + path + "]'").addClass('git-added');
                        },
                        failure: function(data) {
                            alert('Unable to rename file. Error: ' + data ? data : 1);
                        }
                    });
                }

                /**
                   * Save a file at the given path.
                  *
                  * @param {string} path Path of file to save.
               */
                function saveFile(){

                    $.ajax({
                         url: '{{ URL::to("/user/$user/project/$project/file") }}' + window.getTabPath(),
                         type: 'PUT',
                         data: window.getTabContent(),
                         statusCode: {
                             400: function() {
                                 alert('Unable to create file.');
                             }
                         },
                         success: function(data) {
                             document.getElementById("saveAlert").innerHTML= basename(window.getTabPath()) + ' is saved!';
                         },
                         failure: function(data) {
                             //document.getElementById("saveAlert").innerHTML= 'Fail to save ' + basename(window.getTabPath());
                             alert('Unable to save file. Error: ' + data ? data : 1);
                         }
                   });

                }

            </script>
        </body>
@endsection
