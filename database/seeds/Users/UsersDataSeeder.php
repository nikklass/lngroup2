<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersDataSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        
        $faker = Faker::create();

        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();
        
        $config = config('laratrust_seeder.role_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        //create admin users, perms and roles
        foreach ($config as $key => $modules) {
            
            // Create a new role
            $role = \App\Entities\Role::create([
                'name' => $key,
                'uuid' => Uuid::generate(),
                'display_name' => ucwords(str_replace("_", " ", $key)),
                'description' => ucwords(str_replace("_", " ", $key))
            ]);

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $permission = \App\Entities\Permission::firstOrCreate([
                        'name' => ucfirst($permissionValue) . ' ' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ]);

                    $this->command->info('Creating Permission to '.ucfirst($permissionValue).' for '. $module);
                    
                    if (!$role->hasPermissionTo(ucfirst($permissionValue) . ' ' . $module)) {
                        $role->givePermissionTo(ucfirst($permissionValue) . ' ' . $module);
                    } else {
                        $this->command->info($key . ': ' . $p . '_' . ucfirst($permissionValue) . ' already exist');
                    }
                }
            }

            //current time
            $now = date("Y-m-d H:i:s");

            $this->command->info("Creating '{$key}' user");
            // Create default user for each role
            $user = \App\Entities\User::create([
                
                'first_name' => ucwords(str_replace("_", " ", $key)),
                'uuid' => Uuid::generate(),
                'phone' => "07" . $faker->numberBetween(10,29) . $faker->numberBetween(100000,999999),
                'email' => $key.'@pendo.co.ke',
                'gender' => $faker->randomElement($array = array ('m','f')),
                'phone_country' => 'KE',
                'status_id' => '1',
                'active' => '1',
                'password' => '123456',
                'src_ip' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $now,
                'created_by' => '1',
                'updated_by' => '1'

            ]);

            //dump($now, $user);
            $user->assignRole($role);

        }

        //create other users
        factory(\App\Entities\User::class, 50)->create()->each(function($user) {
            $user->assignRole('user');
        });
        
    }


    public function truncateLaratrustTables()
    {
        $config = config('laravel-permission.table_names');

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table($config['role_has_permissions'])->truncate();
        DB::table($config['user_has_permissions'])->truncate();
        DB::table($config['user_has_roles'])->truncate();
        \App\Entities\User::truncate();
        \App\Entities\Role::truncate();
        \App\Entities\Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }


}
