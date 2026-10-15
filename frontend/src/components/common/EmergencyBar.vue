<script setup>
import { useLanguageStore } from '@/stores/language'
import { ShieldAlert, PhoneCall, Flame, Droplets, Zap, Activity } from 'lucide-vue-next'

const langStore = useLanguageStore()

const emergencyNumbers = [
  { name_en: 'Police', name_am: 'ፖሊስ', name_om: 'Poolisii', phone: '991', icon: ShieldAlert, color: 'text-red-700 bg-red-50 border-red-200 hover:bg-red-100' },
  { name_en: 'Fire & Rescue', name_am: 'እሳት አደጋ', name_om: 'Balaa Ibiddii', phone: '939', icon: Flame, color: 'text-orange-700 bg-orange-50 border-orange-200 hover:bg-orange-100' },
  { name_en: 'Ambulance', name_am: 'አምቡላንስ', name_om: 'Ambulaansii', phone: '907', icon: Activity, color: 'text-rose-700 bg-rose-50 border-rose-200 hover:bg-rose-100' },
  { name_en: 'Water Leak', name_am: 'የውሃ ጥገና', name_om: 'Tajaajila Bishaanii', phone: '+251 33 551 0145', icon: Droplets, color: 'text-blue-700 bg-blue-50 border-blue-200 hover:bg-blue-100' },
  { name_en: 'Power Grid', name_am: 'መብራት ኃይል', name_om: 'Tajaajila Ibsaa', phone: '905', icon: Zap, color: 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' },
]

const getEmergencyName = (item) => {
  const lang = langStore.currentLang
  if (lang === 'am') return item.name_am
  if (lang === 'om') return item.name_om
  return item.name_en
}
</script>

<template>
  <div class="bg-white border-b border-slate-200 py-2 px-4 sm:px-8 xl:px-12 text-xs text-slate-700 w-full">
    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-2">
      <!-- Title Label -->
      <div class="flex items-center gap-2 shrink-0">
        <PhoneCall class="w-3.5 h-3.5 text-red-600 shrink-0" />
        <span class="font-extrabold uppercase tracking-wider text-slate-800 text-[11px]">
          {{ langStore.currentLang === 'am' ? 'የ 24/7 አደጋ ጊዜ ድንገተኛ ጥሪዎች' : (langStore.currentLang === 'om' ? 'Sarara Bilbila Balaa Tasaa (24/7)' : '24/7 Emergency Dispatch Hotlines') }}:
        </span>
      </div>

      <!-- Emergency Hotline Badges -->
      <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-none justify-start md:justify-end">
        <a 
          v-for="item in emergencyNumbers" 
          :key="item.phone"
          :href="'tel:' + item.phone"
          :class="[
            'inline-flex items-center gap-1.5 px-3 py-1 rounded-md border text-xs font-bold transition-colors shadow-2xs shrink-0',
            item.color
          ]"
        >
          <component :is="item.icon" class="w-3.5 h-3.5 shrink-0" />
          <span>{{ getEmergencyName(item) }}:</span>
          <span class="font-mono font-black">{{ item.phone }}</span>
        </a>
      </div>
    </div>
  </div>
</template>
