<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==3?'block' :'hidden']">
        <table class="profiletab-table w-full overflow-x-auto">
            <thead>
                <tr>
                    <th>Family Members</th>
                    <th>Relation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in members">
                    <td>
                        <div class="flex items-center">
                            <img :src="user.avatar" class="rounded-full w-8 h-8">
                            <a :href="'/admin/member/show/'+user.name" class="mx-2 text-blue-600 hover:text-blue-400">{{ user.fullname }}</a>
                        </div>
                    </td>
                    <td>{{ user.relation }}</td>
                    <td>
                        <ul class="list-reset flex">
                            <li>
                                <a :href="url+'/admin/member/edit/'+user.name">
                                    <img :src="url+'/uploads/icons/pencil.svg'" class="w-3 h-3">
                                </a>
                            </li>
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
                members:[],
                errors:[],
                success:null, 
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/member/show/family/'+this.name).then(response => {
                    this.members = response.data.members;
                    //console.log(this.members);  
                });
            }
        },
  
        created()
        {       
            this.getData();

            bus.$on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab = data;                   
                }
            });   
        }
    } 
</script>