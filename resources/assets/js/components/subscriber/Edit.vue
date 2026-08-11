<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="first_name" class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="firstname" id="firstname" v-model="firstname" class="tw-form-control w-full" placeholder="First Name">
                            <span v-if="errors.firstname" class="text-red-500 text-xs font-semibold">{{ errors.firstname[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="lastname" class="tw-form-label">Last Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="lastname" id="lastname" v-model="lastname" class="tw-form-control w-full" placeholder="Last Name">
                            <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{ errors.lastname[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="email" class="tw-form-label">Email<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="email" id="email" v-model="email" class="tw-form-control w-full" placeholder="Email">
                            <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{ errors.email[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="aff" class="tw-form-label">Aff<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="aff" id="aff" v-model="aff" class="tw-form-control w-full" placeholder="Aff">
                            <span v-if="errors.aff" class="text-red-500 text-xs font-semibold">{{ errors.aff[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="source" class="tw-form-label">Source<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="source" id="source" v-model="source" class="tw-form-control w-full" placeholder="Source">
                            <span v-if="errors.source" class="text-red-500 text-xs font-semibold">{{errors.source[0]}}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="is_active" class="tw-form-label">Active</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="checkbox" v-model="is_active" v-bind:true-value="1"  name="is_active" class="tw-form-control">
                            <span v-if="errors.is_active" class="text-red-500 text-xs font-semibold">{{ errors.is_active[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url' , 'id' , 'mode'],
        data(){
            return{
                susbcriber:[],       
                firstname:'',
                lastname:'',
                email:'',
                aff:'',
                source:'',
                is_active:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/subscriber/showdetails/'+this.id).then(response => {
                    this.subscriber= response.data; 
                    //console.log(this.subscriber);  
                    this.setData();            
                }); 
            },

            setData()
            {
                if(Object.keys(this.subscriber).length > 0)
                {
                    this.firstname  =   this.subscriber.firstname;
                    this.lastname   =   this.subscriber.lastname;
                    this.email      =   this.subscriber.email;
                    this.aff        =   this.subscriber.aff;
                    this.source     =   this.subscriber.source;
                    this.is_active  =   this.subscriber.is_active;
                }                           
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                      
                formData.append('firstname',this.firstname);                 
                formData.append('lastname',this.lastname);                 
                formData.append('email',this.email);                 
                formData.append('aff',this.aff);                 
                formData.append('source',this.source);                 
                formData.append('is_active',this.is_active);               
                                 
                axios.post('/'+this.mode+'/subscriber/edit/'+this.id,formData).then(response => {   
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