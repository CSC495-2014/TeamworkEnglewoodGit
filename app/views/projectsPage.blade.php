@extends('layout')

@section('content')
        {{ HTML::style('/css/bootstrap.css') }}
<!-- these dont appear to be needed either
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
-->
        {{ HTML::style('css/projectsPage.css') }}
<!-- none of theses below appear to be needed
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
-->
        <style>
        #tabs { margin-top: 0em; }
        #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
        #add_tab { cursor: pointer; }
        </style>
        <body background="{{ URL::asset('css/images/adjbackground.png') }}">
            <div id="topLeft">

            </div>
            <div id="header">
                <h1 style="color:#FFFFFF; text-align: center; padding-top:10px;"> $user </h1>   <!-- FIX $USER -->
            </div>
            <div id="topRight">
                <center>
                    <ul class="nav nav-pills-square nav-stacked">
                         <a href ="https://github.com/ $user " class="btn btn-lgr btn-account btn-block" type="button">GitHub</a>   <!-- FIX $USER IN URL -->
                        <button class="btn btn-lgr btn-account btn-block" type="button">Logout</button>
                    </ul>
                </center>
            </div>
            <center>
                <div id="popup">
                    <!-- David's popup window will go here upon clicking on a project name -->
                </div>
                <div id="projectsPage">
                    <table id="projectsTable" class="table table-hover" border="">
                        <!-- the above border property gives each cell a small thin border and does not alter the overall table border-->
                        <tbody link="#000000" vlink="transparent" alink="transparent">
                            <!-- DOESN'T CHANGE THE TEXT COLOR OF THE LINKS AT ALL...FIGURE THIS OUT!! -->
                            <thead class="th">
                                <th><h1><center> Project Name </center></h1></th>
                                <th><h1><center> Description </center></h1></th>
                                <th><h1><center> Date Last Saved </center></h1></th>
                            </thead>
                                @yield('table')
                        </tbody>
                    </table>
                </div>
            </center>
        </body>
@endsection