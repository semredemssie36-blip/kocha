<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  Building2, 
  Search, 
  Globe, 
  Menu, 
  X, 
  ChevronDown,
  ExternalLink,
  ShieldCheck, 
  FileText, 
  Briefcase, 
  Layers, 
  MapPin, 
  Image, 
  Phone, 
  ShieldAlert, 
  PhoneCall, 
  Flame, 
  Droplets, 
  Zap, 
  Activity, 
  LogIn,
  Landmark,
  Compass,
  FileCheck,
  Award,
  Sparkles
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const route = useRoute()
const router = useRouter()

const isAuthenticated = computed(() => {
  return !!localStorage.getItem('kombolcha_admin_token')
})

const iconMap = {
  ShieldAlert,
  Flame,
  Activity,
  Droplets,
  Zap,
  PhoneCall,
  Phone,
  ShieldCheck,
  Building2
}

const colorMap = {
  red: 'text-red-700 bg-red-50/90 border-red-200 hover:bg-red-100',
  orange: 'text-amber-700 bg-amber-50/90 border-amber-200 hover:bg-amber-100',
  rose: 'text-rose-700 bg-rose-50/90 border-rose-200 hover:bg-rose-100',
  blue: 'text-sky-700 bg-sky-50/90 border-sky-200 hover:bg-sky-100',
  amber: 'text-amber-700 bg-amber-50/90 border-amber-200 hover:bg-amber-100',
  emerald: 'text-emerald-700 bg-emerald-50/90 border-emerald-200 hover:bg-emerald-100',
  indigo: 'text-indigo-700 bg-indigo-50/90 border-indigo-200 hover:bg-indigo-100',
  purple: 'text-purple-700 bg-purple-50/90 border-purple-200 hover:bg-purple-100'
}

const emergencyNumbers = ref([])
const emergencyLoading = ref(true)

const getEmergencyIcon = (icon) => {
  if (!icon) return PhoneCall
  if (typeof icon === 'object' || typeof icon === 'function') return icon
  return iconMap[icon] || PhoneCall
}

const getEmergencyColor = (color) => {
  if (!color) return colorMap.red
  if (typeof color === 'string' && color.includes('text-')) return color
  return colorMap[color] || colorMap.red
}

const getEmergencyName = (item) => {
  const lang = langStore.currentLang
  if (lang === 'am') return item.name_am || item.name_en
  if (lang === 'om') return item.name_om || item.name_en
  return item.name_en
}

const mobileMenuOpen = ref(false)
const langDropdownOpen = ref(false)
const activeDropdown = ref(null)
const mobileDropdownOpen = ref({
  about: false,
  government: false,
  news: false,
  projects: false
})

const searchQuery = ref('')
const searchModalOpen = ref(false)

const languages = [
  { code: 'en', label: 'English', sub: 'English' },
  { code: 'am', label: 'አማርኛ', sub: 'Amharic' },
  { code: 'om', label: 'Afaan Oromoo', sub: 'Oromo' }
]

const currentLanguageLabel = computed(() => {
  const current = languages.find(l => l.code === langStore.currentLang)
  return current ? current.label : 'English'
})

function switchLanguage(code) {
  langStore.setLanguage(code)
  langDropdownOpen.value = false
}

function toggleMobileDropdown(key) {
  mobileDropdownOpen.value[key] = !mobileDropdownOpen.value[key]
}

function handleSearch() {
  if (!searchQuery.value.trim()) return
  router.push({ path: '/resources', query: { q: searchQuery.value.trim() } })
  searchModalOpen.value = false
  searchQuery.value = ''
}

function handleClickOutside(e) {
  if (!e.target.closest('.lang-selector')) {
    langDropdownOpen.value = false
  }
}

const fetchEmergencyContacts = async () => {
  try {
    const res = await api.getEmergencyContacts()
    if (res.data && Array.isArray(res.data) && res.data.length > 0) {
      emergencyNumbers.value = res.data
    }
  } catch (err) {
    console.error('Failed to load emergency contacts:', err)
  } finally {
    emergencyLoading.value = false
  }
}

onMounted(() => {
  window.addEventListener('click', handleClickOutside)
  fetchEmergencyContacts()
})

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <header class="sticky top-0 z-50 shadow-xl bg-slate-950 font-sans">
    <!-- Top Utility Bar: Authority Ticker, 24/7 Hotlines, Staff Portal & Language Selector -->
    <div class="bg-slate-900/95 backdrop-blur-md border-b border-slate-800/80 py-1.5 px-3 sm:px-6 2xl:px-12 text-xs text-slate-300">
      <div class="w-full max-w-[1700px] mx-auto flex flex-col lg:flex-row lg:items-center justify-between gap-2 min-w-0">
        
        <!-- Left: Sovereign Municipal Authority & 24/7 Hotlines -->
        <div class="flex items-center gap-3 overflow-x-auto no-scrollbar py-0.5 w-full lg:w-auto min-w-0">
          <!-- Authority Badge -->
          <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-bold text-amber-400 uppercase tracking-widest shrink-0 border-r border-slate-700/80 pr-3">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>FDRE • Amhara Region</span>
          </div>

          <!-- Hotlines Title -->
          <div class="flex items-center gap-1.5 shrink-0 text-white font-extrabold text-[11px]">
            <PhoneCall class="w-3.5 h-3.5 text-red-500 shrink-0" />
            <span class="uppercase tracking-wider whitespace-nowrap text-slate-200">
              {{ langStore.currentLang === 'am' ? 'የ 24/7 ድንገተኛ ጥሪዎች' : (langStore.currentLang === 'om' ? 'Sarara Balaa (24/7)' : '24/7 Hotlines') }}:
            </span>
          </div>

          <!-- Loading Skeleton -->
          <div v-if="emergencyLoading && emergencyNumbers.length === 0" class="flex items-center gap-1.5 shrink-0">
            <div class="h-5 w-20 bg-slate-800 animate-pulse rounded-md"></div>
            <div class="h-5 w-24 bg-slate-800 animate-pulse rounded-md"></div>
          </div>

          <!-- Emergency Pill Badges -->
          <div v-else class="flex items-center gap-1.5 shrink-0">
            <a 
              v-for="item in emergencyNumbers" 
              :key="item.id || item.phone_number || item.phone"
              :href="'tel:' + (item.phone_number || item.phone)"
              :class="[
                'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md border text-[11px] font-bold transition-all shrink-0 shadow-xs hover:scale-105',
                getEmergencyColor(item.badge_color || item.color)
              ]"
              :title="getEmergencyName(item) + ': ' + (item.phone_number || item.phone)"
            >
              <component :is="getEmergencyIcon(item.icon_name || item.icon)" class="w-3 h-3 shrink-0" />
              <span class="hidden md:inline">{{ getEmergencyName(item) }}:</span>
              <span class="font-mono font-black tracking-wider">{{ item.phone_number || item.phone }}</span>
            </a>
          </div>
        </div>

        <!-- Right: Social Channels, Government Internal Board & Multilingual Switcher -->
        <div class="flex items-center justify-between lg:justify-end gap-3 sm:gap-4 shrink-0 w-full lg:w-auto pt-1 lg:pt-0 border-t lg:border-t-0 border-slate-800/80">
          <!-- Social Icons -->
          <div class="flex items-center gap-3 text-slate-400">
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-blue-400 transition-colors" title="Official Facebook Page">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="hover:text-sky-400 transition-colors" title="Official X / Twitter">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 24.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://telegram.org" target="_blank" rel="noopener noreferrer" class="hover:text-sky-400 transition-colors" title="Official Telegram Channel">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.969z"/></svg>
            </a>
          </div>

          <span class="text-slate-700">|</span>

          <!-- Internal Staff Board Link -->
          <router-link 
            to="/internal-board" 
            class="font-semibold text-slate-300 hover:text-amber-400 transition-colors flex items-center gap-1.5 whitespace-nowrap text-[11px]"
          >
            <ShieldCheck class="w-3.5 h-3.5 text-amber-400" />
            <span>{{ langStore.t('nav.internal_board') }}</span>
          </router-link>

          <span class="text-slate-700">|</span>

          <!-- Language Selector -->
          <div class="relative lang-selector shrink-0">
            <button 
              @click.stop="langDropdownOpen = !langDropdownOpen" 
              class="flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-800/80 hover:bg-slate-800 text-slate-200 border border-slate-700/80 font-bold transition-all cursor-pointer text-[11px]"
            >
              <Globe class="w-3.5 h-3.5 text-amber-400" />
              <span>{{ currentLanguageLabel }}</span>
              <ChevronDown class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': langDropdownOpen }" />
            </button>

            <!-- Language Dropdown -->
            <div 
              v-if="langDropdownOpen" 
              class="absolute right-0 top-full mt-1.5 w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl py-1.5 z-50 text-xs backdrop-blur-xl"
            >
              <button 
                v-for="l in languages" 
                :key="l.code"
                @click="switchLanguage(l.code)"
                :class="[
                  'w-full text-left px-3.5 py-2 font-medium transition-colors cursor-pointer flex items-center justify-between',
                  langStore.currentLang === l.code ? 'bg-amber-400/10 text-amber-400 font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                ]"
              >
                <div>
                  <div class="font-bold">{{ l.label }}</div>
                  <div class="text-[10px] text-slate-400">{{ l.sub }}</div>
                </div>
                <span v-if="langStore.currentLang === l.code" class="text-amber-400 font-black">✓</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar: Deep Imperial Navy (#0B192C / #08182B) with Gold Foil Accent -->
    <div class="bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white border-b border-amber-500/20 shadow-lg">
      <div class="w-full max-w-[1700px] mx-auto px-2 sm:px-4 2xl:px-8 py-2 sm:py-2.5 flex items-center justify-between gap-1.5 sm:gap-2.5 2xl:gap-4">
        
        <!-- Official Government Seal & Regal Typography -->
        <router-link to="/" class="flex items-center gap-2.5 group shrink-0 min-w-0">
          <div class="relative shrink-0">
            <div class="absolute -inset-0.5 bg-gradient-to-tr from-amber-500 to-amber-200 rounded-full blur-[1px] opacity-75 group-hover:opacity-100 transition duration-300"></div>
            <img 
              src="/assets/logo.png" 
              alt="Kombolcha City Official Seal" 
              class="relative w-9 h-9 sm:w-11 sm:h-11 2xl:w-12 2xl:h-12 object-contain rounded-full bg-white p-1 shadow-md group-hover:scale-105 transition-transform duration-300"
            />
          </div>
          <div class="min-w-0">
            <div class="flex items-center gap-1.5">
              <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-widest hidden 2xl:inline">
                {{ langStore.currentLang === 'am' ? 'የከተማ አስተዳደር' : 'Metropolitan Administration' }}
              </span>
            </div>
            <h1 class="text-xs sm:text-sm 2xl:text-base font-black tracking-tight text-white leading-tight drop-shadow-sm truncate">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር' : (langStore.currentLang === 'om' ? 'Bulchiinsa Magaalaa Kombolchaa' : 'Kombolcha City Administration') }}
            </h1>
            <p class="text-[9px] 2xl:text-[10px] text-slate-300 font-semibold tracking-wider uppercase truncate hidden 2xl:block">
              {{ langStore.currentLang === 'am' ? 'የኢንዱስትሪና የሎጂስቲክስ መናኸሪያ' : 'The Industrial & Logistics Gateway of Northern Ethiopia' }}
            </p>
          </div>
        </router-link>

        <!-- Desktop Navigation Items with Civic Dropdowns -->
        <nav class="hidden xl:flex items-center gap-0.5 2xl:gap-1.5 text-[11px] 2xl:text-[13px] font-bold text-slate-200 shrink-0">
          
          <!-- Home -->
          <router-link 
            to="/" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap relative group shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/' }"
          >
            <span>{{ langStore.t('nav.home') }}</span>
            <span v-if="route.path === '/'" class="absolute bottom-0 left-2 right-2 h-0.5 bg-amber-400 rounded-full"></span>
          </router-link>

          <!-- About Us Dropdown -->
          <div 
            class="relative group shrink-0" 
            @mouseenter="activeDropdown = 'about'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/about" 
              class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all flex items-center gap-1 whitespace-nowrap shrink-0"
              :class="{ 'text-amber-400 font-black bg-white/10': route.path.startsWith('/about') }"
            >
              <span>{{ langStore.t('nav.about') }}</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-70 group-hover:rotate-180 transition-transform duration-200" />
            </router-link>

            <!-- Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'about'" 
              class="absolute left-0 top-full pt-2 w-60 z-50 animate-fadeIn"
            >
              <div class="bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl py-2 text-slate-200 text-xs backdrop-blur-xl divide-y divide-slate-800">
                <div class="py-1">
                  <router-link to="/about?tab=about" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <Building2 class="w-4 h-4 text-amber-400/80" />
                    <span>{{ langStore.t('nav.about_city') }}</span>
                  </router-link>
                  <router-link to="/about?tab=history" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <Landmark class="w-4 h-4 text-sky-400/80" />
                    <span>{{ langStore.t('nav.about_history') }}</span>
                  </router-link>
                  <router-link to="/about?tab=vision" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <Award class="w-4 h-4 text-emerald-400/80" />
                    <span>{{ langStore.t('nav.about_vision') }}</span>
                  </router-link>
                </div>
                <div class="py-1">
                  <router-link to="/about?tab=people" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <MapPin class="w-4 h-4 text-purple-400/80" />
                    <span>{{ langStore.t('nav.about_demographics') }}</span>
                  </router-link>
                  <router-link to="/about?tab=geography" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <Compass class="w-4 h-4 text-blue-400/80" />
                    <span>{{ langStore.t('nav.about_geography') }}</span>
                  </router-link>
                  <router-link to="/about?tab=administration" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                    <ShieldCheck class="w-4 h-4 text-amber-400/80" />
                    <span>{{ langStore.t('nav.about_administration') }}</span>
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Government Dropdown -->
          <div 
            class="relative group shrink-0" 
            @mouseenter="activeDropdown = 'government'" 
            @mouseleave="activeDropdown = null"
          >
            <button 
              class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all flex items-center gap-1 cursor-pointer whitespace-nowrap shrink-0"
              :class="{ 'text-amber-400 font-black bg-white/10': route.path.startsWith('/government') }"
            >
              <span>{{ langStore.t('nav.government') }}</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-70 group-hover:rotate-180 transition-transform duration-200" />
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'government'" 
              class="absolute left-0 top-full pt-2 w-64 z-50 animate-fadeIn"
            >
              <div class="bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl py-2 text-slate-200 text-xs backdrop-blur-xl">
                <router-link to="/government/mayor" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  <Award class="w-4 h-4 text-amber-400" />
                  <div>
                    <div class="font-bold">{{ langStore.t('nav.mayor') }}</div>
                    <div class="text-[10px] text-slate-400">Executive Mayoral Chamber</div>
                  </div>
                </router-link>
                <router-link to="/government/mayor?tab=cabinet" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  <ShieldCheck class="w-4 h-4 text-sky-400" />
                  <span>{{ langStore.t('nav.cabinet') }}</span>
                </router-link>
                <router-link to="/government/mayor?tab=council" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  <Landmark class="w-4 h-4 text-indigo-400" />
                  <span>{{ langStore.t('nav.council') }}</span>
                </router-link>
                <div class="my-1 border-t border-slate-800"></div>
                <router-link to="/government/sub-cities" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  <MapPin class="w-4 h-4 text-emerald-400" />
                  <span>{{ langStore.t('nav.sub_cities') }} (4 Sub-Cities)</span>
                </router-link>
                <router-link to="/government/offices" class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  <Building2 class="w-4 h-4 text-amber-400" />
                  <span>{{ langStore.t('nav.offices_directory') }} (22 Bureaus)</span>
                </router-link>
              </div>
            </div>
          </div>

          <!-- Citizen Services -->
          <router-link 
            to="/services" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/services' }"
          >
            {{ langStore.t('nav.services') }}
          </router-link>

          <!-- News & Media Dropdown -->
          <div 
            class="relative group shrink-0" 
            @mouseenter="activeDropdown = 'news'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/media/news" 
              class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all flex items-center gap-1 whitespace-nowrap shrink-0"
              :class="{ 'text-amber-400 font-black bg-white/10': route.path.startsWith('/media') }"
            >
              <span>{{ langStore.t('nav.news') }}</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-70 group-hover:rotate-180 transition-transform duration-200" />
            </router-link>

            <!-- Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'news'" 
              class="absolute left-0 top-full pt-2 w-56 z-50 animate-fadeIn"
            >
              <div class="bg-slate-900 border border-slate-700/80 rounded-2xl shadow-2xl py-2 text-slate-200 text-xs backdrop-blur-xl">
                <router-link to="/media/news" class="block px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  {{ langStore.t('nav.latest_news') }}
                </router-link>
                <router-link to="/media/news?filter=announcement" class="block px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  {{ langStore.t('nav.announcement') }}
                </router-link>
                <router-link to="/media/vacancies" class="block px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors">
                  {{ langStore.t('nav.vacancies') }}
                </router-link>
                <router-link to="/media/tenders" class="block px-4 py-2 hover:bg-slate-800/80 hover:text-amber-400 font-semibold transition-colors text-amber-300">
                  {{ langStore.t('nav.tenders') }} (Active Bids)
                </router-link>
              </div>
            </div>
          </div>

          <!-- Explore City -->
          <router-link 
            to="/explore" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/explore' }"
          >
            {{ langStore.t('nav.explore') }}
          </router-link>

          <!-- Projects -->
          <router-link 
            to="/projects" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/projects' }"
          >
            {{ langStore.t('nav.projects') }}
          </router-link>

          <!-- Resources -->
          <router-link 
            to="/resources" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/resources' }"
          >
            {{ langStore.t('nav.resources') }}
          </router-link>

          <!-- Gallery -->
          <router-link 
            to="/gallery" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/gallery' }"
          >
            {{ langStore.t('nav.gallery') }}
          </router-link>

          <!-- Contact -->
          <router-link 
            to="/contact" 
            class="px-2 2xl:px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/10 transition-all whitespace-nowrap shrink-0"
            :class="{ 'text-amber-400 font-black bg-white/10': route.path === '/contact' }"
          >
            {{ langStore.t('nav.contact') }}
          </router-link>
        </nav>

        <!-- Right Action Items: Search Trigger + Executive Portal Login Button + Mobile Toggle -->
        <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
          
          <!-- Search Modal Trigger Button -->
          <button 
            @click="searchModalOpen = true" 
            class="p-2 sm:px-2.5 sm:py-2 2xl:px-3 rounded-xl bg-white/10 hover:bg-white/20 border border-white/15 text-slate-200 hover:text-white transition-all cursor-pointer flex items-center gap-1.5 text-xs font-semibold shrink-0" 
            :title="langStore.t('nav.search_portal')"
          >
            <Search class="w-4 h-4 text-amber-400" />
            <span class="hidden 2xl:inline">{{ langStore.currentLang === 'am' ? 'ማውጫ ፈልግ' : (langStore.currentLang === 'om' ? 'Barbaadi' : 'Search') }}</span>
          </button>

          <!-- Executive Staff Portal Login / Dashboard Button -->
          <router-link 
            :to="isAuthenticated ? '/admin' : '/admin/login'" 
            class="inline-flex items-center gap-1.5 px-2.5 2xl:px-3.5 py-1.5 2xl:py-2 text-xs font-black text-slate-900 bg-gradient-to-r from-amber-400 to-amber-300 hover:from-amber-300 hover:to-amber-200 border border-amber-300 rounded-xl transition-all shadow-md hover:shadow-amber-400/20 hover:scale-105 shrink-0"
            :title="isAuthenticated ? langStore.t('nav.dashboard') : langStore.t('nav.login')"
          >
            <ShieldCheck class="w-3.5 h-3.5 2xl:w-4 2xl:h-4 shrink-0 text-slate-900" />
            <span class="hidden sm:inline uppercase tracking-wider text-[11px] 2xl:text-xs">{{ isAuthenticated ? langStore.t('nav.dashboard') : langStore.t('nav.login') }}</span>
          </router-link>

          <!-- Mobile Hamburger Toggle -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="xl:hidden p-2 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white cursor-pointer shrink-0"
            aria-label="Toggle Navigation Menu"
          >
            <X v-if="mobileMenuOpen" class="w-5 h-5 text-amber-400" />
            <Menu v-else class="w-5 h-5 text-white" />
          </button>
        </div>
      </div>
    </div>

    <!-- Search Modal with High Visual Depth -->
    <div 
      v-if="searchModalOpen" 
      class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-start justify-center pt-24 px-4"
      @click.self="searchModalOpen = false"
    >
      <div class="bg-slate-900 rounded-2xl shadow-2xl w-full max-w-xl p-6 border border-slate-700/80 animate-fadeIn">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
          <div class="flex items-center gap-2">
            <Search class="w-5 h-5 text-amber-400" />
            <h3 class="text-sm font-bold text-white tracking-wide">{{ langStore.t('nav.search_portal') }}</h3>
          </div>
          <button @click="searchModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
            <X class="w-5 h-5" />
          </button>
        </div>
        <form @submit.prevent="handleSearch" class="mt-4 flex gap-2">
          <div class="relative flex-1">
            <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.t('nav.search_input_placeholder')" 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all"
              autofocus
            />
          </div>
          <button 
            type="submit" 
            class="px-6 py-2.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 text-sm font-extrabold rounded-xl transition-all cursor-pointer shadow-md"
          >
            {{ langStore.currentLang === 'am' ? 'ፈልግ' : (langStore.currentLang === 'om' ? 'Barbaadi' : 'Search') }}
          </button>
        </form>
      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div 
      v-if="mobileMenuOpen" 
      class="xl:hidden fixed inset-0 top-[108px] z-40 bg-slate-950/95 backdrop-blur-xl border-t border-slate-800 text-slate-200 overflow-y-auto px-4 py-6 space-y-4"
    >
      <div class="space-y-1 text-sm font-bold divide-y divide-slate-800/80">
        <!-- Home -->
        <router-link 
          to="/" 
          @click="mobileMenuOpen = false"
          class="flex items-center justify-between py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400"
        >
          <span>{{ langStore.t('nav.home') }}</span>
        </router-link>

        <!-- About Dropdown -->
        <div class="py-1">
          <button 
            @click="toggleMobileDropdown('about')" 
            class="w-full flex items-center justify-between py-3 px-3 rounded-xl hover:bg-slate-900 text-left"
          >
            <span>{{ langStore.t('nav.about') }}</span>
            <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.about }" />
          </button>
          <div v-show="mobileDropdownOpen.about" class="pl-4 py-2 space-y-1 text-xs text-slate-400">
            <router-link to="/about?tab=about" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_city') }}</router-link>
            <router-link to="/about?tab=history" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_history') }}</router-link>
            <router-link to="/about?tab=vision" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_vision') }}</router-link>
            <router-link to="/about?tab=people" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_demographics') }}</router-link>
            <router-link to="/about?tab=geography" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_geography') }}</router-link>
            <router-link to="/about?tab=administration" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.about_administration') }}</router-link>
          </div>
        </div>

        <!-- Government Dropdown -->
        <div class="py-1">
          <button 
            @click="toggleMobileDropdown('government')" 
            class="w-full flex items-center justify-between py-3 px-3 rounded-xl hover:bg-slate-900 text-left"
          >
            <span>{{ langStore.t('nav.government') }}</span>
            <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.government }" />
          </button>
          <div v-show="mobileDropdownOpen.government" class="pl-4 py-2 space-y-1 text-xs text-slate-400">
            <router-link to="/government/mayor" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.mayor') }}</router-link>
            <router-link to="/government/mayor?tab=cabinet" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.cabinet') }}</router-link>
            <router-link to="/government/mayor?tab=council" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.council') }}</router-link>
            <router-link to="/government/sub-cities" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.sub_cities') }}</router-link>
            <router-link to="/government/offices" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.offices_directory') }}</router-link>
          </div>
        </div>

        <!-- Services -->
        <router-link 
          to="/services" 
          @click="mobileMenuOpen = false"
          class="flex items-center justify-between py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400"
        >
          <span>{{ langStore.t('nav.services') }}</span>
        </router-link>

        <!-- Media -->
        <div class="py-1">
          <button 
            @click="toggleMobileDropdown('news')" 
            class="w-full flex items-center justify-between py-3 px-3 rounded-xl hover:bg-slate-900 text-left"
          >
            <span>{{ langStore.t('nav.news') }}</span>
            <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.news }" />
          </button>
          <div v-show="mobileDropdownOpen.news" class="pl-4 py-2 space-y-1 text-xs text-slate-400">
            <router-link to="/media/news" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.latest_news') }}</router-link>
            <router-link to="/media/news?filter=announcement" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.announcement') }}</router-link>
            <router-link to="/media/vacancies" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400">{{ langStore.t('nav.vacancies') }}</router-link>
            <router-link to="/media/tenders" @click="mobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:text-amber-400 text-amber-300">{{ langStore.t('nav.tenders') }}</router-link>
          </div>
        </div>

        <!-- Explore -->
        <router-link to="/explore" @click="mobileMenuOpen = false" class="block py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400">
          {{ langStore.t('nav.explore') }}
        </router-link>

        <!-- Projects -->
        <router-link to="/projects" @click="mobileMenuOpen = false" class="block py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400">
          {{ langStore.t('nav.projects') }}
        </router-link>

        <!-- Resources -->
        <router-link to="/resources" @click="mobileMenuOpen = false" class="block py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400">
          {{ langStore.t('nav.resources') }}
        </router-link>

        <!-- Gallery -->
        <router-link to="/gallery" @click="mobileMenuOpen = false" class="block py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400">
          {{ langStore.t('nav.gallery') }}
        </router-link>

        <!-- Contact -->
        <router-link to="/contact" @click="mobileMenuOpen = false" class="block py-3 px-3 rounded-xl hover:bg-slate-900 hover:text-amber-400">
          {{ langStore.t('nav.contact') }}
        </router-link>
      </div>

      <!-- Emergency quick call inside mobile drawer -->
      <div class="pt-4 border-t border-slate-800">
        <p class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-2">24/7 Emergency Dispatch</p>
        <div class="grid grid-cols-2 gap-2">
          <a 
            v-for="item in emergencyNumbers.slice(0, 4)" 
            :key="item.phone_number || item.phone"
            :href="'tel:' + (item.phone_number || item.phone)"
            class="flex items-center gap-2 p-2 rounded-lg bg-slate-900 border border-slate-800 text-xs font-bold"
          >
            <PhoneCall class="w-3.5 h-3.5 text-red-500" />
            <span class="truncate">{{ getEmergencyName(item) }}</span>
          </a>
        </div>
      </div>
    </div>
  </header>
</template>
