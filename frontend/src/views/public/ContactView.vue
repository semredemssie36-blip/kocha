<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
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
  HelpCircle
} from 'lucide-vue-next'

const route = useRoute()
const langStore = useLanguageStore()

const settings = ref(null)
const copiedCoords = ref(false)

const fetchSettings = async () => {
  try {
    const res = await api.getSettings()
    settings.value = res.data || null
  } catch (err) {
    console.error('Failed to load municipal settings & map coordinates:', err)
  }
}

const latitude = computed(() => Number(settings.value?.latitude) || 11.0845)
const longitude = computed(() => Number(settings.value?.longitude) || 39.7390)

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
  fetchSettings()
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
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/hotel_2.jpg" alt="Contact Us" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የከተማው አስተዳደር ህዝብ ግንኙነትና አቤቱታ መቀበያ' : 'Citizen Feedback, Inquiries & Grievance Desk' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'ምንም አይነት መለያ ሳይፈጥሩ በቀጥታ ለኮምቦልቻ ከተማ ከንቲባ፣ ለማዘጋጃ ቤቱ ወይም ለሚመለከተው ክፍለ ከተማ ጥያቄ፣ አስተያየት ወይም አቤቱታዎን ያስገቡ።'
            : 'Reach out directly to municipal authorities or sub-city administrations. Every citizen submission receives an official tracking record under municipal transparency charter.'
          }}
        </p>

        <!-- Dispatch Hotlines Strip -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800 text-xs">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 block font-bold mb-1">Police Dispatch</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-white">991</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-rose-500/30">
            <span class="text-rose-400 block font-bold mb-1">Fire & Rescue</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-rose-400">993</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 block font-bold mb-1">Ambulance</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">907</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 block font-bold mb-1">City Hall Switchboard</span>
            <div class="text-sm sm:text-base font-black font-mono text-amber-400 truncate">+251 33 551 0022</div>
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
                {{ langStore.currentLang === 'am' ? 'የመልእክት መላኪያ ይፋዊ ቅጽ' : 'Citizen Submission Form' }}
              </h2>
              <p class="text-xs text-slate-500 mt-1">
                {{ langStore.currentLang === 'am' ? 'መረጃዎ በከተማው የህዝብ ግንኙነት ዴስክ በሚስጥር ተይዞ ክትትል ይደረግበታል።' : 'Protected under the Kombolcha Municipal Privacy Charter.' }}
              </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold">
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
                {{ langStore.currentLang === 'am' ? 'የህዝብ ግንኙነት ዴስክ መረጃውን ገምግሞ በተጠቀሰው ስልክ ወይም ኢሜል ምላሽ ይሰጥዎታል። እናመሰግናለን።' : 'Our secretariat will review your submission and follow up via telephone or email within 48 business hours.' }}
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
                  placeholder="e.g. Abebe Kebede"
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
                  placeholder="+251 9..."
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
                placeholder="e.g. Inquiry regarding Kebele 03 Road Paving"
              />
            </div>

            <div>
              <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                {{ langStore.currentLang === 'am' ? 'ዝርዝር መልእክት *' : 'Detailed Message / Grievance *' }}
              </label>
              <textarea 
                v-model="form.message" 
                rows="5" 
                required
                class="w-full p-4 rounded-xl border border-slate-200 focus:outline-none focus:border-blue-600 bg-slate-50 text-xs sm:text-sm text-slate-900 transition leading-relaxed"
                placeholder="Please provide full context and room/kebele details if applicable..."
              ></textarea>
            </div>

            <button 
              type="submit" 
              :disabled="submitting"
              class="w-full py-4 rounded-xl bg-blue-900 hover:bg-blue-800 text-amber-300 font-black text-xs uppercase tracking-wider transition-all shadow-md hover:scale-[1.01] cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2"
            >
              <Send class="w-4 h-4 text-amber-400" />
              <span>{{ submitting ? 'Registering Submission...' : (langStore.currentLang === 'am' ? 'መልእክት ላክ' : 'Submit Official Feedback') }}</span>
            </button>
          </form>
        </div>

        <!-- Right: Map, Coordinates, and Physical City Hall (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
          
          <!-- Physical Location & GPS Coordinates Card -->
          <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <MapPin class="w-4 h-4 text-blue-700" />
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
            <p class="text-xs text-slate-600 leading-relaxed">
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
              class="w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition"
            >
              <Navigation class="w-4 h-4 text-blue-700" />
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
              <span class="text-slate-400 font-bold">Monday - Friday:</span>
              <span class="font-black text-slate-900">8:30 AM - 5:30 PM (EAT)</span>
            </div>
            <div class="flex justify-between border-b border-slate-100 pb-2">
              <span class="text-slate-400 font-bold">Lunch Break:</span>
              <span class="font-black text-slate-900">12:30 PM - 1:30 PM</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400 font-bold">Weekends & Public Holidays:</span>
              <span class="font-bold text-rose-600">Closed (Emergency Only)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
