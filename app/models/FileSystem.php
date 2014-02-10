<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class FileSystem {

	const $ROOT = '../data/';
	var $userName;
	var $projectName;

	/**
	*			  FileSystem
	 * This will set the userName and projectName so all  of the 
	 * class method will be able to access them. 
	 * 
	 */
	function FileSystem($user, $project)
	{

		this->userName = $user;
		this->projectName = $project;
	}

	/** 
	 *             listDir
	 * Passed current user directory $dirpath
	 *
	 *@PARAM current directory
	 *@return list of subdirectories and files in current
	 * user directory.
	 */
	function listDir($dirpath)
	{

		$searchDir = ROOT . 'users/' . $userName . 'projects/' . $projectName . $dirpath;
        $listings = scandir($searchDir);

        $folders = [];
        $files = [];
		
		$fileSystemList = [];

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

        fileSystemList['folders' => $folders, 'files' => $files];

        return fileSystemList;
	}

	/** 
	 *             removeFile
	 * Will remove the passed file from the server's file system
	 *
	 *@PARAM file path from what?
	 *@return 2xx response if succeeded 
	 *@return 5xx response if failed
	 */
	function removeFile($filepath)
	{
		$searchPath = ROOT . 'users/' . $userName . 'projects/' . $projectName . $filepath;
		if (file_exists($filepath))
		{
			return unlink($filepath);
		}
	}

	/** 
	 *             removeDir
	 * Will recursively remove all files and subdirectories from the 
	 * supplied dirpath
	 *
	 *@PARAM file path from what?
	 *@return 2xx response if succeeded 
	 *@return 5xx response if failed
	 */	
	function removeDir($dirpath)
	{
		$searchDir = ROOT . 'users/' . $userName . 'projects/' . $projectName . $dirpath;
		$listings = scanDir()

	}

	// Will create a file with the given contents if the file does not exist, otherwise it
	// will overwrite the currently existing file.
	/** 
	 *             removeFile
	 * Will remove the passed file from the server's file system
	 *
	 *@PARAM file path from what?
	 *@return list of subdirectories and files in current
	 * user directory.
	 */
	function save($username, $project, $filepath, $contents)
	{

	}

	function copy($username, $project, $sourcePath, $destPath)
	{

	}

	function move($username, $project, $sourcePath, $destPath)
	{

	}

	function gitStatus($username, $project)
	{

	}

	function gitAdd($username, $project, $path)
	{

	}

	function gitRm($username, $project, $path)
	{

	}

	function gitCommit($username, $project, $message)
	{

	}

	function gitRemoteAdd($username, $project, $alias, $link)
	{

	}

	function gitRemoteRm($username, $project, $alias)
	{

	} // remove remote

	function gitClone($username, $project)
	{

	}

	function gitPush($username, $project, $remoteAlias, $remoteBranch)
	{

	}

	function gitPull($username, $project, $remoteAlias, $remoteBranch)
	{

	}

}


