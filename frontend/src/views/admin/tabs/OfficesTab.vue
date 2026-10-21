<script setup>
import { ref, computed, watch } from 'vue'
import { 
  Search, 
  LayoutGrid, 
  List, 
  Plus, 
  Building2, 
  Phone, 
  Edit, 
  Trash2, 
  ChevronLeft, 
  ChevronRight 
} from 'lucide-vue-next'

const props = defineProps({
  officesList: {
    type: Array,
    required: true
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem'])

const officeSearch = ref('')
const officesViewMode = ref('grid') // 'grid' | 'table'
const officePage = ref(1)
const officePerPage = 8

const filteredOffices = computed(() => {
  if (!officeSearch.value.trim()) return props.officesList
  const q = officeSearch.value.toLowerCase().trim()
  return props.officesList.filter(off => 
    (off.name_en && off.name_en.toLowerCase().includes(q)) ||
    (off.name_am && off.name_am.includes(q)) ||
    (off.room_number && off.room_number.toLowerCase().includes(q)) ||
    (off.phone && off.phone.includes(q)) ||
    (off.building_location_en && off.building_location_en.toLowerCase().includes(q)) ||
    (off.building_location_am && off.building_location_am.includes(q))
  )
})

const totalOfficePages = computed(() => {
  return Math.ceil(filteredOffices.value.length / officePerPage) || 1
})

const paginatedOffices = computed(() => {
  const start = (officePage.value - 1) * officePerPage
  return filteredOffices.value.slice(start, start + officePerPage)
})

watch(officeSearch, () => {
  officePage.value = 1
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Municipal Offices & Directorate Directory</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ filteredOffices.length }} offices
          </span>
        </h2>
      </div>
      <div class="flex items-center gap-3">
        <!-- Live Filter Search Bar -->
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="officeSearch"
            type="text"
            placeholder="Filter by name, room, phone..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-xs font-medium"
          />
        </div>

        <!-- View Mode Switcher -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
          <button 
            @click="officesViewMode = 'grid'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', officesViewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
            <span class="hidden sm:inline">Cards</span>
          </button>
          <button 
            @click="officesViewMode = 'table'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', officesViewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Directory Table View"
          >
            <List class="w-4 h-4" />
            <span class="hidden sm:inline">Table</span>
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Add Office</span>
        </button>
      </div>
    </div>

    <div v-if="filteredOffices.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 shadow-xs">
      <Building2 class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No municipal offices found matching "<span class="font-bold text-slate-900">{{ officeSearch }}</span>".</p>
      <p class="text-[11px] text-slate-400 mt-1">Try another search keyword or click "Add Office" to register a new bureau.</p>
    </div>

    <!-- 1. DIRECTORY TABLE VIEW -->
    <div v-else-if="officesViewMode === 'table'" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Office & Directorate</th>
              <th class="py-3 px-4">Building Location</th>
              <th class="py-3 px-4">Room #</th>
              <th class="py-3 px-4">Phone Line</th>
              <th class="py-3 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="off in paginatedOffices" :key="off.id" class="hover:bg-slate-50/70 transition-colors">
              <td class="py-3.5 px-4 max-w-sm">
                <div class="font-bold text-slate-900 text-xs leading-snug">{{ off.name_en || off.name_am }}</div>
                <div class="text-[11px] text-slate-400 truncate mt-0.5 font-mono">AM: {{ off.name_am }}</div>
              </td>

              <td class="py-3.5 px-4 text-slate-700 text-xs font-semibold whitespace-nowrap">
                {{ off.building_location_en || off.building_location_am || 'Main Administrative Complex' }}
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-mono font-bold text-xs border border-blue-200">
                  {{ off.room_number ? 'Room ' + off.room_number : 'Main Hall' }}
                </span>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <a :href="'tel:' + off.phone" class="font-mono text-xs font-bold text-blue-700 hover:underline inline-flex items-center gap-1.5">
                  <Phone class="w-3 h-3 text-slate-400" />
                  <span>{{ off.phone || 'N/A' }}</span>
                </a>
              </td>

              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <button @click="emit('openEdit', off)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg border border-slate-200 cursor-pointer transition" title="Edit Office">
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button v-if="isSuperAdmin" @click="emit('deleteItem', off)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg border border-rose-200 cursor-pointer transition" title="Delete Office">
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Offices Pagination Controls Bar (Table) -->
      <div v-if="filteredOffices.length > officePerPage" class="p-3.5 px-6 bg-slate-50/80 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ officePage }}</span> of <span class="font-bold text-slate-900">{{ totalOfficePages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="officePage === 1" 
            @click="officePage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalOfficePages" 
            :key="p"
            @click="officePage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              officePage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="officePage === totalOfficePages" 
            @click="officePage++"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>

    <!-- 2. CARD GRID VIEW -->
    <div v-else class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div 
          v-for="off in paginatedOffices" 
          :key="off.id" 
          class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
          <div>
            <div class="flex items-center justify-between gap-2 mb-3 pt-1">
              <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-mono text-xs font-bold border border-blue-200">
                {{ off.room_number ? 'Room ' + off.room_number : 'Main Hall' }}
              </span>
              <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">
                Desk #{{ off.id }}
              </span>
            </div>

            <h4 class="font-black text-base text-slate-900 leading-snug">{{ off.name_en || off.name_am }}</h4>
            <p class="text-xs text-slate-500 font-mono mt-1">AM: {{ off.name_am }}</p>

            <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5 text-xs">
              <div class="text-slate-600 flex items-center gap-1.5">
                <Building2 class="w-3.5 h-3.5 text-blue-600 shrink-0" />
                <span class="truncate">{{ off.building_location_en || off.building_location_am || 'Administrative Complex' }}</span>
              </div>
              <div class="text-slate-800 font-mono font-bold flex items-center gap-1.5">
                <Phone class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                <a :href="'tel:' + off.phone" class="text-blue-700 hover:underline">{{ off.phone || 'N/A' }}</a>
              </div>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5">
            <button @click="emit('openEdit', off)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl border border-slate-200 cursor-pointer transition" title="Edit Office">
              <Edit class="w-3.5 h-3.5" />
            </button>
            <button v-if="isSuperAdmin" @click="emit('deleteItem', off)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 cursor-pointer transition" title="Delete Office">
              <Trash2 class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>

      <!-- Offices Pagination Controls Bar (Cards) -->
      <div v-if="filteredOffices.length > officePerPage" class="p-4 px-6 bg-white rounded-3xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ officePage }}</span> of <span class="font-bold text-slate-900">{{ totalOfficePages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="officePage === 1" 
            @click="officePage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalOfficePages" 
            :key="p"
            @click="officePage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              officePage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="officePage === totalOfficePages" 
            @click="officePage++"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
