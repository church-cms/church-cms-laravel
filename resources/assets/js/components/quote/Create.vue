<template>
    <div class="bulletin shadow px-4 py-1">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="lg:px-3 md:px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap" v-bind:class="[this.tab=='images'?'block' :'hidden']">
            <div class="my-3 w-full">
                <div class="">
                    <div class="w-full lg:w-1/4 md:w-1/3">
                        <label class="tw-form-label">Image<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-1/4 md:w-1/3">
                        <input type="file" name="image" id="image" @change="fileUpload($event)" class="tw-form-control w-full">
                    </div>
                    <span class="text-red-500 text-xs font-semibold" v-if="errors.image">{{ errors.image[0] }}</span>
                </div>
            </div>
        </div>
        <div class="lg:px-3 md:px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap overflow-y-hidden" v-bind:class="[this.tab=='text'?'block' :'hidden']">
            <div class="my-3">
                <div class="">
                    <div class="w-full lg:w-1/4 md:w-1/4">
                        <label class="tw-form-label">Text<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-3/4 md:w-3/4 background_theme" style="height:250px;">
                        <quill-editor ref="myQuillEditor" id="text" v-model="text" name="text" :options="option" style="height:200px;" />
                    </div>
                    <span class="text-red-500 text-xs font-semibold" v-if="errors.text">{{ errors.text[0] }}</span>
                </div>
            </div>
        </div>

        <div class="lg:px-3 md:px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap overflow-y-hidden" v-bind:class="[this.tab=='bible'?'block' :'hidden']">
            <div class="flex flex-col">
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-1/4">
                            <label class="tw-form-label">Tamil Bible<span class="text-red-500">*</span></label>
                        </div>
                        <div class="flex items-center w-full lg:w-1/2 md:w-2/3 my-2">
                             <select v-model="tamil_bible" @change="tamilChange(tamil_bible)" class="tw-form-control w-full lg:w-1/2 md:w-1/2">
                             <option v-for="tamil in tamil_lists" :value="tamil.book_id">{{ tamil.tamil_book }}</option>
                             </select>
                             <span v-if="tamil_block_loading" class="text-xs mx-2">loading...</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap">
                <div class="w-full lg:w-1/2 md:w-1/2 px-1" v-for="n in parseInt(chapter_count)" v-bind:key="n">
                 <v-collapse-wrapper v-on:beforeClose="my_tamil_action"  class="bible-library-content shadow px-2 py-2 my-2 hover:bg-blue-100">
                     <div class="header text-xs cursor-pointer" v-collapse-toggle>
                         அதிகாரம் {{n}}
                     </div>
                    <div class="my-content text-xs" v-collapse-content>
                        <p class="py-1" v-for="verse in verses[n]">{{verse.verse_id}}. {{verse.tamil_verse}} <button type="button" @click="doCopytamil(verse.tamil_verse, verse.chapter_id, verse.verse_id)" class="text-blue-400 font-medium mx-1">Copy!</button></p>
                    </div>
                </v-collapse-wrapper>
                </div>
                </div>
                <div class="my-5">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="tamil" class="tw-form-label">Tamil Quotes<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-full md:w-full my-2">
                        <div class="lg:h-64 md:h-64 h-56">
                            <quill-editor ref="myQuillEditor" v-model="tamil" name="tamil" :options="option" style="height:200px;" /></quill-editor>
                        </div>
                        </div>
                        <span v-if="errors.tamil" class="text-red-500 text-xs font-semibold">{{ errors.tamil[0] }}</span>
                    </div>
                </div> 

                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4 md:w-1/4">
                            <label class="tw-form-label">English Bible<span class="text-red-500">*</span></label>
                        </div>
                        <div class="flex items-center w-full lg:w-1/2 md:w-2/3 my-2">
                             <select v-model="english_bible" @change="englishChange(english_bible)" class="tw-form-control w-full lg:w-1/2 md:w-1/2">
                             <option v-for="english in english_lists" :value="english.book_id">{{ english.english_book }}</option>
                             </select>
                             <span v-if="english_block_loading" class="text-xs mx-2">loading...</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap">
                <div class="w-full lg:w-1/2 md:w-1/2 px-1" v-for="eng in parseInt(english_chapter_count)" v-bind:english_key="eng">
                <v-collapse-wrapper v-on:beforeClose="my_english_action" class="bible-library-content shadow px-2 py-2 my-2 hover:bg-blue-100">
                     <div class="header text-xs cursor-pointer" v-collapse-toggle="'toggle_second_{{eng}}'">
                         Chapter {{eng}}
                     </div>
                    <div class="content text-xs" ref="toggle_second_{{eng}}" v-collapse-content>
                        <p class="py-1" v-for="eng_verse in english_verses[eng]">{{eng_verse.verse_id}}. {{eng_verse.english_verse}} <button type="button" @click="doCopyeng(eng_verse.english_verse,eng_verse.chapter_id, eng_verse.verse_id)" class="text-blue-400 font-medium mx-1">Copy!</button></p>
                    </div>
                </v-collapse-wrapper>
                </div>
                </div>

                <div class="my-5">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="english" class="tw-form-label">English Quotes<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-full lg:w-full md:w-full my-2">
                        <div class="lg:h-64 md:h-64 h-56">
                            <quill-editor ref="myQuillEditor" v-model="english" name="english" :options="option" style="height:200px;" /></quill-editor>
                        </div>
                        </div>
                        <span v-if="errors.english" class="text-red-500 text-xs font-semibold">{{ errors.english[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:px-3 md:px-3 my-3">
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
                <span v-if="errors.publish_on" class="text-red-500 text-xs font-semibold">{{ errors.publish_on[0] }}</span>
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
    import VueCollapse from 'vue2-collapse'
    import VueClipboard from 'vue-clipboard2'
    Vue.use(VueCollapse)
    Vue.use(VueQuillEditor)
    Vue.use(VueClipboard)
    export default{
        components: {
            VueQuillEditor,
            datetime,
        },
        props:['url' , 'publish_on'],
        data(){
            return{
                tab:'images',
                image:'',
                text:'',
                tamil:'',
                english:'',
                tamil_bible:'1',
                english_bible:'1',
                tamil_lists:[],
                english_lists:[],
                chapter_count:50,
                verses:[],
                english_chapter_count:50,
                english_verses:[],
                tamil_block_loading:false,
                english_block_loading:false,
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
            }
        },
        methods:
        {   
            my_tamil_action : function(vm){ 
            },
            my_english_action : function(vm){ 
            },
            doCopytamil:function(message,chapter_id,verse_id){
                this.$copyText(message+' '+this.tamil_lists[this.tamil_bible-1].tamil_book+' '+chapter_id+':'+verse_id).then(function (e) {
                  alert('Copied');
                }, function (e) {
                  alert('Can not copy');
                });
            },
            doCopyeng:function(message,chapter_id,verse_id){ 
                this.$copyText(message+' '+this.english_lists[this.english_bible-1].english_book+' '+chapter_id+':'+verse_id).then(function (e) {
                  alert('Copied');
                }, function (e) {
                  alert('Can not copy');
                });
            },
            tamilChange(selected){
                this.tamil_block_loading = true;
                axios.get('/admin/quote/bible/tamil/'+selected).then(response => {
                    this.chapter_count = response.data.chapter_count;
                    this.verses = response.data;
                    this.tamil_block_loading = false;
                });
            },
            englishChange(selected){
                this.english_block_loading = true;
                axios.get('/admin/quote/bible/english/'+selected).then(response => {
                    this.english_chapter_count = response.data.chapter_count;
                    this.english_verses = response.data;
                    this.english_block_loading = false;
                });
            },
            getData(type)
            {
                axios.get('/admin/quote/bible/tamil').then(response => {
                    this.tamil_lists = response.data.data;
                });
                axios.get('/admin/quote/bible/english').then(response => {
                    this.english_lists = response.data.data;
                });
                axios.get('/admin/quote/bible/tamil/1').then(response => {
                    this.chapter_count = response.data.chapter_count;
                    this.verses = response.data;
                });
                axios.get('/admin/quote/bible/english/1').then(response => {
                    this.english_chapter_count = response.data.chapter_count;
                    this.english_verses = response.data;
                });
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
              
                axios.post('/admin/quote/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            resetForm()
            {
                window.location.reload();
            },
        },

        mounted()
        {
            $('.OkButton')[1].onclick = function () {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
 
                formData.append('publish_on',$('#tj-datetime-input')[0]['value']); 
              
                axios.post('/admin/quote/validation',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    alert(this.errors.publish_on[0])
                });
            }
        },

        created()
        {
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
</script>

<style scoped>
    @media(max-width: 991px) {
        .quill-editor {
            height: 100 !important; 
        }
    }
    .v-collapse-content{
        max-height: 0;
        transition: max-height 0.3s ease-out;
        overflow: hidden;
        padding: 0;
    }
    .v-collapse-content-end{
        transition: max-height 0.3s ease-in;
        /*max-height: 500px;*/
        max-height: max-content;
    }
</style>