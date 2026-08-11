<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="flex">
            <div class="tw-form-group w-1/2 " v-if="ref_name!=''">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="relation" class="tw-form-label">Relation<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="relation" v-model="relation" name="relation">
                            <option value="" disabled>Relationship</option>
                            <option v-for="list in relationlist" v-bind:value="list.id">{{ list.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.relation" class="text-red-500 text-xs font-semibold">{{ errors.relation[0] }}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
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
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="lastname" class="tw-form-label">Last Name</label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2"> 
                            <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="lastname" name="lastname" id="lastname" class="tw-form-control w-full member-icon" Placeholder="Last Name">
                    </div>
                    <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{errors.lastname[0]}}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="birth_firstname" class="tw-form-label">Birth First Name</label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2"> 
                            <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" class="tw-form-control w-full member-icon" id="birth_firstname" v-model="birth_firstname" name="birth_firstname" Placeholder="Birth First Name">
                    </div>
                    <span v-if="errors.birth_firstname" class="text-red-500 text-xs font-semibold">{{errors.birth_firstname[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="birth_lastname" class="tw-form-label">Birth Last Name</label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2"> 
                            <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="birth_lastname" name="birth_lastname" id="birth_lastname" class="tw-form-control w-full member-icon" Placeholder="Birth Last Name">
                    </div>
                    <span v-if="errors.birth_lastname" class="text-red-500 text-xs font-semibold">{{errors.birth_lastname[0]}}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="aadhar_number" class="tw-form-label">Aadhaar Number</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="aadhar_number" v-model="aadhar_number" name="aadhar_number" Placeholder="Aadhaar Number">
                    </div>
                    <span v-if="errors.aadhar_number" class="text-red-500 text-xs font-semibold">{{errors.aadhar_number[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="date_of_birth" class="tw-form-label">Date Of Birth<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="date" v-model="date_of_birth" name="date_of_birth" id="date_of_birth" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.date_of_birth" class="text-red-500 text-xs font-semibold">{{errors.date_of_birth[0]}}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="mobile_no" class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2">
                            <img :src="this.url+'/uploads/icons/mobile.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="mobile_no" name="mobile_no" id="mobile_no" class="tw-form-control w-full member-icon" placeholder="Mobile Number"> 
                    </div>
                    <span v-if="errors.mobile_no" class="text-red-500 text-xs font-semibold">{{errors.mobile_no[0]}}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="email" class="tw-form-label">Email ID</label>
                    </div>
                    <div class="mb-2">
                        <span class="absolute m-2">
                            <img :src="this.url+'/uploads/icons/send.svg'" class="w-4 h-4">
                        </span>
                        <input type="text" v-model="email" name="email" id="email" class="tw-form-control w-full member-icon" placeholder="Email ID">  
                    </div>
                    <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{errors.email[0]}}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="gender" class="tw-form-label">Gender<span class="text-red-500">*</span></label>
                    </div>
                    <div class="flex">
                        <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
                            <input type="radio" v-model="gender" name="gender" id="gender1" value="male">
                            <span class="text-sm mx-1">Male</span>
                        </div>
                        <div class="w-1/2 flex items-center tw-form-control lg:mr-8"> 
                            <input type="radio" v-model="gender" name="gender" id="gender2" value="female">
                            <span class="text-sm mx-1">Female</span>
                        </div>
                        <!-- hide for demo -->
                        <!-- <div class="w-1/2 flex items-center tw-form-control lg:mr-8 md:mr-8"> 
                            <input type="radio" v-model="gender" name="gender" id="gender3" value="transgender">
                            <span class="text-sm mx-1">Transgender</span>
                        </div> -->
                        <!-- hide for demo -->
                    </div>
                    <span v-if="errors.gender" class="text-red-500 text-xs font-semibold">{{errors.gender[0]}}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="profession" class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="profession" v-model="profession" name="profession">
                            <option value="" disabled>Occupation</option>
                            <option v-for="list in occupationlist" v-bind:value="list.id">{{ list.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.profession" class="text-red-500 text-xs font-semibold">{{errors.profession[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/3" v-if="checkInArray(this.professionlist,this.profession)">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="sub_occupation" class="tw-form-label">Sub-Category<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="sub_occupation" name="sub_occupation" id="sub_occupation" class="tw-form-control w-full" placeholder="Sub Category">
                    </div>
                    <span v-if="errors.sub_occupation" class="text-red-500 text-xs font-semibold">{{errors.sub_occupation[0]}}</span>
                </div> 
            </div>
        </div>

        <portal-target name="address"></portal-target>

        <div class="tw-form-group">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="country" class="tw-form-label">Country<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
                            <option value="" disabled>Select Country</option>
                            <option v-for="country in countrylist" v-bind:value="country.id">{{ country.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{ errors.country_id[0] }}</span>
                </div>

                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="state" class="tw-form-label">State<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
                            <option value="" disabled>Select State</option>
                            <option v-for="state in statelist[this.country_id]" v-bind:value="state.id">{{ state.name }}</option>
                        </select>  
                    </div>
                    <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{ errors.state_id[0] }}</span>
                </div>

                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="city" class="tw-form-label">City<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
                            <option value="" disabled>Select City</option>
                            <option v-for="city in citylist [this.state_id]" v-bind:value="city.id">{{ city.name }}</option>
                        </select>   
                    </div>
                    <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{errors.city_id[0]}}</span>
                </div>

                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="pincode" class="tw-form-label">Pincode<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" v-model="pincode" name="pincode" id="pincode"  placeholder="Enter Pincode">
                    </div>
                    <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{errors.pincode[0]}}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/4">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="family" class="tw-form-label">Family</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" v-model="family" name="family" id="family" Placeholder="Family Name">
                    </div>
                    <span v-if="errors.family" class="text-red-500 text-xs font-semibold">{{errors.family[0]}}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/4">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="marriage_status" class="tw-form-label">Marital Status<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="marriage_status" v-model="marriage_status" name="marriage_status">
                            <option value="" disabled>Marital Status</option>
                            <option v-for="list in maritalstatuslist" v-bind:value="list.id">{{ list.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.marriage_status" class="text-red-500 text-xs font-semibold">{{errors.marriage_status[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/4" v-if="this.marriage_status!='single'">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="marriage_start_date" class="tw-form-label">Marriage Date<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="date" class="tw-form-control w-full" v-model="marriage_start_date" id="marriage_start_date" name="marriage_start_date">
                    </div>
                    <span v-if="errors.marriage_start_date" class="text-red-500 text-xs font-semibold">{{errors.marriage_start_date[0]}}</span>
                </div> 
            </div>

            <!--  <div class="tw-form-group w-full lg:w-1/4" v-if="this.marriage_status!='single'">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="marriage_end_date" class="tw-form-label">Marriage End Date:</label>
                    </div>
                    <div class="mb-2">
                        <input type="date" class="tw-form-control w-full" v-model="marriage_end_date" name="marriage_end_date">
                    </div>
                    <span v-if="errors.marriage_end_date" class="text-red-500 text-xs font-semibold">{{errors.marriage_end_date[0]}}</span>
                </div> 
            </div> -->
        </div>

        <!-- <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="was_baptized" class="tw-form-label">Was Baptized:</label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="was_baptized" v-model="was_baptized" name="was_baptized">
                            <option value="" disabled>Baptism</option>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>
                    <span v-if="errors.was_baptized" class="text-red-500 text-xs font-semibold">{{errors.was_baptized[0]}}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/2" v-if="this.was_baptized=='yes'">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="baptism_date" class="tw-form-label">Baptism Date:</label>
                    </div>
                    <div class="mb-2">
                        <input type="date" v-model="baptism_date" name="baptism_date" id="baptism_date" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.baptism_date" class="text-red-500 text-xs font-semibold">{{errors.baptism_date[0]}}</span>
                </div> 
            </div>
        </div> -->
        <input type="hidden" name="membership_start_date" v-bind:value="membership_start_date">
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="avatar" class="tw-form-label">Avatar<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="file" name="avatar" @change="OnFileSelected" id="avatar" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.avatar" class="text-red-500 text-xs font-semibold">{{errors.avatar[0]}}</span>
                </div>
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="notes" class="tw-form-label">Notes</label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" class="tw-form-control w-full" v-model="notes" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <span v-if="errors.notes" class="text-red-500 text-xs font-semibold">{{errors.notes[0]}}</span>
                </div> 
            </div>
        </div>

        <portal-target name="submit-btn"></portal-target>
        <portal to="submit-btn">
            <div class="mt-4 pb-5">
                <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
                <input type="submit" class="hidden" id="submit-btn">
            </div>
        </portal>
    </div>
</template>

<script> 
    import PortalVue from "portal-vue";
    export default {
        props:['url' , 'ref_name' , 'membership_start_date' , 'membership_end_date'],
        data(){
            return {
                user:[],
                name:'',
                firstname:'',
                lastname:'',
                birth_firstname:'',
                birth_lastname:'',
                gender:'',
                date_of_birth:'',
                was_baptized:'',
                baptism_date:'',
                profession:'',
                sub_occupation:'',
                city_id:'',
                state_id:'',
                country_id:7,
                pincode:'',
                mobile_no:'',
                email:'',
                membership_type:'member',
                family:'',
                marriage_status:'',
                marriage_start_date:'',
                marriage_end_date:'',
                relation:'',
                aadhar_number:'',
                notes:'',
                avatar:'',
                countrylist:[],
                statelist:[],
                citylist:[],
                relationlist:[],
                maritalstatuslist:[],
                occupationlist:[],
                professionlist:['business','doctor','engineer','government_employee','lawyer','pastor','police','professionals','self_employed','teacher','others'],
                errors:[],
                success:null,
            }
        },
        methods:
        {
            resetForm()
            {
                this.name='';
                this.firstname='';
                this.lastname='';
                this.birth_firstname='';
                this.birth_lastname='';
                this.gender='';
                this.date_of_birth='';
                this.was_baptized='';
                this.baptism_date='';
                this.profession='';
                this.sub_occupation='';
                this.city_id='';
                this.state_id='';
                this.country_id=7;
                this.pincode='';
                this.mobile_no='';
                this.email='';
                this.membership_type='member';
                this.family='';
                this.marriage_status='';
                this.marriage_start_date='';
                this.marriage_end_date='';
                this.relation='';
                this.aadhar_number='';
                this.notes='';
                this.avatar='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null;  

                let formData=new FormData();

                formData.append('ref_name',this.ref_name);  
                formData.append('name',this.name);         
                formData.append('firstname',this.firstname);          
                formData.append('lastname',this.lastname);          
                formData.append('birth_firstname',this.birth_firstname);          
                formData.append('birth_lastname',this.birth_lastname);          
                formData.append('gender',this.gender);          
                formData.append('date_of_birth',this.date_of_birth);          
                formData.append('was_baptized',this.was_baptized);          
                formData.append('baptism_date',this.baptism_date);          
                formData.append('profession',this.profession);          
                formData.append('sub_occupation',this.sub_occupation);          
                formData.append('city_id',this.city_id);
                formData.append('state_id',this.state_id);          
                formData.append('country_id',this.country_id);          
                formData.append('pincode',this.pincode);          
                formData.append('mobile_no',this.mobile_no);          
                formData.append('email',this.email);          
                formData.append('membership_type',this.membership_type);          
                formData.append('membership_start_date',this.membership_start_date);
                formData.append('membership_end_date',this.membership_end_date);   
                formData.append('family',this.family);          
                formData.append('marriage_status',this.marriage_status);          
                formData.append('marriage_start_date',this.marriage_start_date);       
                formData.append('marriage_end_date',this.marriage_end_date);
                formData.append('relation',this.relation);
                formData.append('aadhar_number',this.aadhar_number);  
                formData.append('notes',this.notes);          
                formData.append('avatar',this.avatar);

                axios.post('/admin/member/add/validationUser',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    $('#submit-btn').click(); 
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnFileSelected(event)
            {
                this.avatar = event.target.files[0];
            },

            checkInArray(array,value) 
            {
                if(array.includes(value))
                {
                    return true;
                }
            },

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
                    this.countrylist        =   this.user.countrylist;
                    this.statelist          =   this.user.statelist;
                    this.citylist           =   this.user.citylist;
                    this.occupationlist     =   this.user.occupationlist;
                    this.relationlist       =   this.user.relationlist;
                    this.maritalstatuslist  =   this.user.maritalstatuslist;
                }
            },
        },
    
        created()
        {
            this.getData();
        }
    }
</script>