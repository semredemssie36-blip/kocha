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
  MapPin 
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

const emit = defineEmits(['updateStatus', 'refresh'])

const statusFilter = ref('all')
const searchQuery = ref('')

const filteredMessages = computed(() => {
  return props.messagesList.filter(msg => {
    if (statusFilter.value !== 'all' && msg.status !== statusFilter.value) return false
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
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Citizen Relations & Public Desk</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Inquiries Inbox</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Citizen Inquiries & Grievances</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ messagesList.length }} messages
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <div class="relative w-64">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search citizen or subject..."
            class="w-full pl-9 pr-3 py-2 rounded-xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none"
          />
        </div>
      </div>
    </div>

    <!-- Status Filters -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
      <button 
        @click="statusFilter = 'all'"
        :class="['px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer', statusFilter === 'all' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100']"
      >
        All ({{ messagesList.length }})
      </button>
      <button 
        @click="statusFilter = 'unread'"
        :class="['px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer', statusFilter === 'unread' ? 'bg-rose-600 text-white' : 'text-slate-600 hover:bg-slate-100']"
      >
        Unread
      </button>
      <button 
        @click="statusFilter = 'resolved'"
        :class="['px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer', statusFilter === 'resolved' ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-100']"
      >
        Resolved
      </button>
    </div>

    <!-- Inquiries List -->
    <div v-if="filteredMessages.length === 0" class="p-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
      <MessageSquare class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="text-xs font-bold text-slate-600">No citizen messages in this view.</p>
    </div>

    <div v-else class="space-y-3">
      <div 
        v-for="msg in filteredMessages" 
        :key="msg.id"
        class="bg-white rounded-2xl border p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col md:flex-row md:items-start justify-between gap-4"
        :class="msg.status === 'unread' ? 'border-blue-200 bg-blue-50/20' : 'border-slate-200'"
      >
        <div class="space-y-2 flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <span 
              :class="[
                'px-2 py-0.5 rounded-full text-[10px] font-bold uppercase',
                msg.status === 'unread' ? 'bg-rose-100 text-rose-800' :
                msg.status === 'resolved' ? 'bg-emerald-100 text-emerald-800' :
                'bg-slate-100 text-slate-700'
              ]"
            >
              {{ msg.status }}
            </span>
            <span v-if="msg.target_sub_city" class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
              Sub-City: {{ msg.target_sub_city }}
            </span>
            <span class="text-[11px] text-slate-400 font-mono">
              {{ new Date(msg.created_at).toLocaleString() }}
            </span>
          </div>

          <h4 class="font-bold text-sm text-slate-900">{{ msg.subject }}</h4>
          <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ msg.message }}</p>

          <div class="flex items-center gap-4 text-xs text-slate-500 pt-2 border-t border-slate-100">
            <span class="font-bold text-slate-800">{{ msg.sender_name }}</span>
            <a :href="'tel:' + msg.sender_phone" class="inline-flex items-center gap-1 text-blue-600 hover:underline font-mono">
              <Phone class="w-3 h-3" />
              <span>{{ msg.sender_phone }}</span>
            </a>
            <span v-if="msg.sender_email" class="inline-flex items-center gap-1 text-slate-600">
              <Mail class="w-3 h-3" />
              <span>{{ msg.sender_email }}</span>
            </span>
          </div>
        </div>

        <!-- Quick Status Transitions -->
        <div v-if="isEditor" class="shrink-0 flex items-center gap-2 pt-2 md:pt-0">
          <button 
            v-if="msg.status === 'unread'"
            @click="emit('updateStatus', { id: msg.id, status: 'read' })"
            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs cursor-pointer"
          >
            Mark Read
          </button>
          <button 
            v-if="msg.status !== 'resolved'"
            @click="emit('updateStatus', { id: msg.id, status: 'resolved' })"
            class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs cursor-pointer shadow-2xs"
          >
            Resolve
          </button>
          <button 
            v-if="msg.status !== 'archived'"
            @click="emit('updateStatus', { id: msg.id, status: 'archived' })"
            class="px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-500 font-bold text-xs cursor-pointer"
          >
            Archive
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
