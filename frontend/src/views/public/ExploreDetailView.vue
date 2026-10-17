<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import {
  Compass,
  MapPin,
  Clock,
  Phone,
  Mail,
  Ticket,
  ChevronRight,
  ArrowLeft,
  Share2,
  CheckCircle2,
  Building2,
  Factory,
  Mountain,
  ShoppingBag,
  ExternalLink,
  Sparkles,
  PhoneCall
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const langStore = useLanguageStore()

const loading = ref(true)
const destination = ref(null)
const relatedDestinations = ref([])
const copied = ref(false)

const categoryIcons = {
  nature: Mountain,
  industry: Factory,
  culture: ShoppingBag
}

const getCategoryIcon = (category) => {
  return categoryIcons[category] || Compass
}

const getCategoryLabel = (category) => {
  const map = {
    nature: { en: 'Nature & Tourism', am: 'የተፈጥሮና ቱሪዝም መስህብ', om: 'Uumamaa fi Tuurizimii' },
    industry: { en: 'Industry & Investment', am: 'ኢንዱስትሪና ኢንቨስትመንት', om: 'Indaastirii fi Invastimantii' },
    culture: { en: 'Culture & Commerce', am: 'ባህልና ንግድ', om: 'Aadaa fi Daldala' },
  }
  const item = map[category] || { en: 'Destination', am: 'የጉብኝት ስፍራ', om: 'Iddoo Daawwannaa' }
  return item[langStore.currentLang] || item.en
}

const getTitle = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am || item.title_en
  if (lang === 'om') return item.title_om || item.title_en
  return item.title_en || item.title_am
}

const getLocation = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.location_am || item.location_en
  if (lang === 'om') return item.location_om || item.location_en
  return item.location_en || item.location_am
}

const getDescription = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.description_am || item.summary_am || item.description_en || ''
  if (lang === 'om') return item.description_om || item.summary_om || item.description_en || ''
  return item.description_en || item.summary_en || item.description_am || ''
}

const featuresList = computed(() => {
  if (!destination.value?.features) return []
  return destination.value.features
    .split(',')
    .map(f => f.trim())
    .filter(Boolean)
})

const fetchDestinationDetail = async () => {
  loading.value = true
  try {
    const slug = route.params.slug
    const res = await api.getDestination(slug)
    destination.value = res.data?.destination || null
    relatedDestinations.value = res.data?.related || []
  } catch (err) {
    console.error('Failed to load destination details:', err)
    destination.value = null
  } finally {
    loading.value = false
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const sharePage = () => {
  if (navigator.share) {
    navigator.share({
      title: getTitle(destination.value),
      url: window.location.href
    }).catch(() => {})
  } else {
    navigator.clipboard.writeText(window.location.href)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2500)
  }
}

watch(() => route.params.slug, () => {
  fetchDestinationDetail()
})

onMounted(() => {
  fetchDestinationDetail()
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-800">
    <!-- Breadcrumbs -->
    <div class="bg-white border-b border-slate-200 py-3">
      <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 flex items-center justify-between text-xs font-semibold text-slate-500">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
          <router-link to="/" class="hover:text-blue-700 transition-colors">
            {{ langStore.currentLang === 'am' ? 'ዋና ገጽ' : 'Home' }}
          </router-link>
          <ChevronRight class="w-3.5 h-3.5 shrink-0 text-slate-400" />
          <router-link to="/explore" class="hover:text-blue-700 transition-colors">
            {{ langStore.currentLang === 'am' ? 'ኮምቦልቻን ይጎብኙ' : 'Explore Kombolcha' }}
          </router-link>
          <ChevronRight class="w-3.5 h-3.5 shrink-0 text-slate-400" />
          <span class="text-slate-900 truncate max-w-xs font-bold">
            {{ destination ? getTitle(destination) : '...' }}
          </span>
        </div>

        <button 
          @click="$router.push('/explore')" 
          class="inline-flex items-center gap-1.5 text-blue-700 hover:text-blue-900 font-bold shrink-0 transition-colors cursor-pointer"
        >
          <ArrowLeft class="w-3.5 h-3.5" />
          <span>{{ langStore.currentLang === 'am' ? 'ወደ ሁሉም መዳረሻዎች ተመለስ' : 'All Destinations' }}</span>
        </button>
      </div>
    </div>

    <!-- Main Content Container -->
    <main class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8 sm:py-12">
      <!-- Loading Skeleton State -->
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 space-y-6">
          <div class="h-96 bg-slate-200 rounded-3xl animate-pulse"></div>
          <div class="h-8 bg-slate-200 rounded-lg animate-pulse w-3/4"></div>
          <div class="h-4 bg-slate-200 rounded animate-pulse w-1/3"></div>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div v-for="i in 4" :key="i" class="h-24 bg-slate-200 rounded-2xl animate-pulse"></div>
          </div>
          <div class="space-y-3 pt-4">
            <div class="h-4 bg-slate-200 rounded animate-pulse"></div>
            <div class="h-4 bg-slate-200 rounded animate-pulse"></div>
            <div class="h-4 bg-slate-200 rounded animate-pulse w-5/6"></div>
          </div>
        </div>
        <div class="lg:col-span-4 space-y-6">
          <div class="h-64 bg-slate-200 rounded-3xl animate-pulse"></div>
          <div class="h-64 bg-slate-200 rounded-3xl animate-pulse"></div>
        </div>
      </div>

      <!-- Destination Not Found State -->
      <div v-else-if="!destination" class="text-center py-24 bg-white rounded-3xl border border-slate-200 shadow-sm max-w-xl mx-auto p-8">
        <Compass class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h2 class="text-2xl font-black text-slate-900 mb-2">
          {{ langStore.currentLang === 'am' ? 'የመዳረሻ መረጃው አልተገኘም' : 'Destination Not Found' }}
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">
          {{ langStore.currentLang === 'am' ? 'የጠየቁት የቱሪዝም ወይም የኢንዱስትሪ መዳረሻ አልተገኘም ወይም ተሰርዟል።' : 'The requested attraction could not be found or has been moved.' }}
        </p>
        <router-link to="/explore" class="px-6 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white font-bold text-xs uppercase tracking-wider inline-flex items-center gap-2">
          <ArrowLeft class="w-4 h-4" />
          {{ langStore.currentLang === 'am' ? 'ወደ መዳረሻዎች ተመለስ' : 'Back to Destinations' }}
        </router-link>
      </div>

      <!-- Real Loaded Destination Page -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Editorial & Detailed Guide (8 cols) -->
        <article class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-10">
          
          <!-- Top Badges & Share Bar -->
          <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                <component :is="getCategoryIcon(destination.category)" class="w-3.5 h-3.5" />
                {{ getCategoryLabel(destination.category) }}
              </span>
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">
                <CheckCircle2 class="w-3.5 h-3.5" />
                {{ langStore.currentLang === 'am' ? 'ክፍት ይፋዊ መዳረሻ' : 'Official Open Destination' }}
              </span>
            </div>

            <button 
              @click="sharePage"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-900 text-xs font-bold transition-colors cursor-pointer"
            >
              <Share2 class="w-3.5 h-3.5" />
              <span>{{ copied ? (langStore.currentLang === 'am' ? 'ሊንኩ ተገልብጧል!' : 'Copied!') : (langStore.currentLang === 'am' ? 'አጋራ' : 'Share') }}</span>
            </button>
          </div>

          <!-- Destination Title & Location -->
          <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight mb-3">
            {{ getTitle(destination) }}
          </h1>

          <div v-if="getLocation(destination)" class="flex items-center gap-2 text-sm text-slate-600 font-semibold mb-6">
            <MapPin class="w-4 h-4 text-blue-700 shrink-0" />
            <span>{{ getLocation(destination) }}</span>
          </div>

          <!-- Hero Image -->
          <div class="relative rounded-2xl overflow-hidden border border-slate-200 mb-8 aspect-video bg-slate-900">
            <img 
              v-if="destination.image_path" 
              :src="destination.image_path" 
              :alt="getTitle(destination)" 
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400">
              <component :is="getCategoryIcon(destination.category)" class="w-16 h-16 opacity-30 text-blue-700 mb-2" />
              <span class="text-xs font-bold">Kombolcha City Administration</span>
            </div>
          </div>

          <!-- Key Metrics / Visiting Facts Strip (AWS-Style Information Density) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <!-- Visiting Hours -->
            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 flex flex-col justify-between">
              <div class="flex items-center gap-1.5 text-blue-700 text-xs font-bold uppercase tracking-wider mb-2">
                <Clock class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'የስራ / የጉብኝት ሰዓት' : 'Visiting Hours' }}</span>
              </div>
              <p class="text-xs sm:text-sm font-black text-slate-900 leading-snug">
                {{ destination.visiting_hours || (langStore.currentLang === 'am' ? 'መደበኛ የስራ ሰዓት' : 'Standard Business Hours') }}
              </p>
            </div>

            <!-- Admission Fee -->
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 flex flex-col justify-between">
              <div class="flex items-center gap-1.5 text-amber-800 text-xs font-bold uppercase tracking-wider mb-2">
                <Ticket class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'የመግቢያ ዋጋ' : 'Admission Fee' }}</span>
              </div>
              <p class="text-xs sm:text-sm font-black text-slate-900 leading-snug">
                {{ destination.admission_fee || (langStore.currentLang === 'am' ? 'ነጻ መግቢያ' : 'Free / Upon Registration') }}
              </p>
            </div>

            <!-- Inquiry Line -->
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-100 flex flex-col justify-between">
              <div class="flex items-center gap-1.5 text-emerald-700 text-xs font-bold uppercase tracking-wider mb-2">
                <Phone class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'የቀጥታ ስልክ' : 'Inquiry Line' }}</span>
              </div>
              <a 
                v-if="destination.contact_phone"
                :href="'tel:' + destination.contact_phone" 
                class="text-xs sm:text-sm font-mono font-black text-slate-900 hover:text-blue-700 transition-colors"
              >
                {{ destination.contact_phone }}
              </a>
              <span v-else class="text-xs font-mono font-bold text-slate-500">+251 33 551 0145</span>
            </div>

            <!-- Email Contact -->
            <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-100 flex flex-col justify-between">
              <div class="flex items-center gap-1.5 text-purple-700 text-xs font-bold uppercase tracking-wider mb-2">
                <Mail class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'ኢሜይል' : 'Inquiry Email' }}</span>
              </div>
              <a 
                v-if="destination.contact_email"
                :href="'mailto:' + destination.contact_email" 
                class="text-xs font-mono font-bold text-slate-900 hover:text-blue-700 transition-colors truncate"
                :title="destination.contact_email"
              >
                {{ destination.contact_email }}
              </a>
              <span v-else class="text-xs font-mono font-bold text-slate-500">info@kombolcha.gov.et</span>
            </div>
          </div>

          <!-- Comprehensive Overview & Narrative -->
          <div class="space-y-6 mb-8">
            <h2 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-2">
              {{ langStore.currentLang === 'am' ? 'ዝርዝር ማብራሪያና የጎብኚዎች መመሪያ' : 'Overview & Visitor Guide' }}
            </h2>
            <div class="text-sm sm:text-base text-slate-700 leading-relaxed space-y-4">
              <p class="whitespace-pre-line leading-relaxed">
                {{ getDescription(destination) }}
              </p>
            </div>
          </div>

          <!-- Key Highlights & Features -->
          <div v-if="featuresList.length > 0" class="mb-8 pt-6 border-t border-slate-100">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4">
              {{ langStore.currentLang === 'am' ? 'ቁልፍ መለያዎችና አገልግሎቶች' : 'Key Features & Amenities' }}
            </h3>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="(feature, idx) in featuresList" 
                :key="idx"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700"
              >
                <CheckCircle2 class="w-3.5 h-3.5 text-blue-700" />
                {{ feature }}
              </span>
            </div>
          </div>

          <!-- Direct Inquiries Action Bar -->
          <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500">
              {{ langStore.currentLang === 'am' ? 'ለበለጠ መረጃ ወይም የልዑካን ጉብኝት ለማስተባበር የከተማዋን አስተዳደር ያነጋግሩ።' : 'To coordinate institutional delegations or investment visits, contact municipal services.' }}
            </div>
            <router-link 
              :to="{ path: '/contact', query: { subject: `Inquiry regarding: ${destination.title_en || destination.title_am}` } }"
              class="px-6 py-3 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-extrabold uppercase tracking-wider transition-colors inline-flex items-center gap-2 shrink-0 shadow-sm"
            >
              <span>{{ langStore.currentLang === 'am' ? 'ለከተማ አስተዳደሩ ጥያቄ ይላኩ' : 'Send Inquiry to City Administration' }}</span>
              <ChevronRight class="w-4 h-4" />
            </router-link>
          </div>
        </article>

        <!-- Right: "Discover Other Attractions" Sidebar Widget (4 cols) -->
        <aside class="lg:col-span-4 space-y-6">
          
          <!-- Other Attractions Widget -->
          <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <Compass class="w-5 h-5 text-blue-700" />
                <h3 class="text-base font-bold text-slate-900">
                  {{ langStore.currentLang === 'am' ? 'ሌሎች የኮምቦልቻ መዳረሻዎች' : 'Other Attractions' }}
                </h3>
              </div>
              <router-link to="/explore" class="text-xs font-bold text-blue-700 hover:underline">
                {{ langStore.currentLang === 'am' ? 'ሁሉንም' : 'View All' }}
              </router-link>
            </div>

            <div class="space-y-4">
              <router-link 
                v-for="item in relatedDestinations" 
                :key="item.id"
                :to="`/explore/${item.id}`"
                class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group"
              >
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
                  <img 
                    v-if="item.image_path" 
                    :src="item.image_path" 
                    :alt="getTitle(item)" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                    <component :is="getCategoryIcon(item.category)" class="w-6 h-6 opacity-30 text-blue-700" />
                  </div>
                </div>

                <div class="min-w-0 flex-1">
                  <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-0.5">
                    {{ getCategoryLabel(item.category) }}
                  </span>
                  <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-1">
                    {{ getTitle(item) }}
                  </h4>
                  <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                    {{ getLocation(item) }}
                  </p>
                </div>
              </router-link>
            </div>
          </div>

          <!-- Municipal Investment & Tourism Helpdesk Card -->
          <div class="bg-gradient-to-br from-slate-900 to-blue-950 text-white rounded-3xl p-6 sm:p-7 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-8 h-8 rounded-lg bg-blue-600/30 border border-blue-400/30 flex items-center justify-center">
                <Building2 class="w-4 h-4 text-blue-300" />
              </div>
              <span class="text-xs font-bold uppercase tracking-wider text-blue-300">
                {{ langStore.currentLang === 'am' ? 'የኢንቨስትመንት ድጋፍ' : 'Investment & Tourism Desk' }}
              </span>
            </div>
            
            <h4 class="text-base font-black text-white mb-2 leading-snug">
              {{ langStore.currentLang === 'am' ? 'በኮምቦልቻ ከተማ ኢንቨስት ማድረግ ይፈልጋሉ?' : 'Planning to Invest or Tour Kombolcha?' }}
            </h4>
            <p class="text-xs text-slate-300 leading-relaxed mb-4">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር የኢንቨስትመንትና ቱሪዝም ቢሮ ፈጣን የአንድ ማዕከል አገልግሎት ይሰጣል።' : 'Our municipal desk provides full facilitation for business licensing, industrial shed allocation, and tourism permits.' }}
            </p>

            <div class="pt-2 border-t border-white/10 flex items-center justify-between text-xs">
              <span class="font-mono text-blue-200 font-bold">+251 33 551 0145</span>
              <router-link to="/contact" class="font-bold text-white hover:text-blue-200 underline">
                {{ langStore.currentLang === 'am' ? 'አግኙን →' : 'Contact Office →' }}
              </router-link>
            </div>
          </div>
        </aside>

      </div>
    </main>
  </div>
</template>
