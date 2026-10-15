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
  Calendar
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
  if (lang === 'am') return mayor.value.name_am
  if (lang === 'om') return mayor.value.name_om || mayor.value.name_en
  return mayor.value.name_en
}

const getWelcomeMessage = () => {
  if (!mayor.value) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return mayor.value.welcome_message_am
  if (lang === 'om') return mayor.value.welcome_message_om || mayor.value.welcome_message_en
  return mayor.value.welcome_message_en
}

const getBiography = () => {
  if (!mayor.value) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return mayor.value.biography_am
  if (lang === 'om') return mayor.value.biography_om || mayor.value.biography_en
  return mayor.value.biography_en
}

const getMemberName = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.name_am
  if (lang === 'om') return m.name_om || m.name_en
  return m.name_en
}

const getMemberPosition = (m) => {
  const lang = langStore.currentLang
  if (lang === 'am') return m.position_am
  if (lang === 'om') return m.position_om || m.position_en
  return m.position_en
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
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Award class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር አመራር' : (langStore.currentLang === 'om' ? 'Hoggana Bulchiinsa Magaalaa Kombolchaa' : 'Municipal Executive Leadership') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'የከንቲባው ጽሕፈት ቤት እና ካቢኔ' : (langStore.currentLang === 'om' ? 'Waajjira Kantiibaa fi Kaabinee' : "Mayor's Office & City Administration") }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'የከንቲባው የእንኳን ደህና መጡ መልእክት፣ የከተማዋ የልማት ራዕይ እና የካቢኔ አባላት መረጃ።' : (langStore.currentLang === 'om' ? 'Ergaa baga nagaan dhuftanii kantiibaa fi miseensota kaabinee magaalichaa.' : 'Executive vision, mayoral keynote, and governing cabinet member profiles.') }}
        </p>
      </div>
    </div>

    <!-- Mayor Profile Card -->
    <div v-if="loading" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs mb-12 animate-pulse">
      <div class="h-8 w-1/3 bg-slate-200 rounded mb-4"></div>
      <div class="h-32 bg-slate-100 rounded"></div>
    </div>

    <div v-else-if="mayor" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition-all mb-12">
      <div class="grid grid-cols-1 lg:grid-cols-12">
        <!-- Mayor Image / Badge Column: Clean Crisp Official Border -->
        <div class="lg:col-span-4 bg-slate-50 border-b lg:border-b-0 lg:border-r border-slate-200 p-8 flex flex-col items-center justify-center text-center relative">
          <!-- Official Mayor Portrait -->
          <div class="w-48 h-48 rounded-2xl overflow-hidden border-2 border-slate-300 shadow-md mb-6 relative bg-slate-100 flex items-center justify-center">
            <img 
              v-if="mayor.portrait_path"
              :src="mayor.portrait_path" 
              :alt="getMayorName()" 
              class="w-full h-full object-cover object-top"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
              <Award class="w-12 h-12 mb-1 opacity-40 text-blue-600" />
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mayoral Office</span>
            </div>
            <div class="absolute bottom-2 right-2 px-2.5 py-0.5 rounded bg-emerald-700 text-white text-[10px] font-black tracking-widest uppercase">
              {{ langStore.currentLang === 'am' ? 'በስራ ላይ' : 'Active' }}
            </div>
          </div>

          <h2 class="text-2xl font-black text-slate-900 mb-1">{{ getMayorName() }}</h2>
          <p class="text-xs font-bold text-blue-700 tracking-wider uppercase mb-4">
            {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa Magaalaa Kombolchaa' : 'Mayor of Kombolcha City') }}
          </p>

          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-white border border-slate-200 text-slate-600 text-xs font-mono font-medium">
            <Calendar class="w-3.5 h-3.5 text-slate-500" />
            <span>{{ mayor.term_start }} - {{ mayor.term_end }}</span>
          </div>
        </div>

        <!-- Mayor Welcome Address & Bio Column -->
        <div class="lg:col-span-8 p-8 sm:p-12 flex flex-col justify-between">
          <div>
            <div class="flex items-center gap-2 text-xs font-bold text-blue-600 uppercase tracking-wider mb-3">
              <FileText class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'የከንቲባው ይፋዊ የእንኳን ደህና መጡ መልእክት' : 'Official Mayoral Keynote Address' }}</span>
            </div>

            <blockquote class="text-base sm:text-lg font-medium text-slate-800 italic leading-relaxed mb-6 border-l-4 border-blue-600 pl-4 py-1">
              "{{ getWelcomeMessage() }}"
            </blockquote>

            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
              {{ langStore.currentLang === 'am' ? 'የህይወት ታሪክና የልማት ራዕይ' : 'Executive Biography & Municipal Priorities' }}
            </h3>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
              {{ getBiography() }}
            </p>
          </div>

          <!-- Direct Office Inquiries -->
          <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-4">
              <span class="flex items-center gap-1 text-slate-700">
                <Phone class="w-3.5 h-3.5 text-blue-600" />
                <span class="font-mono font-bold">+251 33 551 0022</span>
              </span>
              <span class="flex items-center gap-1 text-slate-700">
                <Mail class="w-3.5 h-3.5 text-blue-600" />
                <span class="font-mono">mayor@kombolchacity.gov.et</span>
              </span>
            </div>
            <router-link 
              to="/contact" 
              class="px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold transition-colors"
            >
              {{ langStore.currentLang === 'am' ? 'ለከንቲባው ጽ/ቤት መልእክት ላክ' : 'Send Inquiry to Mayor Office' }}
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Leadership Section: Cabinet & Council Tabs -->
    <div id="leadership-section" class="mb-8 scroll-mt-24">
      <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-6">
        <div>
          <h2 class="text-2xl font-black text-slate-900">
            {{ langStore.currentLang === 'am' ? 'የከተማው አመራር አካላት' : (langStore.currentLang === 'om' ? 'Qaamolee Hoggana Magaalaa' : 'City Governance Leadership') }}
          </h2>
          <p class="text-xs text-slate-500 mt-1">
            {{ langStore.currentLang === 'am' ? 'የከተማው ስራ አስፈፃሚ ካቢኔ እና የምክር ቤት አባላት' : 'Executive Cabinet Council and City Council Representatives' }}
          </p>
        </div>

        <!-- Tab Switcher -->
        <div class="flex items-center gap-2 p-1 bg-slate-100 rounded-2xl">
          <button 
            @click="setTab('cabinet')"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
              activeTab === 'cabinet' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'
            ]"
          >
            {{ langStore.currentLang === 'am' ? 'የከተማው ካቢኔ (Executive)' : 'Cabinet Council' }}
          </button>
          <button 
            @click="setTab('council')"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer',
              activeTab === 'council' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'
            ]"
          >
            {{ langStore.currentLang === 'am' ? 'የከተማው ምክር ቤት (Council)' : 'City Council' }}
          </button>
        </div>
      </div>

      <!-- Members Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div 
          v-for="member in (activeTab === 'cabinet' ? cabinetMembers : councilMembers)"
          :key="member.id"
          class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between"
        >
          <div>
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl overflow-hidden border-2 border-slate-200 shadow-sm mb-4 bg-slate-100 relative shrink-0">
              <img 
                v-if="member.photo_path" 
                :src="member.photo_path" 
                :alt="getMemberName(member)" 
                class="w-full h-full object-cover object-top hover:scale-105 transition-transform duration-300" 
              />
              <div v-else class="w-full h-full flex flex-col items-center justify-center bg-blue-50 text-blue-600">
                <Users class="w-10 h-10 opacity-40" />
              </div>
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-1">
              {{ getMemberName(member) }}
            </h3>
            <p class="text-xs font-semibold text-blue-600 leading-snug mb-4">
              {{ getMemberPosition(member) }}
            </p>
          </div>

          <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
            <div v-if="member.phone" class="flex items-center gap-2">
              <Phone class="w-3.5 h-3.5 text-slate-400" />
              <span class="font-mono text-slate-700">{{ member.phone }}</span>
            </div>
            <div v-if="member.email" class="flex items-center gap-2">
              <Mail class="w-3.5 h-3.5 text-slate-400" />
              <span class="font-mono text-slate-700">{{ member.email }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
