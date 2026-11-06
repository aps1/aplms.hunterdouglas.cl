<?php

namespace App\Console\Commands;

use App\Services\LeadService;
use Illuminate\Console\Command;

class GetLeads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hd:get-leads {correlative?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the leads from the unified db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(LeadService $leadService)
    {
        // para determinar el numero de iniciao en las cargas iniciales
        // sera nulo en el cron job
        $last_correlative = $this->argument('correlative');
        $leadService->syncLastLeadsFromUnifiedDb($last_correlative);
    }
}
