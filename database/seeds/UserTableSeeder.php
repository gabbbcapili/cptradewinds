<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'last_name' => 'ONE',
            'email' => 'gabbbcapili@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin1'
        ]);
        User::create([
            'name' => 'Admin',
            'last_name' => '2A',
            'email' => 'admin2a@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin2a'
        ]);
        User::create([
            'name' => 'Admin',
            'last_name' => '2B',
            'email' => 'admin2b@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin2b'
        ]);
        User::create([
            'name' => 'Admin',
            'last_name' => 'THREE',
            'email' => 'admin3@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin3'
        ]);
        User::create([
            'name' => 'Supplier',
            'last_name' => 'Name',
            'email' => 'supplier@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'supplier'
        ]);
        User::create([
            'name' => 'Buyer',
            'last_name' => 'Name',
            'email' => 'buyer@gmail.com',
            'phone_no' => '09993586492',
            'password' => Hash::make('admin123'),
            'role' => 'customer'
        ]);
    }
}
