<script setup>
import { ref, computed } from 'vue'
import { 
  PhoneCall, 
  Plus, 
  ShieldAlert, 
  Edit, 
  Trash2, 
  Phone, 
  CheckCircle2, 
  XCircle,
  Search,
  LayoutGrid,
  List,
  Filter,
  Check,
  Power
} from 'lucide-vue-next'

const props = defineProps({
  hotlinesList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'toggleActive'])

const viewMode = ref('cards') // 'cards' | 'table'
const searchQuery = ref('')
const statusFilter = ref('all') // 'all' | 'active' | 'disabled'

const filteredHotlines = computed(() => {
  return props.hotlinesList.filter(item => {
    const q = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !q || 
      (item.name_en && item.name_en.toLowerCase().includes(q)) ||
      (item.name_am && item.name_am.includes(q)) ||
      (item.phone_number && item.phone_number.includes(q)) ||
      (item.category && item.category.toLowerCase().includes(q))

    const matchesStatus = statusFilter.value === 'all' || 
      (statusFilter.value === 'active' && item.is_active) ||
      (statusFilter.value === 'disabled' && !item.is_active)

    return matchesSearch && matchesStatus
  })
})
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>24/7 Emergency Dispatch Hotlines</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ hotlinesList.length }} hotlines
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Toggle -->
        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl shadow-inner">
          <button 
            type="button"
            @click="viewMode = 'cards'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'cards' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            type="button"
            @click="viewMode = 'table'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Sovereign Table View"
          >
            <List class="w-4 h-4" />
          </button>
        </div>

        <button 
          v-if="isEditor"
          @click="emit('openCreate')" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          <span>Add Emergency Hotline</span>
        </button>
      </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="relative w-full sm:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="searchQuery"
          type="text"
          placeholder="Filter by hotline name, number, category..."
          class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto">
        <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs">
          <button 
            type="button"
            @click="statusFilter = 'all'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            All ({{ hotlinesList.length }})
          </button>
          <button 
            type="button"
            @click="statusFilter = 'active'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'active' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            Active 24/7 ({{ hotlinesList.filter(h => h.is_active).length }})
          </button>
          <button 
            type="button"
            @click="statusFilter = 'disabled'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'disabled' ? 'bg-white text-slate-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            Disabled ({{ hotlinesList.filter(h => !h.is_active).length }})
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredHotlines.length === 0" class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-500">
      <PhoneCall class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="font-bold text-base text-slate-800">No emergency hotlines found</h3>
      <p class="text-xs text-slate-500 mt-1">Try refining your search terms or status filter.</p>
    </div>

    <!-- 1. SOVEREIGN TABLE VIEW -->
    <div v-else-if="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-black">
              <th class="py-3.5 px-4">Hotline & Service</th>
              <th class="py-3.5 px-4">Category</th>
              <th class="py-3.5 px-4">Direct Dial / Number</th>
              <th class="py-3.5 px-4">Type</th>
              <th class="py-3.5 px-4">Status</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans">
            <tr 
              v-for="item in filteredHotlines" 
              :key="item.id" 
              class="hover:bg-blue-50/40 transition-colors"
            >
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0 border border-rose-100">
                    <PhoneCall class="w-4 h-4" />
                  </div>
                  <div>
                    <span class="font-bold text-slate-900 text-sm block leading-snug">{{ item.name_en || item.name_am }}</span>
                    <span class="text-xs text-slate-500 font-mono">AM: {{ item.name_am }}</span>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                  {{ item.category || 'Emergency' }}
                </span>
              </td>
              <td class="py-3.5 px-4">
                <a :href="'tel:' + item.phone_number" class="text-base font-black font-mono text-blue-700 hover:underline inline-flex items-center gap-1.5">
                  <Phone class="w-3.5 h-3.5 text-slate-400" />
                  <span>{{ item.phone_number }}</span>
                </a>
              </td>
              <td class="py-3.5 px-4">
                <span v-if="item.is_toll_free" class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-mono text-[10px] font-bold">
                  Toll Free
                </span>
                <span v-else class="text-slate-400 text-xs font-mono">Standard</span>
              </td>
              <td class="py-3.5 px-4">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide',
                    item.is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ item.is_active ? 'Active 24/7' : 'Disabled' }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                  <button 
                    v-if="isEditor"
                    @click="emit('openEdit', item)"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    title="Edit hotline"
                  >
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button 
                    v-if="isEditor"
                    @click="emit('deleteItem', item.id)"
                    class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    title="Delete hotline"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. CARD GRID VIEW -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <div 
        v-for="item in filteredHotlines" 
        :key="item.id"
        class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
      >
        <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
        <div>
          <div class="flex items-center justify-between mb-4">
            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
              {{ item.category || 'Emergency' }}
            </span>
            <span 
              :class="[
                'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide',
                item.is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500'
              ]"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
              {{ item.is_active ? 'Active 24/7' : 'Disabled' }}
            </span>
          </div>

          <div class="flex items-center gap-3.5 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-base shrink-0 border border-rose-100 shadow-2xs">
              <PhoneCall class="w-6 h-6" />
            </div>
            <div>
              <h3 class="font-black text-base text-slate-900 leading-snug">{{ item.name_en || item.name_am }}</h3>
              <p class="text-xs text-slate-500 font-mono">AM: {{ item.name_am }}</p>
            </div>
          </div>

          <div class="mt-4 p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-black uppercase tracking-wider block">Hotline Number</span>
              <a :href="'tel:' + item.phone_number" class="text-lg font-black font-mono text-blue-700 hover:underline">
                {{ item.phone_number }}
              </a>
            </div>
            <span v-if="item.is_toll_free" class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-800 text-[10px] font-bold">
              Toll Free
            </span>
          </div>
        </div>

        <div v-if="isEditor" class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-end gap-1.5">
          <button 
            @click="emit('openEdit', item)"
            class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
            title="Edit hotline"
          >
            <Edit class="w-4 h-4" />
          </button>
          <button 
            @click="emit('deleteItem', item.id)"
            class="p-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
            title="Delete hotline"
          >
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
