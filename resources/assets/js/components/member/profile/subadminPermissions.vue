<template>
    <div>
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-xs mb-3">
            {{ success }}
        </div>

        <div v-if="loading" class="text-xs text-gray-500 py-4">Loading...</div>

        <div v-else>

            <!-- Role preset picker -->
            <div class="flex items-end gap-4 mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                        Apply Role Preset
                    </label>
                    <select v-model="selectedRole" class="tw-form-control w-full text-sm">
                        <option value="">— Select a preset role —</option>
                        <option v-for="role in roles" :key="role.key" :value="role.key">
                            {{ role.label }}
                        </option>
                    </select>
                </div>
                <button
                    @click="applyRole"
                    :disabled="!selectedRole"
                    class="px-4 py-2 text-sm font-medium text-white blue-bg rounded disabled:opacity-50 disabled:cursor-not-allowed">
                    Apply
                </button>
                <button
                    @click="clearAll"
                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded hover:bg-gray-50">
                    Clear All
                </button>
            </div>

            <div v-if="appliedRole" class="text-xs text-indigo-700 bg-indigo-50 border border-indigo-200 rounded px-3 py-2 mb-4">
                <strong>{{ appliedRole }}</strong> preset applied — adjust as needed, then click Save Permissions.
            </div>

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
    const ROLE_PRESETS = {
        preacher: {
            label: 'Preacher',
            permissions: [
                'create-sermons', 'read-sermons', 'update-sermons', 'delete-sermons',
            ],
        },
        event_coordinator: {
            label: 'Event Coordinator',
            permissions: [
                'create-events', 'read-events', 'update-events',
                'create-gallery', 'read-gallery', 'update-gallery',
            ],
        },
        content_manager: {
            label: 'Content Manager',
            permissions: [
                'create-bulletins', 'read-bulletins', 'view-bulletins',
                'create-quotes', 'read-quotes', 'update-quotes',
                'create-gallery', 'read-gallery', 'update-gallery',
                'create-files', 'read-files', 'view-files',
            ],
        },
        finance_officer: {
            label: 'Finance Officer',
            permissions: [
                'create-funds', 'read-funds', 'update-funds', 'view-funds',
                'read-payments', 'create-payments',
                'read-reports', 'view-reports',
            ],
        },
        prayer_coordinator: {
            label: 'Prayer Coordinator',
            permissions: [
                'read-prayers', 'update-prayers',
            ],
        },
        support_coordinator: {
            label: 'Support Coordinator',
            permissions: [
                'read-helps', 'update-helps',
            ],
        },
        web_admin: {
            label: 'Web Admin',
            permissions: [
                'read-contacts',
                'read-feedbacks', 'update-feedbacks',
                'read-video-conferences', 'create-video-conferences', 'delete-video-conferences',
            ],
        },
        email_blaster_manager: {
            label: 'Email Blaster Manager',
            permissions: ['manage-email-blaster'],
        },
        cms_manager: {
            label: 'CMS Manager',
            permissions: ['manage-cms'],
        },
        full_access: {
            label: 'Full Access',
            permissions: '__all__',
        },
    };

    export default {
        props: ['url', 'name'],
        data() {
            return {
                allPermissions: [],
                selected: [],
                loading: true,
                saving: false,
                success: null,
                selectedRole: '',
                appliedRole: null,
                roles: Object.entries(ROLE_PRESETS).map(([key, val]) => ({ key, label: val.label })),
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
            applyRole() {
                if (!this.selectedRole) return;
                const preset = ROLE_PRESETS[this.selectedRole];
                if (!preset) return;

                if (preset.permissions === '__all__') {
                    this.selected = this.allPermissions.map(p => p.name);
                } else {
                    const available = new Set(this.allPermissions.map(p => p.name));
                    this.selected = preset.permissions.filter(p => available.has(p));
                }

                this.appliedRole = preset.label;
                this.success = null;
            },
            clearAll() {
                this.selected = [];
                this.selectedRole = '';
                this.appliedRole = null;
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
                        this.appliedRole = null;
                    })
                    .catch(() => { this.saving = false; });
            }
        },
        created() {
            this.load();
        }
    }
</script>
