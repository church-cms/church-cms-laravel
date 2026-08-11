<template>
 <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.event_tab==2?'block' :'hidden']">
 <!-- <div class="my-1 mx-2">
 <h2 class="text-gray-700 text-lg font-semibold italic">Photos</h2>
 </div> -->
 <div class="my-3 w-1/6">
<!--  <ul class="flex flex-wrap">
  <li class="">
  <div class="border w-32 h-32 p-2 mx-2">
  <img :src="this.url+'/uploads/events/noimage.png'" class="w-full h-full cursor-pointer">
  </div>
    <p class="mx-2 flex justify-between text-gray-900 font-semibold py-1">06-08-2019<span>(10)</span></p>
  </li>
</ul> -->
<showimage :url="this.url" :event_id="this.event_id"></showimage>
 </div>
        </div>
        </template>
<script>
import { bus } from "../../../app";

export default {
      props:['url','event_id'],
      data () {
         return {
               event_tab:'',
               user:[],
               updated_at:'',
               notes:'',
               errors:[],
               success:null,              
             }
       },


  
      created()
      {    

          //alert(this.event_id);
        bus.$on("dataEventTab", data => {
          if(data!='')
            {
              this.event_tab=data;                   
          
            }
           });

          
      }
  
 
  
  }

  
</script>