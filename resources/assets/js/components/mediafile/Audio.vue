<template>
    <div>
        <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
       <flash-message :position="'right bottom'" :timeout="3000" class="myCustomClass"></flash-message>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="px-3 py-3 mx-2">
            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="name" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                        <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full" placeholder="Title">
                        <span class="text-danger text-xs" v-if="errors.name">{{ errors.name[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label class="tw-form-label">Description</label>
                    </div>
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                        <textarea type="textarea" name="description" v-model="description" id="description" class="tw-form-control w-full" rows="3" placeholder="Description"></textarea>
                        <span class="text-danger text-xs" v-if="errors.description">{{ errors.description[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4 md:w-1/4">
                        <label for="audio_type" class="tw-form-label">Audio Type<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5 md:w-2/5 flex">
                        <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
                            <input type="radio" v-model="audio_type" name="audio_type" id="attach" value="attach">
                            <span class="text-sm mx-1">Attach</span>
                        </div>
                        <div class="w-1/2 flex items-center tw-form-control"> 
                            <input type="radio" v-model="audio_type" name="audio_type" id="record" value="record">
                            <span class="text-sm mx-1">Record</span>
                        </div>
                    </div>
                    <span class="text-danger text-xs" v-if="errors.audio_type">{{ errors.audio_type[0] }}</span>
                </div>
            </div>

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2" v-if="audio_type == 'record'">
                         <div class="my-6">
                            <audio-recorder
                                    :attempts="1"
                                    :time="10"
                                    :headers="this.record_header"
                                    :show-upload-button="false"
                                    :before-recording="callback"
                                    :pause-recording="callback"
                                    :after-recording="recordfile"
                                    :before-upload="bfupload"
                                    :successful-upload="callback"
                                    :failed-upload="callback"/>
                                    <span v-if="errors.audioupload" class="text-red-500 text-xs font-semibold">{{ errors.audioupload[0] }}</span>
                                  </div>
                       
                    </div>
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2" v-if="audio_type == 'attach'">
                        <uploader :options="audiooptions" ref="audiouploader" @file-complete="AudiofileComplete"  class="uploader-example" id="uploadaudio" name="uploadaudio" type="file">
                                    <uploader-unsupport></uploader-unsupport>
                                    <uploader-drop>
                                    <uploader-btn :single="single" v-show="this.audios=='' || this.audio_type=='attach'" :attrs="audioattrs">Select File</uploader-btn>
                                    </uploader-drop>
                                    <uploader-list></uploader-list>
                                </uploader>     
                                    </div>
                        <span v-if="errors.audios" class="text-red-500 text-xs font-semibold">{{ errors.audios[0] }}</span>
                    </div>
                </div>

                <div class="mt-6 mb-4">
                <a href="#" class="blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="submitForm()">Submit</a>
                 <a href="#" class="blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="resetForm()">Reset </a>
                 </div>
            </div>

            
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import 'vue-flash-message/dist/vue-flash-message.min.css';
    import VueFlashMessage from 'vue-flash-message';
    Vue.use(VueFlashMessage);
    import uploader from 'vue-simple-uploader';
    Vue.use(uploader)
    import AudioRecorder from 'vue-audio-recorder';
    Vue.use(AudioRecorder)
    export default{
        props:['url' , 'csrf'],
        components:{Loading},
        data(){
            return{
                name:'',
                description:'',
                //audio
                audiooptions: {
                    // https://github.com/simple-uploader/Uploader/tree/develop/samples/Node.js
                    chunkSize: 1000  * 1024 * 1024,
                    target: this.url+'/admin/media/storeaudios',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:false,
                    //query :{status:"hjhj"},
                },
                audioattrs:
                {
                    accept:'audio/*',
                },
                //api_url:this.url+'/admin/media/audio/record',
                record_header:{ 'X-CSRF-TOKEN': this.csrf },
                audio_type:'attach',
                audios:'',
                audioupload:'',
                //audio
                single:true,
                success:null,
                errors:[],
                isLoading: false,
                fullPage: true,
            }
        },

        methods:
        {
            resetForm()
            {
                /*this.name           = '';
                this.description    = '';
                this.audio_type     = '';
                this.videourl       = '';*/
                window.location.reload();
            },
            callback()
            {
                   navigator.mediaDevices.getUserMedia({audio: true, video:false});
            },
            AudiofileComplete()
            {
                this.audios=arguments[0].file;
            },
             bfupload(data)
            {
              //this.audioupload=data.blob;
              //console.log(audioupload);

            },
            recordfile(data)
            {
                this.audioupload=arguments[0].blob;
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;
                this.isLoading=true; 

                let formData=new FormData();

                formData.append('name',this.name);         
                formData.append('description',this.description);          
                formData.append('audio_type',this.audio_type);          
                formData.append('audios',this.audios); 
                formData.append('audioupload',this.audioupload);  
              
                axios.post('/admin/mediafile/audio/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.isLoading=false;
                    this.flash(this.success,'success',{timeout: 3000,
                    beforeDestroy() {
                     window.location.reload();
                    }});
                    //this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.isLoading=false;
                    this.flash('Please fill all fields ☹','error',{timeout: 3000});
                });
            },
        },
    }
</script>
<style scoped>
.myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>