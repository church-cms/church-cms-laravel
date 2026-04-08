<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTestDataSeeder extends Seeder
{
    public function run()
    {
        $tagMap = [
            'Church Renovation Project Update: Phase Two Begins'        => ['News', 'Updates', 'Building'],
            'Our Community Reaches a Milestone: 500 Families Served'    => ['Community', 'Milestone', 'Outreach'],
            'New Partnership with Local Food Bank Launches This Month'  => ['Outreach', 'Food', 'Partnership'],
            'Change in Sunday Service Time — Starting Next Week'        => ['Announcements', 'Service', 'Schedule'],
            'Office Closure During Easter Week — Hours & Contact Info'  => ['Announcements', 'Easter', 'Office'],
            'Volunteer Registration Now Open for Annual Community Day'  => ['Volunteer', 'Community', 'Events'],
            'A Note of Gratitude from Our Senior Pastor'                => ['Pastor', 'Gratitude', 'Faith'],
            'Reflecting on a Year of Growth and Faithfulness'           => ['Reflection', 'Faith', 'Growth'],
            'Walking in Faith When the Path is Uncertain'               => ['Faith', 'Sermon', 'Encouragement'],
            'The Power of Prayer: Lessons from the Book of James'       => ['Prayer', 'Sermon', 'Bible'],
            "Grace Undeserved — Understanding God's Unconditional Love" => ['Grace', 'Sermon', 'Love'],
            'Annual Youth Camp 2026 — Highlights & Photo Recap'         => ['Youth', 'Camp', 'Events'],
            "Women's Retreat: A Weekend of Renewal and Fellowship"      => ['Women', 'Retreat', 'Fellowship'],
            'Easter Sunday Celebration — What to Expect This Year'      => ['Easter', 'Celebration', 'Events'],
            'Weekly Devotional: Finding Peace in the Psalms'            => ['Devotional', 'Prayer', 'Psalms'],
            '30-Day Prayer Challenge — Join Us This Month'              => ['Prayer', 'Challenge', 'Faith'],
            'Trusting God in Seasons of Waiting'                        => ['Faith', 'Devotional', 'Encouragement'],
            'Mission Trip Report: Two Weeks in Rural Communities'       => ['Missions', 'Outreach', 'Community'],
            'Feeding the Hungry: Our Monthly Food Drive Results'        => ['Outreach', 'Food', 'Community'],
            "Youth Group: End-of-Term Celebration & What's Coming Next" => ['Youth', 'Events', 'Ministry'],
            "Introducing Our New Children's Ministry Coordinator"       => ['Ministry', 'Children', 'Announcements'],
            'Teen Bible Study Series — Sign Up Now for Term Two'        => ['Youth', 'Bible', 'Ministry'],
        ];

        foreach ($tagMap as $title => $tagNames) {
            $post = Post::where('title', $title)->first();
            if (! $post) {
                $this->command->warn("  MISS: $title");
                continue;
            }
            $tagIds = [];
            foreach ($tagNames as $name) {
                $tag      = Tag::firstOrCreate(['tag_name' => $name]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->syncWithoutDetaching($tagIds);
            $this->command->info("  Tagged: {$post->title}");
        }

        $this->command->info('TagTestDataSeeder complete.');
    }
}
