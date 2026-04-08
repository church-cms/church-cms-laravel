<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : type === 'audio'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('audio')">Audio</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : type === 'video'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('video')">Video</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : type === 'image'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setTab('image')">Images</a>
            </li>
        </ul>

        <portal to="list_mediafile">
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
                type:'audio',
            }
        },
        components: {
            List,
        },

        methods:
        {
            setTab(val)
            {
                this.type=val;
                bus.$emit("typeTab", this.type);
            }
        },

        created()
        {
            bus.$emit("typeTab", this.type);

            bus.$on("typeTab", data => {
                if(data!='')
                {
                    this.type=data;
                }
            });
        }
    }
</script>
