<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="my-6">
            <div class="flex items-center">
                <img :src="this.avatar_display" class="img-responsive w-12 h-12 rounded-full">
                <div class="mx-2">
                    <h2 class="font-semibold text-sm text-gray-700">{{ user.firstname }} {{ user.lastname }}</h2>
                    <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Avatar
                        <input type="file" size="60" name="avatar" id="avatar" @change="OnFileSelected">
                        <span v-if="errors.avatar" class="text-red-500 text-xs font-semibold">{{errors.avatar[0]}}</span>
                    </label> 
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
                    <span v-if="errors.firstname" class="text-red-500 text-xs font-semibold">{{errors.firstname[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="lastname" class="tw-form-label">Last Name </label>
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
                            <option v-for="list in professionlist" v-bind:value="list.id">{{ list.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.profession" class="text-red-500 text-xs font-semibold">{{errors.profession[0]}}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/3" v-if="checkInArray(this.occupationlist,this.profession)">
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

        <portal-target name="edit_address"></portal-target>

        <div class="tw-form-group">
            <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="country" class="tw-form-label">Country<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
                            <option v-for="country in countrylist" v-bind:value="country.id">{{country.name}}</option>
                        </select>
                    </div>
                    <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{errors.country_id[0]}}</span>
                </div>

                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="state" class="tw-form-label">State<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
                            <option value="" disabled>Select State</option>
                            <option v-for="state in statelist[this.country_id]" v-bind:value="state.id">{{state.name}}</option>
                        </select>  
                    </div>
                    <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{errors.state_id[0]}}</span>
                </div>

                <div class="w-full lg:w-1/4 lg:mr-8 md:pr-8">
                    <div class="mb-2">
                        <label for="city" class="tw-form-label">City<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
                            <option value="" disabled>Select City</option>
                            <option v-for="city in citylist [this.state_id]" v-bind:value="city.id">{{city.name}}</option>
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
            <div class="my-6">
                <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
                <input type="submit" class="hidden" id="submit-btn">
            </div>
        </portal>
    </div>
</template>

<script>
    export default{
        props:['url' , 'name'],
        data(){
            return{
                getdata:[],
                user:[],
                firstname:'',
                lastname:'',
                birth_firstname:'',
                birth_lastname:'',
                aadhar_number:'',
                date_of_birth:'',
                mobile_no:'',
                email:'',
                gender:'',
                profession:'',
                sub_occupation:'',
                country_id:7,
                state_id:'',
                city_id:'',
                pincode:'',
                avatar:'',
                avatar_display:'',
                notes:'',
                countrylist:[],
                statelist:[],
                citylist:[],
                professionlist:[{id:'business' , name:'Business'} , {id:'doctor' , name:'Doctor'} , {id:'engineer' , name:'Engineer'} , {id:'government_employee' , name:'Government Employee'} , {id:'home_maker' , name:'Home Maker'} , {id:'lawyer' , name:'Lawyer'} , {id:'pastor' , name:'Pastor'} , {id:'police' , name:'Police'} , {id:'professionals' , name:'Professionals'} , {id:'self_employed' , name:'Self Employed'} , {id:'student' , name:'Student'} , {id:'teacher' , name:'Teacher'} , {id:'others' , name:'Others'}],
                occupationlist:['business','doctor','engineer','government_employee','lawyer','pastor','police','professionals','self_employed','teacher','others'],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/member').then(response => {
                    this.getdata = response.data;
                    this.setData();   
                });
                axios.get('/admin/subadmin/editList/'+this.name).then(response => {
                    this.user = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.getdata).length>0)
                {
                    this.countrylist    =   this.getdata.countrylist;
                    this.statelist      =   this.getdata.statelist;
                    this.citylist       =   this.getdata.citylist;
                }
                if(Object.keys(this.user).length>0)
                {
                    this.firstname          =   this.user.firstname;
                    this.lastname           =   this.user.lastname;
                    this.birth_firstname    =   this.user.birth_firstname;
                    this.birth_lastname     =   this.user.birth_lastname;
                    this.aadhar_number      =   this.user.aadhar_number;
                    this.date_of_birth      =   this.user.date_of_birth;
                    this.gender             =   this.user.gender;
                    this.profession         =   this.user.profession;
                    this.sub_occupation     =   this.user.sub_occupation;
                    this.country_id         =   this.user.country_id;
                    this.state_id           =   this.user.state_id;
                    this.city_id            =   this.user.city_id;
                    this.pincode            =   this.user.pincode;
                    this.avatar_display     =   this.user.avatar;
                    this.notes              =   this.user.notes;
                }
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;    

                let formData=new FormData();

                formData.append('firstname',this.firstname);          
                formData.append('lastname',this.lastname);          
                formData.append('birth_firstname',this.birth_firstname);          
                formData.append('birth_lastname',this.birth_lastname);
                formData.append('aadhar_number',this.aadhar_number);  
                formData.append('date_of_birth',this.date_of_birth); 
                formData.append('gender',this.gender);               
                formData.append('profession',this.profession);          
                formData.append('sub_occupation',this.sub_occupation);           
                formData.append('country_id',this.country_id);  
                formData.append('state_id',this.state_id);        
                formData.append('city_id',this.city_id);         
                formData.append('pincode',this.pincode);         
                formData.append('avatar',this.avatar); 
                formData.append('notes',this.notes);  

                axios.post('/admin/subadmin/edit/validationUser/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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
                if( array.includes(value) )
                {
                    return true;
                }
            },
        },

        created()
        {
            this.getData();
        }
    }
</script>

<style scoped>
    .tw-label{
        color:#3492e2;
    }
    .tw-label input[type="file"] {
        display: none;
    }
    @media(max-width: 992px) {
        #map_canvas {
            width: 100% !important;
        }
    }
</style>