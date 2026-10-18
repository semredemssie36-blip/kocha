<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  Building2, 
  Search, 
  Phone, 
  Mail, 
  MapPin, 
  Clock, 
  Landmark, 
  Filter, 
  ChevronRight, 
  ExternalLink,
  Layers,
  PhoneCall
} from 'lucide-vue-next'

const route = useRoute()
const langStore = useLanguageStore()
const offices = ref([])
const subCities = ref([])
const searchQuery = ref('')
const selectedSubCity = ref('all')
const loading = ref(true)
const page = ref(1)
const perPage = ref(9)

const totalPages = computed(() => Math.ceil(filteredOffices.value.length / perPage.value) || 1)

const filteredOffices = computed(() => {
  return offices.value.filter(o => {
    // Sub-city filter
    if (selectedSubCity.value !== 'all' && o.sub_city_id != selectedSubCity.value) {
      return false
    }

    // Search query filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const name = (getOfficeName(o) || '').toLowerCase()
      const room = (o.room_number || '').toLowerCase()
      const loc = (getBuildingLocation(o) || '').toLowerCase()
      const phone = (o.phone || '').toLowerCase()
      if (!name.includes(q) && !room.includes(q) && !loc.includes(q) && !phone.includes(q)) return false
    }

    return true
  })
})

const paginatedOffices = computed(() => {
  const start = (page.value - 1) * perPage.value
  return filteredOffices.value.slice(start, start + perPage.value)
})

function getOfficeName(office) {
  const lang = langStore.currentLang
  if (lang === 'am') return office.name_am || office.name_en
  if (lang === 'om') return office.name_om || office.name_en
  return office.name_en || office.name_am
}

function getBuildingLocation(office) {
  const lang = langStore.currentLang
  if (lang === 'am') return office.building_location_am || office.building_location_en || ''
  if (lang === 'om') return office.building_location_om || office.building_location_en || ''
  return office.building_location_en || office.building_location_am || ''
}

function getWorkingHours(office) {
  const lang = langStore.currentLang
  if (lang === 'am') return office.working_hours_am || office.working_hours_en || 'ከሰኞ - አርብ (2:30 - 11:30)'
  if (lang === 'om') return office.working_hours_om || office.working_hours_en || 'Wiixata - Jimmata (2:30 - 11:30)'
  return office.working_hours_en || office.working_hours_am || 'Mon - Fri (8:30 AM - 5:30 PM)'
}

async function fetchInitialData() {
  loading.value = true
  page.value = 1
  try {
    const [officesRes, subCitiesRes] = await Promise.all([
      api.get('/offices'),
      api.get('/sub-cities')
    ])
    offices.value = Array.isArray(officesRes.data) ? officesRes.data : (officesRes.data?.data || [])
    subCities.value = Array.isArray(subCitiesRes.data) ? subCitiesRes.data : []

    // If query has sub_city_id
    if (route.query.sub_city_id) {
      selectedSubCity.value = route.query.sub_city_id
    }
  } catch (err) {
    console.error('Failed to load offices and sub-cities:', err)
  } finally {
    loading.value = false
  }
}

watch(selectedSubCity, () => {
  page.value = 1
})

watch(searchQuery, () => {
  page.value = 1
})

onMounted(() => {
  fetchInitialData()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/hotel.jpg" alt="Offices Directory" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የከተማው መምሪያዎችና ተቋማት ማውጫ' : 'Municipal Sector Offices & Departmental Directory' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ አስተዳደር የ 22ቱ ዘርፍ መምሪያዎች፣ ተቋማትና የህዝብ አገልግሎት መስጫ ዴስኮች ይፋዊ ህንፃ፣ የክፍል ቁጥርና ቀጥታ የስልክ መስመሮች ማውጫ።'
            : 'Official directory of Kombolcha municipal sector bureaus, room numbers, physical building floors, and direct telephone extensions.'
          }}
        </p>

        <!-- Directory Analytics Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Cataloged Sector Bureaus</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ offices.length }} Bureaus</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Sub-City Hubs</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-blue-300">{{ subCities.length }} Zones</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Office Inquiries</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">Direct Phone Lines</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Standard Office Hours</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">8:30 AM - 5:30 PM</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        
        <!-- Search Input -->
        <div class="relative flex-1 min-w-[280px]">
          <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="langStore.currentLang === 'am' ? 'በመምሪያ ስም፣ በህንፃ ወይም በክፍል ቁጥር ፈልግ...' : 'Search by Bureau Name, Building, Room Number, or Phone...'" 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>

        <!-- Sub-City Selector -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button 
            @click="selectedSubCity = 'all'" 
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap',
              selectedSubCity === 'all' ? 'bg-blue-900 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
            ]"
          >
            All Bureaus ({{ offices.length }})
          </button>
          <button 
            v-for="sub in subCities" 
            :key="sub.id" 
            @click="selectedSubCity = sub.id" 
            :class="[
              'px-3.5 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap',
              selectedSubCity == sub.id ? 'bg-blue-900 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
            ]"
          >
            {{ sub.name_am || sub.name_en }}
          </button>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-3xl border border-slate-200 p-6 animate-pulse h-52"></div>
      </div>

      <!-- Offices Grid -->
      <div v-else-if="filteredOffices.length > 0" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="office in paginatedOffices" 
            :key="office.id" 
            class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
          >
            <div>
              <!-- Office Header -->
              <div class="flex items-start justify-between gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100 group-hover:bg-blue-900 group-hover:text-amber-400 transition-colors">
                  <Building2 class="w-5 h-5" />
                </div>
                <span v-if="office.room_number" class="px-2.5 py-1 rounded-lg bg-slate-900 text-amber-300 font-mono text-[11px] font-black shadow-2xs">
                  Room {{ office.room_number }}
                </span>
              </div>

              <!-- Bureau Name -->
              <h3 class="text-base font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug mb-3">
                {{ getOfficeName(office) }}
              </h3>

              <!-- Location & Hours Specs -->
              <div class="space-y-2.5 text-xs text-slate-600 border-t border-slate-100 pt-3">
                <div class="flex items-start gap-2">
                  <MapPin class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" />
                  <span class="leading-relaxed">{{ getBuildingLocation(office) }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <Clock class="w-4 h-4 text-slate-400 shrink-0" />
                  <span>{{ getWorkingHours(office) }}</span>
                </div>
              </div>
            </div>

            <!-- Direct Contact Footer -->
            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
              <a 
                v-if="office.phone"
                :href="'tel:' + office.phone" 
                class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-blue-700 hover:underline"
              >
                <Phone class="w-3.5 h-3.5 text-emerald-600" />
                <span>{{ office.phone }}</span>
              </a>
              <span v-else class="text-xs text-slate-400 font-mono">HQ Switchboard: Ext. {{ office.id }}</span>

              <a 
                v-if="office.email" 
                :href="'mailto:' + office.email" 
                class="p-2 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 transition-colors"
                title="Send Bureau Email"
              >
                <Mail class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>

        <!-- Pagination Controls -->
        <div v-if="filteredOffices.length > perPage" class="p-4 px-6 bg-white rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600 shadow-2xs">
          <div>
            Showing <span class="font-bold text-slate-900">{{ (page - 1) * perPage + 1 }}</span> to 
            <span class="font-bold text-slate-900">{{ Math.min(page * perPage, filteredOffices.length) }}</span> of 
            <span class="font-bold text-slate-900">{{ filteredOffices.length }}</span> bureaus
          </div>
          <div class="flex items-center gap-1.5">
            <button 
              :disabled="page === 1" 
              @click="page--; window.scrollTo({ top: 0, behavior: 'smooth' })"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
            >
              Previous
            </button>
            <button 
              v-for="p in totalPages" 
              :key="p"
              @click="page = p; window.scrollTo({ top: 0, behavior: 'smooth' })"
              :class="[
                'w-8 h-8 rounded-xl font-black transition-colors cursor-pointer text-xs',
                page === p ? 'bg-blue-900 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-50 text-slate-700'
              ]"
            >
              {{ p }}
            </button>
            <button 
              :disabled="page === totalPages" 
              @click="page++; window.scrollTo({ top: 0, behavior: 'smooth' })"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Building2 class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No sector offices match your search</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Try clearing your search query or choosing "All Bureaus".</p>
        <button 
          @click="searchQuery = ''; selectedSubCity = 'all'" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          Reset Filters
        </button>
      </div>
    </div>
  </div>
</template>
