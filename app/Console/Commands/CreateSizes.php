<?php

namespace App\Console\Commands;

use App\Models\Sizes;
use Illuminate\Console\Command;

class CreateSizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'size:create';

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
        $sizes = ['S', 'M', 'L','U'];

        foreach ($sizes as $sizeName) {
            $size = Sizes::firstOrCreate(['size_name' => $sizeName]);
            $this->info('Size ' . $size->size_name . ' seeded successfully.');
        }

        $this->info('Sizes seeding completed.');
    }
}
