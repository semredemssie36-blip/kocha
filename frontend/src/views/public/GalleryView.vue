<script setup>
import { ref, onMounted, computed } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Image as ImageIcon, 
  Video, 
  Eye, 
  X, 
  ZoomIn, 
  Play, 
  Film, 
  MapPin, 
  Layers,
  Sparkles,
  Camera,
  ExternalLink
} from 'lucide-vue-next'

const langStore = useLanguageStore()

const activeCategory = ref('all')
const activeMediaType = ref('all')
const activeItem = ref(null)
const loading = ref(true)
const galleryItems = ref([])

const categories = [
  { key: 'all', label_en: 'All Media', label_am: 'ሁሉንም ምስሎች', label_om: 'Hundaa' },
  { key: 'industry', label_en: 'Industry & Logistics', label_am: 'ኢንዱስትሪና ሎጂስቲክስ', label_om: 'Indaastirii' },
  { key: 'urban', label_en: 'City Landmarks & Aerial', label_am: 'የከተማ ገጽታና አየር ላይ እይታ', label_om: 'Magaalaa' },
  { key: 'nature', label_en: 'Mount Yegof & Nature', label_am: 'ይጎፍ ተራራና ተፈጥሮ', label_om: 'Uumama' },
  { key: 'events', label_en: 'Civic Events & Culture', label_am: 'ክስተቶችና ባህል', label_om: 'Aadaa' }
]

const fetchGallery = async () => {
  loading.value = true
  try {
    const res = await api.get('/gallery')
    galleryItems.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load gallery items:', err)
  } finally {
    loading.value = false
  }
}

const filteredItems = computed(() => {
  return galleryItems.value.filter(item => {
    const matchesCategory = activeCategory.value === 'all' || item.category === activeCategory.value
    const matchesType = activeMediaType.value === 'all' || item.media_type === activeMediaType.value
    return matchesCategory && matchesType
  })
})

const getTitle = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.caption_am || item.caption_en
  if (lang === 'om') return item.caption_om || item.caption_en || item.caption_am
  return item.caption_en || item.caption_am
}

const isYouTubeUrl = (url) => {
  return url && (url.includes('youtube.com') || url.includes('youtu.be'))
}

const getEmbedUrl = (url) => {
  if (!url) return ''
  if (url.includes('embed')) return url
  if (url.includes('watch?v=')) {
    return url.replace('watch?v=', 'embed/')
  }
  if (url.includes('youtu.be/')) {
    const id = url.split('youtu.be/')[1]?.split('?')[0]
    return `https://www.youtube.com/embed/${id}`
  }
  return url
}

onMounted(() => {
  fetchGallery()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/Green_Legacy.jpg" alt="Gallery Heritage" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ገጽታዎች በፎቶና በቪዲዮ' : 'Metropolitan Visual & Documentary Archives' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ኢንዱስትሪ ፓርክ፣ የደረቅ ወደብ ጉምሩክ ተርሚናል፣ የይጎፍ ተራራ የተፈጥሮ ደንና ህዝባዊ ክንውኖችን የሚያሳዩ ይፋዊ የፎቶና ቪዲዮ ሰነዶች።'
            : 'Explore high-definition aerial views, industrial corridors, Mount Yegof biodiversity, and civic celebrations across Kombolcha.'
          }}
        </p>

        <!-- Media Analytics Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Archived Visual Records</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ galleryItems.length }} Media</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Photography Library</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">
              {{ galleryItems.filter(i => i.media_type === 'image').length }} Photos
            </div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Documentary Videos</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">
              {{ galleryItems.filter(i => i.media_type === 'video').length }} Films
            </div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Resolution Standard</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Ultra High-Definition</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Switcher Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button
            v-for="cat in categories"
            :key="cat.key"
            @click="activeCategory = cat.key"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer whitespace-nowrap shadow-2xs',
              activeCategory === cat.key 
                ? 'bg-blue-900 text-amber-300 ring-2 ring-blue-900/20' 
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
          </button>
        </div>

        <!-- Format Switcher -->
        <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold shrink-0">
          <button 
            @click="activeMediaType = 'all'" 
            :class="activeMediaType === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
          >
            All Format
          </button>
          <button 
            @click="activeMediaType = 'image'" 
            :class="activeMediaType === 'image' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
          >
            <Camera class="w-3.5 h-3.5" />
            Photos
          </button>
          <button 
            @click="activeMediaType = 'video'" 
            :class="activeMediaType === 'video' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
          >
            <Video class="w-3.5 h-3.5 text-red-500" />
            Videos
          </button>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="n in 8" :key="n" class="bg-white rounded-3xl border border-slate-200 h-72 animate-pulse"></div>
      </div>

      <!-- Media Grid -->
      <div v-else-if="filteredItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="item in filteredItems"
          :key="item.id"
          class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-amber-400 hover:shadow-2xl transition-all duration-300 group cursor-pointer flex flex-col justify-between"
          @click="activeItem = item"
        >
          <!-- Media Thumbnail -->
          <div class="h-64 bg-slate-900 relative overflow-hidden flex items-center justify-center">
            <img 
              :src="item.thumbnail_url || item.media_path_or_url" 
              :alt="getTitle(item)" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-95"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

            <!-- Video Play Badge Overlay -->
            <div 
              v-if="item.media_type === 'video'" 
              class="absolute inset-0 flex items-center justify-center pointer-events-none"
            >
              <div class="w-14 h-14 rounded-full bg-red-600/90 text-white flex items-center justify-center shadow-2xl group-hover:scale-115 transition-transform duration-300">
                <Play class="w-6 h-6 fill-current ml-0.5" />
              </div>
            </div>

            <!-- Category Tag -->
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 border border-amber-400/30 text-[10px] font-black uppercase tracking-wider shadow-md">
              {{ item.category || 'Archive' }}
            </span>
          </div>

          <!-- Caption Footer -->
          <div class="p-5">
            <h3 class="text-sm font-black text-slate-900 group-hover:text-blue-900 transition-colors line-clamp-2 leading-snug">
              {{ getTitle(item) }}
            </h3>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mt-3 pt-2 border-t border-slate-100">
              <span class="capitalize font-bold text-slate-500">{{ item.media_type }}</span>
              <span class="text-blue-700 font-bold group-hover:translate-x-1 transition-transform">Preview ↗</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Film class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No media found in this archive category</h3>
        <p class="text-xs text-slate-500 mt-1">Select "All Media" to browse the entire documentary archive.</p>
        <button 
          @click="activeCategory = 'all'; activeMediaType = 'all'" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          View All Media
        </button>
      </div>
    </div>

    <!-- Lightbox / Video Modal -->
    <div 
      v-if="activeItem" 
      class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-8"
      @click.self="activeItem = null"
    >
      <div class="bg-slate-900 border border-slate-700/80 rounded-3xl max-w-4xl w-full overflow-hidden shadow-2xl relative text-white animate-fadeIn">
        <!-- Close Button -->
        <button 
          @click="activeItem = null" 
          class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center cursor-pointer transition-colors"
        >
          <X class="w-5 h-5" />
        </button>

        <!-- Media Display -->
        <div class="max-h-[70vh] bg-black flex items-center justify-center overflow-hidden">
          <template v-if="activeItem.media_type === 'video'">
            <iframe 
              v-if="isYouTubeUrl(activeItem.media_path_or_url)"
              :src="getEmbedUrl(activeItem.media_path_or_url)" 
              class="w-full aspect-video" 
              frameborder="0" 
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen
            ></iframe>
            <video 
              v-else 
              :src="activeItem.media_path_or_url" 
              controls 
              autoplay 
              class="max-h-[70vh] max-w-full"
            ></video>
          </template>
          <template v-else>
            <img 
              :src="activeItem.media_path_or_url" 
              :alt="getTitle(activeItem)" 
              class="max-h-[70vh] max-w-full object-contain"
            />
          </template>
        </div>

        <!-- Caption Strip -->
        <div class="p-6 bg-slate-950 border-t border-slate-800 flex items-center justify-between gap-4">
          <div>
            <span class="text-[10px] text-amber-400 font-black uppercase tracking-wider block mb-1">
              {{ activeItem.category || 'Archive Record' }}
            </span>
            <h3 class="text-base font-bold text-white">{{ getTitle(activeItem) }}</h3>
          </div>
          <span class="text-xs text-slate-400 font-mono font-bold uppercase shrink-0">
            {{ activeItem.media_type }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
