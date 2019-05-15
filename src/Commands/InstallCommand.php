<?php

namespace Admin\Commands;

/*
* 
* name InstallCommand.php
* author Yuanchang
* date ${DATA}
*/

use Admin\Seeds\AdminSeeder;
use Admin\Seeds\PermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Database\Seeder;

class InstallCommand extends Command
{
    protected $name = 'ladmin:install';

    protected $description = 'Install the ladmin package';

    public function handle()
    {
        $this->publishResource();

        $this->initDatabase();

        $this->line('<info>installing ladmin success!</info>');

    }

    public function publishResource()
    {
        $this->call('vendor:publish');
    }

    public function initDatabase()
    {
        $this->call('migrate');
        $this->runSeed();

    }

    public function runSeed()
    {
        $seeders = [
            new AdminSeeder(),
            new PermissionSeeder(),
        ];

        foreach ($seeders as $seeder) {
            $seeder->run();
        }
    }
}