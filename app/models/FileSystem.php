<?php

use GitWrapper\GitWrapper;

class FileSystem {

	const ROOT = '../data/';
	private $userName;
	private $projectName;

	/**
	*			  FileSystem Constructor
	* This will set the userName and projectName so all  of the 
	* class method will be able to access them. 
	* 
	*/
	function __construct($userName, $projectName)
	{
		$this->userName = $userName;
		$this->projectName = $projectName;
	}

	/**
	*			  getUserName
	* This will return the user name. 
	*
	*/
	public function getUserName()
	{
		return $this->userName;
	}

	/**
	*			  getProjectName
	* This will return the project name. 
	*
	*/
	public function getProjectName()
	{
		return $this->projectName;
	}

	/**
	*			  getPath
	* This will create a full path to a given resource. 
	* 
	* @PARAM A path to a resource or file
	* @return full path within the application's file system
	*/
	private function getPath($path)
	{
		return FileSystem::ROOT . 'users/' . $this->userName . '/projects/' . $this->projectName . '/' . $path;
	}
	/**
	*			  getFileExtension
	* returns file extension 
	* Credit - Michael Holler 
	*
	* @PARAM filename
	* @return file extension of file
	*/
	private function getFileExtension($filename) 
	{
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

	/** 
	*             listDir
	* Passed current user directory $dirpath
	* 
	* Credit - Michael Holler  
	*@PARAM current directory
	*@return list of subdirectories and files in current
	* user directory.
	*/
	public function listDir($dirpath = "")
	{
		// Creating path
		$searchDir = FileSystem::getPath($dirpath);
		$listing = scandir($searchDir);
		$folders = [];
		$files = [];

		foreach ($listing as $item)
		{
			$item = [
				'name' => $item,
				'path' => $dir . $item . (is_dir($searchDir . $item) ? '/' : ''),
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
				$item['ext'] = $this->getFileExtension($item['name']);
				$files[] = $item;
			}
		}

		return ['folders' => $folders, 'files' => $files];
	}

	/** 
	*             removeFile
	* Will remove the passed file from the server's file system
	*
	*@PARAM file path
	*@return TRUE on success FALSE on failure 
	*/
	public function removeFile($filepath)
	{
		return unlink(FileSystem::getPath($filepath));
	}

	/** 
	*             removeDir
	* Will recursively remove all files and subdirectories from the 
	* supplied dirpath
	*
	*@PARAM file path
	* 
	*/	
	public function removeDir($dirpath)
	{
		$searchDir = FileSystem::getPath($dirpath);
		if (is_dir($searchDir)) 
		{ 
			$objects = scandir($searchDir); 
			foreach ($objects as $object)
			{ 
				// Ignore hidden files 
				if ($object != "." && $object != "..") 
				{ 
					// If it finds a directory, make the recursive call
					if (filetype($searchDir . "/" . $object) == "dir")
					{
						removeDir($searchDir . "/" . $object);	
					}
					// If it's not a directory then it is a file. Remove file.  
					else
					{
						unlink($searchDir . "/" . $object);
					} 
				} 
			} 
			reset($objects); // Reset internal pointer of array
			rmdir($searchDir); 
		} 

	}

	/** 
	*             save
	* Will save the file contents to the webserver's filesystem
	*
	*@PARAM file path to users project
	*@PARAM cotents of file
	*/	
	public function save($filepath, $contents)
	{
		$searchFile = FileSystem::getPath($filepath);
		$handle = fopen($searchFile, 'w');
		fwrite($handle, $contents);
		fclose($handle);
	}

	public function copy($sourcePath, $destPath)
	{

	}

	public function move($sourcePath, $destPath)
	{

	}

	/* Git commands */
	public function gitClone($project)
	{
		$wrapper = new GitWrapper();
		$wrapper->clone($project, "/tmp/");
	}
	
	public function gitStatus($username, $project)
	{

	}

	public function gitAdd($username, $project, $path)
	{

	}

	public function gitRm($username, $project, $path)
	{

	}

	public function gitCommit($username, $project, $message)
	{

	}

	public function gitRemoteAdd($username, $project, $alias, $link)
	{

	}

	public function gitRemoteRm($username, $project, $alias)
	{

	} // remove remote


	public function gitPush($username, $project, $remoteAlias, $remoteBranch)
	{

	}

	public function gitPull($username, $project, $remoteAlias, $remoteBranch)
	{

	}
 	public function isCloned($user, $project)
 	{

 	}

}

	/* --- Testing of file manipulation public interfaces --- */

	/*
	$testFile = "testFile.txt";
	$test = new FileSystem('ZAM-','test-project');
	$test->save($testFile, "This is some data.\n And some other data.\n");
	$test->removeDir("js"); // If you're testing this, make sure to create the dir first before you attempt to delete.
	$test->removeFile($testFile);
	$listFiles = $test->listDir();
	print_r($listFiles);
x	*/

	/* --- Testing of Git Commands --- */
	$test = new FileSystem('ZAM-','test-project');
	$test->gitClone("git://github.com/ZAM-/TestRepo.git");
?>
