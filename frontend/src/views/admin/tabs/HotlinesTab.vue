<script setup>
import { ref } from 'vue'
import { 
  PhoneCall, 
  Plus, 
  ShieldAlert, 
  Edit, 
  Trash2, 
  Phone, 
  CheckCircle2, 
  XCircle 
} from 'lucide-vue-next'

const props = defineProps({
  hotlinesList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'toggleActive'])
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Public Safety & Civic Defense</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Emergency Hotlines</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>24/7 Emergency Dispatch Hotlines</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ hotlinesList.length }} hotlines
          </span>
        </h2>
      </div>

      <button 
        v-if="isEditor"
        @click="emit('openCreate')" 
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer shrink-0"
      >
        <Plus class="w-4 h-4" />
        <span>Add Emergency Hotline</span>
      </button>
    </div>

    <!-- Hotlines Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div 
        v-for="item in hotlinesList" 
        :key="item.id"
        class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between"
      >
        <div>
          <div class="flex items-center justify-between mb-3">
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
              {{ item.category || 'Emergency' }}
            </span>
            <span 
              :class="[
                'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase',
                item.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500'
              ]"
            >
              <span class="w-1.5 h-1.5 rounded-full" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
              {{ item.is_active ? 'Active 24/7' : 'Disabled' }}
            </span>
          </div>

          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0 border border-rose-100">
              <PhoneCall class="w-5 h-5" />
            </div>
            <div>
              <h3 class="font-black text-base text-slate-900">{{ item.name_en || item.name_am }}</h3>
              <p class="text-xs text-slate-500 font-mono">AM: {{ item.name_am }}</p>
            </div>
          </div>

          <div class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase block">Hotline Number</span>
              <a :href="'tel:' + item.phone_number" class="text-lg font-black font-mono text-blue-700 hover:underline">
                {{ item.phone_number }}
              </a>
            </div>
            <span v-if="item.is_toll_free" class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-[10px] font-bold">
              Toll Free
            </span>
          </div>
        </div>

        <div v-if="isEditor" class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5">
          <button 
            @click="emit('openEdit', item)"
            class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
            title="Edit hotline"
          >
            <Edit class="w-3.5 h-3.5" />
          </button>
          <button 
            @click="emit('deleteItem', item.id)"
            class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
            title="Delete hotline"
          >
            <Trash2 class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
