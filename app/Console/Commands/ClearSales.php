<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clearSales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear sales of two previous months';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $currentMonth = now()->format('m') . '.' . now()->format('Y');
        $previousMonth = now()->subMonth()->format('m') . '.' . now()->subMonth()->format('Y');

        DB::table('stock_sales')
            ->where('Дата завершения', 'like', '%.' . $currentMonth)
            ->orWhere('Дата завершения', 'like', '%.' . $previousMonth)
            ->delete();
    }
}
