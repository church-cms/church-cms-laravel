<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="title" v-model="title" name="title" Placeholder="Title">
                    </div>
                    <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{ errors.title[0] }}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" v-model="description" name="description" id="description" class="tw-form-control w-full" Placeholder="Description" rows="3"></textarea>
                    </div>
                    <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="contact_details" class="tw-form-label">Contact Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="contact_details" v-model="contact_details" class="tw-form-control w-full" id="contact_details" placeholder="Contact Number">
                    </div>
                    <span v-if="errors.contact_details" class="text-red-500 text-xs font-semibold">{{ errors.contact_details[0] }}</span>
                </div> 
            </div>
        </div>

        <div class=" mb-6 mt-3">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a> 
        </div>
        
    </div>
</template>

<script>
    import datetime from 'vuejs-datetimepicker';
    export default{
        props:['url'],
        components:
        {  
            datetime,
        },
        data () {
            return {
                title:'',
                description:'',
                contact_details:'',
                errors:[],
                success:null,
            }
        },
        methods: 
        {
            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
             
                formData.append('title',this.title);
                formData.append('description',this.description);
                formData.append('contact_details',this.contact_details);

                axios.post('/admin/help/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            resetForm()
            {
                this.title              = '';
                this.description        = '';
                this.contact_details    = '';
            },
        },
        created()
        {
            //
        }
    }
</script>