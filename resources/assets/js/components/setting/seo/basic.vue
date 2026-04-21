<template>
    <div class="fieldset" v-bind:class="[this.tab==1?'block' :'hidden']">
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="px-3 py-3 mx-2">
            

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4 md:w-1/4">
                        <label for="header_code" class="tw-form-label">Header Code</label>
                    </div>
                    <div class="w-full lg:w-2/5 md:w-3/5 my-2">
                        <textarea type="textarea" name="header_code" v-model="header_code" id="header_code" rows="3" class="tw-form-control w-full" placeholder="Header Code"></textarea>
                    </div>
                    <span class="text-danger text-xs" v-if="errors.header_code">{{ errors.header_code[0] }}</span>
                </div>
            </div>

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4 md:w-1/4">
                        <label for="footer_code" class="tw-form-label">Footer Code</label>
                    </div>
                    <div class="w-full lg:w-2/5 md:w-3/5 my-2">
                        <textarea type="textarea" name="footer_code" v-model="footer_code" id="footer_code" rows="3" class="tw-form-control w-full" placeholder="Footer Code"></textarea>
                    </div>
                    <span class="text-danger text-xs" v-if="errors.footer_code">{{ errors.footer_code[0] }}</span>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            	<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>	
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";

    export default {
        props:['url'],
        data(){
            return{
                tab:1,
                list:[],
                sitetitle:'',
                site_description:'',
                site_keyword:'',
                header_code:'',
                footer_code:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/admin/settings/seo/list').then(response => {
                    this.list = response.data.basic;
                    this.setData();
                    //console.log(this.list)
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.sitetitle          = this.list.sitetitle;
                    this.site_description   = this.list.site_description;
                    this.site_keyword       = this.list.site_keyword;
                    this.header_code        = this.list.header_code;
                    this.footer_code        = this.list.footer_code;
                }
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData = new FormData();
                
                formData.append('sitetitle',this.sitetitle);  
                formData.append('site_description',this.site_description);  
                formData.append('site_keyword',this.site_keyword);                
                formData.append('header_code',this.header_code);                
                formData.append('footer_code',this.footer_code);    
     
                axios.post('/admin/settings/basicseo',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            reset()
            {
                this.sitetitle = '';
                this.site_description = '';
                this.site_keyword = '';
                this.header_code = '';
                this.footer_code = '';
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