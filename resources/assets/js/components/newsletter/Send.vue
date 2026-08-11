<template>
    <div class="my-3">
        <div class="bulletin shadow px-4 py-1 bg-white">
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-3/4"> 
                        <label for="to" class="tw-form-label">To<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-3/4">
                        <select name="to" v-model="to" id="to" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select</option>
                            <option value="1">All Subscribers</option>
                            <option value="0">All Unsubscribers</option>
                        </select>
                        <span v-if="errors.to" class="text-red-500 text-xs font-semibold">{{ errors.to[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-3/4"> 
                        <label for="subject" class="tw-form-label">Subject<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-3/4">
                        <input type="text" name="subject" v-model="subject" id="subject" class="tw-form-control w-full" placeholder="Subject">
                        <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{ errors.subject[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-3/4">
                        <label for="message" class="tw-form-label">Message<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-full lg:w-3/4 my-2">
                        <quill-editor ref="myQuillEditor" v-model="message" name="message" :options="option"/>
                    </div>
                    <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{ errors.message[0] }}</span>
                </div>
            </div>
            
            <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
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
    export default{
        props:['url'],
        components:{},
        data(){
            return{
                to:'',
                subject:'',
                message:'',
                option: {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'header': 1 }, { 'header': 2 }],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'script': 'sub' }, { 'script': 'super' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'direction': 'rtl' }],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'font': [] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'align': [] }],
                            ['clean'],
                        ],
                    },
                    placeholder: '', 
                },
                success:null,
                errors:[],
            }
        },

        methods:
        {
            resetForm()
            {
                this.to='';
                this.subject='';
                this.message='';
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData(); 

                formData.append('to',this.to);          
                formData.append('subject',this.subject);
                formData.append('message',this.message);   

                axios.post(this.url+'/admin/newsletter/send',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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