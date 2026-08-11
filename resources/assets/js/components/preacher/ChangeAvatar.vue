
<template>
<div class="container mx-auto">
<h1 class="admin-h1 mb-3">Change Avatar</h1>
<div class="bg-white shadow border border-grey p-5">
	<form>
 <div v-if="this.success!=null" class="alert alert-success font-muller text-hayvn-purple" id="success-alert">{{this.success}}</div>
 <div class="flex flex-wrap">
 <div class="w-24 mr-4">
    <img v-bind:src="this.avatar" class="img-responsive" height="70" width="90">
 </div>
      <div class="w-24" v-if="image">
     <img :src="image" class="img-responsive h-full">
 </div>
<!--  <div class="w-1/3" v-if="image">
     <img :src="image" class="img-responsive" height="70" width="90">
 </div> -->
 </div>
   <div class="row">
       <div class="my-3">
        <input type="file" @change="OnFileSelected" id="image" class="new form-control">
 <label class="cursor-pointer flex p-1 border border-dashed w-full lg:w-56 md:w-56" for="image"><figure><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" class="w-6 h-6 mx-2"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z" fill="#3490dc"></path></svg></figure><span class="font-semibold my-1">Choose File</span></label>



         <span v-if="errors.avatar"><small class="text-muller text-red">{{errors.avatar}}</small></span>
                          </div>

      <div class="flex">
        <a href="#" class="btn btn-primary submit-btn" @click.prevent="uploadImage()">Upload</a>
                          </div>
                       </div>
</form>

</div>
</div>
</template>

<script>
	import axios from 'axios';
    export default {
          props:['url'],
        data(){
            return {
            	user:[],
                
                 image: '',
                 avatar:'',
                 errors:[],
                 trans:[],
                 success:null,
            }
        },
        methods: {
        		getdata()
        	{
        		axios.get('/preacher/getavatar').then(response => {
            this.user = response.data;
            this.setData();   
            });
        	},

        	setData()
        	{
        		if(Object.keys(this.user).length>0){
            			 this.avatar=this.user.avatar;
            			}
        	},

        	OnFileSelected(event){
        		//console.log(event);
        		this.avatar=event.target.files[0];
        		 let files = event.target.files || event.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
        		

        	},
        	   createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            uploadImage(){
            	this.errors=[];
            	if(this.avatar==null) this.errors["avatar"] = "Required";
            	const fd=new FormData();

            	fd.append('avatar',this.avatar);
                axios.post('/preacher/changeavatar',fd).
               then(response => {
                this.success = response.data.message;
		          	this.avatar = response.data.avatar;
                this.image= '';
                
		          
		         }).catch(error => {
		           this.errors = error.response.data.errors;
		         });
            }
        },
        mounted()
        {
        this.getdata();
        
        }

        
    }
</script>
<style>
.new
{
  width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
</style>