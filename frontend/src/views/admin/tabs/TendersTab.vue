<script setup>
import { ref, computed } from 'vue'
import { 
  FileCheck, 
  Search, 
  Plus, 
  Download, 
  Clock, 
  ShieldCheck, 
  Edit, 
  Trash2,
  AlertCircle,
  LayoutGrid,
  List,
  ChevronLeft,
  ChevronRight,
  ExternalLink
} from 'lucide-vue-next'

const props = defineProps({
  tendersList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  },
  isAuthor: {
    type: Boolean,
    default: false
  },
  currentUserId: {
    type: [Number, String],
    default: null
  }
})

function canEdit(item) {
  if (props.isEditor) return true
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status !== 'open' && item.status !== 'closed'
}

function canDelete(item) {
  if (props.isEditor) return true
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status === 'draft'
}

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem'])

const searchQuery = ref('')
const statusFilter = ref('all')
const viewMode = ref('grid') // 'grid' | 'table'
const currentPage = ref(1)
const perPage = 10

function isClosed(item) {
  if (item.status === 'closed') return true
  if (!item.closing_datetime) return false
  return new Date(item.closing_datetime).getTime() <= Date.now()
}

const filteredTenders = computed(() => {
  return props.tendersList.filter(item => {
    if (statusFilter.value === 'open' && (item.status !== 'open' || isClosed(item))) return false
    if (statusFilter.value === 'pending' && item.status !== 'pending') return false
    if (statusFilter.value === 'draft' && item.status !== 'draft') return false
    if (statusFilter.value === 'closed' && !isClosed(item) && item.status !== 'closed') return false

    if (searchQuery.value.trim() !== '') {
      const q = searchQuery.value.toLowerCase()
      const refNo = (item.reference_number || '').toLowerCase()
      const titleEn = (item.title_en || '').toLowerCase()
      const titleAm = (item.title_am || '').toLowerCase()
      const cat = (item.category || '').toLowerCase()
      if (!refNo.includes(q) && !titleEn.includes(q) && !titleAm.includes(q) && !cat.includes(q)) return false
    }
    return true
  })
})

const totalPages = computed(() => Math.ceil(filteredTenders.value.length / perPage) || 1)
const paginatedTenders = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredTenders.value.slice(start, start + perPage)
})

const openCount = computed(() => props.tendersList.filter(t => t.status === 'open' && !isClosed(t)).length)
const pendingCount = computed(() => props.tendersList.filter(t => t.status === 'pending').length)
const draftCount = computed(() => props.tendersList.filter(t => t.status === 'draft').length)
const closedCount = computed(() => props.tendersList.filter(t => isClosed(t) || t.status === 'closed').length)
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Public Tenders & RFPs</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ tendersList.length }} total
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- Search Field -->
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search reference # or title..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none"
          />
        </div>

        <!-- View Mode Switcher -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
          <button 
            @click="viewMode = 'grid'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', viewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
            <span class="hidden sm:inline">Cards</span>
          </button>
          <button 
            @click="viewMode = 'table'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', viewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Table View"
          >
            <List class="w-4 h-4" />
            <span class="hidden sm:inline">Table</span>
          </button>
        </div>

        <button 
          v-if="isEditor || isAuthor"
          @click="emit('openCreate')" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          <span>Post Tender Notice</span>
        </button>
      </div>
    </div>

    <!-- Status Tabs with Real Counts -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-2">
      <button 
        @click="statusFilter = 'all'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          statusFilter === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>All Tenders</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-700'">
          {{ tendersList.length }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'open'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          statusFilter === 'open' ? 'bg-emerald-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Active Bids</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'open' ? 'bg-emerald-800 text-emerald-100' : 'bg-emerald-100 text-emerald-800'">
          {{ openCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'pending'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Under Review</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'pending' ? 'bg-amber-700 text-amber-100' : 'bg-amber-100 text-amber-800'">
          {{ pendingCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'draft'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          statusFilter === 'draft' ? 'bg-slate-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Drafts</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'draft' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200 text-slate-700'">
          {{ draftCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'closed'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          statusFilter === 'closed' ? 'bg-rose-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Closed / Expired</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'closed' ? 'bg-rose-800 text-rose-100' : 'bg-rose-100 text-rose-800'">
          {{ closedCount }}
        </span>
      </button>
    </div>

    <!-- Empty State -->
    <div v-if="filteredTenders.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 shadow-xs">
      <FileCheck class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No procurement notices found.</p>
      <p class="text-[11px] text-slate-400 mt-1">Try adjusting your search query or status filter.</p>
    </div>

    <!-- 1. HIGH-DENSITY SOVEREIGN TABLE VIEW -->
    <div v-else-if="viewMode === 'table'" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Ref # & Category</th>
              <th class="py-3 px-4">Tender Title</th>
              <th class="py-3 px-4">Bid Bond</th>
              <th class="py-3 px-4">Closing Deadline</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="item in paginatedTenders" :key="item.id" class="hover:bg-slate-50/70 transition-colors">
              <td class="py-3 px-4 whitespace-nowrap">
                <div class="font-mono font-bold text-slate-900 text-xs">{{ item.reference_number }}</div>
                <span class="inline-block px-2 py-0.5 mt-1 rounded bg-slate-100 text-slate-700 font-bold text-[10px] uppercase border border-slate-200">
                  {{ item.category || 'General' }}
                </span>
              </td>

              <td class="py-3 px-4 max-w-sm">
                <div class="font-bold text-slate-900 text-xs line-clamp-1">{{ item.title_en || item.title_am }}</div>
                <div class="text-[11px] text-slate-400 truncate mt-0.5 font-mono">AM: {{ item.title_am }}</div>
              </td>

              <td class="py-3 px-4 whitespace-nowrap font-mono">
                <span class="font-bold text-slate-800">ETB {{ Number(item.bid_bond_amount || 0).toLocaleString() }}</span>
              </td>

              <td class="py-3 px-4 whitespace-nowrap">
                <div class="font-mono text-xs text-slate-800 font-semibold">
                  {{ item.closing_datetime ? new Date(item.closing_datetime).toLocaleDateString() : 'N/A' }}
                </div>
                <span 
                  v-if="!isClosed(item)"
                  class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                >
                  Active Notice
                </span>
                <span 
                  v-else
                  class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200"
                >
                  Closed
                </span>
              </td>

              <td class="py-3 px-4 whitespace-nowrap">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border',
                    item.status === 'draft' ? 'bg-slate-100 text-slate-700 border-slate-300' :
                    item.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-300' :
                    !isClosed(item) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'draft' ? 'bg-slate-400' : item.status === 'pending' ? 'bg-amber-500' : !isClosed(item) ? 'bg-emerald-500' : 'bg-red-500'"></span>
                  {{ item.status === 'draft' ? 'Draft' : item.status === 'pending' ? 'Under Review' : !isClosed(item) ? 'Open Bid' : 'Closed' }}
                </span>
              </td>

              <td class="py-3 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <a 
                    v-if="item.document_pdf_path" 
                    :href="item.document_pdf_path" 
                    target="_blank" 
                    download
                    class="p-1.5 rounded-lg border border-slate-200 text-blue-600 hover:bg-blue-50 transition cursor-pointer"
                    title="Download RFP PDF"
                  >
                    <Download class="w-3.5 h-3.5" />
                  </a>

                  <button 
                    v-if="isEditor && item.status !== 'open'"
                    @click="emit('approveItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
                    title="Approve and open procurement bid live"
                  >
                    Approve
                  </button>

                  <button 
                    v-if="isEditor && item.status === 'open'"
                    @click="emit('unpublishItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
                    title="Move back to draft"
                  >
                    Unpublish
                  </button>

                  <button 
                    v-if="canEdit(item)"
                    @click="emit('openEdit', item)"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    title="Edit tender"
                  >
                    <Edit class="w-3.5 h-3.5" />
                  </button>

                  <button 
                    v-if="canDelete(item)"
                    @click="emit('deleteItem', item.id)"
                    class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    title="Delete tender"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="p-3.5 px-4 bg-slate-50/70 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ currentPage }}</span> of <span class="font-bold text-slate-900">{{ totalPages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            @click="currentPage = Math.max(1, currentPage - 1)" 
            :disabled="currentPage === 1"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            @click="currentPage = Math.min(totalPages, currentPage + 1)" 
            :disabled="currentPage === totalPages"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>

    <!-- 2. SOVEREIGN CARD GRID VIEW -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div 
        v-for="item in filteredTenders" 
        :key="item.id"
        class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
      >
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-amber-500 via-blue-900 to-emerald-600"></div>
        <div>
          <!-- Top Tag Strip -->
          <div class="flex items-center justify-between gap-2 mb-3 pt-1">
            <span class="px-2.5 py-1 rounded-md bg-slate-100 font-mono text-xs font-black text-slate-800 border border-slate-200">
              {{ item.reference_number }}
            </span>
            <span 
              :class="[
                'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border',
                item.status === 'draft' ? 'bg-slate-100 text-slate-700 border-slate-300' :
                item.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-300' :
                !isClosed(item) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'
              ]"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'draft' ? 'bg-slate-400' : item.status === 'pending' ? 'bg-amber-500' : !isClosed(item) ? 'bg-emerald-500' : 'bg-red-500'"></span>
              {{ item.status === 'draft' ? 'Draft' : item.status === 'pending' ? 'Under Review' : !isClosed(item) ? 'Open Bid' : 'Closed' }}
            </span>
          </div>

          <h3 class="font-black text-base text-slate-900 leading-snug line-clamp-2">
            {{ item.title_en || item.title_am }}
          </h3>
          <p class="text-xs text-slate-500 mt-1 line-clamp-1 font-mono">
            AM: {{ item.title_am }}
          </p>

          <!-- Metrics Strip -->
          <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs">
            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
              <span class="text-[10px] text-slate-400 font-black uppercase block">Bid Security Bond</span>
              <span class="font-mono font-black text-slate-900 text-sm">
                ETB {{ Number(item.bid_bond_amount || 0).toLocaleString() }}
              </span>
            </div>
            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
              <span class="text-[10px] text-slate-400 font-black uppercase block">Closing Deadline</span>
              <span class="font-mono text-xs text-amber-800 font-bold block truncate">
                {{ item.closing_datetime ? new Date(item.closing_datetime).toLocaleDateString() : 'N/A' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
          <a 
            v-if="item.document_pdf_path" 
            :href="item.document_pdf_path" 
            target="_blank" 
            download
            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700"
          >
            <Download class="w-3.5 h-3.5" />
            <span>Download RFP (PDF)</span>
          </a>
          <span v-else class="text-[11px] text-slate-400 italic">No document attached</span>

          <div class="flex items-center gap-1.5">
            <!-- Editorial Approve / Unpublish Buttons -->
            <button 
              v-if="isEditor && item.status !== 'open'"
              @click="emit('approveItem', item)"
              class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-black transition shadow-2xs cursor-pointer"
              title="Approve and open procurement bid live"
            >
              Approve
            </button>
            <button 
              v-if="isEditor && item.status === 'open'"
              @click="emit('unpublishItem', item)"
              class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-black transition shadow-2xs cursor-pointer"
              title="Close or unpublish tender"
            >
              Unpublish
            </button>

            <button 
              v-if="canEdit(item)"
              @click="emit('openEdit', item)"
              class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
              title="Edit tender"
            >
              <Edit class="w-3.5 h-3.5" />
            </button>
            <button 
              v-if="canDelete(item)"
              @click="emit('deleteItem', item.id)"
              class="p-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
              title="Delete tender"
            >
              <Trash2 class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
