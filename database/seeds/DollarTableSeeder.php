<?php

use Illuminate\Database\Seeder;
use App\Dollar;
class DollarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dollar::create(['rate' => '50.00']);
    }
}
