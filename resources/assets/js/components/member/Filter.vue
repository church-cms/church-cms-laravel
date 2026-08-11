<template>
    <div>
        <portal to="search">
            <div class="relative mt-2 lg:mt-0">
                <!-- <input type="text" class="border px-10 py-2 text-sm border-gray-400 w-full rounded bg-white shadow" v-model="name" name="name" id="name" placeholder="Search query(Username)"> -->
                <input type="text" class="border px-10 py-2 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search query" @click="showfilter()">
                <span class="input-group-btn absolute left-0 px-3 py-3 top-0">
                    <a href="#">
                        <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4">
                    </a>
                </span>
                <a v-if="this.show_filter == 0" href="#" dusk="show" @click="showfilter()" class="absolute right-0 top-0 px-2 py-3">
                    <img :src="url+'/uploads/icons/down.svg'" class="w-2 h-2 m-1">
                </a>
                <a v-if="this.show_filter == 1" href="#" id="hide" @click="disablefilter()" class="absolute right-0 top-0 px-2 py-3">
                    <img :src="url+'/uploads/icons/cancel.svg'" class="w-2 h-2 m-1">
                </a>
            </div>
        </portal>
        <portal to="memberfilter">
            <div class="hide-menu relative lg:static md:static" id="show-filter">
                <div class="absolute bg-white w-full lg:w-2/4 md:w-full shadow-lg border border-r-0 z-40  h-auto py-3 lg:px-4 overflow-auto search_user_filter">  
                    <div id="search_filter">
                        <div>
                            <ul class="search_filter leading-loose">
                                <li id="filter-content-name" class="filter_container">
                                    <div class="advanced_search_option_container " id="name">
                                        <div class="person_address_container flex">
                                            <div class="flex flex-col lg:flex-row md:flex-row my-1 w-1/2 px-4 items-center">
                                                <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                                    <p class="text-gray-700">First Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-full lg:w-3/5 md:w-3/5">
                                                    <input v-model="firstname" name="firstname" id="firstname" type="text" class="filter-form-control my-2 lg:my-0 md:my-0" placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="flex flex-col lg:flex-row md:flex-row my-1 w-1/2 px-4 items-center">
                                                <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                                    <p class="text-gray-700">Last Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-full lg:w-3/5 md:w-3/5">
                                                    <input v-model="lastname" name="lastname" id="lastname" type="text" class="filter-form-control my-2 lg:my-0 md:my-0" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li id="filter-content-gender" class="filter_container flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Gender</p>
                                        </div>
                                        <div class="advanced_search_option_container flex my-2 lg:my-1 md:my-1 w-full lg:w-3/5 md:w-3/5" id="gender">
                                            <select name="gender" v-model="gender" id="gender" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Gender</option>
                                                <option v-for="list in genderlist" v-bind:value="list.id">{{ list.name }}</option>   
                                            </select>     
                                        </div>
                                    </div>
                                    <div class="flex flex-col lg:flex-row md:flex-row my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Marriage Status</p>
                                        </div>
                                        <div class="advanced_search_option_container flex flex-wrap my-2 lg:my-1 md:my-1 w-full lg:w-3/5 md:w-3/5" id="marriage_status"> 
                                            <select name="marriage_status" v-model="marriage_status" id="marriage_status" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Marriage Status</option>
                                                <option v-for="list in marriagelist" v-bind:value="list.id">{{ list.name }}</option>  
                                            </select>                 
                                        </div>   
                                    </div>
                                </li>

                                <li id="filter-content-family" class="filter_container flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Family Name</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-3/5" id="family">     
                                            <div class="">
                                                <input type="text" class="filter-form-control my-2 lg:my-0 md:my-0" name="family" id="family" v-model="family" placeholder="Family Name">        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Occupation</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 w-full lg:w-3/5 md:w-3/5" id='profession'> 
                                            <select name="profession" v-model="profession" id="profession" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled>Occupation</option>
                                                <option v-for="list in occupationlist" v-bind:value="list.id">{{ list.name }}</option>
                                            </select> 
                                        </div>  
                                    </div>    
                                </li>

                                <li id="filter-content-age" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Age</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="age">   
                                            <div class="w-5/12">
                                                <select name="min_age" v-model="min_age" id="min_age" class="filter-form-control" data-type="dropdown">
                                                    <option value="" disabled="disabled">Age</option>
                                                    <option v-for="n in count" v-bind:value="n">{{n}}</option>
                                                </select>
                                            </div>   
                                            <p class="text-center w-2/12 text-gray-600">to</p>
                                            <div class="w-5/12">
                                                <select name="max_age" v-model="max_age" id="max_age" class="filter-form-control" data-type="dropdown">
                                                    <option value="" disabled="disabled">Age</option>
                                                    <option v-for="n in count" v-bind:value="n">{{n}}</option>
                                                </select>
                                            </div>    
                                        </div>      
                                    </div>      
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Phone</p>
                                        </div>
                                        <div class="advanced_search_option_container  my-1 w-full lg:w-3/5 md:w-3/5">
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" v-model="mobile_no" id="mobile_no" name="mobile_no" type="text" placeholder="Phone">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <ul class="search_filter leading-loose">
                                <li id="filter-content-email" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Email</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-2/5">
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" name="email" v-model="email" id="email" type="text" placeholder="Email">       
                                        </div>  
                                    </div>   
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Location</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full  lg:w-3/5 md:w-3/5" id="location">
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" name="location" v-model="location" id="location" type="text" placeholder="Location">       
                                        </div>    
                                    </div>   
                                </li>

                                <li id="filter-content-marriage" class="filter_container flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Birthday</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="date_of_birth"> 
                                            <select class="filter-form-control" id="date_of_birth" v-model="date_of_birth" name="date_of_birth" data-type="dropdown">
                                                <option value="" disabled="disabled">Month</option>
                                                <option v-for="month in months" v-bind:value="month.id">{{ month.name }}</option>
                                            </select>
                                        </div>      
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tw-form-row my-4 flex justify-end">
                        <a href="#" dusk="reset-btn" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-3 text-sm font-medium" @click="reset()">Reset</a>
                        <a href="#" dusk="search-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 ml-3 text-sm font-medium mr-3 lg:mr-0 md:mr-3" @click="searchmember()">Search</a>
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
                name:'',
                firstname:'',
                lastname:'',
                gender:'',
                marriage_status:'',
                min_age:'',
                max_age:'',
                date_of_birth:'',
                family:'',
                profession:'',
                mobile_no:'',
                email:'',
                location:'',
                count:100,
                params:{},
                show_filter:0, 
                occupationlist:[],
                marriagelist:[],  
                genderlist:[],
                months:[],  
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/member/filter/list').then(response => {
                    this.occupationlist = response.data.occupationlist;
                    this.marriagelist   = response.data.marriagelist;
                    this.genderlist     = response.data.genderlist;
                    this.months         = response.data.months;
                });
            },
            
            reset()
            {
                this.name='';
                this.firstname='';
                this.lastname='';
                this.gender='';
                this.marriage_status='';
                this.min_age='';
                this.max_age='';
                this.date_of_birth='';
                this.family='';
                this.profession='';
                this.mobile_no='';
                this.email='';
                this.location='';
                window.location.href=this.url+"/admin/members";
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

            searchmember()
            {
                this.params = {
                    name:this.name,
                    firstname:this.firstname,
                    lastname:this.lastname,
                    gender:this.gender,
                    marriage_status:this.marriage_status,
                    min_age:this.min_age,
                    max_age:this.max_age,
                    date_of_birth:this.date_of_birth,
                    family:this.family,
                    profession:this.profession,
                    mobile_no:this.mobile_no,
                    email:this.email,
                    location:this.location,
                };

                this.final=this.url+'/admin/members/?'+this.searchquery;
          
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
            this.getData();

            bus.$on("dataMemberName", data => {
                if(data!='')
                {
                    this.disablefilter();
                }
            });
        },
    }
</script>