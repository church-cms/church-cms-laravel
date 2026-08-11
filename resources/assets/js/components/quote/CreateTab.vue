<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : tab === 'images'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setTab('images')">Image</a>
            </li>

            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : tab === 'text'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setTab('text')">Text</a>
            </li>

        </ul>
        <div v-if="this.mode == 'add'">
            <portal to="add_quote_tab">
                <create :url="this.url" :publish_on="this.publish_on"></create>
            </portal>
        </div>
        <div v-if="this.mode == 'edit'">
            <portal to="edit_quote_tab">
                <edit :url="this.url" :id="this.id"></edit>
            </portal>
        </div>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../app";
    import create from "./Create";
    import edit from "./Edit";

    export default {
        props:['url' , 'publish_on' , 'mode' , 'id'],
        data () {
            return {
               tab:'image',
            }
        },
        components: {
            create,
            edit
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