<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="name" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="name" id="name" v-model="name" class="tw-form-control w-full" placeholder="Title">
                            <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{ errors.name[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="mailinglist_id" class="tw-form-label">Mailinglist<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select name="mailinglist_id" id="mailinglist_id" v-model="mailinglist_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Mailinglist</option>
                                <option v-for="list in mailList" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.mailinglist_id" class="text-red-500 text-xs font-semibold">{{ errors.mailinglist_id[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="webhook_url" class="tw-form-label">URL</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="webhook_url" id="webhook_url" v-model="webhook_url" class="tw-form-control w-full" placeholder="URL">
                            <span v-if="errors.webhook_url" class="text-red-500 text-xs font-semibold">{{ errors.webhook_url[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="handshake_key" class="tw-form-label">Handshake Key<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="handshake_key" id="handshake_key" v-model="handshake_key" class="tw-form-control w-full" placeholder="Handshake Key">
                            <span v-if="errors.handshake_key" class="text-red-500 text-xs font-semibold">{{ errors.handshake_key[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="status" class="tw-form-label">Active</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="checkbox" v-model="status" name="status" v-bind:true-value="1" v-bind:false-value="0" class="tw-form-control">
                            <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{ errors.status[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>  
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url' , 'mode'],
        data(){
            return{
                name:'',
                mailinglist_id:'',
                handshake_key:'',
                webhook_url:'',
                status:0,
                mailList:[],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/webhook/add/list').then(response => {
                    this.mailList = response.data.data;
                    //console.log(this.mailList);
                });
            },

            resetForm()
            {
                this.name               =   '';
                this.mailinglist_id     =   '';
                this.handshake_key      =   '';
                this.webhook_url        =   '';
                this.status             =   '';
            }, 

            submitForm()
            { 
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                         
                formData.append('name',this.name);                 
                formData.append('mailinglist_id',this.mailinglist_id);                 
                formData.append('handshake_key',this.handshake_key);                   
                formData.append('webhook_url',this.webhook_url);                 
                formData.append('status',this.status);                 
                     
                axios.post('/'+this.mode+'/webhook/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            //
            this.getData();
        }
    }
</script>