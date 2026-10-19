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
  Clock
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const langStore = useLanguageStore()
const loading = ref(true)
const mayor = ref(null)
const activeTab = ref('cabinet')
const cabinetMembers = ref([])
const councilMembers = ref([])

function setTab(tab) {
  activeTab.value = tab
  router.replace({ query: { ...route.query, tab } })
}

const fetchMayorData = async () => {
  loading.value = true
  try {
    const [mayorRes, cabinetRes, councilRes] = await Promise.all([
      api.getMayor(),
      api.getLeadership({ type: 'cabinet' }),
      api.getLeadership({ type: 'council' })
    ])
    mayor.value = mayorRes.data
    cabinetMembers.value = cabinetRes.data || []
    councilMembers.value = councilRes.data || []
  } catch (err) {
    console.error('Failed to load mayor & leadership data:', err)
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
  if (route.query.tab && ['cabinet', 'council'].includes(route.query.tab)) {
    activeTab.value = route.query.tab
    setTimeout(() => {
      const el = document.getElementById('leadership-section')
      if (el) el.scrollIntoView({ behavior: 'smooth' })
    }, 200)
  }
  fetchMayorData()
})

watch(() => route.query.tab, (newTab) => {
  if (newTab && ['cabinet', 'council'].includes(newTab)) {
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
          {{ langStore.currentLang === 'am' ? 'የከንቲባው ጽሕፈት ቤትና የከተማው ካቢኔ' : "Mayor's Office & Leadership" }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ ከንቲባ ይፋዊ የክብር መልእክት፣ የከተማዋ የልማት ስትራቴጂ፣ የስራ አስፈፃሚ ካቢኔ አባላትና የከተማው ምክር ቤት አመራር ማውጫ።'
            : 'Mayoral keynote address, metropolitan development priorities, executive cabinet portfolios, and municipal council leadership.'
          }}
        </p>

        <!-- Executive Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Executive Head</span>
            <div class="text-xl sm:text-2xl font-black text-white truncate">{{ mayor ? getMayorName() : 'Mayor' }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Mayoral Cabinet</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-blue-300">{{ cabinetMembers.length }} Members</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-amber-500/30">
            <span class="text-amber-400 text-xs block font-bold mb-1">City Council</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-amber-400">{{ councilMembers.length }} Members</div>
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

      <!-- Mayor State Proclamation Card -->
      <div v-else-if="mayor" class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-lg hover:border-slate-300 transition-all mb-14 relative">
        <!-- Top Sovereign Ribbon -->
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-700 via-amber-400 to-emerald-600"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12">
          
          <!-- Left Column: Authentic Mayor Portrait in Royal Frame (4 cols) -->
          <div class="lg:col-span-4 bg-gradient-to-b from-slate-50 to-slate-100/60 border-b lg:border-b-0 lg:border-r border-slate-200 p-8 sm:p-10 flex flex-col items-center justify-center text-center relative">
            <div class="w-52 h-52 sm:w-60 sm:h-60 rounded-3xl overflow-hidden p-2 bg-gradient-to-tr from-blue-900 via-amber-400 to-emerald-700 shadow-xl mb-6 relative bg-white">
              <div class="w-full h-full rounded-[20px] overflow-hidden bg-slate-100 relative">
                <img 
                  v-if="mayor.portrait_path"
                  :src="mayor.portrait_path" 
                  :alt="getMayorName()" 
                  class="w-full h-full object-cover object-top"
                />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-100">
                  <Award class="w-14 h-14 opacity-30 text-blue-700" />
                  <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mt-2">Mayoral Office</span>
                </div>
              </div>
            </div>

            <h2 class="text-2xl font-black text-slate-900 mb-1 tracking-tight">{{ getMayorName() }}</h2>
            <p class="text-xs font-black text-amber-700 tracking-widest uppercase mb-4">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa Magaalaa Kombolchaa' : 'Mayor of Kombolcha Metropolitan') }}
            </p>

            <div v-if="mayor.term_start" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 text-slate-700 text-xs font-mono font-bold shadow-2xs">
              <Calendar class="w-3.5 h-3.5 text-blue-700" />
              <span>Term: {{ mayor.term_start }} - {{ mayor.term_end || 'Present' }}</span>
            </div>
          </div>

          <!-- Right Column: Mayoral Keynote Address & Priorities (8 cols) -->
          <div class="lg:col-span-8 p-8 sm:p-12 flex flex-col justify-between space-y-6">
            <div>
              <div class="inline-flex items-center gap-2 text-xs font-black text-blue-900 uppercase tracking-widest mb-4 px-3 py-1 bg-blue-50 rounded-full border border-blue-200">
                <FileText class="w-4 h-4 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? 'የከንቲባው ይፋዊ የእንኳን ደህና መጡ መልእክት' : 'Official Mayoral Keynote Address' }}</span>
              </div>

              <!-- Keynote Blockquote -->
              <blockquote class="text-lg sm:text-xl font-medium text-slate-800 italic leading-relaxed mb-6 border-l-4 border-amber-500 pl-6 py-2 relative">
                <span class="text-6xl font-serif text-amber-200 absolute -top-7 -left-1 select-none pointer-events-none">“</span>
                <p class="relative z-10">{{ getWelcomeMessage() }}</p>
              </blockquote>

              <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                {{ langStore.currentLang === 'am' ? 'የህይወት ታሪክና የልማት ራዕይ' : 'Executive Biography & Municipal Priorities' }}
              </h3>
              <p class="text-sm text-slate-600 leading-relaxed font-normal">
                {{ getBiography() }}
              </p>
            </div>

            <!-- Direct Office Inquiries Strip -->
            <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 text-xs font-semibold">
              <div class="flex items-center gap-5 text-slate-700">
                <span class="flex items-center gap-1.5">
                  <Phone class="w-4 h-4 text-emerald-600" />
                  <span class="font-mono font-bold">+251 33 551 0022</span>
                </span>
                <span class="flex items-center gap-1.5">
                  <Mail class="w-4 h-4 text-sky-600" />
                  <span class="font-mono">mayor@kombolchacity.gov.et</span>
                </span>
              </div>

              <router-link 
                to="/contact" 
                class="px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black uppercase tracking-wider transition-all shadow-md hover:scale-105"
              >
                {{ langStore.currentLang === 'am' ? 'ለከንቲባው ጽ/ቤት መልእክት ላክ' : 'Send Inquiry to Mayor Office' }}
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Leadership Section: Cabinet & Council Tabs -->
      <div id="leadership-section" class="scroll-mt-24">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-5 mb-8 gap-4">
          <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              {{ langStore.currentLang === 'am' ? 'የከተማው አመራር አካላት' : (langStore.currentLang === 'om' ? 'Qaamolee Hoggana Magaalaa' : 'City Governance Leadership') }}
            </h2>
            <p class="text-xs text-slate-500 mt-1">
              {{ langStore.currentLang === 'am' ? 'የከተማው ስራ አስፈፃሚ ካቢኔ እና የምክር ቤት አባላት' : 'Executive Cabinet Council and City Council Representatives' }}
            </p>
          </div>

          <!-- Tab Switcher -->
          <div class="flex items-center gap-2 p-1.5 bg-slate-100 rounded-2xl border border-slate-200 text-xs font-black">
            <button 
              @click="setTab('cabinet')"
              :class="[
                'px-5 py-2.5 rounded-xl transition-all cursor-pointer',
                activeTab === 'cabinet' ? 'bg-blue-900 text-amber-300 shadow-md' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              {{ langStore.currentLang === 'am' ? 'የከተማው ካቢኔ' : 'Cabinet Council' }} ({{ cabinetMembers.length }})
            </button>
            <button 
              @click="setTab('council')"
              :class="[
                'px-5 py-2.5 rounded-xl transition-all cursor-pointer',
                activeTab === 'council' ? 'bg-blue-900 text-amber-300 shadow-md' : 'text-slate-600 hover:text-slate-900'
              ]"
            >
              {{ langStore.currentLang === 'am' ? 'የከተማው ምክር ቤት' : 'City Council' }} ({{ councilMembers.length }})
            </button>
          </div>
        </div>

        <!-- Real Database Members Grid -->
        <div 
          v-if="(activeTab === 'cabinet' ? cabinetMembers : councilMembers).length > 0"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
          <div 
            v-for="member in (activeTab === 'cabinet' ? cabinetMembers : councilMembers)"
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
                  <Users class="w-10 h-10 opacity-40" />
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

        <!-- Empty State -->
        <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
          <Users class="w-16 h-16 text-slate-300 mx-auto mb-4" />
          <h3 class="text-lg font-black text-slate-800">No leadership records found in this category</h3>
          <p class="text-xs text-slate-500 mt-1">Official appointments will appear here once gazetted by the municipal secretariat.</p>
        </div>
      </div>
    </div>
  </div>
</template>
