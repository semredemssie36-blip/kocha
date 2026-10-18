<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  Compass, 
  MapPin, 
  Mountain, 
  Factory, 
  Trees, 
  ShoppingBag, 
  Truck, 
  Plane, 
  Building,
  CheckCircle2, 
  Sparkles, 
  Loader2,
  X,
  Clock,
  Phone,
  Mail,
  Ticket,
  ArrowRight,
  ExternalLink,
  ShieldCheck,
  Search,
  Layers
} from 'lucide-vue-next'

const router = useRouter()
const langStore = useLanguageStore()
const activeCategory = ref('all')
const destinations = ref([])
const loading = ref(true)
const searchQuery = ref('')

const categories = [
  { key: 'all', label_en: 'All Destinations', label_am: 'ሁሉንም መዳረሻዎች', label_om: 'Bakkeewwan Hunda' },
  { key: 'nature', label_en: 'Nature & Eco-Tourism', label_am: 'የተፈጥሮና ቱሪዝም መስህቦች', label_om: 'Uumamaa fi Tuurizimii' },
  { key: 'industry', label_en: 'Industry & Investment Hubs', label_am: 'ኢንዱስትሪና ኢንቨስትመንት', label_om: 'Indaastirii fi Invastimantii' },
  { key: 'culture', label_en: 'Culture, Commerce & Heritage', label_am: 'ባህልና ንግድ', label_om: 'Aadaa fi Daldala' },
]

const fetchDestinations = async () => {
  loading.value = true
  try {
    const res = await api.get('/destinations')
    destinations.value = Array.isArray(res.data) ? res.data : (res.data?.data || [])
  } catch (err) {
    console.error('Failed to load destinations:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchDestinations()
})

const getCategoryIcon = (item) => {
  const cat = item.category?.toLowerCase() || ''
  const t = (item.title_en || item.title_am || '').toLowerCase()
  if (t.includes('airport') || t.includes('አየር')) return Plane
  if (t.includes('port') || t.includes('ሎጂስቲክስ') || t.includes('ወደብ')) return Truck
  if (cat === 'nature') {
    return t.includes('park') || t.includes('ፓርክ') ? Trees : Mountain
  }
  if (cat === 'culture') return ShoppingBag
  if (cat === 'industry') return Factory
  return Compass
}

const getTitle = (d) => {
  if (!d) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return d.title_am || d.title_en
  if (lang === 'om') return d.title_om || d.title_en || d.title_am
  return d.title_en || d.title_am
}

const getLocation = (d) => {
  if (!d) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return d.location_am || d.location_en || ''
  if (lang === 'om') return d.location_om || d.location_en || d.location_am || ''
  return d.location_en || d.location_am || ''
}

const getSummary = (d) => {
  if (!d) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return d.summary_am || d.summary_en || ''
  if (lang === 'om') return d.summary_om || d.summary_en || d.summary_am || ''
  return d.summary_en || d.summary_am || ''
}

const getFeaturesList = (d) => {
  if (!d || !d.features) return []
  return d.features.split(',').map(f => f.trim()).filter(Boolean)
}

const filteredDestinations = computed(() => {
  return destinations.value.filter(d => {
    // Category match
    if (activeCategory.value !== 'all' && d.category !== activeCategory.value) {
      return false
    }

    // Search query match
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const title = (getTitle(d) || '').toLowerCase()
      const loc = (getLocation(d) || '').toLowerCase()
      const sum = (getSummary(d) || '').toLowerCase()
      if (!title.includes(q) && !loc.includes(q) && !sum.includes(q)) return false
    }

    return true
  })
})

const openDetail = (item) => {
  router.push(`/explore/${item.id}`)
}
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Hero Banner -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/koch_erport.jpg" alt="Explore Kombolcha" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>

      <div class="w-full max-w-[1700px] mx-auto relative z-10">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'ኮምቦልቻን ይጎብኙና ኢንቨስት ያድርጉ' : 'Explore Kombolcha: Tourism, Culture & Industry' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'ከግርማ ሞገሱ የይጎፍ የተፈጥሮ ጥብቅ ደን ተራራ እስከ አለም አቀፍ ደረጃውን የጠበቀው የኮምቦልቻ ኢንዱስትሪ ፓርክና ደረቅ ወደብ ድረስ የከተማዋን ውበትና የንግድ አቅም ይመልከቱ።'
            : 'Where majestic mountain peaks meet national manufacturing might. Discover Mount Yegof, pristine urban parks, vibrant Wollo cultural traditions, and high-yield industrial corridors.'
          }}
        </p>

        <!-- Highlights Metric Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Scenic Altitude</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">1,842m - 2,600m</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Yegof Protected Forest</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-400">19,000+ Ha</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-amber-500/30">
            <span class="text-amber-400 text-xs block font-bold mb-1">Industrial Park Size</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-amber-400">75+ Hectares</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Transport Connectivity</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Air • Rail • Road</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Category Pill Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button 
            v-for="cat in categories" 
            :key="cat.key" 
            @click="activeCategory = cat.key"
            :class="[
              'px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200 cursor-pointer whitespace-nowrap shadow-xs',
              activeCategory === cat.key 
                ? 'bg-blue-900 text-amber-300 ring-2 ring-blue-900/20' 
                : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
            ]"
          >
            {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
          </button>
        </div>

        <!-- Search Input -->
        <div class="relative min-w-[260px]">
          <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="langStore.currentLang === 'am' ? 'መዳረሻዎችን ፈልግ...' : 'Search destinations...'" 
            class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-3xl border border-slate-200 overflow-hidden h-96 animate-pulse"></div>
      </div>

      <!-- Destination Cards Grid -->
      <div v-else-if="filteredDestinations.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <article 
          v-for="item in filteredDestinations" 
          :key="item.id"
          @click="openDetail(item)"
          class="bg-white rounded-3xl border border-slate-200 hover:border-amber-400 hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between group cursor-pointer"
        >
          <div>
            <!-- Photography Cover Frame -->
            <div class="h-60 bg-slate-900 relative overflow-hidden">
              <img 
                :src="item.image_path || '/assets/hero_aerial.jpg'" 
                :alt="getTitle(item)" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-95"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

              <!-- Category Badge -->
              <div class="absolute top-4 left-4 flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 border border-amber-400/30 text-[11px] font-black uppercase tracking-wider shadow-md">
                <component :is="getCategoryIcon(item)" class="w-3.5 h-3.5" />
                <span>{{ item.category || 'Spotlight' }}</span>
              </div>

              <!-- Location Tag -->
              <div v-if="getLocation(item)" class="absolute bottom-4 left-4 flex items-center gap-1.5 text-xs text-white font-bold drop-shadow-md">
                <MapPin class="w-3.5 h-3.5 text-amber-400 shrink-0" />
                <span class="truncate">{{ getLocation(item) }}</span>
              </div>
            </div>

            <!-- Content Body -->
            <div class="p-6 sm:p-7">
              <h2 class="text-xl font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug mb-2.5">
                {{ getTitle(item) }}
              </h2>

              <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3 mb-4">
                {{ getSummary(item) }}
              </p>

              <!-- Feature Pills if available -->
              <div v-if="getFeaturesList(item).length > 0" class="flex flex-wrap gap-1.5 pt-1">
                <span 
                  v-for="(feat, fIdx) in getFeaturesList(item).slice(0, 3)" 
                  :key="fIdx"
                  class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold uppercase tracking-wider"
                >
                  {{ feat }}
                </span>
              </div>
            </div>
          </div>

          <!-- Bottom Action Bar -->
          <div class="p-6 sm:p-7 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between text-xs font-black text-blue-900">
            <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1.5 text-blue-900">
              <span>{{ langStore.currentLang === 'am' ? 'ሙሉ መረጃና መመሪያ' : 'View Full Profile & Route' }}</span>
              <ArrowRight class="w-4 h-4 text-amber-500" />
            </span>
            <span class="text-[10px] text-slate-400 uppercase font-mono">Explore</span>
          </div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Compass class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No destinations found in this category</h3>
        <p class="text-xs text-slate-500 mt-1">Try switching to another category or clearing your search filter.</p>
        <button 
          @click="activeCategory = 'all'; searchQuery = ''" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          View All Destinations
        </button>
      </div>
    </div>
  </div>
</template>
