<?php

use App\Dealership;
use Illuminate\Database\Seeder;

class DealershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Dealership::class, 20)->create();
    }
}
