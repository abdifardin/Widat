<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('users')->insert([
			'email' => 'milad@zeyton.com',
			'name' => 'Milad',
			'surname' => 'Nowzari',
			'user_type' => 'admin',
			'password' => bcrypt('milad1990'),
		]);
    }
}
