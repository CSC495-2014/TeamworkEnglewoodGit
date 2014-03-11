@extends('projectsPage')

@section('table')

    @if(sizeOf($projects) === 0)
        <tr class="tr">
             <td><h2>You have no projects to display at this time.</h2></td>
             <td></td>
             <td></td>
        </tr>
    @endif
        @for($i = 0; $i < sizeOf($projects); $i++)
            <tr class="tr">
                <td id="projectName" onclick="popUp('kwpembrook', 'TeamworkEnglewoodGit');">
                    <h3>
                        <center><a href="">{{ $projects[$i]['name'] }}</a></center>
                    </h3></td>
                <td><p><center>{{ $projects[$i]['description'] }}</center></p></td>
                <td><h4><center>{{ $projects[$i]['date'] }}</center></h4></td>
            </tr>
        @endfor

@endsection