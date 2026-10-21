<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import { 
  Building2, 
  MapPin, 
  Compass, 
  TrendingUp, 
  ShieldCheck, 
  Users, 
  Leaf, 
  Landmark,
  ChevronRight,
  Calendar,
  CloudSun,
  Award,
  Layers,
  Sparkles,
  Mountain,
  HeartHandshake,
  Maximize2,
  X,
  Droplets,
  Thermometer,
  Globe,
  Waves,
  Navigation
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const route = useRoute()
const router = useRouter()
const showMapModal = ref(false)

const tabs = [
  { id: 'about', label_en: 'About Kombolcha', label_am: 'ስለ ከተማዋ', label_om: 'Waa\'ee Magaalaa' },
  { id: 'history', label_en: 'Historical Heritage', label_am: 'ታሪክና አመጣጥ', label_om: 'Seenaa' },
  { id: 'vision', label_en: 'Mission, Vision & Values', label_am: 'ተልዕኮ፣ ርዕይና እሴቶች', label_om: 'Ergama, Mul\'ata fi Duudhaalee' },
  { id: 'people', label_en: 'People & Culture', label_am: 'ህዝብና ባህል', label_om: 'Ummata fi Aadaa' },
  { id: 'geography', label_en: 'Geography & Climate', label_am: 'መልክዓ ምድርና አየር ንብረት', label_om: 'Teessuma Lafaa' },
  { id: 'administration', label_en: 'Governance Charter', label_am: 'የአስተዳደር መዋቅር', label_om: 'Caasaa Bulchiinsaa' }
]

const coreValues = [
  {
    title_am: 'የህግ የበላይነትን ማረጋገጥ',
    title_en: 'Ensuring the Rule of Law',
    title_om: 'Ol\'aantummaa Seeraa Mirkaneessuu'
  },
  {
    title_am: 'ቅንጅታዊ አሰራርን ማጎልበት',
    title_en: 'Fostering Collaborative Synergy',
    title_om: 'Hojii Qindoominaa Cimsuu'
  },
  {
    title_am: 'ፍትሀዊነትን አሰራርን ማስፈን',
    title_en: 'Institutionalizing Fairness & Equity',
    title_om: 'Sirna Haqaa Diriirsuu'
  },
  {
    title_am: 'አሳታፊነት',
    title_en: 'Inclusivity & Public Participation',
    title_om: 'Hirmaachisummaa'
  },
  {
    title_am: 'ተጠያቂነት',
    title_en: 'Transparency & Accountability',
    title_om: 'Itti-gaafatamummaa'
  },
  {
    title_am: 'የደሞክራሲ ስረአት ግንባታ',
    title_en: 'Building Robust Democratic Systems',
    title_om: 'Ijaarsa Sirna Dimokiraasii'
  },
  {
    title_am: 'ሌብነትን መታገል',
    title_en: 'Combating Corruption & Ensuring Integrity',
    title_om: 'Malaammaltummaa Qabsaa\'uu'
  }
]

const activeTab = ref('about')

function selectTab(id) {
  activeTab.value = id
  router.replace({ query: { ...route.query, tab: id } })
}

onMounted(() => {
  if (route.query.tab && tabs.some(t => t.id === route.query.tab)) {
    activeTab.value = route.query.tab
  }
})

watch(() => route.query.tab, (newTab) => {
  if (newTab && tabs.some(t => t.id === newTab)) {
    activeTab.value = newTab
  }
})

const currentTabObj = computed(() => {
  return tabs.find(t => t.id === activeTab.value) || tabs[0]
})

const getTabLabel = (t) => {
  if (langStore.currentLang === 'am') return t.label_am
  if (langStore.currentLang === 'om') return t.label_om
  return t.label_en
}

const getCoreValueTitle = (val) => {
  if (langStore.currentLang === 'am') return val.title_am
  if (langStore.currentLang === 'om') return val.title_om || val.title_en
  return val.title_en
}
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative  from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/city_image.jpg" alt="About Kombolcha" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'ስለ ኮምቦልቻ ከተማ አስተዳደር' : 'About Kombolcha Metropolitan City' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የከተማዋ አመሰራረት፣ ታሪክ፣ ራዕይና ተልዕኮ፣ የህዝብና ባህል አኗኗር፣ የመልክዓ ምድር ገጽታ እና የዘመናዊው አስተዳደር መዋቅር።'
            : 'Explore the founding history, geographic advantage, vision, cultural diversity, and public administration framework of Kombolcha.'
          }}
        </p>
      </div>
    </div>

    <!-- Main Content Container with Left Sidebar -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Sidebar Navigation & Fast Facts (3 cols) -->
        <aside class="lg:col-span-3 space-y-6">
          <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm sticky top-28">
            <div class="bg-slate-900 text-white px-5 py-4 border-b border-slate-800 font-black text-xs uppercase tracking-wider flex items-center justify-between">
              <span>{{ langStore.currentLang === 'am' ? 'የይዘት ማውጫ' : 'Civic Directory' }}</span>
              <Layers class="w-4 h-4 text-amber-400" />
            </div>
            <nav class="flex flex-col divide-y divide-slate-100 text-xs sm:text-sm font-bold">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="selectTab(tab.id)"
                :class="[
                  'w-full text-left px-5 py-4 transition-all flex items-center justify-between cursor-pointer',
                  activeTab === tab.id
                    ? 'bg-blue-900 text-amber-300 shadow-inner font-black'
                    : 'text-slate-700 hover:bg-slate-50 hover:text-blue-900'
                ]"
              >
                <span>{{ getTabLabel(tab) }}</span>
                <ChevronRight class="w-4 h-4 opacity-75" :class="activeTab === tab.id ? 'text-amber-400' : 'text-slate-400'" />
              </button>
            </nav>
          </div>

          <!-- Quick Fast Facts Widget -->
          <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm text-xs text-slate-600">
            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider mb-4 flex items-center gap-2 pb-3 border-b border-slate-100">
              <Landmark class="w-4 h-4 text-blue-700" />
              <span>{{ langStore.currentLang === 'am' ? 'አጫጭር የከተማዋ መረጃዎች' : 'Quick City Facts' }}</span>
            </h4>
            <ul class="space-y-3 font-medium">
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Foundation:</span>
                <span class="font-black text-slate-900">1935 E.C (Biraro)</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Status:</span>
                <span class="font-black text-blue-900">Regiopolitan City</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Total Area:</span>
                <span class="font-black text-slate-900 font-mono">124.5 km²</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Elevation:</span>
                <span class="font-black text-slate-900 font-mono">1,857 meters</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Administration:</span>
                <span class="font-black text-slate-900">4 Sub-Cities (20 Kebeles)</span>
              </li>
              <li class="flex justify-between">
                <span class="text-slate-400 font-bold">Distance from A.A:</span>
                <span class="font-black text-slate-900 font-mono">377 km North</span>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Right Main Panel Content (9 cols) -->
        <main class="lg:col-span-9 bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 shadow-sm">
          
          <!-- TAB 1: ABOUT (የከተማዋ አመሰራረትና የሪጆፖሊታን መዋቅር) -->
          <div v-if="activeTab === 'about'" class="space-y-8 animate-fadeIn">
            <!-- Header -->
            <div class="border-b border-slate-100 pb-4 mb-6">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-black uppercase tracking-wider mb-2">
                <Landmark class="w-3.5 h-3.5 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? '1935 ዓ.ም • የሪጆፖሊታን ከተማ ደረጃ (ከ2015 ዓ.ም)' : (langStore.currentLang === 'om' ? 'Bara 1935 A.L.I • Sadarkaa Rijiyoo-poolitaanii' : 'Chartered 1935 E.C. • Regiopolitan City Status (Since 2015 E.C.)') }}</span>
              </div>
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አመሰራረትና መዋቅር' : (langStore.currentLang === 'om' ? 'Hundeeffama Magaalaa Kombolchaa fi Caasaa Bulchiinsaa' : 'Kombolcha City Foundation & Metropolitan Governance') }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">
                {{ langStore.currentLang === 'am' 
                  ? 'ከቀደምት የቢራሮ ስያሜ እስከ ዘመናዊው የሪጆፖሊታን ከተማ አስተዳደር መዋቅር' 
                  : (langStore.currentLang === 'om' ? 'Seenaa Biraaroo irraa hanga caasaa bulchiinsa magaalaa ammayyaatti' : 'From the historic Biraro heritage to the modern decentralized Regiopolitan framework') 
                }}
              </p>
            </div>

            <!-- Official Municipal Foundation Narrative Banner -->
            <div class="p-6 sm:p-8 rounded-3xl bg-blue-50/70 border border-blue-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-700"></div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-900 text-amber-300 flex items-center justify-center shrink-0 shadow-sm">
                  <Landmark class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-blue-800 block">
                    {{ langStore.currentLang === 'am' ? 'ይፋዊ የከተማው አመሰራረት' : (langStore.currentLang === 'om' ? 'Hundeeffama Magaalaa' : 'Official Municipal Heritage') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-blue-950">
                    {{ langStore.currentLang === 'am' ? 'የከተማዋ አመሰራረት' : (langStore.currentLang === 'om' ? 'Hundeeffama Magaalaa' : 'Foundation & Evolution') }}
                  </h3>
                </div>
              </div>

              <p class="text-slate-800 text-sm sm:text-base leading-relaxed text-justify font-medium">
                {{ langStore.currentLang === 'am'
                  ? 'ከተማዋ በ1935 ዓ.ም በማዘጋጃ ቤት እዉቅና አግኝታ ከመከተሟ በፊት ቢራሮ በመባል ትታወቅ እንደነበር የአካባቢዉ የዕድሜ ባለፀጎች እማኝነታቸዉን ይሰጣሉ፡፡ የኮምቦልቻ ከተማ አስተዳደር ከ2015 ዓ.ም ጀምሮ የሪጆፖሊታን የከተማ ደረጃ አግኝታ በ6 የገጠር ቀበሌ እና በ4ክፍለ ከተማ ማለትም አቢሻገር፤ ቢራሮ፤ ሀሰን አገር፤ ቦርከና ክፍለ ከተማ ተከፍላ በአራቱ ክፍለ ከተማ ስር በ14 የከተማ ቀበሌዎች ማለትም ሁሩባ፤ ሸዋበር፤ ቀዲዳ፤ ራስአገዝ፤ ቦርቸሌ፤ ሚጢቆሎ፤ በርበሬወንዝ፤ ሁዳዱ፤ ሜጃ፤ ቱሉአዳሜ፤ ቁጠባ፤ ሽሻበር፤ የቀድሞዉ አየር ማረፊያ፤የጎፍ ቀበሌ ተከፋፍላ የተዋቀረች ከተማ ናት፡፡ ስድስቱ የገጠር ቀበሌዎች ሙጢ ግራር(06) ፤ ሁላበር(07)፤ ከተማ አገር(08)፤ ትዩ አምባ(09) ፤ መጠኔ(010)እና ጋሌሳ(011) ቀበሌ የሚል ስያሜ አላቸዉ፡፡'
                  : (langStore.currentLang === 'om'
                    ? 'Magaalaan Kombolchaa bara 1935 A.L.Itti beekamtii man-qopheessaa argattee osoo hin hundeeffamin dura \'Biraaroo\' jedhamtee beekamaa akka turte manguddoonni naannichaa dhugaa ba\'u. Bulchiinsi Magaalaa Kombolchaa bara 2015 A.L.I irraa eegalee sadarkaa magaalaa Rijiyoo-poolitaanii argachuudhaan, kutaalee magaalaa 4 (Abishaager, Biraaroo, Hasaannagar fi Borkanaa) fi gandoolee baadiyyaa 6tti qoodamteetti. Kutaalee magaalaa afran jalattis gandoolee magaalaa 14 fi gandoolee baadiyyaa 6 qabattee ijaaramteetti.'
                    : 'According to historical testimonies by local elders, before receiving municipal charter recognition in 1935 E.C. (1943 G.C.), the city was historically known as \'Biraro\'. Elevating its institutional capacity and urban growth, the Kombolcha City Administration officially attained the prestigious status of a Regiopolitan City in 2015 E.C. (2023 G.C.). The city is organized under 4 autonomous Sub-Cities—Abishager, Biraro, Hassen Ager, and Borkena—encompassing 14 Urban Kebeles (Huruba, Shewaber, Qedida, Ras Agez, Borchele, Mitikolo, Berberewenz, Hudadu, Meja, Tulu Adame, Kuteba, Shishaber, Old Airport, and Yegof) and 6 designated Rural Kebeles: Muti Girar (06), Hulaber (07), Ketema Ager (08), Tiyu Amba (09), Metene (010), and Galesa (011).'
                  )
                }}
              </p>

              <!-- Foundation Milestones 4-Cards Grid -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-blue-200/80">
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'የማዘጋጃ ቤት ምስረታ' : 'Municipal Charter' }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-blue-900 mt-1">1935 ዓ.ም</div>
                  <span class="text-[10px] text-amber-600 font-bold">ቀደምት ስያሜ "ቢራሮ"</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'የሪጆፖሊታን ደረጃ' : 'Regiopolitan City' }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-emerald-700 mt-1">2015 ዓ.ም</div>
                  <span class="text-[10px] text-slate-400 font-medium">Metropolitan Grade</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ክፍለ ከተሞች' : 'Sub-Cities' }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-blue-900 mt-1">4 Zones</div>
                  <span class="text-[10px] text-slate-400 font-medium">Autonomous Sectors</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ጠቅላላ ቀበሌዎች' : 'Total Kebeles' }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-purple-900 mt-1">20 Kebeles</div>
                  <span class="text-[10px] text-slate-400 font-medium">14 Urban • 6 Rural</span>
                </div>
              </div>
            </div>

            <!-- The 4 Sub-Cities Showcase -->
            <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white shadow-xl relative overflow-hidden">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'የከተማው አራቱ አስተዳደራዊ ክፍሎች' : 'Administrative Zones' }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'አራቱ ክፍለ ከተሞች (4 Sub-Cities)' : 'The 4 Autonomous Sub-Cities' }}
                  </h3>
                </div>
                <router-link 
                  to="/government/sub-cities" 
                  class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md transition-all self-start sm:self-auto"
                >
                  <span>{{ langStore.currentLang === 'am' ? 'ክፍለ ከተሞችን በዝርዝር ተመልከት' : 'Explore Sub-Cities' }}</span>
                  <ChevronRight class="w-4 h-4" />
                </router-link>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl bg-slate-800/90 border border-slate-700/80 hover:border-amber-400/60 transition-all">
                  <div class="w-9 h-9 rounded-xl bg-blue-900/60 text-blue-300 flex items-center justify-center font-black text-sm mb-3">01</div>
                  <h4 class="text-base font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'አቢሻገር ክፍለ ከተማ' : 'Abishager Sub-City' }}
                  </h4>
                  <p class="text-xs text-slate-400 mt-1 font-medium">
                    {{ langStore.currentLang === 'am' ? 'የከተማው ንግድና ማዕከላዊ የህዝብ አገልግሎት ቀጠና' : 'Commercial hub & centralized administrative zone' }}
                  </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-800/90 border border-slate-700/80 hover:border-amber-400/60 transition-all">
                  <div class="w-9 h-9 rounded-xl bg-amber-900/60 text-amber-300 flex items-center justify-center font-black text-sm mb-3">02</div>
                  <h4 class="text-base font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'ቢራሮ ክፍለ ከተማ' : 'Biraro Sub-City' }}
                  </h4>
                  <p class="text-xs text-slate-400 mt-1 font-medium">
                    {{ langStore.currentLang === 'am' ? 'የቀደምት የከተማዋ ስያሜ መታሰቢያና ታሪካዊ ቀጠና' : 'Named in honor of the historic original Biraro settlement' }}
                  </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-800/90 border border-slate-700/80 hover:border-amber-400/60 transition-all">
                  <div class="w-9 h-9 rounded-xl bg-emerald-900/60 text-emerald-300 flex items-center justify-center font-black text-sm mb-3">03</div>
                  <h4 class="text-base font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'ሀሰን አገር ክፍለ ከተማ' : 'Hassen Ager Sub-City' }}
                  </h4>
                  <p class="text-xs text-slate-400 mt-1 font-medium">
                    {{ langStore.currentLang === 'am' ? 'ማህበራዊ አገልግሎቶችና የመኖሪያ ልማት ቀጠና' : 'Residential expansion & vibrant social service corridor' }}
                  </p>
                </div>

                <div class="p-5 rounded-2xl bg-slate-800/90 border border-slate-700/80 hover:border-amber-400/60 transition-all">
                  <div class="w-9 h-9 rounded-xl bg-purple-900/60 text-purple-300 flex items-center justify-center font-black text-sm mb-3">04</div>
                  <h4 class="text-base font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'ቦርከና ክፍለ ከተማ' : 'Borkena Sub-City' }}
                  </h4>
                  <p class="text-xs text-slate-400 mt-1 font-medium">
                    {{ langStore.currentLang === 'am' ? 'የኢንዱስትሪ፣ የትራንስፖርትና የወንዝ ዳርቻ ቀጠና' : 'Industrial corridor, dry port, & river basin zone' }}
                  </p>
                </div>
              </div>
            </div>

            <!-- 14 Urban Kebeles Breakdown -->
            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/90 shadow-xs">
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-800 flex items-center justify-center shrink-0">
                  <Building2 class="w-5 h-5 text-blue-700" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-blue-700 block">
                    {{ langStore.currentLang === 'am' ? 'በአራቱ ክፍለ ከተሞች ስር የተደራጁ' : 'Organized Under 4 Sub-Cities' }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? '14ቱ የከተማ ቀበሌዎች (14 Urban Kebeles)' : '14 Urban Kebeles Framework' }}
                  </h3>
                </div>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-2.5">
                <div 
                  v-for="(k, idx) in [
                    { am: 'ሁሩባ', en: 'Huruba' },
                    { am: 'ሸዋበር', en: 'Shewaber' },
                    { am: 'ቀዲዳ', en: 'Qedida' },
                    { am: 'ራስአገዝ', en: 'Ras Agez' },
                    { am: 'ቦርቸሌ', en: 'Borchele' },
                    { am: 'ሚጢቆሎ', en: 'Mitikolo' },
                    { am: 'በርበሬወንዝ', en: 'Berberewenz' },
                    { am: 'ሁዳዱ', en: 'Hudadu' },
                    { am: 'ሜጃ', en: 'Meja' },
                    { am: 'ቱሉአዳሜ', en: 'Tulu Adame' },
                    { am: 'ቁጠባ', en: 'Kuteba' },
                    { am: 'ሽሻበር', en: 'Shishaber' },
                    { am: 'የቀድሞዉ አየር ማረፊያ', en: 'Old Airport' },
                    { am: 'የጎፍ ቀበሌ', en: 'Yegof Kebele' }
                  ]" 
                  :key="idx"
                  class="p-3 rounded-2xl bg-slate-50 border border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 transition-all text-center"
                >
                  <span class="text-[10px] font-mono font-bold text-slate-400 block mb-0.5">#{{ idx + 1 }}</span>
                  <h5 class="text-xs sm:text-sm font-black text-slate-900 leading-tight">
                    {{ langStore.currentLang === 'am' ? k.am : k.en }}
                  </h5>
                </div>
              </div>
            </div>

            <!-- 6 Rural Kebeles Breakdown -->
            <div class="p-6 sm:p-8 rounded-3xl bg-emerald-50/70 border border-emerald-200/90 shadow-xs">
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-emerald-800 text-emerald-200 flex items-center justify-center shrink-0">
                  <Leaf class="w-5 h-5 text-emerald-300" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 block">
                    {{ langStore.currentLang === 'am' ? 'የገጠር ልማትና አስተዳደር ቀበሌዎች' : 'Rural Administrative Jurisdictions' }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-emerald-950">
                    {{ langStore.currentLang === 'am' ? 'ስድስቱ የገጠር ቀበሌዎች (6 Rural Kebeles)' : '6 Designated Rural Kebeles' }}
                  </h3>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <div 
                  v-for="rk in [
                    { am: 'ሙጢ ግራር', en: 'Muti Girar', code: '06' },
                    { am: 'ሁላበር', en: 'Hulaber', code: '07' },
                    { am: 'ከተማ አገር', en: 'Ketema Ager', code: '08' },
                    { am: 'ትዩ አምባ', en: 'Tiyu Amba', code: '09' },
                    { am: 'መጠኔ', en: 'Metene', code: '010' },
                    { am: 'ጋሌሳ', en: 'Galesa', code: '011' }
                  ]" 
                  :key="rk.code"
                  class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-2xs flex items-center justify-between"
                >
                  <div>
                    <h5 class="text-sm font-black text-slate-900">
                      {{ langStore.currentLang === 'am' ? rk.am : rk.en }}
                    </h5>
                    <span class="text-xs text-slate-500 font-medium">
                      {{ langStore.currentLang === 'am' ? 'የገጠር ቀበሌ' : 'Rural Kebele' }}
                    </span>
                  </div>
                  <div class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-900 font-mono font-black text-xs">
                    {{ langStore.currentLang === 'am' ? `ቀበሌ ${rk.code}` : `Kebele ${rk.code}` }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Economic & Strategic Powerhouse -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
              <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl">
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                  <Building2 class="w-5 h-5 text-blue-700" />
                  <span>{{ langStore.currentLang === 'am' ? 'የኢንዱስትሪ መዲና' : 'Industrial Hub & Manufacturing' }}</span>
                </h4>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  {{ langStore.currentLang === 'am'
                    ? 'የኮምቦልቻ ኢንዱስትሪ ፓርክ፣ ዘመናዊ የጨርቃ ጨርቅና ስፌት ፋብሪካዎች፣ የቢራ ፋብሪካዎች እና የብረታ ብረት ማቀነባበሪያዎች የሚገኙባት የኢኮኖሚ አንቀሳቃሽ ከተማ ናት።'
                    : 'Home to the state-of-the-art Kombolcha Industrial Park, large-scale textile mills, breweries, and steel processing factories propelling national manufacturing.'
                  }}
                </p>
              </div>
              <div class="p-6 bg-slate-50 border border-slate-200 rounded-3xl">
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                  <Compass class="w-5 h-5 text-emerald-600" />
                  <span>{{ langStore.currentLang === 'am' ? 'የመልቲሞዳል ሎጅስቲክስ ማዕከል' : 'Multimodal Logistics & Gateway' }}</span>
                </h4>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                  {{ langStore.currentLang === 'am'
                    ? 'በደረቅ ወደብ፣ በኮምቦልቻ አየር ማረፊያ እና በኢትዮ-ጅቡቲ ምድር ባቡር መስመር የተሳሰረች የሀገሪቱ ሰሜን-ምስራቅ የንግድ መተላለፊያ በር ናት።'
                    : 'Anchored by the Kombolcha Dry Port, regional airport, and Ethio-Djibouti Railway corridor, serving as northeastern Ethiopia\'s foremost multimodal trading gateway.'
                  }}
                </p>
              </div>
            </div>
          </div>

          <!-- TAB 2: HISTORY (ታሪክና አመጣጥ) -->
          <div v-else-if="activeTab === 'history'" class="space-y-6 animate-fadeIn">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ታሪክና አመጣጥ' : 'Historical Heritage & Evolution' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Foundation, Historical Evolution, and Modern Transformation</p>
            </div>
            <div class="text-slate-700 leading-relaxed space-y-4 text-base">
              <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200/90 text-slate-800">
                <h4 class="font-black text-amber-950 mb-2 flex items-center gap-2">
                  <Landmark class="w-4 h-4 text-amber-700" />
                  <span>{{ langStore.currentLang === 'am' ? 'የከተማዋ ጥንታዊ አመሰራረትና የቢራሮ ስያሜ' : 'Historic Origin & The Biraro Heritage' }}</span>
                </h4>
                <p class="text-sm leading-relaxed text-justify">
                  {{ langStore.currentLang === 'am'
                    ? 'የኮምቦልቻ ከተማ በ1935 ዓ.ም በማዘጋጃ ቤት እዉቅና አግኝታ ከመከተሟ በፊት ቢራሮ በመባል ትታወቅ እንደነበር የአካባቢዉ የዕድሜ ባለፀጎች እማኝነታቸዉን ይሰጣሉ፡፡ ስያሜዋንም አስመልክቶ ከአካባቢው የተፈጥሮ ገጽታ እና ከነዋሪዎች አጠራር ጋር ተያይዞ ኮምቦልቻ ተብላ መጠራት ጀመረች። ከተማዋ በ2015 ዓ.ም የሪጆፖሊታን ከተማ ደረጃን ተጎናጽፋለች።'
                    : 'Prior to being formally chartered as a municipality in 1935 E.C. (1943 G.C.), local community elders recall that the area was historically called \'Biraro\'. With the expansion of commerce and infrastructure along the Wollo corridor, the town evolved into Kombolcha and achieved Regiopolitan status in 2015 E.C.'
                  }}
                </p>
              </div>
              <p class="text-justify">
                {{ langStore.currentLang === 'am'
                  ? 'የኮምቦልቻ ከተማ በስልታዊ የንግድ መተላለፊያ መስመር ላይ በመገኘቷ በ20ኛው ክፍለ ዘመን አጋማሽ የጨርቃ ጨርቅና የስጋ ማቀነባበሪያ ኢንዱስትሪዎች ሲመሰረቱባት የኢትዮጵያ የኢንዱስትሪ መዲና መሆን ጀምራለች።'
                  : 'Kombolcha\'s historical evolution began as a strategic crossroads. During the mid-20th century, the establishment of pioneer textile, meat processing, and manufacturing plants transformed Kombolcha into one of Ethiopia\'s premier industrial epicenters, earning it the celebrated title "The Industrial Hearth of Wollo."'
                }}
              </p>
            </div>
          </div>

          <!-- TAB 3: MISSION, VISION & CORE VALUES -->
          <div v-else-if="activeTab === 'vision'" class="space-y-8 animate-fadeIn">
            <!-- Header Header -->
            <div class="border-b border-slate-100 pb-4 mb-6">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-900 text-xs font-black uppercase tracking-wider mb-2">
                <Compass class="w-3.5 h-3.5 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር' : (langStore.currentLang === 'om' ? 'Bulchiinsa Magaalaa Kombolchaa' : 'Kombolcha City Administration') }}</span>
              </div>
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'ተልዕኮ፣ ርእይ እና እሴቶች' : (langStore.currentLang === 'om' ? 'Ergama, Mul\'ata fi Duudhaalee' : 'Mission, Vision & Core Values') }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">
                {{ langStore.currentLang === 'am' 
                  ? 'የከተማው አስተዳደር መሪ የልማትና የመልካም አስተዳደር መርሆዎች' 
                  : (langStore.currentLang === 'om' ? 'Qajeeltoo Misoomaa fi Bulchiinsa Gaarii Magaalaa Kombolchaa' : 'Strategic Governance Framework and Municipal Development Principles') 
                }}
              </p>
            </div>

            <!-- 1. MISSION (ተልዕኮ) -->
            <div class="p-6 sm:p-8 rounded-3xl bg-blue-50/70 border border-blue-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-700"></div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-900 text-amber-300 flex items-center justify-center shrink-0 shadow-sm">
                  <Compass class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-blue-800 block">
                    {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር ተልዕኮ' : (langStore.currentLang === 'om' ? 'Ergama Bulchiinsa Magaalaa' : 'Municipal Mission') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-blue-950">
                    {{ langStore.currentLang === 'am' ? 'ተልዕኮ' : (langStore.currentLang === 'om' ? 'Ergama Keenya' : 'Our Mission') }}
                  </h3>
                </div>
              </div>

              <p class="text-slate-800 text-sm sm:text-base leading-relaxed text-justify font-medium">
                {{ langStore.currentLang === 'am'
                  ? 'ሁሉንም የልማት ሀይሎች እና ያለንን ሀብት በተቀናጀ መንገድ በመጠቀም ግልጽነት፤ አሳታፊነት፤ ፍትሀዊነትና ተጠያቂነት ያለበትን አሰራር በማስፈን ዲሞክራሲና መልካም አስተዳደር ኢኮኖሚያዊና ማህበራዊ ልማት በማረጋገጥ የከተማውን ኗሪ ህዝብ በየደረጃው ተጠቃሚ በማድረግ ለክልሉ ልማት ትርጉም ያለው አስተዋጽኦ የሚያበረክት ብልጽግና በከተማችን ማምጣት፤'
                  : (langStore.currentLang === 'om'
                    ? 'Humnoota misoomaa fi qabeenya qabnu hunda bifa qindaa\'een fayyadamuun, iftoomina, hirmaachisummaa, haqummaa fi itti-gaafatamummaa diriirsuun, dimokiraasii fi bulchiinsa gaarii mirkaneessuun, misooma diinagdee fi hawaasummaa mirkaneessuudhaan jiraattota magaalaa sadarkaa hundatti fayyadamoo taasisuu fi guddina naannichaatiif gumaacha hiika qabu gochuudhaan badhaadhina magaalaa keenyatti fiduu.'
                    : 'To bring about meaningful prosperity across our city that contributes significantly to regional development by utilizing all development forces and available resources in a coordinated manner; establishing transparent, participatory, equitable, and accountable practices; ensuring democracy, good governance, and socioeconomic development; and benefiting city residents at all levels.'
                  )
                }}
              </p>
            </div>

            <!-- 2. VISION (ርእይ) -->
            <div class="p-6 sm:p-8 rounded-3xl bg-emerald-50/70 border border-emerald-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-emerald-600"></div>
              <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-emerald-800 text-emerald-200 flex items-center justify-center shrink-0 shadow-sm">
                  <Sparkles class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 block">
                    {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር ስትራቴጂካዊ ግቦች' : (langStore.currentLang === 'om' ? 'Galmawwan Mul\'ata Magaalaa' : 'Strategic Vision Targets') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-emerald-950">
                    {{ langStore.currentLang === 'am' ? 'ርእይ' : (langStore.currentLang === 'om' ? 'Mul\'ata Keenya' : 'Our Vision') }}
                  </h3>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Vision Milestone 1 (2022 ዓ.ም) -->
                <div class="p-5 rounded-2xl bg-white border border-emerald-200 shadow-2xs hover:border-emerald-400 hover:shadow-xs transition-all flex flex-col justify-between">
                  <div>
                    <div class="flex items-center gap-2 mb-3">
                      <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-900 font-mono font-black text-xs">
                        {{ langStore.currentLang === 'am' ? 'በ2022 ዓ.ም' : (langStore.currentLang === 'om' ? 'Bara 2022 A.L.I' : 'Target 2030 (2022 E.C.)') }}
                      </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed">
                      {{ langStore.currentLang === 'am'
                        ? 'በ2022 ዓ.ም አስተማማኝ ሠላም ዲሞክራሲና መልካም አስተዳደር ሁለንተናዊ ልማት የሰፈነበትና ለስራና ለኑሮ ተስማሚ የኢንዱስትሪ ማዕከል ሆና ማየት'
                        : (langStore.currentLang === 'om'
                          ? 'Bara 2022tti nagaa amansiisaan, dimokiraasii fi bulchiinsi gaariin, misoomni hundagaleessi kan dagaagee fi hojii fi jireenyaaf mijattuu kan taate giddugala indaastirii taatee arguun.'
                          : 'To see Kombolcha as an industrial hub characterized by enduring peace, democracy, good governance, and comprehensive development—making it an ideal destination to work and live by 2022 E.C. (2030).'
                        )
                      }}
                    </p>
                  </div>
                </div>

                <!-- Vision Milestone 2 (2025 ዓ.ም) -->
                <div class="p-5 rounded-2xl bg-white border border-emerald-200 shadow-2xs hover:border-emerald-400 hover:shadow-xs transition-all flex flex-col justify-between">
                  <div>
                    <div class="flex items-center gap-2 mb-3">
                      <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-900 font-mono font-black text-xs">
                        {{ langStore.currentLang === 'am' ? 'በ2025 ዓ.ም' : (langStore.currentLang === 'om' ? 'Bara 2025 A.L.I' : 'Target 2033 (2025 E.C.)') }}
                      </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed">
                      {{ langStore.currentLang === 'am'
                        ? 'በ2025 ዓ.ም በኢትዮጵያ ደረጃ ምቹ የኢንዱስትሪ፣ የሎጀስቲክና የንግድ ከተማ ሁና ማየት'
                        : (langStore.currentLang === 'om'
                          ? 'Bara 2025tti sadarkaa Itiyoophiyaatti magaalaa indaastirii, lojistiksii fi daldalaa mijattuu taatee arguun.'
                          : 'To see Kombolcha become an exceptionally conducive industrial, logistics, and commercial metropolitan city at the Ethiopian national level by 2025 E.C. (2033).'
                        )
                      }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- 3. VALUES (እሴቶች) -->
            <div class="p-6 sm:p-8 rounded-3xl bg-amber-50/70 border border-amber-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-amber-500"></div>
              <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center shrink-0 shadow-sm font-black">
                  <Award class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-amber-900 block">
                    {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር መመሪያ እሴቶች' : (langStore.currentLang === 'om' ? 'Duudhaalee Ijoo Bulchiinsichaa' : 'Municipal Core Values') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'እሴቶች' : (langStore.currentLang === 'om' ? 'Duudhaalee' : 'Core Values') }}
                  </h3>
                </div>
              </div>

              <!-- 7 Core Values Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                <div 
                  v-for="(val, index) in coreValues" 
                  :key="index"
                  class="p-4 rounded-2xl bg-white border border-amber-200/80 shadow-2xs hover:border-amber-400 hover:shadow-xs transition-all flex items-start gap-3"
                >
                  <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-900 font-mono text-xs font-black flex items-center justify-center shrink-0">
                    {{ index + 1 }}
                  </div>
                  <div class="pt-0.5">
                    <h4 class="text-xs sm:text-sm font-black text-slate-900 leading-snug">
                      {{ getCoreValueTitle(val) }}
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 4: PEOPLE & CULTURE -->
          <div v-else-if="activeTab === 'people'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'ህዝብ፣ ባህልና አኗኗር' : 'People & Culture' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Demographics, Wollo Hospitality, and Social Harmony</p>
            </div>
            <div class="text-slate-700 leading-relaxed space-y-4 text-base">
              <p>
                Kombolcha is renowned across Ethiopia for its deep culture of tolerance, hospitality, and peaceful interfaith coexistence. Christians and Muslims have cohabited harmoniously for generations, celebrating religious festivals and cultural traditions together.
              </p>
              <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                  <HeartHandshake class="w-5 h-5 text-rose-600" />
                  <span>The Spirit of Wollo Hospitality</span>
                </h4>
                <p class="text-xs sm:text-sm text-slate-600">The people of Kombolcha welcome visitors and investors with traditional coffee ceremonies, vibrant Sunday markets, and renowned Wollo cultural melodies.</p>
              </div>
            </div>
          </div>

          <!-- TAB 5: GEOGRAPHY & CLIMATE -->
          <div v-else-if="activeTab === 'geography'" class="space-y-8 animate-fadeIn">
            <!-- Header -->
            <div class="border-b border-slate-100 pb-4 mb-6">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-900 text-xs font-black uppercase tracking-wider mb-2">
                <Globe class="w-3.5 h-3.5 text-emerald-700" />
                <span>11°06' N • 39°45' E</span>
              </div>
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የከተማዋ መልክዓ ምድራዊ አቀማመጥና አየር ንብረት' : (langStore.currentLang === 'om' ? 'Teessuma Lafaa fi Haala Qilleensaa Magaalaa' : 'Geographic Location, Topography & Climate') }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">
                {{ langStore.currentLang === 'am' 
                  ? 'አስትሮኖሚያዊ መገኛ፣ የቆዳ ስፋት፣ የአየር ንብረት ሁኔታ፣ የመሬት ገጽታና የተፈጥሮ ሀብት' 
                  : (langStore.currentLang === 'om' ? 'Qilleensa, Bal\'ina Lafaa, Ol\'aantummaa fi Qabeenya Uumamaa' : 'Astronomical Position, Land Area, Elevation, Climate Profile & Natural Topology') 
                }}
              </p>
            </div>

            <!-- 1. Geographic Location Narrative -->
            <div class="p-6 sm:p-8 rounded-3xl bg-blue-50/70 border border-blue-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-700"></div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-900 text-amber-300 flex items-center justify-center shrink-0 shadow-sm">
                  <MapPin class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-blue-800 block">
                    {{ langStore.currentLang === 'am' ? 'አስትሮኖሚያዊ መገኛ' : (langStore.currentLang === 'om' ? 'Teessuma Hawaa' : 'Astronomical Position & Junction') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-blue-950">
                    {{ langStore.currentLang === 'am' ? 'የከተማዋ መልክዓ ምድራዊ አቀማመጥ' : (langStore.currentLang === 'om' ? 'Teessuma Lafaa Magaalaa' : 'Geographic Position & Key Distances') }}
                  </h3>
                </div>
              </div>

              <p class="text-slate-800 text-sm sm:text-base leading-relaxed text-justify font-medium">
                {{ langStore.currentLang === 'am'
                  ? 'የኢንዱስትሪ መዲናዋ ኮምቦልቻ አስትሮኖሚያዊ መገኛዋ በ11°06\' ሰሜን ላቲቲዩድ እና በ39°45\' ምስራቅ ሎንግቲዩድ ላይ ሲሆን ከአዲስ አበባ በ377 ኪሎ ሜትር ርቀት ላይ፣ ከክልሉ ርዕሰ-ከተማ ባህርዳር በ505 ኪሎ ሜትር ፤ ከደቡብ ወሎ መስተዳድር ዞን ዋና ከተማ ደሴ በ23 ኪሎ ሜትር ፣ እንዲሁም ከጅቡቲ ወደብ በ484 ኪ.ሜትር (301 miles) ርቀት ላይ የምትገኝ እንደሆነ ያገኘነዉ መረጃ ይጠቁማል፡፡'
                  : (langStore.currentLang === 'om'
                    ? 'Magaalaan indaastirii Kombolchaa laatiitiyuudii 11°06\' Kaabaa fi loongiitiyuudii 39°45\' Bahaa irratti kan argamtu yoo ta\'u, Finfinnee irraa km 377, magaalaa guddoo naannichaa Baahir Daar irraa km 505, magaalaa guddoo Zoonii Wallo Kibbaa Dasee irraa km 23, akkasumas buufata doonii Jibuutii irraa km 484 (maayilii 301) fageenya irratti argamti.'
                    : 'The industrial powerhouse of Kombolcha is astronomically situated at 11°06\' North Latitude and 39°45\' East Longitude. It is strategically positioned 377 km north of Addis Ababa, 505 km from regional capital Bahir Dar, 23 km from South Wollo Zone capital Dessie, and 484 km (301 miles) from the international maritime port of Djibouti.'
                  )
                }}
              </p>

              <!-- Strategic Distances Grid -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-6 border-t border-blue-200/80">
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ከአዲስ አበባ' : (langStore.currentLang === 'om' ? 'Finfinnee Irraa' : 'From Addis Ababa') }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-blue-900 mt-1">377 km</div>
                  <span class="text-[10px] text-slate-400 font-medium">National Capital</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ከባህር ዳር' : (langStore.currentLang === 'om' ? 'Baahir Daar Irraa' : 'From Bahir Dar') }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-blue-900 mt-1">505 km</div>
                  <span class="text-[10px] text-slate-400 font-medium">Regional Capital</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ከደሴ' : (langStore.currentLang === 'om' ? 'Dasee Irraa' : 'From Dessie') }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-blue-900 mt-1">23 km</div>
                  <span class="text-[10px] text-slate-400 font-medium">Zonal Center</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-white border border-blue-200 shadow-2xs">
                  <span class="text-[11px] font-bold text-slate-500 block">
                    {{ langStore.currentLang === 'am' ? 'ከጅቡቲ ወደብ' : (langStore.currentLang === 'om' ? 'Buufata Jibuutii' : 'Djibouti Port') }}
                  </span>
                  <div class="text-lg sm:text-xl font-black font-mono text-emerald-700 mt-1">484 km</div>
                  <span class="text-[10px] text-slate-400 font-medium">301 miles (Sea Corridor)</span>
                </div>
              </div>
            </div>

            <!-- 2. Kebele Cadastral Map & Document Showcase -->
            <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white shadow-xl relative overflow-hidden group">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                  <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'ይፋዊ የከተማው ካርታና ሰነድ' : (langStore.currentLang === 'om' ? 'Kaartaa fi Galmee Seeraa' : 'Official Cadastral Survey & Documentation') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-white">
                    {{ langStore.currentLang === 'am' ? 'የቀበሌዎችን መገኛ የሚያሳይ ካርታ' : (langStore.currentLang === 'om' ? 'Kaartaa Argama Gandoolee' : 'Official Kebele Boundaries & Cadastral Map') }}
                  </h3>
                </div>
                <button 
                  type="button" 
                  @click="showMapModal = true"
                  class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md transition-all self-start sm:self-auto"
                >
                  <Maximize2 class="w-4 h-4" />
                  <span>{{ langStore.currentLang === 'am' ? 'ሙሉ ሰነዱን በከፍተኛ ጥራት ተመልከት' : 'Inspect Full Document' }}</span>
                </button>
              </div>

              <!-- Map Image Preview Container -->
              <div 
                @click="showMapModal = true"
                class="rounded-2xl overflow-hidden border border-slate-700/80 bg-slate-950 relative cursor-pointer group-hover:border-amber-400/60 transition-all max-h-96 flex items-center justify-center"
              >
                <img 
                  src="/Geography-Climate.png" 
                  alt="የቀበሌዎችን መገኛ የሚያሳይ ካርታ" 
                  class="w-full h-full object-contain max-h-96 group-hover:scale-102 transition-transform duration-500" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                  <span class="text-xs text-amber-300 font-bold flex items-center gap-2">
                    <Maximize2 class="w-4 h-4" />
                    <span>{{ langStore.currentLang === 'am' ? 'ካርታውንና ይፋዊ ሰነዱን ለማጉላት ጠቅ ያድርጉ' : 'Click to zoom and view full official survey document' }}</span>
                  </span>
                </div>
              </div>
            </div>

            <!-- 3. Surface Area & Elevation Stats (4 Columns) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-500 block mb-1">
                  {{ langStore.currentLang === 'am' ? 'የከተማዋ የቆዳ ስፋት' : (langStore.currentLang === 'om' ? 'Bal\'ina Lafaa' : 'Total Surface Area') }}
                </span>
                <div class="text-2xl font-black font-mono text-slate-900">124.5 km²</div>
                <span class="text-xs text-blue-700 font-mono font-bold mt-1 block">15,550.25 Hectares</span>
              </div>

              <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-500 block mb-1">
                  {{ langStore.currentLang === 'am' ? 'አማካኝ ከፍታ ከባህር ጠለል' : (langStore.currentLang === 'om' ? 'Ol\'aantummaa Giddugaleessaa' : 'Average Elevation') }}
                </span>
                <div class="text-2xl font-black font-mono text-emerald-800">1,857 m</div>
                <span class="text-xs text-slate-400 font-medium mt-1 block">Woina Dega Zone</span>
              </div>

              <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-500 block mb-1">
                  {{ langStore.currentLang === 'am' ? 'ከፍተኛ ቦታ' : (langStore.currentLang === 'om' ? 'Bakka Ol\'aanaa' : 'Highest Altitude Peak') }}
                </span>
                <div class="text-2xl font-black font-mono text-blue-900">2,700 m</div>
                <span class="text-xs text-slate-500 font-medium mt-1 block">Mount Yegof Crest</span>
              </div>

              <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-500 block mb-1">
                  {{ langStore.currentLang === 'am' ? 'ዝቅተኛ ቦታ' : (langStore.currentLang === 'om' ? 'Bakka Gadi-aanaa' : 'Lowest Altitude Basin') }}
                </span>
                <div class="text-2xl font-black font-mono text-amber-700">1,600 m</div>
                <span class="text-xs text-slate-500 font-medium mt-1 block">Borkena Valley Basin</span>
              </div>
            </div>

            <!-- 4. Climate Profile & Agro-Ecological Distribution -->
            <div class="p-6 sm:p-8 rounded-3xl bg-emerald-50/70 border border-emerald-200/90 shadow-xs relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-emerald-600"></div>
              <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-emerald-800 text-emerald-200 flex items-center justify-center shrink-0 shadow-sm">
                  <CloudSun class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-emerald-800 block">
                    {{ langStore.currentLang === 'am' ? 'የአየር ሁኔታና ስነ-ምህዳር' : (langStore.currentLang === 'om' ? 'Haala Qilleensaa' : 'Meteorology & Agro-Ecology') }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-emerald-950">
                    {{ langStore.currentLang === 'am' ? 'የከተማው የአየር ንብረት ሁኔታ' : (langStore.currentLang === 'om' ? 'Haala Qilleensa Magaalaa' : 'Climate Characteristics & Weather Profile') }}
                  </h3>
                </div>
              </div>

              <p class="text-slate-800 text-sm sm:text-base leading-relaxed text-justify font-medium">
                {{ langStore.currentLang === 'am'
                  ? 'የአየር ሁኔታ መቀያየር አንዳንድ ወቅት ላይ የቆላነት ሌላ ጊዜ ደግሞ የወይናደጋነት ባህሪ እያላበሳት ዓመቱን የምትደፍነዉ ኮምቦልቻ ከፍተኛ ዓመታዊ የሙቀት መጠኗ 26.1°C ዝቅተኛዉ ደግሞ 5.8°C ይደርሳል፡፡ አመታዊ አማካኝ የዝናብ መጠኗም 1248 ሚሊ ሜትር እንደሚደርስ መረጃዎች ያመላክታሉ፡፡'
                  : (langStore.currentLang === 'om'
                    ? 'Qilleensi yeroo tokko tokko qorra yeroo kaan immoo ho\'a ta\'uudhaan, waggaa guutuu ho\'i ol\'aanaan 26.1°C fi gadi-aanaan 5.8°C kan qabdu yoo ta\'u, roobni waggaa giddu-galeessaan 1,248 mm ta\'a.'
                    : 'Experiencing seasonal climatic variations blending mild lowland warmth and refreshing subtropical highland qualities throughout the year, Kombolcha records an annual maximum temperature of 26.1°C and an annual minimum of 5.8°C, with an average annual precipitation of 1,248 mm.'
                  )
                }}
              </p>

              <!-- Climate Metrics Cards -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-2xs">
                  <div class="flex items-center gap-2 text-rose-600 mb-1">
                    <Thermometer class="w-4 h-4" />
                    <span class="text-xs font-bold">{{ langStore.currentLang === 'am' ? 'ከፍተኛ ዓመታዊ የሙቀት መጠን' : 'Max Annual Temp' }}</span>
                  </div>
                  <div class="text-2xl font-black font-mono text-slate-900">26.1 °C</div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-2xs">
                  <div class="flex items-center gap-2 text-blue-600 mb-1">
                    <Thermometer class="w-4 h-4" />
                    <span class="text-xs font-bold">{{ langStore.currentLang === 'am' ? 'ዝቅተኛ ዓመታዊ የሙቀት መጠን' : 'Min Annual Temp' }}</span>
                  </div>
                  <div class="text-2xl font-black font-mono text-slate-900">5.8 °C</div>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-2xs">
                  <div class="flex items-center gap-2 text-emerald-600 mb-1">
                    <Droplets class="w-4 h-4" />
                    <span class="text-xs font-bold">{{ langStore.currentLang === 'am' ? 'ዓመታዊ አማካኝ የዝናብ መጠን' : 'Annual Rainfall' }}</span>
                  </div>
                  <div class="text-2xl font-black font-mono text-slate-900">1,248 mm</div>
                </div>
              </div>

              <!-- Agro-Ecological Distribution across Kebeles -->
              <div class="mt-6 pt-6 border-t border-emerald-200/80">
                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-900 mb-3">
                  {{ langStore.currentLang === 'am' ? 'በከተማ አስተዳደሩ ባሉ ቀበሌዎች የአየር ንብረት ድርሻ' : 'Agro-Ecological Distribution across Kebeles' }}
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div class="p-3.5 rounded-xl bg-white border border-emerald-200">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                      <span class="text-emerald-900">{{ langStore.currentLang === 'am' ? 'ወይና ደጋ' : 'Woina Dega (Subtropical)' }}</span>
                      <span class="font-mono font-black text-emerald-700">74.86%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                      <div class="h-full bg-emerald-600 rounded-full" style="width: 74.86%"></div>
                    </div>
                  </div>

                  <div class="p-3.5 rounded-xl bg-white border border-emerald-200">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                      <span class="text-amber-900">{{ langStore.currentLang === 'am' ? 'ቆላ' : 'Kolla (Warm Lowland)' }}</span>
                      <span class="font-mono font-black text-amber-700">17.09%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                      <div class="h-full bg-amber-500 rounded-full" style="width: 17.09%"></div>
                    </div>
                  </div>

                  <div class="p-3.5 rounded-xl bg-white border border-emerald-200">
                    <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                      <span class="text-blue-900">{{ langStore.currentLang === 'am' ? 'ደጋ' : 'Dega (Cool Highland)' }}</span>
                      <span class="font-mono font-black text-blue-700">8.05%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                      <div class="h-full bg-blue-600 rounded-full" style="width: 8.05%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- 5. Topography & Landform Breakdown -->
            <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/90 shadow-xs">
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center shrink-0">
                  <Mountain class="w-5 h-5 text-blue-800" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block">
                    {{ langStore.currentLang === 'am' ? 'የመሬት ገጽታ ስብጥር' : 'Topographic Classification' }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የመሬት ገጽታ / ቶፖግራፊ' : 'Terrain & Topography Composition' }}
                  </h3>
                </div>
              </div>

              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                  <span class="text-xs font-bold text-slate-500 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'ሜዳማ' : 'Plains / Flatlands' }}
                  </span>
                  <div class="text-2xl font-black font-mono text-blue-900">45.5%</div>
                  <div class="w-full h-1.5 rounded-full bg-slate-200 mt-2 overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: 45.5%"></div>
                  </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                  <span class="text-xs font-bold text-slate-500 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'ተራራማ' : 'Mountainous' }}
                  </span>
                  <div class="text-2xl font-black font-mono text-emerald-800">32.9%</div>
                  <div class="w-full h-1.5 rounded-full bg-slate-200 mt-2 overflow-hidden">
                    <div class="h-full bg-emerald-600 rounded-full" style="width: 32.9%"></div>
                  </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                  <span class="text-xs font-bold text-slate-500 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'ወጣገባ' : 'Rugged Terrain' }}
                  </span>
                  <div class="text-2xl font-black font-mono text-amber-700">21.5%</div>
                  <div class="w-full h-1.5 rounded-full bg-slate-200 mt-2 overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full" style="width: 21.5%"></div>
                  </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                  <span class="text-xs font-bold text-slate-500 block mb-1">
                    {{ langStore.currentLang === 'am' ? 'ሸለቋማ' : 'Valley Basins' }}
                  </span>
                  <div class="text-2xl font-black font-mono text-purple-900">0.6%</div>
                  <div class="w-full h-1.5 rounded-full bg-slate-200 mt-2 overflow-hidden">
                    <div class="h-full bg-purple-600 rounded-full" style="width: 5%"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- 6. Boundaries & Surrounding Woredas -->
            <div class="p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200">
              <h3 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
                <Navigation class="w-5 h-5 text-blue-700" />
                <span>{{ langStore.currentLang === 'am' ? 'የከተማዋ አዋሳኞች' : (langStore.currentLang === 'om' ? 'Daangaa Magaalaa' : 'Municipal Territorial Boundaries') }}</span>
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                  <span class="text-[10px] font-black uppercase text-blue-700 tracking-wider block mb-0.5">
                    {{ langStore.currentLang === 'am' ? 'በስተሰሜን (North)' : 'North Boundary' }}
                  </span>
                  <h4 class="text-sm font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የተሁለደሬ ወረዳ' : 'Tehuledere Woreda' }}
                  </h4>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                  <span class="text-[10px] font-black uppercase text-emerald-700 tracking-wider block mb-0.5">
                    {{ langStore.currentLang === 'am' ? 'በስተደቡብ (South)' : 'South Boundary' }}
                  </span>
                  <h4 class="text-sm font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የቃሉ ወረዳ' : 'Kalu Woreda' }}
                  </h4>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                  <span class="text-[10px] font-black uppercase text-amber-700 tracking-wider block mb-0.5">
                    {{ langStore.currentLang === 'am' ? 'በስተምስራቅ (East)' : 'East Boundary' }}
                  </span>
                  <h4 class="text-sm font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የቃሉ ወረዳ' : 'Kalu Woreda' }}
                  </h4>
                </div>

                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                  <span class="text-[10px] font-black uppercase text-purple-700 tracking-wider block mb-0.5">
                    {{ langStore.currentLang === 'am' ? 'በስተምዕራብ (West)' : 'West Boundary' }}
                  </span>
                  <h4 class="text-sm font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የደሴ ዙሪያ ወረዳ' : 'Dessie Zuria Woreda' }}
                  </h4>
                </div>
              </div>
            </div>

            <!-- 7. Rivers & Mountains (Hydrology & Orography) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Rivers Card -->
              <div class="p-6 rounded-3xl bg-blue-50/60 border border-blue-200">
                <div class="flex items-center gap-2.5 mb-4">
                  <div class="w-8 h-8 rounded-xl bg-blue-900 text-white flex items-center justify-center">
                    <Waves class="w-4 h-4" />
                  </div>
                  <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 block">Hydrology</span>
                    <h4 class="text-base font-black text-slate-900">
                      {{ langStore.currentLang === 'am' ? 'በከተማዋ ያሉ ወንዞች' : (langStore.currentLang === 'om' ? 'Lagaalee Magaalaa' : 'Major Rivers & Waterways') }}
                    </h4>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                  <div class="p-3 rounded-xl bg-white border border-blue-200/80 font-bold text-xs text-slate-800">
                    💧 {{ langStore.currentLang === 'am' ? 'ቦርከና' : 'Borkena River' }}
                  </div>
                  <div class="p-3 rounded-xl bg-white border border-blue-200/80 font-bold text-xs text-slate-800">
                    💧 {{ langStore.currentLang === 'am' ? 'በርበሬ ወንዝ' : 'Berbere River' }}
                  </div>
                  <div class="p-3 rounded-xl bg-white border border-blue-200/80 font-bold text-xs text-slate-800">
                    💧 {{ langStore.currentLang === 'am' ? 'አራውሌ' : 'Arawle River' }}
                  </div>
                  <div class="p-3 rounded-xl bg-white border border-blue-200/80 font-bold text-xs text-slate-800">
                    💧 {{ langStore.currentLang === 'am' ? 'ወርቃ' : 'Werka River' }}
                  </div>
                </div>
              </div>

              <!-- Mountains Card -->
              <div class="p-6 rounded-3xl bg-emerald-50/60 border border-emerald-200">
                <div class="flex items-center gap-2.5 mb-4">
                  <div class="w-8 h-8 rounded-xl bg-emerald-800 text-white flex items-center justify-center">
                    <Mountain class="w-4 h-4" />
                  </div>
                  <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-emerald-700 block">Orography</span>
                    <h4 class="text-base font-black text-slate-900">
                      {{ langStore.currentLang === 'am' ? 'በከተማዋ ዳር ያሉ ተራሮች' : (langStore.currentLang === 'om' ? 'Tulluuwwan Magaalaa' : 'Surrounding Mountain Peaks') }}
                    </h4>
                  </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                  <div class="p-2.5 rounded-xl bg-white border border-emerald-200/80 font-bold text-xs text-slate-800 text-center">
                    ⛰️ {{ langStore.currentLang === 'am' ? 'የጎፍ' : 'Yegof' }}
                  </div>
                  <div class="p-2.5 rounded-xl bg-white border border-emerald-200/80 font-bold text-xs text-slate-800 text-center">
                    ⛰️ {{ langStore.currentLang === 'am' ? 'ወበላ' : 'Webela' }}
                  </div>
                  <div class="p-2.5 rounded-xl bg-white border border-emerald-200/80 font-bold text-xs text-slate-800 text-center">
                    ⛰️ {{ langStore.currentLang === 'am' ? 'ነጃውጎራ' : 'Nejawgora' }}
                  </div>
                  <div class="p-2.5 rounded-xl bg-white border border-emerald-200/80 font-bold text-xs text-slate-800 text-center">
                    ⛰️ {{ langStore.currentLang === 'am' ? 'አሰንአገር' : 'Asenager' }}
                  </div>
                  <div class="p-2.5 rounded-xl bg-white border border-emerald-200/80 font-bold text-xs text-slate-800 text-center col-span-2 sm:col-span-1">
                    ⛰️ {{ langStore.currentLang === 'am' ? 'መሰንገር' : 'Mesenger' }}
                  </div>
                </div>
              </div>
            </div>

            <!-- 8. Demographics, Diversity & Faith -->
            <div class="p-6 sm:p-8 rounded-3xl bg-amber-50/70 border border-amber-200/90 shadow-xs">
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center shrink-0 font-black">
                  <Users class="w-5 h-5" />
                </div>
                <div>
                  <span class="text-[11px] font-black uppercase tracking-wider text-amber-900 block">
                    {{ langStore.currentLang === 'am' ? 'ህብረ-ብሔራዊነትና አብሮነት' : 'Social Fabric & Harmony' }}
                  </span>
                  <h3 class="text-lg sm:text-xl font-black text-slate-900">
                    {{ langStore.currentLang === 'am' ? 'የህዝብ ስብጥር፣ የውጭ ዜጎችና እምነት' : 'Demographics, International Communities & Faith' }}
                  </h3>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Ethnic Diversity -->
                <div class="p-5 rounded-2xl bg-white border border-amber-200 shadow-2xs">
                  <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
                    {{ langStore.currentLang === 'am' ? 'በከተማዋ የሚኖሩ ብሔረሰቦች' : 'Ethnic Communities' }}
                  </h4>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="eth in (langStore.currentLang === 'am' ? ['አማራ', 'ትግሬ', 'አፋር', 'ኦሮሞ', 'ጉራጌ', 'አርጎባ'] : ['Amhara', 'Tigrayan', 'Afar', 'Oromo', 'Gurage', 'Argobba'])" 
                      :key="eth"
                      class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-xs text-slate-800"
                    >
                      {{ eth }}
                    </span>
                  </div>
                </div>

                <!-- Foreign Residents -->
                <div class="p-5 rounded-2xl bg-white border border-amber-200 shadow-2xs">
                  <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
                    {{ langStore.currentLang === 'am' ? 'የውጭ ዜጎች' : 'International Residents' }}
                  </h4>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="nat in (langStore.currentLang === 'am' ? ['ቱርክ', 'ቻይና', 'ጣሊያን', 'ሶሪያ'] : ['Turkey', 'China', 'Italy', 'Syria'])" 
                      :key="nat"
                      class="px-2.5 py-1 rounded-lg bg-blue-50 font-bold text-xs text-blue-900 border border-blue-200/60"
                    >
                      🌍 {{ nat }}
                    </span>
                  </div>
                </div>

                <!-- Religious Harmony -->
                <div class="p-5 rounded-2xl bg-white border border-amber-200 shadow-2xs">
                  <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
                    {{ langStore.currentLang === 'am' ? 'እምነትና ሃይማኖቶች' : 'Faith & Religions' }}
                  </h4>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="rel in (langStore.currentLang === 'am' ? ['እስልምና', 'ክርስትና', 'ፕሮቴስታንት', 'የይሖዋ ምስክሮች', 'ካቶሊክ'] : ['Islam', 'Christianity', 'Protestant', 'Jehovah\'s Witnesses', 'Catholic'])" 
                      :key="rel"
                      class="px-2.5 py-1 rounded-lg bg-emerald-50 font-bold text-xs text-emerald-900 border border-emerald-200/60"
                    >
                      🕊️ {{ rel }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- 9. Administrative Structure -->
            <div class="p-6 rounded-3xl bg-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-6">
              <div>
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 block mb-1">
                  {{ langStore.currentLang === 'am' ? 'የአስተዳደር አወቃቀር' : 'Governance Framework' }}
                </span>
                <h4 class="text-lg font-black text-white">
                  {{ langStore.currentLang === 'am' ? 'የከተማው መዋቅራዊ ክፍፍል' : 'Municipal Structural Breakdown' }}
                </h4>
                <p class="text-xs text-slate-400 mt-1">
                  {{ langStore.currentLang === 'am' 
                    ? 'ከተማዋ በአራት ክፍለ ከተማ፣ በ14 የከተማ ቀበሌና በ6 የገጠር ቀበሌ የተዋቀረች ናት።' 
                    : 'Governed through 4 Autonomous Sub-Cities, comprising 14 Urban Kebeles and 6 Rural Kebeles (20 Kebeles total).' 
                  }}
                </p>
              </div>

              <div class="flex items-center gap-3 shrink-0">
                <div class="text-center px-4 py-2.5 rounded-2xl bg-slate-800 border border-slate-700">
                  <div class="text-xl font-black font-mono text-amber-400">4</div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase">{{ langStore.currentLang === 'am' ? 'ክፍለ ከተማ' : 'Sub-Cities' }}</span>
                </div>
                <div class="text-center px-4 py-2.5 rounded-2xl bg-slate-800 border border-slate-700">
                  <div class="text-xl font-black font-mono text-blue-400">14</div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase">{{ langStore.currentLang === 'am' ? 'ከተማ ቀበሌ' : 'Urban' }}</span>
                </div>
                <div class="text-center px-4 py-2.5 rounded-2xl bg-slate-800 border border-slate-700">
                  <div class="text-xl font-black font-mono text-emerald-400">6</div>
                  <span class="text-[10px] font-bold text-slate-400 uppercase">{{ langStore.currentLang === 'am' ? 'ገጠር ቀበሌ' : 'Rural' }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 6: ADMINISTRATION -->
          <div v-else-if="activeTab === 'administration'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር መዋቅር' : 'Municipal Governance Charter' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Cabinet, Council, Sub-Cities, and Directorate Framework</p>
            </div>
            <div class="text-slate-700 leading-relaxed space-y-4 text-base">
              <p class="text-justify">
                {{ langStore.currentLang === 'am'
                  ? 'የኮምቦልቻ ከተማ አስተዳደር በአማራ ብሔራዊ ክልላዊ መንግሥት የማዘጋጃ ቤቶች አዋጅ መሰረት የተቋቋመ ሲሆን ከ2015 ዓ.ም ጀምሮ የሪጆፖሊታን ደረጃ ተሰጥቶታል። አስተዳደሩ በከንቲባ ካቢኔና በከተማው ምክር ቤት የሚመራ ሆኖ አገልግሎቶችን ለህዝቡ በቅርበት ለማድረስ በ 4 ክፍለ ከተሞች (አቢሻገር፣ ቢራሮ፣ ሀሰን አገር፣ ቦርከና) እንዲሁም በ 20 ቀበሌዎች (14 የከተማ እና 6 የገጠር ቀበሌዎች) የተዋቀረ ነው።'
                  : 'Kombolcha is chartered under the Amhara National Regional State Municipal Proclamation and attained Regiopolitan status in 2015 E.C. Governance is spearheaded by the Executive Mayor, assisted by the Mayoral Cabinet and accountable to the elected City Council, decentralizing civil services through 4 Sub-Cities (Abishager, Biraro, Hassen Ager, Borkena) across 20 Kebeles (14 Urban and 6 Rural).'
                }}
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <router-link to="/government/sub-cities" class="p-5 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-all block">
                  <h4 class="font-bold text-blue-900 mb-1 flex items-center justify-between">
                    <span>{{ langStore.currentLang === 'am' ? '4 ክፍለ ከተሞች' : '4 Autonomous Sub-Cities' }}</span>
                    <ChevronRight class="w-4 h-4 text-blue-600" />
                  </h4>
                  <p class="text-xs text-slate-500">
                    {{ langStore.currentLang === 'am' 
                      ? 'አቢሻገር፣ ቢራሮ፣ ሀሰን አገር እና ቦርከና ክፍለ ከተሞች (14 የከተማ እና 6 የገጠር ቀበሌዎች)' 
                      : 'Explore Abishager, Biraro, Hassen Ager, and Borkena administrative zones (20 Kebeles).' 
                    }}
                  </p>
                </router-link>
                <router-link to="/government/offices" class="p-5 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-all block">
                  <h4 class="font-bold text-blue-900 mb-1 flex items-center justify-between">
                    <span>{{ langStore.currentLang === 'am' ? 'የሴክተር መስሪያ ቤቶች' : 'Sector Bureaus' }}</span>
                    <ChevronRight class="w-4 h-4 text-blue-600" />
                  </h4>
                  <p class="text-xs text-slate-500">
                    {{ langStore.currentLang === 'am' 
                      ? 'የከተማው መምሪያዎች፣ ቢሮዎች እና አገልግሎት መስጫ ክፍሎች' 
                      : 'Access direct room numbers and phone extensions across municipal bureaus.' 
                    }}
                  </p>
                </router-link>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- Document & Map Fullscreen Lightbox Modal -->
    <div 
      v-if="showMapModal" 
      class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex flex-col justify-between p-4 sm:p-6"
      @click.self="showMapModal = false"
    >
      <div class="flex items-center justify-between text-white pb-3 z-20">
        <div>
          <span class="text-[10px] font-black uppercase text-amber-400 tracking-wider block">Official Cadastral Document</span>
          <h3 class="text-sm sm:text-base font-black text-white">
            {{ langStore.currentLang === 'am' ? 'የቀበሌዎችን መገኛ የሚያሳይ ካርታና ይፋዊ ሰነድ' : 'Official Kebele Boundaries & Geographic Document' }}
          </h3>
        </div>
        <div class="flex items-center gap-2">
          <a 
            href="/Geography-Climate.png" 
            target="_blank" 
            class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-white flex items-center gap-1.5 transition-colors cursor-pointer"
          >
            <span>Open High-Res</span>
          </a>
          <button 
            @click="showMapModal = false" 
            class="w-10 h-10 rounded-full bg-slate-800 hover:bg-rose-600 text-white flex items-center justify-center cursor-pointer transition-colors shadow-lg"
          >
            <X class="w-5 h-5" />
          </button>
        </div>
      </div>

      <div class="relative flex-1 flex items-center justify-center my-auto overflow-hidden">
        <img 
          src="/Geography-Climate.png" 
          alt="Official Geography & Climate Document" 
          class="max-h-[82vh] max-w-[94vw] object-contain rounded-2xl shadow-2xl animate-fadeIn" 
        />
      </div>
    </div>
  </div>
</template>
