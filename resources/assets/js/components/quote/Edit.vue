<template>
    <div class="bulletin shadow px-4 py-1">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap" v-bind:class="[this.tab=='images'?'block' :'hidden']">
            <div class="my-3">
                <div class="">
                    <div class="w-full">
                        <img :src="this.display_image" class="img-responsive w-32 h-32">
                    </div>
                    <div class="my-2 w-3/4">
                        <label class="tw-label cursor-pointer text-xs text-gray-600">Change Image<span class="text-red-500">*</span>
                            <input type="file" size="60" name="image" id="image" @change="fileUpload($event)">
                            <span v-if="errors.image" class="text-red-500 text-xs font-semibold">{{errors.image[0]}}</span>
                        </label>
                    </div>
                    <span class="text-danger text-xs" v-if="errors.image">{{ errors.image[0] }}</span>
                </div>
            </div>
        </div>
        <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap overflow-y-hidden" v-bind:class="[this.tab=='text'?'block' :'hidden']">
            <div class="my-3">
                <div class="">
                    <div class="w-1/4">
                        <label class="tw-form-label">Text<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-3/4 md:w-3/4" style="height:250px;">
                        <quill-editor ref="myQuillEditor" v-model="text" name="text" :options="option" style="height:200px;" />
                    </div>
                    <span class="text-danger text-xs" v-if="errors.text">{{ errors.text[0] }}</span>
                </div>
            </div>
        </div>
        <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap overflow-y-hidden" v-bind:class="[this.tab=='bible'?'block' :'hidden']">
            <div class="flex flex-col">
                <div class="my-5">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="tamil" class="tw-form-label">Tamil Quotes<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-full md:w-full my-2">
                        <div class="lg:h-64 md:h-64 h-56">
                            <quill-editor ref="myQuillEditor" v-model="tamil" name="tamil" :options="option" style="height:200px;" />
                            </quill-editor>
                            </div>
                        </div>
                        <span v-if="errors.tamil" class="text-red-500 text-xs font-semibold">{{ errors.tamil[0] }}</span>
                    </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="english" class="tw-form-label">English Quotes<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-full md:w-full my-2">
                        <div class="lg:h-64 md:h-64 h-56">
                            <quill-editor ref="myQuillEditor" v-model="english" name="english" :options="option" style="height:200px;"/>
                            </quill-editor>
                            </div>
                        </div>
                        <span v-if="errors.english" class="text-red-500 text-xs font-semibold">{{ errors.english[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-3 my-3">
            <div class="">
                <div class="w-full lg:w-1/4 md:w-1/4">
                    <label class="tw-form-label">Publish Date<span class="text-red-500">*</span></label>
                </div>
                <div class="flex items-center w-full lg:w-1/2 md:w-2/3">
                    <div class="my-2 w-full lg:w-1/2 md:w-1/2">
                        <datetime format="DD-MM-YYYY h:i:s" name="publish_on" v-model="publish_on" class="rounded w-full" id="publish_on"></datetime>
                    </div>
                    <div class="my-2 mx-2">
                        <img :src="url+'/uploads/icons/calendar.svg'" class="w-5 h-5 fill-current text-gray-600" style="filter: brightness(0);">
                    </div>
                </div>
                <span class="text-danger text-xs" v-if="errors.publish_on">{{ errors.publish_on[0] }}</span>
            </div>
        </div>
        <div class="px-3 my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a> 
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import datetime from 'vuejs-datetimepicker';
    import VueQuillEditor from 'vue-quill-editor'
    import 'quill/dist/quill.core.css' // import styles
    import 'quill/dist/quill.snow.css' // for snow theme
    import 'quill/dist/quill.bubble.css' // for bubble theme
    import { quillEditor } from 'vue-quill-editor'
    Vue.use(VueQuillEditor)
    export default{
        components: {
            VueQuillEditor,
            datetime,
        },
        props:['url' , 'id'],
        data(){
            return{
                tab:'images',
                image:'',
                display_image:'',
                text:'',
                tamil:'',
                english:'',
                publish_on:'',
                success:null,
                errors:[],
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
                color: '#3ab6a1',
            }
        },
        methods:
        {
            getData()
            {
                axios.get('/admin/quote/edit/list/'+this.id).then(response => {
                    this.quote = response.data;
                    this.setData();
                });
            },

            setData()
            {
                if(Object.keys(this.quote).length > 0)
                {
                    this.tab            = this.quote.tab;
                    bus.$emit("dataTab", this.tab);
                    this.display_image  = this.quote.image;
                    this.text           = this.quote.text;
                    this.tamil          = this.quote.tamil;
                    this.english        = this.quote.english;
                    this.publish_on     = this.quote.publish_on;
                }
            },

            fileUpload(event)
            {
                this.image = event.target.files[0];
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('tab',this.tab);         
                formData.append('image',this.image);         
                formData.append('text',this.text);         
                formData.append('tamil',this.tamil);         
                formData.append('english',this.english); 
                formData.append('publish_on',this.publish_on); 
              
                axios.post('/admin/quote/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        mounted()
        {
            let vm = this;      

            vm.$nextTick(function () { 
                $('.OkButton')[1].onclick = function (){
                    this.errors=[];
                    this.success=null; 

                    let formData=new FormData();
     
                    formData.append('publish_on',$('#tj-datetime-input')[0]['value']); 
                  
                    axios.post('/admin/quote/editValidation/'+vm.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        this.success = response.data.success;
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                        alert(this.errors.publish_on[0]);
                    });
                }
            });
        },

        created()
        {
            //
            this.getData();
            bus.$emit("dataTab", this.tab);
       
            bus.$on("dataTab", data => {
                if(data!='')
                {
                    this.tab=data;                   
                }
            });
        }
    }
</script>>

<style scoped>
    .tw-label{
        color:#3492e2;
    }
    .tw-label input[type="file"] {
        display: none;
    }
    @media(max-width: 560px) {
        .quill-editor {
            height: 100 !important; 
        }
    }
</style>