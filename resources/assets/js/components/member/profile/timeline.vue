<template>
 <div class=" overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==2?'block' :'hidden']">
<div class="flex flex-wrap custom-table mx-3 my-3">
  <table class="w-full">
    <thead class="bg-grey-light">
      <tr class="border-b">
        <th class="text-left text-sm px-2 py-2 text-grey-darker"> Date and Time </th>
        <th class="text-left text-sm px-2 py-2 text-grey-darker"> Action </th>
        <th class="text-left text-sm px-2 py-2 text-grey-darker"> Description </th>
        <th class="text-left text-sm px-2 py-2 text-grey-darker"> IP </th>
      </tr>
    </thead>   
    <tbody v-if="this.user != ''">
      <tr class="border-b" v-for="users in user">
        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ users['created_at'] }}</p></td>
        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ users['log_name'] }}</p></td>
       <td class="py-3 px-2"><p class="font-semibold text-xs">{{ users['description'] }}</p></td>
        <td class="py-3 px-2"><p class="font-semibold text-xs">{{ users['ip'] }}</p></td>
      </tr>
    </tbody>
    <tbody v-if="this.user == ''">
      <tr class="border-b">
        <td colspan="4"><p class="font-semibold text-s" style="text-align: center">No Records Found</p></td>
      </tr>
    </tbody>
  </table>
  <div v-if="this.page_count>1">
 <paginate
   v-model="page"
   :page-count="this.page_count"
   :page-range="3"
   :margin-pages="1"
   :click-handler="getData"
   :prev-text="'<'"
   :next-text="'>'"
   :container-class="'pagination'"
   :page-class="'page-item'"
   :prev-link-class="'prev'"
   :next-link-class="'next'">
 </paginate>
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
          profile_tab:'',
          user:[],
          errors:[],
          success:null, 
          total: 0,
          page: 1,
          page_count: 0,
        }
      },

       methods:{

        getData(page=1)
          {
            axios.get('/admin/member/show/activity/'+this.name+'?page='+page).then(response => {
              this.user = response.data.data;
              this.page_count = response.data.meta.last_page;
              this.total = response.data.meta.total;
              //console.log(this.user);   
            });
          }

       },
  
      created()
      {   
        //alert(this.user);
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