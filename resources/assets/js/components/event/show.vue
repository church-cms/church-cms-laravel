<script>
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import timeGridPlugin from '@fullcalendar/timegrid'
    import { INITIAL_EVENTS, createEventId } from './event-utils'
    import { bus } from "../../app";
    import PortalVue from "portal-vue";
    export default {
        components: {
            FullCalendar // make the <FullCalendar> tag available
        },
        props:['events'],
        data() {
            return {
                calendarOptions: {
                    plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin ],
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    initialView: 'dayGridMonth',
                    initialEvents: JSON.parse(this.events),
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
        methods:
        {
            handleWeekendsToggle()
            {
                this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
            },

            handleDateSelect(selectInfo)
            {
                /* let title = prompt('Please enter a new title for your event')

                this.errors=[];
                this.success=null;
                let formData=new FormData();

                formData.append('title',title);

                axios.post('/admin/events/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.success=response.data.success;
                    this.showEvents=0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });*/
            },

            handleEventClick(clickInfo)
            {
                this.success=null;
                bus.$emit("dataEventDetail", clickInfo.event.id);
            },

            handleEvents(events)
            {

            }
        }
    }
</script>

<template>
    <FullCalendar :options='calendarOptions'></FullCalendar>
</template>

<style>
    .hide-menu {
        display: none;
    }
</style>
