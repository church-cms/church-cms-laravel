<template>
    <div class="relative">
        <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
            <h1 class="admin-h1">Payment Gateways</h1>
            
        </div>

        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <div class="flex-wrap custom-table overflow-auto mt-3">
            <table class="w-full">
                <thead class="border-t-2 border-b-2">
                    <tr>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">#</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Gateway Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Display Name</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Instructions</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Active</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker" style="width:15%;">Action</th>
                    </tr>
                </thead>
                <tbody v-if="gateways.length">
                    <tr class="border-t-2 border-b-2" v-for="(gateway, index) in gateways" :key="gateway.id">
                        <td class="py-3 px-2">{{ index + 1 }}</td>
                        <td class="py-3 px-2">{{ gateway.name }}</td>
                        <td class="py-3 px-2">{{ gateway.display_name }}</td>
                        <td class="py-3 px-2">{{ gateway.instructions }}</td>
                        <td class="py-3 px-2">
                            <span v-if="gateway.status==1">
                                <a href="#" @click.prevent="statusUpdate(gateway.id)">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g><g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg>
                                </a>
                            </span>
                            <span v-else>
                                <a href="#" @click.prevent="statusUpdate(gateway.id)">
                                    <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-red-600 fill-current"><path d="m256 512c-141.160156 0-256-114.839844-256-256s114.839844-256 256-256 256 114.839844 256 256-114.839844 256-256 256zm0-475.429688c-120.992188 0-219.429688 98.4375-219.429688 219.429688s98.4375 219.429688 219.429688 219.429688 219.429688-98.4375 219.429688-219.429688-98.4375-219.429688-219.429688-219.429688zm0 0"></path><path d="m347.429688 365.714844c-4.679688 0-9.359376-1.785156-12.929688-5.359375l-182.855469-182.855469c-7.144531-7.144531-7.144531-18.714844 0-25.855469 7.140625-7.140625 18.714844-7.144531 25.855469 0l182.855469 182.855469c7.144531 7.144531 7.144531 18.714844 0 25.855469-3.570313 3.574219-8.246094 5.359375-12.925781 5.359375zm0 0"></path><path d="m164.570312 365.714844c-4.679687 0-9.355468-1.785156-12.925781-5.359375-7.144531-7.140625-7.144531-18.714844 0-25.855469l182.855469-182.855469c7.144531-7.144531 18.714844-7.144531 25.855469 0 7.140625 7.140625 7.144531 18.714844 0 25.855469l-182.855469 182.855469c-3.570312 3.574219-8.25 5.359375-12.929688 5.359375zm0 0"></path></svg>
                                </a>
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex items-center">
                                <a :href="url+'/admin/paymentgateway/edit/'+gateway.id" title="Edit" class="mr-2">
                                    <img :src="url+'/uploads/icons/edit.svg'" class="w-4 h-4">
                                </a>
                                <a href="#" @click.prevent="deleteGateway(gateway.id)" title="Delete">
                                    <img :src="url+'/uploads/icons/delete1.svg'" class="w-4 h-4">
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr class="border-t-2 border-b-2">
                        <td colspan="6" class="text-center py-4">No records found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url'],
        data() {
            return {
                gateways: [],
                success: null,
            }
        },
        methods: {
            getData() {
                axios.get('/admin/paymentgateway/list').then(response => {
                    this.gateways = response.data.data;
                });
            },
            statusUpdate(id) {
                var self = this;
                swal({
                    title: 'Are you sure?',
                    text: 'Do you want to update the gateway status?',
                    icon: 'info',
                    buttons: ['No', 'Yes'],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        axios.post(self.url + '/admin/paymentgateway/status/' + id + '/update').then(response => {
                            self.success = response.data.success;
                            window.location.reload();
                        });
                    } else {
                        swal('Cancelled');
                    }
                });
            },
            deleteGateway(id) {
                var self = this;
                swal({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this payment gateway?',
                    icon: 'warning',
                    buttons: ['No', 'Yes'],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        axios.delete(self.url + '/admin/paymentgateway/delete/' + id).then(response => {
                            self.success = response.data.success;
                            window.location.reload();
                        });
                    } else {
                        swal('Cancelled');
                    }
                });
            },
        },
        created() {
            this.getData();
        }
    }
</script>
