<script setup>
import { ref, computed } from 'vue'
import { 
  Shield, 
  ShieldAlert, 
  ShieldCheck, 
  Search, 
  RefreshCw, 
  Clock, 
  Calendar, 
  Trash2, 
  LogIn, 
  LogOut, 
  PlusCircle, 
  FileEdit, 
  CheckCircle2, 
  AlertTriangle, 
  Laptop, 
  ChevronLeft, 
  ChevronRight,
  Filter,
  User as UserIcon
} from 'lucide-vue-next'

const props = defineProps({
  logsList: {
    type: Array,
    required: true
  },
  totalLogs: {
    type: Number,
    default: 0
  },
  currentPage: {
    type: Number,
    default: 1
  },
  lastPage: {
    type: Number,
    default: 1
  },
  perPage: {
    type: Number,
    default: 25
  },
  loading: {
    type: Boolean,
    default: false
  },
  filters: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['updateFilter', 'changePage', 'refresh', 'purgeOld'])

function formatTimestamp(ts) {
  if (!ts) return 'N/A'
  const d = new Date(ts)
  return d.toLocaleString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  })
}

function timeAgo(ts) {
  if (!ts) return ''
  const seconds = Math.floor((new Date() - new Date(ts)) / 1000)
  if (seconds < 60) return `${Math.max(1, seconds)}s ago`
  const minutes = Math.floor(seconds / 60)
  if (minutes < 60) return `${minutes}m ago`
  const hours = Math.floor(minutes / 60)
  if (hours < 24) return `${hours}h ago`
  const days = Math.floor(hours / 24)
  return `${days}d ago`
}

function getActionColor(action) {
  switch (action) {
    case 'LOGIN':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200'
    case 'LOGIN_FAILED':
      return 'bg-rose-50 text-rose-700 border-rose-300 font-black'
    case 'LOGOUT':
      return 'bg-slate-100 text-slate-700 border-slate-200'
    case 'APPROVE':
      return 'bg-teal-50 text-teal-700 border-teal-200 font-bold'
    case 'CREATE':
      return 'bg-blue-50 text-blue-700 border-blue-200'
    case 'UPDATE':
      return 'bg-indigo-50 text-indigo-700 border-indigo-200'
    case 'DELETE':
      return 'bg-red-50 text-red-700 border-red-200'
    default:
      return 'bg-slate-100 text-slate-700 border-slate-200'
  }
}

function getRoleBadge(role) {
  switch (role) {
    case 'super_admin':
      return { label: 'Super Admin', color: 'bg-purple-100 text-purple-800 border-purple-200' }
    case 'editor':
      return { label: 'Editor', color: 'bg-blue-100 text-blue-800 border-blue-200' }
    case 'author':
      return { label: 'Author', color: 'bg-emerald-100 text-emerald-800 border-emerald-200' }
    default:
      return { label: role || 'System', color: 'bg-slate-100 text-slate-700 border-slate-200' }
  }
}
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Security & System Governance</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Audit Trail</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
          <Shield class="w-7 h-7 text-blue-600" />
          <span>System Audit & User Activity Logs</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ totalLogs }} events logged
          </span>
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Real-time tamper-evident records of user logins, role authorizations, content lifecycle updates, and system mutations.
        </p>
      </div>

      <div class="flex items-center gap-2.5 shrink-0">
        <button 
          @click="emit('refresh')" 
          :disabled="loading"
          class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-2xs transition cursor-pointer disabled:opacity-50"
          title="Refresh logs from database"
        >
          <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" />
          <span>Live Refresh</span>
        </button>

        <button 
          @click="emit('purgeOld')" 
          class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs transition cursor-pointer"
          title="Purge logs older than 90 days"
        >
          <Trash2 class="w-3.5 h-3.5" />
          <span>Purge Old Logs</span>
        </button>
      </div>
    </div>

    <!-- Filter Strip -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs space-y-3">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
        <!-- Search -->
        <div class="relative md:col-span-2">
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input 
            :value="filters.search"
            @input="emit('updateFilter', { search: $event.target.value })"
            type="text"
            placeholder="Search description, user name, email, IP address, ID..."
            class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none"
          />
        </div>

        <!-- Action Filter -->
        <div>
          <select 
            :value="filters.action"
            @change="emit('updateFilter', { action: $event.target.value })"
            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white font-medium focus:ring-2 focus:ring-blue-600 outline-none"
          >
            <option value="all">All Actions (ሁሉም ድርጊቶች)</option>
            <option value="LOGIN">LOGIN (የገቡ)</option>
            <option value="LOGIN_FAILED">LOGIN_FAILED (ያልተሳኩ)</option>
            <option value="LOGOUT">LOGOUT (የወጡ)</option>
            <option value="CREATE">CREATE (የተፈጠሩ)</option>
            <option value="UPDATE">UPDATE (የተሻሻሉ)</option>
            <option value="APPROVE">APPROVE (የጸደቁ)</option>
            <option value="DELETE">DELETE (የተሰረዙ)</option>
          </select>
        </div>

        <!-- Module Filter -->
        <div>
          <select 
            :value="filters.module"
            @change="emit('updateFilter', { module: $event.target.value })"
            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white font-medium focus:ring-2 focus:ring-blue-600 outline-none"
          >
            <option value="all">All Modules (ሁሉም ክፍሎች)</option>
            <option value="auth">Auth & Security (ማረጋገጫ)</option>
            <option value="news">News Articles (ዜናዎች)</option>
            <option value="tenders">Tenders & RFPs (ጨረታዎች)</option>
            <option value="vacancies">Vacancies (ክፍት የሥራ ቦታዎች)</option>
            <option value="gallery">Media Gallery (ፎቶና ቪዲዮ)</option>
            <option value="resources">Directives (መመሪያዎች)</option>
            <option value="services">Citizen Services (አገልግሎቶች)</option>
            <option value="destinations">Tourism Spotlight (ቱሪዝም)</option>
            <option value="projects">Infrastructure Projects (ፕሮጀክቶች)</option>
            <option value="staff">Staff Accounts (ሠራተኞች)</option>
            <option value="settings">Site Settings (ቅንብሮች)</option>
          </select>
        </div>
      </div>

      <!-- Secondary filter bar (Role & Date Range) -->
      <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
        <div class="flex items-center gap-2">
          <span class="text-slate-400 font-bold uppercase text-[10px]">Filter by Role:</span>
          <button 
            v-for="r in ['all', 'super_admin', 'editor', 'author']"
            :key="r"
            @click="emit('updateFilter', { user_role: r })"
            :class="[
              'px-2.5 py-1 rounded-lg font-bold transition cursor-pointer text-[11px]',
              filters.user_role === r ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            ]"
          >
            {{ r === 'all' ? 'All Roles' : (r === 'super_admin' ? 'Super Admins' : (r === 'editor' ? 'Editors' : 'Authors')) }}
          </button>
        </div>

        <div class="flex items-center gap-2 text-slate-500">
          <span class="text-[10px] font-bold uppercase text-slate-400">Date Range:</span>
          <input 
            :value="filters.date_from"
            @input="emit('updateFilter', { date_from: $event.target.value })"
            type="date"
            class="px-2 py-1 rounded-lg border border-slate-200 text-xs font-mono"
            title="From date"
          />
          <span>to</span>
          <input 
            :value="filters.date_to"
            @input="emit('updateFilter', { date_to: $event.target.value })"
            type="date"
            class="px-2 py-1 rounded-lg border border-slate-200 text-xs font-mono"
            title="To date"
          />
          <button 
            v-if="filters.date_from || filters.date_to || filters.search || filters.action !== 'all' || filters.module !== 'all' || filters.user_role !== 'all'"
            @click="emit('updateFilter', { search: '', action: 'all', module: 'all', user_role: 'all', date_from: '', date_to: '' })"
            class="ml-2 text-blue-600 hover:underline font-bold text-xs cursor-pointer"
          >
            Clear Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
      <div v-if="loading" class="p-16 text-center text-slate-500">
        <RefreshCw class="w-8 h-8 animate-spin mx-auto mb-3 text-blue-600" />
        <p class="text-xs font-bold">Querying audit logs from secure database...</p>
      </div>

      <div v-else-if="logsList.length === 0" class="p-16 text-center text-slate-400">
        <ShieldCheck class="w-12 h-12 mx-auto mb-3 opacity-30 text-slate-400" />
        <h4 class="font-bold text-slate-700 text-sm">No audit logs found</h4>
        <p class="text-xs mt-1">No activities match your current filter criteria.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-black uppercase tracking-wider text-slate-500">
              <th class="py-3.5 px-4 w-44">Timestamp</th>
              <th class="py-3.5 px-4 w-52">Actor / User</th>
              <th class="py-3.5 px-4 w-28">Action</th>
              <th class="py-3.5 px-4 w-28">Module</th>
              <th class="py-3.5 px-4">Event Description</th>
              <th class="py-3.5 px-4 w-36 text-right">IP & Network</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            <tr 
              v-for="log in logsList" 
              :key="log.id"
              class="hover:bg-slate-50/70 transition-colors"
            >
              <!-- Timestamp -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <div class="font-mono text-[11px] font-bold text-slate-800">{{ formatTimestamp(log.created_at) }}</div>
                <div class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                  <Clock class="w-3 h-3 text-slate-400" />
                  <span>{{ timeAgo(log.created_at) }}</span>
                </div>
              </td>

              <!-- Actor -->
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-[11px] text-slate-700 shrink-0 uppercase">
                    {{ (log.user_name || 'U').charAt(0) }}
                  </div>
                  <div class="min-w-0">
                    <div class="font-bold text-slate-900 truncate leading-snug">{{ log.user_name || 'System / Guest' }}</div>
                    <div class="text-[10px] text-slate-400 truncate font-mono">{{ log.user_email || 'anonymous' }}</div>
                  </div>
                </div>
                <div class="mt-1">
                  <span 
                    class="inline-block px-1.5 py-0.2 rounded text-[9px] font-black uppercase border"
                    :class="getRoleBadge(log.user_role).color"
                  >
                    {{ getRoleBadge(log.user_role).label }}
                  </span>
                </div>
              </td>

              <!-- Action Badge -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span 
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] uppercase font-bold border shadow-2xs"
                  :class="getActionColor(log.action)"
                >
                  <LogIn v-if="log.action === 'LOGIN'" class="w-3 h-3" />
                  <ShieldAlert v-else-if="log.action === 'LOGIN_FAILED'" class="w-3 h-3" />
                  <LogOut v-else-if="log.action === 'LOGOUT'" class="w-3 h-3" />
                  <PlusCircle v-else-if="log.action === 'CREATE'" class="w-3 h-3" />
                  <FileEdit v-else-if="log.action === 'UPDATE'" class="w-3 h-3" />
                  <CheckCircle2 v-else-if="log.action === 'APPROVE'" class="w-3 h-3" />
                  <Trash2 v-else-if="log.action === 'DELETE'" class="w-3 h-3" />
                  <span>{{ log.action }}</span>
                </span>
              </td>

              <!-- Module -->
              <td class="py-3.5 px-4 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-mono text-[11px] font-bold uppercase border border-slate-200">
                  {{ log.module }}
                </span>
                <span v-if="log.record_id" class="ml-1 text-[10px] text-slate-400 font-mono">
                  #{{ log.record_id }}
                </span>
              </td>

              <!-- Description -->
              <td class="py-3.5 px-4">
                <p class="text-xs text-slate-900 font-medium leading-relaxed">
                  {{ log.description }}
                </p>
                <div v-if="log.changes" class="mt-1">
                  <details class="text-[11px] text-slate-500 cursor-pointer">
                    <summary class="hover:text-blue-600 font-mono text-[10px]">Inspect Payload Details</summary>
                    <pre class="mt-1 p-2 bg-slate-900 text-emerald-400 rounded-lg text-[10px] font-mono overflow-x-auto max-h-32">{{ JSON.stringify(JSON.parse(log.changes), null, 2) }}</pre>
                  </details>
                </div>
              </td>

              <!-- IP Address & User Agent -->
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="font-mono text-[11px] font-bold text-slate-700">{{ log.ip_address || '127.0.0.1' }}</div>
                <div class="text-[10px] text-slate-400 truncate max-w-[140px] ml-auto font-mono" :title="log.user_agent">
                  {{ log.user_agent ? (log.user_agent.includes('Mozilla') ? 'Web Browser' : 'API Client') : 'Direct' }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div v-if="totalLogs > perPage" class="p-4 px-6 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
        <div>
          Showing page <span class="font-bold text-slate-900">{{ currentPage }}</span> of <span class="font-bold text-slate-900">{{ lastPage }}</span> 
          (<span class="font-bold text-slate-900">{{ totalLogs }}</span> total entries)
        </div>
        <div class="flex items-center gap-1.5">
          <button 
            :disabled="currentPage === 1" 
            @click="emit('changePage', currentPage - 1)"
            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs flex items-center gap-1"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
            <span>Prev</span>
          </button>
          <button 
            :disabled="currentPage === lastPage" 
            @click="emit('changePage', currentPage + 1)"
            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs flex items-center gap-1"
          >
            <span>Next</span>
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
