<?php

namespace Database\Seeders;

use App\Models\PageCategory;
use Illuminate\Database\Seeder;

class PageCategorySeeder extends Seeder
{
    public function run(): void
    {
        $churchId = 1;

        $categories = [
            [
                'name'        => 'About',
                'slug'        => 'about',
                'icon'        => '⛪',
                'description' => 'The story, mission and history of our church.',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Services',
                'slug'        => 'services',
                'icon'        => '🙏',
                'description' => 'Worship services, sacraments and pastoral care.',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Vision & Values',
                'slug'        => 'vision-values',
                'icon'        => '✨',
                'description' => 'What we believe and where we are going.',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Ministry',
                'slug'        => 'ministry',
                'icon'        => '🤝',
                'description' => 'Ministry teams and outreach programmes.',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Leadership',
                'slug'        => 'leadership',
                'icon'        => '👥',
                'description' => 'Our pastoral team, elders and parish council.',
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Teachings',
                'slug'        => 'teachings',
                'icon'        => '📖',
                'description' => 'Sermon series, Bible studies and devotionals.',
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Groups & Activities',
                'slug'        => 'groups-activities',
                'icon'        => '🎉',
                'description' => 'Community groups, youth, children and special activities.',
                'sort_order'  => 7,
            ],
        ];

        foreach ($categories as $data) {
            PageCategory::firstOrCreate(
                ['church_id' => $churchId, 'slug' => $data['slug']],
                array_merge($data, ['church_id' => $churchId, 'status' => 1])
            );
        }
    }
}
