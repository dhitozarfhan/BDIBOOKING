<?php

namespace App\Console\Commands;

use App\Enums\RoleDefault;
use App\Models\Employee;
use Exception;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:admin {--username=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create first role and first roster';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            if (!Role::count()) {
                $this->info('Import default role init');
                return;
            }

            if (!Permission::count()) {
                $this->info('Import permission first');
                return;
            }
            
            $employee = Employee::where('username', $this->option('username'))->first();

            if ($employee) {

                $roleRoster = Role::where('name', RoleDefault::Roster->value)->first();

                $employee->assignRole([$roleRoster->id]);

                $this->info('Initial admin success');

            } else {

                $this->info('Initial process failed because account does not exist');
            }

        } catch (Exception $e) {
            
            var_dump($e->getMessage());
        }
    }
}
