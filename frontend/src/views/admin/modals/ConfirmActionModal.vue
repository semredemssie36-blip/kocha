<script setup>
import { Trash2, AlertCircle, X, Loader2, Check } from 'lucide-vue-next'

const props = defineProps({
  confirmModal: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close', 'confirm'])
</script>

<template>
  <!-- UNIVERSAL ACTION & DELETE CONFIRMATION CARD MODAL -->
  <div 
    v-if="confirmModal.open" 
    class="fixed inset-0 bg-[#071324]/80 backdrop-blur-md z-50 flex items-center justify-center p-4"
    @click.self="emit('close')"
  >
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 overflow-hidden relative">
      <!-- Top Accent Strip -->
      <div 
        class="h-1.5 w-full"
        :class="confirmModal.variant === 'danger' ? 'bg-gradient-to-r from-rose-500 via-rose-600 to-red-600' : 'bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500'"
      ></div>

      <div class="p-6 sm:p-7">
        <div class="flex items-start justify-between gap-4 mb-4">
          <!-- Icon Badge -->
          <div 
            class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-xs"
            :class="confirmModal.variant === 'danger' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-amber-50 text-amber-600 border border-amber-100'"
          >
            <Trash2 v-if="confirmModal.variant === 'danger'" class="w-6 h-6" />
            <AlertCircle v-else class="w-6 h-6" />
          </div>

          <!-- Close Button -->
          <button 
            type="button" 
            @click="emit('close')" 
            :disabled="confirmModal.loading"
            class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer transition-colors disabled:opacity-50"
            title="Close"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Card Title -->
        <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-snug">
          {{ confirmModal.title }}
        </h3>

        <!-- Highlighted Target Item Badge -->
        <div v-if="confirmModal.itemName" class="my-3.5 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center gap-2.5">
          <span 
            class="w-2.5 h-2.5 rounded-full shrink-0"
            :class="confirmModal.variant === 'danger' ? 'bg-rose-500' : 'bg-amber-500'"
          ></span>
          <span class="font-black text-slate-800 text-xs truncate break-all">
            "{{ confirmModal.itemName }}"
          </span>
        </div>

        <!-- Explanatory Message -->
        <p class="text-xs text-slate-500 leading-relaxed">
          {{ confirmModal.message }}
        </p>

        <!-- Card Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-5 mt-5 border-t border-slate-100">
          <button 
            v-if="confirmModal.variant !== 'alert'"
            type="button" 
            @click="emit('close')" 
            :disabled="confirmModal.loading"
            class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors cursor-pointer disabled:opacity-50"
          >
            {{ confirmModal.cancelText }}
          </button>

          <button 
            type="button" 
            @click="emit('confirm')" 
            :disabled="confirmModal.loading"
            class="px-5 py-2.5 rounded-xl text-xs font-black shadow-lg cursor-pointer flex items-center gap-2 transition-all disabled:opacity-50"
            :class="confirmModal.variant === 'danger' ? 'bg-rose-600 hover:bg-rose-700 text-white shadow-rose-600/30' : 'bg-amber-500 hover:bg-amber-600 text-slate-950 shadow-amber-500/30'"
          >
            <Loader2 v-if="confirmModal.loading" class="w-4 h-4 animate-spin" />
            <Trash2 v-else-if="confirmModal.variant === 'danger'" class="w-4 h-4" />
            <Check v-else class="w-4 h-4" />
            <span>{{ confirmModal.loading ? 'Processing...' : confirmModal.confirmText }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
