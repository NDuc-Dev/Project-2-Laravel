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
            ['table_id' => 1, 'table_status' => 1],
            ['table_id' => 2, 'table_status' => 1],
            ['table_id' => 3, 'table_status' => 1],
            ['table_id' => 4, 'table_status' => 1],
            ['table_id' => 5, 'table_status' => 1],
            ['table_id' => 6, 'table_status' => 1],
            ['table_id' => 7, 'table_status' => 1],
            ['table_id' => 8, 'table_status' => 1],
            ['table_id' => 9, 'table_status' => 1],
            ['table_id' => 10, 'table_status' => 1],
            ['table_id' => 11, 'table_status' => 1],
            ['table_id' => 12, 'table_status' => 1],
            ['table_id' => 13, 'table_status' => 1],
            ['table_id' => 14, 'table_status' => 1],
            ['table_id' => 15, 'table_status' => 1],
            ['table_id' => 16, 'table_status' => 1],
            ['table_id' => 17, 'table_status' => 1],
            ['table_id' => 18, 'table_status' => 1],
            ['table_id' => 19, 'table_status' => 1],
            ['table_id' => 20, 'table_status' => 1],
            ['table_id' => 21, 'table_status' => 1],
            ['table_id' => 22, 'table_status' => 1],
            ['table_id' => 23, 'table_status' => 1],
            ['table_id' => 24, 'table_status' => 1],
            ['table_id' => 25, 'table_status' => 1],
            ['table_id' => 26, 'table_status' => 1],
            ['table_id' => 27, 'table_status' => 1],
            ['table_id' => 28, 'table_status' => 1],
            ['table_id' => 29, 'table_status' => 1],
            ['table_id' => 30, 'table_status' => 1],
            ['table_id' => 31, 'table_status' => 1],
            ['table_id' => 32, 'table_status' => 1],
            ['table_id' => 33, 'table_status' => 1],
            ['table_id' => 34, 'table_status' => 1],
            ['table_id' => 35, 'table_status' => 1],
            ['table_id' => 36, 'table_status' => 1],
            ['table_id' => 37, 'table_status' => 1],
            ['table_id' => 38, 'table_status' => 1],
            ['table_id' => 39, 'table_status' => 1],
            ['table_id' => 40, 'table_status' => 1],

        ];

        foreach ($tables as $table) {
            $table = Tables::firstOrCreate($table);
            $this->info('Table seeded successfully.');
        }

        $this->info('tables seeding completed.');
    }
}
