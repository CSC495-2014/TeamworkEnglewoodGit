<?php
use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;

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
        // AbstractFileSystem constructor
        parent::__construct($userName, $projectName);
				// Setting GitWrapper object
				$this->wrapper = new GitWrapper();
				// Setting Private Key that should have been created with the FileSystem->sshKeyGen()
				// on the user's first successful login.
				$this->wrapper->setPrivateKey(base_path() . AbstractFileSystem::ROOT . 'users/' . $userName . '/id_rsa');
				// Setting git editor env variable to null, so we can supress the editor locally, and the
				// the client side can catch the error.
				$this->wrapper->setEnvVar('GIT_EDITOR', '');
				// Setting the GitHub identification for the user. This allows for commits.
				/*
				$this->getWorkingCopy()
							->config('user.name', $userName)
							->config('user.email', "example@gmail.com");
				*/
				// Create projects dir
				$projectsDir = base_path() . AbstractFileSystem::ROOT . 'users/' . $userName . 'projects/';
				if (!file_exists($projectDir))
				{
					mkdir($projectsDir, Config::get('filesystem.permissions.directory'));

				}
				$db = new DatabaseQueries();
				$email = $db->GetUserEmail($userName);
				$this->getWorkingCopy()
										->config('user.name', $userName)
										->config('user.email', $email);
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
	* @return GitWorkingCopy
	*/
	public function getWorkingCopy()
	{
		return $this->getWrapper()->workingCopy($this->getPath());
	}

	/**
	*
	* clone a repo into a new directory
	* The directory name will be the same as the project name. Will only clone the project if it,
	* doesn't already exist.
	*/
	public function gitClone()
	{
		// example SSH URL - git@github.com:ZAM-/TestRepo.git'
		$repoURL = 'git@github.com:' . $this->getUserName() . '/' . $this->getProjectName() . '.git';
		$projectPath = $this->getPath();
		if (!file_exists($projectPath)) // if the project is not cloned, clone it
		{
			mkdir($projectPath, Config::get('filesystem.permissions.directory'));
			$this->getWrapper()->clone($repoURL, $projectPath);
		}
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
		$outputLines = explode("\n", $WorkingCopy->status(['porcelain' => true])->getOutput());

    $array = [];

    foreach($outputLines as $line) {
    	$status = substr($line, 0, 2);
      $file = substr($line, 3);
      $array[$file] = $status;
    }
    return $array;
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
	* Allows custom git commands to be executed
	*
	* @param string $commands
	* @return string
	*/
	public function git($commands)
	{

		$path = $this->getPath();
		return $this->getWrapper()->git($commands,$path);
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


	// Will clone into ../data/users/ZAM-/projects/TestRepo/
	print "Attempting to clone " . $project . " project...\n";
	$git->gitClone();
	// Adding remote repo
	$git->gitRemoteAdd($remoteAlias, $remoteURL);

	// Adding a file, commiting, then pushing.
	touch($git->getPath() . $testFile); // must first create test file witihin the file system.
	$git->gitAdd($testFile);
	// printing out status
	print_r($git->gitStatus());
	//run custom git command
	print $git->git('status');
	// commit and push to origin master
	$git->gitCommit('Added my test file!');
	$git->gitPush('origin', 'master');

	/*
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
