<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
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

const emergencyNumbers = [
  { name_en: 'Police', name_am: 'ፖሊስ', name_om: 'Poolisii', phone: '991', icon: ShieldAlert, color: 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100' },
  { name_en: 'Fire & Rescue', name_am: 'እሳት አደጋ', name_om: 'Balaa Ibiddii', phone: '939', icon: Flame, color: 'text-orange-700 bg-orange-50 border-orange-200 hover:bg-orange-100' },
  { name_en: 'Ambulance', name_am: 'አምቡላንስ', name_om: 'Ambulaansii', phone: '907', icon: Activity, color: 'text-rose-700 bg-rose-50 border-rose-200 hover:bg-rose-100' },
  { name_en: 'Water Leak', name_am: 'የውሃ ጥገና', name_om: 'Tajaajila Bishaanii', phone: '+251 33 551 0145', icon: Droplets, color: 'text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100' },
  { name_en: 'Power Grid', name_am: 'መብራት ኃይል', name_om: 'Tajaajila Ibsaa', phone: '905', icon: Zap, color: 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' },
]

const getEmergencyName = (item) => {
  const lang = langStore.currentLang
  if (lang === 'am') return item.name_am
  if (lang === 'om') return item.name_om
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

onMounted(() => {
  window.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <header class="sticky top-0 z-50 shadow-md">
    <!-- Top Utility Bar (White background, social icons, Internal Board, Language selector) -->
    <div class="bg-white border-b border-slate-200 py-1.5 px-4 sm:px-8 xl:px-12 text-xs text-slate-600">
      <div class="w-full max-w-[1700px] mx-auto flex flex-wrap items-center justify-between gap-3">
        <!-- Left: 24/7 Emergency Dispatch Hotlines -->
        <div class="flex items-center gap-2 overflow-x-auto scrollbar-none py-0.5 max-w-full">
          <div class="flex items-center gap-1.5 shrink-0 pr-1 text-slate-900 font-extrabold">
            <PhoneCall class="w-3.5 h-3.5 text-red-600 shrink-0" />
            <span class="uppercase tracking-wider text-[11px] whitespace-nowrap">
              {{ langStore.currentLang === 'am' ? 'የ 24/7 ድንገተኛ ጥሪዎች' : (langStore.currentLang === 'om' ? 'Sarara Balaa (24/7)' : '24/7 Hotlines') }}:
            </span>
          </div>

          <div class="flex items-center gap-1.5 shrink-0">
            <a 
              v-for="item in emergencyNumbers" 
              :key="item.phone"
              :href="'tel:' + item.phone"
              :class="[
                'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md border text-[11px] font-bold transition-colors shrink-0 shadow-2xs',
                item.color
              ]"
              :title="getEmergencyName(item) + ': ' + item.phone"
            >
              <component :is="item.icon" class="w-3 h-3 shrink-0" />
              <span class="hidden sm:inline">{{ getEmergencyName(item) }}:</span>
              <span class="font-mono font-black">{{ item.phone }}</span>
            </a>
          </div>
        </div>

        <!-- Right: Social Media, Internal Board & Language Dropdown -->
        <div class="flex items-center gap-4 sm:gap-6 ml-auto">
          <!-- Social Icons (Matching Old Government Website) -->
          <div class="flex items-center gap-3 text-slate-600">
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
          <router-link to="/internal-board" class="font-semibold text-slate-700 hover:text-[#0b3c5d] transition-colors flex items-center gap-1">
            <span>Internal Board</span>
          </router-link>

          <span class="text-slate-300">|</span>

          <!-- Language Selector Dropdown -->
          <div class="relative lang-selector">
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
              class="absolute right-0 top-full mt-1 w-36 bg-white border border-slate-200 rounded-lg shadow-xl py-1 z-50 text-xs"
            >
              <button 
                v-for="l in languages" 
                :key="l.code"
                @click="switchLanguage(l.code)"
                :class="[
                  'w-full text-left px-3 py-1.5 font-medium transition-colors cursor-pointer flex items-center justify-between',
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
      <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-3 flex items-center justify-between gap-4">
        <!-- Official Brand / Emblem -->
        <router-link to="/" class="flex items-center gap-3.5 group shrink-0">
          <img 
            src="/assets/city_logo.png" 
            alt="Kombolcha City Official Seal" 
            class="w-12 h-12 object-contain rounded-full bg-white p-0.5 shadow-md group-hover:scale-105 transition-transform duration-200"
          />
          <div>
            <h1 class="text-base sm:text-lg font-black tracking-tight text-white leading-tight">
              ኮምቦልቻ ከተማ አስተዳደር
            </h1>
            <p class="text-[12px] text-sky-200 font-semibold tracking-wider uppercase">
              Kombolcha City Administration
            </p>
          </div>
        </router-link>

        <!-- Desktop Navigation Items (Exact structure from kombolchacity.gov.et) -->
        <nav class="hidden xl:flex items-center gap-0.5 lg:gap-1 text-[13px] font-semibold text-white">
          <!-- Home -->
          <router-link 
            to="/" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/' }"
          >
            Home
          </router-link>

          <!-- About Us Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'about'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/about" 
              class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1"
              :class="{ 'bg-white/15': route.path.startsWith('/about') }"
            >
              <span>About Us</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- About Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'about'" 
              class="absolute left-0 top-full pt-1 w-52 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/about?tab=about" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  About
                </router-link>
                <router-link to="/about?tab=history" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  History
                </router-link>
                <router-link to="/about?tab=vision" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Vision and Mission
                </router-link>
                <router-link to="/about?tab=people" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  People & Culture
                </router-link>
                <router-link to="/about?tab=geography" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Geography & Climate
                </router-link>
                <router-link to="/about?tab=administration" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Administration
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
              class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1 cursor-pointer"
              :class="{ 'bg-white/15': route.path.startsWith('/government') }"
            >
              <span>Government</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </button>

            <!-- Government Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'government'" 
              class="absolute left-0 top-full pt-1 w-56 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/government/mayor" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa' : 'Mayor') }}
                </router-link>
                <router-link to="/government/mayor?tab=cabinet" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የካቢኔ መገለጫ' : (langStore.currentLang === 'om' ? 'Ibsa Kaabinee' : 'Cabinet Profile') }}
                </router-link>
                <router-link to="/about?tab=administration" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የከተማው መዋቅር' : (langStore.currentLang === 'om' ? 'Caasaa Magaalaa' : 'City Structure') }}
                </router-link>
                <router-link to="/government/mayor?tab=council" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የከተማው ምክር ቤት' : (langStore.currentLang === 'om' ? 'Manni Maree Magaalaa' : 'Council') }}
                </router-link>
                <router-link to="/government/sub-cities" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የክፍለ ከተሞች መረጃ' : (langStore.currentLang === 'om' ? 'Oodeeffannoo Kutaalee Magaalaa' : 'Sub City Information') }}
                </router-link>
                <router-link to="/government/offices" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የመምሪያዎች ማውጫ' : (langStore.currentLang === 'om' ? 'Galmee Waajjiraalee' : 'Offices Directory') }}
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
              class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1"
              :class="{ 'bg-white/15': route.path.startsWith('/media') }"
            >
              <span>News</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- News Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'news'" 
              class="absolute left-0 top-full pt-1 w-48 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/media/news" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Latest News
                </router-link>
                <router-link to="/media/news?filter=announcement" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Announcement
                </router-link>
                <router-link to="/media/vacancies" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Vacancy
                </router-link>
                <router-link to="/media/tenders" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Tender
                </router-link>
              </div>
            </div>
          </div>

          <!-- Service -->
          <router-link 
            to="/services" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/services' }"
          >
            Service
          </router-link>

          <!-- Explore -->
          <router-link 
            to="/explore" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/explore' }"
          >
            Explore
          </router-link>

          <!-- Program & Project Dropdown -->
          <div 
            class="relative group" 
            @mouseenter="activeDropdown = 'projects'" 
            @mouseleave="activeDropdown = null"
          >
            <router-link 
              to="/projects" 
              class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors flex items-center gap-1"
              :class="{ 'bg-white/15': route.path === '/projects' }"
            >
              <span>Program & Project</span>
              <ChevronDown class="w-3.5 h-3.5 opacity-80 group-hover:rotate-180 transition-transform" />
            </router-link>

            <!-- Project Dropdown Menu -->
            <div 
              v-show="activeDropdown === 'projects'" 
              class="absolute left-0 top-full pt-1 w-52 z-50 animate-fadeIn"
            >
              <div class="bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 text-slate-800 text-xs">
                <router-link to="/projects" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Mega Infrastructure
                </router-link>
                <router-link to="/projects" class="block px-4 py-2 hover:bg-slate-100 hover:text-[#0b3c5d] font-medium transition-colors">
                  Urban Development
                </router-link>
              </div>
            </div>
          </div>

          <!-- Resources -->
          <router-link 
            to="/resources" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/resources' }"
          >
            Resources
          </router-link>

          <!-- Gallery -->
          <router-link 
            to="/gallery" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/gallery' }"
          >
            Gallery
          </router-link>

          <!-- Contact -->
          <router-link 
            to="/contact" 
            class="px-2.5 py-1.5 rounded hover:bg-white/10 transition-colors"
            :class="{ 'bg-white/15': route.path === '/contact' }"
          >
            Contact
          </router-link>
        </nav>

        <!-- Right Action Items (Search + Admin Portal + Mobile Toggle) -->
        <div class="flex items-center gap-2.5 shrink-0">
          <!-- Search Button -->
          <button 
            @click="searchModalOpen = true" 
            class="p-2 rounded-full hover:bg-white/10 text-white transition-colors cursor-pointer" 
            title="Search Portal"
          >
            <Search class="w-4 h-4" />
          </button>

          <!-- Login Button -->
          <router-link 
            :to="isAuthenticated ? '/admin' : '/admin/login'" 
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-white/15 hover:bg-white/25 border border-white/25 rounded-md transition-all shadow-2xs"
          >
            <LogIn class="w-3.5 h-3.5" />
            <span>{{ isAuthenticated ? 'Dashboard' : (langStore.currentLang === 'am' ? 'ግባ' : (langStore.currentLang === 'om' ? 'Seensa' : 'Login')) }}</span>
          </router-link>

          <!-- Mobile Hamburger Toggle -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen" 
            class="xl:hidden p-2 rounded-md hover:bg-white/10 text-white"
          >
            <X v-if="mobileMenuOpen" class="w-6 h-6" />
            <Menu v-else class="w-6 h-6" />
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
          <h3 class="text-sm font-bold text-slate-800">Search Kombolcha City Portal</h3>
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
              placeholder="Search news, directives, tenders, services..." 
              class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-[#0b3c5d]"
              autofocus
            />
          </div>
          <button 
            type="submit" 
            class="px-5 py-2 bg-[#0b3c5d] hover:bg-[#0c4a6e] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
          >
            Search
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
        Home
      </router-link>

      <!-- About Us Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('about')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>About Us</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.about }" />
        </button>
        <div v-show="mobileDropdownOpen.about" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/about?tab=about" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">About</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=history" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">History</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=vision" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Vision and Mission</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=people" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">People & Culture</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=geography" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Geography & Climate</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=administration" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Administration</router-link>
        </div>
      </div>

      <!-- Government Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('government')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>Government</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.government }" />
        </button>
        <div v-show="mobileDropdownOpen.government" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/government/mayor" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa' : 'Mayor') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/mayor?tab=cabinet" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'የካቢኔ መገለጫ' : (langStore.currentLang === 'om' ? 'Ibsa Kaabinee' : 'Cabinet Profile') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/about?tab=administration" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'የከተማው መዋቅር' : (langStore.currentLang === 'om' ? 'Caasaa Magaalaa' : 'City Structure') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/mayor?tab=council" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'የከተማው ምክር ቤት' : (langStore.currentLang === 'om' ? 'Manni Maree Magaalaa' : 'Council') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/sub-cities" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'የክፍለ ከተሞች መረጃ' : (langStore.currentLang === 'om' ? 'Oodeeffannoo Kutaalee Magaalaa' : 'Sub City Information') }}</router-link>
          <router-link @click="mobileMenuOpen = false" to="/government/offices" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">{{ langStore.currentLang === 'am' ? 'የመምሪያዎች ማውጫ' : (langStore.currentLang === 'om' ? 'Galmee Waajjiraalee' : 'Offices Directory') }}</router-link>
        </div>
      </div>

      <!-- News Accordion -->
      <div>
        <button 
          @click="toggleMobileDropdown('news')" 
          class="w-full flex items-center justify-between px-3 py-2 rounded font-medium hover:bg-white/10 text-left"
        >
          <span>News</span>
          <ChevronDown class="w-4 h-4 transition-transform" :class="{ 'rotate-180': mobileDropdownOpen.news }" />
        </button>
        <div v-show="mobileDropdownOpen.news" class="pl-4 space-y-1 bg-black/10 py-1 rounded">
          <router-link @click="mobileMenuOpen = false" to="/media/news" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Latest News</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/news?filter=announcement" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Announcement</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/vacancies" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Vacancy</router-link>
          <router-link @click="mobileMenuOpen = false" to="/media/tenders" class="block px-3 py-1.5 text-xs text-sky-100 hover:text-white">Tender</router-link>
        </div>
      </div>

      <router-link @click="mobileMenuOpen = false" to="/services" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Service
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/explore" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Explore
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/projects" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Program & Project
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/resources" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Resources
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/gallery" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Gallery
      </router-link>

      <router-link @click="mobileMenuOpen = false" to="/contact" class="block px-3 py-2 rounded font-medium hover:bg-white/10">
        Contact
      </router-link>

      <router-link 
        @click="mobileMenuOpen = false" 
        :to="isAuthenticated ? '/admin' : '/admin/login'" 
        class="flex items-center gap-2 px-3 py-2 rounded font-medium bg-blue-600/30 text-white mt-2 border border-blue-400/30"
      >
        <LogIn class="w-4 h-4" />
        <span>{{ isAuthenticated ? 'Dashboard' : (langStore.currentLang === 'am' ? 'ግባ (Login)' : (langStore.currentLang === 'om' ? 'Seensa (Login)' : 'Login')) }}</span>
      </router-link>
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
