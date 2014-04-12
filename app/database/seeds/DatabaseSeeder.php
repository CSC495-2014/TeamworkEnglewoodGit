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

		// $this->call('UserTableSeeder');
	}

}

class UserTableSeeder extends Seeder {

	public function run()
	{
		User::create(array('user_id' => '1',
							'username' => 'Zach Mance',
							'useremail' => 'zmance@noctrl.edu'));
	}
}
