<script>
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import timeGridPlugin from '@fullcalendar/timegrid'

    export default {
        components: { FullCalendar },
        props: ['events', 'url', 'count', 'mode'],
        data() {
            return {
                activeView: 'table',
                eventList: JSON.parse(this.events),
                calendarOptions: {
                    plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin ],
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    initialView: 'dayGridMonth',
                    events: this.url + '/' + this.mode + '/events/show',
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    weekends: true,
                    select: this.handleDateSelect,
                    eventClick: this.handleEventClick,
                    eventsSet: this.handleEvents
                },
            }
        },
        computed: {
            sortedEvents() {
                return [...this.eventList].sort((a, b) => new Date(a.start) - new Date(b.start));
            }
        },
        methods: {
            openDetail(id) {
                if (window.openEventPopup) window.openEventPopup(id);
            },
            formatDate(dateStr) {
                if (!dateStr) return '—';
                const d = new Date(dateStr);
                return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            },
            formatTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });
            },
            typeBadge(type) {
                const map = {
                    public:  'bg-green-100 text-green-700',
                    private: 'bg-gray-100 text-gray-600',
                    online:  'bg-blue-100 text-blue-700',
                };
                return map[type] || 'bg-gray-100 text-gray-500';
            },
            handleDateSelect() {},
            handleEventClick(clickInfo) {
                if (window.openEventPopup) window.openEventPopup(clickInfo.event.id);
            },
            handleEvents() {}
        }
    }
</script>

<template>
    <div>
        <!-- View toggle -->
        <div class="flex items-center gap-2 mb-4">
            <button @click="activeView = 'table'"
                    :class="activeView === 'table' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 transition">
                <i class="fas fa-list text-xs"></i>
                <span>Table</span>
            </button>
            <button @click="activeView = 'calendar'"
                    :class="activeView === 'calendar' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="text-sm px-3 py-1.5 rounded flex items-center gap-1.5 transition">
                <i class="fas fa-calendar-alt text-xs"></i>
                <span>Calendar</span>
            </button>
        </div>

        <!-- Table view -->
        <div v-if="activeView === 'table'">
            <div v-if="sortedEvents.length === 0" class="text-center py-16 text-gray-400">
                <i class="fas fa-calendar-times text-4xl mb-3 block"></i>
                <p class="text-sm">No events yet.</p>
            </div>
            <table v-else class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <th class="px-5 py-3 text-left">Title</th>
                        <th class="px-5 py-3 text-left">Date</th>
                        <th class="px-5 py-3 text-left">Category</th>
                        <th class="px-5 py-3 text-left">Location</th>
                        <th class="px-5 py-3 text-left">Type</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="event in sortedEvents" :key="event.id" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <p class="font-medium text-gray-800">{{ event.title }}</p>
                            <p v-if="event.organised_by" class="text-xs text-gray-400 mt-0.5">{{ event.organised_by }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
                            <p>{{ formatDate(event.start) }}</p>
                            <p class="text-xs text-gray-400">{{ formatTime(event.start) }}</p>
                        </td>
                        <td class="px-5 py-4 text-gray-600 capitalize">{{ event.category || '—' }}</td>
                        <td class="px-5 py-4 text-gray-500 max-w-xs truncate">{{ event.location || '—' }}</td>
                        <td class="px-5 py-4">
                            <span :class="typeBadge(event.select_type)"
                                  class="text-xs font-medium px-2 py-0.5 rounded-full capitalize">
                                {{ event.select_type || '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <button @click="openDetail(event.id)"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">
                                    View
                                </button>
                                <a :href="url + '/' + mode + '/events/' + event.id + '/edit'"
                                   class="text-xs text-gray-500 hover:text-gray-700 font-medium transition">
                                    Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Calendar view -->
        <div v-if="activeView === 'calendar'">
            <FullCalendar :options='calendarOptions'></FullCalendar>
        </div>
    </div>
</template>

<style>
    .hide-menu {
        display: none;
    }
</style>
