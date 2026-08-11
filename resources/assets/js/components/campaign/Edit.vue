<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="name" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="name" id="name" v-model="name" class="tw-form-control w-full" placeholder="Title">
                            <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="description" class="tw-form-label">Description</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <textarea type="textarea" name="description" id="description" v-model="description" class="tw-form-control w-full" placeholder="Description" @keyup='remaincharCount()' maxlength="100"></textarea>
                            <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
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
                            <input type="checkbox" v-model="status" name="status" class="tw-form-control">
                            <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{errors.status[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>
      
            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Update</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['id','mode'],
        data(){
            return{
                campaign:[],
                name:'',
                description:'',
                status:'',
                errors:[],
                success:null,
            }
        },
                
        methods:
        {
            remaincharCount()
            {
                var maxLength = 100;
                $('textarea').keyup(function() {
                    var textlen = maxLength - $(this).val().length+'/'+maxLength;
                    $('#rchars').text(textlen);
                });
            },

            getData()
            {
                axios.get('/'+this.mode+'/campaign/editList/'+this.id).then(response => {
                    this.campaign= response.data.data[0]; 
                    this.setData();                   
                }); 
            },

            setData()
            {            
                this.name           =   this.campaign.name;                 
                this.description    =   this.campaign.description;                 
                this.status         =   this.campaign.status;  
            },

            submitForm()
            {        
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('name',this.name);                 
                formData.append('description',this.description);                 
                formData.append('status',this.status);                 
                                 
                axios.post('/'+this.mode+'/campaign/edit/'+this.id,formData).then(response => { 
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>