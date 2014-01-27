<!DOCTYPE HTML>
<html>
    <head>
        <title>Filesystem Test</title>
        {{ HTML::style('/css/bootstrap.css') }}
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::style('/css/jquery.contextMenu.css') }}
        {{ HTML::script('/js/jquery-1.10.2.js') }}
        {{ HTML::script('/js/jqueryFileTree.js') }}
        {{ HTML::script('/js/jquery.ui.position.js') }}
        {{ HTML::script('/js/jquery.contextMenu.js') }}
        {{ HTML::script('/js/bootstrap.js') }}
    </head>
    <body>
        <h1>Filesystem</h1>
        <div id="filesystem"></div>
    @include('modals')
    {{ HTML::script('/js/filesystem.js') }}
    <script>
        $(document).ready( function() {
            $('#filesystem').fileTree({ script: '{{URL::action('FileController@indexPost')}}' }, function(file) {
                alert(file);
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
</html>