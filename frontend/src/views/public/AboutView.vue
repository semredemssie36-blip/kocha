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
  HeartHandshake
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const route = useRoute()
const router = useRouter()

const tabs = [
  { id: 'about', label_en: 'About Kombolcha', label_am: 'ስለ ከተማዋ', label_om: 'Waa\'ee Magaalaa' },
  { id: 'history', label_en: 'Historical Heritage', label_am: 'ታሪክና አመጣጥ', label_om: 'Seenaa' },
  { id: 'vision', label_en: 'Vision & Mission', label_am: 'ራዕይ እና ተልዕኮ', label_om: 'Mul\'ata fi Ergama' },
  { id: 'people', label_en: 'People & Culture', label_am: 'ህዝብና ባህል', label_om: 'Ummata fi Aadaa' },
  { id: 'geography', label_en: 'Geography & Climate', label_am: 'መልክዓ ምድርና አየር ንብረት', label_om: 'Teessuma Lafaa' },
  { id: 'administration', label_en: 'Governance Charter', label_am: 'የአስተዳደር መዋቅር', label_om: 'Caasaa Bulchiinsaa' }
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
                <span class="font-black text-slate-900">1928 E.C / 1936 G.C</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Elevation:</span>
                <span class="font-black text-slate-900 font-mono">1,842 meters</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Sub-Cities:</span>
                <span class="font-black text-slate-900">4 Autonomous</span>
              </li>
              <li class="flex justify-between border-b border-slate-100 pb-2">
                <span class="text-slate-400 font-bold">Sector Bureaus:</span>
                <span class="font-black text-slate-900">22 Directorates</span>
              </li>
              <li class="flex justify-between">
                <span class="text-slate-400 font-bold">Distance from A.A:</span>
                <span class="font-black text-slate-900 font-mono">375 km North</span>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Right Main Panel Content (9 cols) -->
        <main class="lg:col-span-9 bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-12 shadow-sm">
          
          <!-- TAB 1: ABOUT -->
          <div v-if="activeTab === 'about'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'ስለ ኮምቦልቻ ከተማ' : 'About Kombolcha' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Official Municipal Heritage, Origin, and Overview</p>
            </div>

            <div class="prose max-w-none text-slate-700 leading-relaxed space-y-4">
              <h3 class="text-xl font-black text-slate-900 border-l-4 border-blue-700 pl-4">
                የከተማዋ ስያሜ እና አመጣጥ (Origin of the Name)
              </h3>
              <p class="text-justify text-base">
                የኮምቦልቻ ከተማ በ1928 በጣሊያን ወረራ ወቅት እንደተቆረቆረች የሚነገር ሲሆን ስያሜዋንም አስመልክቶ ቃሉ የመጣው ከአካባቢው የተፈጥሮ ገጽታ እና ከነዋሪዎች አጠራር ጋር ተያይዞ መሆኑ ይገለጻል። ከተማዋ በአማራ ብሔራዊ ክልላዊ መንግሥት በደቡብ ወሎ ዞን የምትገኝ ሲሆን ከአዲስ አበባ በ375 ኪሎ ሜትር ርቀት ላይ በስልታዊ የንግድ መተላለፊያ ላይ አርፋለች።
              </p>
              <p class="text-justify text-base">
                Kombolcha is a bustling commercial and industrial powerhouse strategically nestled in the South Wollo zone of Amhara Region. Positioned at the critical junction connecting Addis Ababa with Tigray, Afar, and the international maritime port corridor of Djibouti, Kombolcha has long functioned as Ethiopia's northeastern economic gateway.
              </p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                  <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <Building2 class="w-4 h-4 text-blue-700" />
                    <span>Industrial Identity</span>
                  </h4>
                  <p class="text-xs text-slate-600">Home to Kombolcha Industrial Park, modern textile mills, brewery plants, and steel fabrication centers.</p>
                </div>
                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                  <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-2">
                    <Compass class="w-4 h-4 text-emerald-600" />
                    <span>Multimodal Logistics Hub</span>
                  </h4>
                  <p class="text-xs text-slate-600">Anchored by the Kombolcha Dry Port, airport corridor, and Ethio-Djibouti Railway line connection.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: HISTORY -->
          <div v-else-if="activeTab === 'history'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ታሪክ' : 'Historical Heritage' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Foundation, Evolution, and Modern Development</p>
            </div>
            <div class="text-slate-700 leading-relaxed space-y-4 text-base">
              <p>
                Kombolcha's historical evolution began as a strategic crossroad in the 1930s. The town expanded rapidly due to its location at the intersection of historical trade caravans connecting Northern Ethiopia to the Red Sea coast.
              </p>
              <p>
                During the mid-20th century, the opening of major textile and manufacturing plants transformed Kombolcha into one of Ethiopia's premier industrial epicenters, earning it the moniker "The Industrial Hearth of Wollo."
              </p>
            </div>
          </div>

          <!-- TAB 3: VISION & MISSION -->
          <div v-else-if="activeTab === 'vision'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'ራዕይ፣ ተልዕኮ እና እሴቶች' : 'Vision, Mission & Core Values' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Guiding Principles of Kombolcha Municipal Administration</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="p-6 rounded-2xl bg-blue-50/60 border border-blue-200">
                <h3 class="text-lg font-black text-blue-900 mb-2">Our Vision</h3>
                <p class="text-sm text-slate-700 leading-relaxed">
                  To become an internationally competitive industrial, logistics, and tourism hub in Eastern Africa by 2035, ensuring sustainable prosperity and high quality of life for all residents.
                </p>
              </div>
              <div class="p-6 rounded-2xl bg-emerald-50/60 border border-emerald-200">
                <h3 class="text-lg font-black text-emerald-900 mb-2">Our Mission</h3>
                <p class="text-sm text-slate-700 leading-relaxed">
                  Delivering fast, equitable, and transparent public services, fostering investor confidence, upgrading modern infrastructure, and protecting our natural heritage.
                </p>
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
          <div v-else-if="activeTab === 'geography'" class="space-y-6">
            <div class="border-b border-slate-100 pb-4 mb-6">
              <h2 class="text-2xl sm:text-3xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'መልክዓ ምድርና አየር ንብረት' : 'Geography & Climate' }}
              </h2>
              <p class="text-xs text-slate-400 mt-1">Topography, Mount Yegof, and Weather Characteristics</p>
            </div>
            <div class="text-slate-700 leading-relaxed space-y-4 text-base">
              <p>
                Nestled at an average altitude of 1,842 meters above sea level, Kombolcha enjoys a pleasant subtropical highland climate (Woina Dega) characterized by moderate temperatures year-round ranging from 15°C to 28°C.
              </p>
              <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200">
                <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                  <Mountain class="w-5 h-5 text-emerald-600" />
                  <span>Mount Yegof Ecological Sanctuary</span>
                </h4>
                <p class="text-xs sm:text-sm text-slate-600">Rising to over 2,600 meters directly overlooking the city, Mount Yegof serves as a vital watershed catchment, ecological barrier, and protected indigenous forest sanctuary.</p>
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
              <p>
                Kombolcha is chartered under the Amhara National Regional State Municipal Proclamation. Governance is spearheaded by the Executive Mayor, assisted by the Mayoral Cabinet and accountable to the elected City Council.
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <router-link to="/government/sub-cities" class="p-5 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-all block">
                  <h4 class="font-bold text-blue-900 mb-1">4 Sub-Cities</h4>
                  <p class="text-xs text-slate-500">Explore Abishager, Borkena, Hasenager, and Biraro administrative zones.</p>
                </router-link>
                <router-link to="/government/offices" class="p-5 rounded-2xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-all block">
                  <h4 class="font-bold text-blue-900 mb-1">22 Sector Bureaus</h4>
                  <p class="text-xs text-slate-500">Access direct room numbers and phone extensions across municipal bureaus.</p>
                </router-link>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>
