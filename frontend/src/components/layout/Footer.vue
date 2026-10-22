<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Building2, 
  Phone, 
  Mail, 
  MapPin, 
  ExternalLink,
  ShieldCheck,
  Award,
  Clock,
  PhoneCall,
  FileCheck,
  Landmark,
  Compass,
  Layers,
  ChevronRight
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const settings = ref(null)

onMounted(async () => {
  try {
    const res = await api.getSettings()
    settings.value = res.data || null
  } catch (err) {
    // fallback gracefully
  }
})

const mapAddress = computed(() => {
  if (!settings.value) return 'Kombolcha City Hall, Amhara National Regional State, Ethiopia'
  const lang = langStore.currentLang
  if (lang === 'am') return settings.value.map_address_am || settings.value.map_address_en
  if (lang === 'om') return settings.value.map_address_om || settings.value.map_address_en
  return settings.value.map_address_en
})

const directionsUrl = computed(() => {
  const lat = Number(settings.value?.latitude) || 11.0845
  const lng = Number(settings.value?.longitude) || 39.7390
  return `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`
})
</script>

<template>
  <footer class="bg-gradient-to-b from-slate-950 via-[#071324] to-[#040B15] text-slate-300 border-t border-amber-500/30 relative font-sans">
    <!-- Main Civic Directory Grid -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10">
      
      <!-- Col 1: Metropolitan Identity & Seal (4 cols) -->
      <div class="lg:col-span-4 space-y-5">
        <div class="flex items-center gap-3.5">
          <div class="relative">
            <div class="absolute -inset-0.5 bg-gradient-to-tr from-amber-400 to-amber-200 rounded-full blur-[1px]"></div>
            <img 
              src="/assets/logo.png" 
              alt="Kombolcha City Seal" 
              class="relative w-12 h-12 object-contain rounded-full bg-white p-1 shadow-md" 
            />
          </div>
          <div>
            <h3 class="text-lg font-black text-white tracking-tight leading-snug">
              {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር' : 'Kombolcha City Administration' }}
            </h3>
            <p class="text-xs text-amber-400 font-extrabold tracking-wider uppercase">
              {{ langStore.currentLang === 'am' ? 'የአማራ ብሔራዊ ክልላዊ መንግስት' : 'Amhara National Regional State' }}
            </p>
          </div>
        </div>

        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
          {{ langStore.currentLang === 'am' 
            ? 'ኮምቦልቻ በሰሜን ኢትዮጵያ የኢንዱስትሪ ፓርክ፣ የደረቅ ወደብ ጉምሩክ ተርሚናል፣ የአየር ትራንስፖርትና የባቡር መሥመር መናኸሪያ በመሆን የምጣኔ ሀብት ዕድገትን የምታፋጥን ስትራቴጂካዊ የከተማ ማዕከል ናት።' 
            : 'Kombolcha is the premier industrial and logistics gateway of Northern Ethiopia — anchoring the Kombolcha Industrial Park (KIP), Dry Port Customs Terminal, airport corridor, and Ethio-Djibouti rail connection.' 
          }}
        </p>

        <div class="p-3.5 rounded-xl bg-slate-900/90 border border-slate-800 text-xs text-slate-400 space-y-1.5">
          <div class="flex items-center gap-2 font-bold text-slate-200">
            <Clock class="w-4 h-4 text-amber-400" />
            <span>{{ langStore.t('common.working_hours') }}:</span>
          </div>
          <p class="pl-6 text-[11px]">{{ langStore.t('common.working_days') }} (8:30 AM - 5:30 PM EAT)</p>
        </div>
      </div>

      <!-- Col 2: Government & Sub-Cities (3 cols) -->
      <div class="lg:col-span-3 space-y-4">
        <h4 class="text-xs font-black text-amber-400 tracking-widest uppercase border-b border-slate-800 pb-2.5 flex items-center gap-2">
          <Landmark class="w-4 h-4 text-amber-400" />
          <span>{{ langStore.t('nav.government') }} & {{ langStore.t('common.sub_cities') }}</span>
        </h4>
        <ul class="space-y-2 text-xs">
          <li>
            <router-link to="/government/mayor" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.mayor') }} (Executive Office)</span>
            </router-link>
          </li>
          <li>
            <router-link to="/government/mayor?tab=cabinet" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.cabinet') }} (Mayoral Cabinet)</span>
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>Abishager Sub-City (አቢሻገር)</span>
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>Borkena Sub-City (ቦርከና)</span>
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>Hasenager Sub-City (ሀሰናገር)</span>
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>Biraro Sub-City (ቢራሮ)</span>
            </router-link>
          </li>
          <li class="pt-1.5">
            <router-link to="/government/offices" class="text-amber-400 hover:text-amber-300 font-bold flex items-center gap-1 text-xs">
              <span>{{ langStore.t('nav.offices_directory') }} (22 Bureaus)</span>
              <ExternalLink class="w-3 h-3" />
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Col 3: Public Services & Tenders (2 cols) -->
      <div class="lg:col-span-2 space-y-4">
        <h4 class="text-xs font-black text-amber-400 tracking-widest uppercase border-b border-slate-800 pb-2.5 flex items-center gap-2">
          <Layers class="w-4 h-4 text-amber-400" />
          <span>{{ langStore.t('common.quick_links') }}</span>
        </h4>
        <ul class="space-y-2 text-xs">
          <li>
            <router-link to="/services" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.services') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/media/tenders" class="hover:text-amber-400 transition-colors flex items-center gap-1.5 text-amber-300 font-bold">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.tenders') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/projects" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.projects') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/explore" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.explore') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/resources" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.resources') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/internal-board" class="hover:text-amber-400 transition-colors flex items-center gap-1.5">
              <ChevronRight class="w-3.5 h-3.5 text-slate-600" />
              <span>{{ langStore.t('nav.internal_board') }}</span>
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Col 4: Official Address & Geolocation (3 cols) -->
      <div class="lg:col-span-3 space-y-4">
        <h4 class="text-xs font-black text-amber-400 tracking-widest uppercase border-b border-slate-800 pb-2.5 flex items-center gap-2">
          <MapPin class="w-4 h-4 text-amber-400" />
          <span>{{ langStore.t('common.contact_us') }}</span>
        </h4>
        <div class="space-y-3 text-xs text-slate-400">
          <a 
            :href="directionsUrl" 
            target="_blank" 
            rel="noopener noreferrer" 
            class="flex items-start gap-2.5 hover:text-amber-400 transition-colors group p-2.5 rounded-xl bg-slate-900/80 border border-slate-800"
            title="Open City Hall in Google Maps"
          >
            <MapPin class="w-4 h-4 text-amber-400 mt-0.5 shrink-0 group-hover:scale-110 transition-transform" />
            <div class="flex-1">
              <div class="font-bold text-white text-[11px]">Kombolcha City Hall Headquarters</div>
              <div class="text-[11px] text-slate-400 mt-0.5">{{ mapAddress }}</div>
              <div class="text-[10px] text-amber-400 font-bold mt-1 flex items-center gap-1">
                <span>View Coordinates & Route</span>
                <ExternalLink class="w-2.5 h-2.5" />
              </div>
            </div>
          </a>

          <div class="flex items-center gap-2.5 px-2">
            <Phone class="w-4 h-4 text-emerald-400 shrink-0" />
            <a href="tel:0335510216" class="font-mono text-slate-300 font-bold hover:text-amber-400 transition-colors">033 551 02 16</a>
          </div>

          <div class="flex items-center gap-2.5 px-2">
            <Mail class="w-4 h-4 text-sky-400 shrink-0" />
            <a href="mailto:kochamayoroffice@gmail.com" class="text-slate-300 font-mono text-[11px] hover:text-amber-400 transition-colors">kochamayoroffice@gmail.com</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Official Copyright & Developer Credits Bar -->
    <div class="border-t border-slate-800/80 bg-slate-950/80 py-6 px-4 sm:px-8 xl:px-12 text-xs text-slate-400">
      <div class="w-full max-w-[1700px] mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
          <p>© 2026 {{ langStore.t('common.city_name') }}. {{ langStore.t('common.all_rights_reserved') }}.</p>
          <p class="text-[11px] text-slate-400 mt-0.5">
            Engineered & Maintained by <span class="text-white font-black tracking-wide">AWUDMA Technology</span> — <span class="text-amber-400 font-semibold italic">"Technology for a Better Tomorrow"</span>
          </p>
        </div>
        <div class="flex items-center gap-6 text-[11px] font-semibold text-slate-400">
          <router-link to="/about" class="hover:text-white transition-colors">Privacy Charter</router-link>
          <span>•</span>
          <router-link to="/resources" class="hover:text-white transition-colors">Public Gazette</router-link>
          <span>•</span>
          <router-link to="/contact" class="hover:text-white transition-colors">Citizen Service Charter</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>
