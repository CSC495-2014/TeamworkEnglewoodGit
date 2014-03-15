<?php

class Helper
{
	//Adds a user to the database
	public static function InsertUser($param1, $param2)
	{
		$data = DB::table('users')->insertGetId(
		array('username' => $param1, 'useremail' => $param2));       
		
		return $data;
	}
	
	 //Deletes a user from the database
	 public static function DeleteUsers($userName) {
	    
		$data = DB::table('users')->where('username', $userName)->delete();
		return $data;
	 }
	
	 //Gets the user's ID number to see if they are in the database
	 public static function GetUserId($userName) {
	    
		$data = DB::table('users')->where('username', $userName)->pluck('user_id');
		return $data;
	 }
	 
	 //Gets the user's email. Is required for authentication.
	 public static function GetUserEmail($userName) {
	    
		$data = DB::table('users')->where('username', $userName)->pluck('useremail');
		return $data;
	 }
	 
	 //Is the same as GetUserId
	 //Should only have to use GetUserId.  Left this here just in case.
	 public static function UserExists($userName) {
	
		$data = DB::table('users')->where('username', $userName)->pluck('user_id');
		return $data;
	 }
}
