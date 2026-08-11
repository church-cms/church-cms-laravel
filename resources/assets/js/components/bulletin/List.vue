<template>
    <div class="relative">
        <portal to="add_bulletin">
            <div class="flex flex-wrap lg:flex-row items-center mb-5 justify-between">
                <h1 class="admin-h1 my-3 flex items-center">Bulletins ( {{ Object.keys(this.bulletins).length }} )</h1>
                <div class="relative flex items-center w-8/12 lg:w-3/4 md:w-1/4 justify-end">
                    <div class="flex items-center">
                        <div class="">
                            <div class="flex items-center mx-2">
                                <div class="search relative mx-2">
                                    <input type="text" name="search" v-model="search" class="tw-form-control w-full relative" placeholder="Search">                    
                                    <a href="#" @click="searchList()" class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                        <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                                    </a>
                                </div>
                                <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                                    <a href="#" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4" @click="resetForm()">Reset</a>
                                </div>
                                <div class="relative flex items-center w-1/2 justify-end">
                                    <a :href="url+'/admin/bulletin/create'" id="upload-btn" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                                        <span class="mx-1 text-sm font-semibold">Add</span>
                                        <img :src="url+'/uploads/icons/plus.svg'" class="w-3 h-3">
                                    </a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        
        <div class="">
            <div class="flex flex-wrap custom-table overflow-auto">
                <div class="flex flex-wrap w-full">
                    <div class="w-full lg:w-1/4 md:w-1/2 px-1 my-2" v-for="(bulletinlist,key) in bulletins">
                        <p class="text-sm">{{ key }}</p>
                        <div class="w-full py-2" v-for="bulletin in bulletinlist">
                            <div class="shadow-md p-3">
                                <div class="flex justify-between">
                                    <img class="card-img-top w-16 h-16" :src="bulletin.cover_image">
                                    <div class="flex w-11/12 justify-between">
                                    <div class="px-3">  
                                        <p class="font-bold text-base text-gray-700 capitalize">{{ bulletin.name }}</p>
                                        <p class="font-semibold text-sm text-gray-700 capitalize">{{ bulletin.date }}</p>
                                        <p class="font-medium text-xs text-gray-500 capitalize flex items-center py-1">
                                            <a :href="url+'/admin/bulletin/download/'+bulletin.id">Download File</a>   <a 
  :href="url + '/admin/bulletin/edit/' + bulletin.id"
  class="flex items-center text-white px-3 py-1  rounded"
>
  ✏️
</a>
                                        </p>

     
                                    </div>
                                    <div class="flex items-right">
                                        <a href="#" @click="deleteBulletin(bulletin.id)" id="remove_bulletin" class="left-auto delete-bulletin">
                                            <img :src="url+'/uploads/icons/cancel.svg'" class="w-3 h-3 m-3">
                                        </a>

                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-medium text-sm text-gray-600 capitalize flex items-center py-2" v-if="Object.keys(this.bulletins).length == 0">No records found</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url'],
        data () {
            return {
                bulletins:[],
                type:'month',
                search:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/bulletin/list/'+this.type+'?search='+this.search).then(response => {
                    this.bulletins    = response.data;
                    //console.log(this.bulletins);    
                });
            },

            searchList()
            {
                this.getData();
            },

            resetForm()
            {
                this.search = '';
                this.getData();
            },

            deleteBulletin(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this bulletin ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+ '/admin/bulletin/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        });
                    }
                    else 
                    {
                        swal("Cancelled");
                    }
                });
            }
        },
  
        created()
        {   
            this.getData(); 
            bus.$on("typeTab", data => {
                if(data!='')
                {
                    this.type=data;      
                    this.getData();             
                }
            });
        }
    }
</script>