<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{ this.success }}</div>
        <div class="flex flex-wrap lg:flex-row justify-between items-center">
            <div class="relative flex items-center justify-end">
                <div class="flex items-center">
                    <a href="#" @click="showModal()" class="no-underline text-white px-4 mx-1 flex items-center blue-bg py-1 justify-center text-xs rounded">Send Test Mail</a> 
                </div>
            </div>
        </div>
    
        <div v-if="this.show == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Test Mail</h2>
                        <button id="close-button" class="modal-default-button text-xl" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="to_email" class="tw-form-label">Email</label>
                             </div>
                            <div class="my-2 w-full">
                                <input type="text" name="to_email" v-model="to_email" class="tw-form-control w-full">
                                <span v-if="errors.to_email" class="text-red-500 text-xs font-semibold">{{ errors.to_email[0] }}</span>
                            </div>
                        </div>
            
                        <div class="my-6">
                            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="sendmail()">Submit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['mode','email_id'],
        data () {
            return {
                edit:[],
                to_email:'',
                show:0,
                editshow:0,
                errors:[],
                success:null,
            }
        },

        methods:
        {    
            sendmail()
            {
                axios.post('/'+this.mode+'/sendtestmail',{
                    to_email:this.to_email,
                    id:this.email_id,
                }).then(response => {
                    this.success = response.data.message;
                    this.closeModal();
                    window.location.reload();
                }).catch(error=>{
                    this.errors=error.response.data.errors;
                }); 
            },
   
            showModal()
            {
                this.show = 1;
                this.to_email = '';
            },
    
            closeModal()
            {
                this.show = 0;
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
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        /* height: 550px;*/
        overflow:auto;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 10px 0;
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