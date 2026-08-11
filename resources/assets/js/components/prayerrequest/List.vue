<template>
    <div class="relative">
        <portal to="add_prayer_request">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Prayer Requests ( {{ Object.keys(this.prayers).length }} )</h1>
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
                            <a :href="url+'/admin/prayerrequest/create'" id="upload-btn" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                                <span class="mx-1 text-sm font-semibold">Add</span>
                                <img :src="url+'/uploads/icons/plus.svg'" class="w-3 h-3">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="">
            <div class="flex-wrap custom-table overflow-auto">
                <div class="flex flex-wrap">
                    <table class="w-full">
                        <thead class="border-t-2 border-b-2">
                            <tr class="border-t-2 border-b-2">
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">User Name</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Description</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Date</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker" style="width:20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-grey-light" v-if="Object.keys(prayers).length > 0">
                            <tr class="border-t-2 border-b-2" v-for="prayer in prayers">
                                <td class="py-3 px-2">
                                    <a :href="url+'/admin/member/show/'+prayer.user_name">{{ prayer.user_fullname }}</a>
                                </td>
                                <td class="py-3 px-2">{{ prayer.title }}</td>
                                <td class="py-3 px-2">{{ prayer.description }}</td>
                                <td class="py-3 px-2">{{ prayer.date }}</td>
                                <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <!-- <a :href="url+'/admin/prayerrequest/show/'+prayer.id" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium">View</a> -->
                                    <a :href="url+'/admin/prayerrequest/show/'+prayer.id">
                                    <svg height="512pt" viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current text-black-500 mx-1"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0"></path></svg>
                                    </a>

                                    <a :href="url+'/admin/prayerrequest/approve/'+prayer.id" class="capitalize text-white custom-green rounded px-2 py-1 font-medium mx-1 text-xs" v-if="prayer.status == 'pending'">Approve</a>

                                    <a :href="url+'/admin/prayerrequest/responses/'+prayer.id" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium mx-1 text-xs" v-if="prayer.status == 'approve'">View Response</a>

                                    <a :href="url+'/admin/prayerrequest/audio/store/'+prayer.id" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium mx-1 text-xs" v-if="prayer.audio == '' && prayer.status == 'approve'">Attach</a>

                                    <a :href="prayer.audio" target="_blank" class="capitalize text-white blue-bg rounded px-2 py-1 font-medium mx-1 text-xs" v-if="prayer.audio != '' && prayer.status == 'approve'">Listen</a>
                                    </div>
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
                prayers:[],
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
                axios.get('/admin/prayerrequest/list/'+this.status).then(response => {
                    this.prayers    = response.data.data;
                    //console.log(this.prayers);    
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

                this.final=this.url+'/admin/prayerrequest/list/'+this.status;
          
                Object.keys(this.params).forEach(key => {
                    // this.final.searchParams.append(key, params[key])
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                //window.location.href=this.final;
                axios.get(this.final).then(response => {
                    this.prayers    = response.data.data;
                    //console.log(this.prayers);    
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