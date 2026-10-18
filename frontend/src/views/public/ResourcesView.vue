<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  FileText, 
  Download, 
  Calendar, 
  HardDrive, 
  Search, 
  Filter, 
  CheckCircle,
  FileCheck,
  Building2,
  Landmark,
  Layers,
  Sparkles,
  ExternalLink
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const resources = ref([])
const selectedCategory = ref('all')
const searchQuery = ref('')
const selectedYear = ref('all')

const categories = [
  { key: 'all', label_en: 'All Documents', label_am: 'ሁሉንም ሰነዶች', label_om: 'Sanadoota Hunda' },
  { key: 'Master Plan', label_en: 'Master Plan & Spatial', label_am: 'የከተማ ማስተር ፕላን', label_om: 'Pilaanii Magaalaa' },
  { key: 'Directives', label_en: 'Directives & Regulations', label_am: 'መመሪያዎችና ደንቦች', label_om: 'Qajeelfamoota' },
  { key: 'Annual Reports', label_en: 'Annual Budgets & Fiscal Reports', label_am: 'ዓመታዊ በጀትና ሪፖርቶች', label_om: 'Baajata fi Gabaasa' },
  { key: 'Forms', label_en: 'Official Application Forms', label_am: 'የማመልከቻ ቅጾች', label_om: 'Uunkaalee Iyyannoo' },
]

const years = ['all', '2026', '2025', '2024']

const fetchResources = async () => {
  loading.value = true
  try {
    const params = {}
    if (selectedCategory.value !== 'all') params.category = selectedCategory.value
    if (selectedYear.value !== 'all') params.year = selectedYear.value
    const res = await api.getResources(params)
    resources.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load resources:', err)
  } finally {
    loading.value = false
  }
}

const filteredResources = computed(() => {
  return resources.value.filter(doc => {
    if (!searchQuery.value.trim()) return true
    const q = searchQuery.value.toLowerCase()
    const titleAm = (doc.title_am || '').toLowerCase()
    const titleEn = (doc.title_en || '').toLowerCase()
    const titleOm = (doc.title_om || '').toLowerCase()
    const descAm = (doc.description_am || '').toLowerCase()
    const descEn = (doc.description_en || '').toLowerCase()
    return titleAm.includes(q) || titleEn.includes(q) || titleOm.includes(q) || descAm.includes(q) || descEn.includes(q)
  })
})

const getTitle = (doc) => {
  const lang = langStore.currentLang
  if (lang === 'am') return doc.title_am || doc.title_en
  if (lang === 'om') return doc.title_om || doc.title_en || doc.title_am
  return doc.title_en || doc.title_am
}

const getDescription = (doc) => {
  const lang = langStore.currentLang
  if (lang === 'am') return doc.description_am || doc.description_en || ''
  if (lang === 'om') return doc.description_om || doc.description_en || doc.description_am || ''
  return doc.description_en || doc.description_am || ''
}

const downloadDocument = (doc) => {
  if (doc.file_path) {
    window.open(doc.file_path, '_blank')
    return
  }
  const blob = new Blob([`Kombolcha City Administration Official Document\nTitle: ${getTitle(doc)}\nReference: ${doc.id}\nYear: ${doc.year}`], { type: 'text/plain' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `${doc.title_en ? doc.title_en.toLowerCase().replace(/[^a-z0-9]/g, '_') : 'kombolcha_doc'}.pdf`
  a.click()
  window.URL.revokeObjectURL(url)
}

onMounted(() => {
  fetchResources()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/Resources2.jpg" alt="Resources & Directives" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የሰነዶች፣ መመሪያዎችና የማመልከቻ ቅጾች ቤተ-መዛግብት' : 'Public Resources & Municipal Directives Library' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በምክር ቤቱ የጸደቁ ይፋዊ ደንቦች፣ የከተማ መሪ ማስተር ፕላን፣ ዓመታዊ በጀቶችና የዜጎች አገልግሎት መጠየቂያ ቅጾችን በነፃ ያውርዱ።'
            : 'Download approved municipal regulations, master spatial plans, annual fiscal budget declarations, and standardized citizen application forms.'
          }}
        </p>

        <!-- Document Analytics Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Cataloged Publications</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ resources.length }} Documents</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Access Protocol</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">Open Public Domain</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Official Formats</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">PDF • Gazette Form</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Archive Integrity</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Secretariat Verified</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200/90 shadow-sm mb-8 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
          
          <!-- Search Input -->
          <div class="md:col-span-8 relative">
            <Search class="absolute left-4 top-3.5 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.currentLang === 'am' ? 'ሰነዶችን በርዕስ ወይም በመመሪያ ቁጥር ይፈልጉ...' : 'Search documents by title, directive number, or keyword...'" 
              class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 text-xs sm:text-sm bg-slate-50 transition"
            />
          </div>

          <!-- Year Filter -->
          <div class="md:col-span-4">
            <select 
              v-model="selectedYear" 
              @change="fetchResources"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 text-xs sm:text-sm bg-slate-50 font-bold text-slate-700 cursor-pointer"
            >
              <option value="all">All Publication Years</option>
              <option value="2026">2026 (2018 ዓ.ም)</option>
              <option value="2025">2025 (2017 ዓ.ም)</option>
              <option value="2024">2024 (2016 ዓ.ም)</option>
            </select>
          </div>
        </div>

        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 border-t border-slate-100 pt-3 scrollbar-none">
          <button
            v-for="cat in categories"
            :key="cat.key"
            @click="selectedCategory = cat.key; fetchResources()"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer whitespace-nowrap shadow-2xs',
              selectedCategory === cat.key 
                ? 'bg-blue-900 text-amber-300 ring-2 ring-blue-900/20' 
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
          </button>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="space-y-4">
        <div v-for="n in 4" :key="n" class="bg-white rounded-3xl border border-slate-200 p-6 animate-pulse h-28"></div>
      </div>

      <!-- Resources Document List -->
      <div v-else-if="filteredResources.length > 0" class="space-y-4">
        <div 
          v-for="doc in filteredResources" 
          :key="doc.id" 
          class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col sm:flex-row sm:items-center justify-between gap-6 group"
        >
          <div class="flex items-start gap-4">
            <!-- PDF Icon Badge -->
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center font-mono font-black text-xs shrink-0 border border-rose-200 group-hover:bg-rose-600 group-hover:text-white transition-colors">
              PDF
            </div>

            <!-- Content -->
            <div class="space-y-1">
              <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                  {{ doc.category || 'Gazette' }}
                </span>
                <span v-if="doc.year" class="text-xs font-mono font-bold text-slate-400">
                  {{ doc.year }}
                </span>
              </div>

              <h2 class="text-base sm:text-lg font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug">
                {{ getTitle(doc) }}
              </h2>

              <p v-if="getDescription(doc)" class="text-xs text-slate-500 line-clamp-1 leading-relaxed">
                {{ getDescription(doc) }}
              </p>
            </div>
          </div>

          <!-- Download Action -->
          <div class="shrink-0 flex items-center gap-3 border-t sm:border-t-0 pt-4 sm:pt-0">
            <button 
              @click="downloadDocument(doc)"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-amber-300 text-xs font-black uppercase tracking-wider transition-all shadow-md hover:scale-105 cursor-pointer"
            >
              <Download class="w-4 h-4 text-amber-400" />
              <span>Download PDF</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <FileCheck class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No public documents found</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Try clearing your search keyword or switching categories.</p>
        <button 
          @click="selectedCategory = 'all'; selectedYear = 'all'; searchQuery = ''; fetchResources()" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          View All Documents
        </button>
      </div>
    </div>
  </div>
</template>
