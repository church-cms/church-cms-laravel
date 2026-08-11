<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Widget;
use App\Models\Church;
use App\Models\User;

class DummyWidgetSeeder extends Seeder
{
    public function run()
    {
        $church = Church::first();
        if (!$church) {
            return;
        }

        $user = User::where('church_id', $church->id)->first();
        $userId = $user ? $user->id : 1;

        Widget::where('church_id', $church->id)->where('page', 'home')->delete();

        $sections = $this->sections();

        foreach ($sections as $order => $content) {
            Widget::create([
                'church_id'     => $church->id,
                'slug'          => Str::uuid()->toString(),
                'page'          => 'home',
                'display_order' => $order,
                'content'       => $content,
                'created_by'    => $userId,
            ]);
        }
    }

    private function sections(): array
    {
        return [

            // ── 1. Welcome ────────────────────────────────────────────────────────
            1 => <<<'HTML'
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

      <!-- Text -->
      <div>
        <span class="inline-block text-indigo-600 text-sm font-semibold uppercase tracking-widest mb-3">Welcome Home</span>
        <h2 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
          A Place to Belong.<br>A Family to Grow.
        </h2>
        <div class="mt-2 w-16 h-1 bg-indigo-600 rounded"></div>
        <p class="mt-6 text-lg text-gray-600 leading-relaxed">
          We are a loving community of faith rooted in the gospel of Jesus Christ. Whether you're exploring faith for the first time or searching for a church home, you are welcome here — exactly as you are.
        </p>
        <p class="mt-4 text-gray-500 leading-relaxed">
          Every Sunday we gather to worship, hear God's Word, and encourage one another. We'd love to have you join us.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="/contact"
             class="inline-block bg-indigo-700 hover:bg-indigo-800 text-white font-semibold px-7 py-3 rounded-lg shadow transition">
            Plan Your Visit
          </a>
          <a href="/page"
             class="inline-block border-2 border-indigo-700 text-indigo-700 hover:bg-indigo-100 font-semibold px-7 py-3 rounded-lg transition">
            Learn About Us
          </a>
        </div>
      </div>

      <!-- Decorative -->
      <div class="flex items-center justify-center">
        <div class="relative">
          <div class="w-80 h-80 bg-indigo-100 rounded-full flex items-center justify-center">
            <div class="text-center">
              <svg class="w-24 h-24 mx-auto text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M12 2v20M2 12h20" />
              </svg>
              <p class="mt-4 text-indigo-800 font-bold text-lg tracking-wide">Est. 1892</p>
              <p class="text-indigo-500 text-sm">Serving this community</p>
            </div>
          </div>
          <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-100 rounded-full opacity-80"></div>
          <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-indigo-200 rounded-full opacity-60"></div>
        </div>
      </div>

    </div>
  </div>
</section>
HTML,

            // ── 2. Service Times ──────────────────────────────────────────────────
            2 => <<<'HTML'
<section class="py-16 bg-indigo-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

    <span class="inline-block text-indigo-300 text-sm font-semibold uppercase tracking-widest mb-3">Join Us</span>
    <h2 class="text-3xl sm:text-4xl font-extrabold text-white">Service Times</h2>
    <p class="mt-3 text-indigo-200 text-lg max-w-xl mx-auto">
      We meet throughout the week. Come as you are — all are welcome.
    </p>

    <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

      <div class="bg-indigo-700 rounded-xl p-6 text-white shadow-lg border border-indigo-600">
        <div class="w-12 h-12 mx-auto bg-indigo-600 rounded-full flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-10H20M4 12H3.34M17.66 6.34l-.7.7M7.04 16.96l-.7.7M17.66 17.66l-.7-.7M7.04 7.04l-.7-.7"/>
          </svg>
        </div>
        <p class="text-indigo-300 text-xs font-semibold uppercase tracking-widest">Sunday</p>
        <p class="text-xl font-bold mt-1">8:00 AM</p>
        <p class="text-indigo-200 text-sm mt-2">Early Morning<br>Worship Service</p>
      </div>

      <div class="bg-white rounded-xl p-6 text-indigo-900 shadow-lg border border-indigo-100">
        <div class="w-12 h-12 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-10H20M4 12H3.34M17.66 6.34l-.7.7M7.04 16.96l-.7.7M17.66 17.66l-.7-.7M7.04 7.04l-.7-.7"/>
          </svg>
        </div>
        <p class="text-indigo-400 text-xs font-semibold uppercase tracking-widest">Sunday</p>
        <p class="text-xl font-bold mt-1">10:30 AM</p>
        <p class="text-gray-500 text-sm mt-2">Main Family<br>Worship &amp; Children</p>
        <span class="inline-block mt-3 text-xs bg-indigo-100 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">Featured</span>
      </div>

      <div class="bg-indigo-700 rounded-xl p-6 text-white shadow-lg border border-indigo-600">
        <div class="w-12 h-12 mx-auto bg-indigo-600 rounded-full flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
          </svg>
        </div>
        <p class="text-indigo-300 text-xs font-semibold uppercase tracking-widest">Sunday</p>
        <p class="text-xl font-bold mt-1">6:30 PM</p>
        <p class="text-indigo-200 text-sm mt-2">Evening<br>Prayer &amp; Worship</p>
      </div>

      <div class="bg-indigo-700 rounded-xl p-6 text-white shadow-lg border border-indigo-600">
        <div class="w-12 h-12 mx-auto bg-indigo-600 rounded-full flex items-center justify-center mb-4">
          <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <p class="text-indigo-300 text-xs font-semibold uppercase tracking-widest">Wednesday</p>
        <p class="text-xl font-bold mt-1">7:00 PM</p>
        <p class="text-indigo-200 text-sm mt-2">Midweek Bible<br>Study &amp; Prayer</p>
      </div>

    </div>

    <p class="mt-8 text-indigo-300 text-sm">
      <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      123 Cathedral Lane · Questions? <a href="/contact" class="underline text-indigo-200 hover:text-white">Get in touch</a>
    </p>

  </div>
</section>
HTML,

            // ── 3. Ministries ─────────────────────────────────────────────────────
            3 => <<<'HTML'
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-14">
      <span class="inline-block text-indigo-600 text-sm font-semibold uppercase tracking-widest mb-3">Get Involved</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Our Ministries</h2>
      <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">
        There is a place for everyone. Find your community, discover your gifts, and grow in faith together.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

      <!-- Youth -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-indigo-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Youth Ministry</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">Empowering teenagers to build their faith, form lasting friendships, and discover who God made them to be.</p>
        <a href="/page" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Learn more &rarr;</a>
      </div>

      <!-- Women -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-pink-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-pink-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Women's Fellowship</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">A sisterhood of support, prayer, and encouragement for women in every season of life.</p>
        <a href="/page" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Learn more &rarr;</a>
      </div>

      <!-- Men -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-blue-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Men's Brotherhood</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">Building men of character, integrity, and purpose through fellowship, accountability, and God's Word.</p>
        <a href="/page" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Learn more &rarr;</a>
      </div>

      <!-- Worship -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-yellow-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Worship Team</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">Leading the congregation into the presence of God through music, song, and heartfelt worship.</p>
        <a href="/page" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Learn more &rarr;</a>
      </div>

      <!-- Prayer -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-purple-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Prayer Ministry</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">Interceding for our community, nation, and world — because prayer is the foundation of everything we do.</p>
        <a href="/prayer" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Submit a request &rarr;</a>
      </div>

      <!-- Outreach -->
      <div class="group rounded-xl border border-gray-200 bg-white p-8 hover:shadow-xl hover:border-indigo-200 transition">
        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition">
          <svg class="w-7 h-7 text-green-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Community Outreach</h3>
        <p class="mt-3 text-gray-500 leading-relaxed">Serving our neighbours with practical love — food drives, community programs, and mission trips.</p>
        <a href="/page" class="inline-block mt-5 text-indigo-600 font-semibold text-sm hover:text-indigo-800">Get involved &rarr;</a>
      </div>

    </div>
  </div>
</section>
HTML,

            // ── 4. Sermons ────────────────────────────────────────────────────────
            4 => <<<'HTML'
<section class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12">
      <div>
        <span class="inline-block text-indigo-600 text-sm font-semibold uppercase tracking-widest mb-3">The Word</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Recent Sermons</h2>
        <p class="mt-3 text-gray-500 text-lg max-w-xl">Dig into the message wherever you are. New sermons posted every week.</p>
      </div>
      <a href="/sermons"
         class="mt-6 sm:mt-0 inline-block bg-indigo-700 hover:bg-indigo-800 text-white font-semibold px-6 py-3 rounded-lg shadow transition flex-shrink-0">
        All Sermons &rarr;
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 p-8 flex items-center justify-center">
          <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div class="p-6">
          <span class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">Series: Psalms of Ascent</span>
          <h3 class="mt-2 text-lg font-bold text-gray-900">Walking Through the Valley</h3>
          <p class="mt-2 text-gray-500 text-sm leading-relaxed">Psalm 23 reminds us that even in the darkest seasons, the Good Shepherd walks beside us.</p>
          <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
            <span>Rev. John Matthews</span>
            <span>Apr 20, 2025</span>
          </div>
          <a href="/sermons" class="mt-4 inline-block text-indigo-600 font-semibold text-sm hover:text-indigo-800">Listen &rarr;</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-8 flex items-center justify-center">
          <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div class="p-6">
          <span class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">Series: Faith in Action</span>
          <h3 class="mt-2 text-lg font-bold text-gray-900">More Than Words</h3>
          <p class="mt-2 text-gray-500 text-sm leading-relaxed">James challenges us to move beyond passive belief into a faith that transforms how we live every day.</p>
          <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
            <span>Rev. Sarah Cole</span>
            <span>Apr 13, 2025</span>
          </div>
          <a href="/sermons" class="mt-4 inline-block text-indigo-600 font-semibold text-sm hover:text-indigo-800">Listen &rarr;</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="bg-gradient-to-br from-indigo-800 to-indigo-900 p-8 flex items-center justify-center">
          <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div class="p-6">
          <span class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">Easter Special</span>
          <h3 class="mt-2 text-lg font-bold text-gray-900">He Is Risen Indeed</h3>
          <p class="mt-2 text-gray-500 text-sm leading-relaxed">The resurrection of Jesus is not just history — it is the living hope that anchors our souls through every storm.</p>
          <div class="mt-4 flex items-center justify-between text-sm text-gray-400">
            <span>Rev. John Matthews</span>
            <span>Apr 6, 2025</span>
          </div>
          <a href="/sermons" class="mt-4 inline-block text-indigo-600 font-semibold text-sm hover:text-indigo-800">Listen &rarr;</a>
        </div>
      </div>

    </div>
  </div>
</section>
HTML,

            // ── 5. Upcoming Events ────────────────────────────────────────────────
            5 => <<<'HTML'
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-12">
      <div>
        <span class="inline-block text-indigo-600 text-sm font-semibold uppercase tracking-widest mb-3">Calendar</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Upcoming Events</h2>
        <p class="mt-3 text-gray-500 text-lg">Stay connected with what's happening in our community.</p>
      </div>
      <a href="/events"
         class="mt-6 sm:mt-0 inline-block border-2 border-indigo-700 text-indigo-700 hover:bg-indigo-100 font-semibold px-6 py-3 rounded-lg transition flex-shrink-0">
        View All Events &rarr;
      </a>
    </div>

    <div class="space-y-4">

      <div class="flex items-start gap-6 p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-indigo-200 transition">
        <div class="flex-shrink-0 w-16 h-16 bg-indigo-700 rounded-xl flex flex-col items-center justify-center text-white shadow">
          <span class="text-xs font-semibold uppercase tracking-wide leading-none">May</span>
          <span class="text-2xl font-extrabold leading-none">4</span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-lg font-bold text-gray-900">Community Prayer Breakfast</h3>
            <span class="text-xs bg-indigo-100 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">Free</span>
          </div>
          <p class="mt-1 text-gray-500 text-sm">Start the month in prayer together. All are welcome. Breakfast provided.</p>
          <p class="mt-2 text-xs text-gray-400">
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            8:00 AM &nbsp;&middot;&nbsp;
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Fellowship Hall
          </p>
        </div>
        <a href="/events" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-1">Details &rarr;</a>
      </div>

      <div class="flex items-start gap-6 p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-indigo-200 transition">
        <div class="flex-shrink-0 w-16 h-16 bg-indigo-600 rounded-xl flex flex-col items-center justify-center text-white shadow">
          <span class="text-xs font-semibold uppercase tracking-wide leading-none">May</span>
          <span class="text-2xl font-extrabold leading-none">11</span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-lg font-bold text-gray-900">Youth Camp Registration Sunday</h3>
            <span class="text-xs bg-yellow-100 text-yellow-700 font-semibold px-2 py-0.5 rounded-full">Youth</span>
          </div>
          <p class="mt-1 text-gray-500 text-sm">Summer camp spaces are filling fast! Register your teen for three days of faith, fun, and growth.</p>
          <p class="mt-2 text-xs text-gray-400">
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            After Morning Service &nbsp;&middot;&nbsp;
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Main Foyer
          </p>
        </div>
        <a href="/events" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-1">Details &rarr;</a>
      </div>

      <div class="flex items-start gap-6 p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-indigo-200 transition">
        <div class="flex-shrink-0 w-16 h-16 bg-indigo-500 rounded-xl flex flex-col items-center justify-center text-white shadow">
          <span class="text-xs font-semibold uppercase tracking-wide leading-none">May</span>
          <span class="text-2xl font-extrabold leading-none">18</span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-lg font-bold text-gray-900">Worship Night</h3>
            <span class="text-xs bg-purple-100 text-purple-700 font-semibold px-2 py-0.5 rounded-full">Worship</span>
          </div>
          <p class="mt-1 text-gray-500 text-sm">An extended evening of music, prayer, and encounter. Come with expectation — leave transformed.</p>
          <p class="mt-2 text-xs text-gray-400">
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            7:00 PM &nbsp;&middot;&nbsp;
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Main Sanctuary
          </p>
        </div>
        <a href="/events" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-1">Details &rarr;</a>
      </div>

      <div class="flex items-start gap-6 p-6 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:border-indigo-200 transition">
        <div class="flex-shrink-0 w-16 h-16 bg-gray-700 rounded-xl flex flex-col items-center justify-center text-white shadow">
          <span class="text-xs font-semibold uppercase tracking-wide leading-none">Jun</span>
          <span class="text-2xl font-extrabold leading-none">1</span>
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-lg font-bold text-gray-900">Annual Church Picnic</h3>
            <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-0.5 rounded-full">Family</span>
          </div>
          <p class="mt-1 text-gray-500 text-sm">Bring the whole family for a day of food, games, and fellowship. Our favourite Sunday of the year!</p>
          <p class="mt-2 text-xs text-gray-400">
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            12:00 PM – 5:00 PM &nbsp;&middot;&nbsp;
            <svg class="inline w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Riverside Park
          </p>
        </div>
        <a href="/events" class="flex-shrink-0 text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-1">Details &rarr;</a>
      </div>

    </div>
  </div>
</section>
HTML,

            // ── 6. Scripture Quote ─────────────────────────────────────────────────
            6 => <<<'HTML'
<section class="py-20 bg-indigo-800">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <svg class="w-12 h-12 mx-auto text-indigo-400 mb-6" fill="currentColor" viewBox="0 0 24 24">
      <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
    </svg>
    <blockquote class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
      "For I know the plans I have for you, declares the Lord, plans to prosper you and not to harm you, plans to give you hope and a future."
    </blockquote>
    <cite class="block mt-8 text-indigo-300 text-lg font-semibold not-italic">— Jeremiah 29:11</cite>
  </div>
</section>
HTML,

            // ── 7. Give / Connect CTA ─────────────────────────────────────────────
            7 => <<<'HTML'
<section class="py-20 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="text-center mb-14">
      <span class="inline-block text-indigo-600 text-sm font-semibold uppercase tracking-widest mb-3">Next Steps</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">How Can We Help You?</h2>
      <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">
        Whether you're new, looking to grow, or ready to give — we have a next step for you.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- New Here -->
      <div class="bg-white rounded-2xl shadow p-8 text-center flex flex-col">
        <div class="w-16 h-16 mx-auto bg-indigo-100 rounded-full flex items-center justify-center mb-5">
          <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">I'm New Here</h3>
        <p class="mt-3 text-gray-500 flex-1">
          Not sure where to start? We'd love to meet you. Fill out a connect card and someone from our team will reach out.
        </p>
        <a href="/contact"
           class="mt-6 block w-full bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-3 rounded-lg shadow transition">
          Say Hello
        </a>
      </div>

      <!-- Prayer -->
      <div class="bg-indigo-700 rounded-2xl shadow p-8 text-center flex flex-col">
        <div class="w-16 h-16 mx-auto bg-indigo-600 rounded-full flex items-center justify-center mb-5">
          <svg class="w-8 h-8 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white">Request Prayer</h3>
        <p class="mt-3 text-indigo-200 flex-1">
          You don't have to carry it alone. Our prayer team is here for you — submit a request and we will pray with you.
        </p>
        <a href="/prayer"
           class="mt-6 block w-full bg-white text-indigo-700 hover:bg-indigo-100 font-semibold py-3 rounded-lg shadow transition">
          Submit Request
        </a>
      </div>

      <!-- Give -->
      <div class="bg-white rounded-2xl shadow p-8 text-center flex flex-col">
        <div class="w-16 h-16 mx-auto bg-yellow-100 rounded-full flex items-center justify-center mb-5">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900">Give Online</h3>
        <p class="mt-3 text-gray-500 flex-1">
          Your generosity makes this ministry possible. Give securely online and make a difference in our community and beyond.
        </p>
        <a href="/contact"
           class="mt-6 block w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg shadow transition">
          Give Now
        </a>
      </div>

    </div>
  </div>
</section>
HTML,

        ];
    }
}
