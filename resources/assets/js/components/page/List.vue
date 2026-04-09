<template>
    <div class="relative">
        <div v-if="success != null" class="alert alert-success" id="success-alert">{{ success }}</div>
        <div class="flex-wrap custom-table overflow-auto">
            <div class="flex flex-wrap">
                <table class="w-full">
                    <thead class="border-t-2 border-b-2">
                        <tr>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="5%">#</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="8%">Cover</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="22%">Page Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="15%">Category</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="30%">Description</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="8%">Likes</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody v-if="pages.length > 0">
                        <tr class="border-b" v-for="(page, index) in pages" :key="page.id">
                            <td class="py-3 px-2 text-sm text-gray-600">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                            <td class="py-3 px-2">
                                <img v-if="page.cover_image" :src="page.cover_image" class="w-12 h-12 object-cover rounded">
                                <div v-else class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-300 text-lg">&#9741;</div>
                            </td>
                            <td class="py-3 px-2 text-sm font-medium text-gray-800">{{ page.page_name }}</td>
                            <td class="py-3 px-2 text-sm text-gray-600">{{ page.category }}</td>
                            <td class="py-3 px-2 text-sm text-gray-500">{{ stripHtml(page.description) }}</td>
                            <td class="py-3 px-2 text-sm text-gray-600">
                                <span title="Likes">&#128077; {{ page.like_count }}</span>
                                <span class="ml-2" title="Dislikes">&#128078; {{ page.unlike_count }}</span>
                            </td>
                            <td class="py-3 px-2 whitespace-no-wrap">
                                <a v-if="page.category_slug && page.slug" :href="url + '/page/' + page.category_slug + '/' + page.slug" class="capitalize text-white blue-bg rounded px-2 py-1 text-xs font-medium mr-1" target="_blank">View</a>
                                <a :href="url + '/' + mode + '/page/edit/' + page.id" class="capitalize text-white blue-bg rounded px-2 py-1 text-xs font-medium mr-1" target="_blank">Edit</a>
                                <a href="#" @click.prevent="deletePage(page.id)" class="capitalize text-white bg-red-500 rounded px-2 py-1 text-xs font-medium">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="7" class="text-center py-6 text-sm text-gray-500">No records found</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4" v-if="pageCount > 1">
                    <paginate
                        v-model="currentPage"
                        :page-count="pageCount"
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
    </div>
</template>

<script>
    export default {
        props: ['url', 'mode'],
        data() {
            return {
                pages: [],
                errors: [],
                success: null,
                currentPage: 1,
                pageCount: 0,
                perPage: 5,
            };
        },

        methods: {
            getData(page = 1) {
                this.currentPage = page;
                axios.get(this.url + '/' + this.mode + '/page/list?page=' + page).then(response => {
                    this.pages = response.data.data;
                    this.pageCount = response.data.meta.last_page;
                    this.perPage = response.data.meta.per_page;
                });
            },

            stripHtml(html) {
                if (!html) return '';
                var tmp = document.createElement('div');
                tmp.innerHTML = html;
                var text = tmp.textContent || tmp.innerText || '';
                return text.length > 80 ? text.substring(0, 80) + '...' : text;
            },

            deletePage(id) {
                var self = this;
                swal({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this Page?',
                    icon: 'warning',
                    buttons: ['Cancel', 'Delete'],
                    dangerMode: true,
                }).then(function (confirmed) {
                    if (confirmed) {
                        axios.delete(self.url + '/' + self.mode + '/page/delete/' + id).then(response => {
                            self.success = response.data.success;
                            self.getData(self.currentPage);
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
