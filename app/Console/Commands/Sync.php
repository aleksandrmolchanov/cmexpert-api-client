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
    protected $signature = 'sync {--N|numbers} {--S|sales} {page=1} {pages=null}';

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
        $page = $this->argument('page');
        $pages = $this->argument('pages');

        if($this->option('numbers'))
        {
            $importer = new NumbersImporter();
            $importer->import((int) $page, (int) $pages);
        }

        if($this->option('sales'))
        {
            $importer = new SalesImporter();
            $importer->import((int) $page, (int) $pages);
        }
    }
}
