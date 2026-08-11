<template>
    <div class="bg-white shadow rounded p-2">

                    <div class="w-full">
                        <ul class="list-reset py-1" v-for="sermon in sermons" v-bind:key="sermon.id">
                            <li class="py-2">
                                <div class="flex items-center">
                                    <div class="w-1/6">
                                        <img :src="sermon.cover_image" class="rounded">
                                    </div>
                                    <div class="ml-2 w-5/6">
                                        <a :href="url+'/admin/sermon/show/'+sermon.sermons_id" class="text-gray-700 font-semibold text-sm">{{ sermon.title }}</a>
                                        <p class="text-xs text-purple-500 flex items-center">{{ sermon.sermon_date }}
                                            <span class="mx-2 italic text-gray-700">by</span>
                                            <a :href="url+'/admin/preacher/show/'+sermon.name">{{ sermon.fullname }}</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                         <div class="py-2" v-if="this.sermons == ''">
                        <p class="font-semibold text-sm" style="text-align: center;">No Records Found</p>
                    </div>
                    </div>
                </div>

</template>

<script>
    export default {
        props:['url'],
        data () {
            return {
                sermons:[],
                errors:[],
                success:null,
            }
        },
        methods:
        {
            getData()
            {
                axios.get('/admin/dashboard/sermon').then(response => {
                    this.sermons = response.data.data;
                });
            }
        },
        created()
        {
            this.getData();
        }
    }
</script>
