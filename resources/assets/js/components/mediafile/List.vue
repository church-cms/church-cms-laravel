<template>
    <div class="relative">
        <portal to="add_media_file">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Media Files ( {{ Object.keys(this.mediafiles).length }} )</h1>
                    <div class="">
                        <div class="flex lg:justify-end md:justify-end items-center">
                            <div class="search relative w-48">
                                <input type="text" name="search" v-model="search" class="tw-form-control w-full relative" placeholder="Search">
                                <a href="#" @click="searchList()" class="no-underline text-white px-4 mx-1 py-1 absolute right-0 focus:outline-none">
                                    <img :src="url+'/uploads/icons/search.svg'" class="w-4 h-4 absolute right-0 mt-2 mx-1 top-0">
                                </a>
                            </div>
                            <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                                <a href="#" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4" @click="resetForm()">Reset</a>
                            </div>
                        </div>
                    </div>
                <div class="w-32 relative">
                    <a href="#" class="text-sm rounded px-2 py-1 flex items-center justify-between custom-green w-full" style="color: white;" @click="showeventlink()" id="show">
                        <span>Add Media File</span>
                        <img :src="url+'/uploads/icons/arrow-down.svg'" class="w-2 h-2">
                    </a>
                    <div class="border create_event absolute z-40 w-full bg-white shadow hidden" id="dropdown">
                        <ul class="list-reset text-xs text-gray-700 leading-loose py-1">
                            <li class="px-2">
                                <a :href="url+'/admin/mediafile/audio/create'">Audio</a>
                            </li>
                            <li class="px-2">
                                <a :href="url+'/admin/mediafile/video/create'">Video</a>
                            </li>
                            <li class="px-2">
                                <a :href="url+'/admin/mediafile/image/create'">Image</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2 lg:w-1/4 px-1 my-2" v-for="mediafile in mediafiles">
                <div class="w-full py-2">
                    <div class="shadow-md p-3">
                        <div class="flex justify-between">
                            <img class="card-img-top w-16 h-16" :src="url+'/uploads/video_logo.png'" v-if="mediafile.media_type == 'video'">
                            <img class="card-img-top w-16 h-16" :src="url+'/uploads/audio_logo.png'" v-if="mediafile.media_type == 'audio'">
                            <img class="card-img-top w-16 h-16 object-cover rounded" :src="mediafile.url" v-if="mediafile.media_type == 'image'">
                            <div class="w-11/12 flex justify-between">
                            <div class="px-3">
                                <p class="font-bold text-base text-gray-700 capitalize">{{ mediafile.name }}</p>
                                <p class="font-medium text-xs text-gray-600 capitalize flex items-center py-1">{{ mediafile.description }}</p>

                                <p class="font-medium text-xs text-gray-500 capitalize flex items-center py-1" v-if="mediafile.media_type != 'image'">
                                    <a :href="mediafile.url" target="_blank">Download File</a>
                                </p>
                            </div>
                            <div class="flex items-right">
                                <a href="#" @click="viewFile(mediafile.id)" title="View" class="left-auto" v-if="mediafile.media_type != 'image'">
                                    <svg class="w-4 h-4 m-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999; filter: brightness(0);" xml:space="preserve" width="512px" height="512px"><g><g> <g><path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201 C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418 C447.361,287.923,358.746,385.406,255.997,385.406z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g><g><g><path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275    s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516 s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g></g></svg>
                                </a>
                                <a href="#" @click="deleteFile(mediafile.id)" class="left-auto delete-video">
                                    <img :src="url+'/uploads/icons/cancel.svg'" class="w-3 h-3 m-2">
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="show == mediafile.id" class="modal modal-mask">
                    <div class="modal-wrapper px-4">
                        <div class="modal-container w-full max-w-md px-4 mx-auto">
                            <div class="modal-header flex justify-between items-center">
                                <h2>View File</h2>
                                <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/4">
                                        <label for="name" class="tw-form-label">Name</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/4">
                                        <label for="description" class="tw-form-label">Description</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.description }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/4">
                                        <label for="url" class="tw-form-label">File</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">
                                            <a :href="file.url" target="_blank">Download File</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="font-medium text-sm text-gray-600 capitalize flex items-center py-2" v-if="Object.keys(mediafiles).length == 0">No records found</p>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url'],
        data () {
            return {
                mediafiles:[],
                file:[],
                search:'',
                type:'audio',
                show:false,
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/mediafile/list/'+this.type+'?search='+this.search).then(response => {
                    this.mediafiles    = response.data.data;
                    //console.log(this.mediafiles);
                });
            },

            showeventlink()
            {
                if($('#dropdown').hasClass('hidden'))
                {
                    $('#dropdown').removeClass('hidden').addClass('block');
                }
                else
                {
                    $('#dropdown').removeClass('block').addClass('hidden');
                }
            },

            searchList()
            {
                this.getData();
            },

            resetForm()
            {
                this.search = '';
                this.getData();
            },

            viewFile(id)
            {
                this.show = id;
                axios.get('/admin/mediafile/show/'+id).then(response => {
                    this.file = response.data;
                });
            },

            closeModal()
            {
                this.show = false;
            },

            deleteFile(id)
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this media file ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm)
                    {
                        axios.get(thisswal.url+ '/admin/mediafile/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        });
                    }
                    else
                    {
                        swal("Cancelled");
                    }
                });
            }
        },

        created()
        {
            this.getData();
            bus.$on("typeTab", data => {
                if(data!='')
                {
                    this.type=data;
                    this.getData();
                }
            });
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

    .modal-container-new {
        margin: 0px auto;
        /*padding: 20px 30px;*/
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        height: 500px;
        overflow:auto;
    }

    .modal-container {
        margin: 0px auto;
        /*padding: 20px 30px;*/
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        /*height: 500px;*/
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
