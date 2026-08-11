<template>
	<div>
        <portal to="add_quote">
            <div class="flex lg:items-center md:items-center justify-between flex-col lg:flex-row md:flex-row">
                <h1 class="admin-h1">Quotes</h1>
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
                        <div class="relative flex items-center w-1/2 justify-end">
                            <a :href="url+'/admin/quote/add'" id="upload-btn" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1 justify-center rounded">
                                <span class="mx-1 text-sm font-semibold">Add</span>
                                <img :src="url+'/uploads/icons/plus.svg'" class="w-3 h-3">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </portal>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
    	<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 flex flex-wrap" v-bind:class="[this.tab=='published'?'block' :'hidden' || this.tab=='upcoming'?'block' :'hidden']">
            <div class="w-full lg:w-1/4 md:w-1/4 px-2 my-5" v-for="list in lists">
                <div class="shadow-md" > <!-- style="height: 216px;" -->
                    <div class="flex flex-wrap p-2">
                        <div class="leading-loose my-1 w-2/3">
                            <img class="card-img-top w-32 h-32 lg:h-40 lg:w-5/6 md:w-5/6 md:h-40" :src="list.image" v-if="list.image != null">
                            <p class="text-xs text-gray-700 flex flex-col" v-if="list.tamil != null || list.english != null">
                                <span class="mx-2" v-html="trim(list.tamil)">{{ list.tamil }}</span>
                                <span class="mx-2" v-html="trim(list.english)">{{ list.english }}</span>
                            </p>
		                    <p class="text-xs text-gray-700 flex flex-col" v-if="list.text != null">
		                        <span class="mx-2" v-html="trim(list.text)">{{ list.text }}</span>
		                    </p>
		                </div>
                        <div class="w-1/3">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <a :href="url+'/admin/quote/edit/'+list.id" id="edit_permission" class="left-auto" title="Edit" v-if="tab == 'upcoming'">
                                        <img :src="url+'/uploads/icons/edit.svg'" class="w-3 h-3 m-2">
                                    </a>
                                    <a href="#" @click="reschedule(list.id)" class="left-auto" title="Reschedule" v-if="tab == 'published'">
                                        <img :src="url+'/uploads/icons/reschedule.svg'" class="w-3 h-3 m-2">
                                    </a>
                                    <a href="#" @click="viewQuote(list.id)" title="View" class="left-auto">
                                        <svg class="w-4 h-4 m-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999; filter: brightness(0);" xml:space="preserve" width="512px" height="512px"><g><g> <g><path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201 C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418    C447.361,287.923,358.746,385.406,255.997,385.406z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g><g><g><path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275    s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516 s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g></g></svg>
                                    </a>
                                    <a href="#" @click="deleteQuote(list.id)" title="Delete" class="left-auto delete-member">
                                        <img :src="url+'/uploads/icons/delete.svg'" class="w-3 h-3 m-2" style="filter: brightness(0);">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
	                <div class="leading-loose my-1 mx-2">
                        <div class="relative flex items-center w-full">
                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/></g></g></g></svg>
	                        <p class="text-xs font-semibold text-gray-800 capitalize mx-2 py-2" v-if="tab == 'published'">Published On {{ list.publish_on }}</p>
	                        <p class="text-xs font-semibold text-gray-800 capitalize mx-2 py-2" v-else>Publish On {{ list.publish_on }}</p>
                        </div>
	                </div>
                </div>
                <div v-if="show == list.id" class="modal modal-mask">
                    <div class="modal-wrapper px-4">
                        <div class="modal-container-new w-full max-w-md px-4 mx-auto">
                            <div class="modal-header flex justify-between items-center">
                                <h2>Reschedule Quote</h2>
                                <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-col lg:flex-row">
                                    <div class="w-full lg:w-1/4">
                                        <label for="reschedule_date" class="tw-form-label">Reschedule On<span class="text-red-500">*</span></label>
                                    </div>
                                    <div class="w-full lg:w-3/4">
                                        <datetime format="DD-MM-YYYY h:i:s" name="reschedule_date" v-model="reschedule_date" class="rounded w-full" id="reschedule_date"></datetime>
                                        <span v-if="errors.reschedule_date" class="text-red-500 text-xs font-semibold">{{ errors.reschedule_date[0] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="my-6">
                                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm(list.id)">Submit</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="show == list.id+'_view'" class="modal modal-mask">
                    <div class="modal-wrapper px-4">
                        <div class="modal-container w-full max-w-md px-4 mx-auto">
                            <div class="modal-header flex justify-between items-center">
                                <h2>View Quote</h2>
                                <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-col justify-between items-center">
                                    <div class="leading-loose my-1" v-if="quote.image != null">
                                        <img class="" :src="quote.image" v-if="quote.image != null">
                                    </div>
                                    <div class="leading-loose my-1" v-if="quote.tamil != null || quote.english != null">
                                        <p class="text-xs text-gray-700 flex flex-col">
                                            <span class="mx-2" v-html="quote.tamil">{{ quote.tamil }}</span>
                                            <span class="mx-2" v-html="quote.english">{{ quote.english }}</span>
                                        </p>
                                    </div>
                                    <div class="leading-loose my-1" v-if="quote.text != null">
                                        <p class="text-xs text-gray-700 flex flex-col">
                                            <span class="mx-2" v-html="quote.text">{{ quote.text }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="Object.keys(lists).length == 0">
                <p class="text-xs text-gray-700 flex flex-col">No Records Found</p>
            </div>
    	</div>
	</div>
</template>

<script>
    import { bus } from "../../app";
    import datetime from 'vuejs-datetimepicker';
	export default{
		props:['url'],
        components: {
            datetime,
        },
		data(){
			return{
				tab:'published',
				status:'',
               	lists:[],
                quote:[],
                reschedule_date:'',
                search:'',
                show:false,
				success:null,
				errors:[],
			}
		},

		methods:
		{
            getData(type)
            {
                axios.get('/admin/quote/list/'+type+'?search='+this.search).then(response => {
                    this.lists = response.data.data;
                });
            },

            searchList()
            {
                this.getData(this.tab);
            },

            resetForm()
            {
                this.search = '';
                this.getData(this.tab);
            },

		    trim(string) 
		    {
                var quote = jQuery.parseHTML(string)[0]['innerText'];
		      	return quote.substring(0,56) + '...';
		    },

            reschedule(id)
            {
                this.show = id;
            },

            viewQuote(id)
            {
                this.show = id+'_view';
                axios.get('/admin/quote/edit/list/'+id).then(response => {
                    this.quote = response.data;
                });
            },

            closeModal()
            {
                this.show = false;
            },

     deleteQuote(id) 
      {
    var thisswal = this;
    swal({
        title: 'Are you sure',
        text: 'Do you want to delete this Quote ?',
        icon: "info",
        buttons: ['No', 'Yes'],
        dangerMode: true,
    }).then(function(isConfirm) {
        if (isConfirm) 
        {
            axios.delete(thisswal.url + '/admin/quote/delete/' + id, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                thisswal.success = response.data.success;
                window.location.reload();
            }); 
        }
        else 
        {
            swal("Cancelled");
        }
    });
  },

            submitForm(id)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('reschedule_date',this.reschedule_date);
              
                axios.post('/admin/quote/reschedule/'+id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.closeModal();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
		},

		created()
		{
            this.getData(this.tab);
			bus.$emit("dataTab", this.tab);
       
            bus.$on("dataTab", data => {
                if(data!='')
                {
                    this.tab=data;
                	this.getData(data);                   
                }
            });
		},
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