<template>
	<div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-wrap lg:flex-row justify-between items-center">
        <div class="">
          <h1 class="admin-h1 my-3">Notifications</h1>
        </div>
        <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
          <div class="flex items-center w-full justify-end">
            <div class="flex items-center">
              <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="read('all')">
                <span class="mx-1 text-sm font-semibold">Mark All As Read</span>
              </a> 
            </div>
          </div>
        </div>
      </div>
		<div class="w-full" v-for="notification in unreadNotifications">
	        <div class="bg-white shadow leading-loose px-3 overflow-y-auto border-b">
	         	<ul class="flex flex-wrap items-center justify-between">
	         		<li class="message-bubble-text-right rounded-full inline-block px-2 py-1 my-1 mr-4 font-semibold"> {{ notification.web_message }}</li> 
	         		<li class="flex flex-wrap">
	         			<div> 
	         				<a href="#" class="btn btn-reset bg-red-700 text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="read(notification.id)" v-if="mode == 'admin'">Mark As Read</a> 
	         				<a href="#" class="btn btn-reset bg-purple-700 text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="read(notification.id)" v-else-if="mode == 'teacher'">Mark As Read</a>
	         				<a href="#" class="btn btn-reset student-sidebar text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="read(notification.id)" v-else-if="mode == 'student'">Mark As Read</a>
	         			</div>
	         		</li>
	         	</ul>
	        </div>
	    </div>
		<div class="w-full" v-for="notification in readNotifications">
	        <div class="bg-white shadow leading-loose px-3 overflow-y-auto border-b">
	         	<ul class="flex flex-col py-2">
	         		<li class="message-bubble-text-right rounded-full inline-block px-2 mr-4"> {{ notification.web_message }}</li> 
	         		<li class="flex flex-wrap items-center">
	         			<div class="py-2 px-2">
	         				<svg class="w-3 h-3 fill-current text-blue-600" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill=""/><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill=""/></g></g></g></svg>
	         			</div>
	         			<div>
	         				<a href="#" title="Read At" class="text-blue-600 text-xs">{{ notification.read_at }}</a>
	         			</div>
	         		</li>
	         	</ul>
	        </div>
	    </div>
	</div>	
</template>

<script>
	export default{
		props:[ 'url' , 'mode' ],
		data(){
			return{
				readNotifications:[],
				unreadNotifications:[],
				success:null,
				errors:[],
			}
		},

		methods:
		{
			getData()
			{
				axios.get(this.url+'/'+this.mode+'/notification/list').then( response => {
					this.readNotifications = response.data.read_list;
					this.unreadNotifications = response.data.unread_list;
					//console.log(response.data)
				});
			},

			read(id)
			{
				this.success = null;
				this.errors = [];

				let formData = new FormData();

				formData.append('notification_id',id);

				axios.post(this.url+'/'+this.mode+'/notification/read',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then( response => {    
        			this.success = response.data.success;
					this.getData();
				}).catch(error => {
        			this.errors = error.response.data.errors;
      			});
			},
		},

		created()
		{
			this.getData();
		}
	}
</script>