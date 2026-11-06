<?php

use App\WebClient;
use Illuminate\Database\Seeder;

class WebClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WebClient::create(['name' => 'cliengo', 'token' => 'bcbbea25-604e-41c2-87c9-193685d72856']);
    }
}
