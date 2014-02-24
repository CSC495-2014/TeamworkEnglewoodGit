<?php

require_once '../../vendor/autoload.php';
use GitWrapper\GitWrapper;
require __DIR__ . '/AbstractFileSystem.php';

class GitCommands extends AbstractFileSystem
{
/**
	* Root directory where all users and their projects/repos will be stored.
	*
	* @var string 
	*/
	const ROOT = '../data/';
	
	/**
	* GitWrapper object that will be used to execute Git commands to local file system. 
	*
	* @var GitWrapper
	*/
	private $wrapper;
	
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
		$this->gitWrapper = new GitWrapper();
		// TODO Need to possibly make a WorkingCopy variable that will be set depending if the repo is cloned or not.
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
	* Returns gitWrapper.
	*
	* @return GitWrapper
	*/
	public function getWrapper()
	{
		return $this->wrapper;
	}

	/** 
	* Sets wrapper to a GitWrapper object.
	*
	* @param GitWrapper $wrapper
	*/
	public function setWrapper($wrapper)
	{
		$this->wrapper = $wrapper;
	}

	/**
	* This will create a full path to a given resource within the user's project dir. 
	* 
	* @param string $path
	* @return string full path within the application's file system
	*/
	public function getPath($path = "")
	{
		return GitCommands::ROOT . 'users/' . $this->getUserName() . '/projects/' . $this->getProjectName() . '/' . $path;
	}

	/** 
	* Will clone a repo into a new directory. 
	* The directory name will be the same as the project name.   
	*
	*/
	public function gitClone()
	{
		$repoURL = 'https://github.com/' . $this->getUserName() . '/' . $this->getProjectName() . '.git';
		$path = $this->getPath();
		$this->gitWrapper->clone($repoURL, $path);
	}

	/** 
	* Adds a file to be tracked and staged to commit.
	*
	* @param string $path
	*/
	public function gitAdd($path)
	{
		// #TODO: Doesn't support adding new directories along with files.
		// this only supports adding a single file at a time.
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath()); // This 
		return $WorkingCopy->add($path);
	}
	
	/** 
	* Commits the files that were staged with a message.
	*
	* @param string $message
	*/
	public function gitCommit($message)
	{
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
		return $WorkingCopy->commit($message);
	}

	/** 
	* Pushes changes to the user's remote repository on GitHub. 
	*
	* @param string $remoteAlias
	* @param string $remoteBranch
	*/
	public function gitPush($remoteAlias, $remoteBranch)
	{
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
		return $WorkingCopy->push($remoteAlias, $remoteBranch);
	}

	public function gitRm($path)
	{

	}

	public function gitStatus()
	{

	}

	public function gitRemoteAdd($username, $project, $alias, $link)
	{

	}

	public function gitRemoteRm($username, $project, $alias)
	{

	}

	public function gitPull($username, $project, $remoteAlias, $remoteBranch)
	{

	}

 	public function isCloned($user, $project)
 	{

 	}

}
	/* --- Testing of GitCommands public interfaces --- */

	// Initial clone of repo
	$user = 'ZAM-';
	$project = 'TestRepo';
	$testFile = 'MyFile.txt';
	$git = new GitCommands($user, $project);

	$git->gitClone(); // Test for if the project has already been cloned or not.
	touch($git->getPath() . $testFile);
	$git->gitAdd($testFile);
	$git->gitCommit('Added my test file!');
	$git->gitPush('origin', 'master');	
?>