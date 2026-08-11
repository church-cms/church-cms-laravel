<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="fired_at" class="tw-form-label">Fired At<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <datetime format="DD-MM-YYYY h:i:s" id="fired_at" v-model="fired_at" class="rounded w-full"></datetime>
                            <span v-if="errors.fired_at" class="text-red-500 text-xs font-semibold">{{ errors.fired_at[0] }}</span>
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
    import datetime from 'vuejs-datetimepicker';
    export default {
        props:['id' , 'mode' , 'date'],
        components: { datetime },
        data(){
            return{
                mailqueue:[],
                fired_at:'',       
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
                      
                formData.append('fired_at',this.fired_at);                                
                                 
                axios.post('/'+this.mode+'/mailqueue/edit/'+this.id,formData).then(response => {   
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            //
            this.fired_at = this.date;
        }
    }
</script>