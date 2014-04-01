@extends('projectsPage')

@section('table')

    @if(sizeOf($projects) === 0)
        <tr class="tr">
             <td><h2>You have no projects to display at this time.</h2></td>
             <td><h2>Please create one in GitHub before coming back to this page.</h2></td>
             <td><h2>Thank you!</h2></td>
        </tr>
    @endif
    @for($i = 0; $i < sizeOf($projects); $i++)
            <tr class="tr">
                <td  id="projectName" onclick="popUp('{{$user}}', '{{$projects[$i]['name']}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/git-clone")}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/editor")}}')">
                    <h3><ol><center>{{ $projects[$i]['name'] }}</center></ol></h3>
                </td>
                <td onclick="popUp('{{$user}}', '{{$projects[$i]['name']}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/git-clone")}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/editor")}}')">
                    <p><center>{{ $projects[$i]['description'] }}</center></p>
                </td>
                <td onclick="popUp('{{$user}}', '{{$projects[$i]['name']}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/git-clone")}}', '{{URL::to("/user/".$user."/project/".$projects[$i]['name']."/editor")}}')">
                    <h4><center>{{ $projects[$i]['date'] }}</center></h4>
                </td>
            </tr>
    @endfor

@endsection