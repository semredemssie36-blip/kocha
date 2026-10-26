<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  MapPin, 
  Phone, 
  Mail, 
  Building2, 
  Landmark, 
  ShieldCheck, 
  Users, 
  ChevronRight, 
  ArrowRight,
  Layers, 
  Sparkles,
  CheckCircle2,
  Compass,
  FileText,
  Award
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const subCities = ref([])
const loading = ref(true)
const selectedFilter = ref('all')

async function fetchSubCities() {
  loading.value = true
  try {
    const res = await api.get('/sub-cities')
    subCities.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load sub-cities:', err)
  } finally {
    loading.value = false
  }
}

const filteredSubCities = computed(() => {
  if (selectedFilter.value === 'all') return subCities.value
  return subCities.value.filter(sc => sc.slug === selectedFilter.value)
})

function getSubCityName(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.name_am || city.name_en
  if (lang === 'om') return city.name_om || city.name_en
  return city.name_en || city.name_am
}

function getSlogan(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.slogan_am || city.slogan_en
  if (lang === 'om') return city.slogan_om || city.slogan_en
  return city.slogan_en || city.slogan_am
}

function getDescription(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.description_am || city.description_en
  if (lang === 'om') return city.description_om || city.description_en
  return city.description_en || city.description_am
}

function getAddress(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.office_address_am || city.office_address_en || 'ኮምቦልቻ፣ ኢትዮጵያ'
  if (lang === 'om') return city.office_address_om || city.office_address_en || 'Kombolcha, Itoophiyaa'
  return city.office_address_en || city.office_address_am || 'Kombolcha, Ethiopia'
}

function getKebeles(city) {
  if (Array.isArray(city.kebeles)) return city.kebeles
  if (typeof city.kebeles === 'string') {
    try {
      return JSON.parse(city.kebeles)
    } catch (e) {
      return []
    }
  }
  return []
}

function getHighlights(city) {
  if (Array.isArray(city.highlights)) return city.highlights
  if (typeof city.highlights === 'string') {
    try {
      return JSON.parse(city.highlights)
    } catch (e) {
      return []
    }
  }
  return []
}

onMounted(() => {
  fetchSubCities()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-slate-950 text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden min-h-[380px] flex items-center">
      <img src="/assets/city_image_2.jpg" alt="Sub-Cities" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-slate-950/75 via-slate-950/40 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent to-black/25"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/25 border border-blue-400/50 text-blue-200 text-xs font-black uppercase tracking-wider mb-3 backdrop-blur-md">
          <Layers class="w-3.5 h-3.5 text-blue-300" />
          <span>{{ langStore.currentLang === 'am' ? 'የከተማው መዋቅራዊ አስተዳደር' : 'Metropolitan Governance Framework' }}</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ክፍለ ከተሞች አስተዳደር' : 'Metropolitan Sub-City Administrations' }}
        </h1>
        <p class="text-slate-100 text-sm sm:text-base max-w-3xl leading-relaxed drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)] font-medium">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ አስተዳደር ህዝባዊ አገልግሎቶችን ለነዋሪዎች በቅርበት ለማድረስ በ 4 ራስ ገዝ ክፍለ ከተሞች (አቢሻገር፣ ቢራሮ፣ ሀሰን አገር፣ ቦርከና) እና በ 20 ቀበሌዎች (14 የከተማ እና 6 የገጠር ቀበሌዎች) የተዋቀረ ነው።'
            : 'Kombolcha is decentralized into 4 metropolitan sub-cities (Abishager, Biraro, Hassen Ager, Borkena) spanning 20 Kebeles (14 Urban & 6 Rural) delivering localized municipal services, licensing, civil status registrations, and local infrastructure.'
          }}
        </p>

        <!-- Sub-City Overview Analytics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20">
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Autonomous Sub-Cities</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white drop-shadow">{{ subCities.length || 4 }} Zones</div>
            <span class="text-[10px] text-amber-400 font-bold block mt-0.5">Regiopolitan Charter</span>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Total Constituent Kebeles</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-300 drop-shadow">20 Kebeles</div>
            <span class="text-[10px] text-slate-300 block mt-0.5">14 Urban • 6 Rural</span>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-blue-400 text-xs block font-bold mb-1">Decentralized Services</span>
            <div class="text-lg sm:text-xl font-black font-mono text-blue-300 drop-shadow">IDs • Permits • Vital</div>
            <span class="text-[10px] text-slate-300 block mt-0.5">Localized Citizen Access</span>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Municipal Land Area</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white drop-shadow">124.5 km²</div>
            <span class="text-[10px] text-slate-300 block mt-0.5">15,550.25 Hectares</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      
      <!-- Interactive Sub-City Zone Switcher -->
      <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 text-xs font-bold scrollbar-none">
        <button
          @click="selectedFilter = 'all'"
          :class="[
            'px-5 py-3 rounded-2xl transition-all cursor-pointer whitespace-nowrap flex items-center gap-2 shrink-0',
            selectedFilter === 'all'
              ? 'bg-blue-900 text-amber-300 shadow-md font-black'
              : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'
          ]"
        >
          <Layers class="w-4 h-4" />
          <span>{{ langStore.currentLang === 'am' ? 'ሁሉም ክፍለ ከተሞች (4)' : 'All Sub-Cities (4)' }}</span>
        </button>

        <button
          v-for="city in subCities"
          :key="city.id"
          @click="selectedFilter = city.slug"
          :class="[
            'px-5 py-3 rounded-2xl transition-all cursor-pointer whitespace-nowrap flex items-center gap-2 shrink-0',
            selectedFilter === city.slug
              ? 'bg-blue-900 text-amber-300 shadow-md font-black'
              : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'
          ]"
        >
          <Building2 class="w-4 h-4" />
          <span>{{ getSubCityName(city) }}</span>
          <span class="text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold">
            {{ getKebeles(city).length }}
          </span>
        </button>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div v-for="n in 4" :key="n" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs animate-pulse h-96"></div>
      </div>

      <!-- Sub-Cities Rich Cards Grid -->
      <div v-else-if="filteredSubCities.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div 
          v-for="city in filteredSubCities" 
          :key="city.id" 
          class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:border-blue-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden group"
        >
          <div>
            <!-- Image Header Strip with Zone Badge -->
            <div class="h-44 sm:h-52 w-full relative overflow-hidden bg-slate-900">
              <img 
                :src="city.photo_path || '/assets/city_image_2.jpg'" 
                :alt="getSubCityName(city)" 
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700" 
              />
              <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent"></div>
              
              <div class="absolute bottom-4 left-6 right-6 flex items-end justify-between">
                <div>
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-mono text-xs font-black uppercase tracking-wider mb-2 shadow-sm">
                    Zone #0{{ city.order || city.id }}
                  </span>
                  <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    {{ getSubCityName(city) }}
                  </h2>
                </div>
                <div class="text-right shrink-0">
                  <span class="px-3 py-1 rounded-xl bg-white/20 backdrop-blur-md text-white font-mono font-black text-xs">
                    {{ getKebeles(city).length }} {{ langStore.currentLang === 'am' ? 'ቀበሌዎች' : 'Kebeles' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Card Body Content -->
            <div class="p-6 sm:p-8 space-y-6">
              
              <!-- Official Slogan Banner -->
              <div v-if="getSlogan(city)" class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 text-amber-950 font-bold text-xs sm:text-sm flex items-start gap-3">
                <Sparkles class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <p class="leading-relaxed">{{ getSlogan(city) }}</p>
              </div>

              <!-- Official Narrative Description -->
              <p class="text-sm text-slate-700 leading-relaxed text-justify font-normal">
                {{ getDescription(city) }}
              </p>

              <!-- Constituent Kebeles Grid -->
              <div>
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-2">
                  <Building2 class="w-4 h-4 text-blue-700" />
                  <span>
                    {{ langStore.currentLang === 'am' ? 'በክፍለ ከተማው ስር ያሉ ቀበሌዎች' : 'Constituent Kebele Jurisdictions' }}
                    ({{ getKebeles(city).length }})
                  </span>
                </h4>
                
                <div class="flex flex-wrap gap-2">
                  <span 
                    v-for="(k, idx) in getKebeles(city)" 
                    :key="idx"
                    :class="[
                      'px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 border transition-all',
                      k.type === 'rural' 
                        ? 'bg-emerald-50 text-emerald-900 border-emerald-200' 
                        : 'bg-blue-50 text-blue-900 border-blue-200'
                    ]"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="k.type === 'rural' ? 'bg-emerald-500' : 'bg-blue-600'"></span>
                    <span>{{ langStore.currentLang === 'am' ? k.am : k.en }}</span>
                    <span class="text-[10px] font-mono opacity-60">({{ k.type === 'rural' ? 'Rural' : 'Urban' }})</span>
                  </span>
                </div>
              </div>

              <!-- Key Strategic Highlights / Infrastructure -->
              <div v-if="getHighlights(city).length > 0" class="pt-2">
                <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-2">
                  <Award class="w-4 h-4 text-emerald-600" />
                  <span>{{ langStore.currentLang === 'am' ? 'ቁልፍ የልማትና የመሰረተ ልማት ጥቅሞች' : 'Strategic Infrastructure & Advantages' }}</span>
                </h4>
                
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-semibold text-slate-700">
                  <li 
                    v-for="(hl, hIdx) in getHighlights(city)" 
                    :key="hIdx"
                    class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-2"
                  >
                    <CheckCircle2 class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                    <span>{{ hl }}</span>
                  </li>
                </ul>
              </div>

              <!-- Contact & Address Dossier -->
              <div class="space-y-2.5 pt-4 border-t border-slate-100 text-xs text-slate-600">
                <div class="flex items-start gap-2.5">
                  <MapPin class="w-4 h-4 text-blue-700 shrink-0 mt-0.5" />
                  <span class="font-medium text-slate-800">{{ getAddress(city) }}</span>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-1">
                  <a 
                    v-if="city.phone" 
                    :href="'tel:' + city.phone" 
                    class="inline-flex items-center gap-1.5 font-mono font-bold text-blue-700 hover:text-blue-900"
                  >
                    <Phone class="w-3.5 h-3.5" />
                    <span>{{ city.phone }}</span>
                  </a>

                  <a 
                    v-if="city.email" 
                    :href="'mailto:' + city.email" 
                    class="inline-flex items-center gap-1.5 font-mono text-slate-600 hover:text-blue-700"
                  >
                    <Mail class="w-3.5 h-3.5" />
                    <span>{{ city.email }}</span>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Action Footer -->
          <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <router-link 
              :to="'/government/offices?sub_city_id=' + city.id" 
              class="inline-flex items-center gap-2 text-xs font-black text-blue-900 hover:text-blue-700 transition-colors uppercase tracking-wider"
            >
              <span>{{ langStore.currentLang === 'am' ? 'በክፍለ ከተማው ያሉ መምሪያዎችን ተመልከት' : 'View Sector Bureaus in this Sub-City' }}</span>
              <ArrowRight class="w-4 h-4 text-amber-600 group-hover:translate-x-1 transition-transform" />
            </router-link>
            
            <span class="text-[10px] font-mono font-bold text-slate-400 uppercase">
              Chartered Zone
            </span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Landmark class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No sub-cities found</h3>
        <p class="text-xs text-slate-500 mt-1">Sub-city charter records will appear here once registered.</p>
      </div>
    </div>
  </div>
</template>
