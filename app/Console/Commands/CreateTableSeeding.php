<?php

namespace App\Console\Commands;

use App\Models\Tables;
use Illuminate\Console\Command;

class CreateTableSeeding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $tables = [
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
            ['table_status' => 1],
        ];

        foreach ($tables as $table) {
            $table = Tables::firstOrCreate($table);
            $this->info('Table seeded successfully.');
        }

        $this->info('tables seeding completed.');
    }
}
