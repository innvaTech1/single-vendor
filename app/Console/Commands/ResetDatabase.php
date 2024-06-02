<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabase extends Command
{
    protected $signature = 'db:reset';
    protected $description = 'Drops all tables in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Schema::disableForeignKeyConstraints();
        $tables = DB::select('SHOW TABLES');
        $dbName = 'Tables_in_' . DB::getDatabaseName();
        foreach ($tables as $table) {
            $tableName = $table->$dbName;
            DB::table($tableName)->truncate();
        }
        Artisan::call('db:seed');
        Schema::enableForeignKeyConstraints();
        $this->info('All tables truncated successfully.');
    }
}
