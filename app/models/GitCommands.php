<?php

require_once '../../vendor/autoload.php';
use GitWrapper\GitWrapper;
require __DIR__ . '/GitCommands.php';

class GitCommands extends AbstractFileSystem
{

	private $wrapper;
	private $git;

	/**
	* This instantiatea a GitWrapper object. All of the local Git commands
	* will be executed with methods of this object. 
	* methods can use it.
	*/
	function __construct()
	{
		$this->gitWrapper = new GitWrapper();
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
	* Returns gitWrapper.
	*
	* @return GitWrapper
	*/
	public function getWrapper()
	{
		return $this->wrapper;
	}

	/** 
	* Will clone a repo into a new directory. 
	* The directory name will be the same as the project name.   
	*
	*/
	public function gitClone()
	{
		$repoURL = 'https://github.com/' . $this->userName . '/' . $this->projectName . '.git';
		$path = $this->getPath();
		$git = $this->gitWrapper->clone($repoURL, $path);
		//GitCommands::setGit($git);	
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
		touch($this->getPath() . $path);
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
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
		return $WorkingCopy->rm($path);
	}

	public function gitStatus()
	{
		$WorkingCopy = $this->gitWrapper->workingCopy($this->getPath());
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

	// Initial clone of repo
	$user = 'ZAM-';
	$project = 'TestRepo';
	$git = new GitCommands($user, $project);
	$git->gitClone(); // Test for if the project has already been cloned or not.
	$git->gitAdd($testFile);
	$git->gitCommit('Added my test file!');
	$git->gitPush('origin', 'master');	
?>