<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Briefcase, 
  Clock, 
  Calendar, 
  CheckCircle2, 
  Building2, 
  FileText,
  BadgePercent,
  AlertCircle,
  XCircle,
  Filter,
  Search,
  ShieldCheck,
  Landmark,
  Award,
  Users,
  Check,
  Info,
  MapPin,
  ChevronRight,
  ExternalLink
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const vacancies = ref([])
const statusFilter = ref('all') // 'all' | 'open' | 'closed'
const searchQuery = ref('')
const selectedType = ref('all')

const fetchVacancies = async () => {
  loading.value = true
  try {
    const res = await api.getVacancies()
    vacancies.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load vacancies:', err)
  } finally {
    loading.value = false
  }
}

const getTitle = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.title_am || v.title_en
  if (lang === 'om') return v.title_om || v.title_en
  return v.title_en || v.title_am
}

const getRequirements = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.requirements_am || v.requirements_en
  if (lang === 'om') return v.requirements_om || v.requirements_en
  return v.requirements_en || v.requirements_am
}

const getApplicationGuide = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.application_guide_am || v.application_guide_en
  if (lang === 'om') return v.application_guide_om || v.application_guide_en
  return v.application_guide_en || v.application_guide_am
}

// Real-time calculation: Check if vacancy has closed
const isJobClosed = (job) => {
  if (job.status === 'closed') return true
  if (!job.deadline) return false
  const deadlineDate = new Date(job.deadline.includes('T') || job.deadline.includes(' ') ? job.deadline : job.deadline + 'T23:59:59')
  return deadlineDate.getTime() <= Date.now()
}

// Real-time calculation: Accurate remaining days or closed status
const getDeadlineBadgeText = (job) => {
  if (job.status === 'closed') {
    if (langStore.currentLang === 'am') return 'የተዘጋ'
    if (langStore.currentLang === 'om') return 'Cufameera'
    return 'Closed'
  }
  if (!job.deadline) {
    if (langStore.currentLang === 'am') return 'ክፍት ነው'
    if (langStore.currentLang === 'om') return 'Bantamaa'
    return 'Open'
  }

  const deadlineDate = new Date(job.deadline.includes('T') || job.deadline.includes(' ') ? job.deadline : job.deadline + 'T23:59:59')
  const diffMs = deadlineDate.getTime() - Date.now()

  if (diffMs <= 0) {
    if (langStore.currentLang === 'am') return 'የማመልከቻ ጊዜው አልፏል (የተዘጋ)'
    if (langStore.currentLang === 'om') return 'Yeroon Darbeera (Cufameera)'
    return 'Application Closed'
  }

  const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24))
  if (diffDays <= 1) {
    if (langStore.currentLang === 'am') return 'ዛሬ ያበቃል (የመጨረሻ ቀን)'
    if (langStore.currentLang === 'om') return 'Har\'a Xumurama'
    return 'Closes Today (Last Day)'
  }

  if (langStore.currentLang === 'am') return `${diffDays} ቀናት ቀርተዋል`
  if (langStore.currentLang === 'om') return `Guyyoota ${diffDays} hafe`
  return `${diffDays} days left`
}

const openCount = computed(() => vacancies.value.filter(j => !isJobClosed(j)).length)
const closedCount = computed(() => vacancies.value.filter(j => isJobClosed(j)).length)

const employmentTypes = computed(() => {
  const set = new Set(vacancies.value.map(v => v.employment_type).filter(Boolean))
  return ['all', ...Array.from(set)]
})

const filteredVacanciesList = computed(() => {
  return vacancies.value.filter(job => {
    // Status filter
    const closed = isJobClosed(job)
    if (statusFilter.value === 'open' && closed) return false
    if (statusFilter.value === 'closed' && !closed) return false

    // Employment type filter
    if (selectedType.value !== 'all' && job.employment_type !== selectedType.value) {
      return false
    }

    // Search query filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const title = (getTitle(job) || '').toLowerCase()
      const req = (getRequirements(job) || '').toLowerCase()
      const code = (job.position_code || '').toLowerCase()
      if (!title.includes(q) && !req.includes(q) && !code.includes(q)) return false
    }

    return true
  })
})

onMounted(() => {
  fetchVacancies()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/industry.jpg" alt="Public Careers" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የመንግስት የስራ ቅጥርና ሲቪል ሰርቪስ ክፍት መደቦች' : 'Civil Service Careers & Employment Opportunities' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ አስተዳደር፣ በ 4ቱ ክፍለ ከተሞችና በ 22ቱ መምሪያ ቢሮዎች ስር የሚወጡ ይፋዊ የሲቪል ሰርቪስ ክፍት የስራ መደቦች፣ የትምህርት መስፈርቶችና የማመልከቻ መመሪያዎች።'
            : 'Explore public career opportunities across Kombolcha City Administration, Sub-Cities, and municipal sector directorates. Transparent civil service examination and merit-based hiring.'
          }}
        </p>

        <!-- Recruitment Analytics Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Total Published Vacancies</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ vacancies.length }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Currently Open Roles</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-400">{{ openCount }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Hiring Standard</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">Merit & Equal Opportunity</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Duty Station</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Kombolcha Metropolitan</div>
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
            :placeholder="langStore.currentLang === 'am' ? 'በስራ መደብ መጠሪያ ወይም በኮድ ፈልግ...' : 'Search by Position Title or Code...'" 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Status Filters -->
          <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
            <button 
              @click="statusFilter = 'all'" 
              :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              All ({{ vacancies.length }})
            </button>
            <button 
              @click="statusFilter = 'open'" 
              :class="statusFilter === 'open' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
              Open ({{ openCount }})
            </button>
            <button 
              @click="statusFilter = 'closed'" 
              :class="statusFilter === 'closed' ? 'bg-slate-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3.5 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              Closed ({{ closedCount }})
            </button>
          </div>

          <!-- Employment Type Select -->
          <select 
            v-model="selectedType" 
            class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-600 cursor-pointer"
          >
            <option value="all">All Employment Types</option>
            <option v-for="t in employmentTypes.filter(t => t !== 'all')" :key="t" :value="t">
              {{ t }}
            </option>
          </select>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="space-y-6">
        <div v-for="i in 3" :key="i" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs animate-pulse h-48"></div>
      </div>

      <!-- Vacancies List -->
      <div v-else-if="filteredVacanciesList.length > 0" class="space-y-6">
        <div 
          v-for="job in filteredVacanciesList" 
          :key="job.id"
          class="bg-white rounded-3xl border p-6 sm:p-8 shadow-xs hover:shadow-xl transition-all duration-300 relative overflow-hidden"
          :class="isJobClosed(job) ? 'border-slate-200 bg-slate-50/40' : 'border-slate-200/90 hover:border-blue-500'"
        >
          <!-- Left Decorative Status Stripe -->
          <div 
            class="absolute left-0 top-0 bottom-0 w-1.5"
            :class="!isJobClosed(job) ? 'bg-emerald-500' : 'bg-slate-300'"
          ></div>

          <!-- Header Bar of Position Card -->
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100 pl-2">
            <div>
              <div class="flex flex-wrap items-center gap-2 mb-2.5">
                <!-- Position Code -->
                <span class="px-3 py-1 rounded-lg bg-slate-900 text-amber-400 font-mono text-xs font-black shadow-xs">
                  CODE: {{ job.position_code || `KCA/HR-${job.id}` }}
                </span>

                <!-- Employment Type -->
                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                  {{ job.employment_type || 'Full-Time Civil Service' }}
                </span>

                <!-- Salary Grade Badge -->
                <span v-if="job.salary_grade" class="px-3 py-1 rounded-lg text-xs font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                  {{ job.salary_grade }}
                </span>

                <!-- Status Pill -->
                <span 
                  v-if="isJobClosed(job)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-800 border border-rose-200 text-xs font-black uppercase flex items-center gap-1"
                >
                  <AlertCircle class="w-3.5 h-3.5 text-rose-600" />
                  <span>Closed</span>
                </span>
                <span 
                  v-else 
                  class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-black uppercase flex items-center gap-1.5"
                >
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span>Active Recruitment</span>
                </span>
              </div>

              <!-- Position Title -->
              <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
                {{ getTitle(job) }}
              </h2>
            </div>

            <!-- Deadline Countdown Badge -->
            <div 
              class="flex items-center gap-3 px-4 py-2.5 rounded-2xl shrink-0 border"
              :class="isJobClosed(job) ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-amber-50 border-amber-200 text-amber-900'"
            >
              <Clock class="w-5 h-5 shrink-0" :class="isJobClosed(job) ? 'text-rose-600' : 'text-amber-600'" />
              <div class="text-xs font-bold">
                <span class="block text-[10px] text-slate-500 uppercase font-black tracking-wider">Application Deadline</span>
                <span class="font-mono font-black" :class="isJobClosed(job) ? 'text-rose-700' : 'text-amber-800'">
                  {{ job.deadline }} ({{ getDeadlineBadgeText(job) }})
                </span>
              </div>
            </div>
          </div>

          <!-- Position Body: Requirements & Application Guidelines -->
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pl-2">
            
            <!-- Requirements Column (8 cols) -->
            <div class="lg:col-span-8 space-y-5">
              <div>
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <Award class="w-4 h-4 text-blue-700" />
                  <span>{{ langStore.currentLang === 'am' ? 'የትምህርት ደረጃና የስራ ልምድ መስፈርቶች' : 'Educational Qualifications & Experience Criteria' }}</span>
                </h3>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line font-medium">
                  {{ getRequirements(job) }}
                </div>
              </div>

              <!-- Application Guide -->
              <div v-if="getApplicationGuide(job)">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <Info class="w-4 h-4 text-amber-600" />
                  <span>{{ langStore.currentLang === 'am' ? 'የማመልከቻ መመሪያና አድራሻ' : 'How & Where to Apply' }}</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed p-4 rounded-2xl bg-amber-50/50 border border-amber-200/60">
                  {{ getApplicationGuide(job) }}
                </p>
              </div>
            </div>

            <!-- Meta Sidebar Column (4 cols) -->
            <div class="lg:col-span-4 bg-slate-50/90 rounded-2xl p-6 border border-slate-200/80 flex flex-col justify-between space-y-4">
              <div class="space-y-3.5 text-xs">
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200">
                  <span class="text-slate-500 font-bold">Duty Location:</span>
                  <span class="font-black text-slate-800 flex items-center gap-1">
                    <MapPin class="w-3.5 h-3.5 text-blue-600" />
                    Kombolcha City
                  </span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200">
                  <span class="text-slate-500 font-bold">Salary Grade:</span>
                  <span class="font-bold font-mono text-slate-800">{{ job.salary_grade || 'Government Scale' }}</span>
                </div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-200">
                  <span class="text-slate-500 font-bold">Category:</span>
                  <span class="font-bold text-slate-800">{{ job.employment_type || 'Civil Service' }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 font-bold">Recruitment Mode:</span>
                  <span class="font-bold text-blue-800">Direct Civil Service Exam</span>
                </div>
              </div>

              <!-- Submission In-Person Notice -->
              <div class="pt-4 border-t border-slate-200 text-[11px] text-slate-500 space-y-1">
                <p class="font-bold text-slate-700">Required Submission Dossier:</p>
                <p>• Original & non-returnable copy of educational credentials</p>
                <p>• Valid Kebele ID / Resident card</p>
                <p>• 2 Recent passport-size photographs</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Briefcase class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No career opportunities found</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
          There are no job postings currently matching your filter selection. Try selecting "All Positions" or clearing your search.
        </p>
        <button 
          @click="statusFilter = 'all'; selectedType = 'all'; searchQuery = ''" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          Reset All Filters
        </button>
      </div>

      <!-- Equal Opportunity Public Notice -->
      <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
        <div class="flex items-center gap-2.5 text-xs font-black text-slate-800 uppercase tracking-wider mb-2">
          <ShieldCheck class="w-4 h-4 text-emerald-600" />
          <span>Equal Opportunity Employer (EOE) & Civil Service Ethics</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
          The Kombolcha City Administration is an Equal Opportunity Employer. Female candidates, persons with disabilities, and qualified youth are highly encouraged to apply. All recruitment decisions are strictly governed by the Amhara Regional State Civil Service Proclamation based on competitive merit.
        </p>
      </div>
    </div>
  </div>
</template>
