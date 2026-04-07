<template>
    <div>
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="flex flex-row justify-between">
                <div class="">
                    <h1 class="admin-h1 my-3">Events ( {{ this.count }} )</h1>
                </div>

                <div class="flex items-center justify-end">
                    <div class="w-32 relative">
                        <a href="#" class="text-sm rounded px-2 py-1 flex items-center justify-between btn btn-primary submit-btn w-full" @click="showeventlink()" id="show">
                            <span>Create Event</span>
                            <img :src="this.url+'/uploads/icons/arrow-down.svg'" class="w-2 h-2">
                        </a>
                        <div class="border create_event absolute z-40 w-full bg-white" v-bind:class="[this.show_event_link==1?'block':'hidden']">
                            <ul class="list-reset text-xs text-gray-700 leading-loose py-1">
                                <li class="px-2">
                                    <a href="#" id="private" @click="createEvents('private')">Create Private Event</a>
                                </li>
                                <li class="px-2">
                                    <a href="#" id="public" @click="createEvents('public')">Create Public Event</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="this.showEvents">
                <div class="modal modal-mask">
                    <div class="modal-wrapper px-4">
                        <div class="modal-container w-full  max-w-md px-8 mx-auto">
                            <div class="modal-header flex justify-between items-center">
                                <h2>Add Event</h2>
                                <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                            </div>

                            <div class="modal-body">
                                <div>
                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4">
                                                <input type="text" v-model="title" placeholder="Add a short, clear name" class="tw-form-control w-full" id="title">
                                                <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{ errors.title[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="tw-form-label">Description<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4">
                                                <textarea type="textarea" v-model="description" placeholder="Tell people what your event is about" class="tw-form-control w-full" rows="3" id="description"></textarea>
                                                <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="tw-form-label">Repeats?<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4 flex">
                                                <div class="text-sm flex items-center">
                                                    <input type="radio" name="no" v-model="repeats" value="0" id="repeats">
                                                    <span class="mx-1">No</span>
                                                </div>
                                                <div class="text-sm flex items-center mx-4">
                                                    <input type="radio" name="yes" v-model="repeats" value="1" id="repeat">
                                                    <span class="mx-1">Yes</span>
                                                </div>
                                                <span v-if="errors.repeats" class="text-red-500 text-xs font-semibold">{{ errors.repeats[0] }}</span>
                                            </div>
                                        </div>

                                        <div id="freq" v-bind:class="[this.repeats=='1' ?'block': 'hidden']">
                                            <div class="input-group flex my-3">
                                                <div class="w-1/4">
                                                    <label class="tw-form-label">Every:&nbsp;<span class="text-red-500">*</span></label>
                                                </div>
                                                <div class="w-3/4 flex">
                                                    <div class="w-2/5">
                                                        <input type="text" name="freq" v-model="freq" value="1" class="freq-a tw-form-control w-full">
                                                        <span v-if="errors.freq_term" class="text-red-500 text-xs font-semibold">{{ errors.freq[0] }}</span>
                                                    </div>
                                                    <div class="w-3/5 ml-3">
                                                        <select v-model="freq_term" id="freq_term" class="freq-b tw-form-control w-full">
                                                            <option v-for="list in termlist" v-bind:value="list.id">{{ list.name }}</option>
                                                        </select>
                                                        <span v-if="errors.freq_term" class="text-red-500 text-xs font-semibold">{{ errors.freq_term[0] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label for="location" class="tw-form-label">Location<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4">
                                                <input type="text" v-model="location" placeholder="Include a place or address" class="tw-form-control w-full" id="location">
                                                <span v-if="errors.location" class="text-red-500 text-xs font-semibold">{{ errors.location[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="tw-form-label">Event Category<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4">
                                                <select v-model="category" class="repeats tw-form-control w-full" id="category">
                                                    <option v-for="list in categorylist" v-bind:value="list.id">{{ list.name }}</option>
                                                </select>
                                                <span v-if="errors.category" class="text-red-500 text-xs font-semibold">{{ errors.category[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label for="organised_by" class="tw-form-label">Organised By<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4">
                                                <input type="text" v-model="organised_by" class="tw-form-control w-full" id="organised_by">
                                                <span v-if="errors.organised_by" class="text-red-500 text-xs font-semibold">{{ errors.organised_by[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="input-group-addon tw-form-label">Start Date<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4 text-sm">
                                                <datetime format="DD-MM-YYYY h:i:s" v-model="start_date" name="start_date" class="rounded w-full" id="start_date"></datetime>
                                                <span v-if="errors.start_date" class="text-red-500 text-xs font-semibold">{{ errors.start_date[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="input-group-addon tw-form-label">End Date<span class="text-red-500">*</span></label>
                                            </div>
                                            <div class="w-3/4 text-sm">
                                                <datetime format="DD-MM-YYYY h:i:s" v-model="end_date" name="end_date" class="w-full rounded" id="end_date"></datetime>
                                                <span v-if="errors.end_date" class="text-red-500 text-xs font-semibold">{{ errors.end_date[0] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <div class="flex">
                                            <div class="w-1/4">
                                                <label class="tw-form-label">Cover Image</label>
                                            </div>
                                            <div class="w-3/4">
                                                <div v-if="cover_image_url" class="mb-2">
                                                    <img :src="cover_image_url" class="w-full h-24 object-cover rounded border">
                                                </div>
                                                <a href="#" @click.prevent="showImagePicker=true" class="text-xs text-indigo-600 underline">
                                                    {{ cover_image_url ? 'Change Image' : 'Pick from Media Library' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-3">
                                        <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
                                        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>
                                        <input type="submit" class="hidden" id="submit-btn">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Picker Modal -->
            <div v-if="showImagePicker" class="modal modal-mask" style="z-index:10000;">
                <div class="modal-wrapper px-4">
                    <div class="modal-container w-full max-w-2xl px-6 mx-auto">
                        <div class="modal-header flex justify-between items-center">
                            <h2 class="text-base font-semibold">Pick a Cover Image</h2>
                            <button class="modal-default-button text-2xl py-1" @click="showImagePicker=false">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p v-if="mediaImages.length === 0" class="text-sm text-gray-500 py-4">
                                No images in media library.
                                <a :href="url+'/admin/mediafile/image/create'" target="_blank" class="text-indigo-600 underline">Upload images here</a>.
                            </p>
                            <div class="grid grid-cols-3 gap-3 py-2 max-h-80 overflow-y-auto">
                                <div v-for="img in mediaImages" :key="img.id"
                                    class="cursor-pointer border-2 rounded overflow-hidden"
                                    :class="cover_image_id == img.id ? 'border-indigo-500' : 'border-transparent'"
                                    @click="selectImage(img)">
                                    <img :src="img.url" class="w-full h-24 object-cover">
                                    <p class="text-xs text-gray-600 px-1 py-1 truncate">{{ img.name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex justify-end py-2">
                            <a href="#" class="btn btn-primary submit-btn text-sm px-4 py-1" @click.prevent="showImagePicker=false">Done</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="parseInt(this.count) > parseInt(this.no_of_events)">
            <a href="/pricing">
                <button type="submit" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">Upgrade Plan to Add More Events</button>
            </a>
        </div>
    </div>
</template>

<script>
    import datetime from 'vuejs-datetimepicker';
    export default {
        props:['url','count','no_of_events'],
        components: { datetime },
        data() {
            return {
                event:[],
                select_type:'',
                title:'',
                description:'',
                repeats:'0',
                freq:'',
                freq_term:'',
                location:'',
                category:'',
                organised_by:'',
                start_date:'',
                end_date:'',
                cover_image_id:'',
                cover_image_url:'',
                cover_image_path:'',
                showImagePicker: false,
                mediaImages: [],
                showEvents:0,
                errors:[],
                success:null,
                show_event_link:0,
                termlist:[{id : 'day' , name : 'Day'} , {id : 'week' , name : 'Week'} , {id : 'month' , name : 'Month'} , {id : 'year' , name : 'Year'}],
                categorylist:[{id : 'Culturals' , name : 'Culturals'} , {id : 'Education' , name : 'Education'} , {id : 'Meeting' , name : 'Meeting'} , {id : 'prayer' , name : 'Prayer'} , {id : 'sermon' , name : 'Sermon'} ],
            }
        },

        methods:
        {
            resetForm()
            {
                this.title='';
                this.description='';
                this.repeats ='';
                this.freq ='';
                this.freq_term = '';
                this.location = '';
                this.category = '';
                this.organised_by ='';
                this.cover_image_id = '';
                this.cover_image_url = '';
                var start_date = document.getElementsByName('start_date');
                var end_date = document.getElementsByName('end_date');
                start_date[0]['value'] = '';
                end_date[0]['value'] = '';
            },

            createEvents(selecttype)
            {
                this.select_type=selecttype;
                this.showEvents=1;
                this.showeventlink();
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData();

                formData.append('select_type',this.select_type);
                formData.append('title',this.title);
                formData.append('description',this.description);
                formData.append('repeats',this.repeats);
                formData.append('freq',this.freq);
                formData.append('freq_term',this.freq_term);
                formData.append('location',this.location);
                formData.append('category',this.category);
                formData.append('organised_by',this.organised_by);
                formData.append('start_date',this.start_date);
                formData.append('end_date',this.end_date);
                formData.append('cover_image_id',this.cover_image_id);
                formData.append('cover_image_path',this.cover_image_path);

                axios.post('/admin/events/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.success=response.data.success;
                    this.closeModal();
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            closeModal()
            {
                this.showEvents=0;
            },

            showeventlink()
            {
                this.show_event_link=!this.show_event_link;
            },

            selectImage(img)
            {
                this.cover_image_id  = img.id;
                this.cover_image_url = img.url;
                this.cover_image_path = img.path;
            },

            loadMediaImages()
            {
                axios.get(this.url + '/admin/mediafile/images').then(response => {
                    this.mediaImages = response.data.data;
                });
            },
        },

        created()
        {
            this.loadMediaImages();
        }
    }
</script>

<style scoped>

    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
        overflow:auto;
    }

    .modal-container {
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        height: 550px;
        overflow:auto;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
    */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>
