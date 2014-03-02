@extends('layout')

@section('content')
        {{ HTML::style('/css/bootstrap.css') }}
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
        {{ HTML::style('css/projectsPage.css') }}
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
        <body background="{{ URL::asset('css/images/adjbackground.png') }}">
            <div id="topLeft">

            </div>
            <div id="header">
                <h1 style="color:#FFFFFF; text-align: center; padding-top:10px;">{{ $user }}</h1>
            </div>
            <div id="topRight">
                <center>
                    <ul class="nav nav-pills-square nav-stacked">
                         <a href ="https://github.com/{{ $user }}" class="btn btn-lgr btn-account btn-block" type="button">GitHub</a>
                        <button class="btn btn-lgr btn-account btn-block" type="button" >Logout</button>
                    </ul>
                </center>
            </div>
            <center>
            <div id="projectsPage">
                <table class="table table-hover" border="">
                    <!-- the above border property gives each cell a small thin border and does not alter the overall table border-->
                <tbody>
                    <thead class="th" border="10">
                        <th><h1><center> Project Name </center></h1></th>
                        <th><h1><center> Description </center></h1></th>
                        <th><h1><center> Date Last Saved </center></h1></th>
                    </thead>
                    <tr class="row0">
                        <td><h2> Project 1 </h2></td>
                        <td> short description </td>
                        <td><h3> 10/22/1991 </h3></td>
                    </tr>
                    <tr class="row1">
                        <td><h2> Project 2 </h2></td>
                        <td> short description </td>
                        <td><h3> 12/20/2013 </h3></td>
                    </tr>
                    <tr class="row0">
                        <td><h2> Project 3 </h2></td>
                        <td> short description </td>
                        <td><h3> 01/26/2014 </h3></td>
                    </tr>
                    <tr class="row1">
                        <td><h2> Project 4 </h2></td>
                        <td> short description </td>
                        <td><h3> 02/16/2013 </h3></td>
                    </tr>
                </tbody>
                </table>
            </div>
        </center>
        </body>
@endsection