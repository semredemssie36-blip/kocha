<script setup>
import { ref, onMounted, computed } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { Building2, Search, Phone, Mail, MapPin, Clock } from 'lucide-vue-next'

const langStore = useLanguageStore()
const offices = ref([])
const searchQuery = ref('')
const loading = ref(true)
const page = ref(1)
const perPage = ref(9)

const totalPages = computed(() => Math.ceil(offices.value.length / perPage.value) || 1)

const paginatedOffices = computed(() => {
  const start = (page.value - 1) * perPage.value
  return offices.value.slice(start, start + perPage.value)
})

async function fetchOffices() {
  loading.value = true
  page.value = 1
  try {
    const res = await api.get('/offices', { params: { search: searchQuery.value } })
    offices.value = Array.isArray(res.data) ? res.data : (res.data.data || [])
  } catch (err) {
    console.error('Failed to load offices:', err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchOffices()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
        Municipal Directory
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
        {{ langStore.t('nav.offices_directory') }} <span v-if="offices.length" class="text-blue-700 font-mono">({{ offices.length }})</span>
      </h1>
      <p class="text-slate-600 text-base max-w-2xl mb-6">
        {{ langStore.currentLang === 'am' ? 'የኮምቦልቻ ከተማ አስተዳደር የዘርፍ መምሪያዎች፣ ተቋማትና አገልግሎት መስጫ ቢሮዎች ይፋዊ አድራሻ፣ የክፍል ቁጥርና ስልክ መስመሮች።' : 'Official contact desks, physical locations, room numbers, and direct phone lines for Kombolcha municipal sector bureaus.' }}
      </p>

      <!-- Search Bar -->
      <div class="max-w-xl">
        <div class="relative">
          <Search class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" />
          <input 
            v-model="searchQuery" 
            @input="fetchOffices"
            type="text" 
            :placeholder="langStore.t('common.search_placeholder')"
            class="w-full pl-12 pr-4 py-3 rounded-xl bg-white text-slate-900 text-sm border border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none shadow-xs"
          />
        </div>
      </div>
    </div>

    <!-- Loading Skeleton -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="n in 6" :key="n" class="bg-white rounded-2xl border border-slate-200 p-6 animate-pulse space-y-4">
        <div class="h-6 bg-slate-200 rounded w-3/4"></div>
        <div class="h-4 bg-slate-100 rounded w-1/2"></div>
        <div class="h-4 bg-slate-100 rounded w-2/3"></div>
      </div>
    </div>

    <!-- Offices Grid -->
    <div v-else-if="offices.length > 0" class="space-y-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="office in paginatedOffices" 
          :key="office.id" 
          class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between"
        >
          <div>
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                <Building2 class="w-5 h-5" />
              </div>
              <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block">Sector Bureau</span>
                <h3 class="text-base font-bold text-slate-900 leading-snug">
                  {{ langStore.currentLang === 'am' ? office.name_am : (langStore.currentLang === 'om' ? office.name_om : office.name_en) }}
                </h3>
              </div>
            </div>

            <div class="space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-3">
              <div class="flex items-center gap-2">
                <MapPin class="w-4 h-4 text-slate-400 shrink-0" />
                <span>{{ office.building_location_am }}</span>
                <span v-if="office.room_number" class="ml-auto font-mono font-semibold px-2 py-0.5 rounded bg-slate-100">Room {{ office.room_number }}</span>
              </div>
              <div class="flex items-center gap-2">
                <Clock class="w-4 h-4 text-slate-400 shrink-0" />
                <span>{{ langStore.currentLang === 'am' ? office.working_hours_am : (langStore.currentLang === 'om' ? office.working_hours_om : office.working_hours_en) }}</span>
              </div>
            </div>
          </div>

          <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
            <a :href="'tel:' + office.phone" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700">
              <Phone class="w-3.5 h-3.5" />
              <span>{{ office.phone }}</span>
            </a>
            <a v-if="office.email" :href="'mailto:' + office.email" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-slate-600">
              <Mail class="w-3.5 h-3.5" />
            </a>
          </div>
        </div>
      </div>

      <!-- Public Pagination Controls -->
      <div v-if="offices.length > perPage" class="p-4 px-6 bg-white rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600">
        <div>
          Showing <span class="font-bold text-slate-900">{{ (page - 1) * perPage + 1 }}</span> to 
          <span class="font-bold text-slate-900">{{ Math.min(page * perPage, offices.length) }}</span> of 
          <span class="font-bold text-slate-900">{{ offices.length }}</span> offices
        </div>
        <div class="flex items-center gap-1.5">
          <button 
            :disabled="page === 1" 
            @click="page--; window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            Previous
          </button>
          <button 
            v-for="p in totalPages" 
            :key="p"
            @click="page = p; window.scrollTo({ top: 0, behavior: 'smooth' })"
            :class="[
              'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
              page === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-50 text-slate-700'
            ]"
          >
            {{ p }}
          </button>
          <button 
            :disabled="page === totalPages" 
            @click="page++; window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
          >
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Empty Results -->
    <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200">
      <Building2 class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h4 class="text-base font-bold text-slate-700">No offices found</h4>
      <p class="text-xs text-slate-400 mt-1">Try adjusting your search keyword</p>
    </div>
  </div>
</template>
