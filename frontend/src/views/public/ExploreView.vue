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
  ArrowRight
} from 'lucide-vue-next'

const router = useRouter()
const langStore = useLanguageStore()
const activeCategory = ref('all')
const destinations = ref([])
const loading = ref(true)

const categories = [
  { key: 'all', label_en: 'All Destinations', label_am: 'ሁሉንም መዳረሻዎች', label_om: 'Bakkeewwan Hunda' },
  { key: 'nature', label_en: 'Nature & Tourism', label_am: 'የተፈጥሮና ቱሪዝም መስህቦች', label_om: 'Uumamaa fi Tuurizimii' },
  { key: 'industry', label_en: 'Industry & Investment', label_am: 'ኢንዱስትሪና ኢንቨስትመንት', label_om: 'Indaastirii fi Invastimantii' },
  { key: 'culture', label_en: 'Culture & Commerce', label_am: 'ባህልና ንግድ', label_om: 'Aadaa fi Daldala' },
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

const filteredDestinations = computed(() => {
  if (activeCategory.value === 'all') return destinations.value
  return destinations.value.filter(d => d.category === activeCategory.value)
})

const openDetail = (item) => {
  router.push(`/explore/${item.id}`)
}

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

const getCategoryLabel = (catKey) => {
  const c = categories.find(item => item.key === catKey)
  if (!c) return catKey
  const lang = langStore.currentLang
  if (lang === 'am') return c.label_am
  if (lang === 'om') return c.label_om
  return c.label_en
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

const getDescription = (d) => {
  if (!d) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return d.description_am || d.summary_am || d.summary_en || ''
  if (lang === 'om') return d.description_om || d.summary_om || d.description_en || d.summary_en || ''
  return d.description_en || d.summary_en || d.summary_am || ''
}

const getFeaturesList = (d) => {
  if (!d || !d.features) return []
  return d.features.split(',').map(f => f.trim()).filter(Boolean)
}
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Compass class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'ኮምቦልቻን ይጎብኙ' : (langStore.currentLang === 'om' ? 'Kombolchaa Daawwadhaa' : 'Explore Kombolcha City') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'የቱሪዝም መስህቦችና የኢንቨስትመንት መዳረሻዎች' : (langStore.currentLang === 'om' ? 'Bakkeewwan Hawwata Tuurizimii fi Invastimantii' : 'Tourism, Culture & Investment Spotlight') }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'ከግርማ ሞገሱ የይጎፍ ተራራ እስከ አለም አቀፉ የኢንዱስትሪ ፓርክ ድረስ የከተማዋን ድንቅ ገጽታዎች ይመልከቱ።' : 'Discover the scenic wonders of Mount Yegof, pristine parks, Wollo cultural crafts, and industrial growth engines.' }}
        </p>
      </div>
    </div>

    <!-- Category Filter Bar -->
    <div class="flex items-center gap-2 mb-10 overflow-x-auto pb-2 scrollbar-none">
      <button 
        v-for="cat in categories" 
        :key="cat.key" 
        @click="activeCategory = cat.key"
        :class="[
          'px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap',
          activeCategory === cat.key 
            ? 'bg-blue-700 text-white shadow-2xs' 
            : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
        ]"
      >
        {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="py-24 flex flex-col items-center justify-center gap-3 text-slate-500">
      <Loader2 class="w-8 h-8 animate-spin text-blue-600" />
      <span class="text-xs font-semibold uppercase tracking-wider">
        {{ langStore.currentLang === 'am' ? 'መረጃዎች በመጫን ላይ ናቸው...' : 'Loading destinations...' }}
      </span>
    </div>

    <!-- Empty State -->
    <div v-else-if="filteredDestinations.length === 0" class="py-24 text-center bg-slate-50 rounded-3xl border border-slate-200">
      <Compass class="w-10 h-10 text-slate-400 mx-auto mb-3" />
      <h3 class="text-base font-bold text-slate-700 mb-1">
        {{ langStore.currentLang === 'am' ? 'ምንም መዳረሻ አልተገኘም' : 'No destinations found in this category' }}
      </h3>
      <p class="text-xs text-slate-500">
        {{ langStore.currentLang === 'am' ? 'እባክዎ ሌላ ምድብ ይምረጡ' : 'Please select another category or check back later.' }}
      </p>
    </div>

    <!-- Destinations Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div 
        v-for="item in filteredDestinations" 
        :key="item.id"
        @click="openDetail(item)"
        class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:border-blue-400 hover:shadow-lg transition-all duration-300 flex flex-col justify-between group cursor-pointer"
      >
        <div>
          <!-- Header Visual with Photo if available or Icon fallback -->
          <div v-if="item.image_path" class="h-48 w-full bg-slate-100 overflow-hidden relative">
            <img 
              :src="item.image_path" 
              :alt="getTitle(item)" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="$event.target.style.display='none'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>
            
            <!-- Badges overlay -->
            <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
              <span class="px-2.5 py-1 rounded-lg bg-white/95 backdrop-blur-xs text-slate-800 text-[11px] font-bold uppercase tracking-wider shadow-xs">
                {{ getCategoryLabel(item.category) }}
              </span>
              <div class="p-1.5 bg-white/95 backdrop-blur-xs text-blue-700 rounded-lg shadow-xs">
                <component :is="getCategoryIcon(item)" class="w-4 h-4" />
              </div>
            </div>

            <div v-if="getLocation(item)" class="absolute bottom-3 left-4 right-4 flex items-center gap-1.5 text-xs text-white font-medium drop-shadow-sm">
              <MapPin class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
              <span class="truncate">{{ getLocation(item) }}</span>
            </div>
          </div>

          <!-- Header Fallback when no photo -->
          <div v-else class="h-44 bg-slate-50 p-6 flex flex-col justify-between text-slate-800 border-b border-slate-200 relative">
            <div class="flex items-center justify-between relative z-10">
              <span class="px-2.5 py-1 rounded bg-white text-slate-700 text-[11px] font-bold uppercase tracking-wider border border-slate-200">
                {{ getCategoryLabel(item.category) }}
              </span>
              <div class="p-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
                <component :is="getCategoryIcon(item)" class="w-5 h-5" />
              </div>
            </div>

            <div v-if="getLocation(item)" class="flex items-center gap-1.5 text-xs text-slate-600 font-semibold relative z-10">
              <MapPin class="w-3.5 h-3.5 text-blue-700 shrink-0" />
              <span class="truncate">{{ getLocation(item) }}</span>
            </div>
          </div>

          <!-- Body Content -->
          <div class="p-6">
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2 leading-snug">
              {{ getTitle(item) }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-3">
              {{ getSummary(item) }}
            </p>

            <!-- Quick Visiting Highlights -->
            <div v-if="item.visiting_hours || item.contact_phone" class="mt-4 pt-3 border-t border-slate-100 flex flex-wrap gap-2 text-[11px] text-slate-500 font-medium">
              <span v-if="item.visiting_hours" class="inline-flex items-center gap-1 text-slate-600">
                <Clock class="w-3 h-3 text-blue-600" />
                {{ item.visiting_hours }}
              </span>
              <span v-if="item.admission_fee" class="inline-flex items-center gap-1 text-emerald-600 font-semibold ml-auto">
                <Ticket class="w-3 h-3" />
                {{ item.admission_fee }}
              </span>
            </div>
          </div>
        </div>

        <div class="px-6 pb-6 pt-2 border-t border-slate-100 flex items-center justify-between">
          <span class="text-[11px] font-bold text-emerald-600 flex items-center gap-1">
            <CheckCircle2 class="w-3.5 h-3.5" />
            {{ langStore.currentLang === 'am' ? 'ክፍት የመጎብኛ ስፍራ' : 'Open Destination' }}
          </span>
          <button 
            type="button"
            @click.stop="openDetail(item)"
            class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors inline-flex items-center gap-1 group/btn"
          >
            <span>{{ langStore.currentLang === 'am' ? 'ተጨማሪ መረጃ' : 'Inquire Details' }}</span>
            <span class="group-hover/btn:translate-x-0.5 transition-transform">→</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
