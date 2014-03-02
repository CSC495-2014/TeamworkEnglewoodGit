<?php

class FileController extends \BaseController {

    const ROOT = '../../';

    public function indexPost($user, $project)
    {
        $dir = Input::get('dir');

        if (FileController::containsParentDirectoryReference($dir))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        $listing = null;

        if ($dir == '/')
        {
            // The requested "root" directory should return the top-level folder only. Its path is set
            // to 'null' so we can see when the user is trying to open the root level directory, since
            // '/' is already taken.
            return View::make('filesystem_list', ['folders' => [['path' => 'null', 'name' => $project]], 'files' => []]);
        }
        else if ($dir === 'null')
        {
            // Now we know that we want to list the contents of the top-level directory, so we change
            // $dir back to something the FileSystem can understand means "open the top-level directory.
            $dir = "/";
        }

        try
        {
            $listing = $fs->listDir($dir);
        }
        catch (Exception $e)
        {
            return Response::make(null, 404);
        }

        return View::make('filesystem_list', $listing);
    }

	/**
	 * Display the specified resource.
	 *
     * @param string $user
     * @param string $project
     * @param string $path
     *
	 * @return Response
	 */
	public function show($user, $project, $path)
	{
        if (FileController::containsParentDirectoryReference($path))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        $contents = htmlentities($fs->read($path));

        $response = Response::make($contents, 200);
        $response->header('Content-Type', 'text/plain');

        return $response;
	}

	/**
	 * Update the specified resource in storage.
	 *
     * @param string $user
     * @param string $project
     * @param string $path
     *
	 * @return Response
	 */
	public function update($user, $project, $path)
	{
        if (FileController::containsParentDirectoryReference($path))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);
        $contents = Request::instance()->getContent();

        try
        {
            $fs->save($path, $contents);
        }
        catch (Exception $e)
        {
            return Response::make(null, 400);
        }

        return Response::make(null, 200);
	}

	/**
	 * Delete the specified file or directory.
	 *
     * @param string $user
     * @param string $project
     * @param string $path
     *
	 * @return Response
	 */
	public function destroy($user, $project, $path)
	{
        if (FileController::containsParentDirectoryReference($path))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        if ($fs->isDir($path))
        {
            $fs->removeDir($path);
        }
        else
        {
            $fs->removeFile($path);
        }

        return Response::make(null, 200);
	}

    public function mkdirPost($user, $project)
    {
        $path = Input::get('path');

        if (FileController::containsParentDirectoryReference($path))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        try {
            if ($fs->makeDir($path))
            {
                return Response::make(null, 200);
            }
        }
        catch (Exception $e) { /* Handled by default below. */ }

        return Response::make(null, 400);
    }

    /**
     * Move file or folder from src to dest.
     *
     * @param string $user
     * @param string $project
     */
    public function movePost($user, $project)
    {
        $src = Input::get('src');
        $dest = Input::get('dest');

        if (FileController::containsParentDirectoryReference($src))
        {
            return FileController::makeParentDirectoryResponse();
        }

        if (FileController::containsParentDirectoryReference($dest))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        $fs->move($src, $dest);

        return Response::make(null, 200);
    }

    public function copyPost($user, $project)
    {
        $src = Input::get('src');
        $dest = Input::get('dest');

        if (FileController::containsParentDirectoryReference($src))
        {
            return FileController::makeParentDirectoryResponse();
        }

        if (FileController::containsParentDirectoryReference($dest))
        {
            return FileController::makeParentDirectoryResponse();
        }

        $fs = new FileSystem($user, $project);

        $fs->copy($src, $dest);

        return Response::make(null, 200);
    }

    public static function containsParentDirectoryReference($path)
    {
        return preg_match('/\.\./', $path);
    }

    public static function makeParentDirectoryResponse()
    {
        return Response::make('Cannot reference previous directory.', 400);
    }
}