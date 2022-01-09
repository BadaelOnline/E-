<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($role=1;$role<2;$role++){
            for($permission=1;$permission<32;$permission++) {
                DB::table('permission_role')->insert(
                    [
                        'permission_id'=>$permission,
                        'role_id'=>$role
                    ]
                );
            }
        }
    }
}
