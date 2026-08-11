<template>
    <div>
        <a href="#" class="block cursor:pointer no-underline -mt-1" @click="enableNotification()">
            <div class="relative flex" style="padding:10px 15px;">
                <div>
                    <svg version="1.0" class="w-8 h-8 fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" width="222.000000pt" height="227.000000pt" viewBox="0 0 222.000000 227.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,227.000000) scale(0.100000,-0.100000)" fill="" stroke="none"><path d="M1053 1955 c-45 -19 -73 -69 -73 -128 0 -36 -4 -49 -17 -53 -113 -38 -213 -111 -260 -191 -64 -109 -67 -126 -73 -418 -6 -262 -7 -272 -33 -338 -14 -37 -53 -109 -87 -161 -62 -96 -68 -114 -48 -134 17 -17 1279 -17 1296 0 20 20 14 38 -47 132 -32 50 -71 120 -87 156 -28 65 -28 67 -34 340 -6 247 -8 280 -27 328 -30 74 -79 147 -127 188 -36 31 -128 81 -178 98 -14 4 -18 16 -18 53 0 60 -26 104 -73 126 -42 20 -71 21 -114 2z"/><path d="M145 1786 c-9 -13 -31 -59 -49 -103 -90 -213 -109 -453 -53 -673 27 -102 77 -230 106 -269 18 -23 18 -23 47 -5 16 10 31 19 33 21 2 2 -14 42 -37 90 -71 150 -86 222 -86 418 0 196 15 269 84 413 22 46 40 87 40 91 0 7 -53 41 -64 41 -3 0 -13 -11 -21 -24z"/><path d="M2020 1793 c-14 -9 -27 -18 -29 -20 -2 -1 14 -42 37 -90 71 -150 86 -222 86 -418 0 -198 -15 -270 -87 -418 l-43 -88 34 -21 35 -21 18 24 c28 37 79 165 107 269 22 82 25 116 25 250 1 170 -11 238 -62 380 -31 84 -76 170 -89 170 -4 0 -19 -8 -32 -17z"/><path d="M326 1650 c-66 -122 -103 -294 -93 -435 8 -110 30 -192 79 -296 l40 -87 34 20 c19 11 34 24 34 28 0 4 -13 36 -30 71 -94 201 -94 443 0 629 17 34 29 67 27 74 -3 6 -18 19 -34 28 l-30 17 -27 -49z"/><path d="M1832 1678 c-18 -11 -32 -24 -32 -28 0 -4 16 -41 35 -82 89 -186 87 -420 -5 -617 -17 -35 -30 -67 -30 -72 0 -5 14 -16 32 -25 31 -16 33 -16 48 6 57 87 110 281 110 405 -1 130 -51 322 -108 407 l-18 27 -32 -21z"/><path d="M928 398 c11 -18 41 -46 67 -62 109 -69 242 -34 309 82 6 9 -36 12 -194 12 l-202 0 20 -32z"/></g></svg>
                </div>
                <div v-if="this.count > 0" class="w-4 h-4 bg-blue-600 text-white rounded-full p-1 flex items-center justify-center text-xs -ml-2">{{ this.count }}</div>
            </div>
        </a>

        <div class="hide-menu" id="notification-list">
            <div class="shadow" style="width: 300px; z-index:40; top:0;right:0; position:fixed;background:#fff;">
                <div class="px-3" style="height: 100vh; justify-content: space-between;margin-bottom: 2rem; display: flex; flex-direction: column;">
                    <div class="my-3 flex justify-between items-center w-full">
                        <div class="font-semibold">Unread Notifications</div>
                        <a href="#" @click="disableNotification()" style="display:block;font-bold;text-decoration: none;">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-2 h-2 m-1 fill-current text-gray-700"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717 L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859 c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287 l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285 L284.286,256.002z"></path></g></g></svg>
                        </a>
                    </div>
                    <div style="flex: 1;overflow:auto;">
                        <div class="py-2" v-for="notification in notifications" style="border-top:1px dotted #b8c2cc;border-bottom:1px dotted #b8c2cc;">
                            <a href="#" class="no-underline hover:bg-yellow" @click="view(notification.notification_id)">
                                <div class="text-sm font-semibold text-black text-left">
                                    <p>{{ notification.data }}</p>
                                    <div id="showtype" class="" v-bind:class="[{'snooze_task' : notification.type === 'task'}]"></div>
                                    <div class="flex flex-wrap items-center">
                                        <div class="py-2 px-2">
                                            <svg class="w-3 h-3 fill-current text-blue-600" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill=""/><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill=""/></g></g></g></svg>
                                        </div>
                                        <p class="text-xs text-blue-600">{{ notification.date }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div v-if="this.count == 0" class="py-2 text-sm" style="border-top:1px dotted #b8c2cc;border-bottom:1px dotted #b8c2cc; text-align: center;">No Unread Notifications</div>
                    </div>
                    <div style="text-align:center;padding:5px;">
                        <a href="#" @click="view('all')">View All Notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PortalVue from "portal-vue";
    export default {
        props: [ 'url' , 'mode' ],
        data(){
            return {
                notifications:[],           
                count:0,               
            }
        },

        methods:
        {
            getNotifications()
            {
                axios.get(this.url+'/'+this.mode+'/notification/showList').then(response =>{
                    this.notifications  = response.data.list;
                    this.count          = response.data.count;  
                });
                //this.playSound();
            },


            view(id)
            {
                if(id == 'all')
                {
                    window.location = this.url+'/'+this.mode+'/notifications';   
                }
                else
                {
                    this.success = null;
                    this.errors = [];

                    let formData = new FormData();

                    formData.append('notification_id',id);

                    axios.post(this.url+'/'+this.mode+'/notification/read',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then( response => {
                        window.location = this.url+'/'+this.mode+'/notifications';    
                        this.success = response.data.success;
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            },

            enableNotification()
            {
                $('#notification-list').removeClass('hide-menu').addClass('block');
            },

            disableNotification()
            {
                $('#notification-list').removeClass('block').addClass('hide-menu');
            },

            listenForNotifications() 
            {
               /* window.Echo.channel('notification').listen('AfterNotification', (data) => {                      
                    this.getNotifications();           
                });*/
            },
        },
        created() 
        {
            this.getNotifications();
            this.listenForNotifications();
        },
    }
</script>

<style scoped="">
    .hide-menu{
        display:none !important;
    }
</style>