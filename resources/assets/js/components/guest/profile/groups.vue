<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto" v-bind:class="[this.profile_tab==4?'block' :'hidden']">
    <h3 class="font-semibold text-base text-gray-800 capitalize m-2">Groups</h3>
    <div class="my-5">
      <div class="flex mx-2 my-3 items-start" v-for="group in groups">
        <div class="">
          <img :src="group['cover_image']" class="w-10 h-10">
        </div>
        <div class="w-4/5 mx-4 border rounded px-2 py-2">
          <div class="flex justify-between items-center">
            <div class="flex items-center w-1/2 cursor-pointer ">
              <h4 class="font-semibold text-sm text-gray-700 mx-4">
                <a :href="url+'/admin/group/show/'+group['group_id']">{{ group['name'] }}</a>
              </h4>
            </div>
            <div class="w-1/2 flex justify-end">
              <p class="text-xs text-gray-600 flex items-center">
                <img v-bind:src="url+'/uploads/icons/clock.svg'" class="w-3 h-3">
                <span class="mx-1">Started {{ group['started'] }}</span>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { bus } from "../../../app";

  export default {
      props:['url','name'],
      data () {
        return {
          groups:[],
          profile_tab:'',
          errors:[],
          success:null,     
        }
      },
      methods:
      {
        getData()
          {
            axios.get('/admin/member/show/group/'+this.name).then(response => {
            this.user = response.data.data;
            this.setData();  
            });
          },

        setData()
        {
          if(Object.keys(this.user).length>0)
          {
            this.groups = this.user;
            //console.log(this.groups);
          }
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