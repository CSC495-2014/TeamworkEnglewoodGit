<?php

class FileController extends \BaseController {

    const ROOT = '../../';

    private function getFileExtension($filename) {

        if (!$filename)
        {
            return null;
        }

        $pos = strrpos($filename, '.');

        if ($pos !== false and substr($filename, -1) != '.')
        {
            return 'ext_' . substr($filename, $pos + 1);
        }
        else
        {
            return null;
        }
    }

    public function indexPost($user, $project)
    {
        $dir = Input::get('dir');
//        $searchDir = FileController::ROOT . $dir;
//        $listing = scandir($searchDir);
//
//        // If we want to see the base directory, only show the $project folder as the only top-level folder.
//        if ($dir == '/') {
//            return View::make(
//                'filesystem_list',
//                [
//                    'folders' => [['name' => $project, 'path' => $dir . $project . '/']],
//                    'files' => []
//                ]
//            );
//        }
//
//        $folders = [];
//        $files = [];
//
//
//        foreach ($listing as $item)
//        {
//            $item = [
//                'name' => $item,
//                'path' => $dir . $item . (is_dir($searchDir . $item) ? '/' : ''),
//                'ext'  => $this->getFileExtension($item)
//            ];
//
//            if ($item['name'] == '.' or $item['name'] == '..')
//            {
//                continue;
//            }
//            else if (is_dir($searchDir . $item['name']))
//            {
//                $folders[] = $item;
//            }
//            else
//            {
//                $files[] = $item;
//            }
//        }

//        return View::make('filesystem_list', ['folders' => $folders, 'files' => $files]);
        $fs = new FileSystem($user, $project);
        return View::make('filesystem_list', $fs->listDir($dir));
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
        $contents = htmlentities(file_get_contents(FileController::ROOT . $path));

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
		//
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
}