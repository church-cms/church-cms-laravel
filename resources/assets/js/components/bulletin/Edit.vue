<template>
<div class="my-3">

    <!-- Success -->
    <div v-if="success" class="alert alert-success">
        {{ success }}
    </div>

    <div class="bulletin shadow px-4 py-1 bg-white">

        <!-- NAME -->
        <div class="my-5">
            <label>Bulletin Name <span class="text-red-500">*</span></label>
            <input type="text" v-model="name" class="tw-form-control w-full">
            <span v-if="errors.name" class="text-red-500 text-xs">{{errors.name[0]}}</span>
        </div>

        <!-- YEAR -->
        <div class="my-5">
            <label>Year <span class="text-red-500">*</span></label>
            <select v-model="year" class="tw-form-control w-full">
                <option value="" disabled>Select Year</option>
                <option 
                    v-for="i in range(start,end)" 
                    :key="i" 
                    :value="i">
                    {{ i }}
                </option>
            </select>
            <span v-if="errors.year" class="text-red-500 text-xs">{{errors.year[0]}}</span>
        </div>

        <!-- TYPE -->
        <div class="my-5">
            <label>Type <span class="text-red-500">*</span></label>
            <select v-model="type" class="tw-form-control w-full">
                <option value="" disabled>Select Type</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
            </select>
        </div>

        <!-- WEEK -->
        <div v-if="type=='week'" class="my-5">
            <label>Week</label>
            <select v-model="week" class="tw-form-control w-full">
                <option value="" disabled>Select Week</option>
                <option v-for="n in 52" :key="n" :value="n">{{n}}</option>
            </select>
        </div>

        <!-- MONTH -->
        <div v-if="type=='month'" class="my-5">
            <label>Month</label>
            <select v-model="month" class="tw-form-control w-full">
                <option value="" disabled>Select Month</option>
                <option v-for="m in months" :key="m.num" :value="m.num">{{m.name}}</option>
            </select>
        </div>

        <!-- COVER IMAGE -->
        <div class="my-5">
            <label>Cover Image</label>
            <input type="file" @change="OnImageSelected"  name="cover_image"  id="cover_image" accept="image/*">

            <span v-if="errors.cover_image" class="text-red-500 text-xs">
                {{errors.cover_image[0]}}
            </span>
            <br/>
            <div v-if="old_cover_image">
                <img :src="old_cover_image" width="100">
            </div>
        </div>

        <!-- PDF -->
        <div class="my-5">
            <label>Bulletin File</label>
            <input type="file" id="path" name="path" @change="OnFileSelected" accept="application/pdf">

            <span v-if="errors.path" class="text-red-500 text-xs">
                {{errors.path[0]}}
            </span>
            <br/>
            <div v-if="old_file">
                <a :href="old_file" target="_blank">View Current File</a>
            </div>
        </div>

        <!-- BUTTON -->

          <div class="my-6">
                <a href="#" dusk="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="updateData()">Update</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
            </div>

    </div>
</div>
</template>

<script>
export default {

props:['id'],

data(){
    return{
        name:'',
        cover_image:'',
        path:'',
        old_cover_image:'',
        old_file:'',
        type:'',
        week:'',
        month:'',
        year:'',

        // ✅ FIXED
        start:2017,
        end:new Date().getFullYear(),

        errors:{},
        success:null,

        months:[
            {num:'01',name:'January'},
            {num:'02',name:'February'},
            {num:'03',name:'March'},
            {num:'04',name:'April'},
            {num:'05',name:'May'},
            {num:'06',name:'June'},
            {num:'07',name:'July'},
            {num:'08',name:'August'},
            {num:'09',name:'September'},
            {num:'10',name:'October'},
            {num:'11',name:'November'},
            {num:'12',name:'December'}
        ]
    }
},

methods:{

    // ✅ GET DATA
    getData(){
        axios.get('/admin/bulletin/getdetails/'+this.id).then(res=>{
            let data = res.data;

            this.name = data.name;
            this.type = data.type;
            this.week = data.week;
            this.month = data.month;
            this.year = data.year;

            this.old_cover_image =data.cover_image;

             //this.old_cover_image ='http://church-cms-laravel.test/storage/'+data.CoverImagePath;

            this.old_file ='/admin/bulletin/download/'+this.id;

            // OPTIONAL if API sends year range
            if(data.start && data.end){
                this.start = parseInt(data.start);
                this.end = parseInt(data.end);
            }
        });
    },

    // ✅ IMAGE VALIDATION
    OnImageSelected(e){
        let file = e.target.files[0];
        this.errors = {};

        if(!file) return;

        let allowed = ['image/jpeg','image/png','image/jpg','image/webp'];

        if(!allowed.includes(file.type)){
            this.errors.cover_image = ['Only JPG, PNG, WEBP allowed'];
            return;
        }

        if(file.size > 2*1024*1024){
            this.errors.cover_image = ['Max 2MB'];
            return;
        }

        this.cover_image = file;
    },

    // ✅ PDF VALIDATION
    OnFileSelected(e){
        let file = e.target.files[0];
        this.errors = {};

        if(!file) return;

        if(file.type !== 'application/pdf'){
            this.errors.path = ['Only PDF allowed'];
            return;
        }

        if(file.size > 5*1024*1024){
            this.errors.path = ['Max 5MB'];
            return;
        }

        this.path = file;
    },

    // ✅ UPDATE
    updateData(){

        if(Object.keys(this.errors).length > 0) return;

        let formData = new FormData();

        formData.append('name',this.name);
        formData.append('type',this.type);
        formData.append('week',this.week);
        formData.append('month',this.month);
        formData.append('year',this.year);

        if(this.cover_image){
            formData.append('cover_image',this.cover_image);
        }

        if(this.path){
            formData.append('path',this.path);
        }

        axios.post('/admin/bulletin/update/'+this.id,formData)
        .then(res=>{
            this.success = res.data.success;

            if(res.data.img_path!=''){
               this.old_cover_image =res.data.img_path; 
            }

              
        })
        .catch(err=>{
            this.errors = err.response.data.errors;
        });
    },

    // ✅ FIXED RANGE FUNCTION
    range(start,end){
        let arr=[];
        for(let i=end;i>=start;i--){
            arr.push(i);
        }
        return arr;
    }

},

created(){
    this.getData();
}
}
</script>