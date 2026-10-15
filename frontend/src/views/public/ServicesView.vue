<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { FileText, CheckCircle2, Clock, Banknote, ChevronRight, Layers } from 'lucide-vue-next'

const langStore = useLanguageStore()
const services = ref([])
const selectedService = ref(null)
const loading = ref(true)

async function fetchServices() {
  loading.value = true
  try {
    const res = await api.get('/services')
    services.value = res.data
    if (services.value.length > 0) {
      loadServiceDetail(services.value[0].slug)
    }
  } catch (err) {
    console.error('Failed to load services:', err)
  } finally {
    loading.value = false
  }
}

async function loadServiceDetail(slug) {
  try {
    const res = await api.get(`/services/${slug}`)
    selectedService.value = res.data
  } catch (err) {
    console.error('Failed to load service detail:', err)
  }
}

onMounted(() => {
  fetchServices()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
        Citizen E-Services Portal
      </div>
      <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
        {{ langStore.t('nav.services') }}
      </h1>
      <p class="text-slate-600 text-base max-w-2xl">
        Step-by-step instructions, legal requirements, timelines, and fees for citizen services at Kombolcha City Hall and Sub-City offices.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
      <!-- Left Services List -->
      <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm space-y-2">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 px-3 py-2">
          Available Services ({{ services.length }})
        </h3>
        
        <button
          v-for="service in services"
          :key="service.id"
          @click="loadServiceDetail(service.slug)"
          :class="[
            'w-full text-left p-3.5 rounded-xl transition-all flex items-center justify-between gap-3 text-sm font-semibold',
            selectedService && selectedService.service.id === service.id
              ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20'
              : 'hover:bg-slate-50 text-slate-800'
          ]"
        >
          <div class="flex items-center gap-3">
            <FileText class="w-4 h-4 shrink-0" />
            <span class="line-clamp-1">
              {{ langStore.currentLang === 'am' ? service.title_am : (langStore.currentLang === 'om' ? service.title_om : service.title_en) }}
            </span>
          </div>
          <ChevronRight class="w-4 h-4 opacity-50 shrink-0" />
        </button>
      </div>

      <!-- Right Step-by-Step Procedure Display -->
      <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <div v-if="selectedService">
          <div class="border-b border-slate-100 pb-6 mb-8">
            <div class="flex flex-wrap items-center gap-3 mb-3">
              <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                {{ selectedService.service.type === 'step' ? 'Step-by-Step Civic Walkthrough' : 'Text Guidance' }}
              </span>
              <span class="flex items-center gap-1.5 text-xs text-slate-500">
                <Clock class="w-3.5 h-3.5 text-amber-600" />
                Duration: <strong>{{ langStore.currentLang === 'am' ? selectedService.service.estimated_time_am : selectedService.service.estimated_time_en }}</strong>
              </span>
              <span class="flex items-center gap-1.5 text-xs text-slate-500">
                <Banknote class="w-3.5 h-3.5 text-emerald-600" />
                Fee: <strong>ETB {{ Number(selectedService.service.fee_amount).toLocaleString() }}</strong>
              </span>
            </div>

            <h3 class="text-2xl font-bold text-slate-900 leading-snug">
              {{ langStore.currentLang === 'am' ? selectedService.service.title_am : (langStore.currentLang === 'om' ? selectedService.service.title_om : selectedService.service.title_en) }}
            </h3>
            <p class="text-sm text-slate-500 mt-2">
              {{ langStore.currentLang === 'am' ? selectedService.service.summary_am : (langStore.currentLang === 'om' ? selectedService.service.summary_om : selectedService.service.summary_en) }}
            </p>
          </div>

          <!-- Sequential Step Cards -->
          <div class="space-y-6">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">
              Required Process Steps ({{ selectedService.steps.length }})
            </h4>

            <div 
              v-for="step in selectedService.steps" 
              :key="step.id"
              class="relative pl-12 pb-6 border-l-2 border-blue-200 last:border-transparent last:pb-0"
            >
              <!-- Step Number Badge -->
              <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-md shadow-blue-500/20">
                {{ step.step_number }}
              </div>

              <div class="bg-slate-50 rounded-xl p-5 border border-slate-200/80 space-y-2">
                <h5 class="text-base font-bold text-slate-900">
                  {{ langStore.currentLang === 'am' ? step.title_am : (langStore.currentLang === 'om' ? step.title_om : step.title_en) }}
                </h5>
                <p class="text-sm text-slate-600 leading-relaxed">
                  {{ langStore.currentLang === 'am' ? step.instruction_am : (langStore.currentLang === 'om' ? step.instruction_om : step.instruction_en) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-16 text-slate-400">
          <Layers class="w-12 h-12 mx-auto mb-2 opacity-50" />
          <p class="text-sm">Select a service to view step-by-step guidance</p>
        </div>
      </div>
    </div>
  </div>
</template>
