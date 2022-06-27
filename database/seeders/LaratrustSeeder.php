<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return false
     */
    public function run()
    {
        $this->truncateLaratrustTables();
        $faker = Faker::create();

        $config = Config::get('laratrust_seeder.roles_structure');

        if ($config === null) {
            $this->command->error("The configuration has not been published. Did you run `php artisan vendor:publish --tag=\"laratrust-seeder\"`");
            $this->command->line('');
            return false;
        }

        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Models\Admin\Role::firstOrCreate([
                'is_active' => rand(0, 1),
                'slug' => ucwords(str_replace('_', ' ', $key)),
            ]);
            $roleid = $role->id;
            $roleTrans = DB::table('role_translation')->insert([
                [
                    'name' => $key,
                    'display_name' => ucwords(str_replace('_', ' ', $key)),
                    'description' => ucwords(str_replace('_', ' ', $key)),
                    'local' => 'ar',
                    'role_id' => $roleid
                ],
                [
                    'name' => $key,
                    'display_name' => ucwords(str_replace('-', ' ', $key)),
                    'description' => ucwords(str_replace('-', ' ', $key)),
                    'local' => 'en',
                    'role_id' => $roleid
                ]]);
            $permissions = [];

            $this->command->info('Creating Role ' . strtoupper($key));
            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);
                    $permissions = \App\Models\Admin\Permission::firstOrCreate([
                        'is_active' => rand(0, 1),
                        'slug' => ucfirst($permissionValue) . ' ' . ucfirst($module)
                    ])->id;
                    $permissionsTrans = DB::table('permission_translation')->insert([
                        [
                            'name' => $module . '-' . $permissionValue,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'local' => 'ar',
                            'permission_id' => $permissions
                        ],
                        [
                            'name' => $module . '_' . $permissionValue,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'local' => 'en',
                            'permission_id' => $permissions
                        ]
                    ]);

                    $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                }
            }
            // Attach all permissions to the role
            $role->Permission()->sync($permissions);

            if (Config::get('laratrust_seeder.create_users')) {
                $this->command->info("Creating '{$key}' user");
                // Create default user for each role
                $user = \App\Models\User::firstOrCreate([
                    'first_name' => ucwords(str_replace('_', ' ', $key)),
                    'last_name' => ucwords(str_replace('_', ' ', $key)),
                    'username' => ucwords(str_replace('_', ' ', $key)),
                    'age' => rand(20, 50),
                    'location_id' => rand(1, 5),
                    'social_media_id' => rand(1, 5),
                    'is_active' => 1,
                    'image' => $faker->imageUrl(),
                    'email' => $key . '@app.com',
                    'password' => bcrypt('password')
                ]);
                $userid = $user->id;
                $user->roles()->syncWithoutDetaching($role);
            }
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return  void
     */
    public function truncateLaratrustTables()
    {
        $this->command->info('Truncating User,Employee Role and Permission tables');
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();
        DB::table('role_type')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables')) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users')) {
                $usersTable = (new \App\Models\User)->getTable();
                $usersTransTable = (new \App\Models\Admin\TransModel\UserTranslation)->getTable();
                DB::table($usersTable)->truncate();
                DB::table($usersTransTable)->truncate();
            }
        }
        Schema::disableForeignKeyConstraints();
    }
}
