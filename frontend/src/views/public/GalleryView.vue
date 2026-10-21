<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Image as ImageIcon, 
  Eye, 
  X, 
  ZoomIn, 
  Layers,
  Sparkles,
  Camera,
  ExternalLink,
  ChevronLeft,
  ChevronRight,
  Download,
  Video,
  Play
} from 'lucide-vue-next'

const langStore = useLanguageStore()

const activeCategory = ref('all')
const activeItem = ref(null)
const loading = ref(true)
const galleryItems = ref([])

const categories = [
  { key: 'all', label_en: 'All Media', label_am: 'ሁሉንም ሚዲያ', label_om: 'Suuraa Hundaa' },
  { key: 'video', label_en: 'Videos', label_am: 'ቪዲዮዎች', label_om: 'Viidiyoo' },
  { key: 'urban', label_en: 'City Landmarks & Aerial', label_am: 'የከተማ ገጽታና አየር ላይ እይታ', label_om: 'Magaalaa' },
  { key: 'industry', label_en: 'Industry & Logistics', label_am: 'ኢንዱስትሪና ሎጂስቲክስ', label_om: 'Indaastirii' },
  { key: 'nature', label_en: 'Mount Yegof & Nature', label_am: 'ይጎፍ ተራራና ተፈጥሮ', label_om: 'Uumama' },
  { key: 'events', label_en: 'Civic Events & Culture', label_am: 'ክስተቶችና ባህል', label_om: 'Aadaa' }
]

const isYouTube = (url) => url && /(?:youtu\.be\/|youtube\.com)/i.test(url)
const getYouTubeEmbed = (url) => {
  if (!url) return ''
  const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/)
  return match ? `https://www.youtube.com/embed/${match[1]}?autoplay=1` : ''
}

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
    if (activeCategory.value === 'all') return true
    if (activeCategory.value === 'video') return item.media_type === 'video'
    return item.category === activeCategory.value
  })
})

const getTitle = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.caption_am || item.caption_en
  if (lang === 'om') return item.caption_om || item.caption_en || item.caption_am
  return item.caption_en || item.caption_am
}

const getCategoryLabel = (catKey) => {
  const c = categories.find(cat => cat.key === catKey)
  if (!c) return catKey || 'Archive'
  const lang = langStore.currentLang
  return lang === 'am' ? c.label_am : (lang === 'om' ? c.label_om : c.label_en)
}

const activeIndex = computed(() => {
  if (!activeItem.value) return -1
  return filteredItems.value.findIndex(item => item.id === activeItem.value.id)
})

const nextPhoto = () => {
  if (filteredItems.value.length === 0) return
  if (activeIndex.value < filteredItems.value.length - 1) {
    activeItem.value = filteredItems.value[activeIndex.value + 1]
  } else {
    activeItem.value = filteredItems.value[0]
  }
}

const prevPhoto = () => {
  if (filteredItems.value.length === 0) return
  if (activeIndex.value > 0) {
    activeItem.value = filteredItems.value[activeIndex.value - 1]
  } else {
    activeItem.value = filteredItems.value[filteredItems.value.length - 1]
  }
}

const handleKeyDown = (e) => {
  if (!activeItem.value) return
  if (e.key === 'ArrowRight') nextPhoto()
  if (e.key === 'ArrowLeft') prevPhoto()
  if (e.key === 'Escape') activeItem.value = null
}

onMounted(() => {
  fetchGallery()
  window.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown)
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/Green_Legacy.jpg" alt="Gallery Heritage" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-300 text-xs font-black uppercase tracking-wider mb-4">
          <Camera class="w-3.5 h-3.5" />
          <span>{{ langStore.currentLang === 'am' ? 'ይፋዊ የከተማው ፎቶ ማህደር' : 'Official Metropolitan Photo Archives' }}</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ገጽታዎች በፎቶ ማህደር' : 'Metropolitan Photographic & Visual Archives' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ኢንዱስትሪ ፓርክ፣ የደረቅ ወደብ ጉምሩክ ተርሚናል፣ የይጎፍ ተራራ የተፈጥሮ ደንና ህዝባዊ ክንውኖችን የሚያሳዩ ይፋዊ የፎቶ ሰነዶች።'
            : 'Explore high-definition aerial views, industrial corridors, Mount Yegof biodiversity, and civic celebrations across Kombolcha.'
          }}
        </p>

        <!-- Photo Analytics Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Archived Photography</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ galleryItems.length }} Photos</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Curated Themes</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">
              4 Thematic Collections
            </div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Resolution Standard</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">
              Ultra High-Definition
            </div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Civic Authenticity</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Verified Municipal Records</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Category Filter Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button
            v-for="cat in categories"
            :key="cat.key"
            @click="activeCategory = cat.key"
            :class="[
              'px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer whitespace-nowrap shadow-2xs flex items-center gap-2',
              activeCategory === cat.key 
                ? 'bg-blue-900 text-amber-300 ring-2 ring-blue-900/20' 
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            <span>{{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}</span>
            <span 
              :class="[
                'text-[10px] px-1.5 py-0.2 rounded-md font-mono font-bold',
                activeCategory === cat.key ? 'bg-amber-400/20 text-amber-300' : 'bg-slate-200 text-slate-600'
              ]"
            >
              {{ cat.key === 'all' ? galleryItems.length : galleryItems.filter(i => i.category === cat.key).length }}
            </span>
          </button>
        </div>

        <div class="text-xs font-bold text-slate-500 shrink-0 hidden md:block">
          Showing <span class="text-slate-900 font-black">{{ filteredItems.length }}</span> photos
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="n in 8" :key="n" class="bg-white rounded-3xl border border-slate-200 h-72 animate-pulse"></div>
      </div>

      <!-- Photo Grid -->
      <div v-else-if="filteredItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="item in filteredItems"
          :key="item.id"
          class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-amber-400 hover:shadow-2xl transition-all duration-300 group cursor-pointer flex flex-col justify-between"
          @click="activeItem = item"
        >
          <!-- Photo / Video Thumbnail -->
          <div class="h-64 bg-slate-950 relative overflow-hidden flex items-center justify-center">
            <img 
              :src="item.thumbnail_url || item.media_path_or_url" 
              :alt="getTitle(item)" 
              class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 opacity-95" 
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

            <!-- Video Center Play Button Overlay -->
            <div v-if="item.media_type === 'video'" class="absolute inset-0 flex items-center justify-center pointer-events-none">
              <div class="w-14 h-14 rounded-full bg-purple-600/90 text-white flex items-center justify-center shadow-2xl pl-0.5 group-hover:scale-110 transition-transform">
                <Play class="w-7 h-7 fill-white" />
              </div>
            </div>

            <!-- Hover Zoom Overlay Icon for Images -->
            <div v-else class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none bg-slate-950/30">
              <div class="w-12 h-12 rounded-full bg-white/90 text-blue-900 flex items-center justify-center shadow-2xl scale-75 group-hover:scale-100 transition-transform duration-300">
                <ZoomIn class="w-6 h-6" />
              </div>
            </div>

            <!-- Category & Type Tag -->
            <div class="absolute top-3 left-3 flex items-center gap-1.5">
              <span class="px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 border border-amber-400/30 text-[10px] font-black uppercase tracking-wider shadow-md">
                {{ getCategoryLabel(item.category) }}
              </span>
              <span 
                v-if="item.media_type === 'video'"
                class="px-2.5 py-1 rounded-full bg-purple-900/90 text-purple-200 text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-md"
              >
                <Video class="w-3 h-3 text-purple-300" />
                <span>Video</span>
              </span>
            </div>
          </div>

          <!-- Caption Footer -->
          <div class="p-5">
            <h3 class="text-sm font-black text-slate-900 group-hover:text-blue-900 transition-colors line-clamp-2 leading-snug">
              {{ getTitle(item) }}
            </h3>
            <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mt-3 pt-2 border-t border-slate-100">
              <span class="capitalize font-bold text-slate-500 flex items-center gap-1">
                <Video v-if="item.media_type === 'video'" class="w-3.5 h-3.5 text-purple-600" />
                <Camera v-else class="w-3.5 h-3.5 text-slate-400" />
                <span>{{ item.media_type === 'video' ? 'Video Document' : 'Photo' }}</span>
              </span>
              <span class="text-blue-700 font-bold group-hover:translate-x-1 transition-transform">
                {{ item.media_type === 'video' ? 'Play Video ↗' : 'View Photo ↗' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <ImageIcon class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No photos found in this archive category</h3>
        <p class="text-xs text-slate-500 mt-1">Select "All Photos" to browse the entire municipal photo collection.</p>
        <button 
          @click="activeCategory = 'all'" 
          class="mt-4 px-4 py-2 bg-blue-900 text-amber-300 hover:bg-blue-800 text-xs font-bold rounded-xl transition-colors cursor-pointer"
        >
          View All Photos
        </button>
      </div>
    </div>

    <!-- World-Class High-Resolution Photo Lightbox Modal with Next/Prev Navigation -->
    <div 
      v-if="activeItem" 
      class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6"
      @click.self="activeItem = null"
    >
      <!-- Top Action Bar -->
      <div class="flex items-center justify-between text-white z-20 pb-2">
        <div class="flex items-center gap-3">
          <span class="px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/30 text-xs font-black uppercase tracking-wider">
            {{ getCategoryLabel(activeItem.category) }}
          </span>
          <span class="text-xs text-slate-400 font-mono font-bold">
            Photo {{ activeIndex + 1 }} of {{ filteredItems.length }}
          </span>
        </div>

        <div class="flex items-center gap-2">
          <a 
            :href="activeItem.media_path_or_url" 
            target="_blank" 
            rel="noopener noreferrer" 
            class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold flex items-center gap-1.5 transition-colors cursor-pointer"
            title="Open Original Image"
          >
            <ExternalLink class="w-3.5 h-3.5" />
            <span class="hidden sm:inline">Original</span>
          </a>
          <button 
            @click="activeItem = null" 
            class="w-10 h-10 rounded-full bg-slate-800 hover:bg-rose-600 text-white flex items-center justify-center cursor-pointer transition-colors shadow-lg"
            title="Close (Esc)"
          >
            <X class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Center Lightbox Photo Container with Left / Right Navigation -->
      <div class="relative flex-1 flex items-center justify-center my-auto overflow-hidden">
        <!-- Previous Photo Button -->
        <button 
          v-if="filteredItems.length > 1"
          @click.stop="prevPhoto" 
          class="absolute left-2 sm:left-4 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-blue-600 text-white flex items-center justify-center cursor-pointer transition-all shadow-2xl backdrop-blur-sm group"
          title="Previous (Left Arrow)"
        >
          <ChevronLeft class="w-6 h-6 group-hover:-translate-x-0.5 transition-transform" />
        </button>

        <!-- Video Player Display -->
        <div 
          v-if="activeItem.media_type === 'video'" 
          class="w-full max-w-4xl aspect-video rounded-2xl overflow-hidden shadow-2xl bg-black flex items-center justify-center animate-fadeIn"
        >
          <iframe 
            v-if="isYouTube(activeItem.media_path_or_url)" 
            :src="getYouTubeEmbed(activeItem.media_path_or_url)" 
            class="w-full h-full" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen
          ></iframe>
          <video 
            v-else 
            :src="activeItem.media_path_or_url" 
            controls 
            autoplay 
            class="w-full h-full object-contain"
          ></video>
        </div>

        <!-- Main Photo Display -->
        <img 
          v-else
          :src="activeItem.media_path_or_url" 
          :alt="getTitle(activeItem)" 
          class="max-h-[75vh] max-w-[92vw] object-contain rounded-2xl shadow-2xl animate-fadeIn select-none"
        />

        <!-- Next Photo Button -->
        <button 
          v-if="filteredItems.length > 1"
          @click.stop="nextPhoto" 
          class="absolute right-2 sm:right-4 z-30 w-12 h-12 rounded-full bg-black/60 hover:bg-blue-600 text-white flex items-center justify-center cursor-pointer transition-all shadow-2xl backdrop-blur-sm group"
          title="Next (Right Arrow)"
        >
          <ChevronRight class="w-6 h-6 group-hover:translate-x-0.5 transition-transform" />
        </button>
      </div>

      <!-- Bottom Caption Bar -->
      <div class="bg-slate-900/90 backdrop-blur-md border border-slate-800 rounded-2xl p-4 sm:p-5 max-w-4xl mx-auto w-full text-white mt-3 shadow-xl">
        <h3 class="text-base sm:text-lg font-black text-white leading-snug">
          {{ getTitle(activeItem) }}
        </h3>
        <div v-if="activeItem.caption_en && activeItem.caption_am && langStore.currentLang === 'am'" class="text-xs text-slate-400 mt-1">
          {{ activeItem.caption_en }}
        </div>
      </div>
    </div>
  </div>
</template>
