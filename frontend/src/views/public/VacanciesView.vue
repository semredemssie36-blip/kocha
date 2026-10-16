<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Briefcase, 
  Clock, 
  Calendar, 
  CheckCircle2, 
  Building, 
  FileText,
  BadgePercent,
  AlertCircle,
  XCircle,
  Filter
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const vacancies = ref([])
const statusFilter = ref('all') // 'all' | 'open' | 'closed'

const fetchVacancies = async () => {
  loading.value = true
  try {
    const res = await api.getVacancies()
    vacancies.value = res.data || []
  } catch (err) {
    console.error('Failed to load vacancies:', err)
  } finally {
    loading.value = false
  }
}

const getTitle = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.title_am
  if (lang === 'om') return v.title_om || v.title_en
  return v.title_en
}

const getRequirements = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.requirements_am
  if (lang === 'om') return v.requirements_om || v.requirements_en
  return v.requirements_en
}

const getApplicationGuide = (v) => {
  const lang = langStore.currentLang
  if (lang === 'am') return v.application_guide_am
  if (lang === 'om') return v.application_guide_om || v.application_guide_en
  return v.application_guide_en
}

// Real-time calculation: Check if vacancy has closed
const isJobClosed = (job) => {
  if (job.status === 'closed') return true
  if (!job.deadline) return false
  // A deadline date like '2026-10-14' closes at 23:59:59 on that day
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

const filteredVacanciesList = computed(() => {
  if (statusFilter.value === 'open') {
    return vacancies.value.filter(j => !isJobClosed(j))
  }
  if (statusFilter.value === 'closed') {
    return vacancies.value.filter(j => isJobClosed(j))
  }
  return vacancies.value
})

onMounted(() => {
  fetchVacancies()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-8">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Briefcase class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ሲቪል ሰርቪስና የስራ እድል ፈጠራ' : (langStore.currentLang === 'om' ? 'Tajaajila Siiviilii fi Carraa Hojii Kombolchaa' : 'Municipal Civil Service Careers') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'ክፍት የስራ መደቦችና የቅጥር ማስታወቂያዎች' : (langStore.currentLang === 'om' ? 'Beeksisa Hojii Banaa fi Qacarrii' : 'Public Employment & Career Opportunities') }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'በኮምቦልቻ ከተማ አስተዳደር መምሪያዎች እና ተቋማት ውስጥ ያሉ ክፍት የስራ መደቦች ዝርዝር፣ የትምህርት መስፈርቶች እና የማመልከቻ መመሪያዎች።' : (langStore.currentLang === 'om' ? 'Waajjiraalee bulchiinsa magaalaa keessatti beeksisa qacarrii dhiyaate.' : 'Transparent career opportunities across municipal sector directorates. Merit-based public sector hiring.') }}
        </p>
      </div>
    </div>

    <!-- Filter Tabs (All / Open / Closed) -->
    <div class="flex items-center gap-2 mb-8 overflow-x-auto pb-1 scrollbar-none">
      <button 
        @click="statusFilter = 'all'"
        :class="[
          'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0',
          statusFilter === 'all' 
            ? 'bg-[#0b3c5d] text-white shadow-sm' 
            : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'
        ]"
      >
        <span>{{ langStore.currentLang === 'am' ? 'ሁሉም የቅጥር ማስታወቂያዎች' : (langStore.currentLang === 'om' ? 'Hundumaa' : 'All Positions') }}</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold" :class="statusFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
          {{ vacancies.length }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'open'"
        :class="[
          'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0',
          statusFilter === 'open' 
            ? 'bg-emerald-700 text-white shadow-sm' 
            : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'
        ]"
      >
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        <span>{{ langStore.currentLang === 'am' ? 'አሁን ክፍት የሆኑ' : (langStore.currentLang === 'om' ? 'Bantamaa' : 'Open Positions') }}</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold" :class="statusFilter === 'open' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-700'">
          {{ openCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'closed'"
        :class="[
          'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-2 shrink-0',
          statusFilter === 'closed' 
            ? 'bg-rose-700 text-white shadow-sm' 
            : 'bg-white text-slate-700 hover:bg-slate-50 border border-slate-200'
        ]"
      >
        <span class="w-2 h-2 rounded-full bg-rose-400"></span>
        <span>{{ langStore.currentLang === 'am' ? 'የተዘጉ / ያለፉ ማስታወቂያዎች' : (langStore.currentLang === 'om' ? 'Cufamaa' : 'Closed / Past Postings') }}</span>
        <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold" :class="statusFilter === 'closed' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-700'">
          {{ closedCount }}
        </span>
      </button>
    </div>

    <!-- Vacancies List -->
    <div v-if="loading" class="space-y-6">
      <div v-for="i in 3" :key="i" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm animate-pulse">
        <div class="h-6 w-1/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-8 w-3/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-20 bg-slate-100 rounded"></div>
      </div>
    </div>

    <div v-else-if="filteredVacanciesList.length === 0" class="text-center py-16 bg-white rounded-3xl border border-slate-200">
      <Briefcase class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="text-lg font-bold text-slate-800">
        {{ langStore.currentLang === 'am' ? 'ምንም ማስታወቂያ አልተገኘም' : 'No Positions Found' }}
      </h3>
      <p class="text-slate-500 text-sm mt-1">
        {{ langStore.currentLang === 'am' ? 'በዚህ ምድብ ውስጥ የተመዘገበ ክፍት የስራ ማስታወቂያ የለም።' : 'There are no position records under this selection.' }}
      </p>
    </div>

    <div v-else class="space-y-6">
      <div 
        v-for="job in filteredVacanciesList" 
        :key="job.id"
        class="bg-white rounded-3xl border p-8 shadow-sm hover:shadow-xl transition-all duration-300"
        :class="isJobClosed(job) ? 'border-slate-200 bg-slate-50/40' : 'border-slate-200 hover:border-blue-300'"
      >
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
          <div>
            <div class="flex flex-wrap items-center gap-2.5 mb-2">
              <span 
                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border"
                :class="isJobClosed(job) ? 'bg-slate-100 text-slate-600 border-slate-200' : 'bg-blue-50 text-blue-700 border-blue-100'"
              >
                {{ job.employment_type || 'Full-Time' }}
              </span>
              <span v-if="job.position_code" class="text-xs font-mono font-bold text-slate-500">
                Code: {{ job.position_code }}
              </span>
              <!-- Status Pill on Header -->
              <span 
                v-if="isJobClosed(job)" 
                class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200 text-xs font-extrabold uppercase flex items-center gap-1"
              >
                <AlertCircle class="w-3 h-3" />
                {{ langStore.currentLang === 'am' ? 'የተዘጋ' : (langStore.currentLang === 'om' ? 'Cufamaa' : 'Closed') }}
              </span>
              <span 
                v-else 
                class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-extrabold uppercase flex items-center gap-1"
              >
                <CheckCircle2 class="w-3 h-3" />
                {{ langStore.currentLang === 'am' ? 'ክፍት ነው' : (langStore.currentLang === 'om' ? 'Bantamaa' : 'Open') }}
              </span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black" :class="isJobClosed(job) ? 'text-slate-700' : 'text-slate-900'">
              {{ getTitle(job) }}
            </h2>
          </div>

          <!-- Deadline Tag (Reactive Amber vs Rose) -->
          <div 
            class="flex items-center gap-2.5 px-4 py-2.5 rounded-2xl shrink-0 border"
            :class="isJobClosed(job) ? 'bg-rose-50 border-rose-200 text-rose-900' : 'bg-amber-50 border-amber-200 text-amber-900'"
          >
            <Clock class="w-4 h-4" :class="isJobClosed(job) ? 'text-rose-600' : 'text-amber-600'" />
            <div class="text-xs font-bold">
              <span>{{ isJobClosed(job) ? (langStore.currentLang === 'am' ? 'የማመልከቻ ሁኔታ' : (langStore.currentLang === 'om' ? 'Haala Iyyannoo' : 'Application Status')) : (langStore.currentLang === 'am' ? 'የማመልከቻ ቀነ-ገደብ' : (langStore.currentLang === 'om' ? 'Guyyaa Cufinsaa' : 'Application Closes')) }}:</span>
              <span class="block font-mono" :class="isJobClosed(job) ? 'text-rose-700 font-extrabold' : 'text-amber-700'">
                {{ job.deadline }} ({{ getDeadlineBadgeText(job) }})
              </span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          <!-- Requirements & Details -->
          <div class="lg:col-span-8 space-y-4">
            <div>
              <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                {{ langStore.currentLang === 'am' ? 'የትምህርት ደረጃና የስራ ልምድ መስፈርቶች' : 'Qualifications & Experience Required' }}
              </h3>
              <p class="text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
                {{ getRequirements(job) }}
              </p>
            </div>

            <div v-if="getApplicationGuide(job)">
              <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                {{ langStore.currentLang === 'am' ? 'የማመልከቻ መመሪያና አድራሻ' : 'How To Apply' }}
              </h3>
              <p class="text-xs text-slate-600 leading-relaxed">
                {{ getApplicationGuide(job) }}
              </p>
            </div>
          </div>

          <!-- Meta Sidebar -->
          <div class="lg:col-span-4 bg-slate-50/70 rounded-2xl p-6 border border-slate-100 flex flex-col justify-between">
            <div class="space-y-3 text-xs text-slate-600">
              <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <span class="text-slate-500">{{ langStore.currentLang === 'am' ? 'የደመወዝ ስኬል' : 'Salary Grade' }}:</span>
                <span class="font-bold text-slate-800">{{ job.salary_grade || 'Government Scale' }}</span>
              </div>
              <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <span class="text-slate-500">{{ langStore.currentLang === 'am' ? 'የስራ ሁኔታ' : 'Duty Location' }}:</span>
                <span class="font-bold text-slate-800">Kombolcha City</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-slate-500">{{ langStore.currentLang === 'am' ? 'ሁኔታ' : (langStore.currentLang === 'om' ? 'Haala' : 'Status') }}:</span>
                <span 
                  v-if="!isJobClosed(job)" 
                  class="inline-flex items-center gap-1 font-bold text-emerald-600"
                >
                  <CheckCircle2 class="w-3.5 h-3.5" />
                  {{ langStore.currentLang === 'am' ? 'ክፍት ነው' : (langStore.currentLang === 'om' ? 'Bantamaa' : 'Open') }}
                </span>
                <span 
                  v-else 
                  class="inline-flex items-center gap-1 font-bold text-rose-600"
                >
                  <AlertCircle class="w-3.5 h-3.5" />
                  {{ langStore.currentLang === 'am' ? 'የተዘጋ' : (langStore.currentLang === 'om' ? 'Cufamaa' : 'Closed') }}
                </span>
              </div>
            </div>

            <!-- Inquire Button (Disabled and styled when closed) -->
            <div 
              v-if="isJobClosed(job)" 
              class="mt-6 w-full py-3 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-xs font-bold text-center flex items-center justify-center gap-2 cursor-not-allowed select-none"
            >
              <AlertCircle class="w-4 h-4 text-slate-400" />
              <span>{{ langStore.currentLang === 'am' ? 'የማመልከቻ ጊዜው ተጠናቋል (የተዘጋ)' : (langStore.currentLang === 'om' ? 'Iyyannoon Cufameera' : 'Application Period Closed') }}</span>
            </div>
            <router-link 
              v-else
              to="/contact" 
              class="mt-6 w-full py-3 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold text-center transition-all duration-200 shadow-sm block"
            >
              {{ langStore.currentLang === 'am' ? 'ስለ ስራው ጥያቄ ካለዎት ይጠይቁ' : (langStore.currentLang === 'om' ? 'Waa\'ee Hojii Gaafadhaa' : 'Inquire Regarding Position') }}
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
