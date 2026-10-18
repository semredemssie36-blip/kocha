<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import { 
  Building2, 
  Home, 
  Search, 
  ArrowLeft, 
  FileQuestion, 
  PhoneCall, 
  Layers, 
  Compass, 
  FileText, 
  ShieldCheck,
  ChevronRight
} from 'lucide-vue-next'

const router = useRouter()
const langStore = useLanguageStore()

const currentYear = new Date().getFullYear()

const quickLinks = [
  { name_en: 'City Hall Portal', name_am: 'ዋናው ገጽ', path: '/', icon: Home },
  { name_en: 'Citizen Services', name_am: 'የዜጎች አገልግሎት', path: '/services', icon: Layers },
  { name_en: 'Public Procurement & Tenders', name_am: 'ጨረታዎችና ግዥ', path: '/media/tenders', icon: FileText },
  { name_en: 'Explore Kombolcha', name_am: 'ኮምቦልቻን ይጎብኙ', path: '/explore', icon: Compass },
  { name_en: 'Contact & Grievance Desk', name_am: 'አቤቱታና ቅሬታ ማዕከል', path: '/contact', icon: PhoneCall },
]
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between font-sans selection:bg-amber-400 selection:text-slate-950 relative overflow-hidden">
    
    <!-- Ambient Civic Glow Background -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-amber-500/15 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-6 py-16 sm:py-24 flex flex-col items-center justify-center text-center relative z-10">
      
      <!-- Seal Halo Emblem -->
      <div class="relative mb-8">
        <div class="absolute -inset-2 bg-gradient-to-tr from-amber-400/30 to-blue-600/30 rounded-full blur-md"></div>
        <div class="relative w-24 h-24 rounded-full bg-slate-900 border-2 border-amber-400/40 p-4 shadow-2xl flex items-center justify-center">
          <img src="/assets/logo.png" alt="City Seal" class="w-full h-full object-contain" @error="$event.target.style.display='none'" />
          <FileQuestion class="w-10 h-10 text-amber-400" />
        </div>
      </div>

      <!-- Badge -->
      <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-mono font-bold tracking-widest uppercase mb-4">
        <span>Error 404 • Resource Not Located</span>
      </div>

      <!-- Title & Headline -->
      <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight mb-3">
        {{ langStore.currentLang === 'am' ? 'የተጠየቀው ገጽ አልተገኘም' : 'Official Page Not Found' }}
      </h1>
      <p class="text-sm sm:text-base text-slate-400 max-w-lg mx-auto mb-8 leading-relaxed">
        {{ langStore.currentLang === 'am' 
          ? 'የተጠየቀው የማዘጋጃ ቤት ሰነድ ወይም አድራሻ ተቀይሯል ወይም ከአገልግሎት ውጭ ሆኗል። እባክዎ ከታች ያሉትን የመንግስት ማዕከላት ይጠቀሙ።' 
          : 'The requested municipal document, public directory, or civic page cannot be resolved. It may have been relocated, archived, or temporarily withdrawn from public circulation.' 
        }}
      </p>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center justify-center gap-3.5 mb-12">
        <router-link 
          to="/"
          class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all cursor-pointer"
        >
          <Home class="w-4 h-4" />
          <span>{{ langStore.currentLang === 'am' ? 'ወደ ዋናው ገጽ ይመለሱ' : 'Return To City Hall Portal' }}</span>
        </router-link>

        <button 
          @click="router.back()"
          class="px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer"
        >
          <ArrowLeft class="w-4 h-4" />
          <span>{{ langStore.currentLang === 'am' ? 'ወደ ኋላ ተመለስ' : 'Go Back' }}</span>
        </button>
      </div>

      <!-- Directory Quick Links Grid -->
      <div class="w-full bg-slate-900/60 border border-slate-800 rounded-3xl p-6 sm:p-8 backdrop-blur-md text-left">
        <h3 class="text-xs font-black text-amber-400 uppercase tracking-widest mb-4 flex items-center gap-2">
          <ShieldCheck class="w-4 h-4" />
          <span>{{ langStore.currentLang === 'am' ? 'ዋና ዋና የማዘጋጃ ቤት መግቢያዎች' : 'Verified Metropolitan Gateways' }}</span>
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
          <router-link 
            v-for="link in quickLinks" 
            :key="link.path"
            :to="link.path"
            class="p-3.5 rounded-2xl bg-slate-950/80 hover:bg-blue-950/40 border border-slate-800/80 hover:border-amber-400/40 transition-all flex items-center justify-between group"
          >
            <div class="flex items-center gap-3">
              <component :is="link.icon" class="w-4 h-4 text-amber-400 shrink-0 group-hover:scale-110 transition-transform" />
              <div>
                <span class="text-xs font-bold text-slate-200 group-hover:text-amber-300 block">
                  {{ langStore.currentLang === 'am' ? link.name_am : link.name_en }}
                </span>
                <span class="text-[10px] text-slate-500 font-mono">{{ link.path }}</span>
              </div>
            </div>
            <ChevronRight class="w-3.5 h-3.5 text-slate-600 group-hover:text-amber-400 group-hover:translate-x-0.5 transition-all" />
          </router-link>
        </div>
      </div>
    </main>

    <!-- Footer Bar -->
    <footer class="border-t border-slate-900 bg-slate-950/90 py-4 px-6 text-center text-xs text-slate-500 font-mono">
      <span>Kombolcha Metropolitan Administration • Amhara National Regional State • {{ currentYear }}</span>
    </footer>
  </div>
</template>
