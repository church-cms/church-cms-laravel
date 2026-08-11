<template>
    <div>
        <div class="modal modal-mask" v-if="this.tab != 0">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-4 mx-auto">
                    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
                    <div class="modal-header flex justify-between items-center">
                        <h2>Send Message</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col lg:flex-row">
                            <div class="tw-form-group w-full lg:w-1/2">
                                <div class="lg:mr-8 md:mr-8">
                                    <div class="flex">
                                        <div class="w-1/2 flex items-center lg:mr-8 md:mr-8"> 
                                            <input type="radio" v-model="mode" name="mode" id="mail" value="mail">
                                            <span class="text-sm mx-1">Email</span>
                                        </div>
                                        <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8"> 
                                            <input type="radio" v-model="mode" name="mode" id="notification" value="notification">
                                            <span class="text-sm mx-1">Notification</span>
                                        </div>
                                        <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8"> 
                                            <input type="radio" v-model="mode" name="mode" id="sms" value="sms">
                                            <span class="text-sm mx-1">SMS</span>
                                        </div>
                                    </div>
                                    <span v-if="errors.mode" class="text-red-500 text-xs font-semibold">{{ errors.mode[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.mode == 'mail'">
                        <div class="flex flex-col">
                            <div class="w-full lg:w-1/4">
                                <label for="subject" class="tw-form-label">Subject</label>
                            </div>
                            <div class="w-full">
                                <input type="text" name="subject" v-model="subject" class="tw-form-control w-full" placeholder="Enter Subject">
                                <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{ errors.subject[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col">
                            <div class="w-full lg:w-1/4">
                                <label for="message" class="tw-form-label">Message</label>
                            </div>
                            <div class="w-full">
                                <textarea type="text" name="message" v-model="message" class="tw-form-control w-full" rows="3" placeholder="Enter Message"></textarea>
                                <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{ errors.message[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.mode == 'mail'">
                        <div class="flex flex-col">
                            <div class="w-full lg:w-1/4">
                                <label for="attachments" class="tw-form-label">Attachments</label>
                            </div>
                            <div class="w-full">
                                <input type="file" name="attachments" class="tw-form-control w-full" @change="addAttachment($event)">
                                <span v-if="errors.attachments" class="text-red-500 text-xs font-semibold">{{ errors.attachments[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-6">
                                <input type="checkbox" name="send_later" v-model="send_later" class="tw-form-control w-full" @click="enableDate($event)">
                            </div>
                            <div class="mx-1"> 
                                <label for="subject" class="tw-form-label">Send Later</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.show == 'executed'">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="executed_at" class="tw-form-label">Date Time</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <datetime format="DD-MM-YYYY h:i:s" name="executed_at" v-model="executed_at" class="w-full rounded" id="executed_at"></datetime>
                                <span v-if="errors.executed_at" class="text-red-500 text-xs font-semibold">{{ errors.executed_at[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submit()">Send</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datetime from 'vuejs-datetimepicker';
    export default{
        props:['url' , 'name' , 'tab' , 'type'],
        components:
        {  
            datetime,
        },
        data(){
            return{
                mode:'mail',
                subject:'',
                message:'',
                attachments:'',
                send_later:'',
                executed_at:'',
                show:'',
                success:null,
                errors:[],
            }
        },

        methods:
        {
            enableDate(e)
            {
                if (e.target.checked) 
                {
                    this.send_later = 1;
                    this.show = 'executed';
                }
                else
                {
                    this.send_later = 0;
                    this.show = '';
                }
            },

            addAttachment(event)
            {
                this.attachments = event.target.files[0];
            },

            submit()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData();

                formData.append('mode',this.mode);  
                formData.append('subject',this.subject);  
                formData.append('message',this.message);  
                formData.append('attachments',this.attachments);  
                formData.append('send_later',this.send_later);         
                formData.append('executed_at',this.executed_at);
                
                axios.post('/admin/'+this.type+'/sendMessage/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.closeModal();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            closeModal()
            {
                this.tab = 0;
                window.location.reload();
            },
        },

        created()
        {
            //
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
        /*padding: 20px 30px;*/
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

    .text-danger
    {
        color:red;
    }
</style>