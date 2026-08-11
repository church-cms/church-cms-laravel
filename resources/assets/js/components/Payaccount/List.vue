<template>
    <div class="relative">
        
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Pay Accounts </h1>
                <div class="">
                    <div class="flex lg:justify-end md:justify-end items-center">
                       <!--  <div class="search relative w-48">
                            <input type="text" name="search" v-model="search" class="tw-form-control w-full relative" placeholder="Search">                    
                            <a href="#" @click="searchList()" class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                            </a>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="#" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4" @click="resetForm()">Reset</a>
                        </div> -->
                        <div class="relative flex items-center w-1/2 justify-end">
                            <a :href="url+'/admin/payaccount/create'" id="upload-btn" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                                <span class="mx-1 text-sm font-semibold">Add</span>
                                <img :src="url+'/uploads/icons/plus.svg'" class="w-3 h-3">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
    
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="">
            <div class="flex-wrap custom-table overflow-auto">
                <div class="flex flex-wrap">
                    <table class="w-full">
                        <thead class="border-t-2 border-b-2">
                            <tr class="border-t-2 border-b-2">
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Paymentgateway Name</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Details</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Active</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker" style="width:20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-grey-light" v-if="this.payaccounts!=''">
                            <tr class="border-t-2 border-b-2" v-for="payaccount in payaccounts">
                                <td class="py-3 px-2">{{ payaccount.display_name }}</td>
                                <td class="py-3 px-2">
                                  <div v-if="payaccount.gatewayname=='bank'">
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Account Holder Name</span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param1 }}</p>
                                    </div>
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Account Number </span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param2 }}</p>
                                    </div>
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Bank Name </span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param3 }}</p>
                                    </div>
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">IFSC Code </span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param5 }}</p>
                                    </div>
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Branch name </span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param6 }}</p>
                                    </div>
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Address</span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param4 }}</p>
                                    </div>
                                  </div>   
                                  
                                  <div v-if="payaccount.gatewayname=='gpay'">
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Gpay Number</span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param1 }}</p>
                                    </div>
                                  </div>

                                  <div v-if="payaccount.gatewayname=='cash'">
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Cash Account</span></p>
                                    </div>
                                  </div>     

                                  <div v-if="payaccount.gatewayname=='cheque'">
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">Payee Name</span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param1 }}</p>
                                    </div>
                                  </div>   

                                   <div v-if="payaccount.gatewayname=='upi'">
                                    <div class="flex items-center border">
                                        <p class="text-gray-darker w-1/2 p-1 font-semibold"><span class="text-sm">UPI ID</span></p>
                                        <p class="w-1/2 p-1 border-l my-2">{{ payaccount.param1 }}</p>
                                    </div>
                                  </div>  

                                </td>
                                <td class="py-3 px-2">
                                    <span v-if="payaccount.status==1">
                                      <a href="#" @click="statusUpdate(payaccount.id)" title="Delete"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893
                                     c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561
                                     c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762
                                     C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g> <g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487
                                     c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487
                                     C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg></a>
                                     </span>
                                     <span v-else="">
                                       <a href="#" @click="statusUpdate(payaccount.id)" title="Delete"> <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-red-600 fill-current"><path d="m256 512c-141.160156 0-256-114.839844-256-256s114.839844-256 256-256 256 114.839844 256 256-114.839844 256-256 256zm0-475.429688c-120.992188 0-219.429688 98.4375-219.429688 219.429688s98.4375 219.429688 219.429688 219.429688 219.429688-98.4375 219.429688-219.429688-98.4375-219.429688-219.429688-219.429688zm0 0"></path><path d="m347.429688 365.714844c-4.679688 0-9.359376-1.785156-12.929688-5.359375l-182.855469-182.855469c-7.144531-7.144531-7.144531-18.714844 0-25.855469 7.140625-7.140625 18.714844-7.144531 25.855469 0l182.855469 182.855469c7.144531 7.144531 7.144531 18.714844 0 25.855469-3.570313 3.574219-8.246094 5.359375-12.925781 5.359375zm0 0"></path><path d="m164.570312 365.714844c-4.679687 0-9.355468-1.785156-12.925781-5.359375-7.144531-7.140625-7.144531-18.714844 0-25.855469l182.855469-182.855469c7.144531-7.144531 18.714844-7.144531 25.855469 0 7.140625 7.140625 7.144531 18.714844 0 25.855469l-182.855469 182.855469c-3.570312 3.574219-8.25 5.359375-12.929688 5.359375zm0 0"></path></svg></a>
                                     </span>
                                </td>
                                <td class="py-3 px-2">
                                <div class="flex items-center">
                                   <div class="flex flex-col text-xs w-20 my-1">
                                   <!--  <a :href="url+'/'+mode+'/post/show/'+post.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank">view</a>
                                    <a :href="url+'/'+mode+'/post/edit/'+post.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank" v-if="auth_id == post.created_by">edit</a> -->
                                   <a href="#" @click="deleteAccount(payaccount.id)" title="Delete">
                                        <img :src="url+'/uploads/icons/delete1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                </div>

                                   
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr class="border-t-2 border-b-2">
                                <td colspan="5" style="text-align: center;">No records found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                payaccounts:[],
                search:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/payaccount/list/').then(response => {
                    this.payaccounts    = response.data.data;
                    //console.log(this.payaccounts);    
                });
            },

            statusUpdate(id)
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want update payaccount status ?',
                    icon: "info",
                    buttons: [
                      'No',
                      'Yes'
                    ],
                    dangerMode: true,
                  }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                      axios.get(thisswal.url+'/admin/payaccount/status/'+id+'/update').then(response => {
                        thisswal.success = response.data.success;
                        window.location.reload();
                      }); 
                    }
                    else 
                    {
                      swal("Cancelled");
                    }
                });
            },

            deleteAccount(id)
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Payaccount ?',
                    icon: "info",
                    buttons: [
                      'No',
                      'Yes'
                    ],
                    dangerMode: true,
                  }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                      axios.get(thisswal.url+'/admin/payaccount/delete/'+ id).then(response => {
                        thisswal.success = response.data.success;
                        window.location.reload();
                      }); 
                    }
                    else 
                    {
                      swal("Cancelled");
                    }
                });
            },

        },
  
        created()
        {   
            this.getData(); 
          
        }
    }
</script>