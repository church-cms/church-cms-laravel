<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $subadmin = Role::firstOrCreate(
            ['name' => 'church-subadmin'],
            ['display_name' => 'Church Sub-Admin', 'description' => 'Full church management except financial admin']
        );

        $subadmin->syncPermissions(
            Permission::whereIn('name', [
                'create-members', 'read-members', 'update-members',
                'create-events', 'read-events', 'update-events',
                'read-attendance', 'create-attendance', 'update-attendance',
                'create-files', 'read-files', 'view-files',
                'create-bulletins', 'read-bulletins', 'view-bulletins', 'update-bulletins', 'delete-bulletins',
                'create-gallery', 'read-gallery', 'update-gallery',
                'create-groups', 'read-groups', 'update-groups', 'delete-groups',
                'create-sermons', 'read-sermons', 'update-sermons',
                'create-preachers', 'read-preachers', 'update-preachers',
                'read-prayers', 'update-prayers',
                'create-quotes', 'read-quotes', 'update-quotes',
                'read-funds', 'view-funds',
                'read-reports', 'view-reports',
                'read-helps', 'update-helps',
                'read-contacts',
                'read-feedbacks', 'update-feedbacks',
                'manage-cms', 'manage-email-blaster',
            ])->get()
        );

        $staff = Role::firstOrCreate(
            ['name' => 'staff'],
            ['display_name' => 'Staff', 'description' => 'Day-to-day operational tasks']
        );

        $staff->syncPermissions(
            Permission::whereIn('name', [
                'read-members',
                'read-events', 'create-events',
                'read-attendance', 'create-attendance',
                'read-files', 'create-files', 'view-files',
                'read-bulletins', 'view-bulletins',
                'read-gallery', 'create-gallery',
                'read-groups',
                'read-sermons',
                'read-prayers',
                'read-quotes',
                'read-reports',
                'read-helps', 'read-contacts',
            ])->get()
        );
    }
}
