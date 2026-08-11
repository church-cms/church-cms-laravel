<template>
<div>

  <!-- Success Message -->
  <div v-if="success" class="alert alert-success">
    {{ success }}
  </div>

  <!-- Error Message -->
  <div v-if="errors.length" class="alert alert-danger" style="color:red;">
    <ul>
      <li v-for="(error, index) in errors" :key="index">
        {{ error }}
      </li>
    </ul>
  </div>

  <!-- Gallery -->
  <div v-bind:class="[isphotos==1?'block':'hidden']">
    <GallerySlider :url="url" :gallery_id="gallery_id"></GallerySlider>
  </div>

  <!-- Upload -->
  <VueUploadMultipleImage
    @upload-success="uploadImageSuccess"
    @before-remove="beforeRemove"
    @edit-image="editImage"
    @data-change="dataChange"
    @limit-exceeded="limitExceeded"
  />

  <!-- Submit -->
  <div class="my-2">
    <input type="submit"
           class="btn btn-primary submit-btn cursor-pointer w-full"
           value="Submit"
           @click="saveImage">
  </div>

</div>
</template>

<script>
import { bus } from "../../app";
import GallerySlider from './GallerySlider'
import VueUploadMultipleImage from '../VueUploadMultipleImage'

export default {
  props: ['url','name','gallery_id'],

  data() {
    return {
      isphotos: 0,
      image: [],
      gallery: [],
      success: null,
      errors: [],
      isLoading: false
    }
  },

  components: {
    GallerySlider,
    VueUploadMultipleImage
  },

  methods: {

    // ✅ VALIDATION HERE
   uploadImageSuccess(formData, index, fileList) {

  this.errors = [];

  let allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

  fileList.forEach(file => {

    let fileName = file.name || '';
    let ext = fileName.split('.').pop().toLowerCase();

    // ❌ Extension validation
    if (!allowedExtensions.includes(ext)) {
      this.errors.push('Only JPG, JPEG, PNG, WEBP images are allowed');
    }

    // ❌ Size validation
    if (file.size > 2 * 1024 * 1024) {
      this.errors.push('Image size must be less than 2MB');
    }

  });

  if (this.errors.length === 0) {
    this.image = fileList;
  } else {
    this.image = [];
  }
},

    // ✅ SAVE IMAGE
    saveImage() {

      this.success = null;

      if (this.errors.length > 0) {
        return;
      }

      if (this.image.length === 0) {
        this.errors = ['Please upload at least one valid image'];
        return;
      }

      this.isLoading = true;

      axios.post('/admin/gallery/upload/photos/' + this.gallery_id, {
        data: this.image
      })
      .then(response => {

        this.success = response.data.message;
        this.isLoading = false;

        bus.$emit("photoadded","photouploaded");

      })
      .catch(error => {

        this.errors = ['Upload failed'];
        this.isLoading = false;

      });
    },

    beforeRemove(index, done) {
      if (confirm("Remove image?")) {
        done();
      }
    },

    editImage(formData, index, fileList) {
      console.log('edit', formData);
    },

    dataChange(data) {
      console.log(data);
    },

    limitExceeded() {
      this.errors = ['Upload limit exceeded'];
    }

  },

  created() {

    bus.$on("galleryCount", data => {
      if (data > 0) {
        this.isphotos = 1;
      }
    });

  }
}
</script>