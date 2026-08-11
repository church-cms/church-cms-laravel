<template>
    <div class="my-3">
        <div v-if="success != null" class="alert alert-success" id="success-alert">{{ success }}</div>
        <div v-if="errors && !Array.isArray(errors) && Object.keys(errors).length" class="alert alert-danger">
            <ul class="mb-0">
                <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
            </ul>
        </div>

        <!-- Add Category Modal -->
        <div v-if="show === 'add'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Add Category</h2>
                        <button class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label class="tw-form-label">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" class="tw-form-control w-full mt-1" v-model="newCategoryName" placeholder="Name">
                        <span v-if="errors.name" class="text-red-500 text-xs">{{ errors.name[0] }}</span>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click.prevent="addCategory()">Submit</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Tabs ──────────────────────────────────────────────────────── -->
        <div class="bg-white shadow rounded-lg mb-5">
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap -mb-px px-4 pt-2" aria-label="Tabs">
                    <button v-for="tab in tabs" :key="tab.key" type="button"
                        class="mr-1 px-4 py-2 text-sm font-medium border-b-2 focus:outline-none transition-colors"
                        :class="activeTab === tab.key
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        @click="activeTab = tab.key">
                        {{ tab.label }}
                    </button>
                </nav>
            </div>

            <!-- Tab: Page ────────────────────────────────────────────────── -->
            <div v-show="activeTab === 'page'" class="px-5 py-4 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">

                <div>
                    <label class="tw-form-label">Page Name <span class="text-red-500">*</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="page_name"
                           placeholder="Page Name" maxlength="255" @input="autoSlug">
                    <span v-if="errors.page_name" class="text-red-500 text-xs">{{ errors.page_name[0] }}</span>
                </div>

                <div>
                    <label class="tw-form-label">Category <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-2 mt-1">
                        <select v-model="category" class="tw-form-control flex-1">
                            <option value="" disabled>Select Category</option>
                            <option v-for="item in categorylist" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                        <a href="#" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded whitespace-no-wrap hover:bg-indigo-700" @click.prevent="showCategory()">+ Add</a>
                    </div>
                    <span v-if="errors.category" class="text-red-500 text-xs">{{ errors.category[0] }}</span>
                </div>

                <div class="lg:col-span-2">
                    <label class="tw-form-label">Slug</label>
                    <div class="flex items-center mt-1">
                        <span class="inline-flex items-center px-3 rounded-l border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-xs">/page/</span>
                        <input type="text" class="tw-form-control flex-1 rounded-l-none" v-model="slug"
                               placeholder="auto-generated-from-title" maxlength="255" @input="slugManuallyEdited = true">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers and hyphens only. Leave blank to auto-generate.</p>
                    <span v-if="errors.slug" class="text-red-500 text-xs">{{ errors.slug[0] }}</span>
                </div>

                <div>
                    <label class="tw-form-label">Layout Template</label>
                    <select v-model="layout_template" class="tw-form-control w-full mt-1">
                        <option value="left-sidebar">Left Sidebar</option>
                        <option value="right-sidebar">Right Sidebar</option>
                        <option value="no-sidebar">No Sidebar (Full Width)</option>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="tw-form-label">Cover Image</label>
                    <div class="flex items-center mt-1 gap-4">
                        <img v-if="cover_image_preview" :src="cover_image_preview" class="w-20 h-20 object-cover rounded border border-gray-200">
                        <input type="file" class="tw-form-control" name="cover_image" accept="image/*" @change="OnFileSelected">
                    </div>
                    <span v-if="errors.cover_image" class="text-red-500 text-xs">{{ errors.cover_image[0] }}</span>
                </div>

                <div class="lg:col-span-2">
                    <label class="tw-form-label">Description <span class="text-gray-400 font-normal text-xs">(AI summary — stored in page meta)</span></label>
                    <textarea class="tw-form-control w-full mt-1" v-model="description" rows="3"
                              placeholder="Short summary of this page for AI and search engines"></textarea>
                    <span v-if="errors.description" class="text-red-500 text-xs">{{ errors.description[0] }}</span>
                </div>

            </div>

            <!-- Tab: Content ────────────────────────────────────────────── -->
            <div v-show="activeTab === 'content'" class="px-5 py-4">
                <tiptap-editor v-model="contentDoc" @update:html="onContentHtml"></tiptap-editor>
                <span v-if="errors.content" class="text-red-500 text-xs">{{ errors.content[0] }}</span>

                <div class="mt-4">
                    <button type="button" class="text-xs text-indigo-600 hover:underline focus:outline-none"
                        @click="showCss = !showCss">
                        {{ showCss ? '▲ Hide' : '▼ Show' }} Custom CSS
                    </button>
                    <div v-if="showCss" class="mt-2">
                        <label class="tw-form-label text-xs">Custom CSS <span class="text-gray-400 font-normal">(scoped to .page-content)</span></label>
                        <textarea class="tw-form-control w-full mt-1 font-mono text-xs" v-model="contentCss"
                                  rows="6" placeholder=".page-content h1 { color: #333; }"></textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Navigation ─────────────────────────────────────────── -->
            <div v-show="activeTab === 'navigation'" class="px-5 py-4 grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <label class="tw-form-label">Menu Text</label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="menu_text"
                           placeholder="Label shown in site nav" maxlength="80">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to use Page Name.</p>
                </div>
                <div>
                    <label class="tw-form-label">Menu Order</label>
                    <input type="number" class="tw-form-control w-full mt-1" v-model="menu_order" min="0" placeholder="0">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers appear first.</p>
                </div>
            </div>

            <!-- Tab: SEO ────────────────────────────────────────────────── -->
            <div v-show="activeTab === 'seo'" class="px-5 py-4 grid grid-cols-1 gap-y-4">
                <div>
                    <label class="tw-form-label">Meta Title <span class="text-gray-400 font-normal text-xs">(max 60 chars)</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="meta_title"
                           placeholder="Page title for search engines" maxlength="60">
                    <div class="text-right text-xs text-gray-400 mt-1">{{ 60 - (meta_title ? meta_title.length : 0) }} remaining</div>
                </div>
                <div>
                    <label class="tw-form-label">Meta Description <span class="text-gray-400 font-normal text-xs">(max 160 chars)</span></label>
                    <textarea class="tw-form-control w-full mt-1" v-model="meta_description" rows="2"
                              placeholder="Short description for search results" maxlength="160"></textarea>
                    <div class="text-right text-xs text-gray-400 mt-1">{{ 160 - (meta_description ? meta_description.length : 0) }} remaining</div>
                </div>
                <div>
                    <label class="tw-form-label">Meta Keywords</label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="meta_keywords"
                           placeholder="keyword1, keyword2, keyword3" maxlength="255">
                </div>
                <div>
                    <label class="tw-form-label">OG Image URL <span class="text-gray-400 font-normal text-xs">(Open Graph share image)</span></label>
                    <input type="text" class="tw-form-control w-full mt-1" v-model="og_image" placeholder="https://...">
                </div>
            </div>

        </div><!-- end card -->

        <div class="flex gap-3 mb-8">
            <a href="#" class="btn btn-primary submit-btn" @click.prevent="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset reset-btn" @click.prevent="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'mode'],
        data() {
            return {
                activeTab: 'page',
                tabs: [
                    { key: 'page',       label: 'Page' },
                    { key: 'content',    label: 'Content' },
                    { key: 'navigation', label: 'Navigation' },
                    { key: 'seo',        label: 'SEO' },
                ],
                page_name: '',
                description: '',
                category: '',
                slug: '',
                slugManuallyEdited: false,
                layout_template: 'left-sidebar',
                cover_image: '',
                cover_image_preview: '',
                contentDoc: null,
                contentHtml: '',
                contentCss: '',
                showCss: false,
                menu_text: '',
                menu_order: 0,
                meta_title: '',
                meta_description: '',
                meta_keywords: '',
                og_image: '',
                newCategoryName: '',
                show: '',
                categorylist: [],
                errors: [],
                success: null,
            };
        },

        methods: {
            getData() {
                axios.get(this.url + '/' + this.mode + '/pageCategory/list').then(response => {
                    this.categorylist = response.data.data;
                });
            },

            onContentHtml(html) {
                this.contentHtml = html;
            },

            autoSlug() {
                if (!this.slugManuallyEdited) {
                    this.slug = this.page_name
                        .toLowerCase()
                        .trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                }
            },

            resetForm() {
                this.page_name        = '';
                this.category         = '';
                this.description      = '';
                this.slug             = '';
                this.slugManuallyEdited = false;
                this.layout_template  = 'left-sidebar';
                this.cover_image      = '';
                this.cover_image_preview = '';
                this.contentDoc       = null;
                this.contentHtml      = '';
                this.contentCss       = '';
                this.menu_text        = '';
                this.menu_order       = 0;
                this.meta_title       = '';
                this.meta_description = '';
                this.meta_keywords    = '';
                this.og_image         = '';
                this.errors           = [];
                this.activeTab        = 'page';
            },

            showCategory() { this.show = 'add'; },
            closeModal()   { this.show = ''; this.newCategoryName = ''; },

            addCategory() {
                this.errors = [];
                axios.post(this.url + '/' + this.mode + '/pageCategory/add', { name: this.newCategoryName })
                    .then(() => {
                        this.closeModal();
                        this.getData();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
            },

            submitForm() {
                this.errors = [];
                this.success = null;

                let formData = new FormData();
                formData.append('page_name',        this.page_name);
                formData.append('category',         this.category);
                formData.append('slug',             this.slug);
                formData.append('description',      this.description);
                formData.append('cover_image',      this.cover_image);
                formData.append('layout_template',  this.layout_template);
                formData.append('content', JSON.stringify({
                    doc:           this.contentDoc,
                    rendered_html: this.contentHtml,
                    css:           this.contentCss,
                }));
                formData.append('menu_text',        this.menu_text);
                formData.append('menu_order',       this.menu_order);
                formData.append('meta_title',       this.meta_title);
                formData.append('meta_description', this.meta_description);
                formData.append('meta_keywords',    this.meta_keywords);
                formData.append('og_image',         this.og_image);

                axios.post(this.url + '/' + this.mode + '/page/add', formData)
                    .then(response => {
                        this.success = response.data.success;
                        this.resetForm();
                        window.scrollTo(0, 0);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                        window.scrollTo(0, 0);
                    });
            },

            OnFileSelected(event) {
                this.cover_image = event.target.files[0];
                this.cover_image_preview = URL.createObjectURL(event.target.files[0]);
            },
        },

        created() {
            this.getData();
        },
    };
</script>
