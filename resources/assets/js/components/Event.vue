<template>
    <div class="">
        <div class="container mx-auto">
            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="w-full p-2">
                    <ul class="list-reset" v-for="event in events" v-bind:key="event.id">
                        <li class="mt-2 bg-light  border-l-4 border-teal-400 rounded">
                            <div class="flex items-center">
                                <div class="w-1/4 py-1  text-center leading-tight">
                                    <p class="text-gray-600 text-3xl font-bold ">{{ event['date'] }}</p>
                                    <p class="text-xs text-gray-600 uppercase">{{ event['month'] }}</p>
                                </div>
                                <div class="w-3/4 ml-2 leading-relaxed">
                                    <a :href="url+'/admin/events/show/details/'+event['id']" class="font-semibold text-sm py-1">{{event['title']}}</a>
                                    <p class="text-xs">{{event['category']}}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="py-2" v-if="this.events == ''">
                        <p class="font-semibold text-sm" style="text-align: center;">No Records Found</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                events:[],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/dashboard/event').then(response => {
                    this.events = response.data.data;
                    //console.log(this.events);
                });
            }
        },

        created()
        {
            this.getData();
        }
    }
</script>
