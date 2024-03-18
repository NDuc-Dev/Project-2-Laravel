<?php

namespace App\Console\Commands;

use App\Models\Users;
use Illuminate\Console\Command;

class CreateSampleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

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
        Users::create([
            'name' => 'Admin',
            'user_name' => 'admin',
            'password' => bcrypt('123456789'),
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'status' => 1,
        ]);

        Users::create([
            'name' => 'Seller',
            'user_name' => 'seller',
            'password' => bcrypt('123456789'),
            'role' => 'seller',
            'email' => 'saler@gmail.com',
            'phone' => '0213456789',
            'status' => 1,
        ]);

        Users::create([
            'name' => 'Bartender',
            'user_name' => 'bartender',
            'password' => bcrypt('123456789'),
            'role' => 'bartender',
            'email' => 'bartender@gmail.com',
            'phone' => '0312456789',
            'status' => 1,
        ]);

        $this->info('Sample users created successfully.');
    }
}
