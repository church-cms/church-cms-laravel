<template>
    <div class="relative">
        <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>
         <div v-if="errors.length > 0" class="alert alert-danger bg-red-100 text-red-600 text-sm p-2 rounded mb-4">
            <ul class="mb-0">
                <li v-for="e in errors" :key="e">{{ e }}</li>
            </ul>
        </div>

        <!-- Add Category Panel -->
        <div class="mb-4">
            <button @click="showAddForm = !showAddForm"
                class="text-white px-4 py-1 text-sm font-semibold rounded custom-green flex items-center">
                <span class="mr-1 text-lg leading-none">+</span> Add Category
            </button>
        </div>

        <div v-if="showAddForm" class="bg-white border rounded shadow-sm p-5 mb-6">
            <h3 class="font-semibold text-gray-700 mb-4">New Page Category</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" @input="autoSlug" type="text" maxlength="100"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="e.g. Services" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Slug</label>
                    <input v-model="form.slug" @input="slugManuallyEdited = true" type="text" maxlength="100"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="auto-generated" />
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and hyphens only.</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <textarea v-model="form.description" rows="2" maxlength="500"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="Short description (optional)"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Sort Order</label>
                    <input v-model="form.sort_order" type="number" min="0"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="0" />
                </div>
            </div>
            <div class="mt-4 flex items-center gap-3">
                <button @click="addCategory"
                    class="bg-indigo-600 text-white px-5 py-2 rounded text-sm font-semibold hover:bg-indigo-700">Save</button>
                <button @click="cancelAdd"
                    class="bg-gray-100 text-gray-700 px-5 py-2 rounded text-sm font-semibold hover:bg-gray-200">Cancel</button>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-wrap custom-table overflow-auto">
            <div class="flex flex-wrap">
                <table class="w-full">
                    <thead class="border-t-2 border-b-2">
                        <tr>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="5%">#</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="22%">Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="18%">Slug</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="30%">Description</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="8%">Order</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="7%">Status</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody v-if="categories.length > 0">
                        <tr class="border-b" v-for="(cat, index) in categories" :key="cat.id">
                            <td class="py-3 px-2 text-sm text-gray-600">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                            <td class="py-3 px-2 text-sm font-medium text-gray-800">{{ cat.name }}</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-mono">{{ cat.slug }}</td>
                            <td class="py-3 px-2 text-sm text-gray-500">{{ cat.description ? cat.description.substring(0, 80) + (cat.description.length > 80 ? '…' : '') : '—' }}</td>
                            <td class="py-3 px-2 text-sm text-gray-600 text-center">{{ cat.sort_order }}</td>
                            <td class="py-3 px-2 text-sm">
                                <span :class="cat.status == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                                    class="px-2 py-0.5 rounded text-xs font-medium">
                                    {{ cat.status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="py-3 px-2 whitespace-no-wrap">
                                <a :href="url + '/' + mode + '/pageCategory/edit/' + cat.id"
                                    class="capitalize text-white blue-bg rounded px-2 py-1 text-xs font-medium mr-1">Edit</a>
                                <a href="#" @click.prevent="deleteCategory(cat.id)"
                                    class="capitalize text-white bg-red-500 rounded px-2 py-1 text-xs font-medium">Delete</a>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="7" class="text-center py-6 text-sm text-gray-500">No categories found</td>
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
                categories: [],
                errors: [],
                success: null,
                currentPage: 1,
                pageCount: 0,
                perPage: 10,
                showAddForm: false,
                slugManuallyEdited: false,
                form: {
                    name: '',
                    slug: '',
                    description: '',
                    sort_order: 0,
                },
            };
        },

        methods: {
            getData(page = 1) {
                this.currentPage = page;
                axios.get(this.url + '/' + this.mode + '/pageCategory/list?page=' + page).then(response => {
                    this.categories = response.data.data;
                    this.pageCount = response.data.meta.last_page;
                    this.perPage = response.data.meta.per_page;
                });
            },

            autoSlug() {
                if (!this.slugManuallyEdited) {
                    this.form.slug = this.form.name
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-');
                }
            },

            cancelAdd() {
                this.showAddForm = false;
                this.form = { name: '', slug: '', description: '', sort_order: 0 };
                this.slugManuallyEdited = false;
                this.errors = [];
            },

            addCategory() {
                this.errors = [];
                this.success = null;
                axios.post(this.url + '/' + this.mode + '/pageCategory/add', this.form).then(response => {
                    if (response.data.success) {
                        this.success = response.data.success;
                        this.cancelAdd();
                        this.getData(this.currentPage);
                    }
                }).catch(err => {
                    if (err.response && err.response.data.errors) {
                        this.errors = Object.values(err.response.data.errors).flat();
                    }
                });
            },

            deleteCategory(id) {
                var self = this;
                swal({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this category? Pages in this category will be uncategorised.',
                    icon: 'warning',
                    buttons: ['Cancel', 'Delete'],
                    dangerMode: true,
                }).then(function (confirmed) {
                    if (confirmed) {
                        axios.delete(self.url + '/' + self.mode + '/pageCategory/delete/' + id).then(response => {
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
