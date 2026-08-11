<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="">
            <div class="flex-wrap custom-table overflow-auto">
                <div class="">
                    <div class="flex flex-wrap">
                        <div class="w-full xl:w-1/4 lg:w-1/3 md:w-1/2 my-2 relative" v-for="list in lists">
                            <div class="flex justify-between member-list">
                                <div class="flex items-center student_select">
                                </div>
                                <div class="flex p-2 active w-full">
                                    <div class="px-2">
                                        <h2 class="font-bold text-base text-gray-700">
                                            <a :href="url+'/admin/member/show/'+list.user_name">{{ list.user_fullname }}</a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-if="Object.keys(lists).length == 0" class="text-sm text-gray-700 mx-6">No Records Found</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";
    export default {
        props:['url' , 'event_id'],
        data () {
            return {
                lists:[],
                tab:'attended',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/event/showAttendees/'+this.event_id+'/'+this.tab).then(response => {
                    this.lists    = response.data.data;
                    //console.log(this.lists);    
                });
            }
        },
  
        created()
        {   
            this.getData(); 
            bus.$on("dataTab", data => {
                if(data!='')
                {
                    this.tab=data;      
                    this.getData();             
                }
            });
        }
    }
</script>