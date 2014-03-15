<?php
class DatabaseController extends Controller{
    
    public function QueryTest()
    {
	//Replace function call with any function from Helper.php and pass it
	//a suitable parameter
	$result = Helper::UserExists('greg');
	return $result;
    }    
}
?>