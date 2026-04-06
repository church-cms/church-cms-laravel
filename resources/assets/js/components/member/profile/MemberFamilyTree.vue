<template>
    <div class="w-full">

        <!-- Relation header -->
        <div v-if="reference && reference.referby" class="mb-4 text-xs text-gray-500">
            <span class="font-medium text-gray-700">{{ convertUpper(reference.relation) }}</span>
            of
            <span class="font-medium text-indigo-600">
                {{ reference.referby.userprofile.firstname }} {{ reference.referby.userprofile.lastname }}
            </span>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-10">
            <svg class="animate-spin w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>

        <!-- Tree -->
        <div v-else-if="treeData && treeData.name" class="overflow-x-auto family-tree">
            <TreeChart :json="treeData" />
        </div>

        <!-- Empty -->
        <div v-else class="flex flex-col items-center py-10 text-center">
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500">No family tree data available.</p>
        </div>

    </div>
</template>

<script>
    import TreeChart from "vue-tree-chart";

    export default {
        name: 'MemberFamilyTree',

        props: ['name'],

        components: { TreeChart },

        data() {
            return {
                treeData: {},
                reference: null,
                loading: true,
            };
        },

        methods: {
            getData() {
                axios.get('/member/familytree/' + this.name)
                    .then(response => {
                        this.treeData = response.data[0] || {};
                    })
                    .catch(() => { this.treeData = {}; })
                    .finally(() => { this.loading = false; });
            },

            getRef() {
                axios.get('/member/show/details/' + this.name)
                    .then(response => {
                        this.reference = response.data.data ? response.data.data[0] : null;
                    });
            },

            convertUpper(str) {
                if (!str) return '';
                return str.charAt(0).toUpperCase() + str.slice(1);
            },
        },

        created() {
            this.getData();
            this.getRef();
        },
    };
</script>
