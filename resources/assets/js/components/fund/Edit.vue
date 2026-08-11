<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="modal-body">
            <div class="my-3">
                <div class="flex pb-2">
                    <div class="w-1/4">
                        <label class="tw-form-label">Membership<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4 flex">
                        <div class="text-sm flex items-center">
                            <input type="radio" name="membership" v-model="membership" value="guest" id="guest">
                            <span class="mx-1">Guest</span>
                        </div>
                        <div class="text-sm flex items-center mx-4">
                            <input type="radio" name="membership" v-model="membership" value="member" id="member">
                            <span class="mx-1">Member</span>
                        </div>
                    </div>
                </div>

                <div class="my-3" v-if="this.membership == 'guest'">
                    <div class="flex">
                        <div class="w-1/4">
                            <label for="first_name" class="tw-form-label">First Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-3/4">
                            <input type="text" v-model="first_name" name="first_name" id="first_name" class="tw-form-control w-full lg:w-1/4"><br>
                            <span v-if="errors.first_name" class="text-danger text-xs my-1">{{ errors.first_name[0] }}</span>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="last_name" class="tw-form-label">Last Name<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="last_name" name="last_name" id="last_name" class="tw-form-control w-full lg:w-1/4"><br>
                                <span v-if="errors.last_name" class="text-danger text-xs my-1">{{ errors.last_name[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="address" class="tw-form-label">Address<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="address" name="address" id="address" class="tw-form-control w-full lg:w-1/4"><br>
                                <span v-if="errors.address" class="text-danger text-xs my-1">{{ errors.address[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="mobile_number" class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="mobile_number" name="mobile_number" id="mobile_number" class="tw-form-control w-full lg:w-1/4"><br>
                                <span v-if="errors.mobile_number" class="text-danger text-xs my-1">{{ errors.mobile_number[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-3" v-if="this.membership == 'member'">
                    <div class="flex">
                        <div class="w-1/4">
                            <label class="tw-form-label">Member<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-3/4 lg:w-1/5">
                            <multiselect v-model="selectedUsers" id="ajax" dusk="search" label="fullname" track-by="fullname" open-direction="bottom" :options="users" :show-labels="false" :searchable="true" :internal-search="true" :clear-on-select="false" :close-on-select="true" :options-limit="300" :max-height="600" :show-no-results="true" :hide-selected="false">

                                <template slot="tag" slot-scope="{ option, remove }">
                                    <span>{{selectedUsers}}</span>
                                    <span class="custom__tag">
                                        <span>{{ (option.fullname) }}</span>
                                        <span class="custom__remove" @click="remove(option)">❌</span>
                                    </span>
                                </template>

                                <template slot="clear" slot-scope="props">
                                    <div class="multiselect__clear" v-if="selectedUsers.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                                </template>

                                <span slot="noResult">Oops! No users found.</span>
                            </multiselect>
                            <span v-if="errors.selectedUsers" class="text-danger text-xs my-1">{{ errors.selectedUsers[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <div class="flex">
                    <div class="w-1/4">
                        <label for="amount" class="tw-form-label">Amount<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4">
                        <input type="number" v-model="amount" id="amount" class="tw-form-control w-full lg:w-1/4"><br>
                        <span v-if="errors.amount" class="text-danger text-xs my-1">{{ errors.amount[0] }}</span>
                    </div>
                </div>    
            </div>

            <div class="my-3" v-if="parseInt(this.amount) >= '100000'">
                <div class="flex">
                    <div class="w-1/4">
                        <label for="pan_number" class="tw-form-label">PAN Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4">
                        <input type="text" v-model="pan_number" name="pan_number" id="pan_number" class="tw-form-control w-full lg:w-1/4" placeholder="PAN Number"><br>
                        <span v-if="errors.pan_number" class="text-danger text-xs my-1">{{ errors.pan_number[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <div class="flex">
                    <div class="w-1/4">
                        <label class="tw-form-label">Method<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4">
                        <select v-model="method" id="method" class="repeats tw-form-control w-full lg:w-1/4">
                            <option value="" disabled>Select Method</option>
                            <option v-for="list in methodlist" v-bind:value="list.id">{{ list.name }}</option>
                        </select><br> 
                        <span v-if="errors.method" class="text-danger text-xs my-1">{{ errors.method[0] }}</span>
                    </div>
                </div>

                <div class="my-3" v-if="this.method=='cheque'">
                    <div class="flex">
                        <div class="w-1/4">
                            <label for="cheque_number" class="tw-form-label">Cheque Number<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-3/4">
                            <input type="text" v-model="cheque_number" name="cheque_number" id="cheque_number" class="tw-form-control w-full lg:w-1/4" placeholder="Cheque Number"><br>
                            <span v-if="errors.cheque_number" class="text-danger text-xs my-1">{{ errors.cheque_number[0] }}</span>
                        </div>
                    </div>
      
                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="account_number" class="tw-form-label">Account Number<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="account_number" name="account_number" id="account_number" class="tw-form-control w-full lg:w-1/4" placeholder="Account Number"><br>
                                <span v-if="errors.account_number" class="text-danger text-xs my-1">{{ errors.account_number[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="payee_name" class="tw-form-label">Payee Name<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="payee_name" name="payee_name" id="payee_name" class="tw-form-control w-full lg:w-1/4" placeholder="Payee Name"><br>
                                <span v-if="errors.payee_name" class="text-danger text-xs my-1">{{ errors.payee_name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-3" v-if="this.method == 'demanddraft'">
                    <div class="flex">
                        <div class="w-1/4">
                            <label for="payable_at" class="tw-form-label">Payable At<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-3/4">
                            <input type="text" v-model="payable_at" name="payable_at" id="payable_at" class="tw-form-control w-full lg:w-1/4" placeholder="Payable At"><br>
                            <span v-if="errors.payable_at" class="text-danger text-xs my-1">{{ errors.payable_at[0] }}</span>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="account_number" class="tw-form-label">Account Number<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="account_number" name="account_number" id="account_number" class="tw-form-control w-full lg:w-1/4" placeholder="Account Number"><br>
                                <span v-if="errors.account_number" class="text-danger text-xs my-1">{{ errors.account_number[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-3" v-if="this.method == 'card'">
                    <div class="flex">
                        <div class="w-1/4">
                            <label for="card_name" class="tw-form-label">Card Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="w-3/4">
                            <input type="text" v-model="card_name" name="card_name" id="card_name" class="tw-form-control w-full lg:w-1/4" placeholder="Card Name"><br>
                            <span v-if="errors.card_name" class="text-danger text-xs my-1">{{ errors.card_name[0] }}</span>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="flex">
                            <div class="w-1/4">
                                <label for="bank_name" class="tw-form-label">Bank Name<span class="text-red-500">*</span></label>
                            </div>
                            <div class="w-3/4">
                                <input type="text" v-model="bank_name" name="bank_name" id="bank_name" class="tw-form-control w-full lg:w-1/4" placeholder="Bank Name"><br>
                                <span v-if="errors.bank_name" class="text-danger text-xs my-1">{{ errors.bank_name[0] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <div class="flex">
                    <div class="w-1/4">
                        <label class="tw-form-label">Status<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4">
                        <select v-model="status" id="status" class="repeats tw-form-control w-full lg:w-1/4">
                            <option value="" disabled>Select Status</option>
                            <option v-for="list in statuslist" v-bind:value="list.id">{{ list.name }}</option>
                        </select><br> 
                        <span v-if="errors.status" class="text-danger text-xs my-1">{{ errors.status[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-3" v-if="this.status == 'cancel'">
                <div class="flex">
                    <div class="w-1/4">
                        <label for="comments" class="tw-form-label">Comments<span class="text-red-500">*</span></label>
                    </div>
                    <div class="w-3/4">
                        <input type="text" v-model="comments" name="comments" id="comments" class="tw-form-control w-full lg:w-1/4" placeholder="Comments"><br>
                        <span v-if="errors.comments" class="text-danger text-xs my-1">{{ errors.comments[0] }}</span>
                    </div>
                </div>
            </div>

            <div class="my-3">
                <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click="submitForm()" id="update">Submit</button>
            </div>
        </div>           
    </div>       
</template>

<script>
    import Multiselect from 'vue-multiselect'
    Vue.component('multiselect', Multiselect)
    import datetime from 'vuejs-datetimepicker';
    export default {
        props:['url','id'],
        components: {
            Multiselect
        },
        data() {
            return {
                list: [],
                selectedUsers: [],
                isLoading: false,
                users:[],
                membership:'',
                first_name:'',
                last_name:'',
                address:'',
                mobile_number:'',
                pan_number:'',
                amount:'',
                method:'',
                cheque_number:'',
                account_number:'',
                payee_name:'',
                payable_at:'',
                card_name:'',
                bank_name:'',
                status:'',
                comments:'',
                methodlist:[{id:'card' , name:'Card'} , {id:'Cash' , name:'Cash'} , {id:'cheque' , name:'Cheque'} , {id:'demanddraft' , name:'Demand Draft'}],
                statuslist:[{id:'cancel' , name:'Cancel'} , {id:'deposited' , name:'Deposited'} , {id:'pending' , name:'Pending'}],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/fund/editList/'+this.id).then(response => {
                    this.list = response.data;       
                    this.setData(); 
                });
            },

            setData()
            {
                if(Object.keys(this.list).length>0)
                {
                    this.membership     = this.list.membership;
                    this.first_name     = this.list.first_name;
                    this.last_name      = this.list.last_name;
                    this.address        = this.list.address;
                    this.mobile_number  = this.list.mobile_number;
                    this.pan_number     = this.list.pan_number;
                    this.amount         = this.list.amount;
                    this.method         = this.list.method;
                    this.cheque_number  = this.list.cheque_number;
                    this.account_number = this.list.account_number;
                    this.payee_name     = this.list.payee_name;
                    this.payable_at     = this.list.payable_at;
                    this.card_name      = this.list.card_name;
                    this.bank_name      = this.list.bank_name;
                    this.status         = this.list.status;
                    this.comments       = this.list.comments;
                    this.selectedUsers  = this.list.selectedUsers;
                    this.users          = this.list.memberlist;
                }
            },

            limitText (count) 
            {   
                return `and ${count} other users`
            },

            customLabel ({ fullname}) 
            {
                return `${fullname}`
            },

            clearAll () 
            {
                this.selectedUsers = [];
            },

            asyncFind (query) 
            {
                this.isLoading = true
                this.getMember(query);
            },
            
            submitForm()
            {
                this.errors=[];
                this.success=null;    
        
                axios.post('/admin/fund/edit/'+this.id,{
                    membership:this.membership,
                    first_name:this.first_name,
                    last_name:this.last_name,
                    address:this.address,
                    mobile_number:this.mobile_number,
                    pan_number:this.pan_number,
                    amount:this.amount,
                    method:this.method,
                    cheque_number:this.cheque_number,
                    account_number:this.account_number,
                    payee_name:this.payee_name,
                    payable_at:this.payable_at,
                    card_name:this.card_name,
                    bank_name:this.bank_name,
                    status:this.status,
                    comments:this.comments,
                    selectedUsers:this.selectedUsers,
                }).then(response => {  
                    this.success=response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            this.getData();
        }
    }
</script>