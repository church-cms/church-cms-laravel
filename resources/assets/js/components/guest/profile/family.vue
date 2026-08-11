<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==3?'block' :'hidden']">
        <table class="profiletab-table w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Family Members</th>
                    <th>Relation</th>
                    <th>Birth Date</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="users in user">
                    <td>
                        <div class="flex items-center">
                            <img :src="users.avatar" class="rounded-full w-8 h-8">
                            <a :href="'/admin/member/show/'+users.name" class="mx-2 text-blue-600 hover:text-blue-400">{{ users.fullname }}</a>
                        </div>
                    </td>
                    <td>{{ users.relation }}</td>
                    <td>{{ users.dob }}</td>
                    <td>
                        <a href="#" class="text-blue-600 hover:text-blue-400">{{ users.email }}</a>
                    </td>
                    <td>
                        <ul class="list-reset flex">
                            <li>
                                <a :href="url+'/admin/member/edit/'+users.name">
                                    <img :src="url+'/uploads/icons/pencil.svg'" class="w-3 h-3">
                                </a>
                            </li>
                         <!--  <li class="mx-2">
                            <a href="#">
                              <img :src="'/uploads/icons/delete.svg'" class="w-3 h-3">
                            </a>
                          </li> -->
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
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
                ref_id:'',
                relation:'',
                errors:[],
                success:null, 
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/member/show/family/'+this.name).then(response => {
                    this.user = response.data.data;
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