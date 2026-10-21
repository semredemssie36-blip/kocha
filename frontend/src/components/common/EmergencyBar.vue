<script setup>
import { useLanguageStore } from '@/stores/language'
import { ShieldAlert, PhoneCall, Flame, Droplets, Zap, Activity } from 'lucide-vue-next'

const langStore = useLanguageStore()

const emergencyNumbers = [
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
