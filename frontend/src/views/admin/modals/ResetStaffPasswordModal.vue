<script setup>
import { KeyRound, X } from 'lucide-vue-next'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  targetStaffUser: {
    type: Object,
    default: null
  },
  resetStaffForm: {
    type: Object,
    required: true
  },
  resetStaffLoading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'submit', 'generatePassword'])
</script>

<template>
  <!-- SUPER ADMIN RESET STAFF PASSWORD OVERRIDE MODAL -->
  <div v-if="isOpen && targetStaffUser" class="fixed inset-0 bg-[#071324]/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 overflow-hidden relative">
      <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
      <div class="p-6 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between pt-5">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
            <KeyRound class="w-5 h-5" />
          </div>
          <div>
            <h3 class="text-base font-black text-slate-900">Reset Staff Password</h3>
            <p class="text-xs text-slate-500">Super Administrator override</p>
          </div>
        </div>
        <button @click="emit('close')" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer">
          <X class="w-5 h-5" />
        </button>
      </div>

      <form @submit.prevent="emit('submit')" class="p-6 space-y-4">
        <!-- Target Staff Info Card -->
        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-1.5">
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-bold">Staff Name:</span>
            <span class="font-extrabold text-slate-900">{{ targetStaffUser.name }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-bold">Email:</span>
            <span class="font-mono text-slate-800">{{ targetStaffUser.email }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-slate-500 font-bold">Role:</span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-100 text-blue-700 border border-blue-200">{{ targetStaffUser.role }}</span>
          </div>
        </div>

        <div class="space-y-1">
          <div class="flex items-center justify-between">
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1">New Password</label>
            <button type="button" @click="emit('generatePassword')" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1 cursor-pointer">
              <span>Generate Strong Password</span>
            </button>
          </div>
          <input v-model="resetStaffForm.password" type="text" required minlength="8" placeholder="Enter or generate password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" />
        </div>

        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1">Confirm New Password</label>
          <input v-model="resetStaffForm.password_confirmation" type="text" required minlength="8" placeholder="Repeat new password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" />
        </div>

        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-relaxed">
          <strong>Security Notice:</strong> Performing this reset will instantly terminate all existing login sessions for this account, requiring the staff member to sign in with this new password.
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <button type="button" @click="emit('close')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
          <button type="submit" :disabled="resetStaffLoading" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 cursor-pointer disabled:opacity-50 transition-all">
            {{ resetStaffLoading ? 'Resetting...' : 'Set New Password' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
