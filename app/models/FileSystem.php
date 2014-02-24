<?php

class FileSystem
{

	/**
	* Root directory where all users and their projects/repos will be stored.
	*
	* @var string 
	*/
	const ROOT = '../data/';
	
	/**
	* User name of the instance of this class. 
	* This will be used to create the user's directory path.
	*
	* This will be set in the constructor.
	*
	* @var string 
	*/
	private $userName;


	/**
	* Project name of the instance of this class. 
	* This will be used to create the user's project path.
	*
	* This will be set in the constructor.
	*
	* @var string 
	*/
	private $projectName;

	/**
	* This instantiates a GitWrapper object. All of the local Git commands
	* will be executed with methods of this object. 
	* methods can use it.
	*
	* This will set the userName and projectName
	*/
	
	function __construct($userName, $projectName)
	{
		$this->userName = $userName;
		$this->projectName = $projectName;
	}

	/**
	*			  
	* This will return the user name. 
	*
	* @return string $userName
	*/
	public function getUserName()
	{
		return $this->userName;
	}

	/**
	*			  
	* This will set the user name. 
	*
	* @param string $username
	*/
	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	/**
	*			  
	* This will return the project name. 
	*
	* @return string $projectName
	*/
	public function getProjectName()
	{
		return $this->projectName;
	}

	/**
	*			  
	* This will set the project name. 
	*
	* @param string $projectName
	*/
	public function setProjectName($projectName)
	{
		$this->projectName = $projectName;
	}

	/**
	* This will create a full path to a given resource within the user's project dir. 
	* 
	* @param string $path
	* @return string full path within the application's file system
	*/
	public function getPath($path = "")
	{
		return FileSystem::ROOT . 'users/' . $this->getUserName() . '/projects/' . $this->getProjectName() . '/' . $path;
	}

	/**
	* returns file extension of specified file
	* Credit - Michael Holler 
	*
	* @param string $filename
	* @return file extentions
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
	*    
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
				'path' => $searchDir . $item . (is_dir($searchDir . $item) ? '/' : ''),
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

}

	 
	$user = 'ZAM-';
	$project = 'TestRepo';
	$testFile = "testFile.txt";

	// To properly test the FileCommands class, I need to possibly create a dir before hand.
	// Normally the initial clone would properly create the directory. 
	$fileSystem = new FileSystem($user, $project);
	
	// This saves a test file in the app/data/users/ZAM-/projects/TestRepo directory
	$fileSystem->save($testFile, "This is some data.\n And some other data.\n");
	//$test->removeDir("js"); // If you're testing this, make sure to create the dir first before you attempt to delete.
	//$test->removeFile($testFile);
	$listFiles = $fileSystem->listDir();
	print_r($listFiles);
	
?>
