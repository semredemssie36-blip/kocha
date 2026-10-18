<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Building2, 
  MapPin, 
  FileText, 
  Clock, 
  ArrowRight, 
  ShieldCheck, 
  Award, 
  ChevronRight,
  ChevronLeft,
  TrendingUp,
  FileCheck,
  Download,
  Vote,
  CheckCircle2,
  PhoneCall,
  Landmark,
  Layers,
  HardHat
} from 'lucide-vue-next'

const langStore = useLanguageStore()

// Real Photographic Hero Slides
const currentSlide = ref(0)
let slideTimer = null
// Real Photographic Hero Slides (Exclusively fetched from municipal database)
const slides = ref([])

// Live Data States
const newsList = ref([])
const tendersList = ref([])
const pollData = ref(null)
const mayorData = ref({})
const selectedOption = ref(null)
const hasVoted = ref(false)
const voting = ref(false)

const getMayorName = () => {
  if (!mayorData.value) return 'Mayor of Kombolcha'
  const lang = langStore.currentLang
  if (lang === 'am') return mayorData.value.name_am || 'ክቡር አቶ ከድር አህመድ'
  if (lang === 'om') return mayorData.value.name_om || mayorData.value.name_en || 'Kabajamoo Obbo Kadiir Ahimad'
  return mayorData.value.name_en || 'Hon. Kedir Ahmed'
}

const getMayorKeynote = () => {
  if (!mayorData.value) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return mayorData.value.welcome_message_am || ''
  if (lang === 'om') return mayorData.value.welcome_message_om || mayorData.value.welcome_message_en || ''
  return mayorData.value.welcome_message_en || ''
}

const activeSlide = computed(() => slides.value[currentSlide.value] || slides.value[0])

const isValidText = (val) => val && typeof val === 'string' && !val.startsWith('???') && val.trim() !== ''

const getSlideTag = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.tag_am)) return activeSlide.value.tag_am
  if (lang === 'om' && isValidText(activeSlide.value?.tag_om)) return activeSlide.value.tag_om
  return activeSlide.value?.tag_en || activeSlide.value?.tag_am || 'Kombolcha City'
}

const getSlideTitle = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.title_am)) return activeSlide.value.title_am
  if (lang === 'om' && isValidText(activeSlide.value?.title_om)) return activeSlide.value.title_om
  return activeSlide.value?.title_en || activeSlide.value?.title_am || 'Kombolcha City Administration'
}

const getSlideDesc = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.desc_am)) return activeSlide.value.desc_am
  if (lang === 'om' && isValidText(activeSlide.value?.desc_om)) return activeSlide.value.desc_om
  return activeSlide.value?.desc_en || activeSlide.value?.desc_am || ''
}

const getSlideCta = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.cta_am)) return activeSlide.value.cta_am
  if (lang === 'om' && isValidText(activeSlide.value?.cta_om)) return activeSlide.value.cta_om
  return activeSlide.value?.cta_en || activeSlide.value?.cta_am || 'Explore'
}

const nextSlide = () => {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

const prevSlide = () => {
  if (slides.value.length === 0) return
  currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
}

const homeLoading = ref(true)

const fetchHomeData = async () => {
  homeLoading.value = true
  try {
    const res = await api.getHomeBundle()
    const bundle = res.data || {}
    newsList.value = bundle.news || []
    tendersList.value = bundle.tenders || []
    mayorData.value = bundle.mayor || {}
    if (bundle.poll && bundle.poll.poll) {
      pollData.value = bundle.poll
      hasVoted.value = Boolean(bundle.poll.has_voted || bundle.poll.is_expired || bundle.poll.is_closed)
    }
    if (bundle.hero_slides && bundle.hero_slides.length > 0) {
      slides.value = bundle.hero_slides.map(s => ({
        image: s.image_path,
        tag_am: s.tag_am || 'የኮምቦልቻ ከተማ አስተዳደር',
        tag_en: s.tag_en || 'Kombolcha City Administration',
        tag_om: s.tag_om || 'Bulchiinsa Magaalaa Kombolchaa',
        title_am: s.title_am,
        title_en: s.title_en || s.title_am,
        title_om: s.title_om || s.title_en || s.title_am,
        desc_am: s.subtitle_am || '',
        desc_en: s.subtitle_en || s.subtitle_am || '',
        desc_om: s.subtitle_om || s.subtitle_en || '',
        cta_am: s.button_text_am || 'ተጨማሪ ይመልከቱ',
        cta_en: s.button_text_en || 'Explore More',
        cta_om: s.button_text_om || 'Dabalataaf',
        cta_link: s.button_url || '/explore'
      }))
    } else {
      slides.value = []
    }

    // Preload hero photo before lifting skeleton to ensure zero flicker
    const firstImg = slides.value[0]?.image
    if (firstImg) {
      const img = new Image()
      img.src = firstImg
      img.onload = () => { homeLoading.value = false }
      img.onerror = () => { homeLoading.value = false }
      setTimeout(() => { homeLoading.value = false }, 800)
    } else {
      homeLoading.value = false
    }
  } catch (err) {
    console.error('Failed to load home portal bundle:', err)
    slides.value = []
    homeLoading.value = false
  }
}

const totalVotes = computed(() => {
  if (!pollData.value?.options) return 0
  return pollData.value.options.reduce((sum, opt) => sum + (opt.votes_count || 0), 0)
})

const getOptionPercentage = (votes) => {
  if (totalVotes.value === 0) return 0
  return Math.round((votes / totalVotes.value) * 100)
}

const submitVote = async () => {
  if (!selectedOption.value || !pollData.value?.poll) return
  voting.value = true
  try {
    const res = await api.votePoll(pollData.value.poll.id, selectedOption.value)
    if (res.data?.options) {
      pollData.value.options = res.data.options
    }
    hasVoted.value = true
  } catch (err) {
    console.error('Vote failed:', err)
  } finally {
    voting.value = false
  }
}

const getNewsTitle = (item) => {
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am
  if (lang === 'om') return item.title_om || item.title_en
  return item.title_en
}

const getTenderTitle = (item) => {
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am
  if (lang === 'om') return item.title_om || item.title_en
  return item.title_en
}

const getPollQuestion = () => {
  if (!pollData.value?.poll) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return pollData.value.poll.question_am
  if (lang === 'om') return pollData.value.poll.question_om || pollData.value.poll.question_en
  return pollData.value.poll.question_en
}

const getPollDescription = () => {
  if (!pollData.value?.poll) return ''
  const lang = langStore.currentLang
  if (lang === 'am' && pollData.value.poll.description_am) return pollData.value.poll.description_am
  if (lang === 'om' && pollData.value.poll.description_om) return pollData.value.poll.description_om
  if (pollData.value.poll.description_en) return pollData.value.poll.description_en
  return lang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር የ 2018/19 አመታዊ በጀት ድልድልን ለማቀድ የህዝቡን ድምጽ ያደምጣል።' : (lang === 'om' ? 'Sagalee keessan kennuudhaan misooma magaalaa keenyaa murteessaa.' : 'Cast your vote anonymously. Total verified votes shape municipal capital planning.')
}

const getOptionText = (opt) => {
  const lang = langStore.currentLang
  if (lang === 'am') return opt.option_text_am
  if (lang === 'om') return opt.option_text_om || opt.option_text_en
  return opt.option_text_en
}

onMounted(() => {
  fetchHomeData()
  slideTimer = setInterval(nextSlide, 7000)
})

onUnmounted(() => {
  if (slideTimer) clearInterval(slideTimer)
})
</script>

<template>
  <div class="bg-white text-slate-800">
    <!-- Full-Bleed Municipal Hero Banner Section (Edge-to-Edge Cover Photography Slider) -->
    <section class="relative w-full bg-slate-950 overflow-hidden min-h-[580px] sm:min-h-[640px] lg:min-h-[700px] xl:min-h-[760px] flex items-center">
      
      <!-- 1. Executive Dark Loading Skeleton (Active while fetching real database slides) -->
      <transition name="fade">
        <div 
          v-if="homeLoading || slides.length === 0" 
          class="absolute inset-0 w-full h-full bg-slate-950 flex items-center z-20 pointer-events-none"
        >
          <!-- Shimmering background pattern -->
          <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 animate-pulse"></div>
          <div class="absolute inset-0 bg-[radial-gradient(circle_at_25%_35%,rgba(30,58,138,0.25),transparent_70%)]"></div>

          <div class="relative z-10 w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-12 sm:py-20 lg:py-24">
            <div class="max-w-3xl space-y-6">
              <!-- Pill Tag Skeleton -->
              <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/60 animate-pulse">
                <div class="w-3.5 h-3.5 rounded-full bg-blue-500/40"></div>
                <div class="w-48 h-3.5 bg-slate-700/80 rounded"></div>
              </div>

              <!-- Big Headline Skeleton (2 lines) -->
              <div class="space-y-3">
                <div class="h-10 sm:h-14 lg:h-16 bg-slate-800/90 rounded-2xl w-full max-w-2xl animate-pulse"></div>
                <div class="h-10 sm:h-14 lg:h-16 bg-slate-800/90 rounded-2xl w-4/5 max-w-xl animate-pulse"></div>
              </div>

              <!-- Description Skeleton -->
              <div class="space-y-2 pt-2">
                <div class="h-4 sm:h-5 bg-slate-800/60 rounded-lg w-full max-w-xl animate-pulse"></div>
                <div class="h-4 sm:h-5 bg-slate-800/50 rounded-lg w-3/4 max-w-md animate-pulse"></div>
              </div>

              <!-- CTA Buttons Skeleton -->
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-4">
                <div class="h-12 w-48 bg-blue-900/40 rounded-xl animate-pulse border border-blue-800/40"></div>
                <div class="h-12 w-32 bg-slate-800/50 rounded-xl animate-pulse border border-slate-700/30"></div>
              </div>

              <!-- Slide Dots Skeleton -->
              <div class="flex items-center gap-2 pt-6">
                <div class="w-8 h-2 rounded-full bg-blue-600/60 animate-pulse"></div>
                <div class="w-2.5 h-2 rounded-full bg-slate-800 animate-pulse"></div>
                <div class="w-2.5 h-2 rounded-full bg-slate-800 animate-pulse"></div>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- 2. Real Database Photography Slides -->
      <div 
        v-for="(slide, index) in slides" 
        :key="index"
        class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out pointer-events-none"
        :class="currentSlide === index ? 'opacity-100 z-0' : 'opacity-0 -z-10'"
      >
        <img 
          :src="slide.image" 
          :alt="slide.title_en"
          class="w-full h-full object-cover object-center"
        />
        <!-- Dignified Gradient Scrim to guarantee high text contrast without being pure dark -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-transparent"></div>
      </div>

      <!-- 3. Real Database Slide Overlay Content -->
      <div v-if="slides.length > 0" class="relative z-10 w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-12 sm:py-20 lg:py-24 transition-opacity duration-500" :class="homeLoading ? 'opacity-0' : 'opacity-100'">
        <div class="max-w-3xl text-white">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600/40 text-blue-200 text-xs font-bold tracking-wider uppercase mb-4 border border-blue-400/30">
            <Landmark class="w-3.5 h-3.5 text-blue-300" />
            <span>{{ getSlideTag() }}</span>
          </div>

          <h1 class="text-2xl sm:text-4xl lg:text-5xl xl:text-6xl font-black tracking-tight leading-tight mb-4 drop-shadow-sm break-words">
            {{ getSlideTitle() }}
          </h1>

          <p class="text-sm sm:text-base lg:text-lg text-slate-200 leading-relaxed mb-6 sm:mb-8 max-w-2xl">
            {{ getSlideDesc() }}
          </p>

          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
            <router-link 
              :to="activeSlide?.cta_link || '/explore'" 
              class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-blue-700 hover:bg-blue-600 text-white font-extrabold text-sm transition-colors cursor-pointer shadow-md text-center"
            >
              <span>{{ getSlideCta() }}</span>
              <ArrowRight class="w-4 h-4" />
            </router-link>

            <router-link 
              to="/about" 
              class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3 sm:py-3.5 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-sm border border-white/30 backdrop-blur-sm transition-colors text-center"
            >
              {{ langStore.t('nav.about') }}
            </router-link>
          </div>

          <!-- Slide Controls -->
          <div v-if="slides.length > 1" class="flex items-center gap-3 pt-8">
            <button 
              @click="prevSlide" 
              class="p-2 rounded-full bg-white/20 hover:bg-white/30 text-white border border-white/30 transition-colors cursor-pointer"
              aria-label="Previous Slide"
            >
              <ChevronLeft class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2">
              <button 
                v-for="(s, idx) in slides" 
                :key="idx" 
                @click="currentSlide = idx"
                class="h-2 rounded-full transition-all cursor-pointer"
                :class="currentSlide === idx ? 'w-8 bg-blue-500' : 'w-2.5 bg-white/40 hover:bg-white/70'"
                :aria-label="`Slide ${idx + 1}`"
              ></button>
            </div>
            <button 
              @click="nextSlide" 
              class="p-2 rounded-full bg-white/20 hover:bg-white/30 text-white border border-white/30 transition-colors cursor-pointer"
              aria-label="Next Slide"
            >
              <ChevronRight class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Civic Gateways Grid (Executive Municipal Cards) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8 bg-white">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Sub-Cities -->
        <router-link 
          to="/government/sub-cities" 
          class="p-6 rounded-3xl bg-white border border-slate-200/90 hover:border-blue-500 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
          <div>
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <MapPin class="w-6 h-6" />
              </div>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                4 Sub-Cities
              </span>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-blue-700 transition-colors">
              {{ langStore.currentLang === 'am' ? 'ክፍለ ከተሞች' : (langStore.currentLang === 'om' ? 'Kutaalee Magaalaa' : 'Sub-Cities') }}
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
              Abishager, Borkena, Hasenager, Biraro
            </p>
          </div>
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-700 group-hover:translate-x-1 transition-transform">
            <span>{{ langStore.currentLang === 'am' ? 'ማውጫውን ይመልከቱ' : 'Explore Sub-Cities' }}</span>
            <ChevronRight class="w-4 h-4" />
          </div>
        </router-link>

        <!-- Sector Offices -->
        <router-link 
          to="/government/offices" 
          class="p-6 rounded-3xl bg-white border border-slate-200/90 hover:border-slate-500 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-600 to-slate-800"></div>
          <div>
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center border border-slate-200 group-hover:bg-slate-800 group-hover:text-white transition-colors">
                <Building2 class="w-6 h-6" />
              </div>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700 border border-slate-200">
                Directory
              </span>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-slate-800 transition-colors">
              {{ langStore.currentLang === 'am' ? 'የመምሪያዎች ማውጫ' : (langStore.currentLang === 'om' ? 'Galmee Waajjiraalee' : 'Sector Offices') }}
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
              Direct departmental phone lines & room directories
            </p>
          </div>
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-700 group-hover:translate-x-1 transition-transform">
            <span>{{ langStore.currentLang === 'am' ? 'ቢሮዎችን ያግኙ' : 'Browse Offices' }}</span>
            <ChevronRight class="w-4 h-4" />
          </div>
        </router-link>

        <!-- Citizen Services -->
        <router-link 
          to="/services" 
          class="p-6 rounded-3xl bg-white border border-slate-200/90 hover:border-emerald-500 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 to-teal-600"></div>
          <div>
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <Layers class="w-6 h-6" />
              </div>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                Permits & IDs
              </span>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors">
              {{ langStore.currentLang === 'am' ? 'የዜጎች አገልግሎቶች' : (langStore.currentLang === 'om' ? 'Tajaajilaalee Lammiilee' : 'Citizen Services') }}
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
              Kebele IDs, business trade licenses & civil status
            </p>
          </div>
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-700 group-hover:translate-x-1 transition-transform">
            <span>{{ langStore.currentLang === 'am' ? 'አገልግሎቶችን ያግኙ' : 'Access Services' }}</span>
            <ChevronRight class="w-4 h-4" />
          </div>
        </router-link>

        <!-- Public Tenders -->
        <router-link 
          to="/media/tenders" 
          class="p-6 rounded-3xl bg-white border border-slate-200/90 hover:border-amber-500 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between relative overflow-hidden"
        >
          <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-500"></div>
          <div>
            <div class="flex items-center justify-between mb-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <FileCheck class="w-6 h-6" />
              </div>
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                Procurement
              </span>
            </div>
            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-amber-700 transition-colors">
              {{ langStore.currentLang === 'am' ? 'የመንግስት ጨረታዎች' : (langStore.currentLang === 'om' ? 'Caalbaasiiwwan Mootummaa' : 'Public Tenders') }}
            </h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
              Active civic procurement bidding notices & downloads
            </p>
          </div>
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-700 group-hover:translate-x-1 transition-transform">
            <span>{{ langStore.currentLang === 'am' ? 'ጨረታዎችን ይመልከቱ' : 'View Tender Notices' }}</span>
            <ChevronRight class="w-4 h-4" />
          </div>
        </router-link>
      </div>
    </section>

    <!-- Mayor's Civic Spotlight Section (Executive State Proclamation Style) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10 bg-white">
      <div class="bg-gradient-to-br from-white via-slate-50/60 to-blue-50/20 rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-sm relative overflow-hidden">
        
        <!-- Top Executive Emblem Header Bar -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200/80 mb-8 flex-wrap gap-2">
          <div class="flex items-center gap-2 text-[11px] font-extrabold text-slate-600 uppercase tracking-widest">
            <span class="w-2 h-2 rounded-full bg-blue-700"></span>
            <span>Kombolcha City Administration</span>
            <span class="text-slate-300">•</span>
            <span class="text-blue-800">Office of the Executive Mayor</span>
          </div>
          <span class="text-[11px] font-bold text-slate-400 font-mono">Official Mayoral Dispatch</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          
          <!-- Portrait with Executive Frame -->
          <div class="lg:col-span-4 flex flex-col items-center text-center">
            <div class="w-48 h-48 rounded-3xl overflow-hidden p-1.5 bg-gradient-to-tr from-blue-700 via-indigo-500 to-amber-500 shadow-lg mb-4 relative flex items-center justify-center">
              <div class="w-full h-full rounded-[20px] overflow-hidden bg-slate-100 relative">
                <!-- Shimmer Skeleton while loading -->
                <div v-if="homeLoading && !mayorData.name_en" class="w-full h-full bg-slate-200 animate-pulse"></div>
                <img 
                  v-else-if="mayorData.portrait_path"
                  :src="mayorData.portrait_path" 
                  :alt="getMayorName()" 
                  class="w-full h-full object-cover object-top"
                />
                <div v-else class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                  <Building2 class="w-12 h-12 opacity-30 text-blue-700" />
                </div>
              </div>
            </div>

            <!-- Mayor Name / Title Skeleton or Content -->
            <div v-if="homeLoading && !mayorData.name_en" class="space-y-2 w-36">
              <div class="h-4 bg-slate-200 rounded animate-pulse mx-auto w-3/4"></div>
              <div class="h-3 bg-slate-200 rounded animate-pulse mx-auto w-1/2"></div>
            </div>
            <template v-else>
              <h3 class="text-xl font-black text-slate-900 tracking-tight">
                {{ getMayorName() }}
              </h3>
              <p class="text-xs font-extrabold text-blue-700 uppercase tracking-widest mt-1">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa Magaalaa Kombolchaa' : 'Mayor of Kombolcha City') }}
              </p>
              <p v-if="mayorData.term_start" class="text-[11px] text-slate-400 font-mono mt-1 font-semibold">
                Term: {{ mayorData.term_start }} - {{ mayorData.term_end || 'Present' }}
              </p>
            </template>
          </div>

          <!-- Keynote Message -->
          <div class="lg:col-span-8 space-y-5">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-800 text-xs font-extrabold border border-blue-200 shadow-2xs">
              <Award class="w-4 h-4 text-blue-700" />
              <span>{{ langStore.currentLang === 'am' ? 'የከንቲባው የክብር መልእክት' : "Mayor's Keynote Message" }}</span>
            </div>

            <!-- Loading Keynote Skeleton -->
            <div v-if="homeLoading && !mayorData.name_en" class="space-y-3 py-2 border-l-4 border-slate-200 pl-5">
              <div class="h-4 bg-slate-200 rounded animate-pulse w-full"></div>
              <div class="h-4 bg-slate-200 rounded animate-pulse w-5/6"></div>
              <div class="h-4 bg-slate-200 rounded animate-pulse w-3/4"></div>
            </div>

            <!-- Real Database Keynote Quote -->
            <blockquote v-else class="text-lg sm:text-xl font-medium text-slate-800 italic leading-relaxed border-l-4 border-blue-700 pl-6 py-2 relative">
              <span class="text-5xl font-serif text-blue-200 absolute -top-5 -left-1 select-none pointer-events-none">“</span>
              <p class="relative z-10">{{ getMayorKeynote() }}</p>
            </blockquote>

            <div class="pt-2 flex items-center gap-4 flex-wrap">
              <router-link 
                to="/government/mayor" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm"
              >
                <span>{{ langStore.currentLang === 'am' ? 'የከንቲባውን ሙሉ ገጽና ካቢኔ ይመልከቱ' : 'Read Full Mayoral Profile & Cabinet' }}</span>
                <ChevronRight class="w-4 h-4" />
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News & Announcements (Clean White Background) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10 bg-white">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
          <div class="inline-flex items-center gap-1.5 text-blue-700 font-bold text-xs uppercase tracking-wider mb-1">
            <TrendingUp class="w-4 h-4" />
            Media & Communication
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            {{ langStore.t('nav.news') }}
          </h2>
        </div>
        <router-link to="/media/news" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 uppercase tracking-wider">
          {{ langStore.t('common.view_all') }}
          <ChevronRight class="w-4 h-4" />
        </router-link>
      </div>

      <!-- Loading News Skeletons -->
      <div v-if="homeLoading && newsList.length === 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="i in 3" :key="i" class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between">
          <div>
            <div class="h-44 bg-slate-200 animate-pulse"></div>
            <div class="p-6 space-y-3">
              <div class="h-3 w-24 bg-slate-200 animate-pulse rounded"></div>
              <div class="h-5 w-full bg-slate-200 animate-pulse rounded"></div>
              <div class="h-3.5 w-4/5 bg-slate-200 animate-pulse rounded"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- News Cards Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <article 
          v-for="news in newsList" 
          :key="news.id" 
          @click="$router.push(`/media/news/${news.slug || news.id}`)"
          class="bg-white rounded-2xl border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all overflow-hidden flex flex-col justify-between group cursor-pointer"
        >
          <div>
            <div class="h-44 bg-slate-100 relative overflow-hidden">
              <img 
                :src="news.thumbnail_path || '/assets/hero_industry.jpg'" 
                alt="News article"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-md bg-blue-700 text-white text-[11px] font-bold uppercase tracking-wider shadow-sm">
                {{ news.category || 'General' }}
              </span>
            </div>

            <div class="p-6">
              <div class="flex items-center gap-1.5 text-xs text-slate-400 mb-2 font-mono">
                <Clock class="w-3.5 h-3.5" />
                <span>{{ new Date(news.published_at || news.created_at).toLocaleDateString() }}</span>
              </div>
              <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-700 transition-colors leading-snug mb-2">
                {{ getNewsTitle(news) }}
              </h3>
              <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                {{ langStore.currentLang === 'am' ? news.summary_am : news.summary_en }}
              </p>
            </div>
          </div>

          <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
            <router-link :to="`/media/news/${news.slug || news.id}`" class="text-xs font-bold text-blue-700 group-hover:underline flex items-center gap-1">
              {{ langStore.t('common.read_more') }}
              <ArrowRight class="w-3.5 h-3.5" />
            </router-link>
          </div>
        </article>
      </div>
    </section>

    <!-- Active Civic Poll Section (Clean White Card on White Background) -->
    <section v-if="pollData && pollData.poll" class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10 bg-white">
      <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-sm">
        <div class="max-w-3xl">
          <div class="flex flex-wrap items-center gap-2 mb-3">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-200">
              <Vote class="w-3.5 h-3.5" />
              {{ langStore.currentLang === 'am' ? 'የህዝብ አስተያየት መስጫ (Civic Poll)' : (langStore.currentLang === 'om' ? 'Gaaffii Yaada Lammiilee' : 'Citizen Priority Poll') }}
            </div>
            <span v-if="pollData.is_expired || pollData.is_closed" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold border border-amber-200">
              <Clock class="w-3 h-3" />
              {{ langStore.currentLang === 'am' ? 'ተጠናቋል (የመጨረሻ ውጤት)' : 'Concluded (Final Results)' }}
            </span>
            <span v-else class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
              {{ langStore.currentLang === 'am' ? 'ንቁ መስጫ' : 'Active Balloting' }}
            </span>
          </div>

          <h3 class="text-xl sm:text-2xl font-black text-slate-900 mb-2 leading-snug">
            {{ getPollQuestion() }}
          </h3>

          <p class="text-xs text-slate-500 mb-6">
            {{ getPollDescription() }}
          </p>

          <!-- Vote Options / Results -->
          <div class="space-y-3 mb-6">
            <div 
              v-for="opt in pollData.options" 
              :key="opt.id"
              class="relative rounded-xl overflow-hidden border p-4 transition-colors"
              :class="[
                hasVoted 
                  ? 'bg-slate-50 border-slate-200' 
                  : 'bg-white hover:bg-slate-50 border-slate-200 cursor-pointer',
                selectedOption === opt.id && !hasVoted ? 'border-blue-600 bg-blue-50/50' : ''
              ]"
              @click="!hasVoted && (selectedOption = opt.id)"
            >
              <!-- Progress fill if already voted or expired -->
              <div 
                v-if="hasVoted" 
                class="absolute left-0 top-0 bottom-0 bg-blue-100 transition-all duration-500"
                :style="{ width: `${getOptionPercentage(opt.votes_count)}%` }"
              ></div>

              <div class="relative z-10 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                  <input 
                    v-if="!hasVoted" 
                    type="radio" 
                    :name="'poll_' + pollData.poll.id" 
                    :value="opt.id" 
                    v-model="selectedOption"
                    class="w-4 h-4 text-blue-600 focus:ring-0 cursor-pointer"
                  />
                  <CheckCircle2 v-else-if="selectedOption === opt.id" class="w-4 h-4 text-blue-700 shrink-0" />
                  <span class="text-xs sm:text-sm font-semibold text-slate-800">
                    {{ getOptionText(opt) }}
                  </span>
                </div>

                <div v-if="hasVoted" class="text-xs font-mono font-bold text-blue-800 shrink-0">
                  {{ getOptionPercentage(opt.votes_count) }}% ({{ opt.votes_count }} {{ langStore.currentLang === 'am' ? 'ድምፅ' : (langStore.currentLang === 'om' ? 'sagalee' : 'votes') }})
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div v-if="pollData.is_expired || pollData.is_closed" class="text-xs text-amber-800 font-bold flex items-center gap-1.5">
              <Clock class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ይህ የህዝብ አስተያየት መስጫ ቀን አልፎበታል (ተጠናቋል)። የመጨረሻው የተረጋገጠ ውጤት ከላይ ቀርቧል።' : 'This civic poll has concluded. Final verified citizen voting results are shown.' }}</span>
            </div>
            <button 
              v-else-if="!hasVoted" 
              @click="submitVote"
              :disabled="!selectedOption || voting"
              class="px-6 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-600 text-white text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer disabled:opacity-50 self-start sm:self-auto"
            >
              {{ voting ? (langStore.currentLang === 'am' ? 'ድምፅዎ እየተመዘገበ ነው...' : 'Recording...') : (langStore.currentLang === 'am' ? 'ድምጽ ይስጡ' : 'Cast Vote') }}
            </button>
            <div v-else class="text-xs text-emerald-700 font-bold flex items-center gap-1.5">
              <CheckCircle2 class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ድምጽዎ ተመዝግቧል። እናመሰግናለን!' : 'Your vote has been verified and counted.' }}</span>
            </div>

            <span class="text-xs text-slate-500 font-mono">
              {{ totalVotes }} {{ langStore.currentLang === 'am' ? 'አጠቃላይ ድምጾች' : (langStore.currentLang === 'om' ? 'Sagaleewwan Waliigalaa' : 'Total Verified Ballots') }}
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- Active Public Tenders Section (Clean White Cards on White Background) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10 bg-white border-t border-slate-100">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            {{ langStore.t('nav.tenders') }}
          </h2>
          <p class="text-xs text-slate-500 mt-1">Official Municipal Procurement Notices & Bidding Documents</p>
        </div>
        <router-link to="/media/tenders" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 hover:text-blue-900 uppercase tracking-wider">
          {{ langStore.t('common.view_all') }}
          <ChevronRight class="w-4 h-4" />
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          v-for="tender in tendersList" 
          :key="tender.id" 
          class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-blue-400 transition-colors flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center justify-between gap-2 mb-3">
              <span class="px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-mono font-bold">
                {{ tender.reference_number }}
              </span>
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                Open Bid
              </span>
            </div>

            <h4 class="text-base font-extrabold text-slate-900 leading-snug mb-4">
              {{ getTenderTitle(tender) }}
            </h4>
          </div>

          <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <div class="text-xs">
              <span class="text-slate-400 block">{{ langStore.t('tenders.closing_in') }}:</span>
              <span class="font-bold text-amber-700 text-sm font-mono">
                {{ tender.closing_datetime }}
              </span>
            </div>

            <router-link 
              to="/media/tenders" 
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-700 hover:text-white text-xs font-bold transition-colors"
            >
              <Download class="w-3.5 h-3.5" />
              {{ langStore.t('common.download_pdf') }}
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
