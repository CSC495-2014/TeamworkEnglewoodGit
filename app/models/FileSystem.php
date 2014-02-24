<?php
require __DIR__ . '/AbstractFileSystem.php';

class FileSystem extends AbstractFileSystem
{
	/**
	* In order for the parent's constructor to be called,
	* need to pass user name and project name to AbstractFileSystem's constructor.
	*
	* @param string $userName
	* @param string $projectName
	*/
	function __construct($userName, $projectName)
	{
		parent::__construct($userName, $projectName); // calling AbstractFileSystem's constructor
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
	* Lists files and directories from $dirPath
	* 
	* Credit - Michael Holler  
	*@param string $dirPath
	*@return list of subdirectories and files in current
	* user directory.
	*/
	public function listDir($dirPath = "")
	{
		$searchDir = FileSystem::getPath($dirPath);
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
	* remove a file from the server's file system
	*
	*@param string $filePath
	*@return TRUE on success FALSE on failure 
	*/
	public function removeFile($filePath)
	{
		return unlink(FileSystem::getPath($filePath));
	}

	/** 
	*        
	* recursively remove all files and subdirectories from the 
	* supplied dirpath
	*
	*@param string $dirPath
	* 
	*/	
	public function removeDir($dirPath)
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
	*             
	* save the file contents to the webserver's filesystem
	*
	*@param string $filePath
	*@param string $contents
	*/	
	public function save($filePath, $contents)
	{
		$searchFile = FileSystem::getPath($filePath);
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

	/* --- Testing of FileSystem public interfaces --- */
	/*
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
	*/
?>
