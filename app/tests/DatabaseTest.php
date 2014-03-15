<?php

class DatabaseTest extends TestCase {

    public function testQueries()
    {
	//$this->Helper:GetResult('thirduser','alasdf@msn.com');
	$response = $this->action('GET', 'testName');
    }
}
?>