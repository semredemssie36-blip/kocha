<script setup>
import { ref, onMounted, computed } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { Image as ImageIcon, Video, Eye, X, ZoomIn, Play, Film, MapPin, Layers } from 'lucide-vue-next'

const langStore = useLanguageStore()

const activeCategory = ref('all')
const activeMediaType = ref('all')
const activeItem = ref(null)
const loading = ref(true)
const galleryItems = ref([])

const categories = [
  { key: 'all', label_en: 'All Media', label_am: 'ሁሉንም', label_om: 'Hundaa' },
  { key: 'industry', label_en: 'Industry & Logistics', label_am: 'ኢንዱስትሪና ሎጂስቲክስ', label_om: 'Indaastirii' },
  { key: 'urban', label_en: 'City Landmarks & Aerial', label_am: 'የከተማ ገጽታና አየር ላይ እይታ', label_om: 'Magaalaa' },
  { key: 'nature', label_en: 'Mount Yegof & Nature', label_am: 'ይጎፍ ተራራና ተፈጥሮ', label_om: 'Uumama' },
  { key: 'events', label_en: 'Civic Events & Culture', label_am: 'ክስተቶችና ባህል', label_om: 'Aadaa' }
]

const fetchGallery = async () => {
  loading.value = true
  try {
    const res = await api.get('/gallery')
    galleryItems.value = res.data || []
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
  <div class="w-full bg-white py-10 px-4 sm:px-8 xl:px-12 min-h-screen">
    <div class="max-w-[1700px] mx-auto">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium">
        <router-link to="/" class="hover:text-[#0b3c5d] transition-colors">Home</router-link>
        <span>/</span>
        <span class="text-slate-800 font-bold">{{ langStore.currentLang === 'am' ? 'የፎቶና ቪዲዮ ማዕከል' : 'Media Gallery' }}</span>
      </nav>

      <!-- Header Banner -->
      <div class="bg-white border-b border-slate-200 pb-8 mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Film class="w-3.5 h-3.5 text-[#0b3c5d]" />
          {{ langStore.currentLang === 'am' ? 'ይፋዊ የፎቶና ቪዲዮ ማዕከል' : 'Official Photo & Video Gallery' }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ገጽታዎች በምስልና በቪዲዮ' : 'Kombolcha Metropolitan Visual & Documentary Showcase' }}
        </h1>
        <p class="text-slate-600 text-base max-w-3xl">
          High-definition photography and official video documentaries capturing industrial progress, urban landmarks, natural biodiversity of Mount Yegof, and civic milestones.
        </p>

        <!-- Media Type & Category Filters -->
        <div class="flex flex-wrap items-center justify-between gap-4 mt-8 pt-4 border-t border-slate-100">
          <!-- Category Pills -->
          <div class="flex flex-wrap items-center gap-2">
            <button
              v-for="cat in categories"
              :key="cat.key"
              @click="activeCategory = cat.key"
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
                activeCategory === cat.key 
                  ? 'bg-[#0b3c5d] text-white shadow-2xs' 
                  : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200'
              ]"
            >
              {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
            </button>
          </div>

          <!-- Format Switcher (All / Photos / Videos) -->
          <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
            <button 
              @click="activeMediaType = 'all'"
              :class="['px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5', activeMediaType === 'all' ? 'bg-white text-[#0b3c5d] shadow-xs' : 'text-slate-600 hover:text-slate-900']"
            >
              <Layers class="w-3.5 h-3.5" />
              <span>All ({{ galleryItems.length }})</span>
            </button>
            <button 
              @click="activeMediaType = 'image'"
              :class="['px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5', activeMediaType === 'image' ? 'bg-white text-[#0b3c5d] shadow-xs' : 'text-slate-600 hover:text-slate-900']"
            >
              <ImageIcon class="w-3.5 h-3.5" />
              <span>Photos ({{ galleryItems.filter(i => i.media_type === 'image').length }})</span>
            </button>
            <button 
              @click="activeMediaType = 'video'"
              :class="['px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5', activeMediaType === 'video' ? 'bg-white text-[#0b3c5d] shadow-xs' : 'text-slate-600 hover:text-slate-900']"
            >
              <Video class="w-3.5 h-3.5 text-red-600" />
              <span>Videos ({{ galleryItems.filter(i => i.media_type === 'video').length }})</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Loading Skeleton -->
      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="n in 8" :key="n" class="bg-slate-100 rounded-2xl h-72 animate-pulse border border-slate-200"></div>
      </div>

      <!-- Media Grid -->
      <div v-else-if="filteredItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div 
          v-for="item in filteredItems"
          :key="item.id"
          class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-[#0b3c5d]/50 hover:shadow-md transition-all group cursor-pointer flex flex-col justify-between"
          @click="activeItem = item"
        >
          <!-- Media Thumbnail -->
          <div class="h-64 bg-slate-900 relative overflow-hidden flex items-center justify-center">
            <img 
              :src="item.thumbnail_url || item.media_path_or_url" 
              :alt="getTitle(item)" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            
            <!-- Video Play Badge Overlay -->
            <div v-if="item.media_type === 'video'" class="absolute inset-0 bg-black/40 flex items-center justify-center">
              <div class="w-14 h-14 rounded-full bg-red-600/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <Play class="w-6 h-6 fill-current ml-0.5" />
              </div>
            </div>

            <!-- Image Hover Zoom Overlay -->
            <div v-else class="absolute inset-0 bg-slate-950/0 group-hover:bg-slate-950/30 transition-colors flex items-center justify-center">
              <span class="p-3 bg-white/95 rounded-full text-slate-800 opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                <ZoomIn class="w-5 h-5" />
              </span>
            </div>

            <!-- Media Type Tag -->
            <span 
              :class="[
                'absolute top-3 left-3 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider shadow-sm flex items-center gap-1',
                item.media_type === 'video' ? 'bg-red-600 text-white' : 'bg-slate-900/80 backdrop-blur-xs text-white'
              ]"
            >
              <Video v-if="item.media_type === 'video'" class="w-3 h-3" />
              <ImageIcon v-else class="w-3 h-3" />
              <span>{{ item.media_type }}</span>
            </span>

            <!-- Category Tag -->
            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-md bg-white/90 text-slate-800 text-[11px] font-bold uppercase tracking-wider shadow-sm">
              {{ item.category }}
            </span>
          </div>

          <!-- Caption Info -->
          <div class="p-4 border-t border-slate-100">
            <h3 class="font-bold text-sm text-slate-900 line-clamp-2 leading-snug group-hover:text-[#0b3c5d] transition-colors">
              {{ getTitle(item) }}
            </h3>
            <div class="flex items-center justify-between text-xs text-slate-400 mt-3 pt-2 border-t border-slate-100">
              <span class="capitalize">{{ item.category }} Category</span>
              <span class="text-[#0b3c5d] font-semibold flex items-center gap-1">
                {{ item.media_type === 'video' ? 'Watch Video' : 'View High-Res' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-slate-50 border border-slate-200 rounded-3xl">
        <Film class="w-12 h-12 text-slate-400 mx-auto mb-3" />
        <h3 class="text-base font-bold text-slate-700">No media items in this category</h3>
        <p class="text-xs text-slate-500 mt-1">Select "All Media" to browse all city photos and videos.</p>
      </div>

      <!-- Lightbox Zoom Modal (Handles both Photos and Embedded Videos) -->
      <div 
        v-if="activeItem" 
        class="fixed inset-0 bg-black/85 backdrop-blur-md z-50 flex items-center justify-center p-4" 
        @click="activeItem = null"
      >
        <div class="max-w-5xl w-full bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-slate-800 text-white" @click.stop>
          <!-- Top Modal Bar -->
          <div class="p-4 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-950">
            <div class="flex items-center gap-2">
              <span 
                :class="[
                  'px-2.5 py-0.5 rounded text-xs font-bold uppercase',
                  activeItem.media_type === 'video' ? 'bg-red-600 text-white' : 'bg-[#0b3c5d] text-white'
                ]"
              >
                {{ activeItem.media_type }}
              </span>
              <span class="font-bold text-sm text-slate-200 line-clamp-1">{{ getTitle(activeItem) }}</span>
            </div>
            <button @click="activeItem = null" class="p-2 text-slate-400 hover:text-white rounded-xl cursor-pointer">
              <X class="w-5 h-5" />
            </button>
          </div>

          <!-- Media Display Container -->
          <div class="max-h-[75vh] min-h-[350px] bg-black flex items-center justify-center overflow-hidden relative">
            <!-- Video Player (YouTube or HTML5) -->
            <template v-if="activeItem.media_type === 'video'">
              <iframe 
                v-if="isYouTubeUrl(activeItem.media_path_or_url)"
                :src="getEmbedUrl(activeItem.media_path_or_url) + '?autoplay=1'" 
                class="w-full aspect-video min-h-[480px] border-0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
              ></iframe>
              <video 
                v-else 
                :src="activeItem.media_path_or_url" 
                controls 
                autoplay 
                class="max-h-[70vh] w-full"
              ></video>
            </template>

            <!-- High-Res Image Display -->
            <img 
              v-else 
              :src="activeItem.media_path_or_url" 
              :alt="getTitle(activeItem)" 
              class="max-h-[75vh] w-auto object-contain" 
            />
          </div>

          <!-- Bottom Caption -->
          <div class="p-4 px-6 bg-slate-950 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
            <span>{{ activeItem.caption_en || activeItem.caption_am }}</span>
            <span class="capitalize font-semibold text-slate-300">{{ activeItem.category }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
