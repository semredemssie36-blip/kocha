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
  PhoneCall,
  Sparkles,
  User,
  GraduationCap,
  Compass,
  Award,
  CheckCircle2,
  X,
  Share2,
  Share,
  Send,
  Video,
  Hospital,
  ShieldAlert,
  Droplets
} from 'lucide-vue-next'

const route = useRoute()
const langStore = useLanguageStore()
const offices = ref([])
const subCities = ref([])
const searchQuery = ref('')
const selectedCategory = ref('all') // 'all' | 'city_hall' | 'sector_complex' | 'health_police' | 'utilities'
const selectedOffice = ref(null)
const showDetailModal = ref(false)
const loading = ref(true)

async function fetchInitialData() {
  loading.value = true
  try {
    const [officesRes, subCitiesRes] = await Promise.all([
      api.get('/offices'),
      api.get('/sub-cities')
    ])
    offices.value = Array.isArray(officesRes.data) ? officesRes.data : (officesRes.data?.data || [])
    subCities.value = Array.isArray(subCitiesRes.data) ? subCitiesRes.data : []
  } catch (err) {
    console.error('Failed to load offices and sub-cities:', err)
  } finally {
    loading.value = false
  }
}

function getOfficeName(office) {
  if (!office) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return office.name_am || office.name_en
  if (lang === 'om') return office.name_om || office.name_en
  return office.name_en || office.name_am
}

function getBuildingLocation(office) {
  if (!office) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return office.building_location_am || office.building_location_en || ''
  if (lang === 'om') return office.building_location_om || office.building_location_en || ''
  return office.building_location_en || office.building_location_am || ''
}

function getWorkingHours(office) {
  if (!office) return '8:30 AM - 5:30 PM'
  const lang = langStore.currentLang
  if (lang === 'am') return office.working_hours_am || office.working_hours_en || 'ከሰኞ - አርብ (2:00 - 11:30)'
  if (lang === 'om') return office.working_hours_om || office.working_hours_en || 'Wiixata - Jimmata (2:00 - 11:30)'
  return office.working_hours_en || office.working_hours_am || 'Mon - Fri (8:00 AM - 5:30 PM)'
}

function getHeadName(office) {
  if (!office) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return office.head_name_am || office.head_name_en || ''
  if (lang === 'om') return office.head_name_om || office.head_name_en || ''
  return office.head_name_en || office.head_name_am || ''
}

function getVision(office) {
  if (!office) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return office.vision_am || office.vision_en || ''
  if (lang === 'om') return office.vision_om || office.vision_en || ''
  return office.vision_en || office.vision_am || ''
}

function getMission(office) {
  if (!office) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return office.mission_am || office.mission_en || ''
  if (lang === 'om') return office.mission_om || office.mission_en || ''
  return office.mission_en || office.mission_am || ''
}

function getValues(office) {
  if (!office) return []
  const lang = langStore.currentLang
  let raw = office.values_am
  if (lang === 'en' && office.values_en) raw = office.values_en
  if (lang === 'om' && office.values_om) raw = office.values_om

  if (Array.isArray(raw)) return raw
  if (typeof raw === 'string') {
    try {
      return JSON.parse(raw)
    } catch {
      return []
    }
  }
  return []
}

function categorizeOffice(office) {
  const loc = (office.building_location_am || office.building_location_en || '').toLowerCase()
  const name = (office.name_am || office.name_en || '').toLowerCase()

  if (name.includes('ከንቲባ') || name.includes('ምክር ቤት') || name.includes('ኮሙኒኬሽን') || name.includes('መሬት') || name.includes('ፋይናንስ') || name.includes('ትምህርት') || name.includes('ሴፍትኔት') || name.includes('ቅሬታ')) {
    return 'city_hall'
  }
  if (loc.includes('ሴክተር') || name.includes('መንገዶች') || name.includes('ግብርና') || name.includes('ኢንዱስትሪ') || name.includes('ህብረት') || name.includes('እንስሳት') || name.includes('ሰላም') || name.includes('ስራና ስልጠና')) {
    return 'sector_complex'
  }
  if (name.includes('ጤና') || name.includes('ፖሊስ') || name.includes('ሚሊሻ') || name.includes('ፍትህ')) {
    return 'health_police'
  }
  if (name.includes('ውሃ') || name.includes('ገቢዎች') || name.includes('ደንብ') || name.includes('ትራንስፖርት') || name.includes('ቲቪኢቲ')) {
    return 'utilities'
  }
  return 'general'
}

const filteredOffices = computed(() => {
  return offices.value.filter(o => {
    // Category filter
    if (selectedCategory.value !== 'all') {
      const cat = categorizeOffice(o)
      if (cat !== selectedCategory.value) return false
    }

    // Search query filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const name = (getOfficeName(o) || '').toLowerCase()
      const head = (getHeadName(o) || '').toLowerCase()
      const room = (o.room_number || '').toLowerCase()
      const loc = (getBuildingLocation(o) || '').toLowerCase()
      const phone = (o.phone || '').toLowerCase()
      if (!name.includes(q) && !head.includes(q) && !room.includes(q) && !loc.includes(q) && !phone.includes(q)) return false
    }

    return true
  })
})

function openDetail(office) {
  selectedOffice.value = office
  showDetailModal.value = true
}

function closeDetail() {
  showDetailModal.value = false
  selectedOffice.value = null
}

onMounted(() => {
  fetchInitialData()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/industry_park_2.jpg" alt="Municipal Sector Bureaus" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="absolute inset-0 bg-[radial-gradient(#1e3a8a_1px,transparent_1px)] [background-size:24px_24px] opacity-25"></div>
      
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-black uppercase tracking-wider mb-4">
          <Sparkles class="w-3.5 h-3.5 text-amber-400" />
          <span>{{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር ሴክተር መምሪያዎች ይፋዊ ማውጫ' : 'Official Municipal Sector Bureaus & Departmental Directory' }}</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የከተማው 30 መምሪያዎችና ተቋማት ማውጫ' : '30 Municipal Sector Bureaus & Institutional Directory' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ አስተዳደር ዋና ማዘጋጃ ቤት፣ የሴክተር መ/ቤቶች ህንፃ፣ የፍትህና ፖሊስ ግቢዎች ይፋዊ ፎቅ፣ የክፍል ቁጥር፣ የቀጥታ ስልክ መስመሮች፣ የኃላፊዎች መረጃ እንዲሁም ራዕይና ተልዕኮ።'
            : 'Comprehensive departmental registry cataloging all 30 municipal sector bureaus, room and floor allocations, direct desk telephone extensions, executive leadership, and statutory mandates.'
          }}
        </p>

        <!-- Directory Analytics Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Cataloged Sector Bureaus</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ offices.length }} Bureaus</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Direct Extension Desks</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-blue-300">100% Direct Call</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Floor & Room Locator</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">Floors 1 - 5</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Public Office Hours</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">8:00 AM - 5:30 PM</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm mb-8 space-y-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
          
          <!-- Category Tabs -->
          <div class="flex flex-wrap items-center gap-2">
            <button 
              @click="selectedCategory = 'all'" 
              :class="selectedCategory === 'all' ? 'bg-[#1C398E] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Building2 class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ሁሉም 30 መምሪያዎች' : 'All 30 Bureaus' }}</span>
              <span class="ml-1 text-[11px] px-1.5 py-0.5 rounded-full" :class="selectedCategory === 'all' ? 'bg-white/20' : 'bg-slate-200 text-slate-600'">{{ offices.length }}</span>
            </button>

            <button 
              @click="selectedCategory = 'city_hall'" 
              :class="selectedCategory === 'city_hall' ? 'bg-blue-700 text-white shadow-md' : 'bg-blue-50 text-blue-900 hover:bg-blue-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Landmark class="w-4 h-4 text-blue-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ዋና ማዘጋጃ ቤት' : 'Main City Hall' }}</span>
            </button>

            <button 
              @click="selectedCategory = 'sector_complex'" 
              :class="selectedCategory === 'sector_complex' ? 'bg-amber-700 text-white shadow-md' : 'bg-amber-50 text-amber-900 hover:bg-amber-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Layers class="w-4 h-4 text-amber-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ሴክተር መ/ቤቶች ህንፃ (5 ፎቆች)' : 'Sector Offices Complex' }}</span>
            </button>

            <button 
              @click="selectedCategory = 'health_police'" 
              :class="selectedCategory === 'health_police' ? 'bg-rose-700 text-white shadow-md' : 'bg-rose-50 text-rose-900 hover:bg-rose-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <ShieldAlert class="w-4 h-4 text-rose-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ጤና፣ ፖሊስና ፍትህ' : 'Health, Police & Justice' }}</span>
            </button>

            <button 
              @click="selectedCategory = 'utilities'" 
              :class="selectedCategory === 'utilities' ? 'bg-emerald-700 text-white shadow-md' : 'bg-emerald-50 text-emerald-900 hover:bg-emerald-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Droplets class="w-4 h-4 text-emerald-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ውሃ፣ ደንብና አገልግሎቶች' : 'Utilities & Enforcement' }}</span>
            </button>
          </div>

          <!-- Search Input -->
          <div class="relative w-full lg:w-84">
            <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.currentLang === 'am' ? 'በመምሪያ፣ ኃላፊ፣ ፎቅ ወይም ስልክ ፈልግ...' : 'Search by Bureau, Head, Room, or Phone...'" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium"
            />
          </div>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-3xl border border-slate-200 p-6 animate-pulse h-56"></div>
      </div>

      <!-- Offices Grid -->
      <div v-else-if="filteredOffices.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="office in filteredOffices" 
          :key="office.id" 
          class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:border-[#1C398E] hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
        >
          <div>
            <!-- Office Header Strip -->
            <div class="flex items-start justify-between gap-3 mb-4">
              <div class="w-11 h-11 rounded-2xl bg-blue-50 text-[#1C398E] flex items-center justify-center shrink-0 border border-blue-100 group-hover:bg-[#1C398E] group-hover:text-amber-400 transition-colors shadow-xs">
                <Building2 class="w-5 h-5" />
              </div>
              <span v-if="office.room_number" class="px-2.5 py-1 rounded-xl bg-slate-900 text-amber-300 font-mono text-[11px] font-black shadow-xs">
                Room {{ office.room_number }}
              </span>
            </div>

            <!-- Bureau Name -->
            <h3 class="text-base font-black text-slate-900 group-hover:text-[#1C398E] transition-colors leading-snug mb-2">
              {{ getOfficeName(office) }}
            </h3>

            <!-- Department Head if exists -->
            <div v-if="getHeadName(office)" class="flex items-center gap-2 mb-3 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 text-xs">
              <User class="w-3.5 h-3.5 text-slate-500 shrink-0" />
              <div class="truncate">
                <span class="font-bold text-slate-800">{{ getHeadName(office) }}</span>
                <span v-if="office.head_education_am || office.head_education_en" class="text-slate-500 text-[10px] ml-1 font-medium">({{ office.head_education_am || office.head_education_en }})</span>
              </div>
            </div>

            <!-- Location & Hours Specs -->
            <div class="space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-3">
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

          <!-- Direct Contact & Inspect Footer -->
          <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2">
              <a 
                v-if="office.phone"
                :href="'tel:' + office.phone" 
                class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-[#1C398E] hover:underline bg-blue-50/70 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg transition-colors"
                title="Call direct office line"
              >
                <Phone class="w-3.5 h-3.5 text-emerald-600" />
                <span>{{ office.phone }}</span>
              </a>

              <a 
                v-if="office.email" 
                :href="'mailto:' + office.email" 
                class="p-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 transition-colors"
                title="Send Bureau Email"
              >
                <Mail class="w-3.5 h-3.5" />
              </a>
            </div>

            <!-- Inspect Button -->
            <button 
              @click="openDetail(office)"
              class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-[#1C398E] text-white text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer shrink-0"
            >
              <span>{{ langStore.currentLang === 'am' ? 'ዝርዝር' : 'Details' }}</span>
              <ChevronRight class="w-3.5 h-3.5 text-amber-400" />
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Building2 class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No sector offices match your search</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Try clearing your search query or choosing "All 30 Bureaus".</p>
        <button 
          @click="searchQuery = ''; selectedCategory = 'all'" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors cursor-pointer"
        >
          Reset Filters
        </button>
      </div>

    </div>

    <!-- Office Detailed Profile Modal -->
    <div v-if="showDetailModal && selectedOffice" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
      <div class="bg-white rounded-3xl border border-slate-200 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl relative">
        
        <!-- Modal Header Strip -->
        <div class="bg-gradient-to-r from-slate-900 to-[#071E3D] text-white p-6 sm:p-7 rounded-t-3xl relative">
          <button 
            @click="closeDetail"
            class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
          >
            <X class="w-4 h-4" />
          </button>

          <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-amber-400/20 text-amber-400 text-[10px] font-black uppercase tracking-wider mb-2">
            <span>Kombolcha Metropolitan Civic Directorate</span>
          </div>

          <h3 class="text-xl sm:text-2xl font-black text-white leading-tight mb-2">
            {{ getOfficeName(selectedOffice) }}
          </h3>

          <div class="flex flex-wrap items-center gap-3 text-xs text-slate-300 font-mono pt-2 border-t border-slate-800">
            <span v-if="selectedOffice.room_number" class="bg-amber-400 text-slate-950 px-2 py-0.5 rounded-md font-bold">
              Room {{ selectedOffice.room_number }}
            </span>
            <span class="flex items-center gap-1">
              <MapPin class="w-3.5 h-3.5 text-blue-400" />
              {{ getBuildingLocation(selectedOffice) }}
            </span>
          </div>
        </div>

        <!-- Modal Body Content -->
        <div class="p-6 sm:p-8 space-y-6 text-slate-800">
          
          <!-- Department Head & Contacts Card -->
          <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-2xl bg-[#1C398E] text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-md">
                <User class="w-6 h-6" />
              </div>
              <div>
                <span class="text-[10px] font-black uppercase text-blue-900 tracking-wider block">
                  {{ selectedOffice.head_title_am || selectedOffice.head_title_en || 'የመምሪያው ኃላፊ' }}
                </span>
                <h4 class="text-base font-black text-slate-900">{{ getHeadName(selectedOffice) || 'Executive Directorate Team' }}</h4>
                <div v-if="selectedOffice.head_education_am || selectedOffice.head_education_en" class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                  <GraduationCap class="w-3.5 h-3.5 text-slate-400" />
                  <span>{{ selectedOffice.head_education_am || selectedOffice.head_education_en }}</span>
                </div>
              </div>
            </div>

            <!-- Direct Contact Buttons -->
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
              <a 
                v-if="selectedOffice.phone"
                :href="'tel:' + selectedOffice.phone"
                class="flex-1 sm:flex-none px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-mono font-bold text-xs transition-colors flex items-center justify-center gap-1.5"
              >
                <Phone class="w-3.5 h-3.5" />
                <span>{{ selectedOffice.phone }}</span>
              </a>

              <a 
                v-if="selectedOffice.email || selectedOffice.head_email"
                :href="'mailto:' + (selectedOffice.head_email || selectedOffice.email)"
                class="p-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-blue-700 transition-colors"
                title="Send Official Email"
              >
                <Mail class="w-4 h-4" />
              </a>

              <a 
                v-if="selectedOffice.facebook_url"
                :href="selectedOffice.facebook_url"
                target="_blank"
                rel="noopener noreferrer"
                class="p-2 rounded-xl bg-white border border-slate-200 text-blue-600 hover:bg-blue-50 transition-colors"
                title="Official Facebook Page"
              >
                <Share2 class="w-4 h-4" />
              </a>

              <a 
                v-if="selectedOffice.telegram_url"
                :href="selectedOffice.telegram_url"
                target="_blank"
                rel="noopener noreferrer"
                class="p-2 rounded-xl bg-white border border-slate-200 text-sky-500 hover:bg-sky-50 transition-colors"
                title="Official Telegram Channel"
              >
                <Send class="w-4 h-4" />
              </a>
            </div>
          </div>

          <!-- Additional Department Desks & Extensions (if exists) -->
          <div v-if="selectedOffice.additional_phones && selectedOffice.additional_phones.length > 0" class="space-y-2">
            <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider">
              {{ langStore.currentLang === 'am' ? 'የቅርንጫፎችና ዴስኮች ቀጥታ ስልኮች' : 'Direct Desk Telephone Extensions' }}
            </h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div 
                v-for="(dp, idx) in selectedOffice.additional_phones" 
                :key="idx"
                class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs font-mono"
              >
                <span class="text-slate-700 font-sans font-bold text-[11px] truncate">{{ dp.title_am || dp.title_en }}</span>
                <a :href="'tel:' + dp.phone" class="text-blue-700 hover:underline font-bold shrink-0 ml-2">{{ dp.phone }}</a>
              </div>
            </div>
          </div>

          <!-- Room Breakdown (if exists) -->
          <div v-if="selectedOffice.room_breakdown && selectedOffice.room_breakdown.length > 0" class="space-y-2">
            <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider">
              {{ langStore.currentLang === 'am' ? 'የክፍሎችና ቡድኖች ማውጫ' : 'Floor & Room Directory' }}
            </h5>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div 
                v-for="(rb, idx) in selectedOffice.room_breakdown" 
                :key="idx"
                class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs"
              >
                <span class="text-slate-800 font-bold text-[11px]">{{ rb.function_am || rb.function_en }}</span>
                <span class="px-2 py-0.5 rounded bg-slate-900 text-amber-300 font-mono text-[10px] font-black shrink-0 ml-2">Room {{ rb.room }}</span>
              </div>
            </div>
          </div>

          <!-- Facility Network (for Health Centers, Hospitals, Police Precincts) -->
          <div v-if="selectedOffice.facility_network && selectedOffice.facility_network.length > 0" class="space-y-2">
            <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider">
              {{ langStore.currentLang === 'am' ? 'ተጠሪ ማዕከላትና ድንገተኛ አደጋ አስተባባሪዎች' : 'Decentralized Network & Emergency Coordinators' }}
            </h5>
            <div class="space-y-1.5">
              <div 
                v-for="(fn, idx) in selectedOffice.facility_network" 
                :key="idx"
                class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs"
              >
                <div class="font-bold text-slate-900">{{ fn.name_am || fn.name_en }}</div>
                <div class="text-slate-600 text-[11px] font-mono mt-0.5">{{ fn.contact }}</div>
              </div>
            </div>
          </div>

          <!-- Vision & Mission -->
          <div class="space-y-4 pt-2">
            <div v-if="getVision(selectedOffice)" class="bg-amber-50/60 border border-amber-200/80 p-4 rounded-2xl">
              <div class="flex items-center gap-1.5 text-xs font-black uppercase text-amber-900 mb-1">
                <Compass class="w-4 h-4 text-amber-600" />
                <span>{{ langStore.currentLang === 'am' ? 'የተቋሙ ራዕይ' : 'Institutional Vision' }}</span>
              </div>
              <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                {{ getVision(selectedOffice) }}
              </p>
            </div>

            <div v-if="getMission(selectedOffice)" class="bg-blue-50/60 border border-blue-200/80 p-4 rounded-2xl">
              <div class="flex items-center gap-1.5 text-xs font-black uppercase text-blue-900 mb-1">
                <Award class="w-4 h-4 text-blue-600" />
                <span>{{ langStore.currentLang === 'am' ? 'የተቋሙ ተልዕኮ' : 'Institutional Mission' }}</span>
              </div>
              <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal">
                {{ getMission(selectedOffice) }}
              </p>
            </div>
          </div>

          <!-- Core Values Badges -->
          <div v-if="getValues(selectedOffice).length > 0" class="space-y-2">
            <h5 class="text-xs font-black uppercase text-slate-500 tracking-wider">
              {{ langStore.currentLang === 'am' ? 'መሰረታዊ እሴቶች' : 'Core Operating Values' }}
            </h5>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="(val, idx) in getValues(selectedOffice)" 
                :key="idx"
                class="px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-xs font-bold border border-slate-200 flex items-center gap-1.5"
              >
                <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
                <span>{{ val }}</span>
              </span>
            </div>
          </div>

        </div>

        <!-- Modal Footer -->
        <div class="p-4 px-6 bg-slate-100 border-t border-slate-200 rounded-b-3xl flex items-center justify-between text-xs">
          <span class="text-slate-500">{{ getWorkingHours(selectedOffice) }}</span>
          <button 
            @click="closeDetail"
            class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-colors cursor-pointer"
          >
            Close Directory Card
          </button>
        </div>

      </div>
    </div>

  </div>
</template>
