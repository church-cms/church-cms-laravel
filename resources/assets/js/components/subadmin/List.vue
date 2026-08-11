<template>
    <div>
        <portal to="sub_admin_count">
            <div class="">
                <h1 class="admin-h1 my-3">Sub Admins ( {{ Object.keys(this.users).length }} )</h1>
            </div>
        </portal>
        <div class="my-4 filter-alphabet">
            <ul class="list-reset flex flex-wrap">
                <li v-for="alphabet in alphabets">
                    <a href="#" id="filter" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" v-bind:class="letter === alphabet?'active':'text-blue'" v-text="alphabet"  @click="sortMembers(alphabet)"> </a>   
                </li>
                <li>
                    <a href="#" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" @click="clearAll()">Clear All</a>   
                </li>
            </ul>
            <div class="my-4" v-if="!filteredNames.length">No names for this letter</div>
                <div class="" v-if="filteredNames.length"></div>
            </div>
        <div>
            <div class="my-8">
                <p></p>
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2" @click="enableform(user.name)" v-for="user in users">
                        <div class="flex p-2 member-list active">
                            <img :src="user.avatar" class="w-16 h-16">
                            <div class="px-2">
                                <h2 class="font-bold text-base text-gray-700">
                                    <a :href="url+'/admin/subadmin/show/'+user['name']">{{ user.fullname }}</a>
                                </h2>
                                <p>{{ user.mobile_no }}</p>
                                <p v-if="type == 'date_of_birth'">{{ user.date_of_birth }}</p>
                                <p v-if="type == 'location'">{{ user.state }} - {{ user.city }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import PortalVue from "portal-vue";
    export default {
        props:['url' , 'searchquery' , 'letter' , 'type'],
        data(){
            return{
                users:[],
                alphabets: [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                ],
                selectedLetter: undefined,
                active: false,
                errors:[],
                success:null,
            }
        },
        created() 
        {
            axios.get('/admin/subadmins/find?'+this.searchquery).then(response => {
                this.users = response.data.data;  
            });
            this.getUrl();
        },

        computed: 
        {
            filteredNames () 
            {
                let users = this.users
                //console.log(users);
                if (this.selectedLetter) 
                {
                    users = users.filter((name) => {
                    let firstLetter = name.charAt(0).toUpperCase()
                    return firstLetter === this.selectedLetter
                    })
                }
                return users
            }
        },

        methods:
        {
            enableform(val)
            {
                this.success=null;
                $('#show-detail').removeClass('hide-menu').addClass('block');
                bus.$emit("dataMemberName", val);
            },

            clearAll()
            {
                window.location.href = this.url+'/admin/subadmins';
            },
        
            sortMembers(name)
            {
                this.selectedLetter= name; 
                this.active = true; 
                var q='alphabet='+this.selectedLetter;
                //var url = window.location.href; 
                var url = this.currenturl;  

                if (window.location.search.indexOf('alphabet=') > -1) 
                {
                    var href = new URL(url); 
                    href.searchParams.set('alphabet', this.selectedLetter);
                    url=href.toString();       
                } 
                else 
                {
                    if (url.indexOf('?') > -1)
                    {
                        url += '&'
                    }
                else
                {
                    url += '?'
                }
                url += q;
                }
                //console.log(url);
                window.location.href = url;
            },

            getUrl()
            {
                this.currenturl =  this.url+'/admin/subadmins'; 
                if(this.searchquery!='')
                {
                    this.currenturl =  this.currenturl+'?'+this.searchquery;
                }
            },
        }
    }
</script>