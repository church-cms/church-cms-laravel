<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div>
            <div class="flex">
                <div class="p-2">
                    <img :src="url+'/uploads/icons/gift/birthday.png'" class="w-full h-16">
                </div>
                <div class="overflow-y-auto"> <!-- h-32 max-h-32  -->
                    <ul class="list-reset">
                        <li class="flex items-center py-2" v-for="user in users" v-bind:key="user.id">
                            <img :src="user['avatar']" class="w-8 h-8 rounded-full"> <!-- change -->
                            <a :href="url+'/admin/member/show/'+user['name']" class="mx-2 text-gray-800 text-sm">{{ user['fullname'] }}
                                <span class="text-xs text-gray-600 italic">({{ user['age'] }})</span>
                            </a>
                        </li>
                        <li class="flex items-center py-2 flex items-center h-full" v-if="this.users == ''">
                            <p class="font-semibold text-sm" style="text-align: center">No Records Found</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex my-2 text-center bg-gray-500" v-if="this.users != ''">
                <a :href="url+'/admin/dashboard/birthday'" class=" text-white  px-2  font-medium uppercase w-full text-xs py-1" title="Send Message">Send a Message</a>
            </div>
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
                axios.get('/admin/dashboard/showBirthday').then(response => {
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