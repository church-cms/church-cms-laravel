<template>
    <div class=""> <!-- container mx-auto -->
        <!-- <h1 class="mb-3 text-xl">Add Preacher</h1> -->
        <div class="bg-white shadow border border-grey p-5">
            <div v-if="this.success!=null" class="font-muller alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="flex flex-col lg:flex-row tw-form-group">
                <div class="w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="firstname" class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2"> 
                            <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" class="tw-form-control w-full member-icon" id="firstname" v-model="firstname" name="firstname" Placeholder="First Name">
                    </div>
                    <span v-if="errors.firstname" class="text-red-500 text-xs font-semibold">{{ errors.firstname[0] }}</span>
                </div>

                <div class="w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="lastname" class="tw-form-label">Last Name </label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2"> 
                            <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="lastname" name="lastname" id="lastname" class="tw-form-control w-full member-icon" Placeholder="Last Name">
                    </div>
                    <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{ errors.lastname[0] }}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row tw-form-group">
                <div class="w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="mobile_no" class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2">
                            <img :src="this.url+'/uploads/icons/mobile.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="mobile_no" id="mobile_no" name="mobile_no" class="tw-form-control w-full member-icon" placeholder="Mobile Number"> 
                    </div>
                    <span v-if="errors.mobile_no" class="text-red-500 text-xs font-semibold">{{errors.mobile_no[0]}}</span>
                </div>

                <div class="w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="email" class="tw-form-label">Email ID</label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2">
                            <img :src="this.url+'/uploads/icons/send.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="email" id="email" name="email" class="tw-form-control w-full member-icon" placeholder="Email ID">  
                    </div>
                    <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{errors.email[0]}}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row">
                <div class="tw-form-group w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="">
                        <div class="mb-2">
                            <label class="tw-form-label">Avatar</label>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="avatar" @change="OnFileSelected" id="avatar" class="tw-form-control w-full">
                        </div>
                        <span v-if="errors.avatar" class="text-red-500 text-xs font-semibold">{{ errors.avatar[0] }}</span>
                    </div>
                </div>
                <div class="tw-form-group w-full lg:w-1/3 lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="facebook_id" class="tw-form-label">Facebook ID<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" v-model="facebook_id" id="facebook_id" name="facebook_id"  placeholder="Enter Facebook ID">
                    </div>
                    <span v-if="errors.facebook_id" class="text-red-500 text-xs font-semibold">{{errors.facebook_id[0]}}</span>
                </div>
            </div>

            <div class="w-full lg:w-1/3 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <div class="my-1 lg:mr-8 md:mr-8">  
                        <label class="tw-form-label">Notes<span class="text-red-500">*</span></label>
                    </div>
                    <textarea v-model="description" name="description" id="description" class="tw-form-control w-full" placeholder="Notes"></textarea>
                </div>
                <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
            </div>

            <div>
                <div class="my-6">
                    <a href="#" dusk="submit" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
                    <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
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
                user:[],
                mobile_no:'',
                email:'',
                firstname:'',
                lastname:'',
                description:'',
                avatar:'',
                facebook_id:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            resetForm()
            {
                this.firstname='';
                this.lastname='';
                this.mobile_no='';
                this.email='';
                this.avatar='';
                this.description='';
                this.facebook_id='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null;   

                let formData=new FormData();
              
                formData.append('mobile_no',this.mobile_no);
                formData.append('email',this.email);
                formData.append('firstname',this.firstname); 
                formData.append('lastname',this.lastname);       
                formData.append('description',this.description);          
                formData.append('avatar',this.avatar);               
                formData.append('facebook_id',this.facebook_id);

                axios.post('/admin/preacher/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;          
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnFileSelected(event)
            {
                this.avatar = event.target.files[0];
            },
        },
        created()
        {
            //
        }
    }
</script>