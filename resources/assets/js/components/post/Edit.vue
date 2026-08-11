<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 px-2">
                    <div class="mb-2">
                        <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                    <div>
                        <input type="text" v-model="title" name="title" id="title" class="tw-form-control w-full" placeholder="Title">
                        </div>
                        <div class="text-gray-700 text-xs my-1" v-text="(30 - title.length)+'/'+30" style="text-align: right"></div>               
                    </div>
                    <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{errors.title[0]}}</span>
                </div> 
            </div>
        </div>

         <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 px-2">
                    <div class="mb-2">
                        <label for="category" class="tw-form-label">Category<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                    <div>
                        <select v-model="category" class="tw-form-control w-full mt-1">
                        <option value="">Select Category</option>
                        <option v-for="item in categorylist" :key="item.id" :value="item.id">{{ item.name }}</option>
                    </select>
                        </div>
                                      
                    </div>
                    <span v-if="errors.category" class="text-red-500 text-xs font-semibold">{{ errors.category[0] }}</span>
                </div> 
            </div>
        </div>
         
        <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 px-2">
                    <div class="mb-2">
                        <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                    <div style="height:200px;">
                        <quill-editor ref="myQuillEditor" v-model="description" name="description" :options="option" style="height:150 !important;" /></quill-editor>
                    </div>
                        <div class="text-gray-700 text-xs my-1" v-text="(500 - description.length)+'/'+500" style="text-align: right"></div>               
                    </div>
                    <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
                </div> 
            </div>
        </div>
        <input type="hidden" v-if="this.description != null" name="description" :value="this.description">

        <div class="my-6">
            <div class="w-3/4 flex flex-wrap items-center mb-4 px-2"> <!-- justify-between  -->
                <div class="flex flex-wrap p-1" v-for="attachment in attachments">
                    <img :src="attachment.path" class="img-responsive w-32 h-32">
                    <div class=""> 
                        <a href="#" class="left-auto" @click="deleteAttachment(attachment.id)">
                            <svg class="w-2 h-2 m-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 47.971 47.971" style="enable-background:new 0 0 47.971 47.971;" xml:space="preserve" width="512px" height="512px"><g><g><path d="M28.228,23.986L47.092,5.122c1.172-1.171,1.172-3.071,0-4.242c-1.172-1.172-3.07-1.172-4.242,0L23.986,19.744L5.121,0.88   c-1.172-1.172-3.07-1.172-4.242,0c-1.172,1.171-1.172,3.071,0,4.242l18.865,18.864L0.879,42.85c-1.172,1.171-1.172,3.071,0,4.242 C1.465,47.677,2.233,47.97,3,47.97s1.535-0.293,2.121-0.879l18.865-18.864L42.85,47.091c0.586,0.586,1.354,0.879,2.121,0.879   s1.535-0.293,2.121-0.879c1.172-1.171,1.172-3.071,0-4.242L28.228,23.986z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#718096"/></g></g> </svg>
                        </a>
                    </div>
                </div>
                <span v-if="errors.count" class="text-red-500 text-xs font-semibold">{{errors.count[0]}}</span>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 px-2">
                    <div class="mb-2">
                        <label for="attachment" class="tw-form-label">Attachment</label>
                    </div>
                    <div class="mb-2">
                        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-error="errorMessage"></vue-dropzone>
                        <div class="mt-1"><a href="#" class="btn btn-reset reset-btn" @click="removeAllFiles()">Remove All Files</a> </div>
                    </div>
                </div> 
            </div>
        </div>

        <!-- <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="visibility" class="tw-form-label">Visibility</label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="visibility" v-model="visibility" name="visibility">
                            <option value="" disabled>Select Visibility</option>
                            <option v-for="visible in visiblelist" v-bind:value="visible.id">{{ visible.name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.visibility" class="text-red-500 text-xs font-semibold">{{errors.visibility[0]}}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/2" v-if="this.visibility == 'select_class'">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="visible_for" class="tw-form-label">Select Class</label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="visible_for" v-model="visible_for" name="visible_for">
                            <option value="" disabled>Select Class</option>
                            <option v-for="standard in standardLinkList" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                        </select>
                    </div>
                    <span v-if="errors.visible_for" class="text-red-500 text-xs font-semibold">{{errors.visible_for[0]}}</span>
                </div> 
            </div>
        </div> -->

        <div class="flex flex-col  w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-1/2 py-3 px-2">
                <div class="flex items-center">
                    <div class="w-6">
                        <input type="checkbox" v-model="post_later" name="post_later" id="post_later" class="tw-form-control w-full" @click="showDate($event)">
                    </div>
                    <div class="mx-1">
                        <label for="post_later" class="tw-form-label">Post Later</label>
                    </div>
                    <span v-if="errors.post_later" class="text-red-500 text-xs font-semibold">{{errors.post_later[0]}}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 hidden px-2" id="date">
                    <div class="mb-2">
                        <label for="posted_at" class="tw-form-label">Date Time<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <datetime format="DD-MM-YYYY h:i:s" name="posted_at" v-model="posted_at" class="w-full rounded" id="posted_at"></datetime>
                    </div>
                    <span v-if="errors.posted_at" class="text-red-500 text-xs font-semibold">{{errors.posted_at[0]}}</span>
                </div> 
            </div>   
        </div>

        <div class="mb-6 px-2">
            <a href="#" id="submit" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script> 
    import datetime from 'vuejs-datetimepicker';
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import VueQuillEditor from 'vue-quill-editor'
    import 'quill/dist/quill.core.css' // import styles
    import 'quill/dist/quill.snow.css' // for snow theme
    import 'quill/dist/quill.bubble.css' // for bubble theme
    export default {
        components:{ 
            datetime ,
            vueDropzone: vue2Dropzone,
        },
        props:['url' , 'entity_id' , 'entity_name' , 'id' , 'mode'],
        data(){
            return {
                standardLinkList:[],
                attachments:[],
                 category: '',
                title:'',
                description:'',
                visibility:'',
                visible_for:'',
                posted_at:'',
                post_later:'',
                count:'',
                option:{
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }]
                        ]
                    },
                    placeholder: '', 
                },
                dropzoneOptions: {
                    url: this.url+'/'+this.mode+'/post/edit/attachment/'+this.id,
                    method:'post',
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    },
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    acceptedFiles: ".jpg,.jpeg,.png",
                    addRemoveLinks:true,
                    maxFilesize: 0.5,
                    paramName: "file", // The name that will be used to transfer the file
                    parallelUploads: 6,
                    maxFiles:6,
                },
                visiblelist:[{id:'select_page', name:'Select Page'}],
                errors:[],
                categorylist:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get(this.url+'/'+this.mode+'/post/editList/'+this.id).then(response => {
                    this.post = response.data;
                    this.setData();
                });
                 
            },

             getDatas()
            {
               
                 axios.get(this.url + '/' + this.mode + '/postCategory/list').then(response => {
                    this.categorylist = response.data.data;
                });
            },

            setData()
            {
                if( Object.keys(this.post).length > 0)
                {
                    //this.standardLinkList = this.post.standardLinkList;
                    this.attachments      = this.post.attachment;
                    this.title            = this.post.title;
                    this.category            = this.post.category;
                    this.description      = this.post.description;
                    //this.visibility       = this.post.visibility;
                    //this.visible_for      = this.post.visible_for;
                    this.posted_at        = this.post.post_created_at;
                    this.post_later       = this.post.post_later;
                    if(this.post_later == 1)
                    {
                        $('#date').addClass('block').removeClass('hidden');
                    }
                    else
                    {
                        $('#date').addClass('hidden').removeClass('block');
                    }
                }
            }, 

            init() 
            {
                let myDropzone = this.$refs.myVueDropzone;
                // If you use the maxFiles option, make sure you adjust it to the correct amount:
                let fileCountOnServer = this.attachments.length; // The number of files already uploaded
                myDropzone.options.maxFiles = myDropzone.options.maxFiles - fileCountOnServer;
            },

            sendingEvent (file, xhr, formData) 
            {
                formData.append('count', this.attachments.length);
            },

            errorMessage(file, message, xhr)
            {
                $('.dz-error-message span').text(message.errors.count[0]);
            },

            processImage() 
            {
                let formData=new FormData(); 

                formData.append('attachment_count',this.attachments.length); 

                for(let i=0 ; i < this.attachments.length ; i++)
                {
                    if(typeof this.attachments[i] !== "undefined")
                    {
                        formData.append('attachment'+i,this.attachments[i]['original_path']);
                    }
                    else
                    {
                        formData.append('attachment'+i,'');
                    }
                }

                axios.post(this.url+'/'+this.mode+'/post/edit/attachment/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.success = response.data.success;
                    this.$refs.myVueDropzone.processQueue();
                    //window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            removeAllFiles() 
            {
                this.$refs.myVueDropzone.removeAllFiles();
            },

            deleteAttachment(id)
            {
                this.attachments.splice(id,1);
                this.init();
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData(); 

                formData.append('entity_id',this.entity_id);          
                formData.append('entity_name',this.entity_name);        
                formData.append('title',this.title);
                formData.append('category',         this.category);
                formData.append('description',this.description);
                //formData.append('visibility',this.visibility);          
                //formData.append('visible_for',this.visible_for);          
                formData.append('posted_at',this.posted_at);           
                formData.append('post_later',this.post_later);

                axios.post(this.url+'/'+this.mode+'/post/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {    
                    this.processImage();
                    this.success = response.data.success;
                    //window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            showDate(e)
            {
                if(e.target.checked)
                {
                    $('#date').addClass('block').removeClass('hidden');
                }
                else
                {
                    $('#date').addClass('hidden').removeClass('block');
                }
            },
        },
    
        created()
        {
            this.getData();
            this.getDatas();
        }
    }
</script>