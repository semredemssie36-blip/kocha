<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  FileText, 
  CheckCircle2, 
  Clock, 
  Banknote, 
  ChevronRight, 
  Layers, 
  Search, 
  ShieldCheck, 
  MapPin, 
  PhoneCall, 
  FileCheck,
  Check,
  Info,
  Droplets,
  Wrench,
  Truck,
  Zap,
  UserCheck,
  Store,
  Briefcase,
  AlertTriangle,
  Printer,
  Sparkles,
  Phone,
  Flame,
  Building
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const services = ref([])
const selectedService = ref(null)
const loading = ref(true)
const detailLoading = ref(false)
const searchQuery = ref('')
const activeCategory = ref('all') // 'all' | 'water' | 'electric' | 'trade' | 'enforcement'
const completedSteps = ref(new Set())

// Map icon names from database to Lucide components
const iconMap = {
  Droplets,
  Wrench,
  Truck,
  Zap,
  UserCheck,
  Flame,
  Store,
  Briefcase,
  ShieldCheck,
  FileCheck,
  FileText
}

function getServiceIcon(iconName) {
  return iconMap[iconName] || FileText
}

async function fetchServices() {
  loading.value = true
  try {
    const res = await api.get('/services')
    services.value = Array.isArray(res.data) ? res.data : []
    if (services.value.length > 0) {
      loadServiceDetail(services.value[0].slug)
    }
  } catch (err) {
    console.error('Failed to load services:', err)
  } finally {
    loading.value = false
  }
}

async function loadServiceDetail(slug) {
  if (!slug) return
  detailLoading.value = true
  try {
    const res = await api.get(`/services/${slug}`)
    selectedService.value = res.data
    completedSteps.value.clear()
  } catch (err) {
    console.error('Failed to load service detail:', err)
  } finally {
    detailLoading.value = false
  }
}

function getServiceTitle(item) {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am || item.title_en
  if (lang === 'om') return item.title_om || item.title_en
  return item.title_en || item.title_am
}

function getServiceSummary(item) {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.summary_am || item.summary_en || ''
  if (lang === 'om') return item.summary_om || item.summary_en || ''
  return item.summary_en || item.summary_am || ''
}

function getServiceTime(item) {
  if (!item) return '1-2 Days'
  const lang = langStore.currentLang
  if (lang === 'am') return item.estimated_time_am || item.estimated_time_en || '1-2 የስራ ቀናት'
  if (lang === 'om') return item.estimated_time_om || item.estimated_time_en || 'Guyyoota 1-2'
  return item.estimated_time_en || item.estimated_time_am || '1-2 Business Days'
}

function getStepTitle(step) {
  const lang = langStore.currentLang
  if (lang === 'am') return step.title_am || step.title_en
  if (lang === 'om') return step.title_om || step.title_en
  return step.title_en || step.title_am
}

function getStepInstruction(step) {
  const lang = langStore.currentLang
  if (lang === 'am') return step.instruction_am || step.instruction_en
  if (lang === 'om') return step.instruction_om || step.instruction_en
  return step.instruction_en || step.instruction_am
}

function toggleStepCheck(stepId) {
  if (completedSteps.value.has(stepId)) {
    completedSteps.value.delete(stepId)
  } else {
    completedSteps.value.add(stepId)
  }
}

function categorizeService(service) {
  const slug = service.slug || ''
  if (slug.startsWith('water-') || slug.startsWith('vacuum-')) return 'water'
  if (slug.startsWith('electric-')) return 'electric'
  if (slug.startsWith('trade-')) return 'trade'
  if (slug.startsWith('code-')) return 'enforcement'
  return 'general'
}

const filteredServices = computed(() => {
  return services.value.filter(s => {
    // Category filter
    if (activeCategory.value !== 'all') {
      const cat = categorizeService(s)
      if (cat !== activeCategory.value) return false
    }

    // Search filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const title = (getServiceTitle(s) || '').toLowerCase()
      const summary = (getServiceSummary(s) || '').toLowerCase()
      if (!title.includes(q) && !summary.includes(q)) return false
    }

    return true
  })
})

const completionPercentage = computed(() => {
  if (!selectedService.value?.steps || selectedService.value.steps.length === 0) return 0
  return Math.round((completedSteps.value.size / selectedService.value.steps.length) * 100)
})

function triggerPrint() {
  window.print()
}

onMounted(() => {
  fetchServices()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-slate-950 text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/city_image_2.jpg" alt="Municipal Public Services" class="absolute inset-0 w-full h-full object-cover object-center" />
      <!-- Subtle clear scrim just like home page -->
      <div class="absolute inset-0 bg-gradient-to-r from-slate-950/70 via-slate-950/35 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/25"></div>
      
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        

        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am' ? 'የዜጎች አገልግሎቶችና የመገልገያ መስፈርቶች' : 'Citizen E-Services & Procedural Manual' }}
        </h1>
        <p class="text-white text-sm sm:text-base max-w-3xl leading-relaxed font-semibold drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am'
            ? 'የንፁህ መጠጥ ውሃ መስመር ዝርጋታ፣ የፍሳሽ ቦቴ ማስወገድ፣ የኤሌክትሪክ ኃይል ግንኙነትና ስም ዝውውር፣ የንግድ ምዝገባና እድሳት እንዲሁም የደንብ ማስከበር ደረጃ በደረጃ ህጋዊ ሂደቶች፣ መስፈርቶችና የጊዜ ሰሌዳ።'
            : 'Authoritative procedural steps, required documentary dossiers, guaranteed turnaround windows, and official municipal hotline contacts for utilities, trade licensing, and municipal enforcement.'
          }}
        </p>

        <!-- Citizen Guarantee Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20">
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Official Services Catalog</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ services.length }} Procedures</div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-blue-500/40 shadow-xl">
            <span class="text-blue-400 text-xs block font-bold mb-1">Standard Turnaround</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">24h - 5 Days</div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/40 shadow-xl">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Direct Citizen Hotlines</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">24/7 Leak & Grid</div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Decentralized Centers</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">4 Sub-Cities</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Official Emergency & Utility Desks Notice Strip -->
    <div class="bg-slate-900 border-b border-slate-800 py-3 px-4 sm:px-8 text-xs text-slate-300">
      <div class="max-w-[1700px] mx-auto flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-amber-400 font-black uppercase tracking-wider">
          <PhoneCall class="w-4 h-4 shrink-0" />
          <span>{{ langStore.currentLang === 'am' ? 'የአደጋና ድንገተኛ መስመሮች:' : 'Official Emergency & Leak Desks:' }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-3 text-xs font-mono">
          <div class="flex flex-wrap items-center gap-1.5 bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-700">
            <Droplets class="w-3.5 h-3.5 text-blue-400 shrink-0" />
            <span class="text-slate-400 font-sans font-bold">Water Leaks 24/7:</span>
            <a href="tel:0912746964" class="text-white hover:text-amber-400 font-bold">09 12 74 69 64</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0910539584" class="text-white hover:text-amber-400 font-bold">09 10 53 95 84</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0932029686" class="text-white hover:text-amber-400 font-bold">09 32 02 96 86</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0925894557" class="text-white hover:text-amber-400 font-bold">09 25 89 45 57</a>
          </div>
          <div class="flex items-center gap-1.5 bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-700">
            <Zap class="w-3.5 h-3.5 text-amber-400 shrink-0" />
            <span class="text-slate-400 font-sans font-bold">Grid:</span>
            <a href="tel:905" class="text-white hover:text-amber-400 font-bold">905</a>
            <span class="text-slate-600">/</span>
            <a href="tel:0335510177" class="text-white hover:text-amber-400 font-bold">033 551 01 77</a>
          </div>
          <div class="flex items-center gap-1.5 bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-700">
            <ShieldCheck class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
            <span class="text-slate-400 font-sans font-bold">Code Enforcement:</span>
            <a href="tel:0333516342" class="text-white hover:text-amber-400 font-bold">033 351 63 42</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Category Filter Tabs & Search Bar -->
      <div class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm mb-8 space-y-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
          
          <!-- Category Tabs -->
          <div class="flex flex-wrap items-center gap-2">
            <button 
              @click="activeCategory = 'all'" 
              :class="activeCategory === 'all' ? 'bg-[#1C398E] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Layers class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ሁሉም አገልግሎቶች' : 'All Services' }}</span>
              <span class="ml-1 text-[11px] px-1.5 py-0.5 rounded-full" :class="activeCategory === 'all' ? 'bg-white/20' : 'bg-slate-200 text-slate-600'">{{ services.length }}</span>
            </button>

            <button 
              @click="activeCategory = 'water'" 
              :class="activeCategory === 'water' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-blue-900 hover:bg-blue-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Droplets class="w-4 h-4 text-blue-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ውሃና ፍሳሽ' : 'Water & Sanitation' }}</span>
            </button>

            <button 
              @click="activeCategory = 'electric'" 
              :class="activeCategory === 'electric' ? 'bg-amber-600 text-white shadow-md' : 'bg-amber-50 text-amber-900 hover:bg-amber-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Zap class="w-4 h-4 text-amber-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ኤሌክትሪክ አገልግሎት' : 'Electric Utility' }}</span>
            </button>

            <button 
              @click="activeCategory = 'trade'" 
              :class="activeCategory === 'trade' ? 'bg-emerald-700 text-white shadow-md' : 'bg-emerald-50 text-emerald-900 hover:bg-emerald-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <Store class="w-4 h-4 text-emerald-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ንግድና ኢንዱስትሪ' : 'Trade & Licensing' }}</span>
            </button>

            <button 
              @click="activeCategory = 'enforcement'" 
              :class="activeCategory === 'enforcement' ? 'bg-indigo-700 text-white shadow-md' : 'bg-indigo-50 text-indigo-900 hover:bg-indigo-100'"
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
            >
              <ShieldCheck class="w-4 h-4 text-indigo-300" />
              <span>{{ langStore.currentLang === 'am' ? 'ደንብ ማስከበር' : 'Code Enforcement' }}</span>
            </button>
          </div>

          <!-- Search Input -->
          <div class="relative w-full lg:w-80">
            <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.currentLang === 'am' ? 'አገልግሎት ፈልግ (ውሃ፣ ቆጣሪ፣ ንግድ፣ ቦቴ)...' : 'Search services (water, meter, trade)...'" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all font-medium"
            />
          </div>
        </div>
      </div>

      <!-- Two-Column Master Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Service Selection Directory (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 p-4 shadow-sm space-y-2">
          <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500">
              {{ langStore.currentLang === 'am' ? 'የአገልግሎቶች ዝርዝር' : 'Service Directory' }} ({{ filteredServices.length }})
            </h3>
            <span class="text-[10px] text-blue-700 font-mono font-bold">{{ langStore.currentLang === 'am' ? 'ለመመልከት ይጫኑ' : 'Click to inspect' }}</span>
          </div>

          <!-- Loading Skeleton -->
          <div v-if="loading" class="space-y-2 p-2">
            <div v-for="n in 6" :key="n" class="h-16 bg-slate-100 rounded-2xl animate-pulse"></div>
          </div>

          <!-- Services List -->
          <div v-else-if="filteredServices.length > 0" class="space-y-2 max-h-[780px] overflow-y-auto pr-1">
            <button
              v-for="service in filteredServices"
              :key="service.id"
              @click="loadServiceDetail(service.slug)"
              :class="[
                'w-full text-left p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-3 border cursor-pointer',
                selectedService && selectedService.service.id === service.id
                  ? 'bg-[#1C398E] text-white border-[#1C398E] shadow-md shadow-blue-950/20 scale-[1.01]'
                  : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200/80 hover:border-slate-300'
              ]"
            >
              <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                  <div 
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm mt-0.5"
                    :class="selectedService && selectedService.service.id === service.id ? 'bg-amber-400 text-slate-950' : 'bg-blue-50 text-[#1C398E]'"
                  >
                    <component :is="getServiceIcon(service.icon_name)" class="w-5 h-5" />
                  </div>
                  <div>
                    <h4 class="text-sm font-black leading-snug line-clamp-2">
                      {{ getServiceTitle(service) }}
                    </h4>
                    <span 
                      class="text-[10px] uppercase font-bold tracking-wider mt-1 block"
                      :class="selectedService && selectedService.service.id === service.id ? 'text-blue-200' : 'text-slate-500'"
                    >
                      {{ service.type === 'step' ? (langStore.currentLang === 'am' ? 'ደረጃ በደረጃ ሂደት' : 'Procedural Guide') : 'General Guidance' }}
                    </span>
                  </div>
                </div>
                <ChevronRight 
                  class="w-4 h-4 shrink-0 transition-transform mt-1" 
                  :class="selectedService && selectedService.service.id === service.id ? 'text-amber-400 translate-x-1' : 'text-slate-400'" 
                />
              </div>

              <!-- Metadata Mini Strip -->
              <div 
                class="flex items-center justify-between text-[11px] pt-2 border-t font-semibold"
                :class="selectedService && selectedService.service.id === service.id ? 'border-white/10 text-slate-200' : 'border-slate-100 text-slate-500'"
              >
                <span class="flex items-center gap-1 font-mono">
                  <Clock class="w-3 h-3 text-amber-400" />
                  {{ getServiceTime(service) }}
                </span>
                <span class="font-mono font-bold">
                  {{ Number(service.fee_amount || 0) > 0 ? 'ETB ' + Number(service.fee_amount).toLocaleString() : (langStore.currentLang === 'am' ? 'በግምት / እንደስራው' : 'Statutory Scale') }}
                </span>
              </div>
            </button>
          </div>

          <!-- Empty Search State -->
          <div v-else class="text-center py-10 px-4 text-slate-400">
            <Layers class="w-8 h-8 mx-auto mb-2 opacity-50" />
            <p class="text-xs">{{ langStore.currentLang === 'am' ? 'ምንም አይነት አገልግሎት አልተገኘም።' : 'No services matching your search filter.' }}</p>
          </div>
        </div>

        <!-- Right Column: Interactive Procedure & Step-by-Step Guide (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-sm relative">
          
          <!-- Detail Loading Spinner -->
          <div v-if="detailLoading" class="text-center py-24 text-slate-400 space-y-3">
            <div class="w-8 h-8 border-3 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
            <p class="text-xs font-bold uppercase tracking-wider">Loading Official Procedure...</p>
          </div>

          <!-- Active Service Guide -->
          <div v-else-if="selectedService && selectedService.service">
            
            <!-- Service Header Proclamation Banner -->
            <div class="bg-gradient-to-br from-slate-900 to-[#07172B] text-white rounded-3xl p-6 sm:p-8 mb-8 border border-slate-800 relative overflow-hidden shadow-md">
              <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-2">
                  <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-wider">
                    {{ selectedService.service.type === 'step' ? (langStore.currentLang === 'am' ? 'ህጋዊ መመሪያና ደረጃዎች' : 'Official Civic Walkthrough') : 'Civic Guide' }}
                  </span>
                  <span class="text-xs font-bold text-slate-300 font-mono">
                    Kombolcha City Authority
                  </span>
                </div>

                <button 
                  @click="triggerPrint"
                  class="flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold px-3 py-1.5 rounded-xl border border-slate-700 transition-colors cursor-pointer"
                  title="Print this service procedure guide"
                >
                  <Printer class="w-3.5 h-3.5" />
                  <span>{{ langStore.currentLang === 'am' ? 'አትም / Print' : 'Print Checklist' }}</span>
                </button>
              </div>

              <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight mb-3">
                {{ getServiceTitle(selectedService.service) }}
              </h2>

              <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-3xl font-medium mb-6">
                {{ getServiceSummary(selectedService.service) }}
              </p>

              <!-- Turnaround & Statutory Fees Pill Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-800">
                <div class="flex items-center gap-3 bg-slate-800/80 p-3.5 rounded-2xl border border-slate-700/80">
                  <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-400 flex items-center justify-center shrink-0">
                    <Clock class="w-5 h-5" />
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">{{ langStore.currentLang === 'am' ? 'የሚፈጀው መደበኛ ጊዜ' : 'Statutory Processing Window' }}</span>
                    <strong class="text-xs sm:text-sm text-white font-mono">{{ getServiceTime(selectedService.service) }}</strong>
                  </div>
                </div>

                <div class="flex items-center gap-3 bg-slate-800/80 p-3.5 rounded-2xl border border-slate-700/80">
                  <div class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <Banknote class="w-5 h-5" />
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">{{ langStore.currentLang === 'am' ? 'ህጋዊ የክፍያ ተመን' : 'Statutory Fee Schedule' }}</span>
                    <strong class="text-xs sm:text-sm text-emerald-400 font-mono font-black">
                      {{ Number(selectedService.service.fee_amount || 0) > 0 ? 'ETB ' + Number(selectedService.service.fee_amount).toLocaleString() : (langStore.currentLang === 'am' ? 'እንደ መስፈርቱና ርቀቱ ይወሰናል' : 'Assessed by Technical Scale') }}
                    </strong>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sequential Step-by-Step Procedure & Checklist -->
            <div v-if="selectedService.steps && selectedService.steps.length > 0" class="space-y-6">
              
              <!-- Checklist Progress Header -->
              <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-xl bg-[#1C398E] text-white flex items-center justify-center font-bold text-xs shrink-0">
                    <FileCheck class="w-4 h-4" />
                  </div>
                  <div>
                    <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-900">
                      {{ langStore.currentLang === 'am' ? 'የአገልግሎቱ ቅደም ተከተሎችና ሰነዶች' : 'Required Procedural Steps & Dossier' }} ({{ selectedService.steps.length }})
                    </h3>
                    <p class="text-[11px] text-slate-600">
                      {{ langStore.currentLang === 'am' ? 'የሚያስፈልጉትን ሰነዶች እያሟሉ ምልክት በማድረግ ያረጋግጡ' : 'Check off items as you prepare your documents to ensure zero-delay submission' }}
                    </p>
                  </div>
                </div>

                <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-xl border border-blue-200 shrink-0">
                  <div class="w-24 bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div 
                      class="bg-emerald-500 h-2 rounded-full transition-all duration-300" 
                      :style="{ width: `${completionPercentage}%` }"
                    ></div>
                  </div>
                  <span class="text-xs font-mono font-bold text-slate-700">
                    {{ completedSteps.size }}/{{ selectedService.steps.length }} ({{ completionPercentage }}%)
                  </span>
                </div>
              </div>

              <!-- Interactive Step Timeline -->
              <div class="space-y-6 pt-2">
                <div 
                  v-for="(step, idx) in selectedService.steps" 
                  :key="step.id || idx"
                  class="relative pl-12 pb-8 border-l-2 last:border-transparent last:pb-0 transition-all duration-200"
                  :class="completedSteps.has(step.id || idx) ? 'border-emerald-400' : 'border-blue-200'"
                >
                  <!-- Numbered Step Badge -->
                  <button 
                    @click="toggleStepCheck(step.id || idx)"
                    class="absolute -left-[19px] top-0 w-9 h-9 rounded-full flex items-center justify-center font-black text-xs transition-all shadow-md cursor-pointer"
                    :class="completedSteps.has(step.id || idx) ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-[#1C398E] text-amber-400 hover:scale-110'"
                    :title="completedSteps.has(step.id || idx) ? 'Mark step as incomplete' : 'Mark step as completed'"
                  >
                    <Check v-if="completedSteps.has(step.id || idx)" class="w-4 h-4" />
                    <span v-else>{{ step.step_number || (idx + 1) }}</span>
                  </button>

                  <!-- Step Card Box -->
                  <div 
                    class="rounded-3xl p-6 border transition-all duration-200"
                    :class="completedSteps.has(step.id || idx) ? 'bg-emerald-50/40 border-emerald-200' : 'bg-slate-50/80 border-slate-200/90 hover:bg-slate-50'"
                  >
                    <div class="flex items-start justify-between gap-3 mb-2">
                      <h4 class="text-base font-black text-slate-900 leading-snug">
                        {{ getStepTitle(step) }}
                      </h4>
                      <button 
                        @click="toggleStepCheck(step.id || idx)"
                        class="text-xs font-bold px-3 py-1.5 rounded-xl border transition-colors cursor-pointer shrink-0 flex items-center gap-1.5"
                        :class="completedSteps.has(step.id || idx) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'"
                      >
                        <Check v-if="completedSteps.has(step.id || idx)" class="w-3.5 h-3.5 text-emerald-700" />
                        <span>{{ completedSteps.has(step.id || idx) ? (langStore.currentLang === 'am' ? 'ተጠናቋል' : 'Ready') : (langStore.currentLang === 'am' ? 'ምልክት አድርግ' : 'Mark Ready') }}</span>
                      </button>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal whitespace-pre-line">
                      {{ getStepInstruction(step) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- General Content Display (for Text-based guidance) -->
            <div v-else-if="selectedService.service.content_en || selectedService.service.content_am" class="prose max-w-none text-slate-700 text-sm leading-relaxed p-6 bg-slate-50 rounded-2xl border border-slate-200">
              <div v-html="langStore.currentLang === 'am' ? selectedService.service.content_am : selectedService.service.content_en"></div>
            </div>

            <!-- Citizen Charter Public Commitment Box -->
            <div class="mt-10 p-6 rounded-3xl bg-slate-900 text-white border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center shrink-0">
                  <ShieldCheck class="w-5 h-5" />
                </div>
                <div>
                  <h5 class="text-sm font-black">{{ langStore.currentLang === 'am' ? 'የዜጎች መብትና አገልግሎት አሰጣጥ ቃል ኪዳን' : 'Citizen Charter & Anti-Corruption Guarantee' }}</h5>
                  <p class="text-xs text-slate-400">{{ langStore.currentLang === 'am' ? 'ያልተገባ እንግልት ወይም ክፍያ ሲጠየቁ በከተማው የፍትህና ደንብ ማስከበር ጽ/ቤት በ 033 551 02 16 ያሳውቁ።' : 'Report any procedural extortion or unauthorized fee to the Ethics Desk at 033 551 02 16.' }}</p>
                </div>
              </div>
              <a 
                href="tel:0335510216" 
                class="px-4 py-2 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs transition-colors shrink-0 flex items-center gap-1.5"
              >
                <Phone class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'ቅሬታ አቅርብ' : 'Call Desk' }}</span>
              </a>
            </div>

          </div>

          <!-- Empty Fallback -->
          <div v-else class="text-center py-24 text-slate-400">
            <Layers class="w-16 h-16 mx-auto mb-3 opacity-30 text-blue-700" />
            <h4 class="text-base font-bold text-slate-700">Select a Service from the Directory</h4>
            <p class="text-xs text-slate-400 mt-1">Choose any municipal service on the left to inspect its procedural steps and fees.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
