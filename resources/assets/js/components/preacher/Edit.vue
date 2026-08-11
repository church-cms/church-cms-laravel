<template>
    <div class="container mx-auto">
        <div v-if="this.success!=null" class="font-muller alert alert-success" id="success-alert">{{ this.success }}</div>

        <div class="my-6">
            <div class="flex items-center">
                <img :src="avatar_display" class="img-responsive w-12 h-12 rounded-full">
                <div class="mx-2">
                    <h2 class="font-semibold text-sm text-gray-700">{{ user.firstname }} {{ user.lastname }}</h2>
                    <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Avatar
                        <input type="file" size="60" name="avatar" id="avatar" @change="OnFileSelected">
                        <span v-if="errors.avatar" class="text-red-500 text-xs font-semibold">{{errors.avatar[0]}}</span>
                    </label> 
                </div>
            </div>
        </div>

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

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label for="facebook_id" class="tw-form-label">Facebook ID<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <input type="text" class="tw-form-control w-full" v-model="facebook_id" id="facebook_id" name="facebook_id"  placeholder="Enter Facebook ID">
                </div>
                <span v-if="errors.facebook_id" class="text-red-500 text-xs font-semibold">{{errors.facebook_id[0]}}</span>
            </div>
            <div class="tw-form-group w-full lg:w-1/3 lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label for="facebook_id" class="tw-form-label">Notes<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                    <textarea v-model="description" name="description" id="description" class="tw-form-control w-full" placeholder="Notes"></textarea>
                </div>
                <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
            </div>
        </div>

        <div class="my-6">
            <a href="#" dusk="submit" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
        </div>
    </div>
</template>

<script> 
    export default {
        props:['url' , 'name' , 'mode'],
        data(){
            return {
                user:[],
                firstname:'',
                lastname:'',
                facebook_id:'',
                description:'',
                avatar_display:'',
                avatar:'',
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

                formData.append('firstname',this.firstname); 
                formData.append('lastname',this.lastname);       
                formData.append('description',this.description);          
                formData.append('avatar',this.avatar);               
                formData.append('facebook_id',this.facebook_id);        
                  
                axios.post(this.url+'/'+this.mode+'/edit/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnFileSelected(event)
            {
                this.avatar = event.target.files[0];
            },
         
            getData()
            {
                axios.get(this.url+'/'+this.mode+'/edit/list/'+this.name).then(response => {
                    this.user = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.user).length>0)
                {
                    this.firstname          =   this.user.firstname;
                    this.lastname           =   this.user.lastname;
                    if(this.user.facebook_id != null)
                    {
                        this.description        =   this.user.description;
                    }
                    this.avatar_display     =   this.user.avatar_display;
                    if(this.user.facebook_id != null)
                    {
                        this.facebook_id        =   this.user.facebook_id;
                    }
                }
            },
        },
        
        created()
        {
            this.getData();
        }
    }
</script>