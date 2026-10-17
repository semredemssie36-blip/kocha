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
  LogIn
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
  red: 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100',
  orange: 'text-orange-700 bg-orange-50 border-orange-200 hover:bg-orange-100',
  rose: 'text-rose-700 bg-rose-50 border-rose-200 hover:bg-rose-100',
  blue: 'text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100',
  amber: 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100',
  emerald: 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100',
  indigo: 'text-indigo-700 bg-indigo-50 border-indigo-200 hover:bg-indigo-100',
  purple: 'text-purple-700 bg-purple-50 border-purple-200 hover:bg-purple-100'
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
  { code: 'en', label: 'English' },
  { code: 'am', label: 'አማርኛ' },
  { code: 'om', label: 'Afaan Oromoo' }
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

// Close dropdowns when clicking outside
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
  <header class="sticky top-0 z-50 shadow-md">
    <!-- Top Utility Bar (White background, social icons, Internal Board, Language selector) -->
    <div class="bg-white border-b border-slate-200 py-1.5 px-3 sm:px-6 2xl:px-12 text-xs text-slate-600">
      <div class="w-full max-w-[1700px] mx-auto flex flex-col md:flex-row md:items-center justify-between gap-2 min-w-0">
        <!-- Left: 24/7 Emergency Dispatch Hotlines -->
        <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar py-0.5 w-full md:w-auto min-w-0">
          <div class="flex items-center gap-1.5 shrink-0 pr-1 text-slate-900 font-extrabold text-[11px]">
            <PhoneCall class="w-3.5 h-3.5 text-red-600 shrink-0" />
            <span class="uppercase tracking-wider whitespace-nowrap">
              {{ langStore.currentLang === 'am' ? 'የ 24/7 ድንገተኛ ጥሪዎች' : (langStore.currentLang === 'om' ? 'Sarara Balaa (24/7)' : '24/7 Hotlines') }}:
            </span>
          </div>

          <!-- Loading Skeleton -->
          <div v-if="emergencyLoading && emergencyNumbers.length === 0" class="flex items-center gap-1.5 shrink-0">
            <div class="h-5 w-20 bg-slate-200 animate-pulse rounded-md"></div>
            <div class="h-5 w-24 bg-slate-200 animate-pulse rounded-md"></div>
            <div class="h-5 w-20 bg-slate-200 animate-pulse rounded-md"></div>
          </div>

          <div v-else class="flex items-center gap-1.5 shrink-0">
            <a 
              v-for="item in emergencyNumbers" 
              :key="item.id || item.phone_number || item.phone"
              :href="'tel:' + (item.phone_number || item.phone)"
              :class="[
                'inline-flex items-center gap-1 px-2 py-0.5 rounded-md border text-[10px] sm:text-[11px] font-bold transition-colors shrink-0 shadow-2xs',
                getEmergencyColor(item.badge_color || item.color)
              ]"
              :title="getEmergencyName(item) + ': ' + (item.phone_number || item.phone)"
            >
              <component :is="getEmergencyIcon(item.icon_name || item.icon)" class="w-3 h-3 shrink-0" />
              <span class="hidden sm:inline">{{ getEmergencyName(item) }}:</span>
              <span class="font-mono font-black">{{ item.phone_number || item.phone }}</span>
            </a>
          </div>
        </div>

        <!-- Right: Social Media, Internal Board & Language Dropdown -->
        <div class="flex items-center justify-between md:justify-end gap-3 sm:gap-5 shrink-0 w-full md:w-auto pt-1 md:pt-0 border-t md:border-t-0 border-slate-100">
          <!-- Social Icons (Matching Old Government Website) -->
          <div class="flex items-center gap-2.5 sm:gap-3 text-slate-600">
            <!-- Facebook -->
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#0b3c5d] transition-colors" title="Facebook">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <!-- X (Twitter) -->
            <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#0b3c5d] transition-colors" title="X (Twitter)">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 24.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <!-- YouTube -->
            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:text-red-600 transition-colors" title="YouTube">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
            <!-- LinkedIn -->
            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="hover:text-[#0b3c5d] transition-colors" title="LinkedIn">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </a>
            <!-- Telegram -->
            <a href="https://telegram.org" target="_blank" rel="noopener noreferrer" class="hover:text-sky-500 transition-colors" title="Telegram">
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.969z"/></svg>
            </a>
          </div>

          <span class="text-slate-300">|</span>

          <!-- Internal Board Link (Government Staff Portal) -->
          <router-link to="/internal-board" class="font-semibold text-slate-700 hover:text-[#0b3c5d] transition-colors flex items-center gap-1 whitespace-nowrap">
            <span>{{ langStore.t('nav.internal_board') }}</span>
          </router-link>

          <span class="text-slate-300">|</span>

          <!-- Language Selector Dropdown -->
          <div class="relative lang-selector shrink-0">
            <button 
              @click.stop="langDropdownOpen = !langDropdownOpen" 
              class="flex items-center gap-1.5 font-bold text-slate-700 hover:text-[#0b3c5d] py-1 transition-colors cursor-pointer"
            >
              <span>{{ currentLanguageLabel }}</span>
              <ChevronDown class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': langDropdownOpen }" />
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-if="langDropdownOpen" 
              class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-slate-200 rounded-xl shadow-2xl py-1.5 z-50 text-xs font-semibold"
            >
              <button 
                v-for="l in languages" 
                :key="l.code"
                @click="switchLanguage(l.code)"
                :class="[
                  'w-full text-left px-3.5 py-2 font-medium transition-colors cursor-pointer flex items-center justify-between',
                  langStore.currentLang === l.code ? 'bg-blue-50 text-[#0b3c5d] font-bold' : 'text-slate-700 hover:bg-slate-50'
                ]"
              >
                <span>{{ l.label }}</span>
                <span v-if="langStore.currentLang === l.code" class="text-[#0b3c5d] font-bold">✓</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar: Official Municipal Deep Navy Blue (#0b3c5d / #0c4a6e) -->
    <div class="bg-[#0b3c5d] text-white">
      <div class="w-full max-w-[1700px] mx-auto px-3 sm:px-6 2xl:px-12 py-2 sm:py-2.5 2xl:py-3 flex items-center justify-between gap-2 lg:gap-4">
        <!-- Official Brand / Emblem -->
        <router-link to="/" class="flex items-center gap-2 sm:gap-2.5 2xl:gap-3.5 group min-w-0 flex-1 xl:flex-initial">
          <img 
            src="/assets/logo.png" 
            alt="Kombolcha City Official Seal" 
            class="w-8 h-8 sm:w-10 sm:h-10 2xl:w-12 2xl:h-12 object-contain rounded-full bg-white p-0.5 shadow-md group-hover:scale-105 transition-transform duration-200 shrink-0"
          />
          <div class="min-w-0 flex-1">
            <h1 class="text-[11px] xs:text-xs sm:text-base 2xl:text-lg font-black tracking-tight text-white leading-tight truncate">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር' : (langStore.currentLang === 'om' ? 'Bulchiinsa Magaalaa Kombolchaa' : 'Kombolcha City Administration') }}
            </h1>
            <p class="hidden sm:block text-[9px] sm:text-[10px] 2xl:text-[12px] text-sky-200 font-semibold tracking-wider uppercase truncate">
              {{ langStore.currentLang === 'am' ? 'Kombolcha City Administration' : (langStore.currentLang === 'om' ? 'Kombolcha City Administration' : 'የኮምቦልቻ ከተማ አስተዳደር') }}
            </p>
          </div>
        </router-link>

        <!-- Desktop Navigation Items (Exact structure from kombolchacity.gov.et) -->
        <nav class="hidden xl:flex items-center gap-0.5 2xl:gap-1 text-[11px] 2xl:text-[13px] font-semibold text-white min-w-0">
          <!-- Home -->
          <router-link 
            to="/" 
            class="px-2 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/' }"
          >
            {{ langStore.t('nav.home') }}
          </router-link>

          <!-- About Us Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'about'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/about" 
              class="px-2 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1 whitespace-nowrap"
              :class="{ 'bg-white/15': route.path.startsWith('/about') }"
            >
              <span>{{ langStore.t('nav.about') }}</span>
              <ChevronDown class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- About Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'about'" 
              class="absolute left-0 top-full pt-1 w-52 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/about?tab=about" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_city') }}
                </router-link>
                <router-link to="/about?tab=history" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_history') }}
                </router-link>
                <router-link to="/about?tab=vision" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_vision') }}
                </router-link>
                <router-link to="/about?tab=people" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_demographics') }}
                </router-link>
                <router-link to="/about?tab=geography" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_geography') }}
                </router-link>
                <router-link to="/about?tab=administration" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_administration') }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- Government Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'government'" 
            @mouseleave="activeDropdown = null"
          >
            <button 
              class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1 cursor-pointer whitespace-nowrap"
              :class="{ 'bg-white/15': route.path.startsWith('/government') }"
            >
              <span>{{ langStore.t('nav.government') }}</span>
              <ChevronDown class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </button>

            <!-- Government Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'government'" 
              class="absolute left-0 top-full pt-1 w-56 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/government/mayor" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.mayor') }}
                </router-link>
                <router-link to="/government/mayor?tab=cabinet" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.cabinet') }}
                </router-link>
                <router-link to="/about?tab=administration" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.about_administration') }}
                </router-link>
                <router-link to="/government/mayor?tab=council" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.council') }}
                </router-link>
                <router-link to="/government/sub-cities" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.sub_cities') }}
                </router-link>
                <router-link to="/government/offices" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.offices_directory') }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- News Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'news'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/media/news" 
              class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1 whitespace-nowrap"
              :class="{ 'bg-white/15': route.path.startsWith('/media') }"
            >
              <span>{{ langStore.t('nav.news') }}</span>
              <ChevronDown class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- News Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'news'" 
              class="absolute left-0 top-full pt-1 w-48 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/media/news" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.latest_news') }}
                </router-link>
                <router-link to="/media/news?filter=announcement" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.announcement') }}
                </router-link>
                <router-link to="/media/vacancies" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.vacancies') }}
                </router-link>
                <router-link to="/media/tenders" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.tenders') }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- Service -->
          <router-link 
            to="/services" 
            class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/services' }"
          >
            {{ langStore.t('nav.services') }}
          </router-link>

          <!-- Explore -->
          <router-link 
            to="/explore" 
            class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/explore' }"
          >
            {{ langStore.t('nav.explore') }}
          </router-link>

          <!-- Program & Project Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'projects'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/projects" 
              class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1 whitespace-nowrap"
              :class="{ 'bg-white/15': route.path === '/projects' }"
            >
              <span>{{ langStore.t('nav.projects') }}</span>
              <ChevronDown class="w-3 h-3 2xl:w-3.5 2xl:h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- Project Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'projects'" 
              class="absolute left-0 top-full pt-1 w-52 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/projects" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.mega_projects') }}
                </router-link>
                <router-link to="/projects" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.t('nav.urban_development') }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- Resources -->
          <router-link 
            to="/resources" 
            class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/resources' }"
          >
            {{ langStore.t('nav.resources') }}
          </router-link>

          <!-- Gallery -->
          <router-link 
            to="/gallery" 
            class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/gallery' }"
          >
            {{ langStore.t('nav.gallery') }}
          </router-link>

          <!-- Contact -->
          <router-link 
            to="/contact" 
            class="px-1.5 2xl:px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors whitespace-nowrap"
            :class="{ 'bg-white/15': route.path === '/contact' }"
          >
            {{ langStore.t('nav.contact') }}
          </router-link>
        </nav>

        <!-- Right Action Items (Search + Admin Portal + Mobile Toggle) -->
        <div class="flex items-center gap-1 sm:gap-2 shrink-0">
          <!-- Search Button -->
          <button 
            @click="searchModalOpen = true" 
            class="p-1.5 sm:p-2 rounded-full hover:bg-white/10 text-white transition-colors cursor-pointer shrink-0" 
            :title="langStore.t('nav.search_portal')"
          >
            <Search class="w-4 h-4" />
          </button>

          <!-- Login / Dashboard Button (Icon on small mobile, Full badge on sm+) -->
          <router-link 
            :to="isAuthenticated ? '/admin' : '/admin/login'" 
            class="inline-flex items-center gap-1.5 px-2 sm:px-2.5 2xl:px-3 py-1.5 text-xs font-bold text-white bg-white/15 hover:bg-white/25 border border-white/25 rounded-md transition-all shadow-2xs shrink-0"
            :title="isAuthenticated ? langStore.t('nav.dashboard') : langStore.t('nav.login')"
          >
            <LogIn class="w-3.5 h-3.5 shrink-0" />
            <span class="hidden sm:inline">{{ isAuthenticated ? langStore.t('nav.dashboard') : langStore.t('nav.login') }}</span>
          </router-link>

          <!-- Mobile Hamburger Toggle - Always visible and prominent on mobile -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="xl:hidden p-1.5 sm:p-2 rounded-lg bg-white/10 hover:bg-white/20 border border-white/20 text-white cursor-pointer shrink-0 ml-0.5"
            aria-label="Toggle Navigation Menu"
            title="Toggle Menu"
          >
            <X v-if="mobileMenuOpen" class="w-5 h-5 sm:w-6 sm:h-6" />
            <Menu v-else class="w-5 h-5 sm:w-6 sm:h-6" />
          </button>
        </div>
      </div>
    </div>

    <!-- Search Modal -->
    <div 
      v-if="searchModalOpen" 
      class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-start justify-center pt-24 px-4"
      @click.self="searchModalOpen = false"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-xl p-5 border border-slate-200 animate-fadeIn">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <h3 class="text-sm font-bold text-slate-800">{{ langStore.t('nav.search_portal') }}</h3>
          <button @click="searchModalOpen = false" class="text-slate-400 hover:text-slate-600">
            <X class="w-5 h-5" />
          </button>
        </div>
        <form @submit.prevent="handleSearch" class="mt-4 flex gap-2">
          <div class="relative flex-1">
            <Search class="absolute left-3 top-3 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.t('nav.search_input_placeholder')" 
              class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#0b3c5d]"
              autofocus
            />
          </div>
          <button 
            type="submit" 
            class="px-5 py-2 bg-[#0b3c5d] hover:bg-[#0c4a6e] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
          >
            {{ langStore.currentLang === 'am' ? 'ፈልግ' : (langStore.currentLang === 'om' ? 'Barbaadi' : 'Search') }}
          </button>
        </form>
      </div>
    </div>

    <!-- Mobile Drawer (Responsive for Mobile and Tablet) -->
    <div 
      v-if="mobileMenuOpen" 
      class="xl:hidden bg-[#082d46] text-white px-4 py-4 space-y-1 border-t border-white/10 max-h-[80vh] overflow-y-auto text-sm"
    >
      <router-link @click="mobileMenuOpen = false" to="/" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.home') }}
      </router-link>

      <!-- About Us Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('about')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>{{ langStore.t('nav.about') }}</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.about }" />
        </button>
        <div v-show="mobileDropdownOpen.about" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/about?tab=about" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_city') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=history" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_history') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=vision" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_vision') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=people" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_demographics') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=geography" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_geography') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=administration" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_administration') }}</router-link>
        </div>
      </div>

      <!-- Government Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('government')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>{{ langStore.t('nav.government') }}</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.government }" />
        </button>
        <div v-show="mobileDropdownOpen.government" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/government/mayor" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.mayor') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/mayor?tab=cabinet" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.cabinet') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=administration" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.about_administration') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/mayor?tab=council" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.council') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/sub-cities" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.sub_cities') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/offices" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.offices_directory') }}</router-link>
        </div>
      </div>

      <!-- News Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('news')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>{{ langStore.t('nav.news') }}</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.news }" />
        </button>
        <div v-show="mobileDropdownOpen.news" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/media/news" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.latest_news') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/news?filter=announcement" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.announcement') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/vacancies" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.vacancies') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/tenders" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.t('nav.tenders') }}</router-link>
        </div>
      </div>

      <router-link @click="mobileMenuOpen = false" to="/services" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.services') }}
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/explore" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.explore') }}
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/projects" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.projects') }}
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/resources" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.resources') }}
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/gallery" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.gallery') }}
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/contact" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        {{ langStore.t('nav.contact') }}
      </router-link>

      <router-link 
        @click="mobileMenuOpen = false" 
        :to="isAuthenticated ? '/admin' : '/admin/login'" 
        class="flex items-center gap-2 px-3 py-2 rounded font-medium bg-blue-600/30 text-white mt-2 border border-blue-400/30"
      >
        <LogIn class="w-4 h-4" />
        <span>{{ isAuthenticated ? langStore.t('nav.dashboard') : langStore.t('nav.login') }}</span>
      </router-link>

      <!-- Mobile Language Selector -->
      <div class="pt-3 mt-3 pb-1 border-t border-white/10 flex items-center justify-between">
        <span class="text-xs text-sky-200 uppercase font-bold tracking-wider">Language:</span>
        <div class="flex items-center gap-1.5">
          <button 
            v-for="l in languages" 
            :key="l.code"
            @click="switchLanguage(l.code); mobileMenuOpen = false"
            :class="[
              'px-2.5 py-1 rounded text-xs font-bold transition-colors cursor-pointer',
              langStore.currentLang === l.code ? 'bg-blue-600 text-white' : 'bg-white/10 text-sky-100 hover:bg-white/20'
            ]"
          >
            {{ l.label }}
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-4px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.15s ease-out forwards;
}
</style>
