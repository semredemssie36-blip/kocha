<script setup>
import { ref, computed } from 'vue'
import { 
  LayoutGrid, 
  List, 
  Plus, 
  Search, 
  Edit, 
  Trash2 
} from 'lucide-vue-next'

const props = defineProps({
  heroSlidesList: {
    type: Array,
    required: true
  },
  can: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem'])

const heroSlidesViewMode = ref('cards')
const heroSlidesSearch = ref('')

const filteredHeroSlides = computed(() => {
  if (!heroSlidesSearch.value.trim()) return props.heroSlidesList
  const q = heroSlidesSearch.value.toLowerCase().trim()
  return props.heroSlidesList.filter(s => 
    (s.title_en || '').toLowerCase().includes(q) ||
    (s.title_am || '').toLowerCase().includes(q) ||
    (s.tag_en || '').toLowerCase().includes(q) ||
    (s.tag_am || '').toLowerCase().includes(q) ||
    (s.subtitle_en || '').toLowerCase().includes(q) ||
    (s.subtitle_am || '').toLowerCase().includes(q)
  )
})
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Hero Slider Manager</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ heroSlidesList.length }} slides
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Toggle -->
        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl shadow-inner">
          <button 
            type="button"
            @click="heroSlidesViewMode = 'cards'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="heroSlidesViewMode === 'cards' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            type="button"
            @click="heroSlidesViewMode = 'table'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="heroSlidesViewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Sovereign Table View"
          >
            <List class="w-4 h-4" />
          </button>
        </div>

        <button @click="emit('openCreate')" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 shrink-0 transition-all">
          <Plus class="w-4 h-4" />
          <span>Add New Hero Slide</span>
        </button>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="flex items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="relative w-full sm:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="heroSlidesSearch"
          type="text"
          placeholder="Filter slides by title, tag, subtitle..."
          class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
        />
      </div>
      <span class="text-xs text-slate-400 font-mono hidden sm:inline">Order defines autoplay carousel sequence</span>
    </div>

    <div v-if="filteredHeroSlides.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
      No hero slides found matching your search.
    </div>

    <!-- 1. SOVEREIGN TABLE VIEW -->
    <div v-else-if="heroSlidesViewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-black">
              <th class="py-3.5 px-4">Slide Preview & Tag</th>
              <th class="py-3.5 px-4">Title (English & Amharic)</th>
              <th class="py-3.5 px-4">Call-To-Action (CTA)</th>
              <th class="py-3.5 px-4">Order</th>
              <th class="py-3.5 px-4">Status</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans">
            <tr v-for="slide in filteredHeroSlides" :key="slide.id" class="hover:bg-blue-50/40 transition-colors">
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-20 h-12 rounded-xl bg-slate-900 overflow-hidden shrink-0 border border-slate-200 shadow-2xs">
                    <img :src="slide.image_path" :alt="slide.title_en || slide.title_am" class="w-full h-full object-cover" />
                  </div>
                  <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                    {{ slide.tag_en || slide.tag_am || 'General Tag' }}
                  </span>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span class="font-bold text-slate-900 text-sm block leading-snug">{{ slide.title_en || slide.title_am }}</span>
                <span class="text-xs text-slate-500 font-mono">AM: {{ slide.title_am }}</span>
                <p class="text-xs text-slate-400 mt-1 line-clamp-1 max-w-sm">{{ slide.subtitle_en || slide.subtitle_am }}</p>
              </td>
              <td class="py-3.5 px-4">
                <div class="text-xs font-mono text-slate-700">
                  <span class="font-bold text-blue-700">{{ slide.button_text_en || slide.button_text_am || 'Learn More' }}</span>
                  <span class="block text-[11px] text-slate-400 truncate max-w-xs">{{ slide.button_url || '/' }}</span>
                </div>
              </td>
              <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                #{{ slide.order }}
              </td>
              <td class="py-3.5 px-4">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide',
                    slide.is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="slide.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                  {{ slide.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                  <button @click="emit('openEdit', slide)" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg cursor-pointer transition" title="Edit Slide">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button v-if="can.canDelete" @click="emit('deleteItem', slide)" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg cursor-pointer transition" title="Delete Slide">
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
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="slide in filteredHeroSlides" :key="slide.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-lg transition-all flex flex-col justify-between relative">
        <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E] z-10"></div>
        <div>
          <div class="h-48 bg-slate-900 relative overflow-hidden group">
            <img :src="slide.image_path" :alt="slide.title_en || slide.title_am" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-black/70 backdrop-blur-xs text-white text-[11px] font-mono font-bold border border-white/20">
              Order: {{ slide.order }}
            </span>
            <span :class="['absolute top-3 right-3 px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider backdrop-blur-xs border', slide.is_active ? 'bg-emerald-600/90 text-white border-emerald-400/40' : 'bg-slate-700/90 text-slate-300 border-white/20']">
              {{ slide.is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
          <div class="p-6">
            <div class="text-[11px] font-black uppercase tracking-wider text-blue-700 mb-1.5">{{ slide.tag_en || slide.tag_am || 'General Tag' }}</div>
            <h4 class="font-extrabold text-lg text-slate-900 mb-1 leading-snug">{{ slide.title_en || slide.title_am }}</h4>
            <p class="text-xs text-slate-500 font-mono mb-2">Amharic: {{ slide.title_am }}</p>
            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">{{ slide.subtitle_en || slide.subtitle_am }}</p>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-mono">
              <span>CTA: <strong class="text-slate-800">{{ slide.button_text_en || slide.button_text_am }}</strong></span>
              <span class="truncate max-w-[200px] text-blue-600">Link: {{ slide.button_url }}</span>
            </div>
          </div>
        </div>
        <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
          <button @click="emit('openEdit', slide)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl cursor-pointer transition" title="Edit Slide">
            <Edit class="w-4 h-4" />
          </button>
          <button v-if="can.canDelete" @click="emit('deleteItem', slide)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl cursor-pointer transition" title="Delete Slide">
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
