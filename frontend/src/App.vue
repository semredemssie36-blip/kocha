<script setup>
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/layout/Navbar.vue'
import Footer from './components/layout/Footer.vue'
import { useSiteStore } from '@/stores/site'
import { Wrench, ShieldAlert, ArrowRight } from 'lucide-vue-next'

const route = useRoute()
const siteStore = useSiteStore()

const isAdminRoute = computed(() => {
  return route.path.startsWith('/admin')
})

const isMaintenanceActive = computed(() => {
  return !isAdminRoute.value && (siteStore.siteSettings?.is_maintenance === 1 || siteStore.siteSettings?.is_maintenance === true)
})

onMounted(() => {
  if (isAdminRoute.value) return
  siteStore.fetchSiteData()
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white text-slate-800 overflow-x-clip w-full max-w-full">
    <!-- Maintenance Holding Screen for Public Visitors -->
    <div v-if="isMaintenanceActive" class="min-h-screen flex flex-col items-center justify-center bg-slate-900 text-white p-6 text-center">
      <div class="w-20 h-20 rounded-3xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-500 mb-6 shadow-xl animate-pulse">
        <Wrench class="w-10 h-10" />
      </div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold uppercase tracking-wider mb-4 border border-amber-500/20">
        Emergency Maintenance Mode
      </div>
      <h1 class="text-3xl sm:text-5xl font-black tracking-tight mb-3">
        System Maintenance in Progress
      </h1>
      <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto mb-6">
        The official Kombolcha City Metropolitan Administration digital portal is currently undergoing scheduled infrastructure upgrades.
      </p>
      <div v-if="siteStore.siteSettings?.emergency_notice_en" class="bg-slate-800/80 border border-slate-700 p-4 rounded-2xl max-w-lg mx-auto text-xs text-amber-300 mb-8 flex items-center gap-2">
        <ShieldAlert class="w-5 h-5 shrink-0 text-amber-400" />
        <span>{{ siteStore.siteSettings.emergency_notice_en }}</span>
      </div>
      <div class="flex items-center gap-4">
        <router-link to="/admin" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2">
          <span>Login</span>
          <ArrowRight class="w-4 h-4" />
        </router-link>
      </div>
    </div>

    <!-- Regular Portal -->
    <template v-else-if="!isAdminRoute">
      <!-- 2-Bar Header System: Top Utility/Emergency Bar + Main Official Navbar -->
      <Navbar />
      <main class="flex-grow bg-white">
        <router-view />
      </main>
      <Footer />
    </template>
    
    <template v-else>
      <router-view />
    </template>
  </div>
</template>
