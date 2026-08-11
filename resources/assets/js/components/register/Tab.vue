<template>
    <div>
        <ul id="progressbar">
            <li class="active w-1/2 lg:w-2/5 md:w-2/5">
                <span class="ml-3">Church Info</span>
            </li>

            <li class="w-1/2 lg:w-2/5 md:w-2/5" style="margin-left: -45px;" v-bind:class="[{'active' : tab === '2'}]" >
                <span class="ml-3">Login Info</span>
            </li>
        </ul>

        <portal to="register_tab">
            <stepOne :url="this.url"></stepOne>
            <stepTwo :url="this.url"></stepTwo>
        </portal>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../app";
    import stepOne from './stepOne';
    import stepTwo from './stepTwo';

    export default {
        props:['url'],
        data () {
            return {
                tab:1,     
            }
        },
        components: {
            stepOne,
            stepTwo,
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