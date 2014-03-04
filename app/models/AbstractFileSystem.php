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
	protected $userName;

	/**
	* Project name of the instance of this class.
	* This will be used to create the user's project path.
	*
	* This will be set in the constructor.
	*
	* @var string
	*/
	public $projectName;

	/**
	*
	* instantiates a GitWrapper object. All of the local Git commands
	* will be executed with methods of this object.
	* methods can use it.
	*
	* @param string @userName
	* @param string @projectName
	*/

	function __construct($userName, $projectName)
	{
		$this->userName = $userName;
		$this->projectName = $projectName;
	}

	/**
	*
	* return the user name.
	*
	* @return string $userName
	*/
	public function getUserName()
	{
		return $this->userName;
	}

	/**
	*
	* set the user name.
	*
	* @param string $userName
	*/
	public function setUserName($userName)
	{
		$this->userName = $userName;
	}

	/**
	*
	* return the project name.
	*
	* @return string $projectName
	*/
	public function getProjectName()
	{
		return $this->projectName;
	}

	/**
	*
	* set the project name.
	*
	* @param string $projectName
	*/
	public function setProjectName($projectName)
	{
		$this->projectName = $projectName;
	}

	/**
	*
	* will create a full path to a given resource within the user's project dir.
	*
	* @param string $path
	* @return string full path within the application's file system
	*/
	public function getPath($path = "")
	{
		return AbstractFileSystem::ROOT . 'users/' . $this->getUserName() . '/projects/' . $this->getProjectName() . '/' . $path;
	}

}

?>