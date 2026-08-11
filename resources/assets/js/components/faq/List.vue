<template>
    <div class="relative">
        <div v-if="success" class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-md">
            {{ success }}
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-700 text-white text-xs uppercase tracking-wider">
                        <th class="px-4 py-3 text-left w-px">#</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Question</th>
                        <th class="px-4 py-3 text-left">Answer</th>
                        <th class="px-4 py-3 text-left w-16">Order</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" v-if="faqs.length > 0">
                    <tr class="hover:bg-gray-50 transition-colors" v-for="(faq, index) in faqs" :key="faq.id">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ (page - 1) * 15 + index + 1 }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs font-medium">{{ faq.category }}</td>
                        <td class="px-4 py-3 text-gray-800 font-medium max-w-xs">{{ trim(faq.question) }}</td>
                        <td class="px-4 py-3 text-gray-500 max-w-sm" v-html="trim(faq.answer)"></td>
                        <td class="px-4 py-3 text-gray-500 text-xs text-center">{{ faq.order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <a :href="url + '/admin/faq/show/' + faq.id" target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-sky-50 text-sky-700 hover:bg-sky-100 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </a>
                                <a :href="url + '/admin/faq/edit/' + faq.id"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <button @click="deleteFaq(faq.id)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">No FAQs found.</td>
                    </tr>
                </tbody>
            </table>

            <div class="px-4 py-3 border-t border-gray-100" v-if="page_count > 1">
                <paginate
                    v-model="page"
                    :page-count="page_count"
                    :page-range="3"
                    :margin-pages="1"
                    :click-handler="getData"
                    :prev-text="'&lsaquo;'"
                    :next-text="'&rsaquo;'"
                    :container-class="'pagination'"
                    :page-class="'page-item'"
                    :prev-link-class="'prev'"
                    :next-link-class="'next'"
                ></paginate>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url'],
        data() {
            return {
                faqs: [],
                success: null,
                errors: [],
                total: 0,
                page: 1,
                page_count: 0,
            };
        },

        methods: {
            getData(page = 1) {
                this.page = page;
                axios.get('/admin/faq/list?page=' + page).then(response => {
                    this.faqs = response.data.data;
                    this.page_count = response.data.meta.last_page;
                    this.total = response.data.meta.total;
                });
            },

            trim(string) {
                if (!string) return '';
                return string.length > 75 ? string.substring(0, 75) + '...' : string;
            },

            deleteFaq(id) {
                var self = this;
                swal({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this FAQ?',
                    icon: 'warning',
                    buttons: ['Cancel', 'Delete'],
                    dangerMode: true,
                }).then(function(confirmed) {
                    if (confirmed) {
                        axios.get(self.url + '/admin/faq/delete/' + id).then(response => {
                            self.success = response.data.success;
                            self.getData(self.page);
                        });
                    }
                });
            },
        },

        created() {
            this.getData();
        },
    };
</script>
