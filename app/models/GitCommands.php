<?php
use GitWrapper\GitWrapper;

class GitCommands extends AbstractFileSystem
{

	/**
	*
	* GitWrapper object that will be used to execute Git commands to local file system. 
	*
	* @var wrapper
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
	* Setting the user's private key
	*
	* @param string $userName
	* @param string $projectName
	*/
	function __construct($userName, $projectName)
	{
		$this->wrapper = new GitWrapper();
		$this->wrapper->setPrivateKey('../data/' . 'users/' . $userName . '/id_rsa');
		// AbstractFileSystem constructor
		parent::__construct($userName, $projectName);
	}

	/** 
	*
	* sets wrapper to a GitWrapper object
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
	* returns a working copy object of the repo.
	*
	* @return WorkingCopy
	*/
	public function getWorkingCopy()
	{
		return $this->getWrapper()->workingCopy($this->getPath());	
	}

	/**
	*
	* sets username and email configuration for the 
	* 
	* for now, this is hardcoded in for testing.
	*
	* TODO: This should be automatically set in the constructor,
	* by querying the DB for the email.
	* 
	*/
	public function setIdentity()
	{
		$this->getWorkingCopy()
					->config('user.name', $this->getUserName())
					->config('user.email', 'zachary.mance@gmail.com');

	}

	/**
	* 
	* clone a repo into a new directory 
	* The directory name will be the same as the project name   
	*/
	public function gitClone()
	{
		// example SSH URL - git@github.com:ZAM-/TestRepo.git'
		$repoURL = 'git@github.com:' . $this->getUserName() . '/' . $this->getProjectName() . '.git';
		$path = $this->getPath();
		$this->getWrapper()->clone($repoURL, $path);
	}

	/**
	* 
	* adds a file to be tracked and staged to commit
	*
	* @param string $path
	*/
	public function gitAdd($path)
	{
		// this only supports adding a single file at a time.
        $WorkingCopy = $this->getWorkingCopy(); // This 
		return $WorkingCopy->add($path);
	}
	
	/**
	*
	* commits the files that were staged with a message
	*
	* @param string $message
	*/
	public function gitCommit($message)
	{
        $WorkingCopy = $this->getWorkingCopy();
		$WorkingCopy->commit($message);
	}

	/**
	* 
	*  removes file from staging
	*
	* @param string $path
	*/
	public function gitRm($path)
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->rm($path);
	}
	
	/**
	* 
	*  returns the status of staging area 
	*
	* @param string $path
	*/
	public function gitStatus()
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->status()->getOutput();
	}

	/**
	* 
	*  adds a new remote repository  
	*
	* @param string $userName
	* @param string $project
	* @param string $alias
	*/
	public function gitRemoteAdd($alias, $url)
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->remote('add', $alias, $url);
	}

	/**
	* 
	*  removes a remote repository  
	*
	* @param string $alias
	*/
	public function gitRemoteRm($alias)
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->remote('remove', $alias);	
	}

	/**
	* 
	*  fetches and merges files from a remote repository  
	*
	* @param string $remoteAlias
	* @param string $remoteBranch
	*/
	public function gitPull($remoteAlias, $remoteBranch)
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->pull($remoteAlias, $remoteBranch);
	}

	/**
	* 
	* pushes changes to the user's remote repository
	*
	* @param string $remoteAlias
	* @param string $remoteBranch
	*/
	public function gitPush($remoteAlias, $remoteBranch)
	{
        $WorkingCopy = $this->getWorkingCopy();
		return $WorkingCopy->push($remoteAlias, $remoteBranch);
	}

	/**
	*
	* Checks whether a repository has already been cloned in the user's project's directory.
	*
	* @return boolean
	*/
 	public function isCloned()
 	{
 		$WorkingCopy = $this->getWorkingCopy();
 		return $WorkingCopy->isCloned();
 	}
	/**
	*
	* Allows custom git commands to be executed
	*
	* @param string $commands
	* @return string
	*/
	public function git($commands)
	{
		$escapedCommands = escapeshellcmd($commands);
		$path = $this->getPath();
		return $this->getWrapper()->git($escapedCommands,$path);
		
	
	}

}
	/* --- Testing of GitCommands public interfaces ---
	
	* After all the testing is done, TestRepo should have have
	* remoteTestFile.txt. RemoteTestRepo should have both MyFile.txt and remoteTestFile.txt
	*
	* I will be testing the following git commands below:
	* 
	* add 
	* rm 
	* clone 
	* status
	* remote add
	* remote rm
	* push 
	* pull
	*
	* The terms project and repository are used interchangeably, since we are treating all repos as projects.
	*/

	/*

	$user = 'ZAM-';
	$project = 'TestRepo';
	$remoteProject = 'RemoteTestRepo';
	$remoteAlias = 'upstream';
	$remoteURL = 'git@github.com:ZAM-/RemoteTestRepo.git';
	$testFile = 'MyTestFile.txt';
	$remoteTestFile = 'remoteTestFile.txt';
	$git = new GitCommands($user, $project);


	// Return false, because the repo is not cloned yet.
	if (!$git->isCloned()){
		print "Not cloned! \n";
	}
	//run custom git command
	print $git->git('status');
	// Will clone into ../data/users/ZAM-/projects/TestRepo/
	print "Cloning " . $project . " project...\n";
	$git->gitClone();
	// MUST set the username and email config for the repo.
	$git->setIdentity();
	// Adding remote repo
	$git->gitRemoteAdd($remoteAlias, $remoteURL);

	// Return true, because the repo was just cloned.
	if ($git->isCloned()){
		print "Cloned! \n";
	}
	// Adding a file, commiting, then pushing.
	touch($git->getPath() . $testFile); // must first create test file witihin the file system.
	$git->gitAdd($testFile);
	// printing out status
	print $git->gitStatus();
	// commit and push to origin master
	$git->gitCommit('Added my test file!');
	$git->gitPush('origin', 'master');
	// Pushing it to the remote repo
	// Making sure to pull before pushing
	$git->gitPull($remoteAlias, 'master');
	$git->gitPush($remoteAlias, 'master');
	// Removing the file, commiting, then pushing.
	$git->gitRm($testFile);
	// Commit and push to origin master branch
	$git->gitCommit('Removed my test file!');
	$git->gitPush('origin', 'master');
	// Remove remote

	// Need to test gitPull()
	// Adding a file to the remote, then will pull into local repo.
	$gitRemote = new GitCommands($user, $remoteProject);
	print "Cloning " . $remoteProject . "project...\n";
	$gitRemote->gitClone();
	$gitRemote->setIdentity();
	touch($gitRemote->getPath() . $remoteTestFile); // must first create file within file system. 
	$gitRemote->gitAdd($remoteTestFile);
	$gitRemote->gitCommit('Added file to remote repo');
	$gitRemote->gitPush('origin', 'master');
	
	// Pulling into TestRepo
	$git->gitPull($remoteAlias, 'master');
	$git->gitPush('origin', 'master');
	$git->gitRemoteRm($remoteAlias);

	*/
?>
