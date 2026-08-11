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
                            <label for="campaign_id" class="tw-form-label">Campaign<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select name="campaign_id" id="campaign_id" v-model="campaign_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Campaign</option>
                                <option v-for="list in campaignlist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.campaign_id" class="text-red-500 text-xs font-semibold">{{ errors.campaign_id[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="day_after" class="tw-form-label">Day After</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="number" name="day_after" id="day_after" v-model="day_after" class="tw-form-control w-full" placeholder="Day After">
                            <span v-if="errors.day_after" class="text-red-500 text-xs font-semibold">{{ errors.day_after[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="email_open_campaign_id" class="tw-form-label">Campaign (Email Open from get response)<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select name="email_open_campaign_id" id="email_open_campaign_id" v-model="email_open_campaign_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Campaign</option>
                                <option v-for="list in campaignlist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.email_open_campaign_id" class="text-red-500 text-xs font-semibold">{{ errors.email_open_campaign_id[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="no_email_open_campaign_id" class="tw-form-label">Campaign (No Mail opened from get response)<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select name="no_email_open_campaign_id" id="no_email_open_campaign_id" v-model="no_email_open_campaign_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Campaign</option>
                                <option v-for="list in campaignlist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.no_email_open_campaign_id" class="text-red-500 text-xs font-semibold">{{ errors.no_email_open_campaign_id[0] }}</span>
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
        props:['url' , 'mode' , 'id'],
        data(){
            return{
                name:'',
                campaign_id:'',
                email_open_campaign_id:'',
                no_email_open_campaign_id:'',
                day_after:'',
                status:0,
                campaignlist:[],
                list:[],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/rule/edit/list/'+this.id).then(response => {
                    this.list = response.data;
                    this.setData();
                    //console.log(this.list);
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.campaignlist               = this.list.campaignlist;
                    this.name                       = this.list.name;
                    this.campaign_id                = this.list.campaign_id;
                    this.email_open_campaign_id     = this.list.email_open_campaign_id;
                    this.no_email_open_campaign_id  = this.list.no_email_open_campaign_id;
                    this.day_after                  = this.list.day_after;
                    this.status                     = this.list.status;
                }
            },

            submitForm()
            { 
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                         
                formData.append('name',this.name);                 
                formData.append('campaign_id',this.campaign_id);                 
                formData.append('email_open_campaign_id',this.email_open_campaign_id);                 
                formData.append('no_email_open_campaign_id',this.no_email_open_campaign_id);                 
                formData.append('day_after',this.day_after);                 
                formData.append('status',this.status);                 
                     
                axios.post('/'+this.mode+'/rule/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
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