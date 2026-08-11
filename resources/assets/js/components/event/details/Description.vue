<template>
 <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.event_tab==1?'block' :'hidden']">
     
 <div class="my-2">
 <p class="text-sm text-gray-800 leading-loose">{{ this.description }}</p>
 </div>
        </div>
        </template>
<script>
import { bus } from "../../../app";

  export default {
      props:['url','id'],
      data () {
         return {
               event_tab:'1',
               event:[],
               description:'', 
               errors:[],
               success:null,              
             }
       },

       methods:{

        getData()
          {
            axios.get('/admin/events/showdetails/'+this.id).then(response => {
            this.event = response.data.data[0];
            this.description=this.event.description;
            //console.log(this.event);
              
            });
          }

       },
  
      created()
      {    
      //alert(this.id);  
      this.getData();  
          bus.$on("dataEventTab", data => {
          if(data!='')
            {
              this.event_tab=data;                   
          
            }
           });

          
      }
  
 
  
  }

  
</script>