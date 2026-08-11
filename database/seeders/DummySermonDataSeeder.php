<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * SermonTestDataSeeder
 *
 * Seeds realistic sermon test data with sermon links across audio, video,
 * and document types so every admin view has visible, browsable content.
 *
 * Requires:
 *  - At least one user with usergroup_id=6 (Preacher) for the church.
 *  - If none exists, falls back to the first user in the church.
 *
 * Run:  php artisan db:seed --class=SermonTestDataSeeder
 *
 * Safe to re-run — inserts additional rows each time.
 */
class DummySermonDataSeeder extends Seeder
{
    // ─── Sermon definitions ──────────────────────────────────────────────────

    private array $sermons = [
        [
            'title'       => 'Walking by Faith, Not by Sight',
            'description' => 'An exploration of 2 Corinthians 5:7 — what it truly means to trust God in seasons of uncertainty, when every visible circumstance seems to contradict His promises.',
        ],
        [
            'title'       => 'The Power of the Resurrection',
            'description' => 'A deep study of Romans 8:11 — how the same Spirit that raised Christ from the dead is alive in every believer today, transforming ordinary lives into testimonies of grace.',
        ],
        [
            'title'       => 'Rooted and Built Up in Him',
            'description' => 'Colossians 2:6-7 unpacked — how spiritual rootedness in Christ produces fruitfulness, stability, and overflowing thankfulness even amid life\'s storms.',
        ],
        [
            'title'       => 'The Armour of God',
            'description' => 'A verse-by-verse journey through Ephesians 6:10-18, equipping the congregation to stand firm against spiritual opposition through prayer, truth, and faith.',
        ],
        [
            'title'       => 'Grace That Is Greater',
            'description' => 'Romans 5:20 — where sin abounds, grace abounds all the more. A message of radical redemption and the inexhaustible mercy of God toward every broken heart.',
        ],
        [
            'title'       => 'Abiding in the Vine',
            'description' => 'John 15:1-8 expounded — the call to remain in intimate relationship with Christ as the only source of lasting fruit, purpose, and spiritual vitality.',
        ],
        [
            'title'       => 'Do Not Be Anxious',
            'description' => 'Philippians 4:6-7 opened — a practical and pastoral exposition on handing anxiety to God through thanksgiving and prayer, receiving the peace that surpasses understanding.',
        ],
        [
            'title'       => 'The Prodigal Father',
            'description' => 'A fresh reading of Luke 15:11-32 — not the son\'s waywardness but the father\'s scandalous, running, robe-giving, ring-placing welcome home. Grace reframed.',
        ],
        // [
        //     'title'       => 'Forgiven to Forgive',
        //     'description' => 'Matthew 18:21-35 examined. How the parable of the unmerciful servant challenges every believer to extend the same mercy they have freely received from God.',
        // ],
        // [
        //     'title'       => 'Being Still Before God',
        //     'description' => 'Psalm 46:10 meditated upon — in a noisy, frenzied world, what does it cost to be truly still before God? A call to contemplative faith and Sabbath trust.',
        // ],
        // [
        //     'title'       => 'More Than Conquerors',
        //     'description' => 'Romans 8:31-39 proclaimed — nothing in all creation can separate us from the love of God. A triumphant declaration for every believer walking through trials.',
        // ],
        // [
        //     'title'       => 'The Sermon on the Mount – Blessed Are the Poor in Spirit',
        //     'description' => 'Matthew 5:3, part one of an eight-week Beatitudes series. What does spiritual poverty look like, and why does Jesus call it the gateway to the Kingdom?',
        // ],
        // [
        //     'title'       => 'Streams of Living Water',
        //     'description' => 'John 7:37-39 expounded — Jesus\'s invitation to all who are thirsty. An exploration of what it means to drink deeply of the Holy Spirit in everyday life.',
        // ],
        // [
        //     'title'       => 'The Lord Is My Shepherd',
        //     'description' => 'Psalm 23 unpacked phrase by phrase — green pastures, still waters, the valley of the shadow, the table before enemies. A message of intimate divine care.',
        // ],
        // [
        //     'title'       => 'Called, Chosen, and Faithful',
        //     'description' => 'Revelation 17:14 — a missional sermon on the church\'s identity and purpose. We are not accidents; we are His called-out, chosen, and faithful people sent into the world.',
        // ],
    ];

    // ─── YouTube example URLs (public domain / creative commons sermons) ─────

    private array $youtubeUrls = [
        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'https://www.youtube.com/watch?v=9bZkp7q19f0',
        'https://www.youtube.com/watch?v=OPf0YbXqDm0',
        'https://www.youtube.com/watch?v=kJQP7kiw5Fk',
        'https://www.youtube.com/watch?v=fJ9rUzIMcZQ',
        'https://www.youtube.com/watch?v=60ItHLz5WEA',
        'https://www.youtube.com/watch?v=hT_nvWreIhg',
        'https://www.youtube.com/watch?v=450p7goxZqg',
    ];

    private array $audioSources = [
        'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
        'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3',
        'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3',
        'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3',
        'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-5.mp3',
    ];

    private array $chapterTitles = [
        'Part 1 — Introduction',
        'Part 2 — Deep Dive',
        'Part 3 — Application',
        'Full Sermon',
        'Sunday Message',
        'Wednesday Study',
        'Youth Edition',
        'Q&A Session',
        'Recap & Reflection',
    ];

    // ─── Run ─────────────────────────────────────────────────────────────────

    public function run(): void
    {
        // Resolve church from a preacher, then fall back to any user
        $preacher = User::where('usergroup_id', 6)->first()
            ?? User::whereIn('usergroup_id', [1, 2, 5])->first()
            ?? User::first();

        if (! $preacher) {
            $this->command->error('No users found. Run the installer first.');
            return;
        }

        $churchId  = $preacher->church_id;
        $preacherId = $preacher->id;

        $this->command->info("Seeding sermons for church_id={$churchId}, preacher user_id={$preacherId}...");

        $now        = Carbon::now();
        $sermonRows = [];

        foreach ($this->sermons as $idx => $def) {
            // Spread created_at across the last 18 months
            $createdAt = $now->copy()->subDays(rand($idx * 5, $idx * 5 + 30));

            $paths = [
                'uploads/Images/Sermons/sermons4.jpg',
                'uploads/Images/Sermons/sermons5.jpg',
                'uploads/Images/Sermons/sermons6.jpg',
                'uploads/Images/Sermons/sermons7.jpg',
                'uploads/Images/Sermons/sermons8.jpg',
                'uploads/Images/Sermons/sermons9.jpg',
                'uploads/Images/Sermons/sermons10.jpg',
                'uploads/Images/Sermons/sermons11.jpg',
                'uploads/Images/Sermons/sermons12.jpg',
                'https://picsum.photos/seed/sermon' . ($idx + 1) . '/640/360'
            ];



            $sermonId = DB::table('sermons')->insertGetId([
                'church_id'   => $churchId,
                'user_id'     => $preacherId,
                'title'       => $def['title'],
                'description' => $def['description'],
                'cover_image' => url($paths[array_rand($paths)]),
                'created_at'  => $createdAt,
                'updated_at'  => $createdAt,
            ]);

            $sermonRows[] = [
                'id'        => $sermonId,
                'createdAt' => $createdAt,
            ];
        }

        $this->command->info('Created ' . count($sermonRows) . ' sermons. Adding sermon links...');

        $linkCount = 0;

        foreach ($sermonRows as $row) {
            $sermonId  = $row['id'];
            $baseDate  = $row['createdAt'];

            // Every sermon gets at least one chapter with a video link
            $this->insertLink($sermonId, $churchId, $preacherId, $baseDate, 0, true, true, false);
            $linkCount++;

            // Most sermons get a second chapter with audio only
            if (rand(0, 4) !== 0) {
                $this->insertLink($sermonId, $churchId, $preacherId, $baseDate, 7, false, true, false);
                $linkCount++;
            }

            // About half get a third chapter with a PDF
            if (rand(0, 1) === 1) {
                $this->insertLink($sermonId, $churchId, $preacherId, $baseDate, 14, false, false, true);
                $linkCount++;
            }
        }

        $this->command->info("Created {$linkCount} sermon links.");
        $this->command->info('Done! Total: ' . count($sermonRows) . ' sermons, ' . $linkCount . ' links.');
        $this->command->line('  Browse at: /admin/sermons');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function insertLink(
        int $sermonId,
        int $churchId,
        int $userId,
        Carbon $baseDate,
        int $offsetDays,
        bool $withVideo,
        bool $withAudio,
        bool $withPdf
    ): void {
        $date = $baseDate->copy()->addDays($offsetDays);

        DB::table('sermons_links')->insert([
            'church_id'  => $churchId,
            'user_id'    => $userId,
            'sermons_id' => $sermonId,
            'title'      => $this->chapterTitles[array_rand($this->chapterTitles)],
            'date'       => $date->format('Y-m-d H:i:s'),
            'video_link' => $withVideo ? $this->youtubeUrls[array_rand($this->youtubeUrls)] : null,
            'audio_link' => $withAudio ? $this->audioSources[array_rand($this->audioSources)] : null,
            'pdf_link'   => $withPdf   ? null : null, // file upload not applicable in seeder
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
