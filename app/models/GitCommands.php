<?php

use GitWrapper\GitWrapper;

class GitCommands extends AbstractFileSystem
{

	/**
	*
	* GitWrapper object that will be used to execute Git commands to local file system. 
	*
	* @var GitWrapper
	*/
	private $wrapper;
	
	/**
	*
	* In order for the parent's constructor to be called,
	* need to pass user name and project name to AbstractFileSystem's constructor.
	*
	* Also, instantiates a GitWrapper object. All of the local Git commands
	* will be executed with methods of this object.
	* 
	* @param string $userName
	* @param string $projectName
	*/
	function __construct($userName, $projectName)
	{
		// TODO Need to possibly make a WorkingCopy variable that will be set depending if the repo is cloned or not.
		$this->gitWrapper = new GitWrapper();
		parent::__construct($userName, $projectName); // calling AbstractFileSystem's constructor
	}

	/** 
	*
	* sets wrapper to a GitWrapper object.
	*
	* @param GitWrapper $wrapper
	*/
	public function setWrapper($wrapper)
	{
		$this->wrapper = $wrapper;
	}

	/**
	* 
	* returns gitWrapper.
	*
	* @return GitWrapper
	*/
	public function getWrapper()
	{
		return $this->wrapper;
	}

	/**
	* 
	* clone a repo into a new directory. 
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
	* 
	* adds a file to be tracked and staged to commit.
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
	*
	* commits the files that were staged with a message.
	*
	* @param string $message
	*/
	public function gitCommit($message)
	{
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
		return $WorkingCopy->commit($message);
	}

	/**
	* 
	* pushes changes to the user's remote repository on GitHub. 
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
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
		return $WorkingCopy->rm($path);
	}

	public function gitStatus()
	{

	}

	public function gitRemoteAdd($username, $project, $alias, $link)
	{

	}

	public function gitRemoteRm($username, $project, $alias)
	{

	} // remove remote

	public function gitPull($username, $project, $remoteAlias, $remoteBranch)
	{

	}

 	public function isCloned($user, $project)
 	{

 	}

}
	/* --- Testing of GitCommands public interfaces --- */

	$user = 'ZAM-';
	$project = 'TestRepo';
	$testFile = 'MyFile.txt';
	$git = new GitCommands($user, $project);

	$git->gitClone();
	touch($git->getPath() . $testFile); // Saving test file witihin the file system.
	$git->gitAdd($testFile);
	$git->gitCommit('Added my test file!');
	$git->gitPush('origin', 'master');

?>