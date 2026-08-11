<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="email_id" class="tw-form-label">Email<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select class="tw-form-control w-full" id="email_id" v-model="email_id" name="email_id">
                                <option value="" disabled>Select Email</option>
                                <option v-for="email in emailList" v-bind:value="email.id">{{ email.subject }}</option>
                            </select>
                            <span v-if="errors.email_id" class="text-red-500 text-xs font-semibold">{{ errors.email_id[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="delay_in_days" class="tw-form-label">Delay In Days<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="number" name="delay_in_days" id="delay_in_days" v-model="delay_in_days" class="tw-form-control w-full" placeholder="Delay In Days">
                            <span v-if="errors.delay_in_days" class="text-red-500 text-xs font-semibold">{{ errors.delay_in_days[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="delay_in_hours" class="tw-form-label">Delay In Hours</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="time" name="delay_in_hours" id="delay_in_hours" v-model="delay_in_hours" class="tw-form-control w-full">
                            <span v-if="errors.delay_in_hours" class="text-red-500 text-xs font-semibold">{{ errors.delay_in_hours[0] }}</span>
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
        props:['url' , 'mode' , 'id'],
        data(){
            return{
                list:[],       
                emailList:[],       
                email_id:'',
                delay_in_days:'',
                delay_in_hours:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get(this.url+'/'+this.mode+'/campaign/attachemail/edit/list/'+this.id).then(response => {
                    this.list = response.data;
                    //console.log(this.list); 
                    this.setData();  
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.emailList      = this.list.emailList;
                    this.email_id       = this.list.email_id;
                    this.delay_in_days  = this.list.delay_in_days;
                    this.delay_in_hours = this.list.delay_in_hours;
                }
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                                       
                formData.append('email_id',this.email_id);                 
                formData.append('delay_in_days',this.delay_in_days);                 
                formData.append('delay_in_hours',this.delay_in_hours);                 
       
                axios.post(this.url+'/'+this.mode+'/campaign/attachemail/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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