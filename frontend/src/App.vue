<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/layout/Navbar.vue'
import Footer from './components/layout/Footer.vue'

const route = useRoute()
const isAdminRoute = computed(() => {
  return route.path.startsWith('/admin')
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white text-slate-800">
    <template v-if="!isAdminRoute">
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
