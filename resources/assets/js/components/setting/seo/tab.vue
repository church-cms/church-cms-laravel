<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-3 mx-1 py-2" v-bind:class="[{'active' : tab === 1}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab(1)">Basic</a>
            </li>

            <li class="px-3 mx-1 py-2" v-bind:class="[{'active' : tab === 2}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab(2)">Advanced</a>
            </li>
        </ul>

        <portal to="seo_tab">
            <basic :url="this.url"></basic>
            <advanced :url="this.url"></advanced>
        </portal>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../../app";
    import basic from './basic';
    import advanced from './advanced';

    export default {
        props:['url'],
        data () {
            return {
                tab:1,     
            }
        },
        components: {
            basic,
            advanced,
        },

        methods:
        {
            setTab(val)
            {
                this.tab=val;
                bus.$emit("tabValue", this.tab);
            }
        },

        created()
        {
            bus.$emit("tabValue", this.tab);
       
            bus.$on("tabValue", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });   
        }
    }
</script>