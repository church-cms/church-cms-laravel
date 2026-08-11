<template>
 <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto" v-bind:class="[this.profile_tab==5?'block' :'hidden']">
   <h3 class="font-semibold text-base text-gray-800 capitalize m-2">Volunteer Opportunities</h3>
      <div class="my-5">
       <div class="flex mx-2 my-3 items-center ">
         <div class="">
           <img :src="this.url+'/uploads/admin/member/avatar/council.jpg'" class="w-10 h-10">
         </div>
         <div class="w-4/5 mx-4 border rounded px-2 py-2 ">
         <div class="flex justify-between items-center">
         <div class="flex items-center w-1/2 cursor-pointer">
         <img :src="this.url+'/uploads/icons/right.svg'" class="w-2 h-2">
           <h4 class="font-semibold text-sm text-gray-700 mx-4">Council</h4>
         </div>
         <div class="w-1/2 flex justify-end">
           <p class="text-xs text-gray-600 flex items-center"><img :src="this.url+'/uploads/icons/clock.svg'" class="w-3 h-3"><span class="mx-1">Started Oct-2016</span></p>
           </div>
         </div>
         </div>
       </div>

  <div class="flex mx-2 my-3 items-center ">
         <div class="">
           <img :src="this.url+'/uploads/admin/member/avatar/deacons.jpg'" class="w-10 h-10">
         </div>
         <div class="w-4/5 mx-4 border rounded px-2 py-2 ">
         <div class="flex justify-between items-center">
         <div class="flex items-center w-1/2 cursor-pointer">
         <img :src="this.url+'/uploads/icons/right.svg'" class="w-2 h-2">
           <h4 class="font-semibold text-sm text-gray-700 mx-4">Deacons</h4>
         </div>
         <div class="w-1/2 flex justify-end">
           <p class="text-xs text-gray-600 flex items-center"><img :src="this.url+'/uploads/icons/clock.svg'" class="w-3 h-3"><span class="mx-1">Started Apr-2016</span></p>
           </div>
         </div>
         </div>
       </div>

       <div class="flex mx-2 my-3 items-center ">
         <div class="">
           <img :src="this.url+'/uploads/admin/member/avatar/choir.jpg'" class="w-10 h-10">
         </div>
         <div class="w-4/5 mx-4 border rounded px-2 py-2 ">
         <div class="flex justify-between items-center">
         <div class="flex items-center w-1/2 cursor-pointer">
         <img :src="this.url+'/uploads/icons/right.svg'" class="w-2 h-2">
           <h4 class="font-semibold text-sm text-gray-700 mx-4">Choir</h4>
         </div>
         <div class="w-1/2 flex justify-end">
           <p class="text-xs text-gray-600 flex items-center"><img :src="this.url+'/uploads/icons/clock.svg'" class="w-3 h-3"><span class="mx-1">Started Nov-2016</span></p>
           </div>
         </div>
         </div>
       </div>

       </div>
        <!--  <table class="profiletab-table w-full overflow-x-auto">
          <thead>
           <tr>
           <th>Family Members volunteer</th>
           <th>Role</th>
           <th>Birth Date</th>
            <th>Email</th>
            <th>Actions</th>
            </tr>
           </thead>
            <tbody>
             <tr>
             <td>
              <div class="flex items-center">
              <img :src="this.url+'/uploads/user/avatar/user.jpeg'" class="rounded-full w-8 h-8"><a href="#" class="mx-2 text-blue-600 hover:text-blue-400">celine.heathcote</a>
              </div>
                     </td>
                     <td>Child</td>
                     <td>23/09/1997</td>
                     <td><a href="#" class="text-blue-600 hover:text-blue-400">demouser@gmail.com</a></td>
                     <td>
                     <ul class="list-reset flex">
                       <li><a href="#"><img :src="this.url+'/uploads/icons/pencil.svg'" class="w-3 h-3"></a></li>
                       <li class="mx-2"><a href="#" ><img :src="this.url+'/uploads/icons/delete.svg'" class="w-3 h-3"></a></li>
                     </ul>
                     </td>
                    </tr>
                     <tr>
                        <td>
                        <div class="flex items-center">
                        <img :src="this.url+'/uploads/user/avatar/user_3.jpg'" class="rounded-full w-8 h-8">
                        <a href="#" class="mx-2 text-blue-600 hover:text-blue-400">oconner.karl</a>
                        </div>
                        </td>
                        <td>Child</td>
                        <td>12/03/1999</td>
                        <td><a href="#" class="text-blue-600 hover:text-blue-400">demouser@gmail.com</a></td>
                        <td>
                        <ul class="list-reset flex">
                        <li><a href="#"><img :src="this.url+'/uploads/icons/pencil.svg'" class="w-3 h-3"></a></li>
                        <li class="mx-2"><a href="#" ><img :src="this.url+'/uploads/icons/delete.svg'" class="w-3 h-3"></a></li>
                        </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <div class="flex items-center">
                        <img :src="this.url+'/uploads/user/avatar/user_1.jpeg'" class="rounded-full w-8 h-8">
                        <a href="#" class="mx-2 text-blue-600 hover:text-blue-400">idaugherty</a>
                        </div>
                        </td>
                        <td>Father</td>
                        <td>01/03/1967</td>
                        <td><a href="#" class="text-blue-600 hover:text-blue-400">demouser@gmail.com</a></td>
                        <td>
                        <ul class="list-reset flex">
                        <li><a href="#"><img :src="this.url+'/uploads/icons/pencil.svg'" class="w-3 h-3"></a></li>
                        <li class="mx-2"><a href="#" ><img :src="this.url+'/uploads/icons/delete.svg'" class="w-3 h-3"></a></li>
                        </ul>
                        </td>
                    </tr>
                </tbody>
            </table> -->
        </div>
        </template>
<script>
import { bus } from "../../../app";

  export default {
      props:['url'],
      data () {
         return {
               profile_tab:'',
              
             }
       },
  
      created()
      {       
          bus.$on("dataProfileTab", data => {
          if(data!='')
            {
              this.profile_tab=data;                   
          
            }
           });

          
      }
  
 
  
  }

  
</script>