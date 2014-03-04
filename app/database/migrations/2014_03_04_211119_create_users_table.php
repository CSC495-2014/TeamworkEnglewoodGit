<?php
 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
                Schema::rename('user', 'users');
                
		Schema::table('users', function(Blueprint $table)
		{                        
                        $table->dropColumn('oauth');
                        $table->dropColumn('active');
                        $table->unique('username', 'username_unique');
			$table->string('useremail');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{               
		Schema::table('users', function(Blueprint $table)
		{
                        $table->dropUnique('username_unique');
			$table->dropColumn('useremail');
                        $table->string('oauth');
                        $table->boolean('active')->default(0);                        
		});
                
                Schema::rename('users', 'user');
	}
}
