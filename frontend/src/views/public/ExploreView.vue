<script setup>
import { ref, computed } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { 
  Compass, 
  MapPin, 
  Mountain, 
  Factory, 
  Trees, 
  ShoppingBag, 
  Truck, 
  Plane,
  Building,
  CheckCircle2
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const activeCategory = ref('all')

const categories = [
  { key: 'all', label_en: 'All Destinations', label_am: 'ሁሉንም መዳረሻዎች', label_om: 'Bakkeewwan Hunda' },
  { key: 'nature', label_en: 'Nature & Tourism', label_am: 'የተፈጥሮና ቱሪዝም መስህቦች', label_om: 'Uumamaa fi Tuurizimii' },
  { key: 'industry', label_en: 'Industry & Investment', label_am: 'ኢንዱስትሪና ኢንቨስትመንት', label_om: 'Indaastirii fi Invastimantii' },
  { key: 'culture', label_en: 'Culture & Commerce', label_am: 'ባህልና ንግድ', label_om: 'Aadaa fi Daldala' },
]

const destinations = [
  {
    id: 1,
    category: 'nature',
    title_en: 'Mount Yegof Nature Reserve & Forest',
    title_am: 'የይጎፍ ተራራ የተፈጥሮ ደንና የመስህብ ስፍራ',
    title_om: 'Tulluu Yagoof - Bosona Uumamaa fi Bakka Hawwataa',
    location: 'Bordering Kombolcha South Corridor',
    summary_en: 'Majestic mountain peak towering at 3,014 meters with dense indigenous juniper forests, hiking trails, and panoramic vistas of the entire Borkena valley.',
    summary_am: 'በ 3,014 ሜትር ከፍታ ላይ የሚገኝ፣ አገር በቀል ዛፎችን የያዘ፣ ለተራራ መውጣትና ለሽርሽር ምቹ የሆነ ውብ ተፈጥሯዊ ገጽታ።',
    icon: Mountain
  },
  {
    id: 2,
    category: 'industry',
    title_en: 'Kombolcha Industrial Park (KIP)',
    title_am: 'የኮምቦልቻ ኢንዱስትሪ ፓርክ',
    title_om: 'Paarkii Indaastirii Kombolchaa',
    location: 'Biraro Sub-City, Industrial Corridor',
    summary_en: 'A premier 75-hectare eco-industrial hub housing leading global textile, apparel, and manufacturing export enterprises with plug-and-play sheds.',
    summary_am: 'በ 75 ሄክታር መሬት ላይ የተገነባ፣ ለውጭ ገበያ የሚላኩ የጨርቃ ጨርቅና የማኑፋክቸሪንግ ምርቶችን የሚያመርቱ አለም አቀፍ ፋብሪካዎች ስብስብ።',
    icon: Factory
  },
  {
    id: 3,
    category: 'nature',
    title_en: 'Borkena Green Riverfront Park',
    title_am: 'የቦርከና ወንዝ አረንጓዴ መዝናኛ ፓርክ',
    title_om: 'Paarkii Bashannanaa Qarqara Laga Borkenaa',
    location: 'Borkena Sub-City Promenade',
    summary_en: 'Urban revitalization green belt featuring scenic riverfront walkways, recreational zones, sports fields, and family-friendly picnic gardens.',
    summary_am: 'በቦርከና ወንዝ ዳርቻ የተገነባ ዘመናዊ የመዝናኛ ፓርክ፣ የእግር ጉዞ መንገዶችና የህፃናት መጫወቻ ስፍራዎች።',
    icon: Trees
  },
  {
    id: 4,
    category: 'culture',
    title_en: 'Wollo Cultural Handicraft & Spice Bazaar',
    title_am: 'የወሎ ባህላዊ የእጅ ጥበብና ቅመማ ቅመም ገበያ',
    title_om: 'Gabaa Aadaa fi Ogummaa Harka Walloo',
    location: 'Central Market Area',
    summary_en: 'Vibrant local bazaar showcasing authentic Wollo cultural handloom dresses, leather crafts, organic honey, and aromatic mountain herbs.',
    summary_am: 'የወሎ ባህላዊ የጥበብ አልባሳት፣ የእጅ ስራዎች፣ የተፈጥሮ ማር እና ቅመማ ቅመሞች የሚቀርቡበት ደማቅ የከተማዋ ገበያ።',
    icon: ShoppingBag
  },
  {
    id: 5,
    category: 'industry',
    title_en: 'Kombolcha Multi-Modal Dry Port & Logistics Terminal',
    title_am: 'የኮምቦልቻ ደረቅ ወደብና የሎጂስቲክስ ማዕከል',
    title_om: 'Buufata Gogaa fi Wiirtuu Lojistikii Kombolchaa',
    location: 'Addis Ababa Highway Exit',
    summary_en: 'Crucial customs clearance depot connecting northern and central Ethiopia directly with the ports of Djibouti via railway and road freight.',
    summary_am: 'ከጅቡቲ ወደብ ጋር በቀጥታ የተሳሰረ፣ የጉምሩክ ክሊራንስና የኮንቴነር ማስተናገጃ ዘመናዊ ደረቅ ወደብ።',
    icon: Truck
  },
  {
    id: 6,
    category: 'industry',
    title_en: 'Kombolcha Domestic Airport (DSE/HADC)',
    title_am: 'የኮምቦልቻ አየር ማረፊያ',
    title_om: 'Buufata Xiyyaaraa Kombolchaa',
    location: 'Airfield Road, 5 km from Downtown',
    summary_en: 'Daily Ethiopian Airlines passenger and cargo flights connecting Kombolcha directly with Addis Ababa, Mekelle, and regional capitals.',
    summary_am: 'የኢትዮጵያ አየር መንገድ በየቀኑ በረራዎችን የሚያደርግበት፣ ከተማዋን ከመላው ሀገሪቱ ጋር የሚያስተሳስር ዘመናዊ አየር ማረፊያ።',
    icon: Plane
  }
]

const filteredDestinations = computed(() => {
  if (activeCategory.value === 'all') return destinations
  return destinations.filter(d => d.category === activeCategory.value)
})

const getTitle = (d) => {
  const lang = langStore.currentLang
  if (lang === 'am') return d.title_am
  if (lang === 'om') return d.title_om || d.title_en
  return d.title_en
}

const getSummary = (d) => {
  const lang = langStore.currentLang
  if (lang === 'am') return d.summary_am
  return d.summary_en
}
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Compass class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'ኮምቦልቻን ይጎብኙ' : (langStore.currentLang === 'om' ? 'Kombolchaa Daawwadhaa' : 'Explore Kombolcha City') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'የቱሪዝም መስህቦችና የኢንቨስትመንት መዳረሻዎች' : (langStore.currentLang === 'om' ? 'Bakkeewwan Hawwata Tuurizimii fi Invastimantii' : 'Tourism, Culture & Investment Spotlight') }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'ከግርማ ሞገሱ የይጎፍ ተራራ እስከ አለም አቀፉ የኢንዱስትሪ ፓርክ ድረስ የከተማዋን ድንቅ ገጽታዎች ይመልከቱ።' : 'Discover the scenic wonders of Mount Yegof, pristine parks, Wollo cultural crafts, and industrial growth engines.' }}
        </p>
      </div>
    </div>

    <!-- Category Filter Bar -->
    <div class="flex items-center gap-2 mb-10 overflow-x-auto pb-2 scrollbar-none">
      <button 
        v-for="cat in categories" 
        :key="cat.key" 
        @click="activeCategory = cat.key"
        :class="[
          'px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer whitespace-nowrap',
          activeCategory === cat.key 
            ? 'bg-blue-700 text-white shadow-2xs' 
            : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'
        ]"
      >
        {{ langStore.currentLang === 'am' ? cat.label_am : (langStore.currentLang === 'om' ? cat.label_om : cat.label_en) }}
      </button>
    </div>

    <!-- Destinations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div 
        v-for="item in filteredDestinations" 
        :key="item.id"
        class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group"
      >
        <div>
          <!-- Header Visual / Icon representation: Clean Light Bordered Canvas -->
          <div class="h-44 bg-slate-50 p-6 flex flex-col justify-between text-slate-800 border-b border-slate-200 relative">
            <div class="flex items-center justify-between relative z-10">
              <span class="px-2.5 py-1 rounded bg-white text-slate-700 text-[11px] font-bold uppercase tracking-wider border border-slate-200">
                {{ item.category }}
              </span>
              <div class="p-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
                <component :is="item.icon" class="w-5 h-5" />
              </div>
            </div>

            <div class="flex items-center gap-1.5 text-xs text-slate-600 font-semibold relative z-10">
              <MapPin class="w-3.5 h-3.5 text-blue-700" />
              <span>{{ item.location }}</span>
            </div>
          </div>

          <!-- Body Content -->
          <div class="p-6">
            <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors mb-2 leading-snug">
              {{ getTitle(item) }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {{ getSummary(item) }}
            </p>
          </div>
        </div>

        <div class="px-6 pb-6 pt-2 border-t border-slate-50 flex items-center justify-between">
          <span class="text-[11px] font-bold text-blue-600 flex items-center gap-1">
            <CheckCircle2 class="w-3.5 h-3.5" />
            {{ langStore.currentLang === 'am' ? 'ክፍት የመጎብኛ ስፍራ' : 'Open Destination' }}
          </span>
          <router-link 
            to="/contact" 
            class="text-xs font-bold text-slate-700 hover:text-blue-600 transition-colors"
          >
            {{ langStore.currentLang === 'am' ? 'ተጨማሪ መረጃ' : 'Inquire Details →' }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
