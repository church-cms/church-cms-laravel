<template>
    <div class="">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <table class="w-full text-sm">
            <tr class="">
                <td class="py-3 px-2" style="width: 10%;">User Name</td>
                <td class="py-3 px-2">
                    <a :href="this.url+'/admin/member/show/'+prayer.user_name">{{ prayer.user_fullname }}</a>
                </td>
           </tr>
            <tr class="">
                <td class="py-3 px-2">Title</td>
                <td class="py-3 px-2">{{ prayer.title }} </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Description</td>
                <td class="py-3 px-2">{{ prayer.description }} </td>
            </tr> 
            <tr class="">
                <td class="py-3 px-2">Prayer Date</td>
                <td class="py-3 px-2">{{ prayer.date }}</td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Image</td>
                <td class="py-3 px-2">
                    <img :src="prayer.image" class="w-20 h-20">
                </td>
            </tr>
            <tr class="">
                <td class="py-3 px-2">Status<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <select for="status" name="status" v-model="status" id="status" class="tw-form-control w-1/4">
                        <option value="" disabled>Select Status</option>
                        <option value="approve">Approve</option>
                        <option value="cancel">Cancel</option>
                    </select><br>
                    <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{errors.status[0]}}</span>
                </td>
            </tr>
            <tr class="" v-if="this.status == 'cancel'">
                <td class="py-3 px-2">Comments<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <textarea type="text" name="comments" v-model="comments" id="comments" class="tw-form-control w-1/2" placeholder="Comments" rows="3"></textarea><br>
                    <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{errors.comments[0]}}</span>
                </td> 
            </tr>
            <tr class="flex flex-row w-full items-center" v-if="this.status == 'approve'">
                <input type="checkbox" name="publish_later" v-model="publish_later" class="tw-form-control w-full" @click="enableDate($event)"><span class="whitespace-no-wrap">Publish Later</span>
            </tr>
            <tr class="" v-if="this.show == 'executed' && this.status == 'approve'">
                <td class="py-3 px-2">Date Time<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <datetime format="DD-MM-YYYY h:i:s" name="publish_at" v-model="publish_at" class="w-1/4 rounded" id="publish_at"></datetime><br>
                    <span v-if="errors.publish_at" class="text-red-500 text-xs font-semibold">{{ errors.publish_at[0] }}</span>
                </td>
            </tr>  
            <tr class="" v-if="this.status == 'approve'">
                <td class="py-3 px-2">Expire At<span class="text-red-500">*</span></td>
                <td class="py-3 px-2">
                    <datetime format="DD-MM-YYYY h:i:s" name="expire_at" v-model="expire_at" class="w-1/4 rounded" id="expire_at"></datetime><br>
                    <span v-if="errors.expire_at" class="text-red-500 text-xs font-semibold">{{ errors.expire_at[0] }}</span>
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
    import datetime from 'vuejs-datetimepicker';
    export default {
        props:['url','id'],
        components:
        {  
            datetime,
        },
        data () {
            return {
                prayer: [],
                status:'',
                comments:'',
                publish_later:'',
                publish_at:'',
                expire_at:'',
                date:'',
                show:'',
                errors:[],
                success:null,
            }
        },
        methods: 
        {
            enableDate(e)
            {
                if (e.target.checked) 
                {
                    this.publish_later = 1;
                    this.show = 'executed';
                }
                else
                {
                    this.publish_later = 0;
                    this.show = '';
                }
            },

            getDetails()
            {
                axios.get('/admin/prayerrequest/approveList/'+this.id).then(response => {
                    this.prayer = response.data;
                    this.expire_at = response.data.date;
                    this.date = response.data.date;
                });
            },

            updateDetails()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
             
                formData.append('status',this.status);
                formData.append('comments',this.comments);
                formData.append('publish_later',this.publish_later);
                formData.append('publish_at',this.publish_at);
                formData.append('expire_at',this.expire_at);
                formData.append('date',this.date);

                axios.post('/admin/prayerrequest/approve/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    window.location.href='/admin/prayerrequests';
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