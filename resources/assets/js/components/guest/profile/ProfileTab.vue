<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '1'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('1')">My Profile</a>
            </li>

            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '2'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('2')">Timeline</a>
            </li>
            <!-- <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '7'}]" >
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('7')">Family tree</a>
            </li>
 
            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '3'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('3')">Family</a>
            </li> -->

            <!-- <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '4'}]" v-if="type == 'member'">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('4')">Assigned Groups</a>
            </li> -->

            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '8'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('8')">Messages</a>
            </li>

            <!-- <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '5'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('5')">Volunteer Opportunities</a>
            </li> -->
 
            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '6'}]">
                <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('6')">Notes</a>
            </li>
        </ul>
        <portal to="guest_profile">
            <myprofile :url="this.url" :name="this.name" :mode="this.mode"></myprofile>
            <timeline :url="this.url" :name="this.name"></timeline>
            <family :url="this.url" :name="this.name"></family>
            <familytree :url="this.url" :name="this.name"></familytree>
            <groups :url="this.url" :name="this.name"></groups>
            <messages :url="this.url" :name="this.name"></messages>
            <volunteer :url="this.url" :name="this.name"></volunteer>
            <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==6?'block' :'hidden']">
                <notes :url="this.url" :entity_id="this.entity_id" entity_name="user" :church_id="this.church_id"></notes>
            </div>
        </portal>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    import { bus } from "../../../app";
    import notes from '../../notes';
    import myprofile from './myprofile';
    import timeline from './timeline';
    import groups from './groups';
    import messages from './messages';
    import volunteer from './volunteer';
    import family from './family';
    import familytree from './familytree';

    export default {
        props:['url','name','entity_id','church_id','type','mode'],
        data () {
            return {
               profile_tab:'1',
            }
        },
        components: {
            myprofile,
            timeline,
            notes,
            groups,
            messages,
            volunteer,
            family,
            familytree,
        },
        methods:
        {
            setProfileTab(val)
            {
                this.profile_tab=val;
                bus.$emit("dataProfileTab", this.profile_tab);
            }
        },

        created()
        {
            bus.$emit("dataProfileTab", this.profile_tab);
       
            bus.$on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
           });
        }
    }
</script>