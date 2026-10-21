<script setup>
import { 
  PhoneCall, 
  ChevronRight, 
  MapPin, 
  Navigation, 
  Eye, 
  ExternalLink 
} from 'lucide-vue-next'

const props = defineProps({
  siteSettings: {
    type: Object,
    required: true
  },
  emergencyContactsList: {
    type: Array,
    required: true
  },
  detectingLocation: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['updateSettings', 'detectLiveLocation', 'selectSection'])
</script>

<template>
  <div class="space-y-6 max-w-4xl">
    <div>
      <h2 class="text-2xl font-black text-slate-900">Portal System Settings & Maintenance</h2>
      <p class="text-xs text-slate-500">Configure public emergency notice banner and site maintenance toggle.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
      <!-- Hotlines Management Quick Card in Settings -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl border border-red-200 shadow-2xs">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-sm">
            <PhoneCall class="w-5 h-5" />
          </div>
          <div>
            <h4 class="font-extrabold text-sm text-slate-900">24/7 Emergency Dispatch Hotlines</h4>
            <p class="text-xs text-slate-500">Manage telephone numbers, dispatch lines, icons and colors for Police, Fire, Ambulance and utilities.</p>
          </div>
        </div>
        <button 
          type="button"
          @click="emit('selectSection', 'hotlines')"
          class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold cursor-pointer transition-colors shadow-xs shrink-0 flex items-center gap-1.5 self-start sm:self-auto"
        >
          <span>Manage Hotlines ({{ emergencyContactsList.length }})</span>
          <ChevronRight class="w-3.5 h-3.5" />
        </button>
      </div>

      <!-- Maintenance Mode Toggle Switch -->
      <div 
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-2xl border transition-all"
        :class="siteSettings.is_maintenance ? 'bg-amber-500/10 border-amber-500/40 shadow-xs' : 'bg-slate-50 border-slate-200'"
      >
        <div class="space-y-1">
          <div class="flex items-center gap-2">
            <h4 class="font-extrabold text-sm" :class="siteSettings.is_maintenance ? 'text-amber-900' : 'text-slate-900'">
              Emergency Maintenance Mode
            </h4>
            <span 
              class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors"
              :class="siteSettings.is_maintenance ? 'bg-amber-600 text-white shadow-2xs' : 'bg-slate-200 text-slate-700'"
            >
              {{ siteSettings.is_maintenance ? 'Active (Live on Portal)' : 'Disabled (Portal Public)' }}
            </span>
          </div>
          <p class="text-xs max-w-xl" :class="siteSettings.is_maintenance ? 'text-amber-800' : 'text-slate-500'">
            When enabled, public visitors are shown an emergency maintenance holding screen. The Admin CMS remains fully accessible.
          </p>
        </div>

        <!-- Interactive Toggle Switch -->
        <button 
          type="button"
          @click="siteSettings.is_maintenance = !siteSettings.is_maintenance"
          class="relative inline-flex h-8 w-16 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-xs"
          :class="siteSettings.is_maintenance ? 'bg-amber-600' : 'bg-slate-300'"
          role="switch"
          :aria-checked="siteSettings.is_maintenance"
        >
          <span 
            class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
            :class="siteSettings.is_maintenance ? 'translate-x-8' : 'translate-x-0'"
          />
        </button>
      </div>

      <!-- 📍 Municipal Geographic Location & Interactive Map Coordinates -->
      <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-2xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
              <MapPin class="w-5 h-5" />
            </div>
            <div>
              <h4 class="font-extrabold text-sm text-slate-900">Municipal Headquarters Geographic Location & Map Pin</h4>
              <p class="text-xs text-slate-500">Manage GPS coordinates shown on the public Contact page and Footer map embed.</p>
            </div>
          </div>

          <!-- GPS Auto-Detect Button -->
          <button 
            type="button"
            @click="emit('detectLiveLocation')"
            :disabled="detectingLocation"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer disabled:opacity-50 shrink-0"
          >
            <Navigation class="w-4 h-4" :class="detectingLocation ? 'animate-spin' : ''" />
            <span>{{ detectingLocation ? 'Detecting GPS...' : '📍 Detect Live GPS Location' }}</span>
          </button>
        </div>

        <!-- Coordinates Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
              Latitude (° N) *
            </label>
            <input 
              v-model.number="siteSettings.latitude" 
              type="number" 
              step="0.0000001" 
              placeholder="11.0845000"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
            />
            <span class="text-[10px] text-slate-400 mt-0.5 block">Kombolcha default: 11.0845</span>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
              Longitude (° E) *
            </label>
            <input 
              v-model.number="siteSettings.longitude" 
              type="number" 
              step="0.0000001" 
              placeholder="39.7390000"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
            />
            <span class="text-[10px] text-slate-400 mt-0.5 block">Kombolcha default: 39.7390</span>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
              Map Zoom Level
            </label>
            <select 
              v-model.number="siteSettings.map_zoom"
              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option :value="13">13 - City Overview</option>
              <option :value="14">14 - District Level</option>
              <option :value="15">15 - Street & Building (Recommended)</option>
              <option :value="16">16 - Close Block Zoom</option>
              <option :value="17">17 - High Detail Building</option>
            </select>
            <span class="text-[10px] text-slate-400 mt-0.5 block">Recommended: 15</span>
          </div>
        </div>

        <!-- Physical Address in 3 Languages -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (Amharic)</label>
            <input v-model="siteSettings.map_address_am" type="text" placeholder="ዋናው አስተዳደር ኮሪደር፣ ቀበሌ 02፣ ኮምቦልቻ" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (English)</label>
            <input v-model="siteSettings.map_address_en" type="text" placeholder="Main Administrative Corridor, Kebele 02, Kombolcha" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (Afaan Oromoo)</label>
            <input v-model="siteSettings.map_address_om" type="text" placeholder="Kooridorii Bulchiinsa Magaalaa, Ganda 02, Kombolcha" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
          </div>
        </div>

        <!-- Live Interactive Admin Map Preview -->
        <div class="pt-2">
          <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
            <div class="flex items-center gap-1.5">
              <Eye class="w-3.5 h-3.5 text-blue-600" />
              <span>Live GPS Map Pin Preview</span>
            </div>
            <a 
              :href="'https://www.google.com/maps/search/?api=1&query=' + (siteSettings.latitude || 11.0845) + ',' + (siteSettings.longitude || 39.7390)" 
              target="_blank"
              class="text-blue-600 hover:text-blue-800 text-[11px] font-bold hover:underline inline-flex items-center gap-1"
            >
              <span>Verify in Google Maps</span>
              <ExternalLink class="w-3 h-3" />
            </a>
          </div>

          <div class="h-64 w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 relative">
            <iframe 
              v-if="siteSettings.latitude && siteSettings.longitude"
              class="w-full h-full border-0"
              :src="`https://www.openstreetmap.org/export/embed.html?bbox=${Number(siteSettings.longitude) - 0.008}%2C${Number(siteSettings.latitude) - 0.005}%2C${Number(siteSettings.longitude) + 0.008}%2C${Number(siteSettings.latitude) + 0.005}&layer=mapnik&marker=${siteSettings.latitude}%2C${siteSettings.longitude}`"
              loading="lazy"
            ></iframe>
            <div v-else class="w-full h-full flex items-center justify-center text-xs text-slate-400">
              No coordinates configured yet.
            </div>
          </div>
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Ribbon Notice (English)</label>
        <input v-model="siteSettings.emergency_notice_en" type="text" placeholder="e.g. 24/7 Rapid Emergency Response Active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
      </div>

      <button @click="emit('updateSettings')" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs cursor-pointer shadow-md">
        Save System Settings
      </button>
    </div>
  </div>
</template>
