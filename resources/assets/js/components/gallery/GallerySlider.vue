<template>
  <!-- <div class="lg:h-screen"> -->
  <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
  <div class="my-1 mx-2">
 <h2 class="text-gray-700 text-lg font-semibold italic">Photos({{Object.keys(this.photos).length}})</h2>
 </div>
    <div class="w-full">
      <div class="flex my-4"> 
      
        <div class="" @click="getshowSlider()">
          <div class="flex justify-center" v-if="Object.keys(photos).length>0">
            <img v-bind:src='photos[0].path' class="w-32 h-32 border border-gray-500 p-1 cursor-pointer" id="image">
          </div>
         <!--  <p class="font-semibold text-center my-1"><span class="ml-6">{{Object.keys(photos).length}}</span></p> -->
        
        </div>
      </div>
    </div>

  


       <div  v-if="this.showImage">    
 
    <div class="modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-3xl px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
          <h2 class="text-gray-700 text-base font-semibold italic">Photos</h2>
             <button class="modal-default-button text-2xl py-1"  @click="closeModal()">
                &times;
            </button>
          </div>


          <div class="modal-body">
          <carousel class="mt-0 my-custom-class" :per-page="1" :autoplay="true" :paginationEnabled="false" :autoplayHoverPause="true" :navigationClickTargetSize="25" :mouse-drag="false" :navigationEnabled="this.navigationEnabled" navigationPrevLabel="<img src='../../../../uploads/static/arrow-l.svg' class='btn btn-primary w-6 h-6'>" navigationNextLabel="<img src='../../../../uploads/static/arrow-r.svg' class='btn btn-primary w-6 h-6'>">
        <slide v-for="slider in photos" v-bind:key="slider.id">
            <div class="flex flex-row p-0">
              <img :src="slider.path" class="w-full">
             <a href="#" class="text-2xl py-1 justify-end" @click="deletePhoto(slider.id)">
                <img :src="url+'/uploads/icons/delete.svg'" class="w-5 h-5" style="filter: brightness(0);">
            </a>
            </div>
        </slide>
      </carousel>
       
          </div>
  </div>
 </div>
</div>
          
        </div>
      </div>
  </div>
</template>

<script>
import { bus } from "../../app";
  import VueCarousel from 'vue-carousel';
  Vue.use(VueCarousel);

  export default { 
      props:['url','gallery_id'],
      data(){
        return {
          pdate:'',
          images: [],
          photos: [],
          overlay : false,   
          navigationEnabled:true,
          showImage:0,
          dates:[
  {
    //path: this.url+'/uploads/events/event_1.jpg',
    default: 1,
    highlight: 1,
  }
],
          success:null,
          errors:[],
          segment:'admin',
          imageLink1: this.url+'/uploads/static/arrow-l.png',
          imageLink2: this.url+'/uploads/static/arrow-r.png',
          //imageLink3: this.url+'/uploads/static/bg.jpg',
        }
    },

    created() {
      //alert('asdas');

      this.getSliderImage();
      window.addEventListener('resize', this.handleResize)
      this.handleResize();
     
      this.closeModal();

       bus.$on("photoadded",data => {
        this.getSliderImage();
        //Object.keys(this.photos).length=data;
  
    });
    },

    destroyed() {
      window.removeEventListener('resize', this.handleResize)
    },
    
    methods:{
    
     getSliderImage()  {
//alert('dds');
        axios.get(this.url+'/admin/gallery/display/photos/'+this.gallery_id).then(response => {
         this.photos = response.data.data;
         
         bus.$emit("galleryCount",Object.keys(this.photos).length);

       
         //console.log(this.photos);
        });
        
        /*
        this.photos=[{"id":1,"photo":"\/uploads\/events\/event_1.jpg"},{"id":2,"photo":"\/uploads\/events\/event_2.jpg"},{"id":3,"photo":"\/uploads\/events\/event_3.jpg"},{"id":4,"photo":"\/uploads\/events\/event_1.jpg"},{"id":5,"photo":"\/uploads\/events\/event_2.jpg"},{"id":6,"photo":"\/uploads\/events\/event_3.jpg"}];
        //this.showImage=1;*/
       },

        deletePhoto(id)
        {
          var thisswal = this;
          swal({
            title: 'Are you sure',
            text: 'Do you want to delete this photo ?',
            icon: "info",
            buttons: [
              'No',
              'Yes'
            ],
            dangerMode: true,
          }).then(function(isConfirm) {
            if (isConfirm) 
            {
                axios.get(thisswal.url+ '/admin/gallery/photo/delete/'+ id).then(response => {
                  thisswal.success = response.data.success;
                  window.location.reload();
                }); 
            }
            else 
            {
              swal("Cancelled");
            }
          });
        },

       closeModal()
    {
     this.showImage=0;
    },
    getshowSlider(){
      this.showImage=1;
       
    },

      handleResize() 
      {
     // this.window.width = window.innerWidth;
      //this.window.height = window.innerHeight;
        if(window.innerWidth<=980)
        {
            this.navigationEnabled=false;
        }
        else
        {
          this.navigationEnabled=true;
        }
      }
    },
  }
</script>

<style scoped>
.VueCarousel-dot--active
{
  background-color: white! important;
}
   @media (max-width: 600px) {
.VueCarousel-navigation-button{
    display: none! important;
  }
}
@media screen and (max-width: 660px) {
  .VueCarousel-navigation-prev,
  .VueCarousel-navigation-next{
    display:none! important;
  }
}
.VueCarousel-dot-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border: none;
    background-color:black! important;
    padding: 0;
    border-radius: 100%;
    outline: none;
    cursor: pointer;
}
.tw-form-control-select {
    display: block;
    margin-top: 0.5rem;
   /* -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
   width: 100%; */
    background-color: #f7fafc;
    border-width: 1px;
    border-color: #edf2f7;
    color: #000;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 1rem;
    padding-right: 1rem;
    border-radius: 0.25rem;
}

.modal-mask {
  position: fixed;
  /*position: absolute;
  overflow: auto;*/
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
  overflow:auto;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin:10px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.text-danger
{
  color:red;
}
.my-custom-class .VueCarousel-navigation-button.VueCarousel-navigation-prev {
    border: 1px solid;
    left: -30px;
    padding: 10px !important;
}

</style>