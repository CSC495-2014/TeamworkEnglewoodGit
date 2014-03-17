<?php

class kTest extends TestCase {

	/**
         * Code written for testing the clinet side Filesystem
	 * 	This was run on a custom created directory and 
	 * 	file tree.
         * author: Kenneth McMahon
	*/
	public static function setUpBeforeClass(){
                FileSystem::sshKeyGen('testuser');
                fwrite(STDOUT,"\n".getcwd()."\n");
                fwrite(STDOUT, "\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n");
                //all that exists is /data/users/testuser
                mkdir(getcwd()."/data/users/testuser/projects");
                mkdir(getcwd()."/data/users/testuser/projects/testproject");
	}
        public static function tearDownAfterClass(){
                rmdir(getcwd()."/data/users/testuser/projects/testproject");
                rmdir(getcwd()."/data/users/testuser/projects");
                rmdir(getcwd()."/data/users/testuser");
        }

	public function testmakeDir(){
		$fs = new FileSystem('testuser','testproject');
		
                $filesdir = $fs->makeDir("files");
                $folderone = $fs->makeDir("folderone");
                $foldertwo = $fs->makeDir("foldertwo");
                $copies = $fs->makeDir("files/copies");
		$this->assertTrue($filesdir);
		$this->assertTrue($folderone);
                $this->assertTrue($foldertwo);
                $this->assertTrue($copies);

        }

        /**
         * @depends testmakeDir
         */
	public function testsave(){
		$fs = new FileSystem('testuser','testproject/files');
		
                $fs->save("file1.txt", "file1");
		$fs->save("file2.txt", "file2");
		$fs->save("file3.txt", "file3");
		$fs->save("file4.txt", "file4");
		$fs->save("file5.txt", "file5");
        }

	/**
	* @depends testmakeDir
	*/
	public function testisDir(){
		$fs = new FileSystem('testuser','testproject');
		
                $this->assertTrue($fs->isDir(".."));
		$this->assertTrue($fs->isDir("files"));
		$this->assertTrue($fs->isDir("folderone"));
		$this->assertTrue($fs->isDir("foldertwo"));
		$this->assertFalse($fs->isDir("/files/file1.txt"));
        }

	
	/**
	* @depends testsave
	*/
	public function testread(){
		$fs = new FileSystem('testuser', 'testproject/files');
		
		$contents = $fs->read('file1.txt');
		$this->assertEquals("file1", $contents);
		
		$contents = $fs->read('file2.txt');
		$this->assertEquals("file2", $contents);
		
		$contents = $fs->read('file3.txt');
		$this->assertEquals("file3", $contents);
		
		$contents = $fs->read('file4.txt');
		$this->assertEquals("file4", $contents);
		
		$contents = $fs->read('file5.txt');
		$this->assertEquals("file5", $contents);
	}
	/**
	* @depends testread
	*/
	public function testcopy(){
		$fs = new FileSystem('testuser', 'testproject/files');

		$fs->copy("file1.txt", "copies/file1copy.txt");
		$fs->copy("file2.txt", "../file2copy.txt");
		$fs->copy("file3.txt", "../folderone/file3copy.txt");
		$fs->copy("file4.txt", "../foldertwo/file4copy.txt");
		$fs->copy("file5.txt", "file5copy.txt");
	        
                $cont1 = $fs->read('file1.txt');
                $cont11 = $fs->read('copies/file1copy.txt');
                $cont2 = $fs->read('file2.txt');
                $cont22 = $fs->read('../file2copy.txt');
                $cont3 = $fs->read('file3.txt');
                $cont33 = $fs->read('../folderone/file3copy.txt');
                $cont4 = $fs->read('file4.txt');
                $cont44 = $fs->read('../foldertwo/file4copy.txt');
                $cont5 = $fs->read('file5.txt');
                $cont55 = $fs->read('file5copy.txt');
	}
	/**
	* @depends testsave
	*/
	public function testmove(){
		$fs = new FileSystem('testuser','testproject/files');
		$basepath = getcwd()."/data/users/testuser/projects/testproject";


		$this->assertFileExists($basepath."/files/file1.txt");
		$fs->move("file1.txt", "copies/file1.txt");
		$this->assertFileNotExists($basepath."/files/file1.txt");
		
		$this->assertFileExists($basepath."/files/copies/file1.txt");
		$fs->move("copies/file1.txt", "file1.txt");
		$this->assertFileNotExists($basepath."/files/copies/file1.txt");
		
	}
	/**
	* @depends testcopy
	*/
	public function testremoveFile(){
		$fs = new FileSystem('testuser', 'testproject');
		$basepath = getcwd()."/data/users/testuser/projects/testproject"; 	

		$this->assertFileExists($basepath."/files/copies/file1copy.txt");
		$this->assertFileExists($basepath."/file2copy.txt");
		$this->assertFileExists($basepath."/folderone/file3copy.txt");
		$this->assertFileExists($basepath."/foldertwo/file4copy.txt");
		$this->assertFileExists($basepath."/files/file5copy.txt");		

		$fs->removeFile("/files/copies/file1copy.txt");
		$fs->removeFile("/file2copy.txt");
		$fs->removeFile("/folderone/file3copy.txt");
		$fs->removeFile("/foldertwo/file4copy.txt");
                $fs->removeFile("/files/file5copy.txt");

                $fs->removeFile("/files/file1.txt");
                $fs->removeFile("/files/file2.txt");
                $fs->removeFile("/files/file3.txt");
                $fs->removeFile("/files/file4.txt");
                $fs->removeFile("/files/file5.txt");
                $fs->removeFile("../../id_rsa");
		
		$this->assertFileNotExists($basepath."/files/copies/file1copy.txt");
		$this->assertFileNotExists($basepath."/file2copy.txt");
		$this->assertFileNotExists($basepath."/folderone/file3copy.txt");
		$this->assertFileNotExists($basepath."/foldertwo/file4copy.txt");
		$this->assertFileNotExists($basepath."/files/file5copy.txt");
	}

	/**
	* @depends testmakeDir
        * @depends testremoveFile
	*/
	public function testremoveDir(){
		$fs = new FileSystem('testuser','testproject');
		$projpath = getcwd()."/data/users/testuser/projects/testproject";

                $fs->removeDir("files/copies");
		$fs->removeDir("files");
                $fs->removeDir("folderone");
                $fs->removeDir("foldertwo");

		$this->assertFileNotExists($projpath."/files");
		$this->assertFileNotExists($projpath."/files/copies");
		$this->assertFileNotExists($projpath."/folderone");
		$this->assertFileNotExists($projpath."/foldertwo");
	}
	
}
