<template>
    <div>
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-xs mb-3">
            {{ success }}
        </div>

        <div v-if="loading" class="text-xs text-gray-500 py-4">Loading...</div>

        <div v-else>
            <div v-for="(perms, group) in groupedPermissions" :key="group" class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold capitalize text-gray-700">{{ group }}</p>
                    <a href="#" class="text-xs blue-text" @click.prevent="toggleGroup(group)">Toggle All</a>
                </div>
                <div class="flex flex-wrap">
                    <div v-for="perm in perms" :key="perm.name" class="w-full lg:w-1/3 md:w-1/2 py-1">
                        <label class="flex items-center text-xs text-gray-700 cursor-pointer">
                            <input
                                type="checkbox"
                                class="mr-2"
                                :value="perm.name"
                                v-model="selected"
                            >
                            <span class="capitalize">{{ formatLabel(perm.name) }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button @click="save" class="blue-bg text-white text-xs px-4 py-2 rounded" :disabled="saving">
                    {{ saving ? 'Saving...' : 'Save Permissions' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'name'],
        data() {
            return {
                allPermissions: [],
                selected: [],
                loading: true,
                saving: false,
                success: null,
            };
        },
        computed: {
            groupedPermissions() {
                const groups = {};
                this.allPermissions.forEach(perm => {
                    const parts = perm.name.split('-');
                    const group = parts.length > 1 ? parts[parts.length - 1] : perm.name;
                    if (!groups[group]) groups[group] = [];
                    groups[group].push(perm);
                });
                return groups;
            }
        },
        methods: {
            formatLabel(name) {
                return name.replace(/-/g, ' ');
            },
            toggleGroup(group) {
                const groupPerms = this.groupedPermissions[group].map(p => p.name);
                const allSelected = groupPerms.every(p => this.selected.includes(p));
                if (allSelected) {
                    this.selected = this.selected.filter(p => !groupPerms.includes(p));
                } else {
                    groupPerms.forEach(p => {
                        if (!this.selected.includes(p)) this.selected.push(p);
                    });
                }
            },
            load() {
                axios.get(this.url + '/admin/subadmin/permissions/' + this.name)
                    .then(response => {
                        this.allPermissions = response.data.all;
                        this.selected = response.data.assigned.map(p => p.name);
                        this.loading = false;
                    })
                    .catch(() => { this.loading = false; });
            },
            save() {
                this.saving = true;
                this.success = null;
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                this.selected.forEach(p => formData.append('permissions[]', p));
                axios.post(this.url + '/admin/subadmin/permissions/' + this.name, formData)
                    .then(response => {
                        this.success = response.data.message;
                        this.saving = false;
                    })
                    .catch(() => { this.saving = false; });
            }
        },
        created() {
            this.load();
        }
    }
</script>
