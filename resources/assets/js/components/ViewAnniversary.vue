<template>
    <div class="w-full">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="w-full flex">
            <img :src="url+'/uploads/icons/gift/anniversary.png'" class="h-16 w-auto">
            <div class="w-full overflow-y-auto"><!--  h-32 max-h-32 -->
                <ul class="w-full list-reset h-full flex items-center">
                    <li class="flex items-center py-2" v-if="Object.keys(this.users).length == 0">
                        <p class="font-semibold text-sm" style="text-align: center">No Records Found</p>
                    </li>
                </ul>
                <ul class="w-full list-reset" v-for="user in users">
                    <li class="w-full flex py-2 mb-2 justify-between items-center border" v-bind:key="user.id" v-if="user != 'no data'">
                        <div class="w-1/2 flex flex-col items-center">
                            <img :src="user['husbandAvatar']" class="w-10 h-10 rounded-full mx-auto">
                            <a :href="url+'/admin/member/show/'+user['husbandUserName']" class="text-sm text-gray-800 italic">{{ user['husbandName'] }} </a>
                        </div>

                        <div class="w-1/2 flex flex-col items-center">
                            <img :src="user['wifeAvatar']" class="w-10 h-10 rounded-full mx-auto">
                            <a :href="url+'/admin/member/show/'+user['wifeUserName']" class="text-gray-800 text-sm italic">{{ user['wifeName'] }} </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="flex border mt-2 py-1 text-center bg-gray-500" v-if="Object.keys(this.users).length > 0">
            <a :href="url+'/admin/dashboard/anniversary'" class="text-white px-2 font-medium uppercase w-full text-xs py-1" title="Send Message">Send a Message</a>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                users: [],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getMember()
            {
                axios.get('/admin/dashboard/showAnniversary').then(response => {
                    this.users = response.data.data;
                });
            },
        },

        created()
        {
            this.getMember();
        }
    }
</script>