<?php

abstract class AbstractFileSystem
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

	public function setUserName($userName)
	{
		$this->userName = $userName;
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

	public function setProjectName($projectName)
	{
		$this->projectName = $projectName;
	}

	/**
	*			  getPath
	* This will create a full path to a given resource within the user's project dir. 
	* 
	* @PARAM A path to a resource or file
	* @return full path within the application's file system
	*/
	public function getPath($path = "")
	{
		return AbstractFileSystem::ROOT . 'users/' . $this->getUserName() . '/projects/' . $this->getProjectName() . '/' . $path;
	}
}

?>