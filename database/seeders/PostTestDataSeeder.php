<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * PostTestDataSeeder
 *
 * Seeds 8 post categories with 2-3 sample posts each.
 * Posts use placeholder content with realistic titles.
 * Safe to re-run — uses firstOrCreate on category name + church, and post title + church.
 *
 * Run: php artisan db:seed --class=PostTestDataSeeder
 */
class PostTestDataSeeder extends Seeder
{
    public function run()
    {
        $church = Church::first();

        if (! $church) {
            $this->command->error('No church found. Run church:setup first.');
            return;
        }

        $author = User::where('church_id', $church->id)->first() ?? User::first();

        if (! $author) {
            $this->command->error('No user found. Run church:setup first.');
            return;
        }

        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

        $categories = [
            [
                'name'        => 'News',
                'description' => 'Updates on recent church activities, community impact, and important developments.',
                'posts'       => [
                    ['title' => 'Church Renovation Project Update: Phase Two Begins', 'days_ago' => 2],
                    ['title' => 'Our Community Reaches a Milestone: 500 Families Served', 'days_ago' => 10],
                    ['title' => 'New Partnership with Local Food Bank Launches This Month', 'days_ago' => 18],
                ],
            ],
            [
                'name'        => 'Announcements',
                'description' => 'Official notices such as upcoming events, service timings, holidays, and alerts.',
                'posts'       => [
                    ['title' => 'Change in Sunday Service Time — Starting Next Week', 'days_ago' => 1],
                    ['title' => 'Office Closure During Easter Week — Hours & Contact Info', 'days_ago' => 7],
                    ['title' => 'Volunteer Registration Now Open for Annual Community Day', 'days_ago' => 14],
                ],
            ],
            [
                'name'        => 'General',
                'description' => 'Broad category for miscellaneous posts, reflections, and general church-related content.',
                'posts'       => [
                    ['title' => 'A Note of Gratitude from Our Senior Pastor', 'days_ago' => 5],
                    ['title' => 'Reflecting on a Year of Growth and Faithfulness', 'days_ago' => 30],
                ],
            ],
            [
                'name'        => 'Sermons & Teachings',
                'description' => 'Weekly sermons, Bible teachings, and spiritual messages from pastors.',
                'posts'       => [
                    ['title' => 'Walking in Faith When the Path is Uncertain', 'days_ago' => 7],
                    ['title' => 'The Power of Prayer: Lessons from the Book of James', 'days_ago' => 14],
                    ['title' => 'Grace Undeserved — Understanding God\'s Unconditional Love', 'days_ago' => 21],
                ],
            ],
            [
                'name'        => 'Events & Programs',
                'description' => 'Details and recaps of church events, camps, retreats, and special programs.',
                'posts'       => [
                    ['title' => 'Annual Youth Camp 2026 — Highlights & Photo Recap', 'days_ago' => 3],
                    ['title' => 'Women\'s Retreat: A Weekend of Renewal and Fellowship', 'days_ago' => 20],
                    ['title' => 'Easter Sunday Celebration — What to Expect This Year', 'days_ago' => 25],
                ],
            ],
            [
                'name'        => 'Devotionals & Faith',
                'description' => 'Daily or weekly devotionals, prayer guides, and faith-based encouragement.',
                'posts'       => [
                    ['title' => 'Weekly Devotional: Finding Peace in the Psalms', 'days_ago' => 1],
                    ['title' => '30-Day Prayer Challenge — Join Us This Month', 'days_ago' => 8],
                    ['title' => 'Trusting God in Seasons of Waiting', 'days_ago' => 15],
                ],
            ],
            [
                'name'        => 'Community & Outreach',
                'description' => 'Stories and updates about charity work, missions, and community service.',
                'posts'       => [
                    ['title' => 'Mission Trip Report: Two Weeks in Rural Communities', 'days_ago' => 6],
                    ['title' => 'Feeding the Hungry: Our Monthly Food Drive Results', 'days_ago' => 13],
                ],
            ],
            [
                'name'        => 'Youth & Ministry',
                'description' => 'Content focused on youth groups, ministries, and internal church activities.',
                'posts'       => [
                    ['title' => 'Youth Group: End-of-Term Celebration & What\'s Coming Next', 'days_ago' => 4],
                    ['title' => 'Introducing Our New Children\'s Ministry Coordinator', 'days_ago' => 11],
                    ['title' => 'Teen Bible Study Series — Sign Up Now for Term Two', 'days_ago' => 22],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = PostCategory::firstOrCreate(
                ['church_id' => $church->id, 'name' => $categoryData['name']],
                ['description' => $categoryData['description'], 'status' => 1]
            );

            foreach ($categoryData['posts'] as $postData) {
                $postedAt = Carbon::now()->subDays($postData['days_ago']);

                Post::firstOrCreate(
                    ['church_id' => $church->id, 'title' => $postData['title']],
                    [
                        'category_id'    => $category->id,
                        'entity_id'      => $author->id,
                        'entity_name'    => 'App\Models\User',
                        'description'    => $lorem,
                        'post_created_at'=> $postedAt,
                        'is_posted'      => 1,
                        'posted_at'      => $postedAt,
                        'status'         => 'posted',
                        'created_by'     => $author->id,
                    ]
                );
            }

            $count = Post::where('category_id', $category->id)->count();
            $this->command->info("  Seeded category: {$category->name} ({$count} posts)");
        }

        $this->command->info('PostTestDataSeeder complete.');
    }
}
