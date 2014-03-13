<?php

class DatabaseQueries{
	/**
	* Insert a user into the Users table
	* 
	* @param string $param1
	* @param string $param2
	* @returns user_id
	*/
	public static function InsertUser($param1, $param2)
    	{
        	$data = DB::table('users')->insertGetId(
        	array('username' => $param1, 'useremail' => $param2));       
   
        	return $data;
    	}
	
	/**
	* Delete a user from the Users table
	* 
	* @param string $userName
	*/
	public static function DeleteUser($userName) 
	{
        	$data = DB::table('users')->where('username', $userName)->delete();
        	return $data;
    	}
	
	/**
	* Fetch the specified user's ID
	* 
	* @param string $userName
	* @returns user_id
	*/
	public static function GetUserId($userName) 
	{
        	$data = DB::table('users')->where('username', $userName)->pluck('user_id');
        	return $data;
	}
	
	/**
	* Fetch the specified user's email
	* 
	* @param string $userName
	* @returns useremail
	*/
	public static function GetUserEmail($userName) 
	{
        	$data = DB::table('users')->where('username', $userName)->pluck('useremail');
        	return $data;
    	}	
}
