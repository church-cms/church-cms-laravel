<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <!-- Add Category Modal -->
        <div v-if="show === 'add'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Add Category</h2>
                        <button class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label class="tw-form-label">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" class="tw-form-control w-full mt-1" v-model="newCategoryName" placeholder="Name">
                        <span v-if="errors.name" class="text-red-500 text-xs">{{ errors.name[0] }}</span>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click.prevent="addCategory()">Submit</a>
                    </div>
                </div>
            </div>
        </div>

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
                       
                        <select v-model="category" class="tw-form-control w-full mt-1 mb-2">
                        <option value="" disabled>Select Category</option>
                        <option v-for="item in categorylist" :key="item.id" :value="item.id">{{ item.name }}</option>
                    </select>
                     <a href="#" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded whitespace-no-wrap hover:bg-indigo-700" @click.prevent="showCategory()">+ Add</a>
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

        <div class="flex flex-col lg:flex-row w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 px-2">
                    <div class="mb-2">
                        <label for="attachment" class="tw-form-label">Attachment</label>
                    </div>
                    <div class="mb-2">
                        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" v-on:vdropzone-sending="sendingEvent"></vue-dropzone>
                        <div class="mt-1"><a href="#" class="btn btn-reset reset-btn" @click="removeAllFiles()">Remove All Files</a></div>
                    </div>
                    <span v-if="errors.attachment" class="text-red-500 text-xs font-semibold">{{errors.attachment[0]}}</span>
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

            <div class="tw-form-group w-full lg:w-3/4" v-if="this.visibility == 'select_class'">
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
        <div class=" w-full lg:w-3/5">
        <div class="tw-form-group w-full lg:w-3/4">
            <div class="lg:mr-8 md:mr-8 px-2">
                <div class="mb-2">
                    <label for="tag" class="tw-form-label">Tag Name</label>
                </div>
                <div class="mb-2">
                    <input type="text" v-model="tag" name="tag" id="tag" class="tw-form-control w-full" placeholder="Tag Name">
                </div>
                <span v-if="errors.tag" class="text-red-500 text-xs font-semibold">{{errors.tag[0]}}</span>
            </div>
        </div>
        </div>

        <div class="flex flex-col  w-full lg:w-3/5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="flex items-center px-2">
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
        props:['url' , 'entity_id' , 'entity_name' , 'mode'],
        data(){
            return {
                standardLinkList:[],
                post_id:'',
                title:'',
                description:'',
                visibility:'',
                visible_for:'',
                posted_at:'',
                tag:'',
                post_later:'',
                category: '',
                show: '',
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
                    url: this.url+'/'+this.mode+'/post/add/attachment',
                    method:'post',
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    },
                    addRemoveLinks:"true",
                    maxFilesize: 0.5,
                    paramName: "file", // The name that will be used to transfer the file
                    parallelUploads: 6,
                    maxFiles:6,
                    uploadMultiple: true,
                    acceptedFiles: ".jpg,.jpeg,.png",
                    autoProcessQueue: false,
                    maxThumbnailFilesize:2,
                },  
                visiblelist:[{id:'select_page', name:'Select Page'}],
                errors:[],
                categorylist: [],
                success:null,
            }
        },
        methods:
        {
            getData()
            {
                axios.get(this.url+'/'+this.mode+'/post/add/list').then(response => {
                    this.standardLinkList = response.data.data;
                });

               
            },

            getDatas()
            {
                

                axios.get(this.url + '/' + this.mode + '/postCategory/list').then(response => {
                    this.categorylist = response.data.data;
                });
            },

            init() 
            {
                this.$refs.myVueDropzone.processQueue();
            },

            sendingEvent (file, xhr, formData) 
            {
                formData.append('post_id', this.post_id);
            },

            removeAllFiles() 
            {
                this.$refs.myVueDropzone.removeAllFiles();
            },

            showTag(e)
            {
                if(e.target.checked)
                {
                    this.attach_tag=1;
                }
                else
                {
                    this.attach_tag=0;
                }
            },
            showCategory() { 
           
            this.show = 'add'; },
            closeModal()   { this.show = ''; this.newCategoryName = ''; },
              addCategory() {
                this.errors = [];
                axios.post(this.url + '/' + this.mode + '/postCategory/add', { name: this.newCategoryName })
                    .then(() => {
                        this.closeModal();
                        this.getDatas();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData(); 
                
                formData.append('entity_id',this.entity_id);          
                formData.append('entity_name',this.entity_name); 
                formData.append('category',this.category);       
                formData.append('title',this.title);
                formData.append('description',this.description);
                //formData.append('visibility',this.visibility);          
                //formData.append('visible_for',this.visible_for);          
                formData.append('posted_at',this.posted_at);           
                formData.append('post_later',this.post_later);
                formData.append('tag',this.tag);

                axios.post(this.url+'/'+this.mode+'/post/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                    this.post_id = response.data.id;    
                    this.init();
                    this.success = response.data.success;
                    window.location.reload();
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
            this.getDatas();
        }
    }
</script>
<style>
    .quill-editor {
    width: 100% !important;
    }
    /*.dropzone {
        height: 200px !important;
    }*/
    .dropzone span {
        line-height: 6;
    }
    .dropzone .dz-preview {
        margin: 0 5px 5px 5px !important;
    }
    .dropzone .dz-preview .dz-image {
        width: 150px !important;
        height: 150px !important;
    }
</style>