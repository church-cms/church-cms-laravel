<template>
    <div class="relative">
        <portal to="add_help">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Helps ( {{ Object.keys(this.helps).length }} )</h1>
                <div class="">
                    <div class="flex lg:justify-end md:justify-end items-center">
                        <div class="search relative w-48">
                            <input type="text" name="search" v-model="search" class="tw-form-control w-full relative" placeholder="Search">                    
                            <a href="#" @click="searchList()" class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                            </a>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="#" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4" @click="resetForm()">Reset</a>
                        </div>
                        <div class="relative flex items-center w-1/2 justify-end">
                            <a :href="url+'/admin/help/create'" id="upload-btn" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                                <span class="mx-1 text-sm font-semibold">Add</span>
                                <img :src="url+'/uploads/icons/plus.svg'" class="w-3 h-3">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="">
            <div class="flex-wrap custom-table overflow-auto">
                <div class="flex flex-wrap">
                    <table class="w-full">
                        <thead class="border-t-2 border-b-2">
                            <tr class="border-t-2 border-b-2">
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">User Name</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Description</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Contact Details</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-grey-light" v-if="Object.keys(helps).length > 0">
                            <tr class="border-t-2 border-b-2" v-for="help in helps">
                                <td class="py-3 px-2">
                                    <a :href="url+'/admin/member/show/'+help.user_name">{{ help.user_fullname }}</a>
                                </td>
                                <td class="py-3 px-2">{{ help.title }}</td>
                                <td class="py-3 px-2">{{ help.description }}</td>
                                <td class="py-3 px-2">{{ help.contact_details }}</td>
                                <td class="py-3 px-2">
                                    <a :href="url+'/admin/help/show/'+help.id" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium">View</a>

                                    <a :href="url+'/admin/help/edit/'+help.id" class="capitalize text-white custom-green rounded px-2 py-1 font-medium" v-if="help.status == 'pending'">Approve</a>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr class="border-t-2 border-b-2">
                                <td colspan="5" style="text-align: center;">No records found</td>
                            </tr>
                        </tbody>
                    </table>
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
                helps:[],
                status:'pending',
                search:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/help/list/'+this.status).then(response => {
                    this.helps    = response.data.data;
                    //console.log(this.helps);    
                });
            },

            resetForm()
            {
                this.search = '';
                this.searchList();
            },

            searchList()
            {
                this.params = {
                    search:this.search,
                };

                this.final=this.url+'/admin/help/list/'+this.status;
          
                Object.keys(this.params).forEach(key => {
                    // this.final.searchParams.append(key, params[key])
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                //window.location.href=this.final;
                axios.get(this.final).then(response => {
                    this.helps    = response.data.data;
                    //console.log(this.helps);    
                });
            },

            addParam(url, param, value) 
            {
                param = encodeURIComponent(param);
                var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
                var a = document.createElement('a');
                var regex = new RegExp(r);
                var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
                a.href = url;
                var q = a.search.replace(regex, "$1"+str);
                if (q === a.search) 
                {
                    a.search += (a.search ? "&" : "") + str;
                } 
                else 
                {
                    a.search = q;
                }
                return a.href ;
            },
        },
  
        created()
        {   
            this.getData(); 
            bus.$on("statusTab", data => {
                if(data!='')
                {
                    this.status=data;      
                    this.getData();             
                }
            });
        }
    }
</script>