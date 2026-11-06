<?php

use App\Dealership;
use App\Enums\UserTypes;
use App\User;
use Illuminate\Database\Seeder;

class RealDealershipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // MURINTER
        $murinter = Dealership::create([
            'identifier' => 1239,
            'name' => 'MURINTER',
            'region' => 'Región Metropolitana',
            'city' => 'Santiago',
            'commune' => 'Lo Bernachea',
            'address' => 'JOSE ALCALDE DELANO 10682, LOCAL 2',
            'phone' => '222467300',
        ]);
        User::create([
            'name' => 'Patricia Lopez',
            'email' => 'patricia@murinter.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $murinter->id,
        ]);
        User::create([
            'name' => 'Jose Luis Irarrazaval',
            'email' => 'jirarrazaval@murinter.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $murinter->id,
        ]);
        User::create([
            'name' => 'Paulo Forno',
            'email' => 'pforno@murinter.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $murinter->id,
        ]);

        // DECCO
        $decco = Dealership::create([
            'identifier' => 2248,
            'name' => 'Decco',
            'region' => 'Región Metropolitana',
            'city' => 'Santiago',
            'commune' => 'Santiago',
            'address' => 'APOQUINDO 4831',
            'phone' => '222064712',
        ]);
        User::create([
            'name' => 'PAULA MIRANDA',
            'email' => 'pmddecco@gmail.com',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $decco->id,
        ]);
        User::create([
            'name' => 'MARIA DE LOS ANGELES PARDO',
            'email' => 'mpardo@d-decco.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $decco->id,
        ]);
        User::create([
            'name' => 'LORETO DELPIANO',
            'email' => 'ld.d.decco@gmail.com',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $decco->id,
        ]);

        // AO
        $ao = Dealership::create([
            'identifier' => 1866,
            'name' => 'AO',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'Presidente Kennedy 5600 of 1101',
            'phone' => '27524509',
        ]);
        User::create([
            'name' => 'Cheryl Amaya Opazo',
            'email' => 'camaya@auroraopazo.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $ao->id,
        ]);
        User::create([
            'name' => 'Juan Ignacio Amaya',
            'email' => 'jiamaya@auroraopazo.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $ao->id,
        ]);
        User::create([
            'name' => 'AURORA OPAZO',
            'email' => 'aopazo@auroraopazo.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $ao->id,
        ]);

        // MARCIAL MP
        $mmp = Dealership::create([
            'identifier' => 1186,
            'name' => 'MARCIAL MIRANDA PERSIANAS',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'AV. LA DEHESA 3372',
            'phone' => '29555940',
        ]);
        User::create([
            'name' => 'Cristian Velasquez',
            'email' => 'ventas4@persianasvitacura.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $mmp->id,
        ]);
        User::create([
            'name' => 'Gloria Miranda',
            'email' => 'hmms@persianasvitacura.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $mmp->id,
        ]);
        User::create([
            'name' => 'Yilian Fernandez',
            'email' => 'ventas1@persianasvitacura.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $mmp->id,
        ]);
        User::create([
            'name' => 'Carlos Cornejo',
            'email' => 'ventas5@persianasvitacura.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $mmp->id,
        ]);
        User::create([
            'name' => 'MARCIAL MIRANDA',
            'email' => 'mmiranda@persianasvitacura.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $mmp->id,
        ]);

        // BASTO
        $basto = Dealership::create([
            'identifier' => 3543,
            'name' => 'BASTO',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'CONDOMINIO LOS ALGARROBOS 34',
            'phone' => '94334789',
        ]);
        User::create([
            'name' => 'FRANCISCA TORO',
            'email' => 'francisca@basto.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $basto->id,
        ]);

        // COLDECOR
        $coldecor = Dealership::create([
            'identifier' => 1116,
            'name' => 'COLDECOR',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'IV CENTENARIO 343',
            'phone' => '56 2 2906 4168',
        ]);
        User::create([
            'name' => 'MARIA JOSE AGUIRRE',
            'email' => 'MJAGUIRRE@COLDECOR.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $coldecor->id,
        ]);
        User::create([
            'name' => 'ANA ROMERO',
            'email' => 'AROMERO@COLDECOR.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $coldecor->id,
        ]);
        User::create([
            'name' => 'MARIA ELENA ROMERO',
            'email' => 'MEROMERO@COLDECOR.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $coldecor->id,
        ]);
        User::create([
            'name' => 'MONICA PEREZ',
            'email' => 'MPEREZ@COLDECOR.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $coldecor->id,
        ]);
        User::create([
            'name' => 'ALFONSO AGUIRRE',
            'email' => 'AAGUIRRE@COLDECOR.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $coldecor->id,
        ]);

        // PYC
        $pyc = Dealership::create([
            'identifier' => 69,
            'name' => 'PYC',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'LA DEHESA 457, LOCAL 12',
            'phone' => '2216 32 87',
        ]);
        User::create([
            'name' => 'MARIA VICTORIA RODRIGUEZ C.',
            'email' => 'pyc@tie.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $pyc->id,
        ]);
        User::create([
            'name' => 'ARTURO RODRIGUEZ',
            'email' => 'PYCDECORACION@TIE.CL',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $pyc->id,
        ]);

        // IKEN
        $iken = Dealership::create([
            'identifier' => 48,
            'name' => 'IKEN',
            'region' => 'Región Metropolitana',
            'city' => 'Santiago',
            'commune' => 'Puente Alto',
            'address' => 'EYZAGUIRRE 92',
            'phone' => '229049427',
        ]);
        User::create([
            'name' => 'Viery Silva Cifuentes',
            'email' => 'viery.silva@gmail.com',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Seller,
            'dealership_id' => $iken->id,
        ]);
        User::create([
            'name' => 'Jaime Cifuentes',
            'email' => 'jaime.cifuentes@iken.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $iken->id,
        ]);

        // Decoformas
        $decoformas = Dealership::create([
            'identifier' => 326,
            'name' => 'DOCOFORMAS',
            'region' => 'Región Metropolitana',
            'city' => 'Santiago',
            'commune' => 'Buin',
            'address' => 'VICTOR VERA LETELIER 2637',
            'phone' => '998647813',
        ]);
        User::create([
            'name' => 'Pamela Contreras',
            'email' => 'pamela@decoformas.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $decoformas->id,
        ]);

        // imaggen
        $imaggen = Dealership::create([
            'identifier' => 50,
            'name' => 'IMAGGEN SUR',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'address' => 'ALCANTARA 970 D. 45',
            'phone' => '956430578',
        ]);
        User::create([
            'name' => 'MAY CORBALAN',
            'email' => 'ELRAULLOBO@GMAIL.COM',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $imaggen->id,
        ]);

        // narvik
        $narvik = Dealership::create([
            'identifier' => 70,
            'name' => 'Narvik',
            'region' => 'Región Metropolitana',
            'commune' => 'Vitacura',
            'address' => 'AAUGUSTO VILLANUEVA 20 OF. 2206',
            'phone' => '92976807',
        ]);
        User::create([
            'name' => 'Verónica Carja',
            'email' => 'veronica@narvik.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $narvik->id,
        ]);
        User::create([
            'name' => 'Pablo Toledo',
            'email' => 'pablo@narvik.cl',
            'password' => bcrypt('vkH5duRT34'),
            'type' => UserTypes::Owner,
            'dealership_id' => $narvik->id,
        ]);
    }
}
