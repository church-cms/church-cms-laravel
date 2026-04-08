<template>
    <div>
        <portal to="eventpopup">
            <div class="hide-menu absolute top-0 right-0 left-0 flex flex-col items-center justify-center h-full" id="show-detail" >
                <div class="bg-white w-full  lg:w-1/3 md:w-1/3 shadow-lg border border-r-0 member-detail z-40 right-0 overflow-y-auto mx-auto relative">
                    <a href="#"  @click="disableform()" class="absolute right-0">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" xml:space="preserve" class="w-3 h-3 m-3 fill-current text-gray-700"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717 L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859 c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287 l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285 L284.286,256.002z"></path></g></g></svg>
                    </a>

                    <div class="flex flex-col lg:flex-row md:flex-row py-3" v-if="this.event.category == 'holidays'">
                        <div class="w-full px-3">
                            <div>
                                <div class="leading-relaxed">
                                    <div class="text-base text-gray-600 mx-2 my-1 capitalize flex items-center">
                                        <p class="w-3/4">{{ this.event.title }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row md:flex-row py-3" v-else>
                        <div class="w-full px-3">
                            <div>
                                <div class="leading-relaxed">
                                    <div class="text-base text-gray-600 mx-2 my-1 capitalize flex items-center" >
                                        <p class="w-1/3">Title :</p>
                                        <p class="w-3/4">{{ this.event.title }}</p>
                                    </div>
                                    <div class="text-base text-gray-600 mx-2 my-1 capitalize flex items-center">
                                        <p class="w-1/3">Description :</p>
                                        <p class="w-3/4">{{ this.event.description }}</p>
                                    </div>
                                    <div class="text-base text-gray-600 mx-2 my-1 capitalize flex items-center">
                                        <div class="w-1/3 flex items-center">
                                            <svg class="w-4 h-4 fill-current text-gray-700 mr-3" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"><g><path d="M30,0C13.458,0,0,13.458,0,30s13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z M30,58C14.561,58,2,45.439,2,30 S14.561,2,30,2s28,12.561,28,28S45.439,58,30,58z"/><path d="M30,6c-0.552,0-1,0.447-1,1v23H14c-0.552,0-1,0.447-1,1s0.448,1,1,1h16c0.552,0,1-0.447,1-1V7C31,6.447,30.552,6,30,6z"/></g></svg>
                                            <p class="">Date</p>
                                        </div>
                                        <p class="w-3/4">{{ this.event.start_date }} - {{ this.event.end_date }}</p>
                                    </div>
                                    <div class="text-base text-gray-600 mx-2 my-1 capitalize flex items-center" >
                                        <div class="w-1/3 flex items-center">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 368.16 368.16" xml:space="preserve" class="w-4 h-4 fill-current text-gray-700 mr-3"><g><g><g><path d="M184.08,0c-74.992,0-136,61.008-136,136c0,24.688,11.072,51.24,11.536,52.36c3.576,8.488,10.632,21.672,15.72,29.4 l93.248,141.288c3.816,5.792,9.464,9.112,15.496,9.112s11.68-3.32,15.496-9.104l93.256-141.296 c5.096-7.728,12.144-20.912,15.72-29.4c0.464-1.112,11.528-27.664,11.528-52.36C320.08,61.008,259.072,0,184.08,0z M293.8,182.152c-3.192,7.608-9.76,19.872-14.328,26.8l-93.256,141.296c-1.84,2.792-2.424,2.792-4.264,0L88.696,208.952 c-4.568-6.928-11.136-19.2-14.328-26.808C74.232,181.816,64.08,157.376,64.08,136c0-66.168,53.832-120,120-120 c66.168,0,120,53.832,120,120C304.08,157.408,293.904,181.912,293.8,182.152z"></path><path d="M184.08,64.008c-39.704,0-72,32.304-72,72c0,39.696,32.296,72,72,72c39.704,0,72-32.304,72-72 C256.08,96.312,223.784,64.008,184.08,64.008z M184.08,192.008c-30.872,0-56-25.12-56-56s25.128-56,56-56s56,25.12,56,56 S214.952,192.008,184.08,192.008z"></path></g></g></g></svg>
                                            <p class="">Location</p>
                                        </div>
                                        <p class="w-3/4">{{ this.event.location }}</p>
                                    </div>
                                </div>
                                <div class="pt-3 pb-2 px-1 detail_button">
                                    <a v-if="this.event && this.event.id" :href="this.url+'/'+this.mode+'/events/show/details/'+this.event.id" class="text-blue-500 text-sm flex items-center"><button class="btn-primary bg-blue-500 text-white rounded px-4 py-1">Detail</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </portal>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import PortalVue from "portal-vue";
    export default {
        props:['url','mode'],
        data(){
            return{
                event:[],
                errors:[],
                success:null,
            }
        },

        created()
        {
            //
            bus.$on("dataEventDetail", data => {
                if(data!='')
                {
                    this.eventid=data;
                    this.getData();
                }
            });
            bus.$on("dataEventFilter", data => {
                if(data!='')
                {
                    this.disableform();
                }
            });
        },

        methods:
        {
            disableform()
            {
                $('#show-detail').removeClass('block').addClass('hide-menu');
            },

            getData()
            {
                axios.get(this.url+'/'+this.mode+'/events/showdetails/'+this.eventid).then(response => {
                    this.event = response.data.data[0];
                    $('#show-detail').removeClass('hide-menu').addClass('block');
                });
            }
        },
    }
</script>

<style>
    .hide-menu {
        display: none;
    }
    #show-detail {
        background-color: #33313169;
        height: 100vh;
        position: fixed;
        z-index: 999;
    }
</style>
