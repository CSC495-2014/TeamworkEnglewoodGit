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
        $searchDir = FileController::ROOT . $dir;
        $listing = scandir($searchDir);

        // If we want to see the base directory, only show the $project folder as the only top-level folder.
        if ($dir == '/') {
            return View::make(
                'filesystem_list',
                [
                    'folders' => [['name' => $project, 'path' => $dir . $project . '/']],
                    'files' => []
                ]
            );
        }

        $folders = [];
        $files = [];


        foreach ($listing as $item)
        {
            $item = [
                'name' => $item,
                'path' => $dir . $item . (is_dir($searchDir . $item) ? '/' : ''),
                'ext'  => $this->getFileExtension($item)
            ];

            if ($item['name'] == '.' or $item['name'] == '..')
            {
                continue;
            }
            else if (is_dir($searchDir . $item['name']))
            {
                $folders[] = $item;
            }
            else
            {
                $files[] = $item;
            }
        }

        return View::make('filesystem_list', ['folders' => $folders, 'files' => $files]);
    }

	/**
	 * Display a listing of the resource.
     *
	 * @return Response
	 */
	public function index()
	{
        //
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($user, $project, $filepath)
	{
        echo htmlentities(file_get_contents(FileController::ROOT . $filepath));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}