<template>
    <div class="">
       <div class="shadow px-4 py-1 bg-white">
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4">
                        <label for="category" class="tw-form-label">Faq Category<span class="text-red-500">*</span></label>
                    </div>
                    <div class="flex flex-col lg:flex-row">
                        <div class="w-full lg:w-1/2 my-2">
                            <select name="category" v-model="category" id="category" class="tw-form-control w-full">
                                <option value="" disabled="disabled">Select Faq Category</option>
                                <option v-for="list in categorylist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                        </div>
                        <span v-if="errors.category" class="text-red-500 text-xs font-semibold">{{ errors.category[0] }}</span>
                        <div class="py-3 mx-2">
                            <a href=# class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="addCategory()">Add New Category</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="question" class="tw-form-label">Question<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-1/2">
                        <textarea type="text" name="question" v-model="question" id="question" placeholder="Question" rows="3" class="tw-form-control w-full"></textarea>
                        <span v-if="errors.question" class="text-red-500 text-xs font-semibold">{{ errors.question[0] }}</span>
                    </div>
                </div>
            </div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="answer" class="tw-form-label">Answer<span class="text-red-500">*</span></label>
                    </div>
                    <div class="my-2 w-full lg:w-1/2" style="height:250px;">
                        <quill-editor ref="myQuillEditor" id="answer" v-model="answer" name="answer" :options="option" style="height:200px;"></quill-editor><br><br>
                        <span v-if="errors.answer" class="text-red-500 text-xs font-semibold">{{ errors.answer[0] }}</span>
                    </div>
                </div>
            </div>
            <div class="my-5">
                <div class="">
                    <div class="w-full lg:w-1/4"> 
                        <label for="order" class="tw-form-label">Order</label>
                    </div>
                    <div class="my-2 w-full lg:w-1/2">
                        <input type="text" name="order" v-model="order" id="order" placeholder="Order" class="tw-form-control w-full">
                        <span v-if="errors.order" class="text-red-500 text-xs font-semibold">{{ errors.order[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" dusk="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>
            </div>
        </div>

        <div v-if="this.show == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Add Faq Category</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="name" class="tw-form-label">Category Name</label>
                            </div>
                            <div class="my-2 w-full">
                                <input type="text" class="tw-form-control w-full" id="name" v-model="name" name="name" Placeholder="Category Name">
                                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{ errors.name[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitCategory()">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueQuillEditor from 'vue-quill-editor'
    import 'quill/dist/quill.core.css' // import styles
    import 'quill/dist/quill.snow.css' // for snow theme
    import 'quill/dist/quill.bubble.css' // for bubble theme
    import { quillEditor } from 'vue-quill-editor'
    Vue.use(VueQuillEditor)
    export default {
        props:['url'],
        components: {
            VueQuillEditor
        },
        data(){
            return{
                categorylist:[],
                category:'',
                question:'',
                answer:'',
                order:'',
                name:'',
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
                show:0,
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/admin/faq/addList').then(response => {
                    this.categorylist = response.data.data;
                    //console.log(this.categorylist)
                });
            },

            resetForm()
            {
                this.category='';
                this.question='';
                this.answer='';  
            },

            addCategory()
            {
                this.show = 1;
            },

            closeModal()
            {
                this.show = 0;
            },

            submitCategory()
            {
                this.errors=[];
                this.success=null; 

                let formData = new FormData();

                formData.append('name',this.name);    
              
                axios.post('/admin/faq/category/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.closeModal();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('category',this.category);         
                formData.append('question',this.question);          
                formData.append('answer',this.answer);    
                formData.append('order',this.order);    
              
                axios.post('/admin/faq/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }
        },
        created()
        {
            this.getData();
        }
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
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
        padding: 8px 20px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        /*height: 550px;*/
        overflow:auto;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */
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
</style>