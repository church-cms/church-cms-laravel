<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap" v-bind:class="[this.profile_tab==1?'block' :'hidden']">
        <div>
            <ul class="list-reset leading-loose my-2 text-xs">
                <li class="flex py-1">
                    <div class="flex items-center">
                        <img :src="this.url+'/uploads/icons/age.svg'" class="w-3 h-3">
                        <span class="text-gray-700 font-medium mx-2">Age : </span>
                    </div>
                    <div>
                        <p v-if="this.user.age != null">{{ this.user.age }}</p>
                        <p v-else> -- </p>
                    </div>
                </li>

                <!-- <li class="flex py-1" v-if="mode == 'member'">
                    <div class="flex items-center">
                        <img :src="this.url+'/uploads/icons/baptism.svg'" class="w-3 h-3">
                        <span class="text-gray-700 font-medium mx-2">Was Baptized :</span>
                    </div>
                    <div>
                        <p v-if="this.user.was_baptized != null">{{ this.user.was_baptized }}</p>
                        <p v-else> -- </p>
                    </div>
                </li>
                <li class="flex py-1" v-if="mode == 'member'">
                    <div class="flex items-center">
                        <img :src="this.url+'/uploads/icons/date.svg'" class="w-3 h-3">
                        <span class="text-gray-700 font-medium mx-2">Baptism Date : </span>
                    </div>
                    <div>
                        <p v-if="this.user.was_baptized=='yes'">{{ this.user.baptism_date }}</p>
                        <p v-else> -- </p>
                    </div>
                </li>-->
            </ul>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";
    import TreeChart from "vue-tree-chart";
    export default {
        props:['url' , 'name' , 'mode'],
        data () {
            return {
               profile_tab:'1',
               user:[],
               errors:[],
               success:null,
              
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/guest/show/details/'+this.name).then(response => {
                     this.user = response.data.data[0];
                    //console.log(this.user);
                });
            }
        },
  
        created()
        {      
            this.getData(); 

            bus.$on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });
        }
    }
</script>