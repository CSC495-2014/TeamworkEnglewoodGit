<ul class="jqueryFileTree" style="display: none;">
    @foreach ($folders as $folder)
    <li class="directory collapsed"><a href="#" rel="{{ $folder['path'] }}">{{ $folder['name'] }}</a></li>
    @endforeach
    @foreach ($files as $file)
    <li class="file {{ $file['ext'] }}"><a href="#" rel="{{ $file['path'] }}">{{ $file['name'] }}</a></li>
    @endforeach
</ul>