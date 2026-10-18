<script setup>
import { ref, computed } from 'vue'
import { 
  FileText, 
  Search, 
  Plus, 
  CheckCircle2, 
  Clock, 
  AlertCircle, 
  Edit, 
  Trash2, 
  Eye, 
  ExternalLink,
  ChevronLeft,
  ChevronRight
} from 'lucide-vue-next'

const props = defineProps({
  newsList: {
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

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem'])

const searchQuery = ref('')
const activeFilter = ref('all') // all, published, pending, draft
const currentPage = ref(1)
const perPage = 10

const filteredNews = computed(() => {
  return props.newsList.filter(item => {
    // Status filter
    if (activeFilter.value === 'published' && item.status !== 'published') return false
    if (activeFilter.value === 'pending' && item.status !== 'pending') return false
    if (activeFilter.value === 'draft' && item.status !== 'draft') return false

    // Search query
    if (searchQuery.value.trim() !== '') {
      const q = searchQuery.value.toLowerCase()
      const titleEn = (item.title_en || '').toLowerCase()
      const titleAm = (item.title_am || '').toLowerCase()
      const cat = (item.category || '').toLowerCase()
      if (!titleEn.includes(q) && !titleAm.includes(q) && !cat.includes(q)) {
        return false
      }
    }
    return true
  })
})

const totalPages = computed(() => Math.ceil(filteredNews.value.length / perPage) || 1)

const paginatedNews = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredNews.value.slice(start, start + perPage)
})

const publishedCount = computed(() => {
  return props.newsList.filter(n => n.status === 'published').length
})

const pendingCount = computed(() => {
  return props.newsList.filter(n => n.status === 'pending').length
})

const draftCount = computed(() => {
  return props.newsList.filter(n => n.status === 'draft').length
})

function canEdit(item) {
  if (props.isEditor) return true
  // Authors can edit their own drafts/pending items
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status !== 'published'
}

function canDelete(item) {
  if (props.isEditor) return true
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status === 'draft'
}
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>News & Official Dispatches</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ newsList.length }} total
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
            placeholder="Search press releases..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition"
          />
        </div>

        <!-- Create Button -->
        <button 
          @click="emit('openCreate')" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          <span>{{ isAuthor ? 'Submit Article' : 'New Press Release' }}</span>
        </button>
      </div>
    </div>

    <!-- Status Tabs with Counts -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-2">
      <button 
        @click="activeFilter = 'all'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          activeFilter === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>All Articles</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="activeFilter === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-700'">
          {{ newsList.length }}
        </span>
      </button>

      <button 
        @click="activeFilter = 'published'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          activeFilter === 'published' ? 'bg-emerald-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Live Published</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="activeFilter === 'published' ? 'bg-emerald-800 text-emerald-100' : 'bg-emerald-100 text-emerald-800'">
          {{ publishedCount }}
        </span>
      </button>

      <button 
        @click="activeFilter = 'pending'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          activeFilter === 'pending' ? 'bg-amber-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Pending Review</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="activeFilter === 'pending' ? 'bg-amber-700 text-amber-100' : 'bg-amber-100 text-amber-800'">
          {{ pendingCount }}
        </span>
      </button>

      <button 
        @click="activeFilter = 'draft'; currentPage = 1"
        :class="[
          'px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5',
          activeFilter === 'draft' ? 'bg-slate-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100'
        ]"
      >
        <span>Drafts / Unpublished</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="activeFilter === 'draft' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200 text-slate-700'">
          {{ draftCount }}
        </span>
      </button>
    </div>

    <!-- AWS-Style Enterprise Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
      <div v-if="filteredNews.length === 0" class="p-12 text-center text-slate-400">
        <FileText class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
        <p class="text-xs font-bold text-slate-600">No articles found.</p>
        <p class="text-[11px] text-slate-400 mt-1">Try adjusting your filter or search query.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Article</th>
              <th class="py-3 px-4">Category</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4">Author / Date</th>
              <th class="py-3 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="item in paginatedNews" :key="item.id" class="hover:bg-slate-50/70 transition-colors">
              <!-- Article Title & Thumbnail -->
              <td class="py-3 px-4">
                <div class="flex items-center gap-3">
                  <img 
                    :src="item.thumbnail_path || '/assets/hero_industry.jpg'" 
                    class="w-12 h-10 object-cover rounded-lg border border-slate-200 shrink-0 bg-slate-100" 
                  />
                  <div class="min-w-0 max-w-md">
                    <p class="font-bold text-slate-900 truncate text-xs">{{ item.title_en || item.title_am }}</p>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5 font-mono">AM: {{ item.title_am }}</p>
                  </div>
                </div>
              </td>

              <!-- Category -->
              <td class="py-3 px-4">
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[10px] uppercase border border-slate-200">
                  {{ item.category }}
                </span>
              </td>

              <!-- Status Pill with Dot -->
              <td class="py-3 px-4 whitespace-nowrap">
                <span 
                  v-if="item.status === 'published'"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Published
                </span>
                <span 
                  v-else-if="item.status === 'pending'"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                  Pending Review
                </span>
                <span 
                  v-else
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200"
                >
                  <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                  Draft
                </span>
              </td>

              <!-- Author / Date -->
              <td class="py-3 px-4 whitespace-nowrap text-slate-500 text-[11px]">
                <div class="font-bold text-slate-700">{{ item.author?.name || 'Municipal Bureau' }}</div>
                <div class="text-[10px] text-slate-400 font-mono">{{ new Date(item.created_at).toLocaleDateString() }}</div>
              </td>

              <!-- Actions -->
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <!-- Approve button (Editor / Super Admin) -->
                  <button 
                    v-if="isEditor && item.status !== 'published'"
                    @click="emit('approveItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
                    title="Approve and publish live"
                  >
                    Approve
                  </button>

                  <!-- Unpublish button (Editor / Super Admin) -->
                  <button 
                    v-if="isEditor && item.status === 'published'"
                    @click="emit('unpublishItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
                    title="Move back to draft"
                  >
                    Unpublish
                  </button>

                  <!-- Edit button -->
                  <button 
                    v-if="canEdit(item)"
                    @click="emit('openEdit', item)"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    title="Edit article"
                  >
                    <Edit class="w-3.5 h-3.5" />
                  </button>

                  <!-- Delete button -->
                  <button 
                    v-if="canDelete(item)"
                    @click="emit('deleteItem', item.id)"
                    class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    title="Delete article"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
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
  </div>
</template>
