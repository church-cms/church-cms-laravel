<template>
    <div class="fieldset" v-bind:class="[this.tab==1?'block' :'hidden']">
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="form-group my-6">
            <div class="">
                <input type="text" id="church_name" class="form-control px-2 py-2 w-full text-sm border" name="church_name" v-model="church_name" placeholder="Church Name">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.church_name">{{ errors.church_name[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="input-group flex  w-full">
                <input type="text" id="address" class="form-control px-2 py-2 w-full text-sm border" name="address" v-model="address" placeholder="Address">
            </div>
            <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.address">{{ errors.address[0] }}</span>
        </div>

        <div class="form-group my-6">
            <div class="">
                <select name="country_id" v-model="country_id" class="form-control px-2 py-2 w-full text-sm border">
                    <option value="" disabled>Select Country</option>
                    <option v-for="country in countrylist" v-bind:value="country.id">{{ country.name }}</option>
                </select>
                <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.country_id">{{ errors.country_id[0] }}</span>
            </div>
        </div>

        <div class="form-group my-6" v-if="this.countryid==this.country_id">
            <div class="">
                <select name="state_id" v-model="state_id" class="form-control px-2 py-2 w-full text-sm border">
                    <option value="" disabled>Select State</option>
                    <option v-for="state in statelist" v-bind:value="state.id">{{ state.name }}</option>
                </select>
                <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.state_id">{{ errors.state_id[0] }}</span>
            </div>
        </div>

        <div class="form-group my-6" v-if="this.countryid==this.country_id">
            <div class="">
                <select name="city_id" v-model="city_id" class="form-control px-2 py-2 w-full text-sm border">
                    <option value="" disabled>Select City</option>
                    <option v-for="city in citylist[this.state_id]" v-bind:value="city.id">{{ city.name }}</option>
                </select>
                <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.city_id">{{ errors.city_id[0] }}</span>
            </div>
        </div>

        <div class="form-group my-6">
            <div class="">
                <input type="text" id="pincode" class="form-control px-2 py-2 w-full text-sm border" name="pincode" v-model="pincode" placeholder="Pincode">
                <span class="invalid-feedback text-red-500 text-xs font-semibold" v-if="errors.pincode">{{ errors.pincode[0] }}</span>
            </div>
        </div>

        <div class="my-6">
            <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm('2')">Save & Continue</a>
        	   <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>	
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";

    export default {
        props:['url'],
        data(){
            return{
                tab:1,
                statelist:[],
                citylist:[],
                countrylist:[],
                church_name:'',
                address:'',
                country_id:'',
                state_id:'',
                city_id:'',
                pincode:'',
                countryid:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/register/add/list').then(response => {
                    this.statelist = response.data.statelist;
                    this.citylist = response.data.citylist;
                    this.countrylist = response.data.countrylist;
                    this.countryid = response.data.countrylist['IN']['id'];
                    // console.log(this.countryid)
                });
            },

            setTab(val,response)
            {
                this.tab=val;
                bus.$emit("tabValue", this.tab);
                bus.$emit("message", response.data.message);
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 
                
                let formData=new FormData();
                
                formData.append('church_name',this.church_name);  
                formData.append('address',this.address);  
                formData.append('state_id',this.state_id);                
                formData.append('city_id',this.city_id);
                formData.append('country_id',this.country_id);                
                formData.append('pincode',this.pincode);    
     
                axios.post('/register/stepOne',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {    
                    this.setTab(val,response);
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            reset()
            {
                this.church_name = '';
                this.address = '';
                this.state_id = '';
                this.city_id = '';
                this.pincode = '';
                this.country_id = '';
            },
        },

        created()
        {
            this.getData(); 
            bus.$on("tabValue", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });
        }
    }
</script>