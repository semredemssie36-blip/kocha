<script setup>
import { ref, computed, watch } from 'vue'
import { 
  Search, 
  LayoutGrid, 
  List, 
  Plus, 
  BookOpen, 
  Download, 
  Edit, 
  Trash2, 
  ChevronLeft, 
  ChevronRight 
} from 'lucide-vue-next'

const props = defineProps({
  resourcesList: {
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

const resourcesViewMode = ref('table') // 'table' | 'grid'
const resourcesSearch = ref('')
const resourcesPage = ref(1)
const resourcesPerPage = 8

const filteredResources = computed(() => {
  if (!resourcesSearch.value.trim()) return props.resourcesList
  const q = resourcesSearch.value.toLowerCase().trim()
  return props.resourcesList.filter(r =>
    (r.title_en && r.title_en.toLowerCase().includes(q)) ||
    (r.title_am && r.title_am.includes(q)) ||
    (r.category && r.category.toLowerCase().includes(q)) ||
    (r.year && r.year.toString().includes(q))
  )
})

const totalResourcesPages = computed(() => {
  return Math.ceil(filteredResources.value.length / resourcesPerPage) || 1
})

const paginatedResources = computed(() => {
  const start = (resourcesPage.value - 1) * resourcesPerPage
  return filteredResources.value.slice(start, start + resourcesPerPage)
})

watch(resourcesSearch, () => {
  resourcesPage.value = 1
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Public Directives & Document Library</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ filteredResources.length }} documents
          </span>
        </h2>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="resourcesSearch"
            type="text"
            placeholder="Filter by title, year, category..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-xs font-medium"
          />
        </div>

        <!-- View Mode Switcher -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
          <button 
            @click="resourcesViewMode = 'table'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', resourcesViewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Archive Table View"
          >
            <List class="w-4 h-4" />
            <span class="hidden sm:inline">Table</span>
          </button>
          <button 
            @click="resourcesViewMode = 'grid'"
            :class="['p-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer', resourcesViewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800']"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
            <span class="hidden sm:inline">Cards</span>
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Catalog Directive</span>
        </button>
      </div>
    </div>

    <div v-if="filteredResources.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 shadow-xs">
      <BookOpen class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No directives or resources found matching "<span class="font-bold text-slate-900">{{ resourcesSearch }}</span>".</p>
      <p class="text-[11px] text-slate-400 mt-1">Catalog a new directive or adjust your search filter above.</p>
    </div>

    <!-- 1. ARCHIVAL TABLE VIEW -->
    <div v-else-if="resourcesViewMode === 'table'" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Directive Title</th>
              <th class="py-3 px-4">Category</th>
              <th class="py-3 px-4">Year & Size</th>
              <th class="py-3 px-4">Downloads</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="res in paginatedResources" :key="res.id" class="hover:bg-slate-50/70 transition-colors">
              <td class="py-3.5 px-4 max-w-sm">
                <div class="font-bold text-slate-900 text-xs leading-snug">{{ res.title_en || res.title_am }}</div>
                <div class="text-[11px] text-slate-400 truncate mt-0.5 font-mono">AM: {{ res.title_am }}</div>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px] uppercase border border-blue-200">
                  {{ res.category }}
                </span>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap font-mono text-xs">
                <span class="font-bold text-slate-800">{{ res.year }}</span>
                <span class="text-slate-400 ml-1.5 font-normal">({{ res.file_size_formatted || 'PDF' }})</span>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap font-mono">
                <span class="font-bold text-blue-700">{{ res.downloads_count || 0 }}</span>
                <span class="text-[10px] text-slate-400 ml-1">hits</span>
              </td>

              <td class="py-3.5 px-4 whitespace-nowrap">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase border',
                    res.status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="res.status === 'published' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ res.status === 'published' ? 'Live Published' : 'Draft' }}
                </span>
              </td>

              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <a 
                    v-if="res.file_path" 
                    :href="res.file_path" 
                    target="_blank" 
                    download
                    class="p-1.5 rounded-lg border border-slate-200 text-blue-600 hover:bg-blue-50 transition cursor-pointer"
                    title="Download Document"
                  >
                    <Download class="w-3.5 h-3.5" />
                  </a>

                  <button 
                    v-if="isEditor && res.status !== 'published'" 
                    @click="emit('approveItem', res)" 
                    class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                    title="Approve & publish directive"
                  >
                    Approve
                  </button>

                  <button 
                    v-if="isEditor && res.status === 'published'" 
                    @click="emit('unpublishItem', res)" 
                    class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                    title="Move to draft"
                  >
                    Unpublish
                  </button>

                  <button v-if="canEditItem(res)" @click="emit('openEdit', res)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg border border-slate-200 cursor-pointer transition" title="Edit Directive">
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button v-if="canDeleteItem(res)" @click="emit('deleteItem', res)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg border border-rose-200 cursor-pointer transition" title="Delete Directive">
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Resources Pagination Controls Bar (Table) -->
      <div v-if="filteredResources.length > resourcesPerPage" class="p-3.5 px-6 bg-slate-50/80 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ resourcesPage }}</span> of <span class="font-bold text-slate-900">{{ totalResourcesPages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="resourcesPage === 1" 
            @click="resourcesPage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalResourcesPages" 
            :key="p"
            @click="resourcesPage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              resourcesPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="resourcesPage === totalResourcesPages" 
            @click="resourcesPage++"
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
          v-for="res in paginatedResources" 
          :key="res.id" 
          class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
          <div>
            <div class="flex items-center justify-between gap-2 mb-3 pt-1">
              <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase border border-blue-200">
                {{ res.category }}
              </span>
              <span class="text-xs font-mono font-bold text-slate-500">{{ res.year }}</span>
            </div>

            <h4 class="font-black text-base text-slate-900 leading-snug line-clamp-2">{{ res.title_en || res.title_am }}</h4>
            <p class="text-xs text-slate-500 font-mono mt-1">AM: {{ res.title_am }}</p>

            <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs font-mono">
              <span class="text-slate-600">Size: {{ res.file_size_formatted || 'PDF' }}</span>
              <span class="text-blue-700 font-bold">Downloads: {{ res.downloads_count || 0 }}</span>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
            <a 
              v-if="res.file_path" 
              :href="res.file_path" 
              target="_blank" 
              download
              class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:underline"
            >
              <Download class="w-3.5 h-3.5" />
              <span>Download</span>
            </a>
            <span v-else class="text-[11px] text-slate-400 italic">No file</span>

            <div class="flex items-center gap-1.5">
              <button 
                v-if="isEditor && res.status !== 'published'" 
                @click="emit('approveItem', res)" 
                class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Approve & publish directive"
              >
                Approve
              </button>
              <button 
                v-if="isEditor && res.status === 'published'" 
                @click="emit('unpublishItem', res)" 
                class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Move to draft"
              >
                Unpublish
              </button>
              <button v-if="canEditItem(res)" @click="emit('openEdit', res)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl border border-slate-200 cursor-pointer transition" title="Edit Directive">
                <Edit class="w-3.5 h-3.5" />
              </button>
              <button v-if="canDeleteItem(res)" @click="emit('deleteItem', res)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 cursor-pointer transition" title="Delete Directive">
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Resources Pagination Controls Bar (Cards) -->
      <div v-if="filteredResources.length > resourcesPerPage" class="p-4 px-6 bg-white rounded-3xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ resourcesPage }}</span> of <span class="font-bold text-slate-900">{{ totalResourcesPages }}</span>
        </div>
        <div class="flex items-center gap-1">
          <button 
            :disabled="resourcesPage === 1" 
            @click="resourcesPage--"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalResourcesPages" 
            :key="p"
            @click="resourcesPage = p"
            :class="[
              'w-7 h-7 rounded-lg font-bold transition-colors cursor-pointer text-xs',
              resourcesPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="resourcesPage === totalResourcesPages" 
            @click="resourcesPage++"
            class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
