<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\PmsSystem;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view_all_hotels',
            'manage_hotels',
            'manage_buildings',
            'manage_floors',
            'manage_room_types',
            'view_rooms',
            'manage_rooms',
            'manage_sunbed_areas',
            'manage_sunbed_types',
            'manage_sunbeds',
            'import_data',
            'view_statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $hotelStaffRole = Role::create(['name' => 'hotel_staff']);

        // Super Admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Hotel Staff gets limited permissions (except view_all_hotels and manage_hotels)
        $hotelStaffPermissions = [
            'manage_buildings',
            'manage_floors',
            'manage_room_types',
            'view_rooms',
            'manage_rooms',
            'manage_sunbed_areas',
            'manage_sunbed_types',
            'manage_sunbeds',
            'import_data',
            'view_statistics',
        ];
        $hotelStaffRole->givePermissionTo($hotelStaffPermissions);

        // Create default PMS system for Apaleo
        PmsSystem::create([
            'name' => 'Apaleo',
            'slug' => 'apaleo',
        ]);

        // Create default Super Admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hotel-data.com',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super_admin');
    }
}
