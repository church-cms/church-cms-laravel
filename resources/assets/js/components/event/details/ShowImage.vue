<template>
<div>
<div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>


  <div>
   <VueUploadMultipleImage
          @upload-success="uploadImageSuccess"
          @before-remove="beforeRemove"
          @edit-image="editImage"
          @data-change="dataChange"
          @limit-exceeded="limitExceeded"
          ></VueUploadMultipleImage>   
  <div class="my-2">
          <input type="submit" id="submit" class="btn btn-primary submit-btn cursor-pointer w-full" name="submit" value="Submit" @click="saveImage()">
        </div>
  </div>
  <div v-bind:class="[isphotos==1?'block':'hidden']">
  <PhotosSlider  :url="this.url" :event_id="this.event_id"></PhotosSlider>
  </div>
  </div>
</template>
<script>
   import { bus } from "../../../app";
   import PhotosSlider from './PhotosSlider'
   import  VueUploadMultipleImage from '../../VueUploadMultipleImage'
import Loading from 'vue-loading-overlay';
/*  import 'vue-loading-overlay/dist/vue-loading.css';*/
   export default {
     props:['url','event_id'],
   data () {
         return {
               isphotos:0,

               image:[],
              success:null,
              errors:[],
              isLoading: false,
              fullPage: true,
              amount:null,
              count:null,
             }
           },
    components: {
       PhotosSlider,
       VueUploadMultipleImage,
      Loading,
    },
    methods: {
    uploadImageSuccess(formData, index, fileList) {
     this.image = fileList;
    },
    saveImage(val)
    {
      this.isLoading = true;
      let formData = new FormData();
      axios.post('/admin/upload/photos/'+this.event_id,{ data: this.image }).then(response => {
        this.success = response.data.message;
         bus.$emit("photoadded","photouploaded");
       if(this.success=="Photo added successfully"){
          //window.setTimeout(window.location.href = "/manager/show/projectview/"+this.project_id,1000000);
           this.isLoading=false;
         }
        else
        {
          this.success = response.data.message;
          this.isLoading=false;
        }
      });
    },

    beforeRemove (index, done, fileList) {
      console.log('index', index, fileList)
      var r = confirm("remove image")
      if (r == true) {
        done()
      } else {
      }
    },
    editImage (formData, index, fileList) {
      console.log('edit data', formData, index, fileList)
    },
    dataChange (data) {
      console.log(data)
    },
    limitExceeded(amount){
      console.log(amount)
      
    },
    onCancel() 
    {
      console.log('User cancelled the loader.')
    },

 

  },

  created()
  {

   // alert('hffhj');
    bus.$on("imageCount",data => {
      //alert(data);
      if(data>0)
      {
        this.isphotos=1;

      }
      
    });

    bus.$on('limit-exceeded',data => {
      //alert(data);
    
      this.errors['count']='Count Exceeded';
      console.log(this.errors['count']);

      //this.count='';
      //console.log(this.count);
   
    });
  }

   }
</script>
