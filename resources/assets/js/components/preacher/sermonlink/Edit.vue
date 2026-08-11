<template>
  <div>
    <div v-if="success" class="alert alert-success">{{ success }}</div>

    <!-- Hidden trigger button (called from blade via editSeries(id)) -->
    <button class="hidden" @click="openModal()" id="edit-series-modal">Edit</button>

    <div v-if="showModal" class="modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-lg px-8 mx-auto">

          <div class="modal-header flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Edit Chapter</h2>
            <button class="text-2xl leading-none" @click="closeModal()">&times;</button>
          </div>

          <div class="modal-body">

            <!-- Title -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Chapter Title <span class="text-red-500">*</span></label>
              <div class="flex-1">
                <input type="text" v-model="title" class="tw-form-control w-full" placeholder="e.g. Part 1 — Introduction">
                <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title[0] }}</p>
              </div>
            </div>

            <!-- Date -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Publish Date <span class="text-red-500">*</span></label>
              <div class="flex-1">
                <input type="date" v-model="date" class="tw-form-control w-full">
                <p v-if="errors.date" class="text-red-500 text-xs mt-1">{{ errors.date[0] }}</p>
              </div>
            </div>

            <!-- Video Link -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Video URL</label>
              <div class="flex-1">
                <input type="url" v-model="video_link" class="tw-form-control w-full" placeholder="https://youtube.com/...">
                <p v-if="errors.video_link" class="text-red-500 text-xs mt-1">{{ errors.video_link[0] }}</p>
              </div>
            </div>

            <!-- Audio Link -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">Audio URL</label>
              <div class="flex-1">
                <input type="url" v-model="audio_link" class="tw-form-control w-full" placeholder="https://soundcloud.com/...">
                <p v-if="errors.audio_link" class="text-red-500 text-xs mt-1">{{ errors.audio_link[0] }}</p>
              </div>
            </div>

            <!-- PDF — show existing, allow re-upload -->
            <div class="my-3 flex items-start gap-3">
              <label class="tw-form-label w-28 pt-1 shrink-0">PDF / Doc</label>
              <div class="flex-1">
                <a v-if="pdf_link" :href="pdf_link" target="_blank"
                   class="text-xs text-blue-600 underline block mb-1">Current file (click to view)</a>
                <input type="file" @change="onFileSelected" accept=".pdf,.doc,.docx" class="tw-form-control w-full">
                <p class="text-gray-400 text-xs mt-1">Leave blank to keep current file</p>
                <p v-if="errors.pdf_link" class="text-red-500 text-xs mt-1">{{ errors.pdf_link[0] }}</p>
              </div>
            </div>

            <p v-if="mediaError" class="text-red-500 text-xs mt-1 mb-2">{{ mediaError }}</p>

            <div class="my-6">
              <button class="btn btn-primary blue-bg text-white rounded px-4 py-1 text-sm font-medium"
                      @click="updateSeries()">Update</button>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['base_url'],

  data() {
    return {
      id: null,
      title: '',
      date: '',
      video_link: '',
      audio_link: '',
      pdf_link: null,
      pdf_file: null,
      showModal: false,
      errors: [],
      mediaError: null,
      success: null,
    };
  },

  methods: {
    openModal() {
      this.id = $('#edit_sermon_id').val();
      this.loadData();
    },

    closeModal() {
      this.showModal = false;
      this.errors = [];
      this.mediaError = null;
    },

    loadData() {
      axios.get((this.base_url || '/preacher') + '/links/edit/' + this.id).then(response => {
        const d = response.data.data[0];
        this.title      = d.title      || '';
        this.date       = d.date       || '';
        this.video_link = d.video_link || '';
        this.audio_link = d.audio_link || '';
        this.pdf_link   = d.pdf_link   || null;
        this.showModal  = true;
      });
    },

    onFileSelected(event) {
      this.pdf_file = event.target.files[0] || null;
    },

    updateSeries() {
      this.errors     = [];
      this.mediaError = null;
      this.success    = null;

      if (!this.video_link && !this.audio_link && !this.pdf_file && !this.pdf_link) {
        this.mediaError = 'Please provide at least one of Video URL, Audio URL, or PDF / Doc.';
        return;
      }

      let formData = new FormData();
      formData.append('title', this.title);
      formData.append('date',  this.date);
      if (this.video_link) formData.append('video_link', this.video_link);
      if (this.audio_link) formData.append('audio_link', this.audio_link);
      if (this.pdf_file)   formData.append('pdf_link',   this.pdf_file);

      axios.post(
        (this.base_url || '/preacher') + '/links/update/' + this.id,
        formData
      ).then(response => {
        this.success = response.data.success;
        this.closeModal();
        window.location.reload();
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    },
  },
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}
.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
  overflow: auto;
}
.modal-container {
  margin: 0 auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  height: auto;
  overflow: auto;
}
</style>
