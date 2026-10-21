<script setup>
import { 
  X, 
  LayoutDashboard, 
  Sliders, 
  Film, 
  FileText, 
  FileCheck, 
  Briefcase, 
  Award, 
  Users, 
  Building2, 
  Layers, 
  HardHat, 
  FolderOpen, 
  Compass, 
  PhoneCall, 
  MessageSquare, 
  Vote, 
  ShieldCheck, 
  Shield
} from 'lucide-vue-next'

const props = defineProps({
  activeSection: {
    type: String,
    required: true
  },
  mobileSidebarOpen: {
    type: Boolean,
    default: false
  },
  can: {
    type: Object,
    required: true
  },
  isAuthor: {
    type: Boolean,
    default: false
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  },
  adminT: {
    type: Function,
    required: true
  },
  officesCount: {
    type: Number,
    default: 0
  },
  destinationsCount: {
    type: Number,
    default: 0
  },
  hotlinesCount: {
    type: Number,
    default: 0
  },
  unreadMessagesCount: {
    type: Number,
    default: 0
  },
  pollsCount: {
    type: Number,
    default: 0
  },
  auditLogsTotal: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['selectSection', 'closeMobile'])
</script>

<template>
  <div class="h-full shrink-0 flex flex-col min-h-0">
    <!-- Mobile Backdrop -->
    <div 
      v-if="mobileSidebarOpen" 
      @click="emit('closeMobile')" 
      class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 lg:hidden transition-opacity"
    ></div>

    <!-- Sidebar Navigation (Drawer on Mobile/Tablet, Fixed on Desktop) -->
    <aside 
      :class="[
        'w-64 bg-[#0B1E36] text-slate-300 flex flex-col shrink-0 border-r border-[#102A4C] overflow-y-auto select-none dark-scrollbar transition-transform duration-300 ease-in-out',
        'fixed inset-y-0 left-0 z-50 h-full max-h-screen',
        'lg:static lg:h-full lg:max-h-full lg:translate-x-0',
        mobileSidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'
      ]"
    >
      <!-- Mobile Drawer Close Header -->
      <div class="lg:hidden p-4 pb-2 flex items-center justify-between border-b border-[#102A4C]">
        <div class="flex items-center gap-2">
          <img src="/assets/logo.png" alt="Seal" class="w-7 h-7 object-contain rounded-full bg-white p-0.5" />
          <span class="text-xs font-bold text-white">Kombolcha Admin</span>
        </div>
        <button @click="emit('closeMobile')" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 cursor-pointer">
          <X class="w-5 h-5" />
        </button>
      </div>

      <nav class="p-3.5 pb-12 space-y-1 text-xs font-bold">
        <button 
          @click="emit('selectSection', 'dashboard')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'dashboard' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold shadow-inner' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <LayoutDashboard class="w-4 h-4 text-amber-400" />
          <span>{{ adminT('nav_dashboard') }}</span>
        </button>

        <!-- Media Management Category -->
        <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-widest text-amber-400/80">
          {{ isAuthor ? adminT('cat_content_media') : adminT('cat_media_visuals') }}
        </div>
        <button 
          v-if="can.manageHeroSlides"
          @click="emit('selectSection', 'hero_slides')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'hero_slides' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Sliders class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_hero_slides') }}</span>
        </button>
        <button 
          v-if="can.uploadMedia"
          @click="emit('selectSection', 'gallery')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'gallery' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Film class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_gallery') }}</span>
        </button>
        <button 
          @click="emit('selectSection', 'news')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'news' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <FileText class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_news') }}</span>
        </button>
        <button 
          v-if="can.manageTenders"
          @click="emit('selectSection', 'tenders')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'tenders' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <FileCheck class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_tenders') }}</span>
        </button>
        <button 
          v-if="can.manageVacancies"
          @click="emit('selectSection', 'vacancies')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'vacancies' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Briefcase class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_vacancies') }}</span>
        </button>

        <!-- Governance Management Category -->
        <div v-if="can.manageMayor || can.manageLeadership || can.manageOffices" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-widest text-amber-400/80">
          {{ adminT('cat_governance') }}
        </div>
        <button 
          v-if="can.manageMayor"
          @click="emit('selectSection', 'mayor')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'mayor' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Award class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_mayor') }}</span>
        </button>
        <button 
          v-if="can.manageLeadership"
          @click="emit('selectSection', 'leadership')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'leadership' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Users class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_leadership') }}</span>
        </button>
        <button 
          v-if="can.manageOffices"
          @click="emit('selectSection', 'offices')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'offices' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <Building2 class="w-4 h-4 shrink-0 text-slate-400" />
            <span>{{ adminT('nav_offices') }}</span>
          </div>
          <span v-if="officesCount" class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-white font-mono font-bold">{{ officesCount }}</span>
        </button>

        <!-- Civic Services Category -->
        <div v-if="can.manageServices || can.manageProjects || can.createResources || can.manageDestinations || can.manageHotlines" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-widest text-amber-400/80">
          {{ adminT('cat_services_resources') }}
        </div>
        <button 
          v-if="can.manageServices"
          @click="emit('selectSection', 'services')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'services' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Layers class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_services') }}</span>
        </button>
        <button 
          v-if="can.manageProjects"
          @click="emit('selectSection', 'projects')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'projects' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <HardHat class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_projects') }}</span>
        </button>
        <button 
          v-if="can.createResources"
          @click="emit('selectSection', 'resources')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'resources' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <FolderOpen class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_resources') }}</span>
        </button>
        <button 
          v-if="can.manageDestinations"
          @click="emit('selectSection', 'destinations')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'destinations' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <Compass class="w-4 h-4 shrink-0 text-slate-400" />
            <span>{{ adminT('nav_destinations') }}</span>
          </div>
          <span v-if="destinationsCount" class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-white font-mono font-bold">{{ destinationsCount }}</span>
        </button>
        <button 
          v-if="can.manageHotlines"
          @click="emit('selectSection', 'hotlines')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'hotlines' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <PhoneCall class="w-4 h-4 shrink-0 text-amber-400" />
            <span>{{ adminT('nav_hotlines') || '24/7 Hotlines' }}</span>
          </div>
          <span v-if="hotlinesCount" class="text-[10px] px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 font-mono font-bold">{{ hotlinesCount }}</span>
        </button>

        <!-- Citizen Interaction Category -->
        <div v-if="can.viewInquiries" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-widest text-amber-400/80">
          {{ adminT('cat_civic_feedback') }}
        </div>
        <button 
          v-if="can.viewInquiries"
          @click="emit('selectSection', 'messages')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'messages' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <MessageSquare class="w-4 h-4 text-slate-400" />
            <span>{{ adminT('nav_messages') }}</span>
          </div>
          <span v-if="unreadMessagesCount > 0" class="px-2 py-0.5 rounded-full bg-rose-600 text-white text-[10px] font-black">
            {{ unreadMessagesCount }}
          </span>
        </button>

        <button 
          v-if="can.managePolls"
          @click="emit('selectSection', 'polls')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'polls' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <Vote class="w-4 h-4 text-slate-400" />
            <span>{{ adminT('nav_polls') || 'Civic Opinion Polls' }}</span>
          </div>
          <span v-if="pollsCount" class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-white font-mono font-bold">{{ pollsCount }}</span>
        </button>

        <!-- System Administration -->
        <div v-if="isSuperAdmin" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-widest text-amber-400/80">
          {{ adminT('cat_administration') }}
        </div>
        <button 
          v-if="isSuperAdmin"
          @click="emit('selectSection', 'staff')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'staff' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <ShieldCheck class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_staff') }}</span>
        </button>
        <button 
          v-if="isSuperAdmin"
          @click="emit('selectSection', 'audit')"
          :class="[
            'w-full flex items-center justify-between px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'audit' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <div class="flex items-center gap-3">
            <Shield class="w-4 h-4 shrink-0 text-slate-400" />
            <span>{{ adminT('nav_audit') || 'Audit & System Logs' }}</span>
          </div>
          <span v-if="auditLogsTotal" class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/30 text-purple-200 font-mono font-bold">{{ auditLogsTotal }}</span>
        </button>
        <button 
          v-if="isSuperAdmin"
          @click="emit('selectSection', 'settings')"
          :class="[
            'w-full flex items-center gap-3 px-3.5 py-2 rounded-xl transition-all cursor-pointer text-left',
            activeSection === 'settings' ? 'border-l-3 border-amber-400 bg-gradient-to-r from-amber-500/20 via-blue-900/30 to-transparent text-white font-extrabold' : 'text-slate-300 hover:text-white hover:bg-white/5'
          ]"
        >
          <Sliders class="w-4 h-4 text-slate-400" />
          <span>{{ adminT('nav_settings') }}</span>
        </button>
      </nav>
    </aside>
  </div>
</template>
