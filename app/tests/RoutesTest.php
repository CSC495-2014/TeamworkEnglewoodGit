<?php

class RoutesTest extends TestCase {

	/**
	 * Tests routes for TeamworkEnglewoodGit program
	 * @return void
	 */
	 
	public function setUp()
	{
		parent::setUp();
		
		$user = 'testuser';
		$project = 'testRepo';
		
		//first time you run this you must un comment the line below and run once
		//then re comment them and run again.
		//FileSystem::sshKeyGen($user);
		//$fs = new FileSystem($user, $project);
		//mkDir( $fs->getPath(), Config::get('filesystem.permissions.directory'), true);
	}
	
	public function testBasic()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());
	}

	public function testLogin()
	{
		$crawler = $this->client->request('GET', 'login');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testEditor()
	{
		$crawler = $this->client->request('GET', 'user/{user}/project/{project}/editor');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testProjects($user='testuser')//only works if user is hardcoded in. how do i pass in tests
	{
		$crawler = $this->client->request('GET', 'user/'.$user.'/projects');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitStatus($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('GET', '/user/'.$user.'/project/'.$project.'/git-status');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitRm($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('DELETE', '/user/'.$user.'/project/'.$project.'/git-rm');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitAdd($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-add');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitCommit($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-commit');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitPush($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-push');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitPull($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-pull');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitRemoteDelete($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('DELETE', '/user/'.$user.'/project/'.$project.'/git-remote');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testGitAddRemote($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-remote');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}	 
	
	public function testGit($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	} 
	
	public function testGitClone($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/git-clone');
		//fwrite(STDOUT, $this->client->getResponse());
		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testMkdirPost($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/mkdir');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	 public function testCopyPost($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/copy');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	public function testIndexPost($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/files');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	 public function testMovePost($user='testuser',$project='testRepo')
	{
		$crawler = $this->client->request('POST', '/user/'.$user.'/project/'.$project.'/move');

		$this->assertTrue($this->client->getResponse()->isOk());
	}
	
	
	/*
	Route::pattern('file', '.*');
	Route::resource('user.project.file', 'FileController');
	*/
}