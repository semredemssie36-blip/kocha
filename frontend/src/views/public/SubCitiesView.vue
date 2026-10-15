<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { MapPin, Phone, Mail, UserCheck, ArrowRight } from 'lucide-vue-next'

const langStore = useLanguageStore()
const subCities = ref([])
const loading = ref(true)

async function fetchSubCities() {
  loading.value = true
  try {
    const res = await api.get('/sub-cities')
    subCities.value = res.data
  } catch (err) {
    console.error('Failed to load sub-cities:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchSubCities()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
        Civic Administration Hub
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
        {{ langStore.t('nav.sub_cities') }}
      </h1>
      <p class="text-slate-600 text-base max-w-2xl">
        Kombolcha is decentralized into 4 metropolitan sub-cities providing local vital statistics, Kebele services, and community administration.
      </p>
    </div>

    <!-- Sub-Cities Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div 
        v-for="city in subCities" 
        :key="city.id" 
        class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between"
      >
        <div>
          <div class="flex items-center justify-between mb-4">
            <span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 text-xs font-mono font-bold uppercase">
              Sub-City #0{{ city.order }}
            </span>
            <span class="text-xs text-slate-400 font-medium">Active Zone</span>
          </div>

          <h3 class="text-2xl font-extrabold text-slate-900 mb-2">
            {{ langStore.currentLang === 'am' ? city.name_am : (langStore.currentLang === 'om' ? city.name_om : city.name_en) }}
          </h3>

          <div class="space-y-3 pt-4 border-t border-slate-100 text-sm text-slate-600">
            <div class="flex items-center gap-2.5">
              <UserCheck class="w-4 h-4 text-emerald-600 shrink-0" />
              <span>
                <strong>Chief Executive:</strong> 
                {{ langStore.currentLang === 'am' ? city.chief_executive_am : (langStore.currentLang === 'om' ? city.chief_executive_om : city.chief_executive_en) }}
              </span>
            </div>

            <div class="flex items-start gap-2.5">
              <MapPin class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" />
              <span>{{ langStore.currentLang === 'am' ? city.office_address_am : (langStore.currentLang === 'om' ? city.office_address_om : city.office_address_en) }}</span>
            </div>

            <div class="flex items-center gap-2.5">
              <Phone class="w-4 h-4 text-slate-400 shrink-0" />
              <a :href="'tel:' + city.phone" class="font-mono text-blue-600 hover:underline">{{ city.phone }}</a>
            </div>

            <div class="flex items-center gap-2.5">
              <Mail class="w-4 h-4 text-slate-400 shrink-0" />
              <span>{{ city.email }}</span>
            </div>
          </div>
        </div>

        <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between">
          <router-link :to="'/government/offices?sub_city_id=' + city.id" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 hover:underline">
            View Sector Offices in this Sub-City
            <ArrowRight class="w-3.5 h-3.5" />
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
