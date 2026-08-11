<template>
    <div>
        <portal to="search">
            <div class="relative mt-2 lg:mt-0">
                <!-- <input type="text" class="border px-10 py-2 text-sm border-gray-400 w-full rounded bg-white shadow" v-model="name" name="name" placeholder="Search query(Username)"> -->
                <input type="text" class="border px-10 py-2 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search query" @click="showfilter()">
                <span class="input-group-btn absolute left-0 px-3 py-3 top-0">
                    <a href="#">
                        <img :src="this.url+'/uploads/icons/search.svg'" class="w-4 h-4">
                    </a>
                </span>
                <a v-if="this.show_filter==0" href="#"  @click="showfilter()" class="absolute right-0 top-0 px-2 py-3">
                    <img :src="this.url+'/uploads/icons/down.svg'" class="w-2 h-2 m-1">
                </a>
                <a v-if="this.show_filter==1" href="#"  @click="disablefilter()" class="absolute right-0 top-0 px-2 py-3">
                    <img :src="this.url+'/uploads/icons/cancel.svg'" class="w-2 h-2 m-1">
                </a>
            </div>
        </portal>
        <portal to="memberfilter">
            <div class="hide-menu" id="show-filter">
                <div class="absolute bg-white w-full lg:w-2/4 md:w-full shadow-lg border border-r-0 z-40  h-auto py-3 px-4  overflow-auto">  
                    <div id="search_filter">
                        <div>
                            <ul class="search_filter leading-loose">
                                <li id="filter-content-name" class="filter_container">
                                    <div class="advanced_search_option_container" id="firstname">
                                        <div class="person_address_container flex">
                                            <div class="flex my-1 w-1/2 px-4">
                                                <div class="person_filter_name_title w-2/5">
                                                    <p class="text-gray-700">First Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-3/5">
                                                    <input v-model="firstname" name="firstname" type="text" class="filter-form-control" placeholder="First Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="advanced_search_option_container" id="lastname">
                                        <div class="person_address_container flex">
                                            <div class="flex my-1 w-1/2 px-4">
                                                <div class="person_filter_name_title w-2/5">
                                                    <p class="text-gray-700">Last Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-3/5">
                                                    <input v-model="lastname" name="lastname" type="text" class="filter-form-control" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li id="filter-content-email" class="filter_container hide flex">
                                    <div class="flex items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-2/5">
                                            <p class="text-gray-700">Email</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-3/5" id="email">
                                            <input class="filter-form-control" name="email" v-model="email" type="text" placeholder="Email">       
                                        </div>  
                                    </div>      
                                    <div class="flex items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-2/5">
                                            <p class="text-gray-700">Phone</p>
                                        </div>
                                        <div class="advanced_search_option_container  my-1 w-3/5" id="mobile_no">
                                            <input class="filter-form-control" v-model="mobile_no" name="mobile_no" type="text" placeholder="Phone">
                                        </div>
                                    </div>  
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tw-form-row my-4 flex justify-end">
                        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-3 text-sm font-medium" @click="reset()">Reset</a>
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 ml-3 text-sm font-medium" @click="searchpreacher()">Search</a>
                    </div>
                </div>
            </div>
        </portal>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import PortalVue from "portal-vue";
    export default {
        props:['url','searchquery'],
        data(){
            return{
                firstname:'',
                lastname:'',
                mobile_no:'',
                email:'',
                errors:[],
                success:null,
                params:{},
                show_filter:0,   
            }
        },

        methods:
        {
            reset()
            {
                this.firstname='';
                this.lastname='';
                this.mobile_no='';
                this.email='';
                window.location.href=this.url+"/admin/preachers";
            },

            enableDiv(id)
            {
                if($('#'+id).hasClass('hidden'))
                {
                    $('#'+id).removeClass('hidden').addClass('block');
                }
                else
                {
                    $('#'+id).removeClass('block').addClass('hidden');
                }
            },

            searchpreacher()
            {
                this.params = {
                    firstname:this.firstname,
                    lastname:this.lastname,
                    mobile_no:this.mobile_no,
                    email_id:this.email_id,
                };

                this.final=this.url+'/admin/preachers/?'+this.searchquery;
          
                Object.keys(this.params).forEach(key => {
                    // this.final.searchParams.append(key, params[key])     
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                window.location.href=this.final;
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

            showfilter()
            {   
                this.show_filter =1;  
                $('#show-filter').removeClass('hide-menu').addClass('block');
                bus.$emit("datashowFilter", '1');
            },

            disablefilter()
            {
                this.show_filter =0;  
                $('#show-filter').removeClass('block').addClass('hide-menu');
            },
        },

        created()
        {
            bus.$on("dataMemberName", data => {
                if(data!='')
                {
                    this.disablefilter();
                }
            });
        },
    }
</script>