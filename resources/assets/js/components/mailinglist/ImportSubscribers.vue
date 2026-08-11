<template>
    <div class="pt-3 pb-2">
        <a @click.prevent="open()" class="no-underline text-white blue-bg py-1 px-3 justify-center text-xs rounded mr-2">Import Subscribers</a>
        <a @click.prevent="popup()" class="no-underline text-white blue-bg py-1 px-3 justify-center text-xs rounded mr-2">Import From Csv</a>
        <!--Modal-->
        <div v-if="this.show">    
            <div class="modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full  max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <div class="pr-8">
                                <h2>Import From Mailing List</h2>
                                <p class="text-xs">It will take a minute or two to import subscribers based on the size of the list</p>
                                <div v-if="this.success != null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
                            </div>
                            <button class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <div class="my-4">
                                    <ul class="list-reset overflow-y-auto" style="max-height: 320px;">
                                        <li v-for="list in maillist" class="my-2">
                                            <span v-if="list.total_subscribers > 0">
                                                <input type="checkbox" v-model="mail_list" :value="list.id" id="mail_list">
                                                <span class="px-2">{{ list.name }} 
                                                    <span class="text-red-500"> ( {{ list.total_subscribers}} Subscribers)</span>
                                                </span> 
                                            </span> 
                                        </li>
                                    </ul>
                                    <span v-if="errors.mail_list" class="text-red-500 text-xs font-semibold">{{ errors.mail_list[0] }}</span>
                                </div>
                            </div>
                            <a class="btn btn-default btn-primary inline-flex items-center relative my-2 no-underline text-white blue-bg py-1 px-3 text-xs rounded mr-2" id="save" @click="importsubscriber()">Submit</a>
                            <a class="btn btn-default btn-primary inline-flex items-center relative my-2 btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1  text-xs" id="reset" @click="reset()">Reset</a>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
        <!--Modal-->

        <!--Csv Modal -->
        <div v-if="this.showcsv">    
            <div class="modal-mask">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full  max-w-md px-8 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <div class="pr-8">
                                <h2>Import From Csv</h2>
                                <p class="text-xs">It will take a minute or two to import subscribers based on the size of the list</p>
                                <div class="text-green p-2" v-if="this.success!=null">{{ this.success }}</div>
                            </div>
                            <button class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                        </div>
                        <div class="py-2 success_msg text-sm" v-if="this.data != null"> 
                            <div class="flex pb-2">
                                <div class="w-1/3">
                                    <label class="text-green">Success Count</label>
                                    <span class="text-green p-2">{{ this.success_count }}</span>
                                </div>
                                <div class="w-2/3">
                                    <label class="text-green">Success Path</label>
                                    <span class="text-green p-2" v-if="this.success_count>0">{{ this.success_path }}
                                        <a :href="this.success_path" class="text-green p-2" target="_blank">Click Here</a>
                                    </span>
                                </div>
                            </div>

                            <div class="flex pb-2">
                                <div class="w-1/3">
                                    <label class="text-yellow">Duplicate Count</label>
                                    <span class="text-yellow p-2">{{ this.duplicate_count }}</span>
                                </div>
                                <div class="w-2/3">
                                    <label class="text-yellow">Duplicate Path</label>
                                    <span class="text-yellow p-2" v-if="this.duplicate_count>0">
                                        <a :href="this.duplicate_path" class="text-yellow p-2">Click Here</a>
                                    </span>
                                </div>
                            </div>

                            <div class="flex pb-2">
                                <div class="w-1/3">
                                    <label class="text-red-500">Error Count</label>
                                    <span class="text-red-500 p-2">{{ this.error_count }}</span>
                                </div>
                                <div class="w-2/3">
                                    <label class="text-red-500">Error Path</label>
                                    <span class="text-red-500 p-2" v-if="this.error_count>0">
                                        <a :href="this.error_path" class="text-red-500 p-2">Click Here</a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <div class="">
                                <div class="my-4">
                                    <a :href="url+'/admin/subscriber/downloadformat'" class="btn btn-default btn-primary inline-flex items-center relative my-2 no-underline text-white blue-bg py-1 px-3 text-xs rounded mr-2">Download Sample Format</a>
                                </div>
                            </div>
                        </div>  

                        <div class="modal-body">
                            <div class="">
                                <vue-csv-import v-model="csv" :url="this.importcsvurl" :mapFields="['firstname', 'lastname','email','aff','source','active']" v-on:change="handleFileUpload()" :callback="csvcallback">
                                <vue-csv-toggle-headers></vue-csv-toggle-headers>
                                <vue-csv-errors></vue-csv-errors>
                                <vue-csv-input></vue-csv-input>
                                <vue-csv-map :auto-match="false"></vue-csv-map>
                                <template slot="hasHeaders" slot-scope="{headers, toggle}">
                                    <label class="flex items-center mt-1">
                                        <input type="checkbox" id="hasHeaders" :value="headers" @change="toggle">
                                        <span class="mx-2">Include Headers?</span>
                                    </label>
                                </template> 
                                <template slot="error">
                                    <p class="text-red">File type is invalid</p>
                                </template>
                                <template slot="thead">
                                    <tr>
                                        <th>My Fields</th>
                                        <th>Column</th>
                                    </tr>
                                </template>
                                <template slot="next" slot-scope="{load}">
                                    <button class="btn btn-default bg-success text-green" @click.prevent="load">Load</button>
                                </template>
                                <template slot="submit" slot-scope="{submit}">
                                    <button class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click.prevent="submit">Submit</button>
                                </template>
                                </vue-csv-import>
                            </div>
                        </div>          
                    </div>
                </div>
            </div>
        </div>
        <!--Csv Modal-->
    </div>
</template>

<script>
    import { VueCsvImport } from 'vue-csv-import';
    export default {
        props: ['url' , 'id'],
        components: { VueCsvImport },
        data() {
            return {
                maillist: [],
                show:0,
                showcsv:0,
                csv:null,
                mail_list:[],
                errors:[],
                success:null,
                success_count:0,
                success_path:null,
                duplicate_count:0,
                duplicate_path:null,
                error_count:0,
                error_path:null,
                data:null,
                importcsvurl:'/admin/mailinglist/importcsv/'+this.id
            }
        },

        methods:
        {
            importsubscriber()
            {
                this.errors=[];
                this.success=null;

                axios.post('/admin/mailinglist/importsubscriber', {
                    id: this.id,
                    mail_list: this.mail_list,
                }).then(response => {
                    this.success = response.data.success;
                    //this.closeModal();
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },

            submit()
            {
                const _this = this;
                this.errors=[];
                this.success=null;

                this.form.csv = this.buildMappedCsv();
                //this.success=res.data.message;
            },

            csvcallback(response)
            {
                this.data = Object.keys(response.data).length;
                if(this.data > 0)
                {
                    this.success_count      =   response.data.success_count;
                    this.success_path       =   response.data.success_path;
                    this.duplicate_count    =   response.data.duplicate_count;
                    this.duplicate_path     =   response.data.duplicate_path;
                    this.error_count        =   response.data.error_count;
                    this.error_path         =   response.data.error_path;
                }
            },

            open()
            {
                $('#save').removeClass('disp');
                this.show=1;
            },

            popup()
            {             
                $('#save').removeClass('disp');
                this.showcsv=1;
            },
      
            closeModal()
            {
                this.show=0;
                this.mail_list =[];
                window.location.reload();
            },

            reset()
            {
               $('#save').removeClass('disp');
               this.mail_list =[];
            },

            getData()
            {
                axios.get('/admin/mailinglist/view/'+this.id).then(response => {
                    this.maillist=response.data.data; 
                });
            }
        },

        created()
        {
            this.getData();
        },
    }
</script>

<style>
    /*.importsub {
        position: absolute;
        top: 409px;
        right: 195px;
    }*/

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
    }

    .modal-container {
        margin: 0px auto;
        padding: 10px 20px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        overflow: auto;
        /*  height: 100%;*/
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    /*.modal-body {
        margin: 20px 0;
    }*/

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

    .disp{
        display:none;
    }

    .text-red-500{
        color: #e53e3e;
    }

    .text-green{
        color: green;
    }

    .text-yellow{
        color: #fc5a03;
    }

    table {
        width: 100%;
    }

    select.form-control {
        border-width: 1px;
        border-color: var(--60);
        border-radius: .5rem;
        color: var(--80);
        width:100%;
    }

    table th {
        text-align: left;
        width: 50%;
    }

    .modal-wrapper .table td {
        height: 2.9rem;
        padding-top: 7px;
        padding-bottom: 7px;
    }

    .btn {
        margin-top: 7px;
    }

    .csv-import-checkbox {
        margin-bottom: 7px;
    }

    .vue-csv-uploader-part-two {
        margin-top: 10px;
    }

    .success_msg {
        line-height: 1.4;
    }
</style>