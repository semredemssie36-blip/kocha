<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Award, 
  Mail, 
  Phone, 
  Building2, 
  Users, 
  CheckCircle,
  FileText,
  ShieldCheck,
  Calendar,
  Landmark,
  ExternalLink,
  ChevronRight,
  Sparkles,
  MapPin,
  Clock,
  Briefcase,
  History,
  PhoneCall,
  UserCheck
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const langStore = useLanguageStore()
const loading = ref(true)
const mayor = ref(null)
const FALLBACK_HISTORICAL_MAYORS = [
  {
    id: 1,
    order: 1,
    is_current: false,
    name_am: 'አቶ ያሲን ሁሴን',
    name_en: 'Ato Yasin Husen',
    name_om: 'Obbo Yaasiin Huseen',
    phone: null,
    term_start: '01/01/1998 ዓ.ም',
    term_end: '08/01/1999 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር የመጀመሪያው ከንቲባ በመሆን ከ1998 እስከ 1999 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as the first Mayor of Kombolcha City Administration from 2005 to 2006 G.C.'
  },
  {
    id: 2,
    order: 2,
    is_current: false,
    name_am: 'አቶ አበበ አሸቴ',
    name_en: 'Ato Abebaw Eshetu',
    name_om: 'Obbo Abbabaa Asheetee',
    phone: null,
    term_start: '08/01/1999 ዓ.ም',
    term_end: '30/10/2000 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከ1999 እስከ 2000 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from 2006 to 2008 G.C.'
  },
  {
    id: 3,
    order: 3,
    is_current: false,
    name_am: 'አቶ ሰይድ ኑር',
    name_en: 'Ato Seid Nur',
    name_om: 'Obbo Sayid Nuur',
    phone: null,
    term_start: '01/11/2000 ዓ.ም',
    term_end: '28/05/2002 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከ2000 እስከ 2002 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from 2008 to 2010 G.C.'
  },
  {
    id: 4,
    order: 4,
    is_current: false,
    name_am: 'አቶ ያሲን ሀሰን',
    name_en: 'Ato Yasin Hassen',
    name_om: 'Obbo Yaasiin Hasan',
    phone: null,
    term_start: '01/06/2002 ዓ.ም',
    term_end: '13/02/2004 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከ2002 እስከ 2004 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from 2010 to 2011 G.C.'
  },
  {
    id: 5,
    order: 5,
    is_current: false,
    name_am: 'አቶ ከድር አህመድ',
    name_en: 'Ato Kedir Ahmed',
    name_om: 'Obbo Kadiir Ahimad',
    phone: '0913214567',
    term_start: '14/02/2004 ዓ.ም',
    term_end: '30/02/2008 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከጥቅምት 2004 እስከ ጥቅምት 2008 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from October 2011 to October 2015 G.C.'
  },
  {
    id: 6,
    order: 6,
    is_current: false,
    name_am: 'አቶ ደነቀ ምትኩ',
    name_en: 'Ato Deneke Mitiku',
    name_om: 'Obbo Danqa Mitikkuu',
    phone: null,
    term_start: '01/03/2008 ዓ.ም',
    term_end: '05/11/2010 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከህዳር 2008 እስከ ሐምሌ 2010 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from November 2015 to July 2018 G.C.'
  },
  {
    id: 7,
    order: 7,
    is_current: false,
    name_am: 'አቶ ከበደ ይመር',
    name_en: 'Ato Kebede Yimer',
    name_om: 'Obbo Kabbadaa Yimaam',
    phone: null,
    term_start: '07/11/2010 ዓ.ም',
    term_end: '08/01/2011 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከሐምሌ 2010 እስከ መስከረም 2011 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from July 2018 to September 2018 G.C.'
  },
  {
    id: 8,
    order: 8,
    is_current: false,
    name_am: 'ኢንጅነር ዶክተር ከማል ዘይኑ',
    name_en: 'Eng. Dr. Kemal Zeynu',
    name_om: 'Injiinar Dr. Kamaal Zayinuu',
    phone: null,
    term_start: '08/01/2011 ዓ.ም',
    term_end: '25/06/2014 ዓ.ም',
    biography_am: 'የኮምቦልቻ ከተማ አስተዳደር ከንቲባ በመሆን ከመስከረም 2011 እስከ የካቲት 2014 ዓ.ም አገልግለዋል።',
    biography_en: 'Served as Mayor of Kombolcha City Administration from September 2018 to February 2022 G.C.'
  },
  {
    id: 9,
    order: 9,
    is_current: true,
    name_am: 'አቶ መሀመድ አሚን የሱፍ',
    name_en: 'Hon. Mohammedamin Yesuf',
    name_om: 'Obbo Mahaammad-Amiin Yusuuf',
    phone: '0335514062',
    term_start: '27/06/2014 ዓ.ም',
    term_end: 'እስከ አሁን በስራ ላይ',
    biography_am: 'ክቡር አቶ መሀመድአሚን የሱፍ የኮምቦልቻ ከተማ አስተዳደር ዋና ከንቲባ በመሆን ከየካቲት 2014 ዓ.ም ጀምሮ ከተማዋን በከፍተኛ ቁርጠኝነት እየመሩ ይገኛሉ።',
    biography_en: 'Hon. Mohammedamin Yesuf serves as the Executive Mayor of Kombolcha Metropolitan City Administration since February 2022.'
  }
]

const historicalMayors = ref(FALLBACK_HISTORICAL_MAYORS)
const activeTab = ref('history')
const cabinetMembers = ref([])
const councilMembers = ref([])

function setTab(tab) {
  activeTab.value = tab
  router.replace({ query: { ...route.query, tab } })
}

const fetchMayorData = async () => {
  loading.value = true
  try {
    const [mayorRes, historyRes, cabinetRes, councilRes] = await Promise.allSettled([
      api.getMayor(),
      api.getMayorsHistory(),
      api.getLeadership({ type: 'cabinet' }),
      api.getLeadership({ type: 'council' })
    ])

    if (mayorRes.status === 'fulfilled' && mayorRes.value?.data) {
      mayor.value = mayorRes.value.data
    }
    if (historyRes.status === 'fulfilled' && Array.isArray(historyRes.value?.data) && historyRes.value.data.length > 0) {
      historicalMayors.value = historyRes.value.data
    } else {
      historicalMayors.value = FALLBACK_HISTORICAL_MAYORS
    }
    if (cabinetRes.status === 'fulfilled' && Array.isArray(cabinetRes.value?.data)) {
      cabinetMembers.value = cabinetRes.value.data
    }
    if (councilRes.status === 'fulfilled' && Array.isArray(councilRes.value?.data)) {
      councilMembers.value = councilRes.value.data
    }
  } catch (err) {
    console.warn('Network issue fetching mayor data, fallback in place:', err)
    if (!historicalMayors.value || historicalMayors.value.length === 0) {
      historicalMayors.value = FALLBACK_HISTORICAL_MAYORS
    }
  } finally {
    loading.value = false
  }
}

const getMayorName = () => {
  if (!mayor.value) return 'Mayor of Kombolcha'
  const lang = langStore.currentLang
  if (lang === 'am') return mayor.value.name_am || mayor.value.name_en
  if (lang === 'om') return mayor.value.name_om || mayor.value.name_en
  return mayor.value.name_en || mayor.value.name_am
}

const getWelcomeMessage = () => {
  if (!mayor.value) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return mayor.value.welcome_message_am || mayor.value.welcome_message_en
  if (lang === 'om') return mayor.value.welcome_message_om || mayor.value.welcome_message_en
  return mayor.value.welcome_message_en || mayor.value.welcome_message_am
}

const getBiography = () => {
  if (!mayor.value) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return mayor.value.biography_am || mayor.value.biography_en
  if (lang === 'om') return mayor.value.biography_om || mayor.value.biography_en
  return mayor.value.biography_en || mayor.value.biography_am
}

const getHistoryMayorName = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.name_am || m.name_en
  if (lang === 'om') return m.name_om || m.name_en
  return m.name_en || m.name_am
}

const getHistoryBiography = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.biography_am || m.biography_en
  if (lang === 'om') return m.biography_om || m.biography_en
  return m.biography_en || m.biography_am
}

const getMemberName = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.name_am || m.name_en
  if (lang === 'om') return m.name_om || m.name_en
  return m.name_en || m.name_am
}

const getMemberPosition = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.position_am || m.position_en
  if (lang === 'om') return m.position_om || m.position_en
  return m.position_en || m.position_am
}

onMounted(() => {
  if (route.query.tab && ['history', 'cabinet', 'council'].includes(route.query.tab)) {
    activeTab.value = route.query.tab
    setTimeout(() => {
      const el = document.getElementById('leadership-section')
      if (el) el.scrollIntoView({ behavior: 'smooth' })
    }, 200)
  }
  fetchMayorData()
})

watch(() => route.query.tab, (newTab) => {
  if (newTab && ['history', 'cabinet', 'council'].includes(newTab)) {
    activeTab.value = newTab
    setTimeout(() => {
      const el = document.getElementById('leadership-section')
      if (el) el.scrollIntoView({ behavior: 'smooth' })
    }, 100)
  }
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/earport2.webp" alt="Mayor Executive Office" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
       
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የከንቲባው ጽሕፈት ቤትና የከተማው አመራር' : "Mayor's Office & Civic Governance" }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ ከንቲባ ይፋዊ የክብር መልእክት፣ የቀድሞ ከንቲባዎች ታሪካዊ ሰነድ፣ የስራ አስፈፃሚ ካቢኔ አባላትና የከተማው ምክር ቤት አመራር ማውጫ።'
            : 'Mayoral keynote address, chronological history of municipal mayors, executive cabinet portfolios, and city council leadership.'
          }}
        </p>

        <!-- Executive Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Executive Mayor</span>
            <div class="text-base sm:text-lg font-black text-white truncate">{{ mayor ? getMayorName() : 'Mayor' }}</div>
            <span class="text-[10px] text-amber-400 font-bold block mt-0.5">Incumbent Leader</span>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-amber-500/30">
            <span class="text-amber-400 text-xs block font-bold mb-1">Mayoral Lineage</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-white">{{ historicalMayors.length || 9 }} Mayors</div>
            <span class="text-[10px] text-slate-400 block mt-0.5">1998 E.C. - Present</span>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Mayoral Cabinet</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">{{ cabinetMembers.length }} Members</div>
            <span class="text-[10px] text-slate-400 block mt-0.5">Executive Portfolios</span>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">City Council</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-300">{{ councilMembers.length }} Members</div>
            <span class="text-[10px] text-slate-400 block mt-0.5">Elected Representation</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      
      <!-- Loading Skeleton -->
      <div v-if="loading" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs mb-12 animate-pulse space-y-4">
        <div class="h-8 w-1/3 bg-slate-200 rounded"></div>
        <div class="h-40 bg-slate-100 rounded"></div>
      </div>

      <!-- SECTION 1: Incumbent Mayor State Keynote Card -->
      <div v-else-if="mayor" class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-lg hover:border-slate-300 transition-all mb-10 relative">
        <div class="h-1.5 w-full bg-[#1C398E]"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12">
          
          <!-- Left Column: Mayor Portrait in Royal Frame (4 cols) -->
          <div class="lg:col-span-4 bg-gradient-to-b from-slate-50 to-slate-100/60 border-b lg:border-b-0 lg:border-r border-slate-200 p-8 sm:p-10 flex flex-col items-center justify-center text-center relative">
            <div class="w-52 h-52 sm:w-60 sm:h-60 rounded-3xl overflow-hidden p-2 bg-[#1C398E] shadow-xl mb-6 relative bg-white">
              <div class="w-full h-full rounded-[20px] overflow-hidden bg-slate-100 relative">
                <img 
                  v-if="mayor.portrait_path"
                  :src="mayor.portrait_path" 
                  :alt="getMayorName()" 
                  class="w-full h-full object-cover object-top"
                />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                  <Award class="w-14 h-14 opacity-30 text-blue-700" />
                  <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-2">Executive Mayor</span>
                </div>
              </div>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-black uppercase tracking-wider mb-2">
              <ShieldCheck class="w-3.5 h-3.5 text-emerald-600" />
              <span>{{ langStore.currentLang === 'am' ? 'የወቅቱ ከንቲባ' : 'Incumbent Mayor' }}</span>
            </div>

            <h2 class="text-2xl font-black text-slate-900 mb-1 tracking-tight">{{ getMayorName() }}</h2>
            <p class="text-xs font-black text-blue-900 tracking-wider uppercase mb-4">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር ዋና ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa Magaalaa Kombolchaa' : 'Executive Mayor of Kombolcha City') }}
            </p>

            <div v-if="mayor.term_start" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-mono font-bold shadow-2xs mb-4">
              <Calendar class="w-3.5 h-3.5 text-blue-700" />
              <span>{{ mayor.term_start }} - {{ mayor.term_end || 'Present' }}</span>
            </div>

            <div class="w-full pt-4 border-t border-slate-200/80 space-y-2 text-xs">
              <a 
                href="tel:0335514062" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-blue-50 text-blue-900 font-mono font-bold hover:bg-blue-100 transition-colors"
              >
                <Phone class="w-3.5 h-3.5 text-blue-700" />
                <span>033 551 40 62 (Direct Desk)</span>
              </a>
              <a 
                href="mailto:kochamayoroffice@gmail.com" 
                class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 font-mono text-[11px] hover:bg-slate-200 transition-colors"
              >
                <Mail class="w-3.5 h-3.5 text-slate-500" />
                <span>kochamayoroffice@gmail.com</span>
              </a>
            </div>
          </div>

          <!-- Right Column: Mayoral Keynote Address (8 cols) -->
          <div class="lg:col-span-8 p-8 sm:p-12 flex flex-col justify-between space-y-6">
            <div>
              <div class="inline-flex items-center gap-2 text-xs font-black text-blue-900 uppercase tracking-widest mb-4 px-3 py-1 bg-blue-50 rounded-full border border-blue-200">
                <FileText class="w-4 h-4 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? 'የከንቲባው ይፋዊ የእንኳን ደህና መጡ መልእክት' : 'Official Mayoral Keynote Address' }}</span>
              </div>

              <!-- Keynote Blockquote -->
              <blockquote class="text-base sm:text-lg font-medium text-slate-800 leading-relaxed mb-6 border-l-4 border-amber-500 pl-6 py-2 relative bg-amber-50/30 rounded-r-2xl">
                <p class="relative z-10 text-justify">{{ getWelcomeMessage() }}</p>
              </blockquote>

              <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                <Award class="w-4 h-4 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? 'የህይወት ታሪክና የልማት ራዕይ' : 'Executive Biography & Municipal Priorities' }}</span>
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed font-normal text-justify">
                {{ getBiography() }}
              </p>
            </div>

            <!-- Direct Office Inquiries Strip -->
            <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold">
              <span class="text-slate-500">
                {{ langStore.currentLang === 'am' ? 'የከንቲባ ጽሕፈት ቤት ቀጥታ መስመሮች ክፍት ናቸው' : 'Direct desk inquiries and official secretariat communications' }}
              </span>
              <router-link 
                to="/contact" 
                class="px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black uppercase tracking-wider transition-all shadow-md flex items-center gap-2"
              >
                <Mail class="w-4 h-4" />
                <span>{{ langStore.currentLang === 'am' ? 'ለከንቲባው ጽ/ቤት መልእክት ላክ' : 'Send Inquiry to Mayor Office' }}</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 2: Official Mayoral Office Desk Extensions (Document Page 24) -->
      <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-sm mb-12">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
          <div class="w-10 h-10 rounded-2xl bg-blue-900 text-amber-300 flex items-center justify-center shrink-0">
            <PhoneCall class="w-5 h-5" />
          </div>
          <div>
            <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 block">
              {{ langStore.currentLang === 'am' ? 'ይፋዊ የስልክ ማውጫ' : 'Official Telephone Directory' }}
            </span>
            <h3 class="text-lg sm:text-xl font-black text-slate-900">
              {{ langStore.currentLang === 'am' ? 'የከንቲባ ጽሕፈት ቤት ቀጥታ የዴስክ ስልኮች' : "Mayor's Office Internal Desk Extensions" }}
            </h3>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
          <!-- Main Mayor -->
          <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200/80 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-blue-900 block">
                {{ langStore.currentLang === 'am' ? 'ዋና ከንቲባ' : 'Executive Mayor Desk' }}
              </span>
              <a href="tel:0335514062" class="text-sm font-black font-mono text-slate-900 hover:text-blue-700 transition-colors">
                033 551 40 62
              </a>
            </div>
            <a href="tel:0335514062" class="w-8 h-8 rounded-xl bg-blue-900 text-white flex items-center justify-center hover:bg-blue-800 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>

          <!-- Deputy Mayor -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-slate-600 block">
                {{ langStore.currentLang === 'am' ? 'ምክትል ከንቲባ' : 'Deputy Mayor Desk' }}
              </span>
              <a href="tel:0335512397" class="text-sm font-black font-mono text-slate-900 hover:text-blue-700 transition-colors">
                033 551 23 97
              </a>
            </div>
            <a href="tel:0335512397" class="w-8 h-8 rounded-xl bg-slate-800 text-white flex items-center justify-center hover:bg-slate-700 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>

          <!-- Planning & Development -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-slate-600 block">
                {{ langStore.currentLang === 'am' ? 'ፕላንና ልማት' : 'Planning & Development' }}
              </span>
              <a href="tel:0335512424" class="text-sm font-black font-mono text-slate-900 hover:text-blue-700 transition-colors">
                033 551 24 24
              </a>
            </div>
            <a href="tel:0335512424" class="w-8 h-8 rounded-xl bg-slate-800 text-white flex items-center justify-center hover:bg-slate-700 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>

          <!-- Science & Technology -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-slate-600 block">
                {{ langStore.currentLang === 'am' ? 'ሳይንስና ቴክኖሎጂ' : 'Science & Technology' }}
              </span>
              <a href="tel:0335514413" class="text-sm font-black font-mono text-slate-900 hover:text-blue-700 transition-colors">
                033 551 44 13
              </a>
            </div>
            <a href="tel:0335514413" class="w-8 h-8 rounded-xl bg-slate-800 text-white flex items-center justify-center hover:bg-slate-700 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>

          <!-- Vital Events -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-slate-600 block">
                {{ langStore.currentLang === 'am' ? 'ወሳኝ ኩነቶች' : 'Civil Status & Vital Events' }}
              </span>
              <a href="tel:0333510028" class="text-sm font-black font-mono text-slate-900 hover:text-blue-700 transition-colors">
                033 351 00 28
              </a>
            </div>
            <a href="tel:0333510028" class="w-8 h-8 rounded-xl bg-slate-800 text-white flex items-center justify-center hover:bg-slate-700 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>

          <!-- Reception / Central Line -->
          <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
            <div>
              <span class="text-[11px] font-bold text-emerald-900 block">
                {{ langStore.currentLang === 'am' ? 'ዋና መቀበያ / ሪሴፕሽን' : 'Central Secretariat Reception' }}
              </span>
              <a href="tel:0335510216" class="text-sm font-black font-mono text-slate-900 hover:text-emerald-700 transition-colors">
                033 551 02 16
              </a>
            </div>
            <a href="tel:0335510216" class="w-8 h-8 rounded-xl bg-emerald-800 text-white flex items-center justify-center hover:bg-emerald-700 transition-colors">
              <Phone class="w-4 h-4" />
            </a>
          </div>
        </div>
      </div>

      <!-- SECTION 3: Leadership & Historical Mayors Tabs -->
      <div id="leadership-section" class="scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-5 mb-8 gap-4">
          <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              {{ langStore.currentLang === 'am' ? 'የከተማው አመራር አካላትና የታሪክ ሰነድ' : 'Civic Leadership & Mayoral Heritage' }}
            </h2>
            <p class="text-xs text-slate-500 mt-1">
              {{ langStore.currentLang === 'am' 
                ? 'የቀድሞ ከንቲባዎች ታሪክ፣ የስራ አስፈፃሚ ካቢኔ እና የምክር ቤት አባላት' 
                : 'Chronological timeline of municipal mayors, executive cabinet portfolios, and city council' 
              }}
            </p>
          </div>

          <!-- 3-Way Tab Switcher -->
          <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 rounded-2xl border border-slate-200 text-xs font-black">
            <button 
              @click="setTab('history')"
              :class="[
                'px-4 py-2.5 rounded-xl transition-all cursor-pointer flex items-center gap-2',
                activeTab === 'history' ? 'bg-blue-900 text-amber-300 shadow-md font-black' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              <History class="w-3.5 h-3.5" />
              <span>{{ langStore.currentLang === 'am' ? 'የከንቲባዎች ታሪክ' : 'Hall of Mayors' }} ({{ historicalMayors.length || 9 }})</span>
            </button>

            <button 
              @click="setTab('cabinet')"
              :class="[
                'px-4 py-2.5 rounded-xl transition-all cursor-pointer flex items-center gap-2',
                activeTab === 'cabinet' ? 'bg-blue-900 text-amber-300 shadow-md font-black' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              <Briefcase class="w-3.5 h-3.5" />
              <span>{{ langStore.currentLang === 'am' ? 'የከተማው ካቢኔ' : 'Cabinet' }} ({{ cabinetMembers.length }})</span>
            </button>

            <button 
              @click="setTab('council')"
              :class="[
                'px-4 py-2.5 rounded-xl transition-all cursor-pointer flex items-center gap-2',
                activeTab === 'council' ? 'bg-blue-900 text-amber-300 shadow-md font-black' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              <Users class="w-3.5 h-3.5" />
              <span>{{ langStore.currentLang === 'am' ? 'ምክር ቤት' : 'Council' }} ({{ councilMembers.length }})</span>
            </button>
          </div>
        </div>

        <!-- TAB 1: Historical Mayors Timeline (Document Pages 4-5) -->
        <div v-if="activeTab === 'history'" class="space-y-6 animate-fadeIn">
          <div class="p-6 rounded-3xl bg-slate-900 text-white mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
              <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block mb-1">
                {{ langStore.currentLang === 'am' ? 'ይፋዊ የከንቲባዎች ማውጫ' : 'Official Mayoral Registry' }}
              </span>
              <h3 class="text-xl font-black text-white">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ የቀድሞና የወቅቱ ከንቲባዎች ታሪክ' : 'Historical Mayors of Kombolcha City' }}
              </h3>
              <p class="text-xs text-slate-400 mt-1">
                {{ langStore.currentLang === 'am' 
                  ? 'ከ1998 ዓ.ም ጀምሮ ከተማዋን የመሩ 9 ከንቲባዎች የስራ ዘመንና ማዕረግ' 
                  : 'Documented civic leadership lineage spanning nine administrations since 1998 E.C.' 
                }}
              </p>
            </div>
            <div class="px-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-amber-400 font-mono font-black text-sm shrink-0">
              9 Mayoral Terms
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="hm in historicalMayors" 
              :key="hm.id"
              :class="[
                'rounded-3xl border p-6 transition-all duration-300 flex flex-col justify-between group',
                hm.is_current 
                  ? 'bg-blue-50/50 border-blue-400 shadow-md hover:shadow-xl' 
                  : 'bg-white border-slate-200/90 shadow-2xs hover:border-slate-300 hover:shadow-md'
              ]"
            >
              <div>
                <!-- Top Order Badge & Status -->
                <div class="flex items-center justify-between mb-4">
                  <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-800 font-mono text-xs font-black">
                    <Award class="w-3.5 h-3.5 text-blue-700" />
                    <span>#{{ hm.order }} {{ langStore.currentLang === 'am' ? 'ከንቲባ' : 'Mayor' }}</span>
                  </div>

                  <span 
                    v-if="hm.is_current" 
                    class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-900 font-black text-[10px] uppercase tracking-wider"
                  >
                    {{ langStore.currentLang === 'am' ? 'የወቅቱ ከንቲባ' : 'Incumbent' }}
                  </span>
                  <span 
                    v-else 
                    class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-wider"
                  >
                    {{ langStore.currentLang === 'am' ? 'የቀድሞ ከንቲባ' : 'Former Mayor' }}
                  </span>
                </div>

                <!-- Mayor Name -->
                <h4 class="text-lg font-black text-slate-900 group-hover:text-blue-900 transition-colors mb-2">
                  {{ getHistoryMayorName(hm) }}
                </h4>

                <!-- Service Term -->
                <div class="flex items-center gap-2 text-xs font-mono font-bold text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 mb-3">
                  <Calendar class="w-4 h-4 text-blue-700 shrink-0" />
                  <span>{{ hm.term_start }} - {{ hm.term_end || 'Present' }}</span>
                </div>

                <!-- Biography Snippet -->
                <p class="text-xs text-slate-600 leading-relaxed">
                  {{ getHistoryBiography(hm) }}
                </p>
              </div>

              <!-- Phone Contact (If documented in PDF) -->
              <div v-if="hm.phone" class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-500 font-medium">Official Contact:</span>
                <a 
                  :href="`tel:${hm.phone}`" 
                  class="inline-flex items-center gap-1.5 text-xs font-mono font-black text-blue-700 hover:text-blue-900 transition-colors"
                >
                  <Phone class="w-3.5 h-3.5" />
                  <span>{{ hm.phone }}</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- TAB 2: Cabinet Members Grid -->
        <div v-else-if="activeTab === 'cabinet'" class="space-y-6 animate-fadeIn">
          <div 
            v-if="cabinetMembers.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <div 
              v-for="member in cabinetMembers"
              :key="member.id"
              class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
            >
              <div>
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm mb-5 bg-slate-100 relative shrink-0">
                  <img 
                    v-if="member.photo_path" 
                    :src="member.photo_path" 
                    :alt="getMemberName(member)" 
                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" 
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center bg-blue-50 text-blue-700">
                    <UserCheck class="w-10 h-10 opacity-40" />
                  </div>
                </div>

                <h3 class="text-lg font-black text-slate-900 mb-1 group-hover:text-blue-900 transition-colors">
                  {{ getMemberName(member) }}
                </h3>
                <p class="text-xs font-bold text-blue-700 leading-snug mb-4">
                  {{ getMemberPosition(member) }}
                </p>
              </div>

              <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                <div v-if="member.phone" class="flex items-center gap-2">
                  <Phone class="w-3.5 h-3.5 text-slate-400" />
                  <span class="font-mono text-slate-700 font-bold">{{ member.phone }}</span>
                </div>
                <div v-if="member.email" class="flex items-center gap-2">
                  <Mail class="w-3.5 h-3.5 text-slate-400" />
                  <span class="font-mono text-slate-700">{{ member.email }}</span>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
            <Briefcase class="w-16 h-16 text-slate-300 mx-auto mb-4" />
            <h3 class="text-lg font-black text-slate-800">No cabinet portfolios found</h3>
            <p class="text-xs text-slate-500 mt-1">Official cabinet appointments will appear here once gazetted by the municipal secretariat.</p>
          </div>
        </div>

        <!-- TAB 3: City Council Members Grid -->
        <div v-else-if="activeTab === 'council'" class="space-y-6 animate-fadeIn">
          <div 
            v-if="councilMembers.length > 0"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <div 
              v-for="member in councilMembers"
              :key="member.id"
              class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xs hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
            >
              <div>
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm mb-5 bg-slate-100 relative shrink-0">
                  <img 
                    v-if="member.photo_path" 
                    :src="member.photo_path" 
                    :alt="getMemberName(member)" 
                    class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" 
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center bg-amber-50 text-amber-700">
                    <Users class="w-10 h-10 opacity-40" />
                  </div>
                </div>

                <h3 class="text-lg font-black text-slate-900 mb-1 group-hover:text-blue-900 transition-colors">
                  {{ getMemberName(member) }}
                </h3>
                <p class="text-xs font-bold text-amber-800 leading-snug mb-4">
                  {{ getMemberPosition(member) }}
                </p>
              </div>

              <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                <div v-if="member.phone" class="flex items-center gap-2">
                  <Phone class="w-3.5 h-3.5 text-slate-400" />
                  <span class="font-mono text-slate-700 font-bold">{{ member.phone }}</span>
                </div>
                <div v-if="member.email" class="flex items-center gap-2">
                  <Mail class="w-3.5 h-3.5 text-slate-400" />
                  <span class="font-mono text-slate-700">{{ member.email }}</span>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
            <Users class="w-16 h-16 text-slate-300 mx-auto mb-4" />
            <h3 class="text-lg font-black text-slate-800">No city council records found</h3>
            <p class="text-xs text-slate-500 mt-1">Elected council representatives will appear here once registered.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
