<template>
    <div class="relative">
        <portal to="donation_header">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Donations</h1>
                <div class="flex items-center gap-2">
                    <select v-model="filterStatus" class="tw-form-control" @change="getData()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select v-model="filterCategory" class="tw-form-control" @change="getData()">
                        <option value="">All Types</option>
                        <option value="tithe">Tithe</option>
                        <option value="offering">Offering</option>
                        <option value="building">Building Fund</option>
                        <option value="missions">Missions</option>
                        <option value="welfare">Welfare</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="search relative w-48">
                        <input type="text" v-model="search" class="tw-form-control w-full" placeholder="Search name…" @keyup.enter="getData()">
                    </div>
                    <a href="#" @click.prevent="resetFilters()" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                </div>
            </div>
        </portal>

        <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>

        <div class="w-full">
            <!-- Summary bar -->
            <div class="flex gap-4 mb-4 text-sm text-gray-600">
                <span>Total: <strong>{{ total }}</strong></span>
                <span>Total Amount: <strong>{{ totalAmount }}</strong></span>
            </div>

            <div class="flex flex-row custom-table overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-t-2 border-b-2">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Donor</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Type</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Amount</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Method</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Date</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" style="width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="donations.length > 0">
                        <tr class="border-b" v-for="d in donations" :key="d.id">
                            <td class="py-3 px-2">
                                <div class="font-medium text-sm">{{ d.name || '—' }}</div>
                                <div class="text-xs text-gray-400">{{ d.email }}</div>
                            </td>
                            <td class="py-3 px-2 capitalize">{{ d.category || '—' }}</td>
                            <td class="py-3 px-2 font-semibold">{{ d.currency }} {{ parseFloat(d.amount).toLocaleString(undefined, {minimumFractionDigits: 2}) }}</td>
                            <td class="py-3 px-2 capitalize">{{ d.method }}</td>
                            <td class="py-3 px-2">
                                <span :class="statusClass(d.status)" class="px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ d.status.charAt(0).toUpperCase() + d.status.slice(1) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-sm">{{ d.donated_at }}</td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <a :href="url + '/admin/donation/show/' + d.id" title="View">
                                        <img :src="url + '/uploads/icons/show.svg'" class="w-4 h-4 mx-1">
                                    </a>
                                    <a href="#" @click.prevent="remove(d.id)" title="Delete">
                                        <img :src="url + '/uploads/icons/delete1.svg'" class="w-4 h-4 mx-1">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="7" class="py-6 text-center text-gray-400 text-sm">No donations found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="pageCount > 1">
                <paginate v-model="page" :page-count="pageCount" :page-range="3" :margin-pages="1"
                    :click-handler="getData" :prev-text="'&lsaquo;'" :next-text="'&rsaquo;'"
                    :container-class="'pagination'" :page-class="'page-item'"
                    :prev-link-class="'prev'" :next-link-class="'next'">
                </paginate>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['url'],
    data() {
        return {
            donations: [],
            search: '',
            filterStatus: '',
            filterCategory: '',
            success: null,
            total: 0,
            totalAmount: 0,
            page: 1,
            pageCount: 0,
        };
    },
    methods: {
        getData(page = 1) {
            axios.get('/admin/donation/list', {
                params: {
                    search: this.search,
                    status: this.filterStatus,
                    category: this.filterCategory,
                    page,
                },
            }).then(res => {
                this.donations  = res.data.data;
                this.pageCount  = res.data.meta.last_page;
                this.total      = res.data.meta.total;
                this.totalAmount = this.donations
                    .reduce((sum, d) => sum + parseFloat(d.amount), 0)
                    .toLocaleString(undefined, { minimumFractionDigits: 2 });
            });
        },
        resetFilters() {
            this.search = '';
            this.filterStatus = '';
            this.filterCategory = '';
            this.getData();
        },
        statusClass(status) {
            return {
                pending:   'bg-yellow-100 text-yellow-700',
                completed: 'bg-green-100 text-green-700',
                cancelled: 'bg-red-100 text-red-600',
            }[status] || '';
        },
        remove(id) {
            swal({ title: 'Are you sure?', text: 'Delete this donation?', icon: 'warning', buttons: ['No', 'Yes'], dangerMode: true })
                .then(confirm => {
                    if (confirm) {
                        axios.delete(this.url + '/admin/donation/delete/' + id)
                            .then(() => this.getData());
                    }
                });
        },
    },
    created() {
        this.getData();
    },
};
</script>
