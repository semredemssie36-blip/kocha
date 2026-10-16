<script setup>
import { ref, reactive, onMounted } from 'vue'
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
  PhoneCall
} from 'lucide-vue-next'

const route = useRoute()
const langStore = useLanguageStore()

const form = reactive({
  name: '',
  phone: '',
  email: '',
  sub_city: 'Central Municipality (ዋና ማዘጋጃ ቤት)',
  subject: '',
  message: ''
})

onMounted(() => {
  if (route.query.inquiry_target) {
    const target = route.query.inquiry_target
    form.subject = `Inquiry: ${target}`
    form.message = langStore.currentLang === 'am' 
      ? `ሰላም፣ ስለ ${target} ተጨማሪ የጉብኝትና የማዘጋጃ ቤት መረጃ ለማግኘት እፈልጋለሁ።`
      : `Greetings, I would like to make an inquiry regarding visiting guidelines and municipal coordination for "${target}".`
  }
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
    // Reset form
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
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <Building2 class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'የህዝብ ግንኙነትና የአቤቱታ መቀበያ ዴስክ' : (langStore.currentLang === 'om' ? 'Qunnamtii Ummataa fi Komii Dhaggeeffachuu' : 'Citizen Engagement & Inquiry Desk') }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'አስተያየት፣ ጥያቄ ወይም አቤቱታዎን ይላኩ' : (langStore.currentLang === 'om' ? 'Yaada, Gaaffii fi Komii Keessan Ergaa' : 'Contact Kombolcha City Administration') }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'ምንም አይነት መለያ ሳይፈጥሩ በቀጥታ ለከተማው አመራር ወይም ለሚመለከተው ክፍለ ከተማ መልእክት ያስተላልፉ። ጥያቄዎ በ 48 ሰዓት ውስጥ ምላሽ ያገኛል።' : (langStore.currentLang === 'om' ? 'Akkaawuntii osoo hin banin kallattiin bulchiinsa magaalichaatiif ergaa keessan dabarsaa.' : 'Reach out directly to municipal leadership or sector directorates without account registration. Inquiries are reviewed within 48 business hours.') }}
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      <!-- Contact Form (Left 7 Cols) -->
      <div class="lg:col-span-7">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm">
          <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                {{ langStore.currentLang === 'am' ? 'የመልእክት መላኪያ ቅጽ' : (langStore.currentLang === 'om' ? 'Uunkaa Ergaa' : 'Submit Citizen Feedback') }}
              </h2>
              <p class="text-xs text-slate-500 mt-1">
                {{ langStore.currentLang === 'am' ? 'ግልጽነትና ተጠያቂነትን ለማረጋገጥ መረጃዎ በሚስጥር ይያዛል።' : 'Your inquiry is handled strictly under municipal transparency guidelines.' }}
              </p>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
              <Send class="w-6 h-6" />
            </div>
          </div>

          <!-- Success Alert -->
          <div v-if="submitted" class="mb-8 p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-4">
            <CheckCircle class="w-6 h-6 text-emerald-600 shrink-0 mt-0.5" />
            <div>
              <h4 class="font-bold text-sm">
                {{ langStore.currentLang === 'am' ? 'መልእክትዎ በተሳካ ሁኔታ ተልኳል!' : (langStore.currentLang === 'om' ? 'Ergaan keessan milkaa\'inaan dhiyaateera!' : 'Feedback Submitted Successfully!') }}
              </h4>
              <p class="text-xs text-emerald-700 mt-1 leading-relaxed">
                {{ langStore.currentLang === 'am' ? 'መልእክትዎ ለሚመለከተው የከተማው የስራ ክፍል ተመርቷል። ተገቢውን ምላሽ በስልክዎ ወይም በኢሜይልዎ እናሳውቃለን። እናመሰግናለን!' : 'Your message has been assigned to the responsible municipal desk. A tracking confirmation has been logged.' }}
              </p>
              <button 
                @click="submitted = false" 
                class="mt-3 text-xs font-bold text-emerald-800 underline cursor-pointer"
              >
                {{ langStore.currentLang === 'am' ? 'ሌላ መልእክት ለመላክ' : 'Send another message' }}
              </button>
            </div>
          </div>

          <!-- Error Alert -->
          <div v-if="errorMessage" class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center gap-2">
            <AlertCircle class="w-4 h-4 text-rose-600 shrink-0" />
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Form Fields -->
          <form v-if="!submitted" @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <!-- Full Name -->
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  {{ langStore.currentLang === 'am' ? 'ሙሉ ስም *' : (langStore.currentLang === 'om' ? 'Maqaa Guutuu *' : 'Full Name *') }}
                </label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  required
                  placeholder="e.g. Abebe Bekele"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50"
                />
              </div>

              <!-- Phone Number -->
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  {{ langStore.currentLang === 'am' ? 'ስልክ ቁጥር *' : (langStore.currentLang === 'om' ? 'Lakkoofsa Bilbilaa *' : 'Phone Number *') }}
                </label>
                <input 
                  v-model="form.phone" 
                  type="tel" 
                  required
                  placeholder="+251 9..."
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50"
                />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <!-- Email (Optional) -->
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  {{ langStore.currentLang === 'am' ? 'ኢሜይል (አማራጭ)' : (langStore.currentLang === 'om' ? 'Imeelii (Filannoo)' : 'Email (Optional)') }}
                </label>
                <input 
                  v-model="form.email" 
                  type="email" 
                  placeholder="name@example.com"
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50"
                />
              </div>

              <!-- Target Sub-City -->
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  {{ langStore.currentLang === 'am' ? 'የሚመለከተው ክፍለ ከተማ / ቢሮ' : (langStore.currentLang === 'om' ? 'Kutaa Magaalaa Ilaallatu' : 'Target Sub-City or Department') }}
                </label>
                <select 
                  v-model="form.sub_city" 
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50 font-medium text-slate-800"
                >
                  <option v-for="sc in subCities" :key="sc" :value="sc">{{ sc }}</option>
                </select>
              </div>
            </div>

            <!-- Subject -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ langStore.currentLang === 'am' ? 'ጉዳዩ (ርዕስ) *' : (langStore.currentLang === 'om' ? 'Dhimma (Mata Duree) *' : 'Subject of Inquiry *') }}
              </label>
              <input 
                v-model="form.subject" 
                type="text" 
                required
                placeholder="e.g. Inconvenience regarding road drainage in Borkena"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50"
              />
            </div>

            <!-- Message Body -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ langStore.currentLang === 'am' ? 'ዝርዝር መልእክት *' : (langStore.currentLang === 'om' ? 'Ergaa Bal\'inaan *' : 'Detailed Message *') }}
              </label>
              <textarea 
                v-model="form.message" 
                rows="5" 
                required
                placeholder="Write your constructive feedback, request for clarification, or service complaint here..."
                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm bg-slate-50/50 resize-y"
              ></textarea>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="submitting"
              class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm tracking-wider uppercase transition-all duration-200 shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
            >
              <Send class="w-4 h-4" />
              <span>{{ submitting ? (langStore.currentLang === 'am' ? 'እየተላከ ነው...' : 'Transmitting...') : (langStore.currentLang === 'am' ? 'መልእክቱን ላክ' : (langStore.currentLang === 'om' ? 'Ergaa Ergi' : 'Submit Feedback Now')) }}</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Municipal Details & Map (Right 5 Cols) -->
      <div class="lg:col-span-5 space-y-6">
        <!-- Physical Location & Hours Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
              <Building2 class="w-6 h-6" />
            </div>
            <div>
              <h3 class="font-extrabold text-slate-900 text-lg">
                {{ langStore.currentLang === 'am' ? 'ዋናው ማዘጋጃ ቤት ጽሕፈት ቤት' : 'Central Municipal Hall' }}
              </h3>
              <p class="text-xs text-slate-500">Kombolcha Metropolitan Administration</p>
            </div>
          </div>

          <div class="space-y-4 text-sm text-slate-600">
            <div class="flex items-start gap-3">
              <MapPin class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <div>
                <p class="font-bold text-slate-800">{{ langStore.currentLang === 'am' ? 'አድራሻ' : 'Physical Address' }}</p>
                <p class="text-xs text-slate-500">Main Administrative Corridor, Kebele 02, Kombolcha, Amhara Region, Ethiopia</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <Phone class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <div>
                <p class="font-bold text-slate-800">{{ langStore.currentLang === 'am' ? 'ቀጥታ ስልክ' : 'Direct Telephone' }}</p>
                <p class="text-xs text-slate-500 font-mono">+251 33 551 0022 / +251 33 551 0001</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <Mail class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <div>
                <p class="font-bold text-slate-800">{{ langStore.currentLang === 'am' ? 'ይፋዊ ኢሜይል' : 'Official Portal Email' }}</p>
                <p class="text-xs text-slate-500 font-mono">info@kombolchacity.gov.et</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <Clock class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" />
              <div>
                <p class="font-bold text-slate-800">{{ langStore.currentLang === 'am' ? 'የስራ ሰዓት' : 'Official Working Hours' }}</p>
                <p class="text-xs text-slate-500">Monday - Friday: 8:00 AM - 5:30 PM (Lunch: 12:30 - 1:30 PM)</p>
              </div>
            </div>
          </div>
        </div>

        <!-- 24/7 Rapid Emergency Dispatch Hotline Card: Clean White Bordered Card -->
        <div class="bg-white border-2 border-red-200 text-slate-800 rounded-3xl p-8 shadow-xs">
          <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-red-50 text-red-700 rounded-2xl border border-red-200">
              <PhoneCall class="w-6 h-6" />
            </div>
            <div>
              <h3 class="font-black text-slate-900 text-lg">
                {{ langStore.currentLang === 'am' ? 'የ 24/7 አደጋ ጊዜ ጥሪዎች' : '24/7 Rapid Emergency Dispatch' }}
              </h3>
              <p class="text-xs text-slate-500">Toll-Free Immediate Response</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
              <div class="text-[11px] text-slate-500 font-bold uppercase">City Police</div>
              <div class="text-xl font-black text-slate-900 tracking-wider font-mono">991</div>
            </div>
            <div class="p-3.5 rounded-xl bg-red-50 border border-red-200">
              <div class="text-[11px] text-red-700 font-bold uppercase">Fire & Rescue</div>
              <div class="text-xl font-black text-red-700 tracking-wider font-mono">939</div>
            </div>
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200">
              <div class="text-[11px] text-emerald-800 font-bold uppercase">Ambulance Desk</div>
              <div class="text-xl font-black text-emerald-800 tracking-wider font-mono">907</div>
            </div>
            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200">
              <div class="text-[11px] text-blue-800 font-bold uppercase">Water Leaks</div>
              <div class="text-xs font-bold text-blue-900 mt-1 font-mono">+251 33 551 0145</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
