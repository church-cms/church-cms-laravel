@extends('theme::layout')

@section('title', 'Events')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Upcoming Events --}}
    <section class="mb-14">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
            Upcoming Events
        </h2>

        @if($upcoming->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($upcoming as $event)
            <a href="{{ route('web.event', $event->id) }}" class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden transition">
                @if($event->image)
                    <img src="{{ \Storage::url($event->image) }}" alt="{{ $event->title }}" class="w-full h-44 object-cover">
                @else
                    <div class="w-full h-44 bg-green-50 flex items-center justify-center text-green-300 text-5xl">&#128197;</div>
                @endif
                <div class="p-4">
                    @if($event->category)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $event->category }}</span>
                    @endif
                    <h3 class="font-semibold text-gray-800 mt-2">{{ $event->title }}</h3>
                    @if($event->location)
                        <p class="text-xs text-gray-400 mt-1">&#128205; {{ $event->location }}</p>
                    @endif
                    <p class="text-xs text-indigo-600 mt-1">
                        @if($event->allDay)
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} (All Day)
                        @else
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, g:i A') }}
                        @endif
                    </p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $upcoming->appends(request()->except('upcoming_page'))->links() }}</div>
        @else
            <p class="text-gray-500">No upcoming events.</p>
        @endif
    </section>

    {{-- Completed Events --}}
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span class="inline-block w-3 h-3 rounded-full bg-gray-400"></span>
            Completed Events
        </h2>

        @if(count($completed))

        {{-- Build a flat JS object: { "2026::April": [...], ... } for Alpine --}}
        <script>
            window.__completedEvents = {
                @foreach($completed as $year => $months)
                    @foreach($months as $month => $events)
                        "{{ $year }}::{{ $month }}": [
                            @foreach($events as $event)
                            {
                                id: {{ $event->id }},
                                title: @json($event->title),
                                location: @json($event->location),
                                image: @json($event->image ? \Storage::disk('public')->url($event->image) : null),
                                date: "{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y, g:i A') }}"
                            },
                            @endforeach
                        ],
                    @endforeach
                @endforeach
            };
            window.__eventRouteBase = "{{ url('/event') }}";
        </script>

        <div x-data="{
                openYear: '{{ array_key_first($completed) }}',
                selectedYear: '{{ array_key_first($completed) }}',
                selectedMonth: '{{ array_key_first(reset($completed)) }}',
                get key() { return this.selectedYear + '::' + this.selectedMonth; },
                get events() { return window.__completedEvents[this.key] || []; },
                selectMonth(year, month) {
                    this.selectedYear = year;
                    this.selectedMonth = month;
                }
             }"
             class="flex gap-4">

            {{-- LEFT: Year / Month Nav --}}
            <div class="w-1/4 bg-gray-50 border border-gray-200 rounded-xl overflow-y-auto flex-shrink-0" style="max-height:600px;">
                @foreach($completed as $year => $months)
                <div>
                    {{-- Year header --}}
                    <button @click="openYear = openYear === '{{ $year }}' ? null : '{{ $year }}'"
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-100 border-b border-gray-200 focus:outline-none"
                            :class="openYear === '{{ $year }}' ? 'bg-gray-100' : ''">
                        <span>{{ $year }}</span>
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <span>{{ collect($months)->sum(fn($e) => count($e)) }}</span>
                            <span x-text="openYear === '{{ $year }}' ? '▲' : '▼'"></span>
                        </span>
                    </button>

                    {{-- Months --}}
                    <div x-show="openYear === '{{ $year }}'" x-collapse>
                        @foreach($months as $month => $events)
                        <button @click="selectMonth('{{ $year }}', '{{ $month }}')"
                                class="w-full flex items-center justify-between px-6 py-2 text-sm border-b border-gray-100 focus:outline-none transition"
                                :class="selectedYear === '{{ $year }}' && selectedMonth === '{{ $month }}'
                                    ? 'bg-indigo-600 text-white font-semibold'
                                    : 'text-gray-600 hover:bg-indigo-50'">
                            <span>{{ $month }}</span>
                            <span class="text-xs opacity-70 rounded-full px-1.5 py-0.5"
                                  :class="selectedYear === '{{ $year }}' && selectedMonth === '{{ $month }}'
                                      ? 'bg-indigo-500 text-white'
                                      : 'bg-gray-200 text-gray-500'">
                                {{ count($events) }}
                            </span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            {{-- RIGHT: Events panel --}}
            <div class="w-3/4 bg-white border border-gray-200 rounded-xl overflow-y-auto p-6" style="max-height:600px;">

                {{-- Panel header --}}
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-indigo-600" x-text="selectedMonth"></span>
                    <span x-text="selectedYear"></span>
                    <span class="text-xs font-normal text-gray-400 ml-1" x-text="'— ' + events.length + ' events'"></span>
                </h3>

                {{-- Event cards --}}
                <template x-if="events.length === 0">
                    <p class="text-sm text-gray-400">No events for this month.</p>
                </template>

                <div class="grid grid-cols-1 gap-3">
                    <template x-for="event in events" :key="event.id">
                        <a :href="window.__eventRouteBase + '/' + event.id"
                           class="flex gap-3 bg-gray-50 hover:bg-white border border-gray-100 hover:border-indigo-200 rounded-lg shadow-sm hover:shadow transition overflow-hidden">
                            {{-- Thumbnail --}}
                            <div class="w-20 flex-shrink-0">
                                <template x-if="event.image">
                                    <img :src="event.image" :alt="event.title"
                                         class="w-20 object-cover grayscale hover:grayscale-0 transition" style="height:80px;">
                                </template>
                                <template x-if="!event.image">
                                    <div class="w-20 bg-gray-100 flex items-center justify-center text-gray-300 text-2xl" style="height:80px;">&#128197;</div>
                                </template>
                            </div>
                            {{-- Details --}}
                            <div class="flex flex-col justify-center py-2 pr-3 flex-1 min-w-0">
                                <span class="text-sm font-semibold text-gray-800 leading-snug line-clamp-2" x-text="event.title"></span>
                                <span class="text-xs text-indigo-500 mt-1" x-text="event.date"></span>
                                <template x-if="event.location">
                                    <span class="text-xs text-gray-400 truncate" x-text="'📍 ' + event.location"></span>
                                </template>
                            </div>
                        </a>
                    </template>
                </div>

            </div>
        </div>

        @else
            <p class="text-gray-500">No completed events.</p>
        @endif
    </section>

</div>
@endsection
