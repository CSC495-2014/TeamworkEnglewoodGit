<?php

class DatabaseQueries{
	/**
	* Insert a user into the Users table
	* 
	* @param string $userName
	* @param string $email
	* @returns user_id
	*/
	public function insertUsers(string $userName, string $email) 
	{		
		return DB::table('users')->insertGetId(
		array('username' => '$userName', 'useremail' => '$email')
		);
	}
	
	/**
	* Delete a user from the Users table
	* 
	* @param string $userName
	*/
	public function deleteUsers(string $userName) {
		
		DB::table('users')->where('username', '$userName')->delete();
	}
	
	/**
	* Fetch the specified user's ID
	* 
	* @param string $userName
	* @returns user_id
	*/
	public function getUserId(string $userName) {
		
		return DB::table('users')->where('username', '$userName')->pluck('user_id');
	}
	
	/**
	* Fetch the specified user's email
	* 
	* @param string $userName
	* @returns useremail
	*/
	public function getUserEmail(string $userName) {
		
		return DB::table('users')->where('username', '$userName')->pluck('useremail');
	}	
}
