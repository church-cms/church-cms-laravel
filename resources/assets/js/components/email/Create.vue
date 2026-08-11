<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="subject" class="tw-form-label">Subject<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="subject" id="subject" v-model="subject" class="tw-form-control w-full" placeholder="Subject">
                            <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{ errors.subject[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="from_email" class="tw-form-label">From Email<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="from_email" id="from_email" v-model="from_email" class="tw-form-control w-full" placeholder="From Email">
                            <span v-if="errors.from_email" class="text-red-500 text-xs font-semibold">{{ errors.from_email[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="from_name" class="tw-form-label">From Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="from_name" id="from_name" v-model="from_name" class="tw-form-control w-full" placeholder="From Name">
                            <span v-if="errors.from_name" class="text-red-500 text-xs font-semibold">{{ errors.from_name[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="reply_to_email" class="tw-form-label">Reply To Email<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="reply_to_email" id="reply_to_email" v-model="reply_to_email" class="tw-form-control w-full" placeholder="Reply To Email">
                            <span v-if="errors.reply_to_email" class="text-red-500 text-xs font-semibold">{{ errors.reply_to_email[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="content" class="tw-form-label">Content<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <quill-editor ref="myQuillEditor" v-model="content" name="content" :options="option"/>
                            <span v-if="errors.content" class="text-red-500 text-xs font-semibold">{{ errors.content[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <create-email-template resourceName="test" resourceId="1" :field="this.field"></create-email-template> -->

            <!--  <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="name" class="tw-form-label">Select Template</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="name" id="name" v-model="name" class="tw-form-control w-full" placeholder="Enter Title">
                            <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>-->
    
            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>  
            </div>
        </div>
    </div>
</template>

<script>
    import VueQuillEditor from 'vue-quill-editor'
    import 'quill/dist/quill.core.css' // import styles
    import 'quill/dist/quill.snow.css' // for snow theme
    import 'quill/dist/quill.bubble.css' // for bubble theme
    export default {
        props:['mode'],
        data(){
            return{
                subject:'',
                from_name:'',
                from_email:'',
                reply_to_email:'',
                content:'',
                option: {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],       
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'script': 'sub' }, { 'script': 'super' }],        
                            [{ 'align': [] }],
                        ],
                    },
                    placeholder: '', 
                },
                field:[{value : '1'},{select_template : '1'}],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            resetForm()
            {
                this.subject='';
                this.from_name='';
                this.from_email='';
                this.reply_to_email='';
                this.content='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                         
                formData.append('subject',this.subject);                 
                formData.append('from_name',this.from_name);                 
                formData.append('from_email',this.from_email);                 
                formData.append('reply_to_email',this.reply_to_email);                 
                formData.append('content',this.content);                 
            
                axios.post('/'+this.mode+'/email/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            //
        }
    }
</script>