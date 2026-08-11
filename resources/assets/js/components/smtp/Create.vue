<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="host" class="tw-form-label">Host<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="host" id="host" v-model="host" class="tw-form-control w-full" placeholder="Host">
                            <span class="text-xs">(Eg. : gmail.com)</span>
                            <span v-if="errors.host" class="text-red-500 text-xs font-semibold">{{ errors.host[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="port" class="tw-form-label">Port<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="port" id="port" v-model="port" class="tw-form-control w-full" placeholder="Port">
                            <span class="text-xs">(Eg. : 2525)</span>
                            <span v-if="errors.port" class="text-red-500 text-xs font-semibold">{{ errors.port[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="username" class="tw-form-label">User Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="username" id="username" v-model="username" class="tw-form-control w-full" placeholder="User Name">
                            <span v-if="errors.username" class="text-red-500 text-xs font-semibold">{{ errors.username[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="password" class="tw-form-label">Password<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="password" name="password" id="password" v-model="password" class="tw-form-control w-full" placeholder="Password">
                            <span v-if="errors.password" class="text-red-500 text-xs font-semibold">{{ errors.password[0] }}</span>
                        </div>
                    </div>
                </div>
            </div> 

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="encryption" class="tw-form-label">Encryption<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select name="encryption" id="encryption" v-model="encryption" class="tw-form-control w-full">
                                <option value="" disabled>Select Encryption</option>
                                <option v-for="list in encryptionList" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.encryption" class="text-red-500 text-xs font-semibold">{{ errors.encryption[0] }}</span>
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
                            <input type="checkbox" v-model="status" v-bind:true-value="1"  name="status" class="tw-form-control">
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
                host:'',
                port:'',
                username:'',
                password:'',
                encryption:'',
                status:0,
                encryptionList:[{id : 'ssl' , name : 'SSL'} , {id:'tls' , name:'TLS'}],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            resetForm()
            {
                this.host='';
                this.port='';
                this.username='';
                this.password='';
                this.encryption='';
                this.status='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                                 
                formData.append('host',this.host);                 
                formData.append('port',this.port);                 
                formData.append('username',this.username);                 
                formData.append('password',this.password);                 
                formData.append('encryption',this.encryption);                 
                formData.append('status',this.status);                 
                             
                axios.post('/'+this.mode+'/smtp/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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
        }
    }
</script>