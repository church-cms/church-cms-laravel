<template>
    <div class="relative">
        <portal to="add_fund">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Offerings</h1>
                <div class="">

                    <div class="flex lg:justify-end md:justify-end items-center">
                        <div class="">
                            <select name="paymentgateway_id" v-model="paymentgateway_id" id="paymentgateway_id" class="tw-form-control w-full" v-on:change="searchList()">
                                <option value="">Payment Gateway</option>
                                <option v-for="paymentgateway in paymentgateways" v-bind:value="paymentgateway.id">{{ paymentgateway.display_name }}</option>
                            </select>
                        </div>
                        <div class="">
                            <select name="type" v-model="type" id="name" class="tw-form-control w-full" v-on:change="searchList()">
                                <option value="">Method</option>
                                <option v-for="list in methodlist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                        </div>
                        <div class="">
                            <select name="status" v-model="status" id="name" class="tw-form-control w-full" v-on:change="searchList()">
                                <option value="">Status</option>
                                <option v-for="list in statuslist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                        </div>
                        <div class="search relative w-48">
                            <input type="text" name="search" v-model="search" class="tw-form-control w-full relative" placeholder="Search">                    
                            <a href="#" @click="searchList()" class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                            </a>
                        </div>
                        <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                            <a href="#" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4" @click="resetForm()">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="w-full">
            <div class="flex flex-row custom-table overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-t-2 border-b-2">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">PaymentGateway</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Method</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Amount</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" style="width: 10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-grey-light" v-if="Object.keys(this.funds).length > 0">
                        <tr class="border-b" v-for="fund in funds">
                            <td class="py-3 px-2">{{ fund.name }}</td>
                            <td class="py-3 px-2">{{ fund.payaccount }}</td>
                            <td class="py-3 px-2">{{ fund.method }}</td>
                            <td class="py-3 px-2">{{ fund.amount }}</td>
                            <td class="py-3 px-2">{{ fund.status }}</td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <a :href="url+'/admin/fund/edit/'+fund.id" title="Edit">
                                        <img :src="url+'/uploads/icons/edit1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                    <a :href="url+'/admin/fund/show/'+fund.id" title="Show"> 
                                        <img :src="url+'/uploads/icons/show.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                    <a href="#" @click="deletefund(fund.id)" title="Delete">
                                        <img :src="url+'/uploads/icons/delete1.svg'" class="w-4 h-4 fill-current text-black-500 mx-1">
                                    </a>
                                </div>
                            </td>
                        </tr>
                   </tbody>
                   <tbody class="bg-grey-light" v-else>
                        <tr class="border-t-2 border-b-2">    
                            <td colspan="5" class="py-3 px-2">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                   </tbody>
                </table>   
            </div>
            <div v-if="this.page_count>1">
                <paginate v-model="page" :page-count="this.page_count" :page-range="3" :margin-pages="1" :click-handler="getData" :prev-text="'<'" :next-text="'>'" :container-class="'pagination'" :page-class="'page-item'" :prev-link-class="'prev'" :next-link-class="'next'"></paginate>
            </div>   
        </div>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                funds:[],
                search:'',
                type:'',
                status:'',
                errors:[],
                success:null,
                methodlist:[{id:'card' , name:'Card'} , {id:'Cash' , name:'Cash'} , {id:'cheque' , name:'Cheque'} , {id:'demanddraft' , name:'Demand Draft'}],
                statuslist:[{id:'cancel' , name:'Cancel'} , {id:'deposited' , name:'Deposited'} , {id:'pending' , name:'Pending'}],
                paymentgateways:[],
                paymentgateway_id:'',
                total: 0,
                page: 1,
                page_count: 0,
            }
        },

        methods:
        {
            getData(page=1)
            {
                axios.get('/admin/fund/list?search='+this.search+'&type='+this.type+'&status='+this.status+'&paymentgateway_id='+this.paymentgateway_id+'&page='+page).then(response => {
                    this.funds          = response.data.data;
                    this.page_count     = response.data.meta.last_page;
                    this.total          = response.data.meta.total;
                    //console.log(this.funds);    
                });
            },

            getGateway()
            {
                 axios.get('/admin/payaccount/add/list').then(response => {
                    this.paymentgateways = response.data.data;
                    //console.log(response);  
                });
            },
            

            searchList()
            {
                this.getData();
            },

            resetForm()
            {
                this.search = '';
                this.type = '';
                this.status = '';
                this.paymentgateway_id='';
                this.getData();
            },

            deletefund(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Fund ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+ '/admin/fund/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        });
                    }
                    else 
                    {
                        swal("Cancelled");
                    }
                });
            }
        },
  
        created()
        {   
            this.getData(); 
            this.getGateway();
        }
    }
</script>