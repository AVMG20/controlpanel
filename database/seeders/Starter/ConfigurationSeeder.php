<?php

namespace Database\Seeders\Starter;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Starter configuration
        Configuration::query()->create([
            'name' => 'Starter',
            'description' => '<ul>
                                    <li>1 vCPU</li>
                                    <li>128MB RAM</li>
                                    <li>1GB Disk</li>
                                    <li>1 database</li>
                                    <li>1 backup</li>
                                </ul>',
            'price' => '120',
            'setup_price' => '0',
            'minimum_required_credits' => '0',
            'memory' => '128',
            'cpu' => '100',
            'swap' => '32',
            'disk' => '1024',
            'io' => '500',
            'databases' => '1',
            'backups' => '1',
            'allocations' => '0',
            'disabled' => '1',
        ]);

        //Advanced configuration
        Configuration::query()->create([
            'name' => 'Advanced',
            'description' => '<ul>
                                    <li>2 vCPU</li>
                                    <li>256mb RAM</li>
                                    <li>5GB Disk</li>
                                    <li>5 databases</li>
                                    <li>3 backups</li>
                                    <li>3 allocations</li>
                                </ul>',
            'price' => '210',
            'setup_price' => '10',
            'minimum_required_credits' => '0',
            'memory' => '256',
            'cpu' => '200',
            'swap' => '64',
            'disk' => '5120',
            'io' => '500',
            'databases' => '5',
            'backups' => '3',
            'allocations' => '3',
            'disabled' => '1',
        ]);
    }
}
