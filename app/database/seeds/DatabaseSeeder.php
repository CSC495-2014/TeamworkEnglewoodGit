<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UsersTableSeeder');
		$this->command->info('user table seeded');	
		
	}

}

class UsersTableSeeder extends Seeder {
	
	public function run()
	{	
		DB::table('users')->delete();
		
		User::create(array(        
			'username' => 'firstuser',
			'useremail' => 'cjwhite@noctrl.edu',
			
		));
		User::create(array(                
			'username' => 'seconduser',
			'useremail' => 'asdfasssa@msn.com',
			
		));  
	}
	
	
	
}