<template>
    <div class="shadow border px-4">
        <div class="group" >
      
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="category" class="tw-form-label">Category<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <select class="tw-form-control w-full" id="category" v-model="category" name="category">
                            <option value="" disabled>Select Category</option>
                            <option v-for="(category, index) in categorylist" v-bind:value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.category" class="text-red-500 text-xs font-semibold">{{ errors.category[0] }}</span>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="group_type" class="tw-form-label">Group Type<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5">
                        <select name="group_type" id="group_type" v-model="group_type" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Group Type</option>
                            <option v-for="list in typelist" v-bind:value="list.id">{{ list.name }}</option>
                        </select>
                        <span v-if="errors.group_type" class="text-red-500 text-xs font-semibold">{{ errors.group_type[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="name" class="tw-form-label">Group Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full" placeholder="Enter name of Group">
                    </div>
                    <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{ errors.name[0] }}</span>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="cover_image" v-model="cover_image" class="tw-form-label">Upload Cover Image<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <input type="file" name="cover_image" @change="OnImageSelected" id="cover_image" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.cover_image" class="text-red-500 text-xs font-semibold">{{ errors.cover_image[0] }}</span>
                </div>
            </div>
        
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 my-2">
                        <textarea type="text" name="description" id="description" v-model="description" class="tw-form-control w-full" rows="3"></textarea>
                    </div>
                    <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
                </div>
            </div>
      
            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Upload</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
            </div>
        </div>

    
    </div>
</template>

<script>
    export default {
    props:['id','count'],
      data(){
        return{
            group:[],
            categorylist:[],
            category:'',
            group_type:'',
            name:'',
            cover_image:'',
            description:'',
            typelist:[{id : 'common_interests' , name : 'Common Interests'} , {id : 'everyone' , name : 'Everyone'} , {id : 'married_couples' , name : 'Married Couples'} , {id : 'men' , name : 'Men'} , {id : 'women' , name : 'Women'} , {id : 'young_adults' , name : 'Young Adults'} , {id : 'youth' , name : 'Youth'}],
            errors:[],
            success:null,
        }
      },
        
      methods:
        {
            resetForm()
            {
              this.category='';
              this.group_type='';
              this.name='';
              this.cover_image='';
              this.description='';  
            }, 

            checkForm()
            {
              this.errors=[];
              this.success=null; 

              let formData=new FormData();

              formData.append('category',this.category);          
              formData.append('group_type',this.group_type);                    
              formData.append('name',this.name);         
              formData.append('cover_image',this.cover_image);          
              formData.append('description',this.description);          
              
              axios.post('/admin/group/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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

            getData()
            {
                axios.get('/admin/group/get').then(response => {
                    this.group = response.data;
                    this.setData();   
            });
            },

            setData()
            {
                if(Object.keys(this.group).length>0)
                {
                    this.categorylist = this.group.categorylist;
                //console.log(this.categorylist);
                }
            },

        },
      created()
      {
        this.getData();
        //alert(this.category);
      }
  }
</script>