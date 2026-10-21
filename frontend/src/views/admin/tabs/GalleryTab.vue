<script setup>
import { ref, computed } from 'vue'
import { 
  LayoutGrid, 
  List, 
  Plus, 
  Search, 
  Edit, 
  Trash2, 
  Image as ImageIcon,
  Video
} from 'lucide-vue-next'

const props = defineProps({
  galleryList: {
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

const galleryViewMode = ref('grid')
const gallerySearch = ref('')
const galleryCategoryFilter = ref('all')
const galleryPage = ref(1)
const galleryPerPage = 12

const filteredGallery = computed(() => {
  return props.galleryList.filter(item => {
    if (galleryCategoryFilter.value !== 'all' && item.category !== galleryCategoryFilter.value) {
      return false
    }
    if (gallerySearch.value.trim() !== '') {
      const q = gallerySearch.value.toLowerCase().trim()
      const capEn = (item.caption_en || '').toLowerCase()
      const capAm = (item.caption_am || '').toLowerCase()
      const url = (item.media_path_or_url || '').toLowerCase()
      if (!capEn.includes(q) && !capAm.includes(q) && !url.includes(q)) {
        return false
      }
    }
    return true
  })
})

const totalGalleryPages = computed(() => {
  return Math.ceil(filteredGallery.value.length / galleryPerPage) || 1
})

const paginatedGallery = computed(() => {
  const start = (galleryPage.value - 1) * galleryPerPage
  return filteredGallery.value.slice(start, start + galleryPerPage)
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Photo Gallery Archive</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ filteredGallery.length }} photos
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Toggle -->
        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl shadow-inner">
          <button 
            type="button"
            @click="galleryViewMode = 'grid'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="galleryViewMode === 'grid' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            type="button"
            @click="galleryViewMode = 'table'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="galleryViewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Sovereign Table View"
          >
            <List class="w-4 h-4" />
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Add Photos</span>
        </button>
      </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="relative w-full sm:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="gallerySearch"
          type="text"
          placeholder="Filter gallery caption, category..."
          class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
        />
      </div>

      <div class="flex items-center gap-2 w-full sm:w-auto">
        <select 
          v-model="galleryCategoryFilter"
          class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 outline-none focus:border-blue-600 cursor-pointer"
        >
          <option value="all">All Categories</option>
          <option value="urban">Urban & Infrastructure</option>
          <option value="heritage">Heritage & Culture</option>
          <option value="events">Civic Events</option>
          <option value="industry">Industrial & Logistics</option>
          <option value="nature">Nature & Yegof</option>
        </select>
      </div>
    </div>

    <div v-if="filteredGallery.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
      No media items found matching "<span class="font-bold text-slate-600">{{ gallerySearch }}</span>".
    </div>

    <!-- 1. SOVEREIGN TABLE VIEW -->
    <div v-else-if="galleryViewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-black">
              <th class="py-3.5 px-4">Preview & Type</th>
              <th class="py-3.5 px-4">Caption (English & Amharic)</th>
              <th class="py-3.5 px-4">Category</th>
              <th class="py-3.5 px-4">Order</th>
              <th class="py-3.5 px-4">Status</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans">
            <tr v-for="item in paginatedGallery" :key="item.id" class="hover:bg-blue-50/40 transition-colors">
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-16 h-12 rounded-xl bg-slate-900 overflow-hidden shrink-0 border border-slate-200 shadow-2xs relative">
                    <img :src="item.thumbnail_url || item.media_path_or_url" :alt="item.caption_en || item.caption_am" class="w-full h-full object-cover" />
                    <span 
                      class="absolute bottom-0.5 right-0.5 px-1 py-0.2 rounded text-[8px] font-bold uppercase text-white"
                      :class="item.media_type === 'video' ? 'bg-purple-900/90' : 'bg-blue-900/90'"
                    >
                      {{ item.media_type === 'video' ? 'Video' : 'Photo' }}
                    </span>
                  </div>
                  <span class="text-[11px] font-mono text-slate-500 truncate max-w-[140px]">{{ item.media_path_or_url }}</span>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span class="font-bold text-slate-900 text-sm block leading-snug">{{ item.caption_en || item.caption_am }}</span>
                <span class="text-xs text-slate-500 font-mono">AM: {{ item.caption_am }}</span>
              </td>
              <td class="py-3.5 px-4">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                  {{ item.category }}
                </span>
              </td>
              <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                #{{ item.order }}
              </td>
              <td class="py-3.5 px-4">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide',
                    item.status === 'published' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'published' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ item.status || 'draft' }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                  <button 
                    v-if="isEditor && item.status !== 'published'" 
                    @click="emit('approveItem', item)" 
                    class="px-2 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider transition cursor-pointer"
                  >
                    Approve
                  </button>
                  <button 
                    v-if="isEditor && item.status === 'published'" 
                    @click="emit('unpublishItem', item)" 
                    class="px-2 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-black uppercase tracking-wider transition cursor-pointer"
                  >
                    Unpublish
                  </button>
                  <button v-if="canEditItem(item)" @click="emit('openEdit', item)" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg cursor-pointer transition" title="Edit Item">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button v-if="canDeleteItem(item)" @click="emit('deleteItem', item)" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg cursor-pointer transition" title="Delete Item">
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
    <div v-else class="space-y-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="item in paginatedGallery" :key="item.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-lg transition-all flex flex-col justify-between relative">
          <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E] z-10"></div>
          <div>
            <div class="h-44 bg-slate-950 relative overflow-hidden flex items-center justify-center group">
              <img :src="item.thumbnail_url || item.media_path_or_url" :alt="item.caption_en || item.caption_am" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <span 
                class="absolute top-3 left-3 px-2 py-0.5 rounded text-[10px] font-bold uppercase flex items-center gap-1 text-white shadow-xs"
                :class="item.media_type === 'video' ? 'bg-purple-900/90' : 'bg-slate-900/80'"
              >
                <Video v-if="item.media_type === 'video'" class="w-3 h-3 text-purple-300" />
                <ImageIcon v-else class="w-3 h-3 text-amber-400" />
                <span>{{ item.media_type === 'video' ? 'Video' : 'Photo' }}</span>
              </span>
              <span class="absolute top-3 right-3 px-2 py-0.5 rounded bg-white/90 text-slate-800 text-[10px] font-bold uppercase">
                {{ item.category }}
              </span>
            </div>
            <div class="p-4">
              <h4 class="font-bold text-sm text-slate-900 line-clamp-2 leading-snug mb-1">{{ item.caption_en || item.caption_am }}</h4>
              <p class="text-xs text-slate-500 font-mono mt-0.5">Amharic: {{ item.caption_am }}</p>
              <p class="text-[11px] text-slate-400 truncate mt-2 font-mono">Src: {{ item.media_path_or_url }}</p>
            </div>
          </div>
          <div class="p-3 px-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-400 font-mono">Order: {{ item.order }}</span>
            <div class="flex items-center gap-1">
              <button 
                v-if="isEditor && item.status !== 'published'" 
                @click="emit('approveItem', item)" 
                class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition"
                title="Approve & publish media"
              >
                Approve
              </button>
              <button 
                v-if="isEditor && item.status === 'published'" 
                @click="emit('unpublishItem', item)" 
                class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition"
                title="Move to draft"
              >
                Unpublish
              </button>
              <button v-if="canEditItem(item)" @click="emit('openEdit', item)" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg cursor-pointer" title="Edit Item">
                <Edit class="w-4 h-4" />
              </button>
              <button v-if="canDeleteItem(item)" @click="emit('deleteItem', item)" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg cursor-pointer" title="Delete Item">
                <Trash2 class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery Pagination Controls Bar -->
      <div v-if="filteredGallery.length > galleryPerPage" class="p-4 px-6 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing <span class="font-bold text-slate-900">{{ (galleryPage - 1) * galleryPerPage + 1 }}</span> to 
          <span class="font-bold text-slate-900">{{ Math.min(galleryPage * galleryPerPage, filteredGallery.length) }}</span> of 
          <span class="font-bold text-slate-900">{{ filteredGallery.length }}</span> items
        </div>
        <div class="flex items-center gap-1.5">
          <button 
            :disabled="galleryPage === 1" 
            @click="galleryPage--"
            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            Previous
          </button>
          <button 
            v-for="p in totalGalleryPages" 
            :key="p"
            @click="galleryPage = p"
            :class="[
              'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
              galleryPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="galleryPage === totalGalleryPages" 
            @click="galleryPage++"
            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
