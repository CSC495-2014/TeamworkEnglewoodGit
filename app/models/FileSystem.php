<?php

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

	public function echoPath($path)
	{
		$x = FileSystem::getPath($path);
		echo $x;
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
	*@PARAM file path from what?
	*
	*/
	public function removeFile($filepath)
	{
		$searchFile = FileSystem::getPath($filepath);
		if (file_exists($searchFile))
		{
			return unlink($searchFile);
		}
	}

	/** 
	*             removeDir
	* Will recursively remove all files and subdirectories from the 
	* supplied dirpath
	*
	*@PARAM file path from what?
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
				if ($object != "." && $object != "..") 
				{ 
					if (filetype($searchDir."/".$object) == "dir")
					{
						removeDir($searchDir."/".$object);	
					}  
					else
					{
						unlink($searchDir."/".$object);
					} 
				} 
			} 
			reset($objects); 
			rmdir($searchDir); 
		} 

	}

	/** 
	*             saveFile
	* Will save the file contents to the webserver's filesystem
	*
	*@PARAM file path to users project
	*@PARAM cotents of file
	*/	
	public function saveFile($filepath, $data)
	{
		$searchFile = FileSystem::getPath($filepath);
		$handle = fopen($searchFile, 'w');
		fwrite($handle, $data);
		fclose($handle);
	}

	public function save($filepath, $contents)
	{

	}

	public function copy($sourcePath, $destPath)
	{

	}

	public function move($sourcePath, $destPath)
	{

	}

	/* Git commands */

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

	public function gitClone($username, $project)
	{

	}

	public function gitPush($username, $project, $remoteAlias, $remoteBranch)
	{

	}

	public function gitPull($username, $project, $remoteAlias, $remoteBranch)
	{

	}

	}

	/* --- Testing of public interfaces --- */

	/*
	$testFile = "testFile.txt";
	$test = new FileSystem('ZAM-','test-project');
	$test->saveFile($testFile, "This is some data.\n And some other data.\n");
	$test->removeDir("js"); // If you're testing this, make sure to create the dir first before you attempt to delete.
	$test->removeFile($testFile);
	$listFiles = $test->listDir();
	print_r($listFiles);
	*/	
?>
