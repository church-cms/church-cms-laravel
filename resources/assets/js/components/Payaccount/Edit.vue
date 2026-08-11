<template>
    <div class="bg-white shadow px-4 py-3 my-3">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="paymentgateway_id" class="tw-form-label">Payment Gateway<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="paymentgateway_id" v-model="paymentgateway_id" name="paymentgateway_id">
                            <option value="" disabled>Select PaymentMethod</option>
                            <option v-for="paymentgateway in paymentgateways" v-bind:value="paymentgateway.name">{{ paymentgateway.display_name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.paymentgateway_id" class="text-red-500 text-xs font-semibold">{{ errors.paymentgateway_id[0] }}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
               <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label for="status" class="tw-form-label">Status<span class="text-red-500">*</span></label>
                </div> 
                <div class="flex tw-form-control">
                    <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8">
                        <input type="radio" name="status" v-model="status" id="active" v-bind:value="1"> <span class="text-sm mx-1">Active</span>
                    </div>
                         <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8">
                            <input type="radio" name="status"  v-model="status" id="inactive" v-bind:value="0"> <span class="text-sm mx-1">Inactive</span></div>
                        </div> <!---->
                    </div>
            </div>
        </div>

<!-- bank form -->        
<div v-if="this.paymentgateway_id=='bank'">
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="account_name" class="tw-form-label">Account Holder name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="account_name" v-model="account_name" name="account_name" Placeholder="Holder name">
                    </div>
                    <span v-if="errors.account_name" class="text-red-500 text-xs font-semibold">{{ errors.account_name[0] }}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="account_number" class="tw-form-label">Account Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="account_number" v-model="account_number" name="account_number" Placeholder="Account Number">
                    </div>
                    <span v-if="errors.account_number" class="text-red-500 text-xs font-semibold">{{ errors.account_number[0] }}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="ifsc_code" class="tw-form-label">IFSC Code<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="ifsc_code" v-model="ifsc_code" name="ifsc_code" Placeholder="IFSC CODE">
                    </div>
                    <span v-if="errors.ifsc_code" class="text-red-500 text-xs font-semibold">{{ errors.ifsc_code[0] }}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="branch_name" class="tw-form-label">Branch Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="branch_name" v-model="branch_name" name="branch_name" Placeholder="Branch name">
                    </div>
                    <span v-if="errors.branch_name" class="text-red-500 text-xs font-semibold">{{ errors.branch_name[0] }}</span>
                </div> 
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="bank_name" class="tw-form-label">Bank Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="bank_name" v-model="bank_name" name="bank_name" Placeholder="Bank name">
                    </div>
                    <span v-if="errors.bank_name" class="text-red-500 text-xs font-semibold">{{ errors.bank_name[0] }}</span>
                </div> 
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="branch_address" class="tw-form-label">Branch Address<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="tw-form-control w-full" id="branch_address" v-model="branch_address" name="branch_address" Placeholder="Branch Address">
                    </div>
                    <span v-if="errors.branch_address" class="text-red-500 text-xs font-semibold">{{ errors.branch_address[0] }}</span>
                </div> 
            </div>
        </div>
</div>

<!-- Bank form -->

<!-- cheque -->
<div v-if="this.paymentgateway_id=='cheque'">
 <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="payee_name" class="tw-form-label">Payee Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                         <input type="text" class="tw-form-control w-full" id="payee_name" v-model="payee_name" name="payee_name" Placeholder="Payee name">
                    </div>
                    <span v-if="errors.payee_name" class="text-red-500 text-xs font-semibold">{{ errors.payee_name[0] }}</span>
                </div> 
            </div>
        </div>
</div>
<!-- cheque -->

<!-- Gpay -->
<div v-if="this.paymentgateway_id=='gpay'">
 <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="gpay_number" class="tw-form-label">Gpay Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                         <input type="text" class="tw-form-control w-full" id="gpay_number" v-model="gpay_number" name="gpay_number" Placeholder="Gpay Number">
                    </div>
                    <span v-if="errors.gpay_number" class="text-red-500 text-xs font-semibold">{{ errors.gpay_number[0] }}</span>
                </div> 
            </div>
        </div>
</div>
<!-- Gpay -->

<!-- UPI -->
<div v-if="this.paymentgateway_id=='upi'">
 <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="upi_id" class="tw-form-label">UPI ID<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                         <input type="text" class="tw-form-control w-full" id="upi_id" v-model="upi_id" name="upi_id" Placeholder="UPI ID">
                    </div>
                    <span v-if="errors.upi_id" class="text-red-500 text-xs font-semibold">{{ errors.upi_id[0] }}</span>
                </div> 
            </div>
        </div>
</div>
<!-- UPI -->


        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="comments" class="tw-form-label">Comments</label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" v-model="comments" name="comments" id="comments" class="tw-form-control w-full" Placeholder="Description" rows="3"></textarea>
                    </div>
                    <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{ errors.comments[0] }}</span>
                </div> 
            </div>
        </div>


       

        <div class=" mb-6 mt-3">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a> 
        </div>
        
    </div>
</template>

<script>
    import datetime from 'vuejs-datetimepicker';
    export default{
        props:['url','date'],
        components:
        {  
            datetime,
        },
        data () {
            return {
                paymentgateways:[],
                paymentgateway_id:'',
                status:"1",
                account_name:'',
                account_number:'',
                ifsc_code:'',
                branch_name:'',
                bank_name:'',
                branch_address:'',
                payee_name:'',
                gpay_number:'',
                upi_id:'',
                comments:'',
                errors:[],
                success:null,
            }
        },
        methods: 
        {
            resetForm()
            {
                this.paymentgateway_id='';
                this.status="1";
                this.account_name='';
                this.account_name='';
                this.ifsc_code='';
                this.branch_name='';
                this.bank_name='';
                this.branch_address='';
                this.payee_name='';
                this.gpay_number='';
                this.upi_id='';
                this.comments='';

            },

            submitForm()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData(); 
                formData.append('paymentgateway_id',this.paymentgateway_id);          
                formData.append('status',this.status);
                formData.append('comments',this.comments);
                formData.append('account_name',this.account_name);          
                formData.append('account_number',this.account_number);
                formData.append('ifsc_code',this.ifsc_code);
                formData.append('branch_name',this.branch_name);
                formData.append('branch_address',this.branch_address);
                formData.append('bank_name',this.bank_name);
                formData.append('payee_name',this.payee_name);
                formData.append('gpay_number',this.gpay_number);
                formData.append('upi_id',this.upi_id);

                axios.post(this.url+'/admin/payaccount/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
             
           getData()
            {
                 axios.get('/admin/payaccount/add/list').then(response => {
                    this.paymentgateways = response.data.data;
                    console.log(response);  
                });
            },
            
        },
        created()
        {
            //
           this.getData();
        }
    }
</script>