<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="flex flex-row custom-table overflow-x-auto">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-b">
                        <th width="90%" class="text-left text-sm px-2 py-2 text-grey-darker">Subject</th>
                        <th width="10%" class="text-left text-sm px-2 py-2 text-grey-darker" style="width:10%;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-grey-light" v-if="Object.keys(this.emails).length > 0">
                    <tr class="border-b" v-for="email in emails">
                        <td class="py-3 px-2">{{ email.subject }}</td>
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <a :href="url+'/'+mode+'/email/edit/'+email.id" class="" title="Edit">
                                    <img :src="url+'/uploads/icons/edit1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                                <a :href="url+'/'+mode+'/email/show/'+email.id" class="" title="Show"> 
                                    <img :src="url+'/uploads/icons/show.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                                <a href="#" @click="deleteEmail(email.id)" class="delete" title="Delete">
                                    <img :src="url+'/uploads/icons/delete1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                            </div>
                        </td>
                    </tr>
               </tbody>
               <tbody class="bg-grey-light" v-else>
                    <tr class="border-t-2 border-b-2">    
                        <td colspan="2" class="py-3 px-2">
                            <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                        </td>
                    </tr>
               </tbody>
            </table>      
        </div>
    </div>
</template>

<script>
    export default {
        props:['url','mode'],
        data () {
            return {
                emails:[],          
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/email/list').then(response => {
                    this.emails        = response.data.data;
                    //console.log(this.emails);   
                });
            },

            deleteEmail(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Email ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+'/'+thisswal.mode+'/email/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        }); 
                    }
                    else 
                    {
                        swal("Cancelled");
                    }
                });
            },
        },
  
        created()
        {   
            this.getData();
        }
    }
</script>