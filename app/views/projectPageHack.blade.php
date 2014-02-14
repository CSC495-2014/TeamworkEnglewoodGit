@extends('layout')

@section('content')
<ul>
    @foreach ($projects as $project)
    <li><a href="{{ URL::to("user/$user/project/$project/editor") }} ">{{ $project }}</a></li>
    @endforeach
</ul>
@endsection
