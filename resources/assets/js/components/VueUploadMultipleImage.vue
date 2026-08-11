<template>
  <div style="outline: none;">
    <div class="image-container position-relative text-center" v-if="!images.length">
      <div class="drag-upload-cover position-absolute" v-if="isDragover" @drop="onDrop">
        <div class="centered full-width text-center text-primary">
          <svg class="icon-drag-drop" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M444.5 15C407.7 15 378 44.8 378 81.5s29.8 66.5 66.5 66.5S511 118.2 511 81.5 481.2 15 444.5 15zm29.4 72.4h-23.5l.1 25.9c0 3.2-2.6 5.8-5.8 5.9-3.2 0-5.8-2.6-5.8-5.8l-.1-26h-23.6c-3.2 0-5.8-2.6-5.8-5.8s2.6-5.8 5.8-5.8h23.5l-.1-25.9c0-3.2 2.6-5.8 5.8-5.9 3.2 0 5.8 2.6 5.8 5.8l.1 26h23.6c3.3 0 5.8 2.6 5.8 5.8s-2.6 5.8-5.8 5.8zM199.3 191.3c21.5 0 38.9 17.6 38.9 39.3s-17.4 39.3-38.9 39.3-38.9-17.6-38.9-39.3c0-21.7 17.5-39.3 38.9-39.3zm185.4 201.3H86.3c-6.5 0-11.9-5.3-11.9-11.9v-32.4c0-2.5.7-4.8 2.1-6.9l41.3-58.4c3.7-5.2 10.8-6.5 16.1-3.1l56.4 36.8c4.5 3 10.3 2.5 14.4-1L313 220.1c5.1-4.5 13.1-3.8 17.2 1.7l61.5 79.7c1.6 2 2.5 4.6 2.5 7.2v74.4c0 5.2-4.3 9.5-9.5 9.5zm7.9 117.6h-58.8v-12h58.8v12zm-78.4 0h-58.8v-12h58.8v12zm-78.5 0h-58.8v-12h58.8v12zm-78.4 0H98.4v-12h58.8v12h.1zm-78.5 0H57.7c-14.3 0-27.9-5.4-38.3-15.3l8.3-8.7c8.2 7.8 18.8 12 30.1 12h21.1l-.1 12zm333.6-.1l-.3-12c17.8-.4 33.4-11.5 39.8-28.2l11.2 4.3c-8.1 21.3-28 35.4-50.7 35.9zM6.8 477c-3.2-7.1-4.7-14.7-4.7-22.5v-38.2h12v38.2c0 6.1 1.3 12.1 3.7 17.6l-11 4.9zm459.9-24.1h-12v-58.8h12v58.8zM14.1 396.7h-12v-58.8h12v58.8zm452.6-22.3h-12v-58.8h12v58.8zM14.1 318.3h-12v-58.8h12v58.8zM466.7 296h-12v-58.8h12V296zM14.1 239.8h-12V181h12v58.8zm452.6-22.2h-12v-58.8h12v58.8zM14.1 161.4h-12v-58.8h12v58.8zm2.4-76.1L5.3 81.2C13 59.9 33.4 45.5 56.1 45.5h.2v12h-.2c-17.7 0-33.6 11.2-39.6 27.8zm353.6-27.8h-58.8v-12h58.8v12zm-78.5 0h-58.8v-12h58.8v12zm-78.4 0h-58.8v-12h58.8v12zm-78.5 0H75.9v-12h58.8v12z"></path></svg>
          <h4 class="drop-text-here"><b>{{dropText}}</b></h4>
        </div>
      </div>
      <div v-else class="image-center position-absolute display-flex flex-column justify-content-center align-items-center" @dragover.prevent="onDragover">
        <div>
       <svg class="image-icon-drag fill-current" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 486.3 486.3" style="enable-background:new 0 0 486.3 486.3;" xml:space="preserve" width="512px" height="512px"><g><g>
  <g>
    <path d="M395.5,135.8c-5.2-30.9-20.5-59.1-43.9-80.5c-26-23.8-59.8-36.9-95-36.9c-27.2,0-53.7,7.8-76.4,22.5    c-18.9,12.2-34.6,28.7-45.7,48.1c-4.8-0.9-9.8-1.4-14.8-1.4c-42.5,0-77.1,34.6-77.1,77.1c0,5.5,0.6,10.8,1.6,16    C16.7,200.7,0,232.9,0,267.2c0,27.7,10.3,54.6,29.1,75.9c19.3,21.8,44.8,34.7,72,36.2c0.3,0,0.5,0,0.8,0h86    c7.5,0,13.5-6,13.5-13.5s-6-13.5-13.5-13.5h-85.6C61.4,349.8,27,310.9,27,267.1c0-28.3,15.2-54.7,39.7-69    c5.7-3.3,8.1-10.2,5.9-16.4c-2-5.4-3-11.1-3-17.2c0-27.6,22.5-50.1,50.1-50.1c5.9,0,11.7,1,17.1,3c6.6,2.4,13.9-0.6,16.9-6.9    c18.7-39.7,59.1-65.3,103-65.3c59,0,107.7,44.2,113.3,102.8c0.6,6.1,5.2,11,11.2,12c44.5,7.6,78.1,48.7,78.1,95.6    c0,49.7-39.1,92.9-87.3,96.6h-73.7c-7.5,0-13.5,6-13.5,13.5s6,13.5,13.5,13.5h74.2c0.3,0,0.6,0,1,0c30.5-2.2,59-16.2,80.2-39.6    c21.1-23.2,32.6-53,32.6-84C486.2,199.5,447.9,149.6,395.5,135.8z" data-original="#000000" class="active-path" fill="#c9c8c8"/>
    <path d="M324.2,280c5.3-5.3,5.3-13.8,0-19.1l-71.5-71.5c-2.5-2.5-6-4-9.5-4s-7,1.4-9.5,4l-71.5,71.5c-5.3,5.3-5.3,13.8,0,19.1    c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4l48.5-48.5v222.9c0,7.5,6,13.5,13.5,13.5s13.5-6,13.5-13.5V231.5l48.5,48.5    C310.4,285.3,318.9,285.3,324.2,280z" data-original="#000000" class="active-path" fill="#c9c8c8"/>
  </g>
</g></g> </svg>
        </div>
        <div class="text-center py-2">
          <label class="drag-text font-semibold">Drag images here</label>
          <br/>
          <label class="text-gray-700 text-sm">or</label>
          <br/>
          <a class="browse-text">Select Files</a>
        </div>
        <div class="image-input position-absolute full-width full-height">
          <label :for="idUpload" id="select" class="full-width full-height cursor-pointer">
          </label>
        </div>
         <span v-if="errors.idUpload" class="text-danger text-xs my-1">{{errors.idUpload[0]}}</span>
      </div>
    </div>

    <div class="image-container position-relative text-center image-list" v-else>
      <div class="preview-image full-width position-relative cursor-pointer"  @click="openGallery(currentIndexImage)">
        <div class="image-overlay position-relative full-width full-height" ></div>
        <div class="image-overlay-details full-width">
          <svg class="icon-overlay mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M283.9 186.4h-64.6l-.4-71.1c-.1-8.8-7.2-15.9-16-15.9h-.1c-8.8.1-16 7.3-15.9 16.1l.4 70.9h-64.4c-8.8 0-16 7.2-16 16s7.2 16 16 16h64.6l.4 71.1c.1 8.8 7.2 15.9 16 15.9h.1c8.8-.1 16-7.3 15.9-16.1l-.4-70.9h64.4c8.8 0 16-7.2 16-16s-7.1-16-16-16z"></path><path d="M511.3 465.3L371.2 325.2c-1-1-2.6-1-3.6 0l-11.5 11.5c31.6-35.9 50.8-82.9 50.8-134.3C406.9 90.3 315.6-1 203.4-1 91.3-1 0 90.3 0 202.4s91.3 203.4 203.4 203.4c51.4 0 98.5-19.2 134.3-50.8l-11.5 11.5c-1 1-1 2.6 0 3.6l140.1 140.1c1 1 2.6 1 3.6 0l41.4-41.4c.9-.9.9-2.5 0-3.5zm-307.9-92.5C109.5 372.8 33 296.4 33 202.4S109.5 32.1 203.4 32.1s170.4 76.4 170.4 170.4-76.4 170.3-170.4 170.3z"></path></svg>
        </div>
        <div class="show-image centered">
          <img class="show-img img-responsive" id="image" :src="imagePreview">
        </div>
      </div>
      <div class="image-bottom display-flex position-absolute full-width align-items-center justify-content-between" :class="!showPrimary && 'justify-content-end'">
        <div class="image-bottom-left display-flex align-items-center" v-if="showPrimary">
          <div class="display-flex align-items-center" v-show="imageDefault">
            <span class="image-primary display-flex align-items-center">
              <svg class="image-icon-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle fill="#10BC83" cx="256" cy="256" r="256"></circle><path fill="#FFF" d="M216.7 350.9h-.1c-5.1 0-9.9-2.1-13.4-5.7l-74.2-76c-7.4-7.5-7.2-19.5.4-26.8 7.5-7.4 19.5-7.2 26.8.4L217 305l139.7-138.5c7.5-7.4 19.5-7.4 26.8.1s7.4 19.5-.1 26.8l-153.2 152c-3.7 3.5-8.5 5.5-13.5 5.5z"></path></svg>
              {{primaryText}}
            </span>
            <popper trigger="click" :options="{placement: 'top'}">
              <div class="popper popper-custom">
                {{popupText}}
              </div>
              <i slot="reference" class="cursor-pointer display-flex align-items-center">
                <svg class="image-icon-info" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 32c30.3 0 59.6 5.9 87.2 17.6 26.7 11.3 50.6 27.4 71.2 48s36.7 44.5 48 71.2c11.7 27.6 17.6 56.9 17.6 87.2s-5.9 59.6-17.6 87.2c-11.3 26.7-27.4 50.6-48 71.2s-44.5 36.7-71.2 48C315.6 474.1 286.3 480 256 480s-59.6-5.9-87.2-17.6c-26.7-11.3-50.6-27.4-71.2-48s-36.7-44.5-48-71.2C37.9 315.6 32 286.3 32 256s5.9-59.6 17.6-87.2c11.3-26.7 27.4-50.6 48-71.2s44.5-36.7 71.2-48C196.4 37.9 225.7 32 256 32m0-32C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0z"></path><path d="M304.2 352H296c-4.4 0-8-3.6-8-8v-94.8c0-15.3-11.5-28.1-26.7-29.8-2.5-.3-4.8-.5-6.7-.5-23.7 0-44.6 11.9-57 30.1l-.1.1v-.1c-1 2-1.7 5.3.7 6.5.6.3 1.2.5 1.8.5h16c4.4 0 8 3.6 8 8v80c0 4.4-3.6 8-8 8h-8.2c-8.7 0-15.8 7.1-15.8 15.8v.3c0 8.7 7.1 15.8 15.8 15.8h96.4c8.7 0 15.8-7.1 15.8-15.8v-.3c0-8.7-7.1-15.8-15.8-15.8zM256 128c-17.7 0-32 14.3-32 32s14.3 32 32 32 32-14.3 32-32-14.3-32-32-32z"></path></svg>
              </i>
            </popper>
          </div>
          <a class="text-small mark-text-primary cursor-pointer" @click.prevent="markIsPrimary(currentIndexImage)" v-show="!imageDefault">{{markIsPrimaryText}}</a>
        </div>
        <div class="display-flex">
          <label class="image-edit display-flex cursor-pointer" :for="idEdit">
            <svg class="image-icon-edit" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M469.56 42.433C420.927-6.199 382.331-.168 378.087.68l-4.8.96L36.895 338.001 0 512l173.985-36.894 336.431-336.399.941-4.86c.826-4.257 6.65-42.984-41.797-91.414zM41.944 470.057L64.3 364.617c12.448 3.347 31.968 11.255 50.51 29.794 18.96 18.963 27.84 39.986 31.875 53.436l-104.741 22.21zm132.504-41.134c-6.167-16.597-17.199-37.794-36.775-57.371C119 352.88 99.435 342.57 83.739 336.879l155.156-155.15 97.066-97.051c11.069 2.074 34.864 8.95 57.253 31.338 22.708 22.708 30.95 48.358 33.734 60.428l-96.685 96.663-155.815 155.816zm278.41-278.383c-6.167-16.6-17.196-37.8-36.781-57.384-18.669-18.667-38.228-28.977-53.92-34.668l26.118-26.113c8.785.484 30.373 4.87 58.423 32.918l.001.002c28.085 28.074 32.467 49.675 32.946 58.463l-26.787 26.782z"></path></svg>
          </label>

          <a class="image-delete display-flex cursor-pointer" @click.prevent="deleteImage(currentIndexImage)">
            <svg class="image-icon-delete" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M448 64h-96V0H159.9l.066 64H32v32h32v416h384V96h32V64h-32zM192 32h128v32H192V32zm224 448H96V96h320v384zM192 160h32v256h-32V160zm96 0h32v256h-32V160z"></path></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="image-list-container display-flex flex-wrap" v-if="images.length && multiple">
      <div class="image-list-item position-relative cursor-pointer" :class="image.highlight && 'image-highlight'" v-for="(image, index) in images" :key="index" @click="changeHighlight(index)">
        <div class="centered">
          <img class="show-img img-responsive" :src="image.path">
        </div>
      </div>
      <div class="image-list-item position-relative cursor-pointer display-flex justify-content-center align-items-center" v-if="images.length < maxImage">
        <svg class="icon add-image-svg" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M511.5 227.5h-227V.5h-57v227H-.5v57h228v228h57v-228h227z"></path></svg>
        <div class="input-add-image position-absolute full-width full-height">
          <label :for="idUpload" class="display-block full-width full-height cursor-pointer">
          </label>
        </div>
      </div>
    </div>
    <div>
      <input class="display-none" :id="idUpload" @change="uploadFieldChange" name="image-upload" :multiple="multiple" :accept="accept" type="file">
      <input class="display-none" :id="idEdit" @change="editFieldChange" name="image" :accept="accept" type="file">
    </div>

    <vue-image-lightbox-carousel
      ref="lightbox"
      :show="showLightbox"
      @close="showLightbox = false"
      :images="images"
      @change="changeHighlight"
      :showCaption="false"
      >
    </vue-image-lightbox-carousel>
  </div>
</template>

<script>
import { forEach, findIndex, orderBy, cloneDeep } from 'lodash'
import Popper from 'vue-popperjs'
import { bus } from "../app";
//import 'vue-popperjs/dist/css/vue-popper.css'

import VueImageLightboxCarousel from 'vue-image-lightbox-carousel'
export default {
 
  name: 'VueUploadMultipleImage',

  props: {
    dragText: {
      type: String,
      default: 'drag photos(many)'
    },
    browseText: {
      type: String,
      default: '(or) Select'
    },
    primaryText: {
      type: String,
      default: 'Default'
    },
    markIsPrimaryText: {
      type: String,
      default: 'Set as default'
    },
    popupText: {
      type: String,
      default: 'This image will be displayed as default'
    },
    dropText: {
      type: String,
      default: 'Drop your file here ...'
    },
    accept: {
      type: String,
      default: 'image/jpeg,image/png,image/bmp,image/jpg'
    },
    dataImages: {
      type: Array,
      default: () => {
        return []
      }
    },
    multiple: {
      type: Boolean,
      default: true
    },
    showPrimary: {
      type: Boolean,
      default: true
    },
    maxImage: {
      type: Number,
      default: 5
    },
    idUpload: {
      type: String,
      default: 'image-upload'
    },
    idEdit: {
      type: String,
      default: 'image-edit'
    }
  },
  data () {
    return {
      currentIndexImage: 0,
      images: [],
      isDragover: false,
      showLightbox: false,
      arrLightBox: [],
      errors:[],
      count:null

    }
  },
  components: {
    Popper,
    VueImageLightboxCarousel
  },
  computed: {
    imagePreview () {
      let index = findIndex(this.images, { highlight: 1 })
      if (index > -1) {
        return this.images[index].path
      } else {
        return this.images.length ? this.images[0].path : ''
      }
    },
    imageDefault () {
      if (this.images[this.currentIndexImage]) {
        return this.images[this.currentIndexImage].default
      }
    }
  },
  methods: {
    onDrop (e) {
      this.isDragover = false
      e.stopPropagation()
      e.preventDefault()
      let files = e.dataTransfer.files
      if (!files.length) {
        return false
      }
      if(!this.isValidNumberOfImages(files.length)){
        return false
      }
      forEach(files, (value, index) => {
        this.createImage(value)
        if (!this.multiple) {
          return false
        }
      })
      if (document.getElementById(this.idUpload)) {
        document.getElementById(this.idUpload).value = []
      }
    },
    onDragover () {
      this.isDragover = true
    },
    createImage (file) {
      let reader = new FileReader()
      let formData = new FormData()
      formData.append('file', file)
      reader.onload = (e) => {
        let dataURI = e.target.result
        if (dataURI) {
          if (!this.images.length) {
            this.images.push({name: file.name, path: dataURI, highlight: 1, default: 1})
            this.currentIndexImage = 0
          } else {
            this.images.push({name: file.name, path: dataURI, highlight: 0, default: 0})
          }
          this.$emit('upload-success', formData, this.images.length - 1, this.images)
          bus.$emit('limit-exceeded',count);
        }
      }
      reader.readAsDataURL(file)
    },
    editImage (file) {
      let reader = new FileReader()
      let formData = new FormData()
      formData.append('file', file)
      reader.onload = (e) => {
        let dataURI = e.target.result
        if (dataURI) {
          if (this.images.length && this.images[this.currentIndexImage]) {
            this.images[this.currentIndexImage].path = dataURI
            this.images[this.currentIndexImage].name = file.name
          }
        }
      }
      reader.readAsDataURL(file)
      this.$emit('edit-image', formData, this.currentIndexImage, this.images)
    },
    uploadFieldChange (e) {
      let files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        return false
      }
      if(!this.isValidNumberOfImages(files.length)){
        return false
      }
      forEach(files, (value, index) => {
        this.createImage(value)
      })
      if (document.getElementById(this.idUpload)) {
        document.getElementById(this.idUpload).value = []
      }
    },
    editFieldChange (e) {
      let files = e.target.files || e.dataTransfer.files
      if (!files.length) {
        return false
      }
      if(!this.isValidNumberOfImages(files.length)){
        return false
      }
      forEach(files, (value, index) => {
        this.editImage(value)
      })
      if (document.getElementById(this.idEdit)) {
        document.getElementById(this.idEdit).value = ''
      }
    },
    changeHighlight (currentIndex) {
      this.currentIndexImage = currentIndex
      let arr = this.images
      this.images = []
      arr.map((item, index) => {
        if (currentIndex === index) {
          item.highlight = 1
        } else {
          item.highlight = 0
        }
        return item
      })
      this.images = arr
    },
    markIsPrimary (currentIndex) {
      this.images.map((item, index) => {
        if (currentIndex === index) {
          item.highlight = 1
          item.default = 1
        } else {
          item.highlight = 0
          item.default = 0
        }
        return item
      })
      this.currentIndexImage = 0
      this.images = orderBy(this.images, 'default', 'desc')
      this.$emit('mark-is-primary', currentIndex, this.images)
    },
    deleteImage (currentIndex) {
      this.$emit('before-remove', currentIndex, () => {
        if (this.images[currentIndex].default === 1) {
          this.images[0].default = 1
        }
        this.images.splice(currentIndex, 1)
        this.currentIndexImage = 0
        if (this.images.length) {
          this.images[0].highlight = 1
        }
      }, this.images)
    },
    openGallery(index) {
      this.showLightbox = true
      this.$refs.lightbox.showImage(index)
    },
    onOpenedLightBox(value) {
      if (value) {
        this.showLightbox = true
      } else {
        this.showLightbox = false
      }
    },
    isValidNumberOfImages(amount) {
      if (amount > this.maxImage) {
        bus.$emit('limit-exceeded', amount)
        
        return false
      } else {
        return true
      }
    }
  },
  watch: {
    dataImages: {
      handler: function (newVal) {
        this.images = newVal
      },
      deep: true
    }
  },
  mounted () {
    ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach((event) => {
      window.addEventListener(event, (e) => {
        e.preventDefault()
        e.stopPropagation()
      })
    })
    document.body.addEventListener('dragleave', (event) => {
      event.stopPropagation()
      event.preventDefault()
      this.isDragover = false
    })
  },
  created () {
    this.images = []
    this.images = cloneDeep(this.dataImages)
  }
}
</script>

<style lang="css" scoped>
.text-small {
  font-size: 11px;
}
.position-relative {
  position: relative;
}
.position-absolute {
  position: absolute;
}
.text-center {
  text-align: center;
}
.text-primary {
  color: #2fa3e6;
}
.display-flex {
  display: flex;
}
.flex-column {
  flex-direction: column;
}
.flex-wrap {
  flex-wrap: wrap;
}
.justify-content-center {
  justify-content: center;
}
.justify-content-between {
  justify-content: space-between;
}
.justify-content-end {
  justify-content: flex-end;
}
.align-items-center {
  align-items: center;
}
.full-width {
  width: 100%;
}
.full-height {
  height: 100%;
}
.cursor-pointer {
  cursor: pointer;
}
.centered {
  left: 50%;
  transform: translate(-50%,-50%);
  top: 50%;
  position: absolute;
  display: block;
}
.image-container {
  /*width: 190px;*/
  height: 180px;
  border: 1px dashed #D6D6D6;
  border-radius: 4px;
  background-color: #fff;
}
.image-center {
  width: 100%;
  height: 100%;
}
.image-icon-drag {
  fill: #c9c8c8;
  height: 50px;
  width: 50px;
}
.drag-text {
  padding-top: 5px;
  color: #2d3748;
  font-weight: 500;
  line-height: 1.5;
  font-size: 14px;
}
.browse-text {
  font-size: 86%;
  color: #206ec5;
  text-decoration: none;
}
.image-input {
  overflow: hidden;
  opacity: 0;
  top: 0;
  left: 0;
  bottom: 0;
}
.image-input label {
  display: block;
}
.drag-upload-cover {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #FCFEFF;
  opacity: .9;
  z-index: 1;
  margin: 5px;
  border: 2px dashed #268DDD;
}
.drag-upload-cover {
  font-weight: 400;
  font-size: 20px;
}
.icon-drag-drop {
  height: 50px;
  width: 50px;
  fill: #2fa3e6;
}
.drop-text-here {
  margin: 0;
  line-height: 1.5;
}
.display-none {
  display: none;
}

/* list images*/
.image-list {
  border: 1px solid #D6D6D6;
}
.preview-image {
  height: 140px;
  padding: 5px;
  border-radius: 15px;
  box-sizing: border-box;
}
.image-overlay {
  background: rgba(0,0,0,.7);
  z-index: 10;
  border-radius: 5px;
  opacity: 0;
  transition: all .3s ease-in-out 0s;
}
.image-overlay-details {
  position: absolute;
  z-index: 11;
  opacity: 0;
  transform: translate(0,-50%);
  top: 50%;
}
.icon-overlay {
  width: 40px;
  height: 40px;
  fill: #fff;
}
.preview-image:hover .image-overlay, .preview-image:hover .image-overlay-details{
  opacity: 1;
}
.img-responsive {
  display: block;
  max-width: 100%;
  height: auto;
}
.show-img {
  max-height: 100px;
  max-width: 140px;
  display: block;
  vertical-align: middle;
}
/*image bottom*/
.image-bottom {
  bottom: 0;
  left: 0;
  height: 40px;
  padding: 5px 10px;
  box-sizing: border-box;
}
.image-primary {
  border-radius: 4px;
  background-color: #e3edf7;
  padding: 3px 7px;
  font-size: 11px;
  margin-right: 5px;
}
.image-icon-primary {
  width: 10px;
  height: 10px;
  margin-right: 2px;
}
.image-icon-delete {
  height: 14px;
  width: 14px;
  fill: #222;
}
.image-edit {
  margin-right: 10px;
}
.image-icon-edit {
  height: 14px;
  width: 14px;
  fill: #222;
}
.image-list-container {
  max-width: 190px;
  min-height: 50px;
  margin-top: 10px;
}
.image-list-container .image-list-item {
  height: 32px;
  width: 32px;
  border-radius: 4px;
  border: 1px solid #D6D6D6;
}
.image-list-container .image-list-item:not(:last-child) {
  margin-right: 5px;
  margin-bottom: 5px;
}
.image-list-container .image-list-item .show-img {
  max-width: 25px;
  max-height: 17px;
}
.image-list-container .image-highlight {
  border: 1px solid #2fa3e7;
}
.add-image-svg {
  width: 12px;
  height: 12px;
  fill: #222;
}
.input-add-image {
  overflow: hidden;
  opacity: 0;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 11;
}
.input-add-image label {
  display: block;
}
.image-icon-info {
  width: 14px;
  height: 14px;
  fill: #222;
}
.mark-text-primary {
  color: #034694;
}
.popper-custom {
  background: #000;
  padding: 10px;
  border: none;
  box-shadow: none;
  color: white;
  text-align: left;
  font-size: 12px;
}
</style>
<style lang="css">
.popper-custom .popper__arrow {
  border-color: #000 transparent transparent transparent !important;
}
.swiper-container {
  display: flex !important;
  align-items: center;
}
.vue-lightbox-content .swiper-container .swiper-slide {
    padding: 20px 0;
}
.modal-mask {
  padding-top: 40px !important;
}
</style>
