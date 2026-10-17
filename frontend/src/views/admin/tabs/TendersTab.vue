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
  AlertCircle
} from 'lucide-vue-next'

const props = defineProps({
  tendersList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem'])

const searchQuery = ref('')
const statusFilter = ref('all')

const filteredTenders = computed(() => {
  return props.tendersList.filter(item => {
    if (statusFilter.value !== 'all' && item.status !== statusFilter.value) return false
    if (searchQuery.value.trim() !== '') {
      const q = searchQuery.value.toLowerCase()
      const refNo = (item.reference_number || '').toLowerCase()
      const titleEn = (item.title_en || '').toLowerCase()
      const titleAm = (item.title_am || '').toLowerCase()
      if (!refNo.includes(q) && !titleEn.includes(q) && !titleAm.includes(q)) return false
    }
    return true
  })
})

function isClosed(item) {
  if (item.status === 'closed') return true
  if (!item.closing_datetime) return false
  return new Date(item.closing_datetime).getTime() <= Date.now()
}
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Finance & Procurement Bureau</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Public Bidding</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Public Tenders & RFPs</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ tendersList.length }} total
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search reference # or title..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none"
          />
        </div>

        <button 
          v-if="isEditor"
          @click="emit('openCreate')" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          <span>Post Tender Notice</span>
        </button>
      </div>
    </div>

    <!-- Tenders Cards Grid (AWS Cloudscape Card Style) -->
    <div v-if="filteredTenders.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
      <FileCheck class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No procurement notices found.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div 
        v-for="item in filteredTenders" 
        :key="item.id"
        class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between"
      >
        <div>
          <!-- Top Tag Strip -->
          <div class="flex items-center justify-between gap-2 mb-3">
            <span class="px-2.5 py-1 rounded bg-slate-100 font-mono text-xs font-bold text-slate-800 border border-slate-200">
              {{ item.reference_number }}
            </span>
            <span 
              :class="[
                'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase',
                !isClosed(item) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'
              ]"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="!isClosed(item) ? 'bg-emerald-500' : 'bg-red-500'"></span>
              {{ !isClosed(item) ? 'Open Bid' : 'Closed' }}
            </span>
          </div>

          <h3 class="font-bold text-sm text-slate-900 leading-snug line-clamp-2">
            {{ item.title_en || item.title_am }}
          </h3>
          <p class="text-[11px] text-slate-500 mt-1 line-clamp-1 font-mono">
            AM: {{ item.title_am }}
          </p>

          <!-- Metrics Strip -->
          <div class="mt-4 pt-3 border-t border-slate-100 grid grid-cols-2 gap-2 text-xs">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase block">Bid Bond</span>
              <span class="font-mono font-bold text-slate-800">
                ETB {{ Number(item.bid_bond_amount || 0).toLocaleString() }}
              </span>
            </div>
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase block">Deadline</span>
              <span class="font-mono text-xs text-amber-700 font-bold">
                {{ item.closing_datetime ? new Date(item.closing_datetime).toLocaleDateString() : 'N/A' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
          <a 
            v-if="item.document_pdf_path" 
            :href="item.document_pdf_path" 
            target="_blank" 
            download
            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700"
          >
            <Download class="w-3.5 h-3.5" />
            <span>Download RFP Spec</span>
          </a>
          <span v-else class="text-[11px] text-slate-400 italic">No document attached</span>

          <div v-if="isEditor" class="flex items-center gap-1.5">
            <button 
              @click="emit('openEdit', item)"
              class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
              title="Edit tender"
            >
              <Edit class="w-3.5 h-3.5" />
            </button>
            <button 
              @click="emit('deleteItem', item.id)"
              class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
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
