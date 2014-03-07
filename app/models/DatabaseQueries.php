<?php

class DatabaseQueries extends Eloquent{
	
	public function insertUsers(string $str1, string $str2) 
	{		
	
		return DB::table('users')->insertGetId(
		array('username' => '$str1', 'useremail' => '$str2')
		);
	}
	
	public function deleteUsers(string $str1) {
		
		DB::table('users')->where('username', '$str1')->delete();
	}
	
	public function getUserId(string $str1) {
		
		return DB::table('users')->where('username', '$str1')->pluck('user_id');
	}
	
	public function getUserEmail(string $str1) {
		
		return DB::table('users')->where('username', '$str1')->pluck('useremail');
	}	
	
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

