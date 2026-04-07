<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $churchId = 1;

        $map = [
            'about' => [
                [
                    'page_name'        => 'Our Story',
                    'slug'             => 'our-story',
                    'menu_text'        => 'Our Story',
                    'menu_order'       => 1,
                    'meta_title'       => 'Our Story | About Our Church',
                    'meta_description' => 'Discover the history and journey of our church community, from our founding to the present day.',
                    'meta_keywords'    => 'church history, our story, founding, community',
                    'description'      => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vehicula felis in lorem posuere, vel gravida leo volutpat. Fusce venenatis neque sed lorem finibus, at volutpat purus condimentum.</p><p>Integer porta velit sit amet velit condimentum, vitae placerat enim suscipit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>',
                ],
                [
                    'page_name'        => 'Our Mission',
                    'slug'             => 'our-mission',
                    'menu_text'        => 'Our Mission',
                    'menu_order'       => 2,
                    'meta_title'       => 'Our Mission | About Our Church',
                    'meta_description' => 'Learn about the mission and purpose that drives everything we do as a church community.',
                    'meta_keywords'    => 'church mission, purpose, calling, vision',
                    'description'      => '<p>Our mission is to love God, love people and serve the world. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p><p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',
                ],
                [
                    'page_name'        => 'Our History',
                    'slug'             => 'our-history',
                    'menu_text'        => 'Our History',
                    'menu_order'       => 3,
                    'meta_title'       => 'Our History | About Our Church',
                    'meta_description' => 'A timeline of key milestones and moments that have shaped our church over the years.',
                    'meta_keywords'    => 'church history, milestones, timeline, heritage',
                    'description'      => '<p>Since our founding, we have grown from a small gathering into a welcoming community of hundreds. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam semper orci a fermentum sodales.</p>',
                ],
            ],

            'services' => [
                [
                    'page_name'        => 'Worship Times',
                    'slug'             => 'worship-times',
                    'menu_text'        => 'Worship Times',
                    'menu_order'       => 1,
                    'meta_title'       => 'Worship Service Times',
                    'meta_description' => 'Find service times for Sunday worship, midweek gatherings and special services throughout the year.',
                    'meta_keywords'    => 'service times, Sunday worship, church schedule',
                    'description'      => '<p><strong>Sunday Services:</strong> 8:00 am, 10:00 am, 6:00 pm</p><p><strong>Wednesday Bible Study:</strong> 7:00 pm</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tincidunt, odio non sodales tristique, ipsum nisl tristique felis.</p>',
                ],
                [
                    'page_name'        => 'Baptism & Celebrations',
                    'slug'             => 'baptism-celebrations',
                    'menu_text'        => 'Baptism & Celebrations',
                    'menu_order'       => 2,
                    'meta_title'       => 'Baptisms, Weddings & Celebrations',
                    'meta_description' => 'Information about baptisms, weddings, renewal of vows and other life celebrations at our church.',
                    'meta_keywords'    => 'baptism, wedding, renewal of vows, celebrations, sacraments',
                    'description'      => '<p>We celebrate life\'s most important moments alongside you. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras commodo cursus magna, vel scelerisque nisl consectetur et.</p>',
                ],
                [
                    'page_name'        => 'Pastoral Care',
                    'slug'             => 'pastoral-care',
                    'menu_text'        => 'Pastoral Care',
                    'menu_order'       => 3,
                    'meta_title'       => 'Pastoral Care & Support',
                    'meta_description' => 'Our pastoral team is here to support you through illness, bereavement, counselling and life\'s challenges.',
                    'meta_keywords'    => 'pastoral care, counselling, support, prayer, bereavement',
                    'description'      => '<p>Our pastoral team offers confidential support and prayer for those going through difficult seasons. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas sed diam eget risus varius blandit.</p>',
                ],
                [
                    'page_name'        => 'Children\'s Church',
                    'slug'             => 'childrens-church',
                    'menu_text'        => 'Children\'s Church',
                    'menu_order'       => 4,
                    'meta_title'       => 'Children\'s Church & Sunday School',
                    'meta_description' => 'Fun, safe and nurturing Sunday programmes for children from toddlers to age 12.',
                    'meta_keywords'    => 'children\'s church, Sunday school, kids, nursery',
                    'description'      => '<p>Every Sunday during the 10:00 am service we run dedicated programmes for children of all ages. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam scelerisque leo sit amet nibh commodo sagittis.</p>',
                ],
            ],

            'vision-values' => [
                [
                    'page_name'        => 'Our Vision',
                    'slug'             => 'our-vision',
                    'menu_text'        => 'Our Vision',
                    'menu_order'       => 1,
                    'meta_title'       => 'Our Vision for the Future',
                    'meta_description' => 'Where we are headed as a church — our faith-filled vision for our community and beyond.',
                    'meta_keywords'    => 'church vision, future, growth, transformation',
                    'description'      => '<p>We believe in a church that is alive, growing and engaged with its community. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error.</p>',
                ],
                [
                    'page_name'        => 'What We Believe',
                    'slug'             => 'what-we-believe',
                    'menu_text'        => 'What We Believe',
                    'menu_order'       => 2,
                    'meta_title'       => 'Our Statement of Beliefs',
                    'meta_description' => 'A clear statement of the core Christian beliefs and doctrines that our church is founded on.',
                    'meta_keywords'    => 'beliefs, doctrine, statement of faith, theology',
                    'description'      => '<p>We hold to the historic Christian faith as expressed in Scripture and the early creeds. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur.</p>',
                ],
                [
                    'page_name'        => 'Our Values',
                    'slug'             => 'our-values',
                    'menu_text'        => 'Our Values',
                    'menu_order'       => 3,
                    'meta_title'       => 'The Values That Shape Us',
                    'meta_description' => 'The core values that guide how we worship, serve and relate to one another.',
                    'meta_keywords'    => 'church values, core values, principles, culture',
                    'description'      => '<p>Our values of grace, integrity, community and service shape everything we do. Lorem ipsum dolor sit amet, consectetur adipiscing elit. At vero eos et accusamus et iusto odio dignissimos.</p>',
                ],
            ],

            'ministry' => [
                [
                    'page_name'        => 'Outreach & Missions',
                    'slug'             => 'outreach-missions',
                    'menu_text'        => 'Outreach & Missions',
                    'menu_order'       => 1,
                    'meta_title'       => 'Outreach & Missions Ministry',
                    'meta_description' => 'How our church serves the local community and supports global mission partners.',
                    'meta_keywords'    => 'outreach, missions, community service, evangelism',
                    'description'      => '<p>We are committed to serving our local neighbourhood and supporting missionaries around the world. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam libero tempore, cum soluta nobis est eligendi optio.</p>',
                ],
                [
                    'page_name'        => 'Worship Ministry',
                    'slug'             => 'worship-ministry',
                    'menu_text'        => 'Worship Ministry',
                    'menu_order'       => 2,
                    'meta_title'       => 'Worship Ministry Team',
                    'meta_description' => 'Meet our worship ministry team and find out how to get involved in leading worship.',
                    'meta_keywords'    => 'worship team, music, praise, ministry team',
                    'description'      => '<p>Our worship team leads the congregation each Sunday with heartfelt, Christ-centred worship. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Temporibus autem quibusdam et aut officiis debitis.</p>',
                ],
                [
                    'page_name'        => 'Prayer Ministry',
                    'slug'             => 'prayer-ministry',
                    'menu_text'        => 'Prayer Ministry',
                    'menu_order'       => 3,
                    'meta_title'       => 'Prayer Ministry',
                    'meta_description' => 'Join our prayer teams and discover the prayer resources available to our church family.',
                    'meta_keywords'    => 'prayer, intercession, prayer team, pray',
                    'description'      => '<p>Prayer is at the heart of everything we do. Our prayer ministry meets weekly and is always ready to pray with and for you. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
                ],
            ],

            'leadership' => [
                [
                    'page_name'        => 'Our Pastors',
                    'slug'             => 'our-pastors',
                    'menu_text'        => 'Our Pastors',
                    'menu_order'       => 1,
                    'meta_title'       => 'Meet Our Pastors',
                    'meta_description' => 'Meet the pastoral team who lead and shepherd our church community with vision and care.',
                    'meta_keywords'    => 'pastors, senior pastor, leadership, church leaders',
                    'description'      => '<p>Our pastoral team is passionate about seeing lives transformed by the gospel. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Itaque earum rerum hic tenetur a sapiente delectus.</p>',
                ],
                [
                    'page_name'        => 'Elders & Deacons',
                    'slug'             => 'elders-deacons',
                    'menu_text'        => 'Elders & Deacons',
                    'menu_order'       => 2,
                    'meta_title'       => 'Our Elders & Deacons',
                    'meta_description' => 'Our elders and deacons provide faithful spiritual oversight and servant leadership to the congregation.',
                    'meta_keywords'    => 'elders, deacons, church governance, servant leadership',
                    'description'      => '<p>The elders provide spiritual oversight while our deacons serve the practical needs of our congregation. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut aut reiciendis voluptatibus maiores.</p>',
                ],
                [
                    'page_name'        => 'Parish Council',
                    'slug'             => 'parish-council',
                    'menu_text'        => 'Parish Council',
                    'menu_order'       => 3,
                    'meta_title'       => 'Parish Council',
                    'meta_description' => 'Our parish council oversees the administration and stewardship of church resources.',
                    'meta_keywords'    => 'parish council, administration, stewardship, governance',
                    'description'      => '<p>The parish council works alongside the pastoral team to ensure the church operates with integrity and accountability. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
                ],
            ],

            'teachings' => [
                [
                    'page_name'        => 'Current Sermon Series',
                    'slug'             => 'sermon-series',
                    'menu_text'        => 'Sermon Series',
                    'menu_order'       => 1,
                    'meta_title'       => 'Current Sermon Series',
                    'meta_description' => 'Follow along with our current sermon series and dive deeper into God\'s Word.',
                    'meta_keywords'    => 'sermons, sermon series, preaching, teaching',
                    'description'      => '<p>Each sermon series is designed to help you grow in faith and understanding of Scripture. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse.</p>',
                ],
                [
                    'page_name'        => 'Bible Study Resources',
                    'slug'             => 'bible-study-resources',
                    'menu_text'        => 'Bible Study',
                    'menu_order'       => 2,
                    'meta_title'       => 'Bible Study Resources',
                    'meta_description' => 'Access study guides, devotionals and recommended reading to support your personal Bible study.',
                    'meta_keywords'    => 'Bible study, devotionals, study guides, small groups',
                    'description'      => '<p>We provide a range of resources to help you study the Bible on your own and with others. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.</p>',
                ],
                [
                    'page_name'        => 'Devotionals',
                    'slug'             => 'devotionals',
                    'menu_text'        => 'Devotionals',
                    'menu_order'       => 3,
                    'meta_title'       => 'Daily Devotionals',
                    'meta_description' => 'Short daily devotions written by our pastoral team to encourage and inspire your walk with God.',
                    'meta_keywords'    => 'devotionals, daily devotion, quiet time, spiritual growth',
                    'description'      => '<p>Start each day with a short reflection to focus your heart on God. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.</p>',
                ],
            ],

            'groups-activities' => [
                [
                    'page_name'        => 'Youth Group',
                    'slug'             => 'youth-group',
                    'menu_text'        => 'Youth Group',
                    'menu_order'       => 1,
                    'meta_title'       => 'Youth Group | Ages 13–18',
                    'meta_description' => 'A vibrant community for teenagers to grow in faith, friendship and fun.',
                    'meta_keywords'    => 'youth group, teenagers, youth ministry, young people',
                    'description'      => '<p>Our youth group meets every Friday evening and creates a fun, safe space for young people to explore faith. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
                ],
                [
                    'page_name'        => 'Small Groups',
                    'slug'             => 'small-groups',
                    'menu_text'        => 'Small Groups',
                    'menu_order'       => 2,
                    'meta_title'       => 'Small Groups & Home Groups',
                    'meta_description' => 'Connect with others in a smaller setting through our network of home groups across the city.',
                    'meta_keywords'    => 'small groups, home groups, community, cell groups',
                    'description'      => '<p>Small groups are where real community happens. Find a group near you and start building lasting friendships. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
                ],
                [
                    'page_name'        => 'Community Events',
                    'slug'             => 'community-events',
                    'menu_text'        => 'Community Events',
                    'menu_order'       => 3,
                    'meta_title'       => 'Community Events & Activities',
                    'meta_description' => 'From family fun days to charity fundraisers — see what\'s happening in our church community.',
                    'meta_keywords'    => 'community events, activities, fundraiser, family',
                    'description'      => '<p>We host events throughout the year to bring our congregation and neighbours together. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.</p>',
                ],
            ],
        ];

        foreach ($map as $categorySlug => $pages) {
            $category = PageCategory::where('church_id', $churchId)
                ->where('slug', $categorySlug)
                ->first();

            if (! $category) {
                continue;
            }

            foreach ($pages as $pageData) {
                Page::firstOrCreate(
                    ['church_id' => $churchId, 'slug' => $pageData['slug']],
                    array_merge($pageData, [
                        'church_id'   => $churchId,
                        'category_id' => $category->id,
                        'created_by'  => 1,
                        'status'      => 1,
                    ])
                );
            }
        }
    }
}
