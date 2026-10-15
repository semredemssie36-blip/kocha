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
  Building
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const resources = ref([])
const selectedCategory = ref('all')
const searchQuery = ref('')
const selectedYear = ref('all')

const categories = [
  { key: 'all', label_en: 'All Documents', label_am: 'ሁሉንም ሰነዶች', label_om: 'Sanadoota Hunda' },
  { key: 'Master Plan', label_en: 'Master Plan', label_am: 'የከተማ ማስተር ፕላን', label_om: 'Pilaanii Magaalaa' },
  { key: 'Directives', label_en: 'Directives & Bylaws', label_am: 'መመሪያዎችና ደንቦች', label_om: 'Qajeelfamoota' },
  { key: 'Annual Reports', label_en: 'Annual Budgets & Reports', label_am: 'ዓመታዊ በጀትና ሪፖርቶች', label_om: 'Baajata fi Gabaasa' },
  { key: 'Forms', label_en: 'Application Forms & Charters', label_am: 'የማመልከቻ ቅጾች', label_om: 'Uunkaalee Iyyannoo' },
]

const years = ['all', '2026', '2025', '2024']

const fetchResources = async () => {
  loading.value = true
  try {
    const params = {}
    if (selectedCategory.value !== 'all') params.category = selectedCategory.value
    if (selectedYear.value !== 'all') params.year = selectedYear.value
    const res = await api.getResources(params)
    resources.value = res.data || []
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
  if (lang === 'am') return doc.description_am || doc.description_en
  if (lang === 'om') return doc.description_om || doc.description_en || doc.description_am
  return doc.description_en || doc.description_am
}

const downloadDocument = (doc) => {
  if (doc.file_path) {
    window.open(doc.file_path, '_blank')
    return
  }
  // Trigger simulated official PDF download
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
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <FileCheck class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር ይፋዊ ሰነዶች ማዕከል' : (langStore.currentLang === 'om' ? 'Wiirtuu Sanadoota Bulchiinsa Magaalaa Kombolchaa' : 'Official Document & Public Resource Repository') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'የሰነዶችና መመሪያዎች ማውረጃ ማዕከል' : (langStore.currentLang === 'om' ? 'Kuusaa Sanadootaa fi Qajeelfamootaa' : 'Public Resources & Directives Library') }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'በምክር ቤቱ የጸደቁ ደንቦች፣ የከተማ መሪ ማስተር ፕላን፣ ዓመታዊ በጀቶች እና የንግድ ምዝገባ ቅጾችን ያውርዱ።' : (langStore.currentLang === 'om' ? 'Qajeelfamoota mana mareetiin mirkanaa\'an, pilaanii magaalaa, baajata waggaa fi uunkaalee adda addaa buufadhaa.' : 'Freely download approved municipal directives, land cadastre manuals, urban spatial plans, and annual fiscal reports.') }}
        </p>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-10">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
        <!-- Search Input -->
        <div class="md:col-span-6 relative">
          <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="langStore.currentLang === 'am' ? 'ሰነዶችን በርእስ ወይም በመመሪያ ቁጥር ይፈልጉ...' : (langStore.currentLang === 'om' ? 'Sanada barbaadaa...' : 'Search documents by title or topic...')"
            class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-sm bg-slate-50/50"
          />
        </div>

        <!-- Year Filter -->
        <div class="md:col-span-3">
          <div class="relative">
            <Calendar class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <select 
              v-model="selectedYear" 
              @change="fetchResources"
              class="w-full pl-10 pr-8 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50 font-medium text-slate-700"
            >
              <option value="all">{{ langStore.currentLang === 'am' ? 'ሁሉም ዓመታት' : (langStore.currentLang === 'om' ? 'Waggaa Hunda' : 'All Publication Years') }}</option>
              <option value="2026">2026 (2018 ዓ.ም)</option>
              <option value="2025">2025 (2017 ዓ.ም)</option>
              <option value="2024">2024 (2016 ዓ.ም)</option>
            </select>
          </div>
        </div>

        <!-- Document Count Stats -->
        <div class="md:col-span-3 flex justify-end">
          <div class="px-4 py-2.5 rounded-xl bg-blue-50 border border-blue-100 text-blue-900 text-xs font-semibold inline-flex items-center gap-2">
            <HardDrive class="w-4 h-4 text-blue-600" />
            <span>{{ filteredResources.length }} {{ langStore.currentLang === 'am' ? 'የተገኙ ሰነዶች' : (langStore.currentLang === 'om' ? 'Sanadoota' : 'Documents Available') }}</span>
          </div>
        </div>
      </div>

      <!-- Categories Pills -->
      <div class="flex items-center gap-2 mt-6 overflow-x-auto pb-2 scrollbar-none">
        <button
          v-for="cat in categories"
          :key="cat.key"
          @click="selectedCategory = cat.key; fetchResources()"
          :class="[
            'px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all duration-200 cursor-pointer',
            selectedCategory === cat.key
              ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20'
              : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
          ]"
        >
          {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
        </button>
      </div>
    </div>

    <!-- Documents Grid -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div v-for="i in 4" :key="i" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm animate-pulse">
        <div class="h-6 w-1/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-8 w-3/4 bg-slate-200 rounded mb-2"></div>
        <div class="h-16 w-full bg-slate-100 rounded mb-4"></div>
        <div class="h-10 w-full bg-slate-200 rounded"></div>
      </div>
    </div>

    <div v-else-if="filteredResources.length === 0" class="text-center py-16 bg-white rounded-2xl border border-slate-200">
      <FileText class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="text-lg font-bold text-slate-800">
        {{ langStore.currentLang === 'am' ? 'ምንም አይነት ሰነድ አልተገኘም' : (langStore.currentLang === 'om' ? 'Sanadni homaayyuu hin argamne' : 'No Documents Found') }}
      </h3>
      <p class="text-slate-500 text-sm mt-1">
        {{ langStore.currentLang === 'am' ? 'እባክዎ የፍለጋ ቃላትን ይቀይሩ ወይም ምድቦችን ያስተካክሉ።' : 'Try broadening your search keywords or switching filters.' }}
      </p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="doc in filteredResources" 
        :key="doc.id"
        class="bg-white rounded-2xl border border-slate-200 hover:border-blue-400 p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
      >
        <div>
          <!-- Header Meta -->
          <div class="flex items-center justify-between mb-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100">
              <Building class="w-3 h-3 text-blue-600" />
              {{ doc.category }}
            </span>
            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium">
              <span class="flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5 text-slate-400" />
                {{ doc.year }}
              </span>
              <span class="px-2 py-0.5 rounded bg-slate-100 font-bold text-slate-600 uppercase text-[10px]">
                {{ doc.file_extension || 'PDF' }}
              </span>
            </div>
          </div>

          <!-- Title -->
          <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors duration-200 mb-2 leading-snug">
            {{ getTitle(doc) }}
          </h3>

          <!-- Description -->
          <p class="text-slate-600 text-sm leading-relaxed mb-6 line-clamp-3">
            {{ getDescription(doc) }}
          </p>
        </div>

        <!-- Footer Actions -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <div class="text-xs text-slate-500 font-medium">
            <span class="font-bold text-slate-700">{{ doc.file_size_formatted || '2.4 MB' }}</span>
            <span class="mx-1.5">•</span>
            <span>{{ doc.downloads_count || 120 }} {{ langStore.currentLang === 'am' ? 'ውርዶች' : 'Downloads' }}</span>
          </div>

          <button 
            @click="downloadDocument(doc)"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-blue-600 text-white text-xs font-bold transition-all duration-200 shadow-sm cursor-pointer hover:shadow-md hover:shadow-blue-600/20"
          >
            <Download class="w-4 h-4" />
            <span>{{ langStore.currentLang === 'am' ? 'ሰነዱን አውርድ' : (langStore.currentLang === 'om' ? 'Buufadhaa' : 'Download File') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
