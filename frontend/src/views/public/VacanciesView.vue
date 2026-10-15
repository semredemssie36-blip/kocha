<script setup>
import { ref, onMounted } from 'vue'
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
  AlertCircle
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const vacancies = ref([])

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

const getDaysRemaining = (deadlineStr) => {
  if (!deadlineStr) return 0
  const deadline = new Date(deadlineStr)
  const now = new Date()
  const diffTime = deadline - now
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return diffDays > 0 ? diffDays : 0
}

onMounted(() => {
  fetchVacancies()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
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

    <!-- Vacancies List -->
    <div v-if="loading" class="space-y-6">
      <div v-for="i in 3" :key="i" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm animate-pulse">
        <div class="h-6 w-1/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-8 w-3/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-20 bg-slate-100 rounded"></div>
      </div>
    </div>

    <div v-else-if="vacancies.length === 0" class="text-center py-16 bg-white rounded-3xl border border-slate-200">
      <Briefcase class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="text-lg font-bold text-slate-800">
        {{ langStore.currentLang === 'am' ? 'በአሁኑ ሰዓት ክፍት የስራ ማስታወቂያ የለም' : 'No Open Positions At This Time' }}
      </h3>
      <p class="text-slate-500 text-sm mt-1">
        {{ langStore.currentLang === 'am' ? 'አዳዲስ ማስታወቂያዎች ሲኖሩ በዚህ ገጽ ላይ ይለጠፋሉ። እባክዎ በየጊዜው ይከታተሉ።' : 'Check back regularly for civil service postings and apprentice programs.' }}
      </p>
    </div>

    <div v-else class="space-y-6">
      <div 
        v-for="job in vacancies" 
        :key="job.id"
        class="bg-white rounded-3xl border border-slate-200 hover:border-blue-300 p-8 shadow-sm hover:shadow-xl transition-all duration-300"
      >
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-3 mb-2">
              <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-100">
                {{ job.employment_type || 'Full-Time' }}
              </span>
              <span v-if="job.position_code" class="text-xs font-mono font-bold text-slate-500">
                Code: {{ job.position_code }}
              </span>
            </div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">
              {{ getTitle(job) }}
            </h2>
          </div>

          <!-- Deadline Tag -->
          <div class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shrink-0">
            <Clock class="w-4 h-4 text-amber-600" />
            <div class="text-xs font-bold">
              <span>{{ langStore.currentLang === 'am' ? 'የማመልከቻ ቀነ-ገደብ' : 'Application Closes' }}:</span>
              <span class="block text-amber-700 font-mono">{{ job.deadline }} ({{ getDaysRemaining(job.deadline) }} days left)</span>
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
                <span class="text-slate-500">{{ langStore.currentLang === 'am' ? 'ሁኔታ' : 'Status' }}:</span>
                <span class="inline-flex items-center gap-1 font-bold text-emerald-600">
                  <CheckCircle2 class="w-3.5 h-3.5" />
                  {{ langStore.currentLang === 'am' ? 'ክፍት ነው' : 'Open' }}
                </span>
              </div>
            </div>

            <router-link 
              to="/contact" 
              class="mt-6 w-full py-3 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold text-center transition-all duration-200 shadow-sm"
            >
              {{ langStore.currentLang === 'am' ? 'ስለ ስራው ጥያቄ ካለዎት ይጠይቁ' : 'Inquire Regarding Position' }}
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
