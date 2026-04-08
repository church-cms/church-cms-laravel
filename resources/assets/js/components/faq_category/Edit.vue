<template>
    <div class="relative">
        <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>
        <div v-if="errors.length > 0" class="alert alert-danger">
            <ul class="mb-0">
                <li v-for="e in errors" :key="e">{{ e }}</li>
            </ul>
        </div>

        <!-- Basic Information -->
        <div class="bg-white shadow rounded mb-5">
            <div class="border-b px-5 py-3">
                <span class="font-semibold text-sm text-gray-700 uppercase tracking-wide">Basic Information</span>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Category Name <span class="text-red-500">*</span></label>
                    <input v-model="form.name" type="text" maxlength="30"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select v-model="form.status"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="save"
                class="bg-indigo-600 text-white px-6 py-2 rounded text-sm font-semibold hover:bg-indigo-700">
                Save Changes
            </button>
            <a :href="url + '/admin/faq-categories'"
                class="bg-gray-100 text-gray-700 px-6 py-2 rounded text-sm font-semibold hover:bg-gray-200">
                Cancel
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'id'],
        data() {
            return {
                form: {
                    name: '',
                    status: '1',
                },
                errors: [],
                success: null,
            };
        },

        methods: {
            save() {
                this.errors = [];
                this.success = null;
                axios.post(this.url + '/admin/faqCategory/edit/' + this.id, this.form).then(response => {
                    if (response.data.success) {
                        this.success = response.data.success;
                        window.scrollTo(0, 0);
                    }
                }).catch(err => {
                    if (err.response && err.response.data.errors) {
                        this.errors = Object.values(err.response.data.errors).flat();
                    }
                    window.scrollTo(0, 0);
                });
            },

            loadData() {
                axios.get(this.url + '/admin/faqCategory/editList/' + this.id).then(response => {
                    this.form.name   = response.data.name   || '';
                    this.form.status = String(response.data.status ?? 1);
                });
            },
        },

        created() {
            this.loadData();
        },
    };
</script>
