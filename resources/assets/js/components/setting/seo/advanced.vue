<template>
    <div class="fieldset" v-bind:class="[this.tab==2?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="px-3 py-3 mx-2 flex flex-col lg:flex-row md:flex-row">
            <div class="w-full lg:w-1/2 md:w-1/2">
                <div class="my-6">
                    <div class="flex items-center">
                        <img :src="this.facebook_image" class="img-responsive w-12 h-12 rounded-full">
                        <div class="mx-2">
                            <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Facebook Image
                                <input type="file" name="facebook_image" id="facebook_image" class="tw-form-control w-full" @change="OnFileSelected($event,'facebook')">
                                <span class="text-danger text-xs" v-if="errors.facebook_image">{{ errors.facebook_image[0] }}</span>
                            </label> 
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="facebook_title" class="tw-form-label">Facebook Title </label>
                        </div>
                        <div class="w-3/4 lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="facebook_title" v-model="facebook_title" id="facebook_title" class="tw-form-control w-full" placeholder="Facebook Title">
                            <span class="text-danger text-xs" v-if="errors.facebook_title">{{ errors.facebook_title[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="facebook_description" class="tw-form-label">Facebook Description</label>
                        </div>
                        <div class="w-3/4 lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="facebook_description" v-model="facebook_description" id="facebook_description" class="tw-form-control w-full" placeholder="Facebook Description">
                            <span class="text-danger text-xs" v-if="errors.facebook_description">{{ errors.facebook_description[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="facebook_url" class="tw-form-label">Facebook URL</label>
                        </div>
                        <div class="w-3/4 lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="facebook_url" v-model="facebook_url" id="facebook_url" class="tw-form-control w-full" placeholder="Facebook URL">
                        </div>
                        <span class="text-danger text-xs" v-if="errors.facebook_url">{{ errors.facebook_url[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 md:w-1/2">
                <div class="my-6">
                    <div class="flex items-center">
                        <img :src="this.twitter_image" class="img-responsive w-12 h-12 rounded-full">
                        <div class="mx-2">
                            <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Twitter Image
                                <input type="file" name="twitter_image" id="twitter_image" class="tw-form-control w-full" @change="OnFileSelected($event,'twitter')">
                                <span class="text-danger text-xs" v-if="errors.twitter_image">{{ errors.twitter_image[0] }}</span>
                            </label> 
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="twitter_title" class="tw-form-label">Twitter Title </label>
                        </div>
                        <div class="w-full lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="twitter_title" v-model="twitter_title" id="facebook_title" class="tw-form-control w-full" placeholder="Twitter Title">
                            <span class="text-danger text-xs" v-if="errors.twitter_title">{{ errors.twitter_title[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="twitter_description" class="tw-form-label">Twitter Description</label>
                        </div>
                        <div class="w-3/4 lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="twitter_description" v-model="twitter_description" id="twitter_description" class="tw-form-control w-full" placeholder="Facebook Description">
                            <span class="text-danger text-xs" v-if="errors.twitter_description">{{ errors.twitter_description[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-full">
                            <label for="twitter_url" class="tw-form-label">Twitter URL</label>
                        </div>
                        <div class="w-full lg:w-3/4 md:w-3/4 my-2">
                            <input type="text" name="twitter_url" v-model="twitter_url" id="twitter_url" class="tw-form-control w-full" placeholder="Twitter URL">
                        </div>
                        <span class="text-danger text-xs" v-if="errors.twitter_url">{{ errors.twitter_url[0] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 pt-3 pb-5 mx-2">
            <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>    
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";

    export default {
        props:['url'],
        data(){
            return{
                tab:'',
                list:[],
                facebook_title:'',
                facebook_description:'',
                facebook_url:'',
                facebook_image:'',
                twitter_title:'',
                twitter_description:'', 
                twitter_url:'',
                twitter_image:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/admin/settings/seo/list').then(response => {
                    this.list = response.data.advanced;
                    this.setData();
                    //console.log(this.list)
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.facebook_title         = this.list.facebook_title;
                    this.facebook_description   = this.list.facebook_description;
                    this.facebook_url           = this.list.facebook_url;
                    this.facebook_image         = this.list.facebook_image;
                    this.twitter_title          = this.list.twitter_title;
                    this.twitter_description    = this.list.twitter_description;
                    this.twitter_url            = this.list.twitter_url;
                    this.twitter_image          = this.list.twitter_image;
                }
            },

            OnFileSelected(event,type)
            {
                if(type == 'facebook')
                {
                    this.facebook_image = event.target.files[0];
                }
                else if(type == 'twitter')
                {
                    this.twitter_image = event.target.files[0];
                }
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                
                formData.append('facebook_title',this.facebook_title);  
                formData.append('facebook_description',this.facebook_description);  
                formData.append('facebook_url',this.facebook_url);                
                formData.append('facebook_image',this.facebook_image);                
                formData.append('twitter_title',this.twitter_title);    
                formData.append('twitter_description',this.twitter_description);    
                formData.append('twitter_url',this.twitter_url);    
                formData.append('twitter_image',this.twitter_image);    
     
                axios.post('/admin/settings/advancedseo',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {    
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            reset()
            {
                this.facebook_title = '';
                this.facebook_description = '';
                this.facebook_url = '';
                this.facebook_image = '';
                this.twitter_title = '';
                this.twitter_description = '';
                this.twitter_url = '';
                this.twitter_image = '';
            },
        },

        created()
        {
            this.getData(); 
            bus.$on("tabValue", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });
        }
    }
</script>

<style>
    .tw-label{
        color:#3492e2;
    }
    .tw-label input[type="file"] {
        display: none;
    }
</style>