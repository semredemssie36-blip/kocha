<script setup>
import { ref, computed } from 'vue'
import { 
  MessageSquare, 
  Search, 
  Phone, 
  Mail, 
  Clock, 
  CheckCircle2, 
  Archive, 
  MapPin,
  AlertCircle,
  PhoneCall,
  FileCheck,
  ExternalLink
} from 'lucide-vue-next'

const props = defineProps({
  messagesList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['updateStatus', 'refresh', 'navigateToModule'])

const statusFilter = ref('all')
const searchQuery = ref('')

function isEditorial(msg) {
  return Boolean(msg.target_sub_city && msg.target_sub_city.startsWith('Editorial:'))
}

function getEditorialModule(msg) {
  if (msg.target_sub_city && msg.target_sub_city.startsWith('Editorial:')) {
    const mod = msg.target_sub_city.replace('Editorial:', '').trim().toLowerCase()
    const map = {
      news: 'news',
      tender: 'tenders',
      tenders: 'tenders',
      vacancy: 'vacancies',
      vacancies: 'vacancies',
      service: 'services',
      services: 'services',
      project: 'projects',
      projects: 'projects',
      resource: 'resources',
      resources: 'resources',
      gallery: 'gallery',
      destination: 'destinations',
      destinations: 'destinations'
    }
    return map[mod] || mod
  }
  if (msg.admin_notes) {
    try {
      const meta = typeof msg.admin_notes === 'string' ? JSON.parse(msg.admin_notes) : msg.admin_notes
      if (meta?.module) return meta.module
    } catch (e) {}
  }
  return 'news'
}

const filteredMessages = computed(() => {
  return props.messagesList.filter(msg => {
    if (statusFilter.value === 'editorial') {
      if (!isEditorial(msg)) return false
    } else if (statusFilter.value === 'citizen') {
      if (isEditorial(msg)) return false
    } else if (statusFilter.value !== 'all' && msg.status !== statusFilter.value) {
      return false
    }
    if (searchQuery.value.trim() !== '') {
      const q = searchQuery.value.toLowerCase()
      const name = (msg.sender_name || '').toLowerCase()
      const phone = (msg.sender_phone || '').toLowerCase()
      const subject = (msg.subject || '').toLowerCase()
      const text = (msg.message || '').toLowerCase()
      if (!name.includes(q) && !phone.includes(q) && !subject.includes(q) && !text.includes(q)) return false
    }
    return true
  })
})

const unreadCount = computed(() => props.messagesList.filter(m => m.status === 'unread').length)
const resolvedCount = computed(() => props.messagesList.filter(m => m.status === 'resolved').length)
const editorialUnreadCount = computed(() => props.messagesList.filter(m => isEditorial(m) && m.status === 'unread').length)
const totalEditorialCount = computed(() => props.messagesList.filter(m => isEditorial(m)).length)
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Municipal Inquiries & Notifications</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ messagesList.length }} total
          </span>
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">Citizen grievances, feedback, and author content submitted for editorial approval.</p>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search sender or subject..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Status Filters with Badges -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 flex-wrap">
      <button 
        @click="statusFilter = 'all'"
        :class="['px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5', statusFilter === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100']"
      >
        <span>All Items</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-700'">
          {{ messagesList.length }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'editorial'"
        :class="['px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5', statusFilter === 'editorial' ? 'bg-amber-600 text-white shadow-2xs' : 'text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200']"
      >
        <FileCheck class="w-3.5 h-3.5" />
        <span>Editorial Reviews</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'editorial' ? 'bg-amber-800 text-white' : 'bg-amber-200 text-amber-900'">
          {{ totalEditorialCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'unread'"
        :class="['px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5', statusFilter === 'unread' ? 'bg-rose-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100']"
      >
        <span>Unread</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'unread' ? 'bg-rose-800 text-rose-100' : 'bg-rose-100 text-rose-800'">
          {{ unreadCount }}
        </span>
      </button>

      <button 
        @click="statusFilter = 'resolved'"
        :class="['px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5', statusFilter === 'resolved' ? 'bg-emerald-700 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100']"
      >
        <span>Resolved</span>
        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" :class="statusFilter === 'resolved' ? 'bg-emerald-800 text-emerald-100' : 'bg-emerald-100 text-emerald-800'">
          {{ resolvedCount }}
        </span>
      </button>
    </div>

    <!-- Messages / Submissions List -->
    <div v-if="filteredMessages.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-200 shadow-xs">
      <MessageSquare class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No notifications in this view.</p>
      <p class="text-[11px] text-slate-400 mt-1">Grievances and author editorial submissions will appear here in real-time.</p>
    </div>

    <div v-else class="space-y-4">
      <div 
        v-for="msg in filteredMessages" 
        :key="msg.id"
        class="bg-white rounded-3xl border p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col md:flex-row md:items-start justify-between gap-5 relative overflow-hidden"
        :class="[
          isEditorial(msg) 
            ? (msg.status === 'unread' ? 'border-amber-400/90 bg-amber-50/15' : 'border-amber-200/60 bg-amber-50/5')
            : (msg.status === 'unread' ? 'border-blue-400/80 bg-blue-50/15' : 'border-slate-200')
        ]"
      >
        <div 
          v-if="msg.status === 'unread'" 
          class="absolute top-0 left-0 bottom-0 w-1.5"
          :class="isEditorial(msg) ? 'bg-amber-500' : 'bg-blue-600'"
        ></div>

        <div class="space-y-3 flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <span 
              :class="[
                'px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border',
                msg.status === 'unread' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                msg.status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                'bg-slate-100 text-slate-700 border-slate-200'
              ]"
            >
              ● {{ msg.status }}
            </span>

            <!-- Editorial Review Badge -->
            <span 
              v-if="isEditorial(msg)"
              class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300 text-[10px] font-black flex items-center gap-1.5"
            >
              <FileCheck class="w-3.5 h-3.5 text-amber-700" />
              <span>{{ msg.target_sub_city }}</span>
            </span>

            <!-- Regular Sub-City Badge -->
            <span 
              v-else-if="msg.target_sub_city" 
              class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold flex items-center gap-1"
            >
              <MapPin class="w-3 h-3 text-blue-600" />
              <span>Sub-City: {{ msg.target_sub_city }}</span>
            </span>

            <span class="text-[11px] text-slate-400 font-mono">
              {{ new Date(msg.created_at).toLocaleString() }}
            </span>
          </div>

          <h4 class="font-black text-base text-slate-900 leading-snug">{{ msg.subject }}</h4>
          <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50 p-4 rounded-2xl border border-slate-100 font-medium">
            {{ msg.message }}
          </p>

          <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 pt-1">
            <span class="font-black text-slate-900 text-xs">{{ msg.sender_name }}</span>
            <a :href="'tel:' + msg.sender_phone" class="inline-flex items-center gap-1.5 text-blue-700 hover:underline font-mono font-bold bg-blue-50 px-2.5 py-1 rounded-lg">
              <PhoneCall class="w-3.5 h-3.5 text-blue-600" />
              <span>{{ msg.sender_phone }}</span>
            </a>
            <span v-if="msg.sender_email" class="inline-flex items-center gap-1.5 text-slate-600 font-mono">
              <Mail class="w-3.5 h-3.5 text-slate-400" />
              <span>{{ msg.sender_email }}</span>
            </span>
          </div>
        </div>

        <!-- Quick Status Transitions & One-Click Review Actions -->
        <div v-if="isEditor" class="shrink-0 flex flex-col sm:flex-row md:flex-col items-stretch gap-2 pt-2 md:pt-0">
          <!-- Direct Review Item Button for Editorial submissions -->
          <button 
            v-if="isEditorial(msg)"
            @click="emit('navigateToModule', getEditorialModule(msg))"
            class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs cursor-pointer shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-1.5"
          >
            <ExternalLink class="w-3.5 h-3.5" />
            <span>Review Item &rarr;</span>
          </button>

          <div class="flex items-center gap-2">
            <button 
              v-if="msg.status === 'unread'"
              @click="emit('updateStatus', { id: msg.id, status: 'read' })"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer transition shadow-2xs"
            >
              Mark Read
            </button>
            <button 
              v-if="msg.status !== 'resolved'"
              @click="emit('updateStatus', { id: msg.id, status: 'resolved' })"
              class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-black text-xs cursor-pointer shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5"
            >
              <CheckCircle2 class="w-4 h-4" />
              <span>Resolve</span>
            </button>
            <button 
              v-if="msg.status === 'resolved'"
              @click="emit('updateStatus', { id: msg.id, status: 'unread' })"
              class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold text-xs cursor-pointer transition"
            >
              Reopen
            </button>
            <button 
              v-if="msg.status !== 'archived'"
              @click="emit('updateStatus', { id: msg.id, status: 'archived' })"
              class="px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-500 font-bold text-xs cursor-pointer transition"
            >
              Archive
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
