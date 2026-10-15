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
  Layers
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const route = useRoute()
const router = useRouter()

const tabs = [
  { id: 'about', label_en: 'About', label_am: 'ስለ ከተማዋ', label_om: 'Waa\'ee Magaalaa' },
  { id: 'history', label_en: 'History', label_am: 'ታሪክ', label_om: 'Seenaa' },
  { id: 'vision', label_en: 'Vision and Mission', label_am: 'ራዕይ እና ተልዕኮ', label_om: 'Mul\'ata fi Ergama' },
  { id: 'people', label_en: 'People & Culture', label_am: 'ህዝብና ባህል', label_om: 'Ummata fi Aadaa' },
  { id: 'geography', label_en: 'Geography & Climate', label_am: 'መልክዓ ምድር', label_om: 'Teessuma Lafaa' },
  { id: 'administration', label_en: 'Administration', label_am: 'የአስተዳደር መዋቅር', label_om: 'Caasaa Bulchiinsaa' }
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
  <div class="w-full bg-white min-h-screen">
    <!-- Breadcrumb Bar (Matching Old Government Website) -->
    <div class="bg-slate-50 border-b border-slate-200 py-3 px-4 sm:px-8 xl:px-12 text-xs text-slate-500">
      <div class="w-full max-w-[1700px] mx-auto flex items-center gap-2">
        <router-link to="/" class="hover:text-[#0b3c5d] font-medium">Home</router-link>
        <span>/</span>
        <router-link to="/about" class="hover:text-[#0b3c5d] font-medium">About</router-link>
        <span>/</span>
        <span class="text-[#0b3c5d] font-bold">{{ getTabLabel(currentTabObj) }}</span>
      </div>
    </div>

    <!-- Main Content Container with Left Sidebar (Exact Old Site Layout) -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8 lg:py-12">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        <!-- Left Sidebar Navigation -->
        <aside class="lg:col-span-3">
          <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-2xs sticky top-28">
            <div class="bg-slate-100 px-4 py-3 border-b border-slate-200 font-bold text-slate-800 text-sm flex items-center justify-between">
              <span>{{ langStore.currentLang === 'am' ? 'የይዘት ማውጫ' : 'Navigation Menu' }}</span>
              <Layers class="w-4 h-4 text-[#0b3c5d]" />
            </div>
            <nav class="flex flex-col divide-y divide-slate-100 text-sm">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="selectTab(tab.id)"
                :class="[
                  'w-full text-left px-4 py-3.5 font-semibold transition-all flex items-center justify-between cursor-pointer',
                  activeTab === tab.id
                    ? 'bg-[#0b3c5d] text-white shadow-inner font-bold'
                    : 'text-slate-700 hover:bg-slate-50 hover:text-[#0b3c5d]'
                ]"
              >
                <span>{{ getTabLabel(tab) }}</span>
                <ChevronRight class="w-4 h-4 opacity-75" :class="{ 'text-white': activeTab === tab.id, 'text-slate-400': activeTab !== tab.id }" />
              </button>
            </nav>
          </div>

          <!-- Quick Fast Facts Widget -->
          <div class="mt-6 bg-slate-50 border border-slate-200 rounded-lg p-5 text-xs text-slate-600">
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider mb-3 flex items-center gap-2">
              <Landmark class="w-4 h-4 text-[#0b3c5d]" />
              {{ langStore.currentLang === 'am' ? 'አጫጭር መረጃዎች' : 'Quick City Facts' }}
            </h4>
            <ul class="space-y-2.5">
              <li class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Foundation:</span>
                <span class="font-bold text-slate-800">1928 E.C / 1936 G.C</span>
              </li>
              <li class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Elevation:</span>
                <span class="font-bold text-slate-800">1,842 meters</span>
              </li>
              <li class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Sub-Cities:</span>
                <span class="font-bold text-slate-800">4 Divisions</span>
              </li>
              <li class="flex justify-between border-b border-slate-200 pb-1.5">
                <span class="text-slate-500">Sector Offices:</span>
                <span class="font-bold text-slate-800">22 Municipal Directorates</span>
              </li>
              <li class="flex justify-between">
                <span class="text-slate-500">Distance from A.A:</span>
                <span class="font-bold text-slate-800">375 km North</span>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Right Main Panel Content -->
        <main class="lg:col-span-9 bg-white">
          <!-- TAB 1: ABOUT (Exact Amharic title & Italian history from screenshot) -->
          <div v-if="activeTab === 'about'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'ስለ ኮምቦልቻ ከተማ' : 'About Kombolcha' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">Official Municipal Heritage, Origin, and Overview</p>
            </div>

            <div class="prose max-w-none text-slate-700 leading-relaxed space-y-4">
              <h3 class="text-xl font-bold text-slate-900 border-l-4 border-[#0b3c5d] pl-3">
                የከተማዋ ስያሜ እና አመጣጥ (Origin of the Name)
              </h3>
              <p class="text-justify text-base">
                የኮምቦልቻ ከተማ በ1928 በጣሊያን ወረራ ወቅት እንደተቆረቆረች የሚነገር ሲሆን ስያሜዋንም አስመልክቶ ቃሉ የመጣው ከአካባቢው የተፈጥሮ ገጽታ እና ከነዋሪዎች አጠራር ጋር ተያይዞ መሆኑ ይገለጻል። ከተማዋ በአማራ ብሔራዊ ክልላዊ መንግሥት በደቡብ ወሎ ዞን የምትገኝ ሲሆን ከአዲስ አበባ በ375 ኪሎ ሜትር ርቀት ላይ በስልታዊ የንግድ መተላለፊያ ላይ አርፋለች።
              </p>
              <p class="text-justify text-base">
                Kombolcha is a bustling commercial and industrial powerhouse strategically nestled in the South Wollo zone of Amhara Region. Positioned at the critical junction connecting Addis Ababa with Tigray, Afar, and the international maritime port corridor of Djibouti, Kombolcha has long functioned as Ethiopia's northeastern economic gateway.
              </p>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                  <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                    <Building2 class="w-4 h-4 text-[#0b3c5d]" />
                    Industrial Distinction
                  </h4>
                  <p class="text-xs text-slate-600 leading-relaxed">
                    Home to Ethiopia's state-of-the-art Kombolcha Industrial Park, specialized textile mills, brewery complexes, steel fabrication plants, and the region's primary multi-modal Dry Port logistics terminal.
                  </p>
                </div>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                  <h4 class="font-bold text-slate-900 mb-2 flex items-center gap-2">
                    <Compass class="w-4 h-4 text-[#0b3c5d]" />
                    Transport & Connectivity
                  </h4>
                  <p class="text-xs text-slate-600 leading-relaxed">
                    Accessible via the modern Kombolcha Domestic Airport with daily scheduled passenger and cargo flights, express arterial highways, and planned regional railway links.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 2: HISTORY -->
          <div v-else-if="activeTab === 'history'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ታሪክ' : 'Historical Genesis of Kombolcha' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">From a crossroad transit outpost to an industrial metropolis</p>
            </div>

            <div class="space-y-6 text-slate-700 leading-relaxed">
              <div class="flex gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-[#0b3c5d] font-bold flex items-center justify-center shrink-0 border border-blue-200">
                  1928
                </div>
                <div>
                  <h4 class="text-base font-bold text-slate-900">Establishment During Italian Occupation (1928 E.C / 1936 G.C)</h4>
                  <p class="text-sm text-slate-600 mt-1 text-justify">
                    During the Italian occupation of Ethiopia, the strategic topography between Dessie and the lowlands was selected as a strategic transit and military encampment. Roads were constructed across the challenging mountainous corridors connecting to Asmara and Addis Ababa.
                  </p>
                </div>
              </div>

              <div class="flex gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-[#0b3c5d] font-bold flex items-center justify-center shrink-0 border border-blue-200">
                  1960s
                </div>
                <div>
                  <h4 class="text-base font-bold text-slate-900">The Rise of Manufacturing Hub (1960s - 1980s)</h4>
                  <p class="text-sm text-slate-600 mt-1 text-justify">
                    Kombolcha emerged as a premier manufacturing destination in Ethiopia with the inauguration of the Kombolcha Textile Share Company (KTSC), meat processing factories, tanneries, and metallurgical industries, providing employment to tens of thousands across Wollo.
                  </p>
                </div>
              </div>

              <div class="flex gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-[#0b3c5d] font-bold flex items-center justify-center shrink-0 border border-blue-200">
                  2015+
                </div>
                <div>
                  <h4 class="text-base font-bold text-slate-900">Modern Eco-Industrial Park & Metropolitan Evolution</h4>
                  <p class="text-sm text-slate-600 mt-1 text-justify">
                    In 2017, the federal and regional governments inaugurated the Kombolcha Eco-Industrial Park, alongside expanding dry port freight customs terminals and airport infrastructure, firmly consolidating Kombolcha's status as a 21st-century economic titan.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 3: VISION AND MISSION -->
          <div v-else-if="activeTab === 'vision'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'ራዕይ እና ተልዕኮ' : 'Vision, Mission & Core Values' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">Guiding principles of the Kombolcha City Administration</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-slate-50 border-2 border-blue-100 rounded-xl p-6">
                <div class="flex items-center gap-2 text-xs font-bold text-[#0b3c5d] uppercase tracking-wider mb-2">
                  <Compass class="w-5 h-5 text-[#0b3c5d]" />
                  <span>{{ langStore.currentLang === 'am' ? 'የከተማው ራዕይ' : 'Municipal Vision' }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-3">Our 2030 Horizon</h3>
                <p class="text-sm text-slate-700 leading-relaxed">
                  "በ 2030 ኮምቦልቻ በኢትዮጵያ ውስጥ በኢንዱስትሪ ማኑፋክቸሪንግ፣ በሎጂስቲክስ እና በዘመናዊ ዲጂታል አገልግሎት አሰጣጥ ግንባር ቀደም፣ ለነዋሪዎቿ ምቹና አረንጓዴ ሜትሮፖሊታን ከተማ ሆና ማየት።"
                </p>
                <p class="text-xs text-slate-500 mt-3 italic">
                  To be Ethiopia's premier center of industrial excellence, green commerce, and accountable public service by 2030.
                </p>
              </div>

              <div class="bg-slate-50 border-2 border-emerald-100 rounded-xl p-6">
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">
                  <TrendingUp class="w-5 h-5 text-emerald-700" />
                  <span>{{ langStore.currentLang === 'am' ? 'የከተማው ተልዕኮ' : 'Municipal Mission' }}</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-3">Core Operational Purpose</h3>
                <p class="text-sm text-slate-700 leading-relaxed">
                  "የነዋሪዎችን የኑሮ ደረጃ የሚያሻሽሉ ጥራት ያላቸው መሰረተ ልማቶችን መገንባት፣ ኢንቨስትመንትን ማበረታታት እና ፍትሃዊ፣ ፈጣንና ከአድልዎ የጸዳ የመንግስት አገልግሎት ማረጋገጥ።"
                </p>
                <p class="text-xs text-slate-500 mt-3 italic">
                  Cultivating resilient infrastructure, attracting high-yield investments, and delivering transparent, corruption-free public administration.
                </p>
              </div>
            </div>
          </div>

          <!-- TAB 4: PEOPLE -->
          <div v-else-if="activeTab === 'people'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'ህዝብና ባህል' : 'People, Demographics & Cultural Harmony' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">A vibrant tapestry of Wollo warmth and peaceful coexistence</p>
            </div>

            <div class="prose max-w-none text-slate-700 leading-relaxed space-y-4">
              <p class="text-base text-justify">
                Kombolcha is renowned across Ethiopia as a beacon of fraternal coexistence, religious harmony, and genuine Wollo hospitality. For generations, Orthodox Christians, Muslims, and Protestant believers have celebrated holidays, shared meals, and built communities side-by-side with profound respect.
              </p>
              <p class="text-base text-justify">
                The current estimated population exceeds 220,000 residents, driven by young, dynamic workforce inflows seeking careers in the manufacturing parks, university institutions, commercial markets, and transport logistics firms.
              </p>

              <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 mt-4">
                <h4 class="font-bold text-slate-900 mb-2">Key Cultural Milestones</h4>
                <ul class="list-disc pl-5 space-y-1 text-sm text-slate-600">
                  <li>Celebration of Meskel, Eid al-Fitr, Timket, and Eid al-Adha as unified community events.</li>
                  <li>Traditional Wollo music, poetic debate (Qene), and culinary traditions.</li>
                  <li>Active participation of youth and women associations in municipal civic deliberations.</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- TAB 5: GEOGRAPHY -->
          <div v-else-if="activeTab === 'geography'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'መልክዓ ምድርና የአየር ንብረት' : 'Geography, Climate & Watersheds' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">Surrounded by Mount Yegof and the fertile Borkena basin</p>
            </div>

            <div class="prose max-w-none text-slate-700 leading-relaxed space-y-4">
              <p class="text-base text-justify">
                Kombolcha is situated at a latitude of 11°05′N and longitude of 39°44′E, with an elevation ranging from 1,840 to 1,900 meters above sea level. It enjoys a subtropical highland climate with moderate temperatures throughout the year (averaging 18°C to 28°C), making it ideal for urban living and heavy industry.
              </p>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg text-center">
                  <div class="text-xl font-bold text-[#0b3c5d]">Mount Yegof</div>
                  <p class="text-xs text-slate-600 mt-1">Majestic forested mountain ridge guarding the city's south and protecting vital water aquifers.</p>
                </div>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg text-center">
                  <div class="text-xl font-bold text-[#0b3c5d]">Borkena River</div>
                  <p class="text-xs text-slate-600 mt-1">Primary hydrological lifeline supplying valley irrigation and municipal greenery initiatives.</p>
                </div>
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg text-center">
                  <div class="text-xl font-bold text-[#0b3c5d]">Woina Dega</div>
                  <p class="text-xs text-slate-600 mt-1">Temperate, invigorating climate with distinct bi-modal rainfall seasons (Belg and Kiremt).</p>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB 6: ADMINISTRATION -->
          <div v-else-if="activeTab === 'administration'" class="space-y-6">
            <div class="border-b border-slate-200 pb-4">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b3c5d]">
                {{ langStore.currentLang === 'am' ? 'የከተማው የአስተዳደር መዋቅር' : 'Municipal Governance Structure' }}
              </h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">Administrative hierarchy, Sub-Cities, and Directorates</p>
            </div>

            <div class="space-y-4 text-slate-700 leading-relaxed">
              <p class="text-base text-justify">
                Kombolcha operates under a metropolitan municipal administration model chartered under the Amhara National Regional State. Executive leadership is spearheaded by the Mayor and City Cabinet, accountable to the democratically elected City Council.
              </p>

              <div class="border border-slate-200 rounded-lg overflow-hidden">
                <div class="bg-slate-100 px-4 py-2.5 font-bold text-slate-800 text-sm">
                  The Four Sub-City Administrations (ክፍለ ከተሞች)
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-200 text-sm">
                  <div class="p-4 space-y-1">
                    <div class="font-bold text-[#0b3c5d]">1. Abishager Sub-City (አቢሻገር)</div>
                    <p class="text-xs text-slate-600">Central municipal sector, historical commercial avenues, and central administrative buildings.</p>
                  </div>
                  <div class="p-4 space-y-1">
                    <div class="font-bold text-[#0b3c5d]">2. Borkena Sub-City (ቦርከና)</div>
                    <p class="text-xs text-slate-600">Flanking the river valley, modern residential extensions, and agricultural green belts.</p>
                  </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-slate-200 text-sm border-t border-slate-200">
                  <div class="p-4 space-y-1">
                    <div class="font-bold text-[#0b3c5d]">3. Hasenager Sub-City (ሀሰናገር)</div>
                    <p class="text-xs text-slate-600">Dynamic logistics district, transportation centers, and highway commercial activity.</p>
                  </div>
                  <div class="p-4 space-y-1">
                    <div class="font-bold text-[#0b3c5d]">4. Biraro Sub-City (ቢራሮ)</div>
                    <p class="text-xs text-slate-600">Industrial park zone, dry port facilities, airport access corridors, and new housing developments.</p>
                  </div>
                </div>
              </div>

              <div class="pt-4 flex gap-4">
                <router-link to="/government/offices" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0b3c5d] text-white rounded-lg text-xs font-bold hover:bg-[#0c4a6e] transition-colors">
                  <Layers class="w-4 h-4" />
                  View All 22 Sector Offices Directory
                </router-link>
                <router-link to="/government/mayor" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-50 transition-colors">
                  <Landmark class="w-4 h-4" />
                  Mayor's Office Executive Profile
                </router-link>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>
