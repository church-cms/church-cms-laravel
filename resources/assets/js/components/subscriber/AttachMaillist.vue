<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="mailinglist_id" class="tw-form-label">Mailing List<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <select class="tw-form-control w-full" id="mailinglist_id" v-model="mailinglist_id" name="mailinglist_id">
                                <option value="" disabled>Select Mailing List</option>
                                <option v-for="list in mailinglist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                            <span v-if="errors.mailinglist_id" class="text-red-500 text-xs font-semibold">{{ errors.mailinglist_id[0] }}</span>
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
        props:['mode' , 'subscriber_id'],
        data(){
            return{
                mailinglist:[],
                mailinglist_id:'',       
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            resetForm()
            {
                this.mailinglist_id='';
            }, 

            getData()
            {
                axios.get('/'+this.mode+'/mailinglist/list').then(response => {
                    this.mailinglist       = response.data.data;
                    //console.log(this.mailinglist);   
                });
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                         
                formData.append('subscriber_id',this.subscriber_id);                 
                formData.append('mailinglist_id',this.mailinglist_id);                 
                                           
                axios.post('/'+this.mode+'/subscriber/attach/mailinglist/'+this.subscriber_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
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