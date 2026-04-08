<template>
    <div class="my-3">
        <div v-if="success != null" class="alert alert-success" id="success-alert">{{ success }}</div>

        <!-- ── Section: Basic Info ───────────────────────────────────────── -->
        <div class="bg-white shadow rounded-lg mb-5">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Basic Information</h3>
            </div>
            <div class="px-5 py-4 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">

                <!-- Page Name -->
                <div>
                    <label class="tw-form-label">Page Name <span class="text-red-500">*</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="page_name"
                           placeholder="Page Name" maxlength="255" @input="autoSlug">
                    <span v-if="errors.page_name" class="text-red-500 text-xs">{{ errors.page_name[0] }}</span>
                </div>

                <!-- Category -->
                <div>
                    <label class="tw-form-label">Category <span class="text-red-500">*</span></label>
                    <select v-model="category" class="tw-form-control w-full mt-1">
                        <option value="" disabled>Select Category</option>
                        <option v-for="item in categorylist" :key="item.id" :value="item.id">{{ item.display_name }}</option>
                    </select>
                    <span v-if="errors.category" class="text-red-500 text-xs">{{ errors.category[0] }}</span>
                </div>

                <!-- Slug -->
                <div class="lg:col-span-2">
                    <label class="tw-form-label">Slug</label>
                    <div class="flex items-center mt-1">
                        <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs">/page/</span>
                        <input type="text" class="tw-form-control flex-1 rounded-l-none" v-model="slug"
                               placeholder="auto-generated-from-title" maxlength="255" @input="onSlugInput">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and hyphens only. Leave blank to auto-generate.</p>
                    <span v-if="errors.slug" class="text-red-500 text-xs">{{ errors.slug[0] }}</span>
                </div>

                <!-- Cover Image -->
                <div class="lg:col-span-2">
                    <label class="tw-form-label">Cover Image</label>
                    <div class="flex items-center mt-1 gap-4">
                        <img v-if="cover_image_display" :src="cover_image_display" class="w-20 h-20 object-cover rounded border border-gray-200">
                        <div v-else class="w-20 h-20 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-gray-300 text-2xl">&#9741;</div>
                        <label class="cursor-pointer text-sm text-indigo-600 hover:underline">
                            Change Cover Image
                            <input type="file" class="hidden" name="cover_image" accept="image/*" @change="OnFileSelected">
                        </label>
                    </div>
                    <span v-if="errors.cover_image" class="text-red-500 text-xs">{{ errors.cover_image[0] }}</span>
                </div>

            </div>
        </div>

        <!-- ── Section: Description ──────────────────────────────────────── -->
        <div class="bg-white shadow rounded-lg mb-5">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Description</h3>
            </div>
            <div class="px-5 py-4">
                <input type="hidden" v-if="description != null" name="description" :value="description">
                <trix-editor v-model="description" name="description" input="x"></trix-editor>
                <span v-if="errors.description" class="text-red-500 text-xs">{{ errors.description[0] }}</span>
            </div>
        </div>

        <!-- ── Section: Navigation ───────────────────────────────────────── -->
        <div class="bg-white shadow rounded-lg mb-5">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Navigation</h3>
            </div>
            <div class="px-5 py-4 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <label class="tw-form-label">Menu Text</label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="menu_text"
                           placeholder="Label shown in site nav" maxlength="80">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to use Page Name.</p>
                </div>

                <div>
                    <label class="tw-form-label">Menu Order</label>
                    <input type="number" class="tw-form-control w-full mt-1" v-model="menu_order"
                           min="0" placeholder="0">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                </div>

            </div>
        </div>

        <!-- ── Section: SEO / Meta Tags ─────────────────────────────────── -->
        <div class="bg-white shadow rounded-lg mb-5">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">SEO &amp; Meta Tags</h3>
            </div>
            <div class="px-5 py-4 grid grid-cols-1 gap-y-4">

                <div>
                    <label class="tw-form-label">Meta Title <span class="text-gray-400 font-normal text-xs">(max 60 chars)</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="meta_title"
                           placeholder="Page title for search engines" maxlength="60">
                    <div class="text-right text-xs text-gray-400 mt-1">{{ (60 - (meta_title ? meta_title.length : 0)) }} remaining</div>
                </div>

                <div>
                    <label class="tw-form-label">Meta Description <span class="text-gray-400 font-normal text-xs">(max 160 chars)</span></label>
                    <textarea class="tw-form-control w-full mt-1" v-model="meta_description" rows="2"
                              placeholder="Short description for search results" maxlength="160"></textarea>
                    <div class="text-right text-xs text-gray-400 mt-1">{{ (160 - (meta_description ? meta_description.length : 0)) }} remaining</div>
                </div>

                <div>
                    <label class="tw-form-label">Meta Keywords</label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="meta_keywords"
                           placeholder="keyword1, keyword2, keyword3" maxlength="255">
                </div>

                <div>
                    <label class="tw-form-label">OG Image URL <span class="text-gray-400 font-normal text-xs">(Open Graph share image)</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="og_image"
                           placeholder="https://...">
                </div>

            </div>
        </div>

        <!-- ── Actions ──────────────────────────────────────────────────── -->
        <div class="flex gap-3 mb-8">
            <a href="#" class="btn btn-primary submit-btn" @click.prevent="submitForm()">Save Changes</a>
            <a href="#" class="btn btn-reset reset-btn" @click.prevent="resetForm()">Reset</a>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['url', 'id', 'mode'],

        data() {
            return {
                page: [],
                page_name: '',
                description: '',
                category: '',
                slug: '',
                cover_image: '',
                cover_image_display: '',
                slugManuallyEdited: false,
                menu_text: '',
                menu_order: 0,
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                og_image: '',
                categorylist: [],
                errors: [],
                success: null,
            };
        },

        methods: {
            getData() {
                axios.get(this.url + '/' + this.mode + '/page/editList/' + this.id).then(response => {
                    this.page = response.data;
                    this.setData();
                });

                axios.get(this.url + '/' + this.mode + '/pageCategory/list').then(response => {
                    this.categorylist = response.data.data;
                });
            },

            setData() {
                if (Object.keys(this.page).length > 0) {
                    this.page_name         = this.page.page_name;
                    this.description       = this.page.description;
                    this.category          = this.page.category;
                    this.cover_image_display = this.page.cover_image;
                    this.menu_text         = this.page.menu_text || '';
                    this.menu_order        = this.page.menu_order || 0;
                    this.meta_title        = this.page.meta_title || '';
                    this.meta_description  = this.page.meta_description || '';
                    this.meta_keywords     = this.page.meta_keywords || '';
                    this.slug              = this.page.slug || '';
                    this.slugManuallyEdited = !!this.page.slug;

                    var element = document.querySelector('trix-editor');
                    if (element && this.page.description) {
                        element.editor.insertHTML(this.page.description);
                    }
                }
            },

            submitForm() {
                this.errors = [];
                this.success = null;

                var element = document.querySelector('trix-editor');
                this.description = element ? element.innerHTML : this.description;

                let formData = new FormData();
                formData.append('page_name',        this.page_name);
                formData.append('category',         this.category);
                formData.append('slug',             this.slug);
                formData.append('description',      this.description);
                formData.append('cover_image',      this.cover_image);
                formData.append('menu_text',        this.menu_text);
                formData.append('menu_order',       this.menu_order);
                formData.append('meta_title',       this.meta_title);
                formData.append('meta_description', this.meta_description);
                formData.append('meta_keywords',    this.meta_keywords);
                formData.append('og_image',         this.og_image);

                axios.post(this.url + '/' + this.mode + '/page/edit/' + this.id, formData)
                    .then(response => {
                        this.success = response.data.success;
                        window.scrollTo(0, 0);
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        window.scrollTo(0, 0);
                    });
            },

            resetForm() {
                this.setData();
            },

            autoSlug() {
                // Only auto-generate if user hasn't manually edited the slug
                if (!this.slugManuallyEdited) {
                    this.slug = this.page_name
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }
            },

            onSlugInput() {
                this.slugManuallyEdited = true;
            },

            OnFileSelected(event) {
                this.cover_image = event.target.files[0];
                this.cover_image_display = URL.createObjectURL(event.target.files[0]);
            },
        },

        created() {
            this.getData();
        },
    };
</script>
