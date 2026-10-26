<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  HardHat, 
  TrendingUp, 
  Calendar, 
  CheckCircle2, 
  Clock, 
  Banknote, 
  Building2, 
  Landmark, 
  Filter, 
  ChevronRight, 
  Sparkles,
  Layers
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const projects = ref([])
const activeStatus = ref('all')
const loading = ref(true)

async function fetchProjects() {
  loading.value = true
  try {
    const res = await api.get('/projects', { params: { status: activeStatus.value } })
    projects.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load projects:', err)
  } finally {
    loading.value = false
  }
}

function getTitle(project) {
  const lang = langStore.currentLang
  if (lang === 'am') return project.title_am || project.title_en
  if (lang === 'om') return project.title_om || project.title_en
  return project.title_en || project.title_am
}

function getDescription(project) {
  const lang = langStore.currentLang
  if (lang === 'am') return project.description_am || project.description_en || ''
  if (lang === 'om') return project.description_om || project.description_en || ''
  return project.description_en || project.description_am || ''
}

const totalBudget = computed(() => {
  return projects.value.reduce((sum, p) => sum + (Number(p.budget_etb) || 0), 0)
})

const completedCount = computed(() => {
  return projects.value.filter(p => p.progress_percent === 100 || p.status === 'completed').length
})

const avgProgress = computed(() => {
  if (projects.value.length === 0) return 0
  const sum = projects.value.reduce((s, p) => s + (Number(p.progress_percent) || 0), 0)
  return Math.round(sum / projects.value.length)
})

const filteredProjects = computed(() => {
  if (activeStatus.value === 'all') return projects.value
  if (activeStatus.value === 'completed') {
    return projects.value.filter(p => p.progress_percent === 100 || p.status === 'completed')
  }
  if (activeStatus.value === 'in_progress') {
    return projects.value.filter(p => (p.progress_percent || 0) < 100 && p.status !== 'completed')
  }
  return projects.value
})

onMounted(async () => {
  await fetchProjects()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-slate-950 text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden min-h-[380px] flex items-center">
      <img src="/assets/industry_park.jpg" alt="Capital Projects" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-slate-950/75 via-slate-950/40 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent to-black/25"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am' ? 'የከተማው ታላላቅ የመሰረተ ልማት ፕሮጀክቶች' : 'Capital Infrastructure & Megaprojects Tracker' }}
        </h1>
        <p class="text-slate-100 text-sm sm:text-base max-w-3xl leading-relaxed drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)] font-medium">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ አስተዳደር የሚተገበሩ የኮብልስቶንና አስፋልት መንገዶች፣ የድልድይና የፍሳሽ ማስወገጃ፣ የኢንዱስትሪ ፓርክ ማስፋፊያና የአረንጓዴ ፓርኮች ግንባታ የክንውን ሂደት።'
            : 'Live monitoring of municipal capital projects, road paving, drainage infrastructure, industrial expansion, and urban renewal contracts.'
          }}
        </p>

        <!-- Megaproject Analytics Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20">
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Monitored Projects</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white drop-shadow">{{ projects.length }} Works</div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-blue-400 text-xs block font-bold mb-1">Total Capital Investment</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300 drop-shadow">
              ETB {{ (totalBudget / 1000000).toFixed(1) }}M
            </div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Completed Milestones</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-300 drop-shadow">{{ completedCount }} Delivery</div>
          </div>
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl">
            <span class="text-slate-300 text-xs block font-bold mb-1">Average Implementation</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-amber-400 drop-shadow">{{ avgProgress }}%</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
          <button 
            @click="activeStatus = 'all'" 
            :class="activeStatus === 'all' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-4 py-2 rounded-lg transition-all cursor-pointer"
          >
            All Projects ({{ projects.length }})
          </button>
          <button 
            @click="activeStatus = 'in_progress'" 
            :class="activeStatus === 'in_progress' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-4 py-2 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
          >
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            In Progress
          </button>
          <button 
            @click="activeStatus = 'completed'" 
            :class="activeStatus === 'completed' ? 'bg-blue-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            class="px-4 py-2 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
          >
            <CheckCircle2 class="w-3.5 h-3.5 text-emerald-400" />
            Completed ({{ completedCount }})
          </button>
        </div>

        <span class="text-xs font-mono font-bold text-slate-500">
          Supervised by Kombolcha Urban Infrastructure Directorate
        </span>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-3xl border border-slate-200 p-6 animate-pulse h-64"></div>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="project in filteredProjects" 
          :key="project.id" 
          class="bg-white rounded-3xl border border-slate-200/90 p-7 shadow-xs hover:border-amber-400 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
        >
          <!-- Left Status Stripe -->
          <div 
            class="absolute left-0 top-0 bottom-0 w-1.5"
            :class="project.progress_percent === 100 ? 'bg-emerald-500' : 'bg-blue-600'"
          ></div>

          <div class="space-y-4 pl-2">
            <!-- Header Badges -->
            <div class="flex items-center justify-between gap-2">
              <span 
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border shadow-2xs"
                :class="project.progress_percent === 100 
                  ? 'bg-emerald-50 text-emerald-800 border-emerald-300' 
                  : 'bg-blue-50 text-blue-800 border-blue-200'"
              >
                <CheckCircle2 v-if="project.progress_percent === 100" class="w-3.5 h-3.5 text-emerald-600" />
                <Clock v-else class="w-3.5 h-3.5 text-blue-600" />
                {{ project.progress_percent === 100 ? 'Completed' : 'Active Execution' }}
              </span>

              <span v-if="project.target_completion_date" class="text-xs font-mono font-bold text-slate-500 flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5 text-slate-400" />
                <span>Target: {{ project.target_completion_date }}</span>
              </span>
            </div>

            <!-- Project Title -->
            <h2 class="text-lg sm:text-xl font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug">
              {{ getTitle(project) }}
            </h2>

            <!-- Project Description -->
            <p v-if="getDescription(project)" class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
              {{ getDescription(project) }}
            </p>

            <!-- Visual Progress Bar -->
            <div class="space-y-2 pt-2">
              <div class="flex items-center justify-between text-xs font-bold">
                <span class="text-slate-500 uppercase tracking-wider text-[10px]">Execution Progress</span>
                <span class="font-mono font-black text-slate-900 text-sm">{{ project.progress_percent || 0 }}%</span>
              </div>
              <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200">
                <div 
                  class="h-full rounded-full transition-all duration-700 shadow-xs" 
                  :class="project.progress_percent === 100 
                    ? 'bg-emerald-500' 
                    : 'bg-gradient-to-r from-blue-700 via-indigo-600 to-amber-500'" 
                  :style="{ width: `${project.progress_percent || 0}%` }"
                ></div>
              </div>
            </div>
          </div>

          <!-- Contractor & Budget Specs Footer -->
          <div class="pt-5 mt-6 border-t border-slate-100 pl-2 text-xs text-slate-600 space-y-2">
            <div v-if="project.contractor_name" class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Contractor:</span>
              <span class="font-black text-slate-900 truncate max-w-[200px]">{{ project.contractor_name }}</span>
            </div>
            <div v-if="project.budget_etb" class="flex items-center justify-between">
              <span class="text-slate-400 font-bold">Allocated Budget:</span>
              <span class="font-mono font-black text-emerald-700">ETB {{ Number(project.budget_etb).toLocaleString() }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <HardHat class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No projects currently under this filter</h3>
        <p class="text-xs text-slate-500 mt-1">Select "All Projects" to view the full municipal capital works tracker.</p>
        <button 
          @click="activeStatus = 'all'" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          View All Projects
        </button>
      </div>
    </div>
  </div>
</template>
