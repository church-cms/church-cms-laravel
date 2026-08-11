<template>
    <div>
         <loading :active.sync="isLoading" 
        :can-cancel="true" 
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
       <flash-message :position="'right bottom'" :timeout="3000" class="myCustomClass"></flash-message>
        <portal to="member_count">
            <div class="">
                <h1 class="admin-h1 my-3">Members ( {{ Object.keys(this.users).length }} )</h1>
            </div>
        </portal>
        <div class="my-4 filter-alphabet">
            <ul class="list-reset flex flex-wrap">
                <li v-for="alphabet in alphabets">
                    <a href="#" id="filter" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" v-bind:class="letter === alphabet?'active':'text-blue'" v-text="alphabet"  @click="sortMembers(alphabet)"></a>   
                </li>
                <li>
                    <a href="#" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" @click="clearAll()">Clear All</a>   
                </li>
            </ul>
            <div class="my-4" v-if="!filteredNames.length">No names for this letter</div>
            <div class="" v-if="filteredNames.length"></div>
        </div>
        <div>
            <portal-target name="memberdetail"></portal-target>

            <div class="my-8">
                <div class="w-full flex flex-wrap items-center justify-between mb-4">
                    <div class="flex items-center text-sm">
                        <div class="px-3 border-r" v-if="this.selectedUsersCount > 0">
                            {{ parseInt(this.selectedUsersCount) }} members selected
                        </div>
                        <div class="px-3 border-r relative" v-if="Object.keys(this.users).length > 0">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
                        </div>
                        <div class="px-3 relative" v-if="this.selectedUsersCount > 0">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
                        </div>
                    </div> 
                    <div class="relative flex items-center w-full lg:w-1/4 md:w-1/4 lg:justify-end mx-3 lg:mx-0 md:mx-0 my-2 lg:my-0 md:my-0" v-if="this.selectedUsersCount > 0">
                        <a href="#" class="btn btn-submit blue-bg text-white px-3 py-1 text-sm font-medium rounded mr-1 whitespace-no-wrap" @click="showModal('message')">Send Message</a>

                        <a href="#" class="btn btn-submit blue-bg text-white px-3 py-1 text-sm font-medium rounded whitespace-no-wrap" @click="showModal('newsletter')">Subscribe News Letter</a>
                    </div>
                </div>
                <div class="flex flex-wrap" v-if="Object.keys(this.users).length > 0">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="user in users">
                        <div class="flex justify-between member-list">
                            <div class="flex items-center student_select">
                                <input class="w-5 h-5" type="checkbox" v-model="selected" :value="user.id" @click="selectedCount(user.id,$event)">
                                <label></label>
                            </div>
                            <div class="flex p-2 active w-full" :id="user.id" @click="enableform(user.name)">
                                <img :src="user.avatar" class="w-16 h-16">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ user.fullname }}</h2>
                                    <p v-if="user.sub_occupation != null ">{{ user.profession }} <span> ( {{ user.sub_occupation }} ) </span> </p>
                                    <p v-else>{{ user.profession }}</p>
                                    <p v-if="type == 'date_of_birth'">{{ user.date_of_birth }}</p>
                                    <p v-if="type == 'marriage_status'">{{ user.marriage_status }}</p>
                                    <p v-if="type == 'location'">{{ user.state }} - {{ user.city }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal modal-mask" v-if="this.showtab == 'message'">
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
        <div class="modal modal-mask" v-if="this.showtab == 'newsletter'">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-4 mx-auto">
                    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
                    <div class="modal-header flex justify-between items-center">
                        <h2>Subscribe News Letter</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>

                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="subscribe()">Subscribe</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import 'vue-flash-message/dist/vue-flash-message.min.css';
    import VueFlashMessage from 'vue-flash-message';
    Vue.use(VueFlashMessage);

    import { bus } from "../../app";
    import PortalVue from "portal-vue";
    import sendMessage from './sendMessage';
    import datetime from 'vuejs-datetimepicker';
    export default {
        props:['url' , 'searchquery' , 'letter' , 'type' ],
        components:
        {
            sendMessage,
            datetime,
            Loading
        },
        data(){
            return{
                users:[],
                alphabets: [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
                ],
                selectedLetter: undefined,
                selected: [],
                selectedUsers:[],
                selectedUsersCount:0,
                send_later:'',
                allSelected: false,
                noneSelected:false,
                mode:'mail',
                subject:'',
                message:'',
                attachments:'',
                executed_at:'',
                showtab:'',
                show:'',
                active: false,
                errors:[],
                success:null,
                isLoading: false,
                fullPage: true,
                membership_type:'member',
            }
        },

        created() 
        {
            axios.get('/admin/members/find?'+this.searchquery).then(response => {
                this.users = response.data.data; 
                //console.log(this.users) 
            });
            this.getUrl();
        },

        computed: 
        {
            filteredNames () 
            {
                let users = this.users
                if (this.selectedLetter) 
                {
                    users = users.filter((name) => {
                        let firstLetter = name.charAt(0).toUpperCase()
                        return firstLetter === this.selectedLetter
                    })
                }
                return users
            }
        },

        methods:
        {
            enableform(val)
            {
                this.success=null;

                window.location.href=this.url+'/admin/member/show/'+val;

            },
        
            sortMembers(name)
            {
                this.selectedLetter= name; 
                this.active = true; 
                var q='alphabet='+this.selectedLetter;
                //var url = window.location.href; 
                var url = this.currenturl;  

                if (window.location.search.indexOf('alphabet=') > -1) 
                {
                    var href = new URL(url); 
                    href.searchParams.set('alphabet', this.selectedLetter);
                    url=href.toString();       
                } 
                else 
                {
                    if (url.indexOf('?') > -1)
                    {
                        url += '&'
                    }
                    else
                    {
                        url += '?'
                    }
                    url += q;
                }
                window.location.href = url;
            },

            getUrl()
            {
                this.currenturl =  this.url+"/admin/members/"; 
                if(this.searchquery!='')
                {
                    this.currenturl =  this.currenturl+'?'+this.searchquery;
                }
            },

            clearAll()
            {
                window.location.href = this.url+'/admin/members';
            },

            selectAll(e) 
            { 
                var selected = [];
                var selectedUsers = [];
                if (e.target.checked) 
                {
                    $('.member-list').addClass('student_selected');
                    if(this.allSelected == false) 
                    {
                        this.users.forEach(function (user) 
                        {
                            selectedUsers.push(user.id);
                            selected.push(user.id);
                        });
                        this.selected = selected; 
                        this.selectedUsers = selectedUsers; 
                        this.selectedUsersCount = selected.length;
                        this.allSelected = true;
                    }
                }
                else
                {
                    this.users.forEach(function (user) 
                    {
                        //selected.splice(user.id);
                        //selectedUsers.splice(user.id);

                        for (var i=0 ; i < selected.length ; i++)
                        {
                            if (selected[i]==user.id)
                            {
                                selected.splice(i,1); //this delete from the "i" index in the array to the "1" length
                                break;
                            }
                        } 

                        for (var i=0 ; i < selectedUsers.length ; i++)
                        {
                            if (selectedUsers[i]==user.id)
                            {
                                selectedUsers.splice(i,1); //this delete from the "i" index in the array to the "1" length
                                break;
                            }
                        }
                    });
                    this.selected = selected; 
                    this.selectedUsers = selectedUsers;
                    this.selectedUsersCount = selected.length;
                    this.noneSelected = false;
                    $('.member-list').removeClass('student_selected');
                }
            },

            selectNone(e) 
            { 
                var selected = [];
                var selectedUsers = [];
                if (e.target.checked) 
                {
                    $('.member-list').removeClass('student_selected');
                    this.users.forEach(function (user) 
                    {
                        //selected.splice(user.id);
                        //selectedUsers.splice(user.id);

                        for (var i=0 ; i < selected.length ; i++)
                        {
                            if (selected[i]==user.id)
                            {
                                selected.splice(i,1); //this delete from the "i" index in the array to the "1" length
                                break;
                            }
                        } 

                        for (var i=0 ; i < selectedUsers.length ; i++)
                        {
                            if (selectedUsers[i]==user.id)
                            {
                                selectedUsers.splice(i,1); //this delete from the "i" index in the array to the "1" length
                                break;
                            }
                        } 
                    });
                    this.selected = selected;
                    this.selectedUsers = selectedUsers;
                    this.selectedUsersCount = selected.length;
                    this.noneSelected = false;
                }
            },
      
            showModal(value)
            {
                if(this.selectedUsersCount > 0)
                {
                    this.showtab = value;
                }
                else
                {
                    alert("Select Members")
                }
            },

            closeModal()
            {
                this.showtab = 0;
            },

            addAttachment(event)
            {
                this.attachments = event.target.files[0];
                //console.log(this.attachments);
            },

            submit()
            {
                this.isLoading=true;
                this.errors=[];
                this.success=null;

                let formData=new FormData();

                formData.append('mode',this.mode);  
                formData.append('subject',this.subject);  
                formData.append('message',this.message);  
                formData.append('attachments',this.attachments);  
                formData.append('send_later',this.send_later);         
                formData.append('executed_at',this.executed_at);
                formData.append('count',this.selectedUsers.length);
                formData.append('selected',this.selectedUsers);
                formData.append('membership_type',this.membership_type);

                for(let i=0; i<this.selectedUsers.length;i++)
                {
                    if(typeof this.selectedUsers[i] !== "undefined")
                    {
                        formData.append('user'+i,this.selectedUsers[i]);
                    }
                    else
                    {
                        formData.append('user'+i,'');
                    }
                }
                
                axios.post('/admin/member/sendMessageToAll',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                    this.success = response.data.message;
                    this.showtab=0;
                    this.isLoading=false;
                    this.flash(this.success,'success',{timeout: 3000,
                    beforeDestroy() {
                     window.location.reload();
                    }});
                   
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.isLoading=false;
                    this.flash('Please fill all fields ☹','error',{timeout: 3000});
                });
            },

            subscribe()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData();

                formData.append('count',this.selectedUsers.length);

                for(let i=0; i<this.selectedUsers.length;i++)
                {
                    if(typeof this.selectedUsers[i] !== "undefined")
                    {
                        formData.append('user'+i,this.selectedUsers[i]);
                    }
                    else
                    {
                        formData.append('user'+i,'');
                    }
                }
                
                axios.post('/admin/members/subscribe',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                    this.success = response.data.message;
                    this.showtab=0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

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

            selectedCount(id,e) 
            { 
                if (e.target.checked) 
                {   
                    this.selectedUsersCount++;
                    this.selectedUsers.push(id);
                    $('#'+id).addClass('student_selected');
                }
                else
                {
                    this.selectedUsersCount--;
                    //this.selectedUsers.splice(id);
                    this.removeUser(id,this.selectedUsers);
                    $('#'+id).removeClass('student_selected');
                }
            },

            removeUser(item, arr)
            {
                for (var i=0 ; i < arr.length ; i++)
                {
                    if (arr[i]==item)
                    {
                        arr.splice(i,1); //this delete from the "i" index in the array to the "1" length
                        break;
                    }
                } 
            },
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
  .myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>
