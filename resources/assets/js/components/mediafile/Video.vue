<template>
    <div>
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
                        <label for="video_type" class="tw-form-label">Video Type<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-2/5 md:w-2/5 flex">
                        <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
                            <input type="radio" v-model="video_type" name="video_type" id="video_url" value="url">
                            <span class="text-sm mx-1">URL</span>
                        </div>
                        <div class="w-1/2 flex items-center tw-form-control"> 
                            <input type="radio" v-model="video_type" name="video_type" id="video_upload" value="upload">
                            <span class="text-sm mx-1">Upload</span>
                        </div>
                    </div>
                    <span class="text-danger text-xs" v-if="errors.video_type">{{ errors.video_type[0] }}</span>
                </div>
            </div>

            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2" v-if="video_type == 'url'">
                        <input type="text" name="videourl" v-model="videourl" id="videourl" class="tw-form-control w-full" placeholder="URL">
                        <span class="text-danger text-xs" v-if="errors.videourl">{{ errors.videourl[0] }}</span>
                    </div>
                    <div class="w-full lg:w-2/5 md:w-2/5 my-2" v-if="video_type == 'upload'">
                        <uploader :options="options" class="uploader-example" id="videoupload" name="videoupload" type="file">
                            <uploader-unsupport></uploader-unsupport>
                            <uploader-drop>
                                <uploader-btn :single="single" :attrs="attrs">Select File</uploader-btn>
                            </uploader-drop>
                            <uploader-list></uploader-list>
                        </uploader>
                        <span class="text-danger text-xs" v-if="errors.videoupload">{{ errors.videoupload[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 mb-4">
                <a href="#" class="blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="submitForm()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    import uploader from 'vue-simple-uploader';
    Vue.use(uploader)
    export default{
        props:['url' , 'csrf'],
        data(){
            return{
                name:'',
                description:'',
                video_type:'',
                videourl:'',
                videoupload:'',
                options: {
                    chunkSize: 1000  * 1024 * 1024,
                    target: this.url+'/admin/mediafile/video/save',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:false,
                },
                attrs:
                {
                    accept:'video/*',
                },
                single:true,
                success:null,
                errors:[],
            }
        },

        methods:
        {
            resetForm()
            {
                this.name           = '';
                this.description    = '';
                this.video_type     = '';
                this.videourl       = '';
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('name',this.name);         
                formData.append('description',this.description);          
                formData.append('video_type',this.video_type);          
                formData.append('videourl',this.videourl); 
              
                axios.post('/admin/mediafile/video/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },
    }
</script>