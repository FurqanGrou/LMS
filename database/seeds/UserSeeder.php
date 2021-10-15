<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 50; $i++){
            User::create([
                'name' => 'مسباح ' . $i,
                'email' => 'user@user.com' . $i,
                'password' => bcrypt('12345678'),
            ]);
        }
    }
}
