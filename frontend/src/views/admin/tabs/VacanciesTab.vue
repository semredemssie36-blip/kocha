<script setup>
import { ref, computed } from 'vue'
import { 
  Briefcase, 
  Search, 
  List, 
  LayoutGrid, 
  Plus, 
  ChevronLeft, 
  ChevronRight, 
  Edit, 
  Trash2 
} from 'lucide-vue-next'

const props = defineProps({
  vacanciesList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  },
  canEditItem: {
    type: Function,
    required: true
  },
  canDeleteItem: {
    type: Function,
    required: true
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem'])

const vacanciesSearch = ref('')
const vacanciesViewMode = ref('table')
const vacanciesPage = ref(1)
const vacanciesPerPage = 9

const filteredVacancies = computed(() => {
  if (!vacanciesSearch.value.trim()) return props.vacanciesList
  const q = vacanciesSearch.value.toLowerCase().trim()
  return props.vacanciesList.filter(job => 
    (job.position_code || '').toLowerCase().includes(q) ||
    (job.title_en || '').toLowerCase().includes(q) ||
    (job.title_am || '').toLowerCase().includes(q) ||
    (job.employment_type || '').toLowerCase().includes(q) ||
    (job.salary_grade || '').toLowerCase().includes(q)
  )
})

const totalVacanciesPages = computed(() => {
  return Math.ceil(filteredVacancies.value.length / vacanciesPerPage) || 1
})

const paginatedVacancies = computed(() => {
  const start = (vacanciesPage.value - 1) * vacanciesPerPage
  return filteredVacancies.value.slice(start, start + vacanciesPerPage)
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Civil Service Vacancies & Careers</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ filteredVacancies.length }} positions
          </span>
        </h2>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="vacanciesSearch"
            type="text"
            placeholder="Filter by code, title, type..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none shadow-xs font-medium"
          />
        </div>

        <!-- View Mode Switcher -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
          <button 
            @click="vacanciesViewMode = 'table'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', vacanciesViewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Table View"
          >
            <List class="w-4 h-4" />
            <span class="hidden sm:inline">Table</span>
          </button>
          <button 
            @click="vacanciesViewMode = 'grid'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', vacanciesViewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
            <span class="hidden sm:inline">Cards</span>
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Post Job Vacancy</span>
        </button>
      </div>
    </div>

    <div v-if="filteredVacancies.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 shadow-xs">
      <Briefcase class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No vacancies found matching "<span class="font-bold text-slate-900">{{ vacanciesSearch }}</span>".</p>
      <p class="text-[11px] text-slate-400 mt-1">Adjust search filter or click "Post Job Vacancy" above.</p>
    </div>

    <!-- TABLE VIEW -->
    <div v-else-if="vacanciesViewMode === 'table'" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Position Code & Type</th>
              <th class="py-3 px-4">Job Title & Scale</th>
              <th class="py-3 px-4">Application Deadline</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="job in paginatedVacancies" :key="job.id" class="hover:bg-slate-50/70 transition-colors">
              <td class="py-3.5 px-4 whitespace-nowrap">
                <div class="font-mono font-black text-slate-900 text-xs">{{ job.position_code }}</div>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase border border-emerald-200">
                  {{ job.employment_type }}
                </span>
              </td>

              <td class="py-3.5 px-4 max-w-sm">
                <div class="font-bold text-slate-900 text-xs leading-snug">{{ job.title_en || job.title_am }}</div>
                <div class="text-[11px] text-slate-400 truncate mt-0.5 font-mono">AM: {{ job.title_am }}</div>
                <div class="text-[10px] text-slate-500 font-bold mt-1">Scale: {{ job.salary_grade || 'Government Scale' }}</div>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <div class="font-mono text-xs text-slate-800 font-bold">{{ job.deadline }}</div>
                <span 
                  :class="[
                    'inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold border',
                    (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date())
                      ? 'bg-rose-50 text-rose-700 border-rose-200'
                      : 'bg-emerald-50 text-emerald-700 border-emerald-200'
                  ]"
                >
                  {{ (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date()) ? 'Closed' : 'Accepting Applications' }}
                </span>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase border',
                    job.status === 'draft' ? 'bg-slate-100 text-slate-700 border-slate-300' :
                    job.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-300' :
                    (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date())
                      ? 'bg-rose-50 text-rose-700 border-rose-200' 
                      : 'bg-emerald-50 text-emerald-700 border-emerald-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="job.status === 'draft' ? 'bg-slate-400' : job.status === 'pending' ? 'bg-amber-500' : (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date()) ? 'bg-rose-500' : 'bg-emerald-500'"></span>
                  {{ job.status === 'draft' ? 'Draft' : job.status === 'pending' ? 'Under Review' : (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date()) ? 'Closed' : 'Open' }}
                </span>
              </td>

              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <button 
                    v-if="isEditor && job.status !== 'open'" 
                    @click="emit('approveItem', job)" 
                    class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                    title="Approve & open vacancy"
                  >
                    Approve
                  </button>
                  <button 
                    v-if="isEditor && job.status === 'open'" 
                    @click="emit('unpublishItem', job)" 
                    class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                    title="Move back to draft"
                  >
                    Unpublish
                  </button>
                  <button v-if="canEditItem(job)" @click="emit('openEdit', job)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg border border-slate-200 cursor-pointer transition" title="Edit Vacancy">
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button v-if="canDeleteItem(job)" @click="emit('deleteItem', job)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg border border-rose-200 cursor-pointer transition" title="Delete Vacancy">
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Vacancies Pagination Controls Bar -->
      <div v-if="filteredVacancies.length > vacanciesPerPage" class="p-3.5 px-6 bg-slate-50/80 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ vacanciesPage }}</span> of <span class="font-bold text-slate-900">{{ totalVacanciesPages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="vacanciesPage === 1" 
            @click="vacanciesPage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalVacanciesPages" 
            :key="p"
            @click="vacanciesPage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              vacanciesPage === p ? 'bg-emerald-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="vacanciesPage === totalVacanciesPages" 
            @click="vacanciesPage++"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>

    <!-- CARD GRID VIEW -->
    <div v-else class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div 
          v-for="job in paginatedVacancies" 
          :key="job.id" 
          class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
          <div>
            <div class="flex items-center justify-between gap-2 mb-3 pt-1">
              <span class="px-2.5 py-0.5 rounded-md bg-slate-100 font-mono text-xs font-black text-slate-800 border border-slate-200">
                {{ job.position_code }}
              </span>
              <span 
                :class="[
                  'px-2 py-0.5 rounded-full text-[10px] font-black uppercase border',
                  job.status === 'draft' ? 'bg-slate-100 text-slate-700 border-slate-300' :
                  job.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-300' :
                  (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date())
                    ? 'bg-rose-50 text-rose-700 border-rose-200' 
                    : 'bg-emerald-50 text-emerald-700 border-emerald-200'
                ]"
              >
                {{ job.status === 'draft' ? 'Draft' : job.status === 'pending' ? 'Under Review' : (job.status === 'closed' || new Date(job.deadline?.includes('T') ? job.deadline : (job.deadline || '') + 'T23:59:59') <= new Date()) ? 'Closed' : 'Open' }}
              </span>
            </div>

            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold uppercase border border-emerald-200 inline-block mb-2">
              {{ job.employment_type }}
            </span>

            <h4 class="font-black text-base text-slate-900 leading-snug line-clamp-2">{{ job.title_en || job.title_am }}</h4>
            <p class="text-xs text-slate-500 font-mono mt-1">AM: {{ job.title_am }}</p>

            <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
              <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-black uppercase block">Scale</span>
                <span class="font-mono font-bold text-slate-900 text-xs truncate block">{{ job.salary_grade || 'Gov Scale' }}</span>
              </div>
              <div class="p-2 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-black uppercase block">Deadline</span>
                <span class="font-mono font-bold text-amber-800 text-xs truncate block">{{ job.deadline }}</span>
              </div>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
              <button 
                v-if="isEditor && job.status !== 'open'" 
                @click="emit('approveItem', job)" 
                class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Approve & open vacancy"
              >
                Approve
              </button>
              <button 
                v-if="isEditor && job.status === 'open'" 
                @click="emit('unpublishItem', job)" 
                class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Move back to draft"
              >
                Unpublish
              </button>
            </div>

            <div class="flex items-center gap-1.5">
              <button v-if="canEditItem(job)" @click="emit('openEdit', job)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl border border-slate-200 cursor-pointer transition" title="Edit Vacancy">
                <Edit class="w-3.5 h-3.5" />
              </button>
              <button v-if="canDeleteItem(job)" @click="emit('deleteItem', job)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 cursor-pointer transition" title="Delete Vacancy">
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Vacancies Pagination Controls Bar (Cards) -->
      <div v-if="filteredVacancies.length > vacanciesPerPage" class="p-4 px-6 bg-white rounded-3xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ vacanciesPage }}</span> of <span class="font-bold text-slate-900">{{ totalVacanciesPages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="vacanciesPage === 1" 
            @click="vacanciesPage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalVacanciesPages" 
            :key="p"
            @click="vacanciesPage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              vacanciesPage === p ? 'bg-emerald-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="vacanciesPage === totalVacanciesPages" 
            @click="vacanciesPage++"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
