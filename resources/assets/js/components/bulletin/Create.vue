<template>
    <div class="my-3">
       <!-- <div class="bulletin shadow px-4 py-1 bg-white" v-if="parseInt(this.count)<=parseInt(this.no_of_bulletins)"> -->
        <div class="bulletin shadow px-4 py-1 bg-white" >
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="name" class="tw-form-label">Bulletin Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full" placeholder="Enter name of Bulletin">
                    </div>
                    <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                </div>
            </div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="year" class="tw-form-label">Year<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5">
                        <select name="year" v-model="year" id="year" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Year</option>
                            <option v-for="i in range(start,end)" v-bind:value="i">{{i}}</option>
                        </select>
                        <span v-if="errors.year" class="text-red-500 text-xs font-semibold">{{errors.year[0]}}</span>
                    </div>
                </div>
            </div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="type" class="tw-form-label">Type<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5">
                        <select name="type" v-model="type" id="type" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Type</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                        </select>
                        <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
                    </div>
                </div>
            </div>
            <div class="my-5" v-if="this.type == 'week'">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="week" class="tw-form-label">Week<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5">
                        <select name="week" v-model="week" id="week" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Week</option>
                            <option v-for="n in week_count" v-bind:value="n">{{n}}</option>
                        </select>
                        <span v-if="errors.week" class="text-red-500 text-xs font-semibold">{{errors.week[0]}}</span>
                    </div>
                </div>
            </div>
            <div class="my-5" v-if="this.type == 'month'">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="month" class="tw-form-label">Month<span class="text-red-500">*</span></label>
                    </div>
                    <!-- <div class="my-2 w-full lg:w-2/5">
                        <datepicker name="month" v-model="month" :minimumView="'month'" :maximumView="'year'" :initialView="'year'" format="MMMM yyyy"></datepicker>
                    </div> -->
                    <div class="my-2 w-full lg:w-2/5">
                        <select name="month" v-model="month" id="month" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Month</option>
                            <option v-for="month in months" v-bind:value="month.num">{{ month.name }}</option>
                        </select>
                        <span v-if="errors.month" class="text-red-500 text-xs font-semibold">{{errors.month[0]}}</span>
                    </div>
                </div>
            </div>
             <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="cover_image" v-model="cover_image" class="tw-form-label">Upload Cover Image</label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <input type="file" name="cover_image" @change="OnImageSelected" id="cover_image" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.cover_image" class="text-red-500 text-xs font-semibold">{{errors.cover_image[0]}}</span>
                </div>
            </div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="path" v-model="path" class="tw-form-label">Bulletin File<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <input type="file" name="path" @change="OnFileSelected" id="path" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.path" class="text-red-500 text-xs font-semibold">{{errors.path[0]}}</span>
                </div>
            </div>
            <div class="my-6">
                <a href="#" dusk="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
            </div>
        </div>

        
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    export default {
        props:['count'],
        components: {
            Datepicker
        },
        data(){
            return{
                bulletin:[],
                name:'',
                cover_image:'',
                type:'',
                week:'',
                month:'',
                months:[{num:'01' , name:'January'} , {num:'02' , name:'February'} , {num:'03' , name:'March'} , {num:'04' , name:'April'} , {num:'05' , name:'May'} , {num:'06' , name:'June'} , {num:'07' , name:'July'} , {num:'08' , name:'August'} , {num:'09' , name:'September'} , {num:'10' , name:'October'} , {num:'11' , name:'November'} , {num:'12' , name:'December'}],
                year:'',
                week_count:52,
                start:'',
                end:'',
                path:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/admin/bulletin/getDate').then(response => {
                    this.bulletin = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.bulletin).length>0)
                {
                    this.start=parseInt(this.bulletin.start);
                    this.end=parseInt(this.bulletin.end);
                }
            },

            range(max,min)
            {
                var array = [],
                j = 0;
                for(var i = max; i >= min; i--)
                {
                    array[j] = i;
                    j++;
                }
                return array;
            },

            resetForm()
            {
                this.name='';
                this.cover_image='';
                this.type='';
                this.week='';
                this.month='';
                this.year=''; 
                this.path='';     
            }, 

            checkForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('name',this.name);         
                formData.append('type',this.type);          
                formData.append('week',this.week);          
                formData.append('month',this.month);          
                formData.append('year',this.year);          
                formData.append('cover_image',this.cover_image);          
                formData.append('path',this.path);          
              
                axios.post('/admin/bulletin/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnImageSelected(event)
            {
                this.cover_image = event.target.files[0];
            },

            OnFileSelected(event)
            {
                this.path = event.target.files[0];
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>