<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <table class="w-full text-sm">
            <tr class="">
                <td class="py-3 px-2" style="width: 20%;">User Name</td>
                <td class="py-3 px-2">
                    <a :href="this.url+'/admin/member/show/'+help.user_name">{{ help.user_fullname }}</a>
                </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Title</td>
                <td class="py-3 px-2">{{ help.title }} </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Description</td>
                <td class="py-3 px-2">{{ help.description }} </td>
            </tr> 
            <tr class="">
                <td class="py-3 px-2">Contact Details</td>
                <td class="py-3 px-2">{{ help.contact_details }}</td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Status</td>
                <td class="py-3 px-2">
                    <select for="status" name="status" v-model="status" id="status" class="tw-form-control w-1/2">
                        <option value="" disabled>Select Status</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                    </select>
                </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2"></td>
                <td class="py-3 px-2">
                    <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{ errors.status[0] }}</span>
                </td> 
            </tr>  
            <tr class="" v-if="this.status == 'approve'">
                <td class="py-3 px-2">Expired At<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <select for="expired_at" name="expired_at" v-model="expired_at" id="expired_at"  class="tw-form-control w-1/2">
                        <option value="" disabled>Select Expire Within Days</option>
                        <option value="1">1</option>
                        <option value="3">3</option>
                        <option value="5">5</option>
                        <option value="7">7</option>
                    </select>
                </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2"></td>
                <td class="py-3 px-2">
                    <span v-if="errors.expired_at" class="text-red-500 text-xs font-semibold">{{ errors.expired_at[0] }}</span>
                </td> 
            </tr>  
            <tr class="" v-if="this.status == 'reject'">
                <td class="py-3 px-2">Comments<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <input type="text" name="comments" v-model="comments" id="comments" class="tw-form-control w-1/2" placeholder="Comments">
                </td>   
            </tr>
            <tr class="">
                <td class="py-3 px-2"></td>
                <td class="py-3 px-2">
                    <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{ errors.comments[0] }}</span>
                </td> 
            </tr>  
            <tr>
                <td class="py-3 px-2">
                    <a href="#" class="btn btn-primary submit-btn" @click="updateDetails()">Submit</a>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        props:['url','id'],
        data () {
            return {
                help: [],
                status:'',
                comments:'',
                expired_at:'',
                errors:[],
                success:null,
            }
        },

        methods: 
        {
            getDetails()
            {
                axios.get('/admin/help/showDetails/'+this.id).then(response => {
                    this.help = response.data;
                });
            },

            updateDetails()
            {
                this.errors=[];
                this.success=null;  

                let formData=new FormData();
             
                formData.append('status',this.status);          
                formData.append('expired_at',this.expired_at);
                formData.append('comments',this.comments);

                axios.post('/admin/help/update/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.message;
                    window.location.href='/admin/helps';
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            this.getDetails();
        }
    }
</script>