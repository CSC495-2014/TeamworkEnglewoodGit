<ul class="jqueryFileTree" style="display: none;">
    @foreach ($folders as $folder)
    <li class="directory collapsed"><a href="#" class="git" rel="{{ $folder['path'] }}">{{ $folder['name'] }}</a></li>
    @endforeach
    @foreach ($files as $file)
    <li class="file {{ $file['ext'] }}"><a href="#" class="git" rel="{{ $file['path'] }}">{{ $file['name'] }}</a></li>
    @endforeach
</ul>