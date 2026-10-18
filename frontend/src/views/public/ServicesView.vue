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
  Landmark, 
  Building2, 
  MapPin, 
  PhoneCall, 
  HelpCircle,
  FileCheck,
  Check,
  Info,
  ExternalLink,
  Sparkles
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const services = ref([])
const selectedService = ref(null)
const loading = ref(true)
const detailLoading = ref(false)
const searchQuery = ref('')
const activeTypeFilter = ref('all') // 'all' | 'step' | 'text'
const completedSteps = ref(new Set())

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

const filteredServices = computed(() => {
  return services.value.filter(s => {
    // Search filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const title = (getServiceTitle(s) || '').toLowerCase()
      const summary = (getServiceSummary(s) || '').toLowerCase()
      if (!title.includes(q) && !summary.includes(q)) return false
    }

    // Type filter
    if (activeTypeFilter.value !== 'all' && s.type !== activeTypeFilter.value) {
      return false
    }

    return true
  })
})

onMounted(() => {
  fetchServices()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl">
      <div class="w-full max-w-[1700px] mx-auto">
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-400/20 text-emerald-300 border border-emerald-400/40 text-xs font-black uppercase tracking-widest">
            <ShieldCheck class="w-3.5 h-3.5 text-emerald-400" />
            Citizen Service Charter & E-Governance
          </span>
          <span class="text-xs text-slate-400 font-mono">Service Delivery Excellence Guarantee</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የዜጎች አገልግሎቶች መመሪያና ዲጂታል መድረክ' : 'Citizen E-Services & Procedural Guide' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ አስተዳደርና በ 4ቱ ክፍለ ከተሞች የሚሰጡ የነዋሪነት መታወቂያ፣ የንግድ ፈቃድ፣ የግንባታ ፈቃድና የፍትህ አገልግሎቶች ዝርዝር የደረጃ በደረጃ መመሪያ፣ የጊዜ ሰሌዳና ህጋዊ ክፍያዎች።'
            : 'Interactive step-by-step citizen guides, statutory fee schedules, document verification checklists, and guaranteed processing turnaround timelines.'
          }}
        </p>

        <!-- Citizen Guarantee Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Cataloged Public Services</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ services.length }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Standard Processing Window</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">24h - 48h</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Delivery Quality Standard</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">100% Zero-Bribe</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Sub-City Service Centers</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-slate-300">4 Sub-Cities</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Search & Filter Bar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <div class="relative flex-1 min-w-[280px]">
          <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="langStore.currentLang === 'am' ? 'የአገልግሎቱን ስም ወይም ቁልፍ ቃል ፈልግ (ለምሳሌ: መታወቂያ፣ ንግድ፣ ግንባታ)...' : 'Search services (e.g., Kebele ID, Trade License, Building Permit)...'" 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>

        <div class="flex items-center gap-2">
          <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
            <button 
              @click="activeTypeFilter = 'all'" 
              :class="activeTypeFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              All Types
            </button>
            <button 
              @click="activeTypeFilter = 'step'" 
              :class="activeTypeFilter === 'step' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              Step-by-Step Guides
            </button>
            <button 
              @click="activeTypeFilter = 'text'" 
              :class="activeTypeFilter === 'text' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              General Guidance
            </button>
          </div>
        </div>
      </div>

      <!-- Two-Column Interactive Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Service Selection Directory (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 p-4 shadow-sm space-y-2">
          <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-500">
              Service Directory ({{ filteredServices.length }})
            </h3>
            <span class="text-[10px] text-blue-700 font-mono font-bold">Select to view</span>
          </div>

          <!-- Loading Skeleton -->
          <div v-if="loading" class="space-y-2 p-2">
            <div v-for="n in 5" :key="n" class="h-14 bg-slate-100 rounded-2xl animate-pulse"></div>
          </div>

          <!-- Services List -->
          <div v-else-if="filteredServices.length > 0" class="space-y-1.5 max-h-[750px] overflow-y-auto pr-1">
            <button
              v-for="service in filteredServices"
              :key="service.id"
              @click="loadServiceDetail(service.slug)"
              :class="[
                'w-full text-left p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-2 border cursor-pointer',
                selectedService && selectedService.service.id === service.id
                  ? 'bg-blue-900 text-white border-blue-900 shadow-md shadow-blue-950/20 scale-[1.01]'
                  : 'bg-white hover:bg-slate-50 text-slate-800 border-slate-200/80 hover:border-slate-300'
              ]"
            >
              <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-2.5">
                  <div 
                    class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                    :class="selectedService && selectedService.service.id === service.id ? 'bg-amber-400 text-slate-950' : 'bg-blue-50 text-blue-700'"
                  >
                    <FileText class="w-4 h-4" />
                  </div>
                  <div>
                    <h4 class="text-sm font-black leading-snug line-clamp-1">
                      {{ getServiceTitle(service) }}
                    </h4>
                    <span 
                      class="text-[10px] uppercase font-bold"
                      :class="selectedService && selectedService.service.id === service.id ? 'text-blue-200' : 'text-slate-400'"
                    >
                      {{ service.type === 'step' ? 'Step-by-Step Procedure' : 'General Guidance' }}
                    </span>
                  </div>
                </div>
                <ChevronRight 
                  class="w-4 h-4 shrink-0 transition-transform" 
                  :class="selectedService && selectedService.service.id === service.id ? 'text-amber-400 translate-x-1' : 'text-slate-400'" 
                />
              </div>

              <!-- Metadata Mini Strip -->
              <div 
                class="flex items-center justify-between text-[11px] pt-1 border-t font-semibold"
                :class="selectedService && selectedService.service.id === service.id ? 'border-white/10 text-slate-200' : 'border-slate-100 text-slate-500'"
              >
                <span class="flex items-center gap-1 font-mono">
                  <Clock class="w-3 h-3" />
                  {{ getServiceTime(service) }}
                </span>
                <span class="font-mono font-bold">
                  ETB {{ Number(service.fee_amount || 0).toLocaleString() }}
                </span>
              </div>
            </button>
          </div>

          <!-- Empty Search State -->
          <div v-else class="text-center py-10 px-4 text-slate-400">
            <Layers class="w-8 h-8 mx-auto mb-2 opacity-50" />
            <p class="text-xs">No services matching your search query.</p>
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
              <div class="flex flex-wrap items-center gap-2.5 mb-3">
                <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-wider">
                  {{ selectedService.service.type === 'step' ? 'Official Civic Walkthrough' : 'Informational Guide' }}
                </span>
                <span class="text-xs font-bold text-slate-300 font-mono">
                  Municipal Directorate Service
                </span>
              </div>

              <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight mb-3">
                {{ getServiceTitle(selectedService.service) }}
              </h2>

              <p class="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-2xl font-medium mb-6">
                {{ getServiceSummary(selectedService.service) }}
              </p>

              <!-- Turnaround & Statutory Fees Pill Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-800">
                <div class="flex items-center gap-3 bg-slate-800/80 p-3 rounded-2xl border border-slate-700/80">
                  <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-400 flex items-center justify-center shrink-0">
                    <Clock class="w-5 h-5" />
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Turnaround Standard</span>
                    <strong class="text-xs sm:text-sm text-white font-mono">{{ getServiceTime(selectedService.service) }}</strong>
                  </div>
                </div>

                <div class="flex items-center gap-3 bg-slate-800/80 p-3 rounded-2xl border border-slate-700/80">
                  <div class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-400 flex items-center justify-center shrink-0">
                    <Banknote class="w-5 h-5" />
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Statutory Municipal Fee</span>
                    <strong class="text-xs sm:text-sm text-emerald-400 font-mono font-black">
                      ETB {{ Number(selectedService.service.fee_amount || 0).toLocaleString() }}
                    </strong>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sequential Step-by-Step Procedure -->
            <div v-if="selectedService.steps && selectedService.steps.length > 0" class="space-y-6">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                  <Layers class="w-4 h-4 text-blue-700" />
                  <span>Required Procedural Steps ({{ selectedService.steps.length }})</span>
                </h3>
                <span class="text-xs font-mono font-bold text-slate-500">
                  {{ completedSteps.size }} of {{ selectedService.steps.length }} checked
                </span>
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
                    :class="completedSteps.has(step.id || idx) ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-blue-900 text-amber-400 hover:scale-110'"
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
                        class="text-xs font-bold px-2.5 py-1 rounded-lg border transition-colors cursor-pointer shrink-0"
                        :class="completedSteps.has(step.id || idx) ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100'"
                      >
                        {{ completedSteps.has(step.id || idx) ? 'Completed ✓' : 'Mark as Done' }}
                      </button>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
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
            <div class="mt-10 p-6 rounded-3xl bg-blue-50/60 border border-blue-200/80 space-y-2">
              <div class="flex items-center gap-2 text-xs font-black uppercase text-blue-900">
                <ShieldCheck class="w-4 h-4 text-blue-700" />
                <span>Municipal Anti-Corruption & Ethics Hotline</span>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                If you encounter unwarranted delays, requests for unofficial payments, or misconduct during service delivery at any Sub-City or City Hall office, report directly to the Municipal Ethics & Anti-Corruption Directorate at hotline <strong>991</strong> or email <strong>ethics@kombolchacity.gov.et</strong>.
              </p>
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
