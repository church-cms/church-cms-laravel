<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="address" class="tw-form-label">Address<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="address" v-model="address" id="address" class="tw-form-control w-full" placeholder="Address">
                    </div>
                    <span v-if="errors.address" class="text-red-500 text-xs font-semibold">{{ errors.address[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="country_id" class="tw-form-label">Country<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
                            <option value="" disabled>Select Country</option>
                            <option v-for="country in countrylist" v-bind:value="country.id">{{country.name}}</option>
                        </select>
                    </div>
                    <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{ errors.country_id[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="state_id" class="tw-form-label">State<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
                            <option value="" disabled>Select State</option>
                            <option v-for="state in statelist[this.country_id]" v-bind:value="state.id">{{ state.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{ errors.state_id[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="city_id" class="tw-form-label">City<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
                            <option value="" disabled>Select City</option>
                            <option v-for="city in citylist [this.state_id]" v-bind:value="city.id">{{ city.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{ errors.city_id[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="pincode" class="tw-form-label">Pincode<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" v-model="pincode" name="pincode" id="pincode"  placeholder="Enter Pincode">
                    </div>
                    <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{ errors.pincode[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="tw-form-group w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="comments" class="tw-form-label">Comments<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" class="tw-form-control w-full" v-model="comments" id="comments" name="comments" rows="3"></textarea>
                    </div>
                    <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{ errors.comments[0] }}</span>
                </div>
            </div>
        </div>

        <div class="mt-4 pb-5">
            <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script> 
    export default {
        props:['url' , 'name'],
        data(){
            return {
                user:[],
                address:'',
                country_id:7,
                state_id:'',
                city_id:'',
                pincode:'',
                comments:'',
                countrylist:[],
                statelist:[],
                citylist:[],
                errors:[],
                success:null,
            }
        },
        methods:
        {
            getData()
            {
                axios.get('/admin/member').then(response => {
                    this.user = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.user).length>0)
                {
                    this.countrylist    =   this.user.countrylist;
                    this.statelist      =   this.user.statelist;
                    this.citylist       =   this.user.citylist;
                }
            },

            resetForm()
            {
                this.address        =   '';
                this.country_id     =   7;
                this.state_id       =   '';
                this.city_id        =   '';
                this.pincode        =   '';
                this.comments       =   '';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null;   

                let formData=new FormData();
          
                formData.append('address',this.address);         
                formData.append('country_id',this.country_id);
                formData.append('state_id',this.state_id);            
                formData.append('city_id',this.city_id);         
                formData.append('pincode',this.pincode);          
                formData.append('comments',this.comments);

                axios.post('/admin/member/exit/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
                    window.location = this.url+'/admin/members';  
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