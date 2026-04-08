<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

/**
 * FaqTestDataSeeder
 *
 * Seeds 6 FAQ categories with generic church FAQs (~4-6 items each).
 * Uses firstOrCreate to stay safe on re-runs.
 *
 * Run: php artisan db:seed --class=FaqTestDataSeeder
 */
class FaqTestDataSeeder extends Seeder
{
    public function run()
    {
        $church = Church::first();

        if (! $church) {
            $this->command->error('No church found. Run church:setup first.');
            return;
        }

        $data = [
            [
                'name' => 'General Questions',
                'faqs' => [
                    [
                        'question' => 'What is the history of this church?',
                        'answer'   => 'Our church was founded with the mission of serving our community and spreading the love of God. Over the years we have grown into a vibrant congregation dedicated to worship, fellowship, and outreach.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'Is everyone welcome here?',
                        'answer'   => 'Absolutely. We welcome people of all backgrounds, ages, and walks of life. Our doors are open to anyone seeking fellowship, spiritual growth, or simply a place to belong.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'How do I get in touch with the church office?',
                        'answer'   => 'You can reach us through the Contact page on this website, or visit us during office hours. Our staff will be happy to assist you.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'Do I need to be a member to attend services?',
                        'answer'   => 'Not at all. All services are open to the public. We encourage everyone to attend and experience our community before deciding whether to become a formal member.',
                        'order'    => 4,
                    ],
                ],
            ],
            [
                'name' => 'Beliefs & Theology',
                'faqs' => [
                    [
                        'question' => 'What are the core beliefs of this church?',
                        'answer'   => 'We believe in the authority of Scripture, salvation through faith in Jesus Christ, the Trinity, and the importance of living out our faith through love and service to others.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'What translation of the Bible do you use?',
                        'answer'   => 'Our services primarily use the New International Version (NIV), though our pastors may reference other translations to provide deeper context during sermons.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'Do you practice baptism?',
                        'answer'   => 'Yes, we practice baptism as an outward declaration of an inward faith. We offer both infant dedication and believer\'s baptism. Speak with a pastor to learn more.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'What is your stance on communion?',
                        'answer'   => 'We celebrate communion (the Lord\'s Supper) regularly as a time of remembrance, reflection, and renewal. All who profess faith in Jesus Christ are welcome to participate.',
                        'order'    => 4,
                    ],
                ],
            ],
            [
                'name' => 'Services & Events',
                'faqs' => [
                    [
                        'question' => 'When are your regular Sunday services?',
                        'answer'   => 'We hold Sunday services every week. Times are listed on our Events page and updated regularly. We encourage you to arrive a few minutes early to find a seat and connect with others.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'Are there mid-week services or programs?',
                        'answer'   => 'Yes, we hold mid-week Bible studies, prayer meetings, and various ministry programs throughout the week. Check our Events page for the latest schedule.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'How do I find out about upcoming events?',
                        'answer'   => 'All upcoming events are listed on the Events page of this website. You can also follow our social media channels or subscribe to our newsletter for updates.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'Can I live-stream the services?',
                        'answer'   => 'We strive to make our services accessible to everyone. Please check our website or contact the office to learn about available live-stream or recorded service options.',
                        'order'    => 4,
                    ],
                    [
                        'question' => 'Are special services held on religious holidays?',
                        'answer'   => 'Yes, we host special services for Christmas, Easter, and other significant Christian holidays. These are announced on the Events page well in advance.',
                        'order'    => 5,
                    ],
                ],
            ],
            [
                'name' => 'Spiritual Matters',
                'faqs' => [
                    [
                        'question' => 'How can I request prayer for myself or a loved one?',
                        'answer'   => 'You can submit a prayer request through the Prayer Requests page on this website. Our prayer team reviews submissions regularly and lifts them up in prayer.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'Is pastoral counselling available?',
                        'answer'   => 'Yes, our pastoral team is available for one-on-one counselling and spiritual guidance. Please contact the church office to schedule an appointment.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'How do I get baptised or take the next step in my faith?',
                        'answer'   => 'We would love to walk alongside you. Speak with any of our pastors after a service, or reach out through the Contact page and someone will be in touch.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'Are there small groups or Bible study groups I can join?',
                        'answer'   => 'Yes, we have a range of small groups meeting throughout the week for Bible study, prayer, and fellowship. Contact the church office or speak to a pastor to find the right group for you.',
                        'order'    => 4,
                    ],
                ],
            ],
            [
                'name' => 'Children & Youth',
                'faqs' => [
                    [
                        'question' => 'Are there programs for children during Sunday services?',
                        'answer'   => 'Yes, we run age-appropriate children\'s programs during the main Sunday service so parents can worship freely. Children are welcomed into the service first before being dismissed to their classes.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'What youth programs do you offer for teenagers?',
                        'answer'   => 'Our youth ministry provides weekly gatherings, camps, and community service opportunities designed to help teenagers grow in their faith in a fun and supportive environment.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'Are background checks done for children\'s ministry volunteers?',
                        'answer'   => 'Yes, the safety of children is our highest priority. All volunteers working with children undergo background screening and complete our safeguarding training before serving.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'Can I volunteer in the children\'s ministry?',
                        'answer'   => 'We would love to have you! Contact our children\'s ministry coordinator through the church office to find out about volunteer opportunities and the onboarding process.',
                        'order'    => 4,
                    ],
                ],
            ],
            [
                'name' => 'Getting Involved',
                'faqs' => [
                    [
                        'question' => 'How do I become a member of the church?',
                        'answer'   => 'We offer a membership class that covers our beliefs, vision, and how to get connected. Speak with a pastor or contact the office to find out when the next class is scheduled.',
                        'order'    => 1,
                    ],
                    [
                        'question' => 'How can I volunteer or serve?',
                        'answer'   => 'There are many ways to serve — from worship and hospitality to outreach and administration. Visit the church office or speak to a ministry leader after a service to explore opportunities.',
                        'order'    => 2,
                    ],
                    [
                        'question' => 'Do you have outreach or community service programs?',
                        'answer'   => 'Yes, community outreach is central to our mission. We run regular programs serving the local community including food drives, visitation programs, and partnership with local charities.',
                        'order'    => 3,
                    ],
                    [
                        'question' => 'How can I give or support the church financially?',
                        'answer'   => 'Giving can be done in-person during services or online through our giving platform. We believe in transparent stewardship and publish annual financial summaries for our congregation.',
                        'order'    => 4,
                    ],
                    [
                        'question' => 'Are there opportunities for women\'s and men\'s ministries?',
                        'answer'   => 'Yes, we have dedicated women\'s and men\'s fellowship groups that meet regularly for Bible study, prayer, and social connection. Contact the church office for the current schedule.',
                        'order'    => 5,
                    ],
                ],
            ],
        ];

        foreach ($data as $categoryData) {
            $category = FaqCategory::firstOrCreate(
                ['church_id' => $church->id, 'name' => $categoryData['name']],
                ['status' => 1]
            );

            foreach ($categoryData['faqs'] as $faqData) {
                Faq::firstOrCreate(
                    [
                        'church_id'       => $church->id,
                        'faq_category_id' => $category->id,
                        'question'        => $faqData['question'],
                    ],
                    [
                        'answer' => $faqData['answer'],
                        'order'  => $faqData['order'],
                        'status' => 1,
                    ]
                );
            }

            $this->command->info("  Seeded category: {$category->name} ({$category->faq()->count()} FAQs)");
        }

        $this->command->info('FaqTestDataSeeder complete.');
    }
}
