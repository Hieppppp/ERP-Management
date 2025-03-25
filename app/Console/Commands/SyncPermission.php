<?php

namespace App\Console\Commands;

use App\Enums\Permission as EnumsPermission;
use App\Models\Permission;
use Illuminate\Console\Command;

class SyncPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-permission';

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
        $allPermission = Permission::get(['code'])->pluck('code')->toArray();
        $enumPermission = EnumsPermission::getValues();
        $permissions = array_map(function ($permission) {
            return ['code' => $permission];
        }, array_diff($enumPermission, $allPermission));
        Permission::insert($permissions);
        $this->info('Sync Success');
    }
}
