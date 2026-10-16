<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { FileCheck, Download, Clock, ShieldCheck, AlertCircle } from 'lucide-vue-next'

const langStore = useLanguageStore()
const tenders = ref([])
const loading = ref(true)

async function fetchTenders() {
  loading.value = true
  try {
    const res = await api.get('/tenders')
    tenders.value = res.data
  } catch (err) {
    console.error('Failed to load tenders:', err)
  } finally {
    loading.value = false
  }
}

// Calculate remaining time
function isTenderClosed(tender) {
  if (tender.status === 'closed') return true
  if (!tender.closing_datetime) return false
  return new Date(tender.closing_datetime).getTime() <= Date.now()
}

function formatCountdown(closingDatetime) {
  const diff = new Date(closingDatetime) - new Date()
  if (diff <= 0) return { closed: true, text: langStore.currentLang === 'am' ? 'ጨረታው ተዘግቷል' : (langStore.currentLang === 'om' ? 'Caalbaasiin Cufameera' : 'Tender Closed') }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  const hours = Math.floor((diff / (1000 * 60 * 60)) % 24)
  const minutes = Math.floor((diff / 1000 / 60) % 60)

  if (days <= 0) {
    return {
      closed: false,
      text: langStore.currentLang === 'am' 
        ? `${hours} ሰዓታት : ${minutes} ደቂቃዎች ቀርተዋል` 
        : `${hours} Hours : ${minutes} Mins left`
    }
  }

  return {
    closed: false,
    text: langStore.currentLang === 'am' 
      ? `${days} ቀናት : ${hours} ሰዓታት ቀርተዋል` 
      : `${days} Days : ${hours} Hours : ${minutes} Mins left`
  }
}

onMounted(() => {
  fetchTenders()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
        Public Procurement Portal
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
        {{ langStore.t('nav.tenders') }}
      </h1>
      <p class="text-slate-600 text-base max-w-2xl">
        Transparent competitive bidding, RFP dossiers, civil works, and goods procurement notices with strict automated deadline countdowns.
      </p>
    </div>

    <!-- Tenders List -->
    <div v-if="loading" class="space-y-4">
      <div v-for="n in 3" :key="n" class="bg-white rounded-2xl border border-slate-200 p-6 animate-pulse h-36"></div>
    </div>

    <div v-else-if="tenders.length > 0" class="space-y-6">
      <div 
        v-for="tender in tenders" 
        :key="tender.id"
        class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm hover:shadow-md transition-shadow flex flex-col lg:flex-row lg:items-center justify-between gap-6"
      >
        <div class="space-y-3 max-w-3xl">
          <div class="flex flex-wrap items-center gap-2.5">
            <span class="px-3 py-1 rounded bg-slate-100 font-mono text-xs font-bold text-slate-800">
              {{ tender.reference_number }}
            </span>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
              Category: {{ tender.category }}
            </span>
            <span 
              :class="[
                'px-2.5 py-0.5 rounded-full text-xs font-bold uppercase',
                !isTenderClosed(tender) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'
              ]"
            >
              {{ langStore.currentLang === 'am' ? 'ሁኔታ' : (langStore.currentLang === 'om' ? 'Haala' : 'Status') }}: {{ !isTenderClosed(tender) ? (langStore.currentLang === 'am' ? 'ክፍት' : (langStore.currentLang === 'om' ? 'BANTAMAA' : 'OPEN')) : (langStore.currentLang === 'am' ? 'የተዘጋ' : (langStore.currentLang === 'om' ? 'CUFAMAA' : 'CLOSED')) }}
            </span>
          </div>

          <h3 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
            {{ langStore.currentLang === 'am' ? tender.title_am : (langStore.currentLang === 'om' ? tender.title_om : tender.title_en) }}
          </h3>

          <div class="flex flex-wrap items-center gap-6 text-xs text-slate-500 pt-1">
            <div class="flex items-center gap-1.5">
              <ShieldCheck class="w-4 h-4 text-emerald-600" />
              <span>Bid Security Bond: <strong>ETB {{ Number(tender.bid_bond_amount).toLocaleString() }}</strong></span>
            </div>
            <div class="flex items-center gap-1.5">
              <Clock class="w-4 h-4 text-amber-600" />
              <span>Closing: <strong>{{ new Date(tender.closing_datetime).toLocaleString() }}</strong></span>
            </div>
          </div>
        </div>

        <!-- Right Action & Countdown -->
        <div class="shrink-0 flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-4 border-t lg:border-t-0 pt-4 lg:pt-0">
          <div class="text-left lg:text-right">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Deadline Countdown</span>
            <span 
              :class="[
                'text-sm font-bold',
                formatCountdown(tender.closing_datetime).closed ? 'text-red-600' : 'text-amber-600 font-mono'
              ]"
            >
              {{ formatCountdown(tender.closing_datetime).text }}
            </span>
          </div>

          <a 
            v-if="tender.document_pdf_path"
            :href="tender.document_pdf_path" 
            target="_blank"
            download
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-md shadow-blue-500/20 transition-all cursor-pointer"
          >
            <Download class="w-4 h-4" />
            <span>Download Bid Dossier (PDF)</span>
          </a>
          <button 
            v-else
            @click="alert('The official bid dossier document is available in person at the Kombolcha Finance & Public Procurement Directorate.')"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-all cursor-pointer"
          >
            <Download class="w-4 h-4" />
            <span>Download Bid Dossier (PDF)</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200">
      <FileCheck class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h4 class="text-base font-bold text-slate-700">No active tenders right now</h4>
      <p class="text-xs text-slate-400 mt-1">Check back soon for new public procurement opportunities.</p>
    </div>
  </div>
</template>
