<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="flex flex-row justify-between custom-table overflow-x-auto">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-b">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Email</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subscriber</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Campaign</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Mailing List</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Scheduled At</th>
                        <th width="10%" class="text-left text-sm px-2 py-2 text-grey-darker" style="width:10%;">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-grey-light" v-if="Object.keys(this.mailQueueList).length > 0">
                    <tr class="border-b" v-for="mailqueue in mailQueueList">
                        <td class="py-3 px-2">{{ mailqueue.email }}</td>
                        <td class="py-3 px-2">{{ mailqueue.subscriber_email }}</td>
                        <td class="py-3 px-2">{{ mailqueue.campaign }}</td>
                        <td class="py-3 px-2">{{ mailqueue.mailinglist }}</td>
                        <td class="py-3 px-2">{{ mailqueue.scheduled_at }}</td>
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <a :href="url+'/'+mode+'/mailqueue/edit/'+mailqueue.id" title="Edit">
                                    <img :src="url+'/uploads/icons/edit1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                                <a :href="url+'/'+mode+'/mailqueue/show/'+mailqueue.id" title="Show"> 
                                    <img :src="url+'/uploads/icons/show.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                                <a href="#" @click="deleteMailQueue(mailqueue.id)" title="Delete">
                                    <img :src="url+'/uploads/icons/delete1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody class="bg-grey-light" v-else>
                    <tr class="border-t-2 border-b-2">    
                        <td colspan="6" class="py-3 px-2">
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
                mailQueueList:[],          
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/mailqueue/list').then(response => {
                    this.mailQueueList = response.data.data;
                    //console.log(this.mailQueueList);   
                });
            },

            deleteMailQueue(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this MailQueue ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+'/'+thisswal.mode+'/mailqueue/delete/'+ id).then(response => {
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