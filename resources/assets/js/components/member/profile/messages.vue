<template>
    <div class="overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab == 8?'block' :'hidden']">
        <div class="flex flex-wrap custom-table mx-3 my-3">
            <table class="w-full">
                <thead class="bg-grey-light">
                    <tr class="border-b">
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Mode</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Subject</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Message</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Attachments</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Sent On</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                        <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th>
                    </tr>
                </thead>   
                <tbody v-if="Object.keys(this.messages).length != 0">
                    <tr class="border-b" v-for="message in messages">
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">{{ message.mode }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs" v-if="message.subject != null">{{ message.subject }}</p>
                            <p class="font-semibold text-xs" v-else>--</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">{{ message.message }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">
                                <a :href="message.attachments" target="_blank" v-if="message.attachments != null">Download</a>
                                <a href="#" v-else>--</a>
                            </p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">{{ message.date }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">{{ message.status }}</p>
                        </td>
                        <td class="py-3 px-2">
                            <p class="font-semibold text-xs">
                                <a :href="url+'/admin/message/show/'+message.id" class="btn btn-primary submit-btn cursor-pointer">View</a>
                            </p>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr class="border-b">
                        <td colspan="7">
                            <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="this.page_count>1">
                <paginate v-model="page" :page-count="this.page_count" :page-range="3" :margin-pages="1" :click-handler="getData" :prev-text="'<'" :next-text="'>'" :container-class="'pagination'" :page-class="'page-item'" :prev-link-class="'prev'" :next-link-class="'next'"></paginate>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../app";
    export default {
        props:['url','name'],
        data () {
            return {
                profile_tab:'',
                messages:[],
                errors:[],
                success:null, 
                total: 0,
                page: 1,
                page_count: 0,
            }
        },

        methods:
        {
            getData(page=1)
            {
                axios.get('/admin/member/show/message/'+this.name+'?page='+page).then(response => {
                    this.messages = response.data.data;
                    this.page_count = response.data.meta.last_page;
                    this.total = response.data.meta.total;
                    //console.log(this.messages);   
                });
            }
        },
  
        created()
        {   
            this.getData();

            bus.$on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });  
        }
    }
</script>