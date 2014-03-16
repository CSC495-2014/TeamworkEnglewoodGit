<?php

class kTest extends TestCase {

	/**
         * Code written for testing the clinet side Filesystem
	 * 	This was run on a custom created directory and 
	 * 	file tree.
         * author: Kenneth McMahon
	*/
	public function setUp(){
		parent::setUp();
		$user = 'kjmcmahon';

	        //FileSystem::sshKeyGen($user);
	}
	
	public function testmakeDir(){
		$fs = new FileSystem('kjmcmahon','testproject');
		
		$newdir = $fs->makeDir("testmakedir");
		$highdir = $fs->makeDir("../updir");
		$this->assertTrue($newdir);
		$this->assertTrue($highdir);

	}
       	
	public function testisDir(){
		$fs = new FileSystem('kjmcmahon','testproject');
		
                $this->assertTrue($fs->isDir(".."));
		$this->assertTrue($fs->isDir("files"));
		$this->assertTrue($fs->isDir("folderone"));
		$this->assertTrue($fs->isDir("foldertwo"));
		$this->assertTrue($fs->isDir("inProject"));
		$this->assertFalse($fs->isDir("notafolder"));
		$this->assertFalse($fs->isDir("toplevel.html"));
		$this->assertFalse($fs->isDir("madeup"));	
        }
	
	public function testsave(){
		$fs = new FileSystem('kjmcmahon','testproject/files');
		
                $fs->save("file1.txt", "file1");
		$fs->save("file2.txt", "file2");
		$fs->save("file3.txt", "file3");
		$fs->save("file4.txt", "file4");
		$fs->save("file5.txt", "file5");
		
		
        }
	/**
	* @depends testmakeDir
	*/
	public function testremoveDir(){
		$fs = new FileSystem('kjmcmahon','testproject');
		
		$fs->removeDir("testmakedir");
		$fs->removeDir("../updir");
		// Creating files with . .. or / is prevented by
		// 	the client webpage

		$this->assertFileNotExists("/home/ken/lampstack/frameworks/laravel/data/users/kjmcmahon/projects/testproject/testmakedir");
		$this->assertFileNotExists("/home/ken/lampstack/frameworks/laravel/data/users/kjmcmahon/projects/updir");
	}
	
	
	/**
	* @depends testsave
	*/
	public function testread(){
		$fs = new FileSystem('kjmcmahon', 'testproject/files');
		
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
		$fs = new FileSystem('kjmcmahon', 'testproject/files');

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
		$fs = new FileSystem('kjmcmahon','testproject/files');
		$basepath = "/home/ken/lampstack/frameworks/laravel/data/users/kjmcmahon/projects/testproject/files";

		$this->assertFileExists($basepath."/file1.txt");
		$fs->move("file1.txt", "copies/file1.txt");
		$this->assertFileNotExists($basepath."/file1.txt");
		
		$this->assertFileExists($basepath."/copies/file1.txt");
		$fs->move("copies/file1.txt", "file1.txt");
		$this->assertFileNotExists($basepath."/copies/file1.txt");
		
	}
	/**
	* @depends testcopy
	*/
	public function testremoveFile(){
		$fs = new FileSystem('kjmcmahon', 'testproject/files');
		$basepath = "/home/ken/lampstack/frameworks/laravel/data/users/kjmcmahon/projects/testproject/files/";		

		$this->assertFileExists($basepath."copies/file1copy.txt");
		$this->assertFileExists($basepath."../file2copy.txt");
		$this->assertFileExists($basepath."../folderone/file3copy.txt");
		$this->assertFileExists($basepath."../foldertwo/file4copy.txt");
		$this->assertFileExists($basepath."file5copy.txt");		

		$fs->removeFile("copies/file1copy.txt");
		$fs->removeFile("../file2copy.txt");
		$fs->removeFile("../folderone/file3copy.txt");
		$fs->removeFile("../foldertwo/file4copy.txt");
		$fs->removeFile("file5copy.txt");
		
		$this->assertFileNotExists($basepath."copies/file1copy.txt");
		$this->assertFileNotExists($basepath."copies/file2copy.txt");
		$this->assertFileNotExists($basepath."copies/file3copy.txt");
		$this->assertFileNotExists($basepath."copies/file4copy.txt");
		$this->assertFileNotExists($basepath."copies/file5copy.txt");
		
	}

	
	

}
