<template>
    <div class="px-3 py-3 mx-2">
        <div v-if="success" class="alert alert-success" id="success-alert">{{ success }}</div>

        <div class="my-3">
            <div class="w-full lg:w-1/4">
                <label for="name" class="tw-form-label">Title<span class="text-red-500">*</span></label>
            </div>
            <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full" placeholder="Title">
                <span class="text-danger text-xs" v-if="errors.name">{{ errors.name[0] }}</span>
            </div>
        </div>

        <div class="my-3">
            <div class="w-full lg:w-1/4">
                <label class="tw-form-label">Description</label>
            </div>
            <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                <textarea v-model="description" class="tw-form-control w-full" rows="3" placeholder="Description"></textarea>
            </div>
        </div>

        <div class="my-3">
            <div class="w-full lg:w-1/4">
                <label class="tw-form-label">Image<span class="text-red-500">*</span></label>
            </div>
            <div class="w-full lg:w-2/5 md:w-2/5 my-2">
                <input type="file" ref="imageFile" accept="image/*" @change="onFileChange" class="tw-form-control w-full">
                <span class="text-danger text-xs" v-if="errors.image">{{ errors.image[0] }}</span>
            </div>
            <div class="my-2" v-if="preview">
                <img :src="preview" class="w-40 h-40 object-cover rounded border">
            </div>
        </div>

        <div class="mt-6 mb-4">
            <a href="#" class="blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-2 text-sm font-medium" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'csrf'],
        data() {
            return {
                name: '',
                description: '',
                imageFile: null,
                preview: null,
                errors: [],
                success: null,
            };
        },
        methods: {
            onFileChange(e) {
                const file = e.target.files[0];
                this.imageFile = file;
                this.preview = file ? URL.createObjectURL(file) : null;
            },

            submitForm() {
                this.errors = [];
                this.success = null;

                const formData = new FormData();
                formData.append('name', this.name);
                formData.append('description', this.description);
                formData.append('_token', this.csrf);
                if (this.imageFile) {
                    formData.append('image', this.imageFile);
                }

                axios.post(this.url + '/admin/mediafile/image/create', formData)
                    .then(response => {
                        this.success = 'Image uploaded successfully.';
                        this.resetForm();
                        setTimeout(() => { window.location.href = this.url + '/admin/mediafiles'; }, 1000);
                    }).catch(error => {
                        if (error.response && error.response.data.errors) {
                            this.errors = error.response.data.errors;
                        } else if (error.response && error.response.data.error) {
                            this.errors = { image: [error.response.data.error] };
                        }
                    });
            },

            resetForm() {
                this.name = '';
                this.description = '';
                this.imageFile = null;
                this.preview = null;
                if (this.$refs.imageFile) this.$refs.imageFile.value = '';
            },
        },
    };
</script>
