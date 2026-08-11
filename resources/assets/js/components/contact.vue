<template>
    <div class="bg-blue-100">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="container mx-auto py-5">
            <h1 class="text-3xl text-center uppercase">Contact</h1>
            <div class="text-center text-sm text-gray-800">
                <div class="w-full lg:w-1/2 mx-auto my-3 px-2">
                    <div class="flex flex-col lg:flex-row">
                        <div class="tw-form-group w-full lg:w-1/2">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <input type="text" class="tw-form-control w-full" id="fullname" v-model="fullname" name="fullname" Placeholder="Full Name">
                                </div>
                                <span v-if="errors.fullname" class="text-red-500 text-xs font-semibold">{{ errors.fullname[0] }}</span>
                            </div> 
                        </div>

                        <div class="tw-form-group w-full lg:w-1/2">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <input type="text" v-model="mobile" name="mobile" id="mobile" class="tw-form-control w-full" Placeholder="Mobile Number">
                                </div>
                                <span v-if="errors.mobile" class="text-red-500 text-xs font-semibold">{{ errors.mobile[0] }}</span>
                            </div> 
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row">
                        <div class="tw-form-group w-full lg:w-full">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <input type="text" class="tw-form-control w-full" id="email" v-model="email" name="email" Placeholder="Email">
                                </div>
                                <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{ errors.email[0] }}</span>
                            </div> 
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row">
                        <div class="tw-form-group w-full lg:w-full">
                            <div class="lg:mr-8 md:mr-8">
                                <div class="mb-2">
                                    <textarea type="text" v-model="query_message" name="query_message" id="query_message" class="tw-form-control w-full" Placeholder="Query" rows="3"></textarea>
                                </div>
                                <span v-if="errors.query_message" class="text-red-500 text-xs font-semibold">{{ errors.query_message[0] }}</span>
                            </div> 
                        </div>
                    </div>

                    <div class="text-center my-5">
                        <a class="btn btn-primary submit-btn cursor-pointer" @click="submitForm()">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script> 
    export default {
        props:['url'],
        data(){
            return {
                fullname:'',
                email:'',
                mobile:'',
                query_message:'',
                errors:[],
                success:null,
            }
        },
        methods:
        {
            resetForm()
            {
                this.fullname='';
                this.email='';
                this.mobile='';
                this.query_message='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('fullname',this.fullname);  
                formData.append('email',this.email);  
                formData.append('mobile',this.mobile);  
                formData.append('query_message',this.query_message);       

                axios.post(this.url+'/contact',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
                    this.success = response.data.success;
                    //this.resetForm();   
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },
    }
</script>
<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-30px);}
        60% {transform: translateY(-15px);}
    }
    .bounce {
        animation-name: bounce;
    }
</style>