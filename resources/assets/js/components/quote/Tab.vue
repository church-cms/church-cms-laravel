<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : tab === 'published'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setTab('published')">Published</a>
            </li>

            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : tab === 'upcoming'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setTab('upcoming')">Upcoming</a>
            </li>
        </ul>
        <portal to="quote_tab">
            <list :url="this.url"></list>
        </portal>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../app";
    import list from "./List";

    export default {
        props:['url'],
        data () {
            return {
               tab:'published',
            }
        },
        components: {
            list
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