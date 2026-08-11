<template>
    <div class="relative">
        <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>
        <div v-if="errors.length > 0" class="alert alert-danger bg-red-100 text-red-600 text-sm p-2 rounded mb-4">
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
                    <input v-model="form.name" @input="autoSlug" type="text" maxlength="100"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Slug</label>
                    <input v-model="form.slug" @input="onSlugInput" type="text" maxlength="100"
                        class="w-full border rounded px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and hyphens only. Leave blank to auto-generate.</p>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <textarea v-model="form.description" rows="3" maxlength="500"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                        placeholder="Short description (optional)"></textarea>
                </div>
            </div>
        </div>

        <!-- Settings -->
        <div class="bg-white shadow rounded mb-5">
            <div class="border-b px-5 py-3">
                <span class="font-semibold text-sm text-gray-700 uppercase tracking-wide">Settings</span>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Sort Order</label>
                    <input v-model.number="form.sort_order" type="number" min="0"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first in menus.</p>
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
            <a :href="url + '/' + mode + '/page-categories'"
                class="bg-gray-100 text-gray-700 px-6 py-2 rounded text-sm font-semibold hover:bg-gray-200">
                Cancel
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'mode', 'id'],
        data() {
            return {
                form: {
                    name: '',
                    slug: '',
                    description: '',
                    sort_order: 0,
                    status: '1',
                },
                slugManuallyEdited: true,
                errors: [],
                success: null,
            };
        },

        methods: {
            autoSlug() {
                if (!this.slugManuallyEdited) {
                    this.form.slug = this.form.name
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/\s+/g, '-');
                }
            },

            onSlugInput() {
                this.slugManuallyEdited = true;
            },

            save() {
                this.errors = [];
                this.success = null;
                axios.post(this.url + '/' + this.mode + '/pageCategory/edit/' + this.id, this.form).then(response => {
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
                axios.get(this.url + '/' + this.mode + '/pageCategory/editList/' + this.id).then(response => {
                    const d = response.data;
                    this.form.name        = d.name        || '';
                    this.form.slug        = d.slug        || '';
                    this.form.description = d.description || '';
                    this.form.sort_order  = d.sort_order  ?? 0;
                    this.form.status      = String(d.status ?? 1);
                    // Mark slug as manually edited since existing record has one
                    this.slugManuallyEdited = !!d.slug;
                });
            },
        },

        created() {
            this.loadData();
        },
    };
</script>
