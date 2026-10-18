<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  FileCheck, 
  Download, 
  Clock, 
  ShieldCheck, 
  AlertCircle, 
  Search, 
  Filter, 
  Building2, 
  Landmark, 
  Calendar, 
  Banknote, 
  FileText, 
  ChevronRight,
  ExternalLink,
  Layers,
  Sparkles,
  Info
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const tenders = ref([])
const loading = ref(true)
const searchQuery = ref('')
const selectedCategory = ref('all')
const statusFilter = ref('all') // 'all' | 'open' | 'closed'
const viewMode = ref('cards') // 'cards' | 'table'

async function fetchTenders() {
  loading.value = true
  try {
    const res = await api.get('/tenders')
    tenders.value = Array.isArray(res.data) ? res.data : (res.data?.data || [])
  } catch (err) {
    console.error('Failed to load tenders:', err)
  } finally {
    loading.value = false
  }
}

function getTitle(tender) {
  const lang = langStore.currentLang
  if (lang === 'am') return tender.title_am || tender.title_en
  if (lang === 'om') return tender.title_om || tender.title_en
  return tender.title_en || tender.title_am
}

function getDescription(tender) {
  const lang = langStore.currentLang
  if (lang === 'am') return tender.description_am || tender.description_en || ''
  if (lang === 'om') return tender.description_om || tender.description_en || ''
  return tender.description_en || tender.description_am || ''
}

function isTenderClosed(tender) {
  if (tender.status === 'closed') return true
  if (!tender.closing_datetime) return false
  return new Date(tender.closing_datetime).getTime() <= Date.now()
}

function formatCountdown(closingDatetime) {
  if (!closingDatetime) return { closed: true, text: langStore.currentLang === 'am' ? 'ጨረታው ተዘግቷል' : (langStore.currentLang === 'om' ? 'Caalbaasiin Cufameera' : 'Tender Closed') }
  const diff = new Date(closingDatetime) - new Date()
  if (diff <= 0) {
    return { 
      closed: true, 
      text: langStore.currentLang === 'am' ? 'ጨረታው ተዘግቷል' : (langStore.currentLang === 'om' ? 'Caalbaasiin Cufameera' : 'Tender Closed') 
    }
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24)
  const minutes = Math.floor((diff / 1000 / 60) % 60)

  if (days <= 0) {
    return {
      closed: false,
      text: langStore.currentLang === 'am' 
        ? `${hours} ሰዓት : ${minutes} ደቂቃ ቀርቷል` 
        : `${hours}h : ${minutes}m left`
    }
  }

  return {
    closed: false,
    text: langStore.currentLang === 'am' 
      ? `${days} ቀናት : ${hours} ሰዓታት ቀርተዋል` 
      : `${days} Days : ${hours} Hours left`
  }
}

const categories = computed(() => {
  const set = new Set(tenders.value.map(t => t.category).filter(Boolean))
  return ['all', ...Array.from(set)]
})

const filteredTenders = computed(() => {
  return tenders.value.filter(tender => {
    // Search query filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const refMatch = (tender.reference_number || '').toLowerCase().includes(q)
      const titleMatch = (getTitle(tender) || '').toLowerCase().includes(q)
      const catMatch = (tender.category || '').toLowerCase().includes(q)
      if (!refMatch && !titleMatch && !catMatch) return false
    }

    // Category filter
    if (selectedCategory.value !== 'all' && tender.category !== selectedCategory.value) {
      return false
    }

    // Status filter
    const closed = isTenderClosed(tender)
    if (statusFilter.value === 'open' && closed) return false
    if (statusFilter.value === 'closed' && !closed) return false

    return true
  })
})

const openTendersCount = computed(() => tenders.value.filter(t => !isTenderClosed(t)).length)
const closedTendersCount = computed(() => tenders.value.filter(t => isTenderClosed(t)).length)

const totalBondAmount = computed(() => {
  return tenders.value
    .filter(t => !isTenderClosed(t))
    .reduce((sum, t) => sum + (Number(t.bid_bond_amount) || 0), 0)
})

onMounted(() => {
  fetchTenders()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/industry_parks.jpg" alt="Public Procurement Tenders" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የመንግስት ግዥና ጨረታዎች መድረክ' : 'Public Procurement & Tenders Portal' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ አስተዳደርና መምሪያዎች ስር የሚወጡ ህጋዊ የከተማ ልማት፣ የግንባታ፣ የእቃ አቅርቦትና የማማከር ጨረታዎች ይፋዊ የሰነድ ማውረጃና የጊዜ ቆጣሪ መድረክ።'
            : 'Official municipal procurement notice board. Transparent competitive bidding dossiers, civil works contracts, goods acquisition, and strict countdown schedules.'
          }}
        </p>

        <!-- Live Bidding Analytics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Total Published Bids</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ tenders.length }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Active Live Bids</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-400">{{ openTendersCount }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-amber-500/30">
            <span class="text-amber-400 text-xs block font-bold mb-1">Active Bid Bonds Guarantee</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-amber-400">
              ETB {{ (totalBondAmount / 1000).toLocaleString() }}K
            </div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Concluded RFPs</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-slate-400">{{ closedTendersCount }}</div>
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
            :placeholder="langStore.currentLang === 'am' ? 'በጨረታ መለያ ቁጥር ወይም ርዕስ ፈልግ...' : 'Search by Tender Reference No. or Title...'" 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>

        <!-- Filter Badges -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Status Toggles -->
          <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
            <button 
              @click="statusFilter = 'all'" 
              :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              All ({{ tenders.length }})
            </button>
            <button 
              @click="statusFilter = 'open'" 
              :class="statusFilter === 'open' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3 py-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1.5"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
              Open ({{ openTendersCount }})
            </button>
            <button 
              @click="statusFilter = 'closed'" 
              :class="statusFilter === 'closed' ? 'bg-slate-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
              class="px-3 py-1.5 rounded-lg transition-all cursor-pointer"
            >
              Closed ({{ closedTendersCount }})
            </button>
          </div>

          <!-- Category Select -->
          <select 
            v-model="selectedCategory" 
            class="px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-600 cursor-pointer"
          >
            <option value="all">All Categories</option>
            <option v-for="cat in categories.filter(c => c !== 'all')" :key="cat" :value="cat">
              {{ cat }}
            </option>
          </select>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="space-y-4">
        <div v-for="n in 4" :key="n" class="bg-white rounded-3xl border border-slate-200 p-6 animate-pulse h-40"></div>
      </div>

      <!-- Tenders List (Financial Cards) -->
      <div v-else-if="filteredTenders.length > 0" class="space-y-5">
        <div 
          v-for="tender in filteredTenders" 
          :key="tender.id"
          class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs hover:border-amber-400 hover:shadow-xl transition-all duration-300 flex flex-col lg:flex-row lg:items-center justify-between gap-6 group relative overflow-hidden"
        >
          <!-- Left Status Stripe -->
          <div 
            class="absolute left-0 top-0 bottom-0 w-1.5"
            :class="!isTenderClosed(tender) ? 'bg-emerald-500' : 'bg-slate-300'"
          ></div>

          <!-- Main Info Column -->
          <div class="space-y-3.5 max-w-4xl pl-2">
            <div class="flex flex-wrap items-center gap-2.5">
              <!-- Reference Number Pill -->
              <span class="px-3 py-1 rounded-lg bg-slate-900 text-amber-400 font-mono text-xs font-black shadow-xs">
                REF: {{ tender.reference_number }}
              </span>
              
              <!-- Category Badge -->
              <span class="px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200">
                {{ tender.category || 'General Procurement' }}
              </span>

              <!-- Live Status Badge -->
              <span 
                :class="[
                  'px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider flex items-center gap-1.5 border',
                  !isTenderClosed(tender) 
                    ? 'bg-emerald-50 text-emerald-800 border-emerald-300' 
                    : 'bg-rose-50 text-rose-800 border-rose-200'
                ]"
              >
                <span class="w-2 h-2 rounded-full" :class="!isTenderClosed(tender) ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500'"></span>
                {{ !isTenderClosed(tender) ? 'Live Bidding' : 'Concluded' }}
              </span>
            </div>

            <!-- Title -->
            <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-snug group-hover:text-blue-900 transition-colors">
              {{ getTitle(tender) }}
            </h2>

            <!-- Description if available -->
            <p v-if="getDescription(tender)" class="text-xs sm:text-sm text-slate-600 line-clamp-2 leading-relaxed">
              {{ getDescription(tender) }}
            </p>

            <!-- Financial & Deadline Specs -->
            <div class="flex flex-wrap items-center gap-6 text-xs text-slate-600 pt-1">
              <!-- Bid Security Bond -->
              <div class="flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                <Banknote class="w-4 h-4 text-emerald-600 shrink-0" />
                <span>
                  Bid Bond: <strong class="text-slate-900 font-mono font-black">ETB {{ Number(tender.bid_bond_amount || 0).toLocaleString() }}</strong> (CPO/Bank Guarantee)
                </span>
              </div>

              <!-- Opening Datetime -->
              <div v-if="tender.opening_datetime" class="flex items-center gap-1.5">
                <Calendar class="w-4 h-4 text-slate-400" />
                <span>Opening: <strong>{{ new Date(tender.opening_datetime).toLocaleDateString() }}</strong></span>
              </div>

              <!-- Closing Datetime -->
              <div class="flex items-center gap-1.5">
                <Clock class="w-4 h-4 text-amber-600" />
                <span>Closing: <strong>{{ new Date(tender.closing_datetime).toLocaleString() }}</strong></span>
              </div>
            </div>
          </div>

          <!-- Right Action & Countdown Column -->
          <div class="shrink-0 flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-4 border-t lg:border-t-0 pt-4 lg:pt-0 pl-2 lg:pl-0">
            <div class="text-left lg:text-right">
              <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-0.5">Bidding Window Closes In</span>
              <div 
                :class="[
                  'text-sm sm:text-base font-black font-mono px-3 py-1 rounded-xl inline-block',
                  !isTenderClosed(tender) ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-slate-100 text-slate-500'
                ]"
              >
                {{ formatCountdown(tender.closing_datetime).text }}
              </div>
            </div>

            <!-- PDF Dossier Download Trigger -->
            <a 
              v-if="tender.document_pdf_path"
              :href="tender.document_pdf_path" 
              target="_blank"
              download
              class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-md hover:scale-105 transition-all cursor-pointer"
            >
              <Download class="w-4 h-4 text-amber-400" />
              <span>Download Bid Dossier (PDF)</span>
            </a>
            <button 
              v-else
              @click="alert('The official bid dossier document is available in person at the Kombolcha Finance & Public Procurement Directorate.')"
              class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs border border-slate-200 transition-all cursor-pointer"
            >
              <Info class="w-4 h-4 text-slate-500" />
              <span>Dossier In-Person Pickup</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <FileCheck class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No matching public tenders found</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
          Try clearing your search query or selecting a different procurement status filter.
        </p>
        <button 
          @click="searchQuery = ''; selectedCategory = 'all'; statusFilter = 'all'" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          Reset All Filters
        </button>
      </div>

      <!-- Submission Guidelines Notice Drawer -->
      <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
        <div class="flex items-center gap-2.5 text-xs font-black text-slate-800 uppercase tracking-wider mb-3">
          <Info class="w-4 h-4 text-blue-700" />
          <span>Statutory Bidding & Submission Notice for Bidders</span>
        </div>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
          All bids must be sealed and deposited into the official Municipal Tender Box located on the 2nd Floor, Room 204 (Finance & Public Procurement Directorate), Kombolcha City Hall Headquarters, before the exact closing datetime specified above. Bids must be accompanied by an unconditional Bank Guarantee or CPO issued by a recognized financial institution in Ethiopia. Late bids will be automatically rejected.
        </p>
        <div class="text-xs font-mono font-bold text-slate-500">
          Inquiries: procurement@kombolchacity.gov.et | Phone: +251 33 551 0022 Ext. 204
        </div>
      </div>
    </div>
  </div>
</template>
