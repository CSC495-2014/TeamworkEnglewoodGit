@extends('projectsPage')

@section('table')

    <ol>
        @for($i = 0; $i < sizeOf($projects); $i++)
            <tr class="tr">
                <td id="projectName">
                    <h3><a href="{{ URL::to("/user/user/project/project/editor") }}">
                        <center>{{ $projects[$i]['name'] }}</center></a>
                    </h3></td>
                <td><p><center>{{ $projects[$i]['description'] }}</center></p></td>
                <td><h4><center>{{ $projects[$i]['date'] }}</center></h4></td>
            </tr>
        @endfor
    </ol>

@endsection