<?php

use App\Enums\ActivityTypes;
use App\Enums\ContactSources;
use App\Enums\ContactTypes;
use App\Enums\DiscardReasons;
use App\Enums\LeadOrigins;
use App\Enums\LeadPriority;
use App\Enums\LeadSources;
use App\Enums\LeadStatus;
use App\Enums\ProductCategory;
use App\Enums\UserTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enums = [
            'activity_types' => ActivityTypes::toOriginalSelectArray(),
            'contact_sources' => ContactSources::toOriginalSelectArray(),
            'contact_types' => ContactTypes::toOriginalSelectArray(),
            'discard_reasons' => DiscardReasons::toOriginalSelectArray(),
            'lead_origins' => LeadOrigins::toOriginalSelectArray(),
            'lead_priorities' => LeadPriority::toOriginalSelectArray(),
            'lead_sources' => LeadSources::toOriginalSelectArray(),
            'lead_status' => LeadStatus::toOriginalSelectArray(),
            'product_categories' => ProductCategory::toOriginalSelectArray(),
            'user_types' => UserTypes::toOriginalSelectArray(),
        ];
        foreach ($enums as $tabla => $data) {
            DB::table($tabla)->truncate();
            foreach ($data as $id => $name) {
                DB::table($tabla)->insert([
                    'name' => $name,
                    'id' => $id,
                ]);
            }
        }
    }
}
