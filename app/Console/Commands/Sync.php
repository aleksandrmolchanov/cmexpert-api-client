<?php

namespace App\Console\Commands;

use App\Services\NumbersImporter;
use App\Services\SalesImporter;
use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync {--N|numbers} {--S|sales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all required entities';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if($this->option('numbers'))
        {
            $importer = new NumbersImporter();
            $importer->import();
        }

        if($this->option('sales'))
        {
            $importer = new SalesImporter();
            $importer->import();
        }
    }
}
