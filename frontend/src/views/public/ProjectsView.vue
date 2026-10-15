<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { HardHat, TrendingUp, Calendar, CheckCircle2, Clock } from 'lucide-vue-next'

const langStore = useLanguageStore()
const projects = ref([])
const activeStatus = ref('all')
const loading = ref(true)

async function fetchProjects() {
  loading.value = true
  try {
    const res = await api.get('/projects', { params: { status: activeStatus.value } })
    projects.value = res.data
  } catch (err) {
    console.error('Failed to load projects:', err)
  } finally {
    loading.value = false
  }
}

// Sample fallback projects for initial state if DB is empty
const defaultProjects = [
  {
    id: 1,
    title_am: 'የቦርከና ወንዝ የተፋሰስ ልማት እና የጎርፍ መከላከያ ቦይ ግንባታ',
    title_en: 'Borkena River Flood Mitigation & Drainage Basin Construction',
    title_om: 'Pirojektii Misooma Sulula Laga Borkanaa fi Booyii Ittisa Lolaa',
    progress_percent: 75,
    budget_etb: 45000000,
    contractor_name: 'Kombolcha Urban Civil Works Enterprise',
    status: 'in_progress',
    target_completion_date: 'Dec 2026'
  },
  {
    id: 2,
    title_am: 'የኢንዱስትሪ ፓርክ ወደ ደረቅ ወደብ የሚያገናኝ የጭነት መንገድ አስፋልት ስራ',
    title_en: 'Industrial Park to Dry Port Heavy Freight Corridor Asphalt Paving',
    title_om: 'Ijaarsa Daandii Asfaaltii Wiirtuu Indaastirii fi Buufata Gogaa Walqunnamsiisu',
    progress_percent: 92,
    budget_etb: 82000000,
    contractor_name: 'Amhara Roads Authority',
    status: 'in_progress',
    target_completion_date: 'Nov 2026'
  },
  {
    id: 3,
    title_am: 'የኮምቦልቻ ዘመናዊ የህዝብ መዝናኛ ፓርክ እና አረንጓዴ ስፍራ ልማት',
    title_en: 'Kombolcha Modern Civic Green Park & Recreation Facility',
    title_om: 'Misooma Paarkii Magaalaa fi Iddoo Bashannanaa Magaalaa Kombolchaa',
    progress_percent: 100,
    budget_etb: 18500000,
    contractor_name: 'Green Legacy Engineering',
    status: 'completed',
    target_completion_date: 'Aug 2026'
  }
]

onMounted(async () => {
  await fetchProjects()
  if (projects.value.length === 0) {
    projects.value = defaultProjects
  }
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
        Urban Transparency Tracker
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
        {{ langStore.t('nav.projects') }}
      </h1>
      <p class="text-slate-600 text-base max-w-2xl">
        Live public tracking of municipal infrastructure investments, road paving, drainage mitigation, and urban development initiatives.
      </p>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="project in projects" 
        :key="project.id" 
        class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between"
      >
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold"
              :class="project.progress_percent === 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200'"
            >
              <CheckCircle2 v-if="project.progress_percent === 100" class="w-3.5 h-3.5" />
              <Clock v-else class="w-3.5 h-3.5" />
              {{ project.progress_percent === 100 ? 'Completed' : 'In Progress' }}
            </span>
            <span class="text-xs font-mono font-bold text-slate-400">{{ project.target_completion_date }}</span>
          </div>

          <h3 class="text-base font-bold text-slate-900 leading-snug">
            {{ langStore.currentLang === 'am' ? project.title_am : (langStore.currentLang === 'om' ? project.title_om : project.title_en) }}
          </h3>

          <!-- Visual Progress Bar -->
          <div class="space-y-1.5 pt-2">
            <div class="flex items-center justify-between text-xs">
              <span class="text-slate-500 font-medium">Implementation Progress</span>
              <span class="font-bold font-mono text-slate-800">{{ project.progress_percent }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
              <div 
                class="h-full rounded-full transition-all duration-500" 
                :class="project.progress_percent === 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-blue-600 to-indigo-500'" 
                :style="{ width: project.progress_percent + '%' }"
              ></div>
            </div>
          </div>
        </div>

        <div class="pt-4 mt-6 border-t border-slate-100 text-xs text-slate-500 space-y-1">
          <p><strong>Contractor:</strong> {{ project.contractor_name }}</p>
          <p><strong>Budget:</strong> ETB {{ Number(project.budget_etb).toLocaleString() }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
