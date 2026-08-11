<template>
    <div class="fieldset" v-bind:class="[this.tab==2?'block' :'hidden']">
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="form-group my-6">
            <div class="input-group flex w-full">
                <span class="input-group-addon w-12 flex items-center justify-center">
                    <img :src="url+'/uploads/icons/user.svg'" class="w-4 h-4">
                </span>
                <input type="text" id="name" class="form-control px-2 py-2 w-full text-sm border" name="name" v-model="name" placeholder="Name">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert" v-if="errors.name">{{ errors.name[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="input-group flex w-full">
                <span class="input-group-addon w-12 flex items-center justify-center">
                    <img :src="url+'/uploads/icons/mobile.svg'" class="w-4 h-4" style="filter: brightness(0) invert(1);">
                </span>
                <input type="text" id="mobile_no" class="form-control px-2 py-2 w-full text-sm border" name="mobile_no" v-model="mobile_no" placeholder="Mobile Number">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert" v-if="errors.mobile_no">{{ errors.mobile_no[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="input-group flex w-full">
                <span class="input-group-addon w-12 flex items-center justify-center">
                    <img :src="url+'/uploads/icons/envelope.svg'" class="w-4 h-4">
                </span>
                <input id="email" type="email" class="form-control px-2 py-2 w-full text-sm border" name="email" v-model="email" placeholder="E-Mail Address">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert" v-if="errors.email">{{ errors.email[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="input-group flex w-full">
                <span class="input-group-addon w-12 flex items-center justify-center">
                    <img :src="url+'/uploads/icons/lock.svg'" class="w-4 h-4">
                </span>
                <input id="password" type="password" class="form-control px-2 py-2 w-full text-sm border" name="password" v-model="password" placeholder="Password">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert" v-if="errors.password">{{ errors.password[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="input-group flex w-full">
                <span class="input-group-addon w-12 flex items-center justify-center">
                    <img :src="url+'/uploads/icons/lock.svg'" class="w-4 h-4">
                </span>
                <input id="password-confirm" type="password" class="form-control px-2 py-2 w-full text-sm border" name="password_confirmation" v-model="password_confirmation" placeholder="Confirm Password">
            </div>
        </div>

        <div class="flex lg:items-center flex-col lg:flex-row">
            <div class="w-full py-1">
                <div class="form-check flex items-center">
                    <input type="checkbox" class="form-check-input" name="termsandcondn" v-model="termsandcondn" value="1" >
                    <label for="termsandcondn" class="form-control px-2 py-2 w-full text-sm">I Agree to
                        <a :href="url+'/terms-of-service'" target="_blank">
                            <b>Terms and Conditions</b>
                        </a>
                    </label>
                </div>
                <span class="invalid-feedback text-red-500 text-xs font-semibold" role="alert" v-if="errors.termsandcondn">{{ errors.termsandcondn[0] }}</span>
            </div>
        </div>

        <portal-target name="google_tab"></portal-target>

        <portal-target name="submit-btn"></portal-target>
        <portal to="submit-btn">
            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="setTab('1')">Back</a>
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
                <input type="submit" class="hidden" id="submit_btn" value="Submit"> 
            </div>
        </portal>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url' , 'google_key'],
        data(){
            return{
                tab:'',
                name:'',
                mobile_no:'',
                email:'',
                password:'',
                password_confirmation:'',
                termsandcondn:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            setTab(val)
            {
                this.tab=val;
                bus.$emit("tabValue", this.tab);
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                
                formData.append('name',this.name);                
                formData.append('mobile_no',this.mobile_no);         
                formData.append('email',this.email);  
                formData.append('password',this.password);  
                formData.append('password_confirmation',this.password_confirmation);  
                formData.append('termsandcondn',this.termsandcondn);  
                  
                axios.post('/register/stepTwo',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {      
                    $('#submit_btn').click();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        { 
            bus.$on("tabValue", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });
            bus.$on("message", data => {
                if(data!='')
                {
                    this.success=data;                   
                }
            });
        }
    }
</script>