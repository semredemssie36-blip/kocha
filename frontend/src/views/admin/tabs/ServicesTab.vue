<script setup>
import { Plus, Layers, Edit, Trash2 } from 'lucide-vue-next'

const props = defineProps({
  servicesList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  },
  canEditItem: {
    type: Function,
    required: true
  },
  canDeleteItem: {
    type: Function,
    required: true
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem'])
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Municipal Citizen Services & Guides</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ servicesList.length }} services
          </span>
        </h2>
      </div>
      <button @click="emit('openCreate')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 transition-all shrink-0">
        <Plus class="w-4 h-4" />
        <span>Add Service Guide</span>
      </button>
    </div>

    <div v-if="servicesList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm shadow-xs">
      <Layers class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="font-bold text-slate-600 text-xs">No citizen services cataloged yet.</p>
      <p class="text-[11px] text-slate-400 mt-1">Click "Add Service Guide" above to register civic guidelines.</p>
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="srv in servicesList" 
        :key="srv.id" 
        class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
      >
        <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
        <div>
          <div class="flex items-center justify-between mb-3 pt-1">
            <div class="flex items-center gap-1.5 flex-wrap">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border" :class="srv.type === 'step' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200'">
                {{ srv.type === 'step' ? '● Step-by-Step' : '■ Civic Guide' }}
              </span>
              <span 
                :class="[
                  'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border',
                  (srv.status === 'published' || srv.is_active) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200'
                ]"
              >
                <span class="w-1.5 h-1.5 rounded-full" :class="(srv.status === 'published' || srv.is_active) ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                {{ (srv.status === 'published' || srv.is_active) ? 'Live Published' : 'Draft' }}
              </span>
            </div>
            <div class="flex items-center gap-1">
              <button 
                v-if="isEditor && srv.status !== 'published' && !srv.is_active" 
                @click="emit('approveItem', srv)" 
                class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Approve & publish service live"
              >
                Approve
              </button>
              <button 
                v-if="isEditor && (srv.status === 'published' || srv.is_active)" 
                @click="emit('unpublishItem', srv)" 
                class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Move to draft"
              >
                Unpublish
              </button>
              <button v-if="canEditItem(srv)" @click="emit('openEdit', srv)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-xl border border-slate-200 cursor-pointer transition" title="Edit Service">
                <Edit class="w-3.5 h-3.5" />
              </button>
              <button v-if="canDeleteItem(srv)" @click="emit('deleteItem', srv)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 cursor-pointer transition" title="Delete Service">
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>

          <h4 class="font-black text-base text-slate-900 leading-snug">{{ srv.title_en || srv.title_am }}</h4>
          <p class="text-xs text-slate-500 font-mono mt-1">AM: {{ srv.title_am }}</p>
          <p class="text-xs text-slate-600 mt-2.5 line-clamp-2 leading-relaxed bg-slate-50 p-3 rounded-2xl border border-slate-100 font-medium">
            {{ srv.summary_en || srv.summary_am }}
          </p>
        </div>

        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600 font-mono">
          <span class="font-bold text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">
            Fee: {{ srv.fee_amount ? Number(srv.fee_amount).toLocaleString() + ' ETB' : 'Free of Charge' }}
          </span>
          <span class="text-amber-800 font-bold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
            Est: {{ srv.estimated_time_am || srv.estimated_time_en || '1-3 Business Days' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
