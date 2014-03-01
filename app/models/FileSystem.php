<?php
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
	* @param string $fileName
	* @return file extentions
	*/
	private function getFileExtension($fileName) 
	{
		if (!$fileName)
		{
			return null;
		}

		$pos = strrpos($fileName, '.');

		if ($pos !== false and substr($fileName, -1) != '.')
		{
			return 'ext_' . substr($fileName, $pos + 1);
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
				'path' => $dirPath . $item . (is_dir($searchDir . $item) ? '/' : ''),
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
     * @param string $path
     *
     * @return bool
     */
    public function isDir($path) {
        return is_dir(FileSystem::getPath($path));
    }

    public function removeDir($dirPath) {
        $this->_removeDir(FileSystem::getPath($dirPath));
    }

	/** 
	*        
	* recursively remove all files and subdirectories from the 
	* supplied dirpath
	*
	*@param string $dirPath
	* 
	*/	
	protected function _removeDir($dirPath)
	{
        $objects = scandir($dirPath);
        foreach ($objects as $object)
        {
            // Ignore hidden files
            if ($object != "." && $object != "..")
            {
                // If it finds a directory, make the recursive call
                if (is_dir($dirPath. "/" . $object))
                {
                    $this->_removeDir($dirPath. "/" . $object);
                }
                // If it's not a directory then it is a file. Remove file.
                else
                {
                    unlink($dirPath . "/" . $object);
                }
            }
        }

        reset($objects); // Reset internal pointer of array
        rmdir($dirPath);
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
		print $searchFile;
		$handle = fopen($searchFile, 'w');
		fwrite($handle, $contents);
		fclose($handle);
		// after writing to the file, change the perm to RW for user 
		chmod($searchFile, 600);
	}

    public function read($path)
    {
        return file_get_contents(FileSystem::getPath($path));
    }

	public function copy($sourcePath, $destPath)
	{

	}

	public function move($sourcePath, $destPath)
	{

	}

	/** 
	* 
	* generates an SSH keygen pair and saves the private key in the user's directory
	*
	* RSA Private Key Format - PKCS#1
	* RSA Public Key Format - OpenSSH
	*
	*@param string $user
	*@return string $publickey
	*/	

	public static function sshKeyGen($user)
	{
		// Create key
		$rsa = new Crypt_RSA();
		$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
		extract($rsa->createKey(1024));
		// Save private key to file
		$path = FileSystem::ROOT . 'users/' . $user;
		// need to first create directories since this gets called when a user first logs in.
		mkdir($path, 0700, true); // RW for user
		file_put_contents($path . '/id_rsa.pub', $privatekey);
		chmod($path . '/id_rsa.pub', 600);
		return $publickey;
	}

}

	/* --- Testing of FileSystem public interfaces --- */
	
	/*
	$user = 'ZAM-';
	$project = 'TestRepo';
	$testFile = "testFile.txt";

	// Creating an ssh key and saving the private key under /data/users/ZAM-/
	//FileSystem::sshKeyGen($user);

	// To properly test the FileCommands class, I need to create a dir before hand.
	// example dir -> /data/users/ZAM-/projects/TestRepo/
	// Normally the initial clone would properly create the directory. 
	$fileSystem = new FileSystem($user, $project);
	
	// This saves a test file in the app/data/users/ZAM-/projects/TestRepo directory
	$fileSystem->save($testFile, "This is some data.\n And some other data.\n");
	//$test->removeDir("js"); // If you're testing this, make sure to create the dir first before you attempt to delete.
	//$fileSystem->removeFile($testFile);
	$listFiles = $fileSystem->listDir();
	print_r($listFiles);
	*/
?>
