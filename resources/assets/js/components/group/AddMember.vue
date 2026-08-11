<template>
  <div>
    <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm mb-4">
      {{ success }}
    </div>

    <!-- Role selector -->
    <div class="flex items-center gap-4 mb-5">
      <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Assign as</label>
      <select v-model="role" class="tw-form-control w-48">
        <option value="" disabled>Select Role</option>
        <option value="group_admin">Group Admin</option>
        <option value="member">Member</option>
        <option value="guest">Guest</option>
      </select>
      <span class="text-xs text-gray-400">
        <strong>Group Admin</strong> – manage &amp; message &nbsp;|&nbsp;
        <strong>Member</strong> – message only &nbsp;|&nbsp;
        <strong>Guest</strong> – view only
      </span>
    </div>

    <!-- Dual listbox -->
    <div class="flex items-start gap-3">

      <!-- Left: Available -->
      <div class="flex-1 border border-gray-200 rounded-lg overflow-hidden bg-white">
        <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wide flex items-center justify-between">
          <span>Available members</span>
          <span class="text-gray-400 font-normal normal-case">{{ filteredAvailable.length }} shown</span>
        </div>
        <div class="px-3 py-2 border-b border-gray-100">
          <div class="relative">
            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input
              v-model="leftSearch"
              type="text"
              placeholder="Search..."
              class="tw-form-control w-full pl-7 py-1.5 text-sm"
            >
          </div>
        </div>
        <ul class="overflow-y-auto" style="min-height:220px;max-height:280px;">
          <li v-if="loading" class="px-3 py-8 text-center text-sm text-gray-400">Loading…</li>
          <li v-else-if="!filteredAvailable.length" class="px-3 py-8 text-center text-sm text-gray-400">No members available</li>
          <li
            v-for="user in filteredAvailable"
            :key="user.id"
            @click="toggleLeft(user.id)"
            :class="['flex items-center gap-2 px-3 py-2 cursor-pointer select-none text-sm transition',
                     leftHighlighted.includes(user.id) ? 'bg-indigo-50 text-indigo-800' : 'text-gray-700 hover:bg-gray-50']"
          >
            <img :src="user.avatar" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
            <span class="truncate">{{ user.fullname }}</span>
          </li>
        </ul>
      </div>

      <!-- Transfer buttons -->
      <div class="flex flex-col items-center justify-center gap-2 pt-24">
        <button @click="moveAllRight" title="Add all" class="transfer-btn">»</button>
        <button @click="moveRight"    title="Add selected" class="transfer-btn">›</button>
        <button @click="moveLeft"     title="Remove selected" class="transfer-btn">‹</button>
        <button @click="moveAllLeft"  title="Remove all" class="transfer-btn">«</button>
      </div>

      <!-- Right: Selected -->
      <div class="flex-1 border border-gray-200 rounded-lg overflow-hidden bg-white">
        <div class="px-3 py-2 bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wide flex items-center justify-between">
          <span>Selected members</span>
          <span class="text-gray-400 font-normal normal-case">{{ selected.length }} selected</span>
        </div>
        <div class="px-3 py-2 border-b border-gray-100">
          <div class="relative">
            <i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input
              v-model="rightSearch"
              type="text"
              placeholder="Search..."
              class="tw-form-control w-full pl-7 py-1.5 text-sm"
            >
          </div>
        </div>
        <ul class="overflow-y-auto" style="min-height:220px;max-height:280px;">
          <li v-if="!filteredSelected.length" class="px-3 py-8 text-center text-sm text-gray-400">No members selected</li>
          <li
            v-for="user in filteredSelected"
            :key="user.id"
            @click="toggleRight(user.id)"
            :class="['flex items-center gap-2 px-3 py-2 cursor-pointer select-none text-sm transition',
                     rightHighlighted.includes(user.id) ? 'bg-indigo-50 text-indigo-800' : 'text-gray-700 hover:bg-gray-50']"
          >
            <img :src="user.avatar" class="w-7 h-7 rounded-full object-cover flex-shrink-0">
            <span class="truncate">{{ user.fullname }}</span>
          </li>
        </ul>
      </div>

    </div>

    <!-- Actions -->
    <div class="flex items-center gap-3 mt-5">
      <button
        @click="submit"
        :disabled="!selected.length || !role || saving"
        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded disabled:opacity-50 disabled:cursor-not-allowed transition">
        {{ saving ? 'Adding…' : 'Add Members' }}
      </button>
      <a :href="url + '/admin/group/show/' + group_id" class="px-5 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-medium rounded hover:bg-gray-50 transition">
        Cancel
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['url', 'church_id', 'group_id'],
    data() {
      return {
        available: [],
        selected: [],
        leftSearch: '',
        rightSearch: '',
        leftHighlighted: [],
        rightHighlighted: [],
        role: '',
        loading: true,
        saving: false,
        success: null,
      }
    },
    computed: {
      filteredAvailable() {
        const q = this.leftSearch.toLowerCase()
        return q ? this.available.filter(u => u.fullname.toLowerCase().includes(q)) : this.available
      },
      filteredSelected() {
        const q = this.rightSearch.toLowerCase()
        return q ? this.selected.filter(u => u.fullname.toLowerCase().includes(q)) : this.selected
      },
    },
    methods: {
      toggleLeft(id) {
        const idx = this.leftHighlighted.indexOf(id)
        idx >= 0 ? this.leftHighlighted.splice(idx, 1) : this.leftHighlighted.push(id)
      },
      toggleRight(id) {
        const idx = this.rightHighlighted.indexOf(id)
        idx >= 0 ? this.rightHighlighted.splice(idx, 1) : this.rightHighlighted.push(id)
      },
      moveRight() {
        const moving = this.available.filter(u => this.leftHighlighted.includes(u.id))
        this.available = this.available.filter(u => !this.leftHighlighted.includes(u.id))
        this.selected.push(...moving)
        this.leftHighlighted = []
      },
      moveAllRight() {
        this.selected.push(...this.available)
        this.available = []
        this.leftHighlighted = []
      },
      moveLeft() {
        const moving = this.selected.filter(u => this.rightHighlighted.includes(u.id))
        this.selected = this.selected.filter(u => !this.rightHighlighted.includes(u.id))
        this.available.push(...moving)
        this.rightHighlighted = []
      },
      moveAllLeft() {
        this.available.push(...this.selected)
        this.selected = []
        this.rightHighlighted = []
      },
      submit() {
        if (!this.selected.length || !this.role) return
        this.saving = true
        this.success = null
        axios.post('/admin/group/addMember/' + this.group_id, {
          church_id: this.church_id,
          group_id:  this.group_id,
          role:      this.role,
          user_ids:  this.selected.map(u => u.id),
        }).then(response => {
          this.success = response.data.success
          this.selected = []
          this.rightHighlighted = []
          this.saving = false
          this.loadMembers()
        }).catch(() => { this.saving = false })
      },
      loadMembers() {
        this.loading = true
        axios.get('/admin/group/showMember/' + this.group_id).then(response => {
          this.available = (response.data?.memberlist || []).map(u => ({
            id: u.id,
            fullname: u.fullname,
            avatar: u.avatar,
            mobile_no: u.mobile_no,
          }))
          this.loading = false
        }).catch(() => { this.loading = false })
      },
    },
    created() {
      this.loadMembers()
    },
  }
</script>

<style scoped>
.transfer-btn {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: white;
  font-size: 18px;
  color: #374151;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
  line-height: 1;
}
.transfer-btn:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}
</style>
