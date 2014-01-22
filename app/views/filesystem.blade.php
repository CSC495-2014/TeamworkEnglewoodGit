<!DOCTYPE HTML>
<html>
    <head>
        <title>Filesystem Test</title>
        {{ HTML::style('/css/jqueryFileTree.css') }}
        {{ HTML::script('/js/jquery-1.10.2.js') }}
        {{ HTML::script('/js/jqueryFileTree.js') }}
    </head>
    <body>
        <div id="filesystem"></div>
        <script>
            $(document).ready( function() {
                $('#filesystem').fileTree({ script: '{{URL::action('FileController@indexPost')}}' }, function(file) {
                    alert(file);
                });
            });
        </script>
    </body>
</html>