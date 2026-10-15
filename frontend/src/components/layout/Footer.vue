<script setup>
import { useLanguageStore } from '@/stores/language'
import { Building2, Phone, Mail, MapPin, ExternalLink } from 'lucide-vue-next'

const langStore = useLanguageStore()
</script>

<template>
  <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
      <!-- Col 1: About City -->
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white">
            <Building2 class="w-6 h-6" />
          </div>
          <div>
            <h3 class="text-base font-bold text-white tracking-tight">{{ langStore.t('common.city_name') }}</h3>
            <p class="text-xs text-slate-400 font-medium">Kombolcha Metropolitan Administration</p>
          </div>
        </div>
        <p class="text-sm text-slate-400 leading-relaxed">
          {{ langStore.t('common.city_tagline') }} — Connecting northern Ethiopia through industry, railway, aviation, and vibrant trade corridors.
        </p>
        <div class="pt-2 text-xs text-slate-500">
          <p class="font-medium text-slate-400">{{ langStore.t('common.working_hours') }}:</p>
          <p>{{ langStore.t('common.working_days') }}</p>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="space-y-4">
        <h4 class="text-sm font-semibold text-white tracking-wider uppercase border-b border-slate-800 pb-2">
          {{ langStore.t('common.quick_links') }}
        </h4>
        <ul class="space-y-2.5 text-sm">
          <li>
            <router-link to="/about" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.about') }}
            </router-link>
          </li>
          <li>
            <router-link to="/services" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.services') }}
            </router-link>
          </li>
          <li>
            <router-link to="/media/tenders" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.tenders') }}
            </router-link>
          </li>
          <li>
            <router-link to="/projects" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.projects') }}
            </router-link>
          </li>
          <li>
            <router-link to="/resources" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.resources') }}
            </router-link>
          </li>
          <li>
            <router-link to="/internal-board" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
              <span>›</span> {{ langStore.t('nav.internal_board') }}
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Col 3: Sub-Cities & Directory -->
      <div class="space-y-4">
        <h4 class="text-sm font-semibold text-white tracking-wider uppercase border-b border-slate-800 pb-2">
          {{ langStore.t('common.sub_cities') }}
        </h4>
        <ul class="space-y-2.5 text-sm">
          <li>
            <router-link to="/government/sub-cities" class="hover:text-blue-400 transition-colors">
              Abishager Sub-City (አቢሻገር)
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-blue-400 transition-colors">
              Borkena Sub-City (ቦርከና)
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-blue-400 transition-colors">
              Hasenager Sub-City (ሀሰናገር)
            </router-link>
          </li>
          <li>
            <router-link to="/government/sub-cities" class="hover:text-blue-400 transition-colors">
              Biraro Sub-City (ቢራሮ)
            </router-link>
          </li>
          <li class="pt-2 border-t border-slate-800">
            <router-link to="/government/offices" class="text-blue-400 hover:underline flex items-center gap-1 text-xs font-semibold">
              {{ langStore.t('nav.offices_directory') }} <ExternalLink class="w-3 h-3" />
            </router-link>
          </li>
        </ul>
      </div>

      <!-- Col 4: Contact & Location -->
      <div class="space-y-4">
        <h4 class="text-sm font-semibold text-white tracking-wider uppercase border-b border-slate-800 pb-2">
          {{ langStore.t('common.contact_us') }}
        </h4>
        <div class="space-y-3 text-sm text-slate-400">
          <div class="flex items-start gap-2.5">
            <MapPin class="w-4 h-4 text-blue-400 mt-1 shrink-0" />
            <span>Kombolcha City Hall, Amhara Region, Ethiopia</span>
          </div>
          <div class="flex items-center gap-2.5">
            <Phone class="w-4 h-4 text-emerald-400 shrink-0" />
            <span>+251 33 551 0022</span>
          </div>
          <div class="flex items-center gap-2.5">
            <Mail class="w-4 h-4 text-purple-400 shrink-0" />
            <span>info@kombolchacity.gov.et</span>
          </div>
        </div>

        <div class="pt-2">
          <router-link to="/admin/login" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 hover:text-white transition-colors">
            Staff Portal Login
          </router-link>
        </div>
      </div>
    </div>

    <!-- Copyright Sub-footer -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 mt-12 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
      <p>© 2026 {{ langStore.t('common.city_name') }}. {{ langStore.t('common.all_rights_reserved') }}.</p>
      <div class="flex items-center gap-6">
        <a href="#" class="hover:text-slate-400">Privacy Policy</a>
        <a href="#" class="hover:text-slate-400">Terms of Service</a>
        <a href="#" class="hover:text-slate-400">Accessibility</a>
      </div>
    </div>
  </footer>
</template>
