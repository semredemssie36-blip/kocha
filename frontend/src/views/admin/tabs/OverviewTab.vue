<script setup>
import { computed } from 'vue'
import { 
  LayoutDashboard, 
  PenTool, 
  Plus, 
  TrendingUp, 
  FileText, 
  FileCheck, 
  PhoneCall, 
  MessageSquare, 
  ShieldCheck, 
  ArrowRight,
  Eye,
  CheckCircle2,
  Clock,
  Layers,
  Camera
} from 'lucide-vue-next'

const props = defineProps({
  stats: {
    type: Object,
    required: true
  },
  currentUser: {
    type: Object,
    required: true
  },
  isAuthor: {
    type: Boolean,
    default: false
  },
  isEditor: {
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
  newsCount: {
    type: Number,
    default: 0
  },
  galleryCount: {
    type: Number,
    default: 0
  },
  resourcesCount: {
    type: Number,
    default: 0
  }
})

const emit = defineEmits(['navigate', 'openModal'])
</script>

<template>
  <div class="space-y-8 animate-fadeIn">
    <!-- Breadcrumb & Workspace Header -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Kombolcha Metropolitan Administration</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Console Overview</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
          <LayoutDashboard class="w-6 h-6 text-blue-600" />
          <span>{{ adminT('overview_title') }}</span>
        </h2>
      </div>

      <!-- Quick Action Button -->
      <button 
        @click="emit('openModal', 'news')" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer"
      >
        <Plus class="w-4 h-4" />
        <span>{{ isAuthor ? 'Submit Press Release' : 'Publish Announcement' }}</span>
      </button>
    </div>

    <!-- Contributor Role Banner (if Author) -->
    <div v-if="isAuthor" class="p-6 rounded-2xl bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white border border-blue-900/50 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-emerald-400 text-xs font-bold uppercase tracking-wider mb-1">
          <PenTool class="w-4 h-4" />
          <span>Municipal Journalist & Contributor Workspace</span>
        </div>
        <h3 class="text-xl font-black">Welcome, {{ currentUser?.name || 'Author' }}!</h3>
        <p class="text-xs text-slate-300 mt-1 max-w-2xl leading-relaxed">
          You are empowered to draft trilingual press briefings and submit civic reports for editorial review. Once approved by the Communications Bureau Editor, your articles are published live to the official portal.
        </p>
      </div>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-xs">
          Author Privilege Active
        </span>
      </div>
    </div>

    <!-- 4 AWS-Style High-Density Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Card 1: Views -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-slate-300 transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-blue-600"></div>
        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
          <span>{{ adminT('stat_views') }}</span>
          <Eye class="w-4 h-4 text-slate-400" />
        </div>
        <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ stats.monthly_views || '24,520' }}</div>
        <div class="mt-2 flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
          <TrendingUp class="w-3.5 h-3.5" />
          <span>{{ adminT('stat_traffic') }}</span>
        </div>
      </div>

      <!-- Card 2: News Articles -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-slate-300 transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500"></div>
        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
          <span>{{ adminT('stat_articles') }}</span>
          <FileText class="w-4 h-4 text-slate-400" />
        </div>
        <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ stats.news_count || 0 }}</div>
        <div class="mt-2 text-xs text-slate-500 font-medium">
          <span class="text-emerald-700 font-bold">{{ stats.published_news || 0 }} live</span>
          <span v-if="stats.pending_drafts > 0" class="ml-2 text-amber-600 font-bold">({{ stats.pending_drafts }} pending review)</span>
        </div>
      </div>

      <!-- Card 3: Active Tenders -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-slate-300 transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-amber-500"></div>
        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
          <span>{{ adminT('stat_tenders') }}</span>
          <FileCheck class="w-4 h-4 text-slate-400" />
        </div>
        <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ stats.active_tenders || 0 }}</div>
        <div class="mt-2 text-xs text-amber-700 font-bold">
          {{ adminT('stat_tenders_sub') }}
        </div>
      </div>

      <!-- Card 4: Citizen Inquiries -->
      <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:border-slate-300 transition-all relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-purple-600"></div>
        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
          <span>Citizen Inquiries</span>
          <MessageSquare class="w-4 h-4 text-slate-400" />
        </div>
        <div class="text-3xl font-black text-slate-900 font-mono tracking-tight">{{ stats.unread_messages || 0 }}</div>
        <div class="mt-2 text-xs text-purple-700 font-bold">
          {{ stats.unread_messages > 0 ? 'Requires municipal dispatch' : 'All inquiries addressed' }}
        </div>
      </div>
    </div>

    <!-- Rapid Management Shortcuts (AWS Console Grid) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
      <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
          <h3 class="text-base font-black text-slate-900">{{ adminT('rapid_shortcuts') }}</h3>
          <p class="text-xs text-slate-500">Direct navigation to active administrative modules.</p>
        </div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <button 
          @click="emit('navigate', 'news')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <FileText class="w-5 h-5 text-blue-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-blue-700">News Articles</div>
          <div class="text-[11px] text-slate-500 mt-0.5">Manage press releases</div>
        </button>

        <button 
          @click="emit('navigate', 'tenders')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <FileCheck class="w-5 h-5 text-amber-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-amber-700">Public Tenders</div>
          <div class="text-[11px] text-slate-500 mt-0.5">Procurement notices</div>
        </button>

        <button 
          @click="emit('navigate', 'destinations')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <Layers class="w-5 h-5 text-emerald-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-emerald-700">Destinations</div>
          <div class="text-[11px] text-slate-500 mt-0.5">Tourism & industry</div>
        </button>

        <button 
          @click="emit('navigate', 'hotlines')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <PhoneCall class="w-5 h-5 text-rose-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-rose-700">24/7 Hotlines</div>
          <div class="text-[11px] text-slate-500 mt-0.5">Emergency dispatch</div>
        </button>

        <button 
          @click="emit('navigate', 'messages')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <MessageSquare class="w-4 h-4 text-purple-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-purple-700">Citizen Inquiries</div>
          <div class="text-[11px] text-slate-500 mt-0.5">Grievance inbox</div>
        </button>

        <button 
          v-if="isSuperAdmin"
          @click="emit('navigate', 'staff')" 
          class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-blue-50/50 hover:border-blue-200 text-left transition-all group cursor-pointer"
        >
          <ShieldCheck class="w-5 h-5 text-indigo-600 mb-2 group-hover:scale-110 transition-transform" />
          <div class="text-xs font-bold text-slate-900 group-hover:text-indigo-700">Staff Accounts</div>
          <div class="text-[11px] text-slate-500 mt-0.5">User security & roles</div>
        </button>
      </div>
    </div>
  </div>
</template>
