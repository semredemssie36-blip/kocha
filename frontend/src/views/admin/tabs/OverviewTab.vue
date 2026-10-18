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
  Camera,
  Globe
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
    <!-- Sovereign Workspace Banner -->
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#102A4C] text-white p-6 sm:p-8 shadow-xl border border-amber-500/20">
      <!-- Sovereign Micro-Ribbon -->
      <div class="absolute top-0 inset-x-0 h-1 flex">
        <div class="h-full flex-1 bg-emerald-500"></div>
        <div class="h-full flex-1 bg-amber-400"></div>
        <div class="h-full flex-1 bg-red-500"></div>
      </div>

      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
          <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-300 mb-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Kombolcha Metropolitan Administration • Command Desk</span>
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
            <span>Welcome, {{ currentUser?.name || 'Administrator' }}</span>
          </h2>
          <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
            {{ isAuthor 
              ? 'Draft trilingual press briefings and submit civic reports for editorial review. Once approved, dispatches are published live.' 
              : 'Direct oversight and editorial moderation console for municipal services, procurement, and public announcements.' 
            }}
          </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
          <router-link 
            to="/" 
            target="_blank" 
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs border border-white/15 transition-all cursor-pointer"
          >
            <Globe class="w-4 h-4 text-amber-400" />
            <span>View Public Site</span>
          </router-link>

          <button 
            @click="emit('openModal', 'news')" 
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-lg shadow-amber-500/20 transition-all cursor-pointer"
          >
            <Plus class="w-4 h-4" />
            <span>{{ isAuthor ? 'Submit Press Release' : 'Publish Announcement' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- 4 High-Density Metrics Cards (Role-Aware) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Card 1: Views / Audience Impact -->
      <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-blue-600"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>{{ isAuthor ? 'My Article Readers' : adminT('stat_views') }}</span>
          <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <Eye class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ stats.monthly_views || '24,520' }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-lg w-fit">
          <TrendingUp class="w-3.5 h-3.5" />
          <span>{{ isAuthor ? 'Audience Reach & Impact' : adminT('stat_traffic') }}</span>
        </div>
      </div>

      <!-- Card 2: Published Articles -->
      <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-emerald-500"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>{{ isAuthor ? 'Live Published Articles' : adminT('stat_articles') }}</span>
          <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <FileText class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ stats.published_news || 0 }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-emerald-700 font-bold bg-emerald-50 px-2.5 py-1 rounded-lg w-fit">
          <CheckCircle2 class="w-3.5 h-3.5 text-emerald-600" />
          <span>{{ stats.published_news || 0 }} live on public site</span>
        </div>
      </div>

      <!-- Card 3: Author -> Pending Editorial Review | Admin -> Active Tenders -->
      <div v-if="isAuthor" class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-amber-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-amber-500"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>Pending Review</span>
          <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <Clock class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ stats.pending_drafts || 0 }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-amber-800 font-bold bg-amber-50 px-2.5 py-1 rounded-lg w-fit">
          <Clock class="w-3.5 h-3.5 text-amber-600" />
          <span>{{ stats.pending_drafts > 0 ? 'Awaiting Editor Approval' : 'No articles in queue' }}</span>
        </div>
      </div>
      <div v-else class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-amber-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-amber-500"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>{{ adminT('stat_tenders') }}</span>
          <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <FileCheck class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ stats.active_tenders || 0 }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-amber-800 font-bold bg-amber-50 px-2.5 py-1 rounded-lg w-fit">
          <FileCheck class="w-3.5 h-3.5 text-amber-600" />
          <span>{{ adminT('stat_tenders_sub') }}</span>
        </div>
      </div>

      <!-- Card 4: Author -> Personal Drafts | Admin -> Citizen Inquiries -->
      <div v-if="isAuthor" class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-purple-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-purple-600"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>Personal Drafts</span>
          <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <PenTool class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ Math.max(0, (stats.news_count || 0) - (stats.published_news || 0) - (stats.pending_drafts || 0)) }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs text-purple-800 font-bold bg-purple-50 px-2.5 py-1 rounded-lg w-fit">
          <PenTool class="w-3.5 h-3.5 text-purple-600" />
          <span>Work in progress</span>
        </div>
      </div>
      <div v-else class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm hover:shadow-md hover:border-purple-300 transition-all relative overflow-hidden group">
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-purple-600"></div>
        <div class="flex items-center justify-between text-xs font-black uppercase tracking-wider text-slate-500 mb-3">
          <span>Citizen Inquiries</span>
          <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <MessageSquare class="w-4 h-4" />
          </div>
        </div>
        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight">{{ stats.unread_messages || 0 }}</div>
        <div class="mt-3 flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-lg w-fit" :class="stats.unread_messages > 0 ? 'text-rose-700 bg-rose-50' : 'text-purple-700 bg-purple-50'">
          <MessageSquare class="w-3.5 h-3.5" />
          <span>{{ stats.unread_messages > 0 ? 'Requires municipal dispatch' : 'All inquiries addressed' }}</span>
        </div>
      </div>
    </div>

    <!-- Rapid Management Shortcuts (AWS Console Grid - Role-Aware) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
      <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
        <div>
          <h3 class="text-base font-black text-slate-900">
            {{ isAuthor ? 'Journalist Quick Actions' : adminT('rapid_shortcuts') }}
          </h3>
          <p class="text-xs text-slate-500">
            {{ isAuthor ? 'Direct shortcuts to write, review, and preview your municipal press releases.' : 'Direct navigation to active administrative modules.' }}
          </p>
        </div>
      </div>

      <!-- Author Shortcuts (Only Authorized Content Actions) -->
      <div v-if="isAuthor" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <button 
          @click="emit('openModal', 'news')" 
          class="p-5 rounded-2xl border border-blue-100 bg-blue-50/50 hover:bg-blue-100/60 hover:border-blue-300 text-left transition-all group cursor-pointer"
        >
          <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-3 group-hover:scale-105 transition-transform shadow-sm">
            <Plus class="w-5 h-5" />
          </div>
          <div class="text-xs font-extrabold text-slate-900 group-hover:text-blue-700">Submit Press Release</div>
          <div class="text-[11px] text-slate-500 mt-1">Draft new trilingual article for editor review</div>
        </button>

        <button 
          @click="emit('navigate', 'news')" 
          class="p-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-left transition-all group cursor-pointer"
        >
          <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
            <FileText class="w-5 h-5" />
          </div>
          <div class="text-xs font-extrabold text-slate-900 group-hover:text-blue-700">My Press Releases</div>
          <div class="text-[11px] text-slate-500 mt-1">Track published, pending, and drafts</div>
        </button>

        <router-link 
          to="/media/news" 
          target="_blank"
          class="p-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-left transition-all group cursor-pointer flex flex-col justify-between"
        >
          <div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
              <Eye class="w-5 h-5" />
            </div>
            <div class="text-xs font-extrabold text-slate-900 group-hover:text-emerald-700">Live Public News</div>
            <div class="text-[11px] text-slate-500 mt-1">View news portal as seen by citizens</div>
          </div>
          <div class="mt-3 flex items-center gap-1 text-[11px] font-bold text-emerald-700">
            <span>Open Feed</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </div>
        </router-link>

        <router-link 
          to="/" 
          target="_blank"
          class="p-5 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 text-left transition-all group cursor-pointer flex flex-col justify-between"
        >
          <div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
              <Globe class="w-5 h-5" />
            </div>
            <div class="text-xs font-extrabold text-slate-900 group-hover:text-indigo-700">City Portal Home</div>
            <div class="text-[11px] text-slate-500 mt-1">Official homepage of Kombolcha City</div>
          </div>
          <div class="mt-3 flex items-center gap-1 text-[11px] font-bold text-indigo-700">
            <span>Visit Homepage</span>
            <ArrowRight class="w-3.5 h-3.5" />
          </div>
        </router-link>
      </div>

      <!-- Editor & Super Admin Shortcuts (Full Administrative Modules) -->
      <div v-else class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
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
