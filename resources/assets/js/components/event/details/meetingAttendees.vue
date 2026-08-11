<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.event_tab == 3?'block' :'hidden']">
        <div class="flex lg:justify-end w-full px-3"> 
            <div class="flex flex-row lg:flex-col md:flex-col">
                <a :href="url+'/admin/event/'+event_id+'/attendance'" class="text-white text-xs flex items-center blue-bg rounded px-2 py-1 ml-2 font-medium">Import Attendance</a>
            </div>   
        </div>
        <div class="w-full">
            <ul class="list-reset flex text-xs profile-tab flex-wrap">
                <li class="px-2 mx-3 py-3" v-bind:class="[{'active' : tab === 'attended'}]">
                    <a href="#" class="text-gray-700 font-medium" @click="setTab('attended')">Attended</a>
                </li>

                <li class="px-2 mx-3 py-3" v-bind:class="[{'active' : tab === 'not_attended'}]">
                    <a href="#" class="text-gray-700 font-medium" @click="setTab('not_attended')">Not Attended</a>
                </li>
            </ul>
            <attendeesList :url="this.url" :event_id="this.event_id"></attendeesList>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";
    import attendeesList from './attendeesList';
    export default {
        props:['url','event_id','expired'],
        components: {
            attendeesList,
        },
        data () {
            return {
                lists:[],
                event_tab:'',
                tab:'attended',
                errors:[],
                success:null,     
            }
        },
        methods:
        {
            setTab(val)
            {
                this.tab=val;
                bus.$emit("dataTab", this.tab);
            }
        },
  
        created()
        {      
            bus.$on("dataEventTab", data => {
                if(data!='')
                {
                    this.event_tab=data;                   
                }
            }); 
            bus.$emit("dataTab", this.tab);
       
            bus.$on("dataTab", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });      
        }
    }
</script>