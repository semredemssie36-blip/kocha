<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import { useSiteStore } from '@/stores/site'
import { api } from '@/services/api'
import { 
  Send, 
  Phone, 
  Mail, 
  MapPin, 
  Clock, 
  CheckCircle, 
  AlertCircle, 
  Building2, 
  ShieldCheck, 
  PhoneCall, 
  Navigation, 
  ExternalLink, 
  Copy, 
  Check, 
  Landmark, 
  Sparkles, 
  HelpCircle, 
  Droplets, 
  Zap, 
  Flame, 
  Activity, 
  ShieldAlert, 
  ChevronRight, 
  Search, 
  User, 
  GraduationCap, 
  Share2, 
  FileText 
} from 'lucide-vue-next'

const route = useRoute()
const langStore = useLanguageStore()
const siteStore = useSiteStore()

const settings = computed(() => siteStore.siteSettings)
const copiedCoords = ref(false)

const offices = ref([])
const officesLoading = ref(true)
const officeSearchQuery = ref('')

const fetchOffices = async () => {
  officesLoading.value = true
  try {
    const res = await api.get('/offices')
    offices.value = Array.isArray(res.data) ? res.data : (res.data?.data || [])
  } catch (err) {
    console.error('Failed to load official offices:', err)
  } finally {
    officesLoading.value = false
  }
}

const parseJsonData = (val) => {
  if (!val) return []
  if (Array.isArray(val)) return val
  try {
    return JSON.parse(val)
  } catch (e) {
    return []
  }
}

const filteredOffices = computed(() => {
  let list = offices.value
  if (officeSearchQuery.value.trim()) {
    const q = officeSearchQuery.value.toLowerCase().trim()
    list = list.filter(o => 
      (o.name_am && o.name_am.toLowerCase().includes(q)) ||
      (o.name_en && o.name_en.toLowerCase().includes(q)) ||
      (o.head_name_am && o.head_name_am.toLowerCase().includes(q)) ||
      (o.head_name_en && o.head_name_en.toLowerCase().includes(q)) ||
      (o.phone && o.phone.includes(q)) ||
      (o.email && o.email.toLowerCase().includes(q)) ||
      (o.building_location_am && o.building_location_am.toLowerCase().includes(q))
    )
  }
  return list
})

const latitude = computed(() => Number(settings.value?.latitude) || 11.0498)
const longitude = computed(() => Number(settings.value?.longitude) || 39.7492)

const mapIframeUrl = computed(() => {
  const lat = latitude.value
  const lng = longitude.value
  const dLng = 0.007
  const dLat = 0.004
  return `https://www.openstreetmap.org/export/embed.html?bbox=${lng - dLng}%2C${lat - dLat}%2C${lng + dLng}%2C${lat + dLat}&layer=mapnik&marker=${lat}%2C${lng}`
})

const directionsUrl = computed(() => {
  return `https://www.google.com/maps/dir/?api=1&destination=${latitude.value},${longitude.value}`
})

const getMapAddress = () => {
  if (!settings.value) return 'Central City Hall Administrative Corridor, Kebele 02, Kombolcha, Ethiopia'
  const lang = langStore.currentLang
  if (lang === 'am') return settings.value.map_address_am || settings.value.map_address_en || 'ኮምቦልቻ፣ አማራ ክልል፣ ኢትዮጵያ'
  if (lang === 'om') return settings.value.map_address_om || settings.value.map_address_en || 'Kombolcha, Itoophiyaa'
  return settings.value.map_address_en || 'Kombolcha City Hall, Amhara Region, Ethiopia'
}

const copyCoordinates = () => {
  const coords = `${latitude.value}, ${longitude.value}`
  navigator.clipboard.writeText(coords)
  copiedCoords.value = true
  setTimeout(() => { copiedCoords.value = false }, 2500)
}

const form = reactive({
  name: '',
  phone: '',
  email: '',
  sub_city: 'Central Municipality (ዋና ማዘጋጃ ቤት)',
  subject: '',
  message: ''
})

const submitting = ref(false)
const submitted = ref(false)
const errorMessage = ref('')

const subCities = [
  'Central Municipality (ዋና ማዘጋጃ ቤት)',
  'Abishager Sub-City (አቢሻገር)',
  'Borkena Sub-City (ቦርከና)',
  'Hasenager Sub-City (ሀሰናገር)',
  'Biraro Sub-City (ቢራሮ)'
]

onMounted(() => {
  siteStore.fetchSiteData()
  fetchOffices()
  if (route.query.inquiry_target) {
    const target = route.query.inquiry_target
    form.subject = `Inquiry: ${target}`
    form.message = langStore.currentLang === 'am' 
      ? `ሰላም፣ ስለ ${target} ተጨማሪ የጉብኝትና የማዘጋጃ ቤት መረጃ ለማግኘት እፈልጋለሁ።`
      : `Greetings, I would like to make an inquiry regarding municipal coordination for "${target}".`
  }
})

const submitForm = async () => {
  if (!form.name || !form.phone || !form.subject || !form.message) {
    errorMessage.value = langStore.currentLang === 'am' ? 'እባክዎ ሁሉንም አስፈላጊ መረጃዎች በትክክል ይሙሉ' : 'Please complete all required fields.'
    return
  }

  submitting.value = true
  errorMessage.value = ''
  
  try {
    await api.submitContact(form)
    submitted.value = true
    form.name = ''
    form.phone = ''
    form.email = ''
    form.subject = ''
    form.message = ''
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Failed to submit message. Please try again.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-slate-950 text-white py-14 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/hotel_2.jpg" alt="City Hall & Municipal Contact" class="absolute inset-0 w-full h-full object-cover object-center" />
      <!-- Subtle clear scrim just like home page -->
      <div class="absolute inset-0 bg-gradient-to-r from-slate-950/70 via-slate-950/35 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/25"></div>
      
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3 drop-shadow-[0_4px_16px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር ህዝብ ግንኙነትና አቤቱታ መቀበያ' : 'Citizen Inquiries, Emergency Dispatch & Grievance Desk' }}
        </h1>
        <p class="text-white text-sm sm:text-base max-w-3xl leading-relaxed font-semibold drop-shadow-[0_2px_8px_rgba(0,0,0,0.95)]">
          {{ langStore.currentLang === 'am'
            ? 'ለኮምቦልቻ ከተማ ከንቲባ፣ ለ 4ቱ ክፍለ ከተሞች ወይም ለ 30ው ሴክተር መምሪያዎች በቀጥታ ጥያቄዎን፣ አቤቱታዎን ወይም አስተያየትዎን ያስገቡ፤ እንዲሁም የ 24 ሰዓት የውሃ፣ መብራትና ፖሊስ መስመሮችን ያግኙ።'
            : 'Submit official petitions, citizen grievances, or municipal inquiries directly to the Mayor’s Cabinet, 4 Sub-Cities, or 30 Sector Bureaus with guaranteed official tracking numbers.'
          }}
        </p>

        <!-- Dispatch Hotlines Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-white/20 text-xs">
          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-white/20 shadow-xl flex items-center justify-between">
            <div>
              <span class="text-slate-300 block font-bold mb-1">Police Dispatch</span>
              <a href="tel:991" class="text-xl sm:text-2xl font-black font-mono text-white hover:text-blue-400 transition-colors">991</a>
              <span class="text-[10px] text-slate-300 block mt-0.5 font-mono">Zone: 033 551 56 89</span>
            </div>
            <ShieldAlert class="w-7 h-7 text-blue-400 opacity-80" />
          </div>

          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-rose-500/40 shadow-xl flex items-center justify-between">
            <div>
              <span class="text-rose-400 block font-bold mb-1">Fire & Rescue</span>
              <a href="tel:939" class="text-xl sm:text-2xl font-black font-mono text-rose-400 hover:text-rose-300 transition-colors">939 / 993</a>
              <span class="text-[10px] text-slate-300 block mt-0.5">Municipal Fire Unit</span>
            </div>
            <Flame class="w-7 h-7 text-rose-400 opacity-80" />
          </div>

          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/40 shadow-xl flex items-center justify-between">
            <div>
              <span class="text-emerald-400 block font-bold mb-1">Ambulance & Hospital</span>
              <a href="tel:0911639869" class="text-sm sm:text-base font-black font-mono text-emerald-400 hover:underline block">09 11 63 98 69</a>
              <span class="text-[10px] text-slate-300 block font-mono">Hospital: 033 351 10 69</span>
            </div>
            <Activity class="w-7 h-7 text-emerald-400 opacity-80" />
          </div>

          <div class="bg-slate-950/50 backdrop-blur-md p-4 rounded-2xl border border-amber-500/40 shadow-xl flex items-center justify-between">
            <div>
              <span class="text-amber-400 block font-bold mb-1">Mayor Office Switchboard</span>
              <a href="tel:0335510216" class="text-sm sm:text-base font-black font-mono text-amber-400 hover:underline block truncate">033 551 02 16</a>
              <span class="text-[10px] text-slate-300 block font-mono">Mayor: 033 551 40 62</span>
            </div>
            <Landmark class="w-7 h-7 text-amber-400 opacity-80" />
          </div>
        </div>
      </div>
    </div>

    <!-- Official Emergency & Utility Desks Notice Strip -->
    <div class="bg-slate-900 border-b border-slate-800 py-3.5 px-4 sm:px-8 text-xs text-slate-300">
      <div class="max-w-[1700px] mx-auto flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-amber-400 font-black uppercase tracking-wider shrink-0">
          <PhoneCall class="w-4 h-4 shrink-0" />
          <span>{{ langStore.currentLang === 'am' ? 'የ 24 ሰዓት የውሃ ፍሳሽና የመብራት ድንገተኛ መስመሮች:' : '24/7 Utility Leaks & Grid Dispatch:' }}</span>
        </div>
        <div class="flex flex-wrap items-center gap-3 text-xs font-mono">
          <div class="flex flex-wrap items-center gap-1.5 bg-slate-800/90 px-3 py-1.5 rounded-xl border border-slate-700">
            <Droplets class="w-3.5 h-3.5 text-blue-400 shrink-0" />
            <span class="text-slate-400 font-sans font-bold">Water Leaks 24/7:</span>
            <a href="tel:0912746964" class="text-white hover:text-amber-400 font-bold">09 12 74 69 64</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0910539584" class="text-white hover:text-amber-400 font-bold">09 10 53 95 84</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0932029686" class="text-white hover:text-amber-400 font-bold">09 32 02 96 86</a>
            <span class="text-slate-600">|</span>
            <a href="tel:0925894557" class="text-white hover:text-amber-400 font-bold">09 25 89 45 57</a>
          </div>
          <div class="flex items-center gap-1.5 bg-slate-800/90 px-3 py-1.5 rounded-xl border border-slate-700 shrink-0">
            <Zap class="w-3.5 h-3.5 text-amber-400 shrink-0" />
            <span class="text-slate-400 font-sans font-bold">Grid:</span>
            <a href="tel:905" class="text-white hover:text-amber-400 font-bold">905</a>
            <span class="text-slate-600">/</span>
            <a href="tel:0335510177" class="text-white hover:text-amber-400 font-bold">033 551 01 77</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <!-- Left: Interactive Contact Form (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200/90 p-8 sm:p-10 shadow-sm relative overflow-hidden">
          
          <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የመልእክትና አቤቱታ መላኪያ ይፋዊ ቅጽ' : 'Citizen Submission & Feedback Form' }}
              </h2>
              <p class="text-xs text-slate-500 mt-1">
                {{ langStore.currentLang === 'am' ? 'መረጃዎ በከተማው የህዝብ ግንኙነትና ቅሬታ ሰሚ ጽ/ቤት በሚስጥር ተይዞ ክትትል ይደረግበታል።' : 'Protected under the Kombolcha Municipal Transparency and Anti-Corruption Charter.' }}
              </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#1C398E] flex items-center justify-center font-bold">
              <Send class="w-6 h-6" />
            </div>
          </div>

          <!-- Success Alert -->
          <div v-if="submitted" class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 mb-6 flex items-start gap-3">
            <CheckCircle class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" />
            <div class="text-xs sm:text-sm">
              <strong class="font-black block text-emerald-950">
                {{ langStore.currentLang === 'am' ? 'መልእክትዎ በተሳካ ሁኔታ ደርሷል!' : 'Your message has been officially registered!' }}
              </strong>
              <p class="mt-1 text-emerald-800">
                {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር ጽ/ቤት መረጃውን ገምግሞ በተጠቀሰው ስልክ ወይም ኢሜል ምላሽ ይሰጥዎታል። እናመሰግናለን።' : 'Our secretariat will review your submission and follow up via telephone or email within 48 business hours.' }}
              </p>
            </div>
          </div>

          <!-- Error Alert -->
          <div v-if="errorMessage" class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 mb-6 text-xs font-bold flex items-center gap-2">
            <AlertCircle class="w-4 h-4 text-rose-600 shrink-0" />
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Form Fields -->
          <form @submit.prevent="submitForm" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                  {{ langStore.currentLang === 'am' ? 'ሙሉ ስም *' : 'Full Name *' }}
                </label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition"
                  placeholder="e.g. Mohammed Ahmed"
                />
              </div>

              <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                  {{ langStore.currentLang === 'am' ? 'ስልክ ቁጥር *' : 'Phone Number *' }}
                </label>
                <input 
                  v-model="form.phone" 
                  type="tel" 
                  required
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition font-mono"
                  placeholder="09..."
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                  {{ langStore.currentLang === 'am' ? 'ኢሜል (አማራጭ)' : 'Email Address (Optional)' }}
                </label>
                <input 
                  v-model="form.email" 
                  type="email" 
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition font-mono"
                  placeholder="name@example.com"
                />
              </div>

              <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                  {{ langStore.currentLang === 'am' ? 'የሚመለከተው አስተዳደር *' : 'Target Sub-City / Administration *' }}
                </label>
                <select 
                  v-model="form.sub_city" 
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition font-bold cursor-pointer"
                >
                  <option v-for="sub in subCities" :key="sub" :value="sub">
                    {{ sub }}
                  </option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                {{ langStore.currentLang === 'am' ? 'የመልእክቱ ርዕስ *' : 'Inquiry Subject *' }}
              </label>
              <input 
                v-model="form.subject" 
                type="text" 
                required
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition"
                placeholder="e.g. Inquiry regarding building permit / water maintenance"
              />
            </div>

            <div>
              <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                {{ langStore.currentLang === 'am' ? 'ዝርዝር መልእክት ወይም አቤቱታ *' : 'Detailed Message / Grievance Context *' }}
              </label>
              <textarea 
                v-model="form.message" 
                rows="5" 
                required
                class="w-full p-4 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition leading-relaxed"
                placeholder="Please describe your inquiry with specific kebele or house number if applicable..."
              ></textarea>
            </div>

            <button 
              type="submit" 
              :disabled="submitting"
              class="w-full py-4 rounded-xl bg-[#1C398E] hover:bg-blue-900 text-amber-300 font-black text-xs uppercase tracking-wider transition-all shadow-md hover:scale-[1.01] cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2"
            >
              <Send class="w-4 h-4 text-amber-400" />
              <span>{{ submitting ? 'Registering Submission...' : (langStore.currentLang === 'am' ? 'ይፋዊ መልእክት ላክ' : 'Submit Official Feedback') }}</span>
            </button>
          </form>
        </div>

        <!-- Right: Map, Coordinates, and Physical City Hall (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
          
          <!-- Physical Location & GPS Coordinates Card -->
          <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <MapPin class="w-4 h-4 text-[#1C398E]" />
                <span>City Hall Geolocation</span>
              </h3>
              <button 
                @click="copyCoordinates" 
                class="text-[11px] font-mono font-bold text-blue-700 hover:underline flex items-center gap-1 cursor-pointer"
              >
                <Check v-if="copiedCoords" class="w-3.5 h-3.5 text-emerald-600" />
                <Copy v-else class="w-3.5 h-3.5" />
                <span>{{ copiedCoords ? 'Copied' : `${latitude.toFixed(4)}, ${longitude.toFixed(4)}` }}</span>
              </button>
            </div>

            <!-- Physical Address -->
            <p class="text-xs text-slate-600 leading-relaxed font-medium">
              {{ getMapAddress() }}
            </p>

            <!-- Embedded OpenStreetMap Frame -->
            <div class="w-full h-56 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 relative">
              <iframe 
                :src="mapIframeUrl" 
                class="w-full h-full border-0"
                loading="lazy"
                title="Kombolcha City Hall Location Map"
              ></iframe>
            </div>

            <!-- Route Directions Button -->
            <a 
              :href="directionsUrl" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition cursor-pointer"
            >
              <Navigation class="w-4 h-4 text-[#1C398E]" />
              <span>Get Directions in Google Maps</span>
              <ExternalLink class="w-3.5 h-3.5 text-slate-400" />
            </a>
          </div>

          <!-- Working Hours & Direct Lines Card -->
          <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-sm space-y-3.5 text-xs text-slate-600">
            <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-slate-900 pb-3 border-b border-slate-100">
              <Clock class="w-4 h-4 text-amber-600" />
              <span>Municipal Working Hours</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-2">
              <span class="text-slate-500 font-bold">Monday - Friday:</span>
              <span class="font-black text-slate-900 font-mono">8:00 AM - 5:30 PM (EAT)</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-2">
              <span class="text-slate-500 font-bold">Lunch Break:</span>
              <span class="font-black text-slate-900 font-mono">12:30 PM - 1:30 PM</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500 font-bold">Weekends & Holidays:</span>
              <span class="font-bold text-rose-600">Closed (Emergency Units 24/7)</span>
            </div>
          </div>

          <!-- 4 Sub-Cities Quick Contact Card -->
          <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-7 shadow-sm space-y-3 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
              <span class="font-black uppercase tracking-wider text-amber-400">4 Sub-Cities Direct Lines</span>
              <Landmark class="w-4 h-4 text-amber-400" />
            </div>
            <div class="space-y-2 font-mono">
              <div class="flex justify-between items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                <span class="text-slate-300 font-sans font-bold">አቢሻገር (Abishager)</span>
                <a href="tel:0335510022" class="text-amber-400 hover:underline font-bold">033 551 00 22</a>
              </div>
              <div class="flex justify-between items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                <span class="text-slate-300 font-sans font-bold">ቢራሮ (Biraro)</span>
                <a href="tel:0335511505" class="text-amber-400 hover:underline font-bold">033 551 15 05</a>
              </div>
              <div class="flex justify-between items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                <span class="text-slate-300 font-sans font-bold">ቦርከና (Borkena)</span>
                <a href="tel:0335510229" class="text-amber-400 hover:underline font-bold">033 551 02 29</a>
              </div>
              <div class="flex justify-between items-center bg-slate-800/80 p-2.5 rounded-xl border border-slate-700">
                <span class="text-slate-300 font-sans font-bold">ሀሰናገር (Hassen Ager)</span>
                <a href="tel:0335510005" class="text-amber-400 hover:underline font-bold">033 551 00 05</a>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Comprehensive Official Municipal Bureaus & Sectoral Directory -->
      <div class="mt-16 pt-12 border-t border-slate-200">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-[#1C398E] text-xs font-black uppercase tracking-wider mb-2">
              <Building2 class="w-3.5 h-3.5" />
              <span>{{ langStore.currentLang === 'am' ? 'ይፋዊ የመንግስት ሰነድ መረጃ' : 'Official Municipal Gazette Directory' }}</span>
            </div>
            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ ሴክተር መምሪያዎችና ተጠሪ ጽ/ቤቶች ይፋዊ ስልክ ማውጫ' : 'Official Municipal Bureaus, Sectors & Facility Directory' }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-3xl">
              {{ langStore.currentLang === 'am'
                ? 'በከተማው ሰነድ መሰረት የተረጋገጡ 30ው ሴክተር መምሪያዎች፣ ተጠሪ ጽ/ቤቶች፣ የጤና ጣቢያዎችና የድንገተኛ አደጋ መስመሮች ዝርዝር አድራሻ።'
                : 'Verified departmental contact numbers, office room locations, bureau heads, and facility dispatch networks as officially gazetted.'
              }}
            </p>
          </div>

          <!-- Directory Search Input -->
          <div class="relative w-full md:w-80 shrink-0">
            <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input 
              v-model="officeSearchQuery" 
              type="text" 
              :placeholder="langStore.currentLang === 'am' ? 'መምሪያ፣ ስልክ ወይም ኃላፊ ፈልግ...' : 'Search bureau, phone, or head...'" 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-blue-600 bg-white shadow-2xs font-medium"
            />
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="officesLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="i in 6" :key="i" class="h-48 rounded-3xl bg-white border border-slate-200 animate-pulse p-6"></div>
        </div>

        <!-- Directory Cards Grid -->
        <div v-else-if="filteredOffices.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="office in filteredOffices" 
            :key="office.id" 
            class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-2xs hover:border-blue-400 hover:shadow-md transition-all duration-300 flex flex-col justify-between group"
          >
            <div>
              <!-- Office Header -->
              <div class="flex items-start justify-between gap-3 mb-3">
                <div class="flex-1 min-w-0">
                  <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md inline-block mb-1.5">
                    Sector #{{ office.id }}
                  </span>
                  <h4 class="text-base font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug">
                    {{ langStore.currentLang === 'am' ? (office.name_am || office.name_en) : (office.name_en || office.name_am) }}
                  </h4>
                </div>
                <div class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-700 group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors shrink-0">
                  <Building2 class="w-5 h-5" />
                </div>
              </div>

              <!-- Bureau Head -->
              <div v-if="office.head_name_am || office.head_name_en" class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50/80 p-2.5 rounded-xl border border-slate-100 mb-3">
                <User class="w-3.5 h-3.5 text-blue-700 shrink-0" />
                <span class="font-bold text-slate-800 truncate">
                  {{ langStore.currentLang === 'am' ? (office.head_name_am || office.head_name_en) : (office.head_name_en || office.head_name_am) }}
                </span>
                <span v-if="office.head_education_am || office.head_education_en" class="text-[10px] bg-slate-200/80 px-1.5 py-0.5 rounded text-slate-600 ml-auto shrink-0 font-medium">
                  {{ langStore.currentLang === 'am' ? (office.head_education_am || office.head_education_en) : (office.head_education_en || office.head_education_am) }}
                </span>
              </div>

              <!-- Physical Location & Rooms -->
              <div v-if="office.building_location_am || office.building_location_en" class="flex items-start gap-2 text-xs text-slate-500 mb-3">
                <MapPin class="w-3.5 h-3.5 text-amber-600 shrink-0 mt-0.5" />
                <span class="line-clamp-2">
                  {{ langStore.currentLang === 'am' ? (office.building_location_am || office.building_location_en) : (office.building_location_en || office.building_location_am) }}
                  <span v-if="office.room_number" class="font-mono font-bold text-slate-700"> ({{ office.room_number }})</span>
                </span>
              </div>

              <!-- Additional Facility / Division Lines -->
              <div v-if="parseJsonData(office.additional_phones).length > 0" class="space-y-1.5 pt-2 border-t border-slate-100 mb-3">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Departmental Process Lines:</span>
                <div 
                  v-for="(p, pIdx) in parseJsonData(office.additional_phones)" 
                  :key="pIdx"
                  class="flex items-center justify-between text-xs font-mono"
                >
                  <span class="text-slate-600 truncate pr-2">{{ langStore.currentLang === 'am' ? (p.title_am || p.title_en) : p.title_en }}:</span>
                  <a :href="'tel:' + p.phone.replace(/[^0-9+]/g, '')" class="font-bold text-blue-700 hover:text-blue-900 shrink-0">
                    {{ p.phone }}
                  </a>
                </div>
              </div>
            </div>

            <!-- Card Bottom: Primary Phone & Email -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 text-xs">
              <a 
                v-if="office.phone" 
                :href="'tel:' + office.phone.replace(/[^0-9+]/g, '')" 
                class="inline-flex items-center gap-1.5 font-mono font-bold text-slate-800 hover:text-blue-700 transition-colors"
              >
                <Phone class="w-3.5 h-3.5 text-blue-700" />
                <span>{{ office.phone }}</span>
              </a>
              <span v-else class="text-slate-400 font-mono text-[11px]">No direct line</span>

              <a 
                v-if="office.email || office.head_email" 
                :href="'mailto:' + (office.email || office.head_email)" 
                class="inline-flex items-center gap-1 text-[11px] font-mono text-slate-500 hover:text-blue-700 transition-colors max-w-[140px] truncate"
                :title="office.email || office.head_email"
              >
                <Mail class="w-3 h-3 text-slate-400 shrink-0" />
                <span class="truncate">{{ (office.email || office.head_email).split('@')[0] }}@</span>
              </a>
            </div>
          </div>
        </div>

        <!-- No Results Search Fallback -->
        <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 shadow-2xs">
          <Search class="w-12 h-12 text-slate-300 mx-auto mb-3" />
          <h4 class="text-base font-bold text-slate-700">No departments match "{{ officeSearchQuery }}"</h4>
          <p class="text-xs text-slate-400 mt-1">Please try searching by bureau title, phone number, or department head.</p>
        </div>
      </div>
    </div>
  </div>
</template>
