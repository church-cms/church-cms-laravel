<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==7?'block' :'hidden']"> <!-- flex flex-wrap -->
        <div class="flex" v-if="this.reference.referby != null">
            <ul class="list-reset leading-loose my-2 text-xs">
                <li class="flex py-1">
                    <div class="flex items-center">
                        <img :src="this.url+'/uploads/icons/age.svg'" class="w-3 h-3">
                        <span class="text-gray-700 font-medium mx-2">Relation : </span>
                    </div>
                    <div>
                        <p>{{ convertUpper(this.reference.relation) }} of
                            <a :href="'/admin/member/show/'+this.reference.referby.name" class="mx-2 text-blue-600 hover:text-blue-400">
                                {{ this.reference.referby.userprofile.firstname }} {{ this.reference.referby.userprofile.lastname }}
                            </a>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="flex w-full lg:w-3/4 mx-auto family-tree my-6">
            <TreeChart :json="treeData"/>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";
    import TreeChart from "vue-tree-chart";
    export default {
        props:['url' , 'name' , 'mode'],
        components: {
            TreeChart, 
        },
        data(){
            return{
                profile_tab:'',
                user:[],
                errors:[],
                success:null,
                reference:[],
                treeData:{},
                treeDatas: {
                    name: 'root',
                    image_url: "https://static.refined-x.com/avat.jpg",
                    mate: '',
                    children: [
                    {
                        name: 'children1',
                        image_url: "https://static.refined-x.com/avat1.jpg"
                    },
                    {
                        name: 'children2',
                        image_url: "https://static.refined-x.com/avat2.jpg",
                        mate: {
                            name: 'mate',
                            image_url: "https://static.refined-x.com/avat3.jpg"
                        },
                        children: [
                            {
                                name: 'grandchild',
                                image_url: "https://static.refined-x.com/avat.jpg"
                            },
                            {
                                name: 'grandchild2',
                                image_url: "https://static.refined-x.com/avat1.jpg"
                            },
                            {
                                name: 'grandchild3',
                                image_url: "https://static.refined-x.com/avat2.jpg"
                            }
                        ]
                    },
                    {
                        name: 'children3',
                        image_url: "https://static.refined-x.com/avat.jpg",
                        mate: {
                            name: 'mate',
                            image_url: "https://static.refined-x.com/avat3.jpg"
                        }
                    }
                    ]
                },
            }
        },

        methods:
        {
            getRef()
            {
                axios.get('/admin/member/show/details/'+this.name).then(response => {
                     this.reference = response.data.data[0];
                    //console.log(this.reference);
                });
            },
            getData()
            {
                axios.get('/admin/member/show/familytree/'+this.name).then(response => {
                    this.treeData =response.data[0];
                    //console.log(this.treeData);
                });
            },

            convertUpper(str)
            {   
                return str.charAt(0).toUpperCase() + str.slice(1);
            }
        },
        
        created()
        {      
            this.getData(); 
            this.getRef();

            bus.$on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });
        }
    }
</script>