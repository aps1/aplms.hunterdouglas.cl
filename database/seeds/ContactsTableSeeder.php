<?php

use App\Contact;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            factory(Contact::class)->create([
                'dealership_id' => rand(1, 20),
            ]);
        }
    }
}
