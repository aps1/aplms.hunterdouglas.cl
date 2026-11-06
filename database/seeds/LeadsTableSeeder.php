<?php

use App\Enums\LeadStatus;
use App\Lead;
use Illuminate\Database\Seeder;

class LeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leads = factory(Lead::class, 50)->create();
        $leads->each(function ($lead) {
            // Los creados no estan asignados, cuando se asignan, manual o automaticamente estan asignados
            if ($lead->status == LeadStatus::Creado) {
                $lead->update(['dealership_id' => null]);
            }
        });

        // crea leads de prueba para el dealership 1, con status creado
        factory(Lead::class, 5)->create([
            'dealership_id' => 1,
            'status' => 1,
        ]);
        // crea leads de prueba para el dealership 3, con status derivado
        factory(Lead::class, 3)->create([
            'dealership_id' => 1,
            'status' => 2,
        ]);
    }
}
