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
  HardHat,
  Compass,
  Sparkles,
  ExternalLink,
  ShieldAlert,
  Users,
  Factory,
  Container,
  Zap
} from 'lucide-vue-next'

const langStore = useLanguageStore()

// Real Photographic Hero Slides
const currentSlide = ref(0)
let slideTimer = null
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
  if (lang === 'am') return mayorData.value.name_am || 'ክቡር አቶ መሀመድ አሚን የሱፍ'
  if (lang === 'om') return mayorData.value.name_om || mayorData.value.name_en || 'Kabajamoo Obbo Mahaammad-Amiin Yusuuf'
  return mayorData.value.name_en || 'Hon. Mohammed Amin Yesuf'
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
  return activeSlide.value?.tag_en || activeSlide.value?.tag_am || 'Kombolcha City Administration'
}

const getSlideTitle = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.title_am)) return activeSlide.value.title_am
  if (lang === 'om' && isValidText(activeSlide.value?.title_om)) return activeSlide.value.title_om
  return activeSlide.value?.title_en || activeSlide.value?.title_am || 'The Industrial & Logistics Gateway of Ethiopia'
}

const getSlideDesc = () => {
  const lang = langStore.currentLang
  let text = ''
  if (lang === 'am' && isValidText(activeSlide.value?.desc_am)) text = activeSlide.value.desc_am
  else if (lang === 'om' && isValidText(activeSlide.value?.desc_om)) text = activeSlide.value.desc_om
  else text = activeSlide.value?.desc_en || activeSlide.value?.desc_am || 'Connecting Northern Ethiopia through manufacturing, dry-port customs, railway logistics, and modern public governance.'
  return text.replace(/([a-zA-Z])\?s\b/g, "$1's")
}

const getSlideCta = () => {
  const lang = langStore.currentLang
  if (lang === 'am' && isValidText(activeSlide.value?.cta_am)) return activeSlide.value.cta_am
  if (lang === 'om' && isValidText(activeSlide.value?.cta_om)) return activeSlide.value.cta_om
  return activeSlide.value?.cta_en || activeSlide.value?.cta_am || 'Explore Services'
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
        cta_link: s.button_url || '/services'
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
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am || item.title_en || item.title_om || ''
  if (lang === 'om') return item.title_om || item.title_en || item.title_am || ''
  return item.title_en || item.title_am || item.title_om || ''
}

const getTenderTitle = (item) => {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am || item.title_en || item.title_om || ''
  if (lang === 'om') return item.title_om || item.title_en || item.title_am || ''
  return item.title_en || item.title_am || item.title_om || ''
}

const isTenderClosed = (tender) => {
  if (!tender) return true
  if (tender.status === 'closed') return true
  if (!tender.closing_datetime) return false
  return new Date(tender.closing_datetime).getTime() <= Date.now()
}

const formatTenderCountdown = (closingDatetime) => {
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
  return lang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር የህዝቡን ድምጽ በማድመጥ የልማት ቅድሚያዎችን ያዘጋጃል።' : (lang === 'om' ? 'Sagalee keessan kennuudhaan misooma magaalaa keenyaa murteessaa.' : 'Cast your vote anonymously. Total verified votes shape municipal capital planning.')
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
  <div class="bg-slate-50 text-slate-900 font-sans">
    
    <!-- SECTION 1: Sovereign Full-Bleed Municipal Hero Section -->
    <section class="relative w-full bg-[#050D1A] overflow-hidden min-h-[620px] sm:min-h-[680px] lg:min-h-[750px] xl:min-h-[820px] flex items-center">
      
      <!-- Shimmer Loading Skeleton while fetching database slides -->
      <transition name="fade">
        <div 
          v-if="homeLoading || slides.length === 0" 
          class="absolute inset-0 w-full h-full bg-[#050D1A] flex items-center z-20 pointer-events-none"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 animate-pulse"></div>
          <div class="relative z-10 w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-16">
            <div class="max-w-3xl space-y-6">
              <div class="h-8 w-64 bg-slate-800 rounded-full animate-pulse"></div>
              <div class="space-y-3">
                <div class="h-14 bg-slate-800 rounded-2xl w-full max-w-2xl animate-pulse"></div>
                <div class="h-14 bg-slate-800 rounded-2xl w-3/4 max-w-xl animate-pulse"></div>
              </div>
              <div class="h-5 bg-slate-800/60 rounded-lg w-full max-w-xl animate-pulse"></div>
              <div class="flex gap-4 pt-4">
                <div class="h-12 w-48 bg-amber-400/20 rounded-xl animate-pulse"></div>
                <div class="h-12 w-36 bg-slate-800/50 rounded-xl animate-pulse"></div>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- Real Database Photography Slides -->
      <div 
        v-for="(slide, index) in slides" 
        :key="index"
        class="absolute inset-0 w-full h-full transition-opacity duration-1000 ease-in-out pointer-events-none"
        :class="currentSlide === index ? 'opacity-100 z-0' : 'opacity-0 -z-10'"
      >
        <img 
          :src="slide.image" 
          :alt="slide.title_en"
          class="w-full h-full object-cover object-center transform scale-105 transition-transform duration-10000"
        />
        <!-- Subtle scrim: keeps background imagery bright & clear while ensuring text legibility -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/45 via-slate-950/20 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#050D1A]/80 via-transparent to-black/25"></div>
      </div>

      <!-- Real Database Slide Overlay Content -->
      <div 
        v-if="slides.length > 0" 
        class="relative z-10 w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-16 sm:py-24 lg:py-28 transition-opacity duration-500" 
        :class="homeLoading ? 'opacity-0' : 'opacity-100'"
      >
        <div class="max-w-3xl text-white">
          <!-- Majestic Headline -->
          <h1 class="text-3xl sm:text-5xl lg:text-6xl xl:text-7xl font-black tracking-tight leading-[1.1] mb-6 drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)] break-words">
            <span class="bg-gradient-to-r from-white via-slate-100 to-amber-200 bg-clip-text text-transparent">
              {{ getSlideTitle() }}
            </span>
          </h1>

          <!-- Authoritative Subtitle -->
          <p class="text-sm sm:text-base lg:text-lg text-white/95 leading-relaxed mb-8 max-w-2xl font-semibold drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)]">
            {{ getSlideDesc() }}
          </p>

          <!-- Executive Call-to-Actions -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <router-link 
              :to="activeSlide?.cta_link || '/services'" 
              class="inline-flex items-center justify-center gap-2.5 px-7 py-4 rounded-xl bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 hover:from-amber-300 hover:to-amber-500 text-slate-950 font-black text-sm tracking-wide transition-all shadow-xl hover:shadow-amber-500/25 hover:scale-105 cursor-pointer text-center"
            >
              <Sparkles class="w-4 h-4 text-slate-950" />
              <span>{{ getSlideCta() }}</span>
              <ArrowRight class="w-4 h-4 text-slate-950" />
            </router-link>

            <router-link 
              to="/explore" 
              class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/25 backdrop-blur-md transition-all hover:scale-105 text-center"
            >
              <Factory class="w-4 h-4 text-amber-400" />
              <span>{{ langStore.currentLang === 'am' ? 'የኢንዱስትሪና የንግድ ኢንቨስትመንት' : 'Industrial & Investment Hub' }}</span>
            </router-link>

            <router-link 
              to="/government/mayor" 
              class="inline-flex items-center justify-center gap-2 px-5 py-4 rounded-xl bg-slate-900/70 hover:bg-slate-900 text-slate-300 hover:text-white font-bold text-sm border border-slate-700/80 backdrop-blur-md transition-all text-center"
            >
              <Award class="w-4 h-4 text-amber-400" />
              <span>{{ langStore.currentLang === 'am' ? 'የከንቲባ ጽ/ቤት' : "Mayor's Chamber" }}</span>
            </router-link>
          </div>

          <!-- Slide Controls with Pill Progress -->
          <div v-if="slides.length > 1" class="flex items-center gap-4 pt-10">
            <button 
              @click="prevSlide" 
              class="p-2.5 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white border border-slate-700 transition-colors cursor-pointer"
              aria-label="Previous Slide"
            >
              <ChevronLeft class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2.5">
              <button 
                v-for="(s, idx) in slides" 
                :key="idx" 
                @click="currentSlide = idx"
                class="h-2 rounded-full transition-all cursor-pointer"
                :class="currentSlide === idx ? 'w-10 bg-amber-400 shadow-md shadow-amber-400/50' : 'w-3 bg-white/30 hover:bg-white/60'"
                :aria-label="`Slide ${idx + 1}`"
              ></button>
            </div>
            <button 
              @click="nextSlide" 
              class="p-2.5 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white border border-slate-700 transition-colors cursor-pointer"
              aria-label="Next Slide"
            >
              <ChevronRight class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 2: Elevated Citizen Action Dock (Floating Glassmorphism over Hero Seam) -->
    <section class="relative z-20 w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 -mt-10 sm:-mt-14 lg:-mt-16">
      <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-4 sm:p-6 shadow-2xl border border-slate-200/90">
        
        <!-- Action Dock Header Bar -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 mb-4 border-b border-slate-100 gap-2">
          <div class="flex items-center gap-2">
            <h2 class="text-xs sm:text-sm font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
              <Layers class="w-4 h-4 text-blue-700" />
              <span>{{ langStore.currentLang === 'am' ? 'የዜጎች ፈጣን አገልግሎቶች' : 'Citizen Quick Services' }}</span>
            </h2>
          </div>
          <span class="text-[11px] font-bold text-slate-500 font-mono">{{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር' : 'City Administration Services' }}</span>
        </div>

        <!-- 5-Column High-Prestige Gateways Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
          
          <!-- Gateway 1: Kebele ID & Civil Status -->
          <router-link 
            to="/services" 
            class="group p-4 rounded-2xl bg-gradient-to-br from-blue-50/50 to-white border border-slate-200/80 hover:border-blue-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-3 shadow-md group-hover:scale-110 transition-transform">
                <ShieldCheck class="w-5 h-5" />
              </div>
              <h3 class="text-sm font-black text-slate-900 group-hover:text-blue-700 transition-colors">
                {{ langStore.currentLang === 'am' ? 'የነዋሪነትና መታወቂያ' : 'Kebele ID & Civil Status' }}
              </h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ langStore.currentLang === 'am' ? 'የነዋሪነት መታወቂያ፣ የልደትና የጋብቻ ሰነዶች' : 'Resident IDs, birth certificates & civil records' }}
              </p>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-700">
              <span>{{ langStore.currentLang === 'am' ? 'አገልግሎት ያግኙ' : 'Apply Now' }}</span>
              <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </div>
          </router-link>

          <!-- Gateway 2: Public Tenders & Procurement -->
          <router-link 
            to="/media/tenders" 
            class="group p-4 rounded-2xl bg-gradient-to-br from-amber-50/50 to-white border border-slate-200/80 hover:border-amber-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center mb-3 shadow-md group-hover:scale-110 transition-transform">
                <FileCheck class="w-5 h-5" />
              </div>
              <div class="flex items-center gap-1.5 mb-1">
                <h3 class="text-sm font-black text-slate-900 group-hover:text-amber-700 transition-colors">
                  {{ langStore.currentLang === 'am' ? 'የከተማ ጨረታዎች' : 'Public Tenders' }}
                </h3>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-800 uppercase">Live</span>
              </div>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ langStore.currentLang === 'am' ? 'የመንግስት ግዥዎች፣ የጨረታ ሰነዶችና ማስታወቂያዎች' : 'Active municipal RFPs, bid bonds & documents' }}
              </p>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-700">
              <span>{{ langStore.currentLang === 'am' ? 'ጨረታዎችን ይመልከቱ' : 'View Notices' }}</span>
              <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </div>
          </router-link>

          <!-- Gateway 3: Sector Bureaus (22 Offices) -->
          <router-link 
            to="/government/offices" 
            class="group p-4 rounded-2xl bg-gradient-to-br from-emerald-50/50 to-white border border-slate-200/80 hover:border-emerald-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-3 shadow-md group-hover:scale-110 transition-transform">
                <Building2 class="w-5 h-5" />
              </div>
              <h3 class="text-sm font-black text-slate-900 group-hover:text-emerald-700 transition-colors">
                {{ langStore.currentLang === 'am' ? '30 የመምሪያ ቢሮዎች' : '30 Sector Bureaus' }}
              </h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ langStore.currentLang === 'am' ? 'የቢሮዎች ስልክ፣ የክፍል ቁጥርና ኃላፊዎች ማውጫ' : 'Direct departmental hotlines & room directories' }}
              </p>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-700">
              <span>{{ langStore.currentLang === 'am' ? 'ማውጫውን ክፈት' : 'View Directory' }}</span>
              <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </div>
          </router-link>

          <!-- Gateway 4: 4 Metropolitan Sub-Cities -->
          <router-link 
            to="/government/sub-cities" 
            class="group p-4 rounded-2xl bg-gradient-to-br from-purple-50/50 to-white border border-slate-200/80 hover:border-purple-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center mb-3 shadow-md group-hover:scale-110 transition-transform">
                <MapPin class="w-5 h-5" />
              </div>
              <h3 class="text-sm font-black text-slate-900 group-hover:text-purple-700 transition-colors">
                {{ langStore.currentLang === 'am' ? '4 ክፍለ ከተሞች' : '4 Sub-Cities' }}
              </h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Abishager, Borkena, Hasenager, Biraro
              </p>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-purple-700">
              <span>{{ langStore.currentLang === 'am' ? 'ዝርዝር ይመልከቱ' : 'Sub-City Hubs' }}</span>
              <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </div>
          </router-link>

          <!-- Gateway 5: Vacancies & Careers -->
          <router-link 
            to="/media/vacancies" 
            class="group p-4 rounded-2xl bg-gradient-to-br from-rose-50/50 to-white border border-slate-200/80 hover:border-rose-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center mb-3 shadow-md group-hover:scale-110 transition-transform">
                <Award class="w-5 h-5" />
              </div>
              <h3 class="text-sm font-black text-slate-900 group-hover:text-rose-700 transition-colors">
                {{ langStore.currentLang === 'am' ? 'የስራ ቅጥር ማስታወቂያ' : 'Job Vacancies' }}
              </h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ langStore.currentLang === 'am' ? 'የመንግስት የስራ ዕድሎችና የቅጥር ማስታወቂያዎች' : 'Civil service career openings & exam schedules' }}
              </p>
            </div>
            <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-rose-700">
              <span>{{ langStore.currentLang === 'am' ? 'ክፍት ስራዎች' : 'Explore Jobs' }}</span>
              <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </div>
          </router-link>
        </div>
      </div>
    </section>

    <!-- SECTION 3: National Industrial Powerhouse & Metropolitan Fact Counters -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-12">
      <div class="bg-gradient-to-r from-slate-900 via-[#07172B] to-slate-900 rounded-3xl p-8 sm:p-10 text-white shadow-xl border border-slate-800 relative overflow-hidden">
        
        <!-- Subtle Background Motif -->
        <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-600/10 to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 divide-y lg:divide-y-0 lg:divide-x divide-slate-800">
          
          <!-- Stat 1: Population Served -->
          <div class="pt-4 lg:pt-0 lg:px-6 first:px-0">
            <div class="flex items-center gap-2 text-amber-400 text-xs font-black uppercase tracking-wider mb-2">
              <Users class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'የህዝብ ብዛት' : 'Metropolitan Scale' }}</span>
            </div>
            <div class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono tracking-tight text-white mb-1">
              220,000<span class="text-amber-400">+</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed">
              {{ langStore.currentLang === 'am' ? 'በ 4 ክፍለ ከተሞችና በ 12 ቀበሌዎች የሚኖሩ ዜጎች' : 'Permanent residents across 4 Sub-Cities & 12 Kebeles' }}
            </p>
          </div>

          <!-- Stat 2: Manufacturing & Industry -->
          <div class="pt-4 lg:pt-0 lg:px-6">
            <div class="flex items-center gap-2 text-sky-400 text-xs font-black uppercase tracking-wider mb-2">
              <Factory class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ኢንዱስትሪና ፋብሪካዎች' : 'Industrial Output' }}</span>
            </div>
            <div class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono tracking-tight text-white mb-1">
              45<span class="text-sky-400">+</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed">
              {{ langStore.currentLang === 'am' ? 'በኮምቦልቻ ኢንዱስትሪ ፓርክና በከተማው የሚንቀሳቀሱ ፋብሪካዎች' : 'Active manufacturing plants inside Kombolcha Industrial Park' }}
            </p>
          </div>

          <!-- Stat 3: Dry Port Logistics -->
          <div class="pt-4 lg:pt-0 lg:px-6">
            <div class="flex items-center gap-2 text-emerald-400 text-xs font-black uppercase tracking-wider mb-2">
              <Container class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ደረቅ ወደብና ጉምሩክ' : 'Dry Port Logistics' }}</span>
            </div>
            <div class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono tracking-tight text-white mb-1">
              10,000<span class="text-emerald-400">+</span>
            </div>
            <p class="text-xs text-slate-400 leading-relaxed">
              {{ langStore.currentLang === 'am' ? 'TEU አመታዊ የእቃ መያዣና የኮንቴይነር የማስተናገድ አቅም' : 'TEU annual containerized freight customs clearance' }}
            </p>
          </div>

          <!-- Stat 4: Rapid Municipal Hotlines -->
          <div class="pt-4 lg:pt-0 lg:px-6">
            <div class="flex items-center gap-2 text-rose-400 text-xs font-black uppercase tracking-wider mb-2">
              <PhoneCall class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'የ 24/7 ፈጣን ጥሪዎች' : '24/7 Citizen Protection' }}</span>
            </div>
            <div class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono tracking-tight text-white mb-1">
              24<span class="text-rose-400">/</span>7
            </div>
            <p class="text-xs text-slate-400 leading-relaxed">
              {{ langStore.currentLang === 'am' ? 'ቀን ከሌሊት ዝግጁ የሆነ የፖሊስ፣ የእሳት አደጋና የህክምና እርዳታ' : 'Uninterrupted municipal emergency and civil defense response' }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 4: Executive State Proclamation (Mayor's Official Keynote Chamber) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-6">
      <div class="bg-gradient-to-br from-white via-slate-50 to-amber-50/20 rounded-3xl p-8 sm:p-12 border border-slate-200/90 shadow-lg relative overflow-hidden">
        
        <!-- Solid Prestige Top Border -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#1C398E]"></div>

        <!-- Mayor Keynote Header Bar -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200/80 mb-8 flex-wrap gap-2">
          <div class="flex items-center gap-2.5 text-xs font-black text-slate-700 uppercase tracking-widest">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-700"></span>
            <span>Kombolcha Metropolitan Administration</span>
            <span class="text-slate-300">•</span>
            <span class="text-blue-900">Office of the Executive Mayor</span>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          
          <!-- Authentic Mayoral Portrait in State Frame -->
          <div class="lg:col-span-4 flex flex-col items-center text-center">
            <div class="w-52 h-52 sm:w-60 sm:h-60 rounded-3xl overflow-hidden p-2 bg-[#1C398E] shadow-2xl mb-4 relative flex items-center justify-center">
              <div class="w-full h-full rounded-[20px] overflow-hidden bg-slate-100 relative">
                <div v-if="homeLoading && !mayorData.name_en" class="w-full h-full bg-slate-200 animate-pulse"></div>
                <img 
                  v-else-if="mayorData.portrait_path"
                  :src="mayorData.portrait_path" 
                  :alt="getMayorName()" 
                  class="w-full h-full object-cover object-top"
                />
                <div v-else class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400">
                  <Building2 class="w-16 h-16 opacity-30 text-blue-700" />
                </div>
              </div>
            </div>

            <!-- Mayor Name & Dignified Title -->
            <div v-if="homeLoading && !mayorData.name_en" class="space-y-2 w-44">
              <div class="h-5 bg-slate-200 rounded animate-pulse mx-auto w-3/4"></div>
              <div class="h-4 bg-slate-200 rounded animate-pulse mx-auto w-1/2"></div>
            </div>
            <template v-else>
              <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                {{ getMayorName() }}
              </h3>
              <p class="text-xs font-black text-amber-700 uppercase tracking-widest mt-1">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ከንቲባ' : (langStore.currentLang === 'om' ? 'Kantiibaa Magaalaa Kombolchaa' : 'Executive Mayor of Kombolcha') }}
              </p>
              <p v-if="mayorData.term_start" class="text-xs text-slate-400 font-mono mt-1 font-semibold">
                Administration Term: {{ mayorData.term_start }} - {{ mayorData.term_end || 'Present' }}
              </p>
            </template>
          </div>

          <!-- Mayoral Keynote Dispatch -->
          <div class="lg:col-span-8 space-y-6">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 text-blue-900 text-xs font-black border border-blue-200 shadow-xs">
              <Award class="w-4 h-4 text-blue-700" />
              <span>{{ langStore.currentLang === 'am' ? 'የከንቲባው የክብር መልእክት' : "Mayor's Keynote Message to the Citizens" }}</span>
            </div>

            <!-- Loading Keynote Skeleton -->
            <div v-if="homeLoading && !mayorData.name_en" class="space-y-3 py-2 border-l-4 border-slate-200 pl-6">
              <div class="h-5 bg-slate-200 rounded animate-pulse w-full"></div>
              <div class="h-5 bg-slate-200 rounded animate-pulse w-5/6"></div>
              <div class="h-5 bg-slate-200 rounded animate-pulse w-3/4"></div>
            </div>

            <!-- Real Database Keynote Quote -->
            <blockquote v-else class="text-lg sm:text-xl font-medium text-slate-800 italic leading-relaxed border-l-4 border-amber-500 pl-6 py-2 relative">
              <span class="text-6xl font-serif text-amber-200 absolute -top-7 -left-1 select-none pointer-events-none">“</span>
              <p class="relative z-10">{{ getMayorKeynote() }}</p>
            </blockquote>

            <!-- Mayoral Cabinet & Executive Assembly Buttons -->
            <div class="pt-2 flex items-center gap-4 flex-wrap">
              <router-link 
                to="/government/mayor" 
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition-all shadow-md hover:scale-105"
              >
                <span>{{ langStore.currentLang === 'am' ? 'የከንቲባውን ሙሉ ገጽ ይመልከቱ' : "Read Mayor's Biography & Vision" }}</span>
                <ChevronRight class="w-4 h-4 text-amber-400" />
              </router-link>

              <router-link 
                to="/government/mayor?tab=cabinet" 
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black uppercase tracking-wider transition-colors"
              >
                <ShieldCheck class="w-4 h-4 text-slate-600" />
                <span>{{ langStore.t('nav.cabinet') }}</span>
              </router-link>

              <router-link 
                to="/government/mayor?tab=council" 
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-black uppercase tracking-wider transition-colors"
              >
                <Landmark class="w-4 h-4 text-slate-600" />
                <span>{{ langStore.t('nav.council') }}</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 5: Strategic Megaprojects & Economic Pillars Spotlight -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
          <div class="inline-flex items-center gap-2 text-blue-700 font-black text-xs uppercase tracking-widest mb-1">
            <HardHat class="w-4 h-4" />
            <span>Economic Engine & Urban Transformation</span>
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            {{ langStore.currentLang === 'am' ? 'ስትራቴጂካዊ የኢንዱስትሪና የመሰረተ ልማት ፕሮጀክቶች' : 'Strategic Industrial & Infrastructure Corridors' }}
          </h2>
        </div>
        <router-link to="/projects" class="inline-flex items-center gap-1.5 text-xs font-black text-blue-700 hover:text-blue-900 uppercase tracking-wider">
          {{ langStore.t('common.view_all') }}
          <ChevronRight class="w-4 h-4" />
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pillar 1: Kombolcha Industrial Park (KIP) -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
          <div class="h-48 relative overflow-hidden bg-slate-900">
            <img 
              src="/assets/industry_park.jpg" 
              alt="Kombolcha Industrial Park" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider shadow-md">
              Manufacturing Hub
            </span>
          </div>
          <div class="p-6">
            <h3 class="text-base font-black text-slate-900 group-hover:text-blue-700 transition-colors leading-snug mb-2">
              {{ langStore.currentLang === 'am' ? 'ኮምቦልቻ ኢንዱስትሪ ፓርክ' : 'Kombolcha Industrial Park (KIP)' }}
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
              {{ langStore.currentLang === 'am' ? 'ለአለም አቀፍ የጨርቃ ጨርቅ፣ የቆዳና የማኑፋክቸሪንግ ምርቶች የተዘጋጀ ዘመናዊ የኢንዱስትሪ ማዕከል' : 'State-of-the-art export manufacturing zone with specialized zero-liquid-discharge infrastructure.' }}
            </p>
            <router-link to="/explore" class="text-xs font-bold text-blue-700 group-hover:underline flex items-center gap-1">
              <span>{{ langStore.currentLang === 'am' ? 'የኢንቨስትመንት መረጃ' : 'Explore Investment' }}</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </router-link>
          </div>
        </div>

        <!-- Pillar 2: Dry Port & Multimodal Logistics -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
          <div class="h-48 relative overflow-hidden bg-slate-900">
            <img 
              src="/assets/city_image.jpg" 
              alt="Dry Port & Logistics" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider shadow-md">
              Logistics Terminal
            </span>
          </div>
          <div class="p-6">
            <h3 class="text-base font-black text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug mb-2">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ደረቅ ወደብና ጉምሩክ' : 'Dry Port Customs & Freight Hub' }}
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
              {{ langStore.currentLang === 'am' ? 'ከጅቡቲ ወደብ ጋር በቀጥታ የተገናኘ የሰሜን ኢትዮጵያ ቀዳሚ የገቢና ወጪ ንግድ መናኸሪያ' : 'Multimodal customs cargo terminal linking the Ethio-Djibouti rail and highway corridors.' }}
            </p>
            <router-link to="/explore" class="text-xs font-bold text-emerald-700 group-hover:underline flex items-center gap-1">
              <span>{{ langStore.currentLang === 'am' ? 'የሎጂስቲክስ አቅም' : 'Logistics Profile' }}</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </router-link>
          </div>
        </div>

        <!-- Pillar 3: Borkena River Eco-Boulevard & Green City -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
          <div class="h-48 relative overflow-hidden bg-slate-900 flex items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-900 to-slate-900"></div>
            <Sparkles class="w-16 h-16 text-teal-400 opacity-50 relative z-10" />
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-teal-600 text-white text-[10px] font-black uppercase tracking-wider shadow-md">
              Urban Green Corridor
            </span>
          </div>
          <div class="p-6">
            <h3 class="text-base font-black text-slate-900 group-hover:text-teal-700 transition-colors leading-snug mb-2">
              {{ langStore.currentLang === 'am' ? 'የቦርከና ወንዝ ዳርቻ ልማት' : 'Borkena Riverfront Green Park' }}
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
              {{ langStore.currentLang === 'am' ? 'የአረንጓዴ ልማት፣ የህዝብ መዝናኛ ስፍራዎችና የወንዝ ዳርቻ ጥበቃ ታላቅ ፕሮጀክት' : 'Metropolitan eco-revitalization with urban cycling walkways, flood control, and public parks.' }}
            </p>
            <router-link to="/projects" class="text-xs font-bold text-teal-700 group-hover:underline flex items-center gap-1">
              <span>{{ langStore.currentLang === 'am' ? 'የፕሮጀክት ሂደት' : 'View Masterplan' }}</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </router-link>
          </div>
        </div>

        <!-- Pillar 4: Digital Smart City Governance -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group flex flex-col justify-between">
          <div class="h-48 relative overflow-hidden bg-slate-900 flex items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-slate-900"></div>
            <ShieldCheck class="w-16 h-16 text-indigo-400 opacity-50 relative z-10" />
            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-indigo-600 text-white text-[10px] font-black uppercase tracking-wider shadow-md">
              Smart Governance
            </span>
          </div>
          <div class="p-6">
            <h3 class="text-base font-black text-slate-900 group-hover:text-indigo-700 transition-colors leading-snug mb-2">
              {{ langStore.currentLang === 'am' ? 'ዘመናዊ ዲጂታል የከተማ አስተዳደር' : 'E-Governance & Digital City Hall' }}
            </h3>
            <p class="text-xs text-slate-500 leading-relaxed mb-4">
              {{ langStore.currentLang === 'am' ? 'ቀልጣፋና ከብልሹ አሰራር የጸዳ የኤሌክትሮኒክስ የህዝብ አገልግሎት አሰጣጥ ሥርዓት' : 'Automated municipal service delivery, computerized land registry, and citizen audit trail.' }}
            </p>
            <router-link to="/services" class="text-xs font-bold text-indigo-700 group-hover:underline flex items-center gap-1">
              <span>{{ langStore.currentLang === 'am' ? 'ዲጂታል አገልግሎቶች' : 'Access E-Services' }}</span>
              <ArrowRight class="w-3.5 h-3.5" />
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 6: Municipal Press Dispatches & Gazette (Latest News) -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
          <div class="inline-flex items-center gap-2 text-blue-700 font-black text-xs uppercase tracking-widest mb-1">
            <TrendingUp class="w-4 h-4" />
            <span>Official Dispatches & Municipal Gazette</span>
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            {{ langStore.t('nav.news') }}
          </h2>
        </div>
        <router-link to="/media/news" class="inline-flex items-center gap-1.5 text-xs font-black text-blue-700 hover:text-blue-900 uppercase tracking-wider">
          {{ langStore.t('common.view_all') }}
          <ChevronRight class="w-4 h-4" />
        </router-link>
      </div>

      <!-- Loading News Skeletons -->
      <div v-if="homeLoading && newsList.length === 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="i in 3" :key="i" class="bg-white rounded-3xl border border-slate-200 overflow-hidden flex flex-col justify-between">
          <div class="h-48 bg-slate-200 animate-pulse"></div>
          <div class="p-6 space-y-3">
            <div class="h-4 w-28 bg-slate-200 animate-pulse rounded"></div>
            <div class="h-6 w-full bg-slate-200 animate-pulse rounded"></div>
            <div class="h-4 w-4/5 bg-slate-200 animate-pulse rounded"></div>
          </div>
        </div>
      </div>

      <!-- Real Database News Cards Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <article 
          v-for="news in newsList" 
          :key="news.id" 
          @click="$router.push(`/media/news/${news.slug || news.id}`)"
          class="bg-white rounded-3xl border border-slate-200 hover:border-blue-500 hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group cursor-pointer"
        >
          <div>
            <div class="h-52 bg-slate-100 relative overflow-hidden">
              <img 
                :src="news.thumbnail_path || '/assets/industry.jpg'" 
                alt="News article"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-blue-900 text-white text-[11px] font-black uppercase tracking-wider shadow-md">
                {{ news.category || 'Gazette' }}
              </span>
            </div>

            <div class="p-6">
              <div class="flex items-center gap-2 text-xs text-slate-400 mb-2.5 font-mono">
                <Clock class="w-3.5 h-3.5 text-slate-400" />
                <span>{{ new Date(news.published_at || news.created_at).toLocaleDateString() }}</span>
              </div>
              <h3 class="text-base font-black text-slate-900 group-hover:text-blue-700 transition-colors leading-snug mb-2">
                {{ getNewsTitle(news) }}
              </h3>
              <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                {{ langStore.currentLang === 'am' ? news.summary_am : news.summary_en }}
              </p>
            </div>
          </div>

          <div class="p-6 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between">
            <span class="text-xs font-bold text-blue-700 group-hover:underline flex items-center gap-1.5">
              <span>{{ langStore.t('common.read_more') }}</span>
              <ArrowRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
            </span>
          </div>
        </article>
      </div>
    </section>

    <!-- SECTION 7: Active Public Tenders & Procurement Chamber -->
    <section class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="bg-gradient-to-br from-white to-amber-50/20 rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-md">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
          <div>
            <div class="inline-flex items-center gap-2 text-amber-700 font-black text-xs uppercase tracking-widest mb-1">
              <FileCheck class="w-4 h-4" />
              <span>Official Municipal Procurement Chamber</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              {{ langStore.t('nav.tenders') }}
            </h2>
            <p class="text-xs text-slate-500 mt-1">Legally binding municipal procurement notices, bidding documents & RFP downloads</p>
          </div>
          <router-link to="/media/tenders" class="inline-flex items-center gap-1.5 text-xs font-black text-amber-700 hover:text-amber-900 uppercase tracking-wider">
            {{ langStore.t('common.view_all') }}
            <ChevronRight class="w-4 h-4" />
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div 
            v-for="tender in tendersList" 
            :key="tender.id" 
            class="bg-white rounded-2xl border border-slate-200 p-6 hover:border-amber-400 hover:shadow-lg transition-all duration-200 flex flex-col justify-between"
          >
            <div>
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-800 text-xs font-mono font-bold">
                  Ref: {{ tender.reference_number }}
                </span>
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black border uppercase tracking-wider',
                    !isTenderClosed(tender)
                      ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                      : 'bg-rose-50 text-rose-800 border-rose-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="!isTenderClosed(tender) ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                  {{ !isTenderClosed(tender) 
                    ? (langStore.currentLang === 'am' ? 'ክፍት ጨረታ' : (langStore.currentLang === 'om' ? 'Caalbaasii Bantamaa' : 'Open Bid'))
                    : (langStore.currentLang === 'am' ? 'የተዘጋ ጨረታ' : (langStore.currentLang === 'om' ? 'Caalbaasii Cufame' : 'Closed')) 
                  }}
                </span>
              </div>

              <h4 class="text-base font-black text-slate-900 leading-snug mb-4">
                {{ getTenderTitle(tender) }}
              </h4>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
              <div class="text-xs">
                <span class="text-slate-400 block mb-0.5">{{ langStore.t('tenders.closing_in') }}:</span>
                <span 
                  class="font-mono text-xs sm:text-sm font-black"
                  :class="isTenderClosed(tender) ? 'text-rose-600 font-extrabold' : 'text-amber-700'"
                >
                  {{ formatTenderCountdown(tender.closing_datetime).text }}
                </span>
              </div>

              <a 
                v-if="tender.document_pdf_path || tender.document_url || tender.document_path"
                :href="tender.document_pdf_path || tender.document_url || tender.document_path" 
                target="_blank"
                download
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-md hover:scale-105 transition-all cursor-pointer"
              >
                <Download class="w-4 h-4 text-amber-400" />
                <span>{{ langStore.currentLang === 'am' ? 'የጨረታ ሰነድ አውርድ' : (langStore.currentLang === 'om' ? 'SANADA CAALBAASII' : 'DOWNLOAD BID DOSSIER') }}</span>
              </a>
              <router-link 
                v-else
                to="/media/tenders" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white font-black text-xs uppercase tracking-wider shadow-md hover:scale-105 transition-all cursor-pointer"
              >
                <Download class="w-4 h-4 text-amber-400" />
                <span>{{ langStore.currentLang === 'am' ? 'የጨረታ ሰነድ አውርድ ' : (langStore.currentLang === 'om' ? 'SANADA CAALBAASII' : 'DOWNLOAD BID DOSSIER') }}</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 8: Active Civic Opinion Poll (Citizen Direct Democracy) -->
    <section v-if="pollData && pollData.poll" class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="bg-gradient-to-br from-white to-blue-50/30 rounded-3xl p-8 sm:p-10 border border-slate-200/90 shadow-md">
        <div class="max-w-3xl">
          <div class="flex flex-wrap items-center gap-2 mb-3">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-black uppercase tracking-wider border border-blue-200">
              <Vote class="w-3.5 h-3.5 text-blue-700" />
              {{ langStore.currentLang === 'am' ? 'የዜጎች ቀጥታ ድምፅ መስጫ' : (langStore.currentLang === 'om' ? 'Gaaffii Yaada Lammiilee' : 'Citizen Priority Ballot') }}
            </div>
            <span v-if="pollData.is_expired || pollData.is_closed" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold border border-amber-200">
              <Clock class="w-3 h-3" />
              {{ langStore.currentLang === 'am' ? 'ተጠናቋል (የመጨረሻ ውጤት)' : 'Concluded (Final Results)' }}
            </span>
            <span v-else class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-200">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
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
              class="relative rounded-2xl overflow-hidden border p-4 transition-all duration-200"
              :class="[
                hasVoted 
                  ? 'bg-slate-50 border-slate-200' 
                  : 'bg-white hover:bg-blue-50/40 border-slate-200 cursor-pointer shadow-xs',
                selectedOption === opt.id && !hasVoted ? 'border-blue-600 bg-blue-50/80 ring-2 ring-blue-500/20' : ''
              ]"
              @click="!hasVoted && (selectedOption = opt.id)"
            >
              <!-- Progress fill if already voted or expired -->
              <div 
                v-if="hasVoted" 
                class="absolute left-0 top-0 bottom-0 bg-blue-100/80 transition-all duration-700"
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
                  <span class="text-xs sm:text-sm font-bold text-slate-900">
                    {{ getOptionText(opt) }}
                  </span>
                </div>

                <div v-if="hasVoted" class="text-xs font-mono font-black text-blue-900 shrink-0">
                  {{ getOptionPercentage(opt.votes_count) }}% ({{ opt.votes_count }} {{ langStore.currentLang === 'am' ? 'ድምፅ' : (langStore.currentLang === 'om' ? 'sagalee' : 'votes') }})
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
            <div v-if="pollData.is_expired || pollData.is_closed" class="text-xs text-amber-800 font-bold flex items-center gap-1.5">
              <Clock class="w-4 h-4" />
              <span>{{ langStore.currentLang === 'am' ? 'ይህ የህዝብ አስተያየት መስጫ ቀን አልፎበታል (ተጠናቋል)። የመጨረሻው የተረጋገጠ ውጤት ከላይ ቀርቧል።' : 'This civic poll has concluded. Final verified citizen voting results are shown.' }}</span>
            </div>
            <button 
              v-else-if="!hasVoted" 
              @click="submitVote"
              :disabled="!selectedOption || voting"
              class="px-8 py-3 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-black uppercase tracking-wider transition-all cursor-pointer disabled:opacity-50 self-start sm:self-auto shadow-md hover:scale-105"
            >
              {{ voting ? (langStore.currentLang === 'am' ? 'ድምፅዎ እየተመዘገበ ነው...' : 'Recording Ballot...') : (langStore.currentLang === 'am' ? 'ድምጽ ይስጡ' : 'Cast Official Ballot') }}
            </button>
            <div v-else class="text-xs text-emerald-800 font-bold flex items-center gap-1.5">
              <CheckCircle2 class="w-4 h-4 text-emerald-600" />
              <span>{{ langStore.currentLang === 'am' ? 'ድምጽዎ ተመዝግቧል። እናመሰግናለን!' : 'Your vote has been verified and counted.' }}</span>
            </div>

            <span class="text-xs text-slate-500 font-mono font-bold">
              {{ totalVotes }} {{ langStore.currentLang === 'am' ? 'አጠቃላይ ድምጾች' : (langStore.currentLang === 'om' ? 'Sagaleewwan Waliigalaa' : 'Total Verified Ballots') }}
            </span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
