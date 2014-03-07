<?php

class DatabaseQueries extends Eloquent{
	
	//Insert a user into the Users table
	public function insertUsers(string $str1, string $str2) 
	{		
	
		return DB::table('users')->insertGetId(
		array('username' => '$str1', 'useremail' => '$str2')
		);
	}
	
	//Delete a user from the Users table
	public function deleteUsers(string $str1) {
		
		DB::table('users')->where('username', '$str1')->delete();
	}
	
	//Fetch the specified user's ID
	public function getUserId(string $str1) {
		
		return DB::table('users')->where('username', '$str1')->pluck('user_id');
	}
	
	//Fetch the specified user's email
	public function getUserEmail(string $str1) {
		
		return DB::table('users')->where('username', '$str1')->pluck('useremail');
	}	
	
	//Find out if a user exits in the Users table
	public function userExists(string $str1) {
		
		DB::table('users')->whereExists(function($query)
		{
			$query->select('user_id')
			->from('users')
			->whereRaw('username = $str1');
		})
		->get();
	}
}

