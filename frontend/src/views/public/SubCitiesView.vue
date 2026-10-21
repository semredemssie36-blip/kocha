<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  MapPin, 
  Phone, 
  Mail, 
  UserCheck, 
  ArrowRight, 
  Building2, 
  Landmark, 
  ShieldCheck, 
  Users, 
  ChevronRight,
  ExternalLink,
  Layers,
  Sparkles
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const subCities = ref([])
const loading = ref(true)

async function fetchSubCities() {
  loading.value = true
  try {
    const res = await api.get('/sub-cities')
    subCities.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load sub-cities:', err)
  } finally {
    loading.value = false
  }
}

function getSubCityName(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.name_am || city.name_en
  if (lang === 'om') return city.name_om || city.name_en
  return city.name_en || city.name_am
}

function getChiefExecutive(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.chief_executive_am || city.chief_executive_en || 'የክፍለ ከተማው ዋና ስራ አስፈፃሚ'
  if (lang === 'om') return city.chief_executive_om || city.chief_executive_en || 'Hoogganaa Olaanaa'
  return city.chief_executive_en || city.chief_executive_am || 'Chief Executive Officer'
}

function getAddress(city) {
  const lang = langStore.currentLang
  if (lang === 'am') return city.office_address_am || city.office_address_en || 'ኮምቦልቻ፣ ኢትዮጵያ'
  if (lang === 'om') return city.office_address_om || city.office_address_en || 'Kombolcha, Itoophiyaa'
  return city.office_address_en || city.office_address_am || 'Kombolcha, Ethiopia'
}

onMounted(() => {
  fetchSubCities()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/city_image_2.jpg" alt="Sub-Cities" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ክፍለ ከተሞች አስተዳደር' : 'Metropolitan Sub-City Administrations' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'የኮምቦልቻ ከተማ አስተዳደር ህዝባዊ አገልግሎቶችን ለነዋሪዎች በቅርበት ለማድረስ በ 4 ራስ ገዝ ክፍለ ከተሞች (አቢሻገር፣ ቦርከና፣ ሀሰናገር፣ ቢራሮ) እና በ 12 ቀበሌዎች የተዋቀረ ነው።'
            : 'Kombolcha is decentralized into 4 metropolitan sub-cities (Abishager, Borkena, Hasenager, Biraro) delivering localized civil registrations, business licensing, community policing, and infrastructure maintenance.'
          }}
        </p>

        <!-- Sub-City Overview Analytics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Autonomous Sub-Cities</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ subCities.length }} Zones</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Subordinate Kebeles</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-400">12 Kebeles</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Decentralized Services</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">IDs • Vital • Permits</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Administration Model</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Chartered Metro</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      
      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div v-for="n in 4" :key="n" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs animate-pulse h-64"></div>
      </div>

      <!-- Sub-Cities Grid -->
      <div v-else-if="subCities.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div 
          v-for="city in subCities" 
          :key="city.id" 
          class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xs hover:border-emerald-500 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
        >
          <!-- Top Accent Stripe -->
          <div class="absolute top-0 left-0 right-0 h-1.5 bg-[#1C398E]"></div>

          <div>
            <!-- Zone Tag Bar -->
            <div class="flex items-center justify-between mb-4 pt-1">
              <span class="px-3 py-1 rounded-full bg-slate-900 text-amber-300 text-xs font-mono font-black uppercase tracking-wider shadow-2xs">
                Zone #0{{ city.order || city.id }}
              </span>
              <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Active Sub-City
              </span>
            </div>

            <!-- Sub-City Title -->
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-blue-900 transition-colors mb-4">
              {{ getSubCityName(city) }}
            </h2>

            <!-- Contact & Executive Dossier -->
            <div class="space-y-3.5 pt-4 border-t border-slate-100 text-xs sm:text-sm text-slate-600">
              
              <!-- Chief Executive -->
              <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200/80">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0 font-bold">
                  <UserCheck class="w-5 h-5 text-emerald-700" />
                </div>
                <div>
                  <span class="text-[10px] text-slate-400 font-black uppercase tracking-wider block">Chief Executive Officer</span>
                  <strong class="text-slate-900 font-black">{{ getChiefExecutive(city) }}</strong>
                </div>
              </div>

              <!-- Address -->
              <div class="flex items-start gap-3 px-1">
                <MapPin class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" />
                <span>{{ getAddress(city) }}</span>
              </div>

              <!-- Phone Hotline -->
              <div class="flex items-center gap-3 px-1">
                <Phone class="w-4 h-4 text-emerald-600 shrink-0" />
                <a :href="'tel:' + city.phone" class="font-mono font-bold text-blue-700 hover:underline">
                  {{ city.phone || '+251 33 551 0022' }}
                </a>
              </div>

              <!-- Email -->
              <div class="flex items-center gap-3 px-1">
                <Mail class="w-4 h-4 text-sky-600 shrink-0" />
                <span class="font-mono text-slate-600">{{ city.email || `${city.slug || 'subcity'}@kombolchacity.gov.et` }}</span>
              </div>
            </div>
          </div>

          <!-- Bottom Action: Sector Offices within this Sub-City -->
          <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
            <router-link 
              :to="'/government/offices?sub_city_id=' + city.id" 
              class="inline-flex items-center gap-2 text-xs font-black text-blue-900 hover:text-emerald-700 transition-colors uppercase tracking-wider"
            >
              <span>View Departmental Bureaus in {{ getSubCityName(city) }}</span>
              <ArrowRight class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" />
            </router-link>
            <span class="text-xs text-slate-400 font-mono">Directory</span>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Landmark class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No sub-cities found</h3>
        <p class="text-xs text-slate-500 mt-1">Sub-city charter records will appear here once registered.</p>
      </div>
    </div>
  </div>
</template>
