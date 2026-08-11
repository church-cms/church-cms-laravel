<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'pending'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('pending')">Pending</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'approve'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('approve')">Approved</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'cancel'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('cancel')">Cancelled</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'closed'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('closed')">Closed</a>
            </li>
        </ul>

        <portal to="list_prayer">
        <div class="p-2">
            <List :url="this.url"></List>
        </div>
        </portal>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../app";
    import List from './List';

    export default {
        props:['url'],
        data () {
            return {
                status:'pending',     
            }
        },
        components: {
            List,
        },

        methods:
        {
            setTab(val)
            {
                this.status=val;
                bus.$emit("statusTab", this.status);
            }
        },

        created()
        {
            bus.$emit("statusTab", this.status);
       
            bus.$on("statusTab", data => {
                if(data!='')
                {
                    this.status=data;                   
                }
            });
        }
    }
</script>