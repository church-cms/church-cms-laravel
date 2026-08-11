<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyPrayerCategorySeeder extends Seeder
{
    public function run()
    {
        $churchId = DB::table('church')->value('id') ?? 1;

        // Default categories
        $categories = [
            [
                'name'           => 'Health',
                'css_class'      => 'category-health',
                'emoji'          => '🏥',
                'display_color'  => '#EF4444',
                'gradient_start' => '#FEE2E2',
                'gradient_end'   => '#FCE7F3',
                'sort_order'     => 1,
                'is_active'      => true,
                'description'    => 'Physical and mental health concerns',
            ],
            [
                'name'           => 'Family',
                'css_class'      => 'category-family',
                'emoji'          => '👨‍👩‍👧',
                'display_color'  => '#8B5CF6',
                'gradient_start' => '#EDE9FE',
                'gradient_end'   => '#F3E8FF',
                'sort_order'     => 2,
                'is_active'      => true,
                'description'    => 'Marriage, parenting, and family relationships',
            ],
            [
                'name'           => 'Employment',
                'css_class'      => 'category-employment',
                'emoji'          => '💼',
                'display_color'  => '#F59E0B',
                'gradient_start' => '#FEF3C7',
                'gradient_end'   => '#FDE68A',
                'sort_order'     => 3,
                'is_active'      => true,
                'description'    => 'Work, career, and livelihood needs',
            ],
            [
                'name'           => 'Financial',
                'css_class'      => 'category-financial',
                'emoji'          => '💰',
                'display_color'  => '#10B981',
                'gradient_start' => '#D1FAE5',
                'gradient_end'   => '#A7F3D0',
                'sort_order'     => 4,
                'is_active'      => true,
                'description'    => 'Financial provision and debt concerns',
            ],
            [
                'name'           => 'Personal',
                'css_class'      => 'category-personal',
                'emoji'          => '🙏',
                'display_color'  => '#3B82F6',
                'gradient_start' => '#DBEAFE',
                'gradient_end'   => '#EFF6FF',
                'sort_order'     => 5,
                'is_active'      => true,
                'description'    => 'Personal spiritual growth and guidance',
            ],
            [
                'name'           => 'Other',
                'css_class'      => 'category-other',
                'emoji'          => '✨',
                'display_color'  => '#6366F1',
                'gradient_start' => '#EEF2FF',
                'gradient_end'   => '#E0E7FF',
                'sort_order'     => 6,
                'is_active'      => true,
                'description'    => 'All other prayer needs',
            ],
        ];

        $now = now();
        foreach ($categories as $category) {
            $category['church_id']   = $churchId;
            $category['created_at']  = $now;
            $category['updated_at']  = $now;

            DB::table('prayer_categories')
                ->insertOrIgnore($category);
        }

        // Settings — use existing settings table (key-value store)
        $fieldJson = '{"name":"value","label":"Value","type":"text"}';

        $settings = [
            [
                'key'         => 'prayer:defaultExpiryDays',
                'name'        => 'Prayer Default Expiry Days',
                'description' => 'Default number of days a prayer stays active before expiring',
                'value'       => '60',
                'field'       => $fieldJson,
                'active'      => 1,
            ],
            [
                'key'         => 'prayer:autoDeleteRejectedAfterDays',
                'name'        => 'Auto-Delete Rejected Prayers After Days',
                'description' => 'Days before a rejected prayer is permanently deleted',
                'value'       => '7',
                'field'       => $fieldJson,
                'active'      => 1,
            ],
            [
                'key'         => 'prayer:allowAnonymousSubmission',
                'name'        => 'Allow Anonymous Prayer Submission',
                'description' => 'Whether guests can submit prayers without logging in',
                'value'       => '0',
                'field'       => $fieldJson,
                'active'      => 1,
            ],
            [
                'key'         => 'prayer:allowAnonymousParticipation',
                'name'        => 'Allow Anonymous Prayer Participation',
                'description' => 'Whether unregistered visitors can lift prayers',
                'value'       => '1',
                'field'       => $fieldJson,
                'active'      => 1,
            ],
        ];

        foreach ($settings as $setting) {
            $setting['created_at'] = $now;
            $setting['updated_at'] = $now;
            DB::table('settings')->insertOrIgnore($setting);
        }

        $this->command->info('Prayer Board seeded: ' . count($categories) . ' categories + 4 settings.');
    }
}
