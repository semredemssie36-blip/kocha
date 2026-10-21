<script setup>
import { ref, computed } from 'vue'
import { 
  Users, 
  LayoutGrid, 
  List, 
  Plus, 
  Search, 
  Phone, 
  Mail, 
  Edit, 
  Trash2 
} from 'lucide-vue-next'

const props = defineProps({
  leadershipList: {
    type: Array,
    required: true
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem'])

const leadershipViewMode = ref('cards')
const leadershipSearch = ref('')
const leadershipTypeFilter = ref('all')

const filteredLeadership = computed(() => {
  return props.leadershipList.filter(m => {
    const q = leadershipSearch.value.toLowerCase().trim()
    const matchesSearch = !q ||
      (m.name_en && m.name_en.toLowerCase().includes(q)) ||
      (m.name_am && m.name_am.includes(q)) ||
      (m.position_en && m.position_en.toLowerCase().includes(q)) ||
      (m.position_am && m.position_am.includes(q)) ||
      (m.email && m.email.toLowerCase().includes(q)) ||
      (m.phone && m.phone.includes(q))
    const matchesType = leadershipTypeFilter.value === 'all' || m.type === leadershipTypeFilter.value
    return matchesSearch && matchesType
  })
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Cabinet & City Council Members</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ filteredLeadership.length }} members
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Toggle -->
        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl shadow-inner">
          <button 
            type="button"
            @click="leadershipViewMode = 'cards'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="leadershipViewMode === 'cards' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            type="button"
            @click="leadershipViewMode = 'table'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="leadershipViewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Sovereign Table View"
          >
            <List class="w-4 h-4" />
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Add Member</span>
        </button>
      </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="relative w-full sm:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="leadershipSearch"
          type="text"
          placeholder="Filter by name, portfolio, email, phone..."
          class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
        />
      </div>

      <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs w-full sm:w-auto">
        <button 
          type="button"
          @click="leadershipTypeFilter = 'all'"
          class="px-3 py-1 rounded-lg font-bold transition cursor-pointer"
          :class="leadershipTypeFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
        >
          All ({{ leadershipList.length }})
        </button>
        <button 
          type="button"
          @click="leadershipTypeFilter = 'cabinet'"
          class="px-3 py-1 rounded-lg font-bold transition cursor-pointer"
          :class="leadershipTypeFilter === 'cabinet' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
        >
          Cabinet ({{ leadershipList.filter(m => m.type === 'cabinet').length }})
        </button>
        <button 
          type="button"
          @click="leadershipTypeFilter = 'council'"
          class="px-3 py-1 rounded-lg font-bold transition cursor-pointer"
          :class="leadershipTypeFilter === 'council' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
        >
          City Council ({{ leadershipList.filter(m => m.type === 'council').length }})
        </button>
      </div>
    </div>

    <div v-if="filteredLeadership.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
      No leadership members found matching your search.
    </div>

    <!-- 1. SOVEREIGN TABLE VIEW -->
    <div v-else-if="leadershipViewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-black">
              <th class="py-3.5 px-4">Member Name & Portrait</th>
              <th class="py-3.5 px-4">Portfolio / Position</th>
              <th class="py-3.5 px-4">Body / Branch</th>
              <th class="py-3.5 px-4">Contact Phone & Email</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans">
            <tr v-for="m in filteredLeadership" :key="m.id" class="hover:bg-blue-50/40 transition-colors">
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <img 
                    v-if="m.photo_path"
                    :src="m.photo_path" 
                    :alt="m.name_en || m.name_am" 
                    class="w-11 h-11 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0" 
                  />
                  <div v-else class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                    <Users class="w-5 h-5 text-blue-600" />
                  </div>
                  <div>
                    <span class="font-bold text-slate-900 text-sm block leading-snug">{{ m.name_en || m.name_am }}</span>
                    <span class="text-xs text-slate-500 font-mono">AM: {{ m.name_am }}</span>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span class="font-bold text-slate-800 text-xs block leading-snug">{{ m.position_en || m.position_am }}</span>
                <span v-if="m.position_am && m.position_en" class="text-xs text-slate-500 font-mono">{{ m.position_am }}</span>
              </td>
              <td class="py-3.5 px-4">
                <span 
                  :class="[
                    'px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border',
                    m.type === 'cabinet' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'
                  ]"
                >
                  {{ m.type }}
                </span>
              </td>
              <td class="py-3.5 px-4">
                <div class="space-y-0.5 text-xs font-mono">
                  <div v-if="m.phone && m.phone !== 'N/A'" class="text-slate-800 flex items-center gap-1.5">
                    <Phone class="w-3 h-3 text-slate-400" />
                    <span>{{ m.phone }}</span>
                  </div>
                  <div v-if="m.email && m.email !== 'N/A'" class="text-blue-700 flex items-center gap-1.5">
                    <Mail class="w-3 h-3 text-slate-400" />
                    <span>{{ m.email }}</span>
                  </div>
                  <span v-if="(!m.phone || m.phone === 'N/A') && (!m.email || m.email === 'N/A')" class="text-slate-400">
                    Official Records
                  </span>
                </div>
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                  <button @click="emit('openEdit', m)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg cursor-pointer transition" title="Edit Member">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button v-if="isSuperAdmin" @click="emit('deleteItem', m)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg cursor-pointer transition" title="Delete Member">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. CARD GRID VIEW -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="m in filteredLeadership" :key="m.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:shadow-lg hover:border-slate-300 transition-all flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
        <div>
          <div class="flex items-center gap-4 mb-3">
            <img 
              v-if="m.photo_path"
              :src="m.photo_path" 
              :alt="m.name_en || m.name_am" 
              class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-2xs shrink-0" 
            />
            <div v-else class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
              <Users class="w-6 h-6 text-blue-600" />
            </div>
            <div>
              <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider" :class="m.type === 'cabinet' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200'">
                {{ m.type }}
              </span>
              <h4 class="font-extrabold text-base text-slate-900 mt-1 leading-snug">{{ m.name_en || m.name_am }}</h4>
            </div>
          </div>
          <p class="text-xs font-semibold text-blue-700">{{ m.position_en || m.position_am }}</p>
          <div class="text-xs text-slate-500 font-mono mt-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
            <span>{{ m.phone || 'N/A' }}</span>
            <span class="text-slate-300 mx-1.5">•</span>
            <span>{{ m.email || 'N/A' }}</span>
          </div>
        </div>
        <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-end gap-2">
          <button @click="emit('openEdit', m)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer transition" title="Edit Member">
            <Edit class="w-4 h-4" />
          </button>
          <button v-if="isSuperAdmin" @click="emit('deleteItem', m)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer transition" title="Delete Member">
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
