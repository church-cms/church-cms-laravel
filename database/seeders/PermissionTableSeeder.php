<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Members
            ['name' => 'create-members',        'display_name' => 'Create Members',         'description' => 'Add new members'],
            ['name' => 'read-members',           'display_name' => 'Read Members',           'description' => 'View member list and profiles'],
            ['name' => 'update-members',         'display_name' => 'Update Members',         'description' => 'Edit member profiles and details'],

            // Events
            ['name' => 'create-events',          'display_name' => 'Create Events',          'description' => 'Create and manage events'],
            ['name' => 'read-events',            'display_name' => 'Read Events',            'description' => 'View events list'],
            ['name' => 'update-events',          'display_name' => 'Update Events',          'description' => 'Edit and update events'],

            // Attendance
            ['name' => 'read-attendance',        'display_name' => 'Read Attendance',        'description' => 'View attendance sessions and reports'],
            ['name' => 'create-attendance',      'display_name' => 'Create Attendance',      'description' => 'Open attendance sessions and scan member QR codes'],
            ['name' => 'update-attendance',      'display_name' => 'Update Attendance',      'description' => 'Lock/unlock sessions and assign staff to events'],

            // Files / Media
            ['name' => 'create-files',           'display_name' => 'Create Files',           'description' => 'Upload files and videos'],
            ['name' => 'read-files',             'display_name' => 'Read Files',             'description' => 'View file list'],
            ['name' => 'view-files',             'display_name' => 'View Files',             'description' => 'Download and view files'],

            // Bulletins
            ['name' => 'create-bulletins',       'display_name' => 'Create Bulletins',       'description' => 'Create and edit bulletins'],
            ['name' => 'read-bulletins',         'display_name' => 'Read Bulletins',         'description' => 'View bulletins list'],
            ['name' => 'view-bulletins',         'display_name' => 'View Bulletins',         'description' => 'Download bulletin attachments'],

            // Gallery
            ['name' => 'create-gallery',         'display_name' => 'Create Gallery',         'description' => 'Create new galleries'],
            ['name' => 'read-gallery',           'display_name' => 'Read Gallery',           'description' => 'View gallery list'],
            ['name' => 'update-gallery',         'display_name' => 'Update Gallery',         'description' => 'Edit galleries and upload photos'],

            // Groups
            ['name' => 'create-groups',          'display_name' => 'Create Groups',          'description' => 'Create new groups'],
            ['name' => 'read-groups',            'display_name' => 'Read Groups',            'description' => 'View groups list'],
            ['name' => 'update-groups',          'display_name' => 'Update Groups',          'description' => 'Edit groups and manage members'],
            ['name' => 'delete-groups',          'display_name' => 'Delete Groups',          'description' => 'Delete groups'],

            // Sermons
            ['name' => 'create-sermons',         'display_name' => 'Create Sermons',         'description' => 'Add new sermons'],
            ['name' => 'read-sermons',           'display_name' => 'Read Sermons',           'description' => 'View sermons list'],
            ['name' => 'update-sermons',         'display_name' => 'Update Sermons',         'description' => 'Edit sermons'],
            ['name' => 'delete-sermons',         'display_name' => 'Delete Sermons',         'description' => 'Delete sermons'],

            // Preachers
            ['name' => 'create-preachers',       'display_name' => 'Create Preachers',       'description' => 'Add new preachers'],
            ['name' => 'read-preachers',         'display_name' => 'Read Preachers',         'description' => 'View preachers list'],
            ['name' => 'update-preachers',       'display_name' => 'Update Preachers',       'description' => 'Edit preachers'],

            // Prayers
            ['name' => 'read-prayers',           'display_name' => 'Read Prayers',           'description' => 'View prayer board'],
            ['name' => 'update-prayers',         'display_name' => 'Update Prayers',         'description' => 'Moderate prayers (approve, reject, pin, mark answered)'],

            // Quotes
            ['name' => 'create-quotes',          'display_name' => 'Create Quotes',          'description' => 'Add new quotes'],
            ['name' => 'read-quotes',            'display_name' => 'Read Quotes',            'description' => 'View quotes list'],
            ['name' => 'update-quotes',          'display_name' => 'Update Quotes',          'description' => 'Edit quotes'],

            // Funds / Finance
            ['name' => 'create-funds',           'display_name' => 'Create Funds',           'description' => 'Record new fund transactions'],
            ['name' => 'read-funds',             'display_name' => 'Read Funds',             'description' => 'View funds list'],
            ['name' => 'update-funds',           'display_name' => 'Update Funds',           'description' => 'Edit fund records'],
            ['name' => 'view-funds',             'display_name' => 'View Funds',             'description' => 'View fund transaction details'],
            ['name' => 'read-payments',          'display_name' => 'Read Payments',          'description' => 'View payment records'],
            ['name' => 'create-payments',        'display_name' => 'Create Payments',        'description' => 'Process payments'],

            // Reports
            ['name' => 'read-reports',           'display_name' => 'Read Reports',           'description' => 'View reports dashboard'],
            ['name' => 'view-reports',           'display_name' => 'View Reports',           'description' => 'Export and download reports'],

            // Help & Feedback & Contacts
            ['name' => 'read-helps',             'display_name' => 'Read Help Requests',     'description' => 'View help requests list and details'],
            ['name' => 'update-helps',           'display_name' => 'Update Help Requests',   'description' => 'Respond to and update help requests'],
            ['name' => 'read-contacts',          'display_name' => 'Read Contacts',          'description' => 'View contact form submissions'],
            ['name' => 'read-feedbacks',         'display_name' => 'Read Feedbacks',         'description' => 'View feedback submissions'],
            ['name' => 'update-feedbacks',       'display_name' => 'Update Feedbacks',       'description' => 'Update feedback status'],

            // CMS & Email Blaster (module-level)
            ['name' => 'manage-cms',             'display_name' => 'Manage CMS',             'description' => 'Full access to pages, posts, FAQ, code snippets, and Google Analytics'],
            ['name' => 'manage-email-blaster',   'display_name' => 'Manage Email Blaster',   'description' => 'Full access to campaigns, emails, mailing lists, subscribers, SMTP, queues, rules, and webhooks'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore($permission);
        }
    }
}
