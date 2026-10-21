<script setup>
import { ref } from 'vue'
import { User, X, Lock, Camera, AlertCircle } from 'lucide-vue-next'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  profileForm: {
    type: Object,
    required: true
  },
  passwordForm: {
    type: Object,
    required: true
  },
  roleLabel: {
    type: String,
    default: 'Staff Member'
  },
  profileLoading: {
    type: Boolean,
    default: false
  },
  uploadingFile: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'saveProfile', 'changePassword', 'uploadAvatar'])

const profileActiveTab = ref('details')
</script>

<template>
  <!-- SECURITY & PROFILE SETTINGS MODAL -->
  <div v-if="isOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
      <!-- Modal Header -->
      <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
        <div>
          <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
            <User class="w-5 h-5 text-blue-600" />
            <span>Account Profile & Security</span>
          </h3>
          <p class="text-xs text-slate-500 mt-0.5">Manage your personal credentials, contact info, avatar and password.</p>
        </div>
        <button @click="emit('close')" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer">
          <X class="w-5 h-5" />
        </button>
      </div>

      <!-- Navigation Tabs -->
      <div class="flex border-b border-slate-200 px-6 bg-white">
        <button 
          type="button"
          @click="profileActiveTab = 'details'"
          :class="[
            'py-3.5 px-4 font-bold text-xs border-b-2 transition-colors cursor-pointer flex items-center gap-2',
            profileActiveTab === 'details' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-900'
          ]"
        >
          <User class="w-4 h-4" />
          <span>Profile Details</span>
        </button>
        <button 
          type="button"
          @click="profileActiveTab = 'password'"
          :class="[
            'py-3.5 px-4 font-bold text-xs border-b-2 transition-colors cursor-pointer flex items-center gap-2',
            profileActiveTab === 'password' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-900'
          ]"
        >
          <Lock class="w-4 h-4" />
          <span>Change Password</span>
        </button>
      </div>

      <!-- Tab 1: Profile Details Form -->
      <form v-if="profileActiveTab === 'details'" @submit.prevent="emit('saveProfile')" class="p-6 space-y-4">
        <!-- Avatar Preview & Upload -->
        <div class="flex items-center gap-5 pb-4 border-b border-slate-100">
          <div class="w-20 h-20 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 relative group">
            <img v-if="profileForm.avatar_path" :src="profileForm.avatar_path" class="w-full h-full object-cover" />
            <div v-else class="text-2xl font-black text-slate-400">
              {{ (profileForm.name || 'U').substring(0, 2).toUpperCase() }}
            </div>
          </div>
          <div class="space-y-1.5 flex-grow">
            <label class="block text-xs font-bold text-slate-700 uppercase">Profile Photo / Avatar</label>
            <div class="flex gap-2">
              <label class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold cursor-pointer transition-colors flex items-center gap-1.5">
                <Camera class="w-3.5 h-3.5" />
                <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                <input type="file" @change="emit('uploadAvatar', $event)" accept="image/*" class="hidden" />
              </label>
              <button v-if="profileForm.avatar_path" type="button" @click="profileForm.avatar_path = ''" class="px-2.5 py-1.5 rounded-xl text-rose-600 hover:bg-rose-50 text-xs font-bold">
                Remove
              </button>
            </div>
            <input v-model="profileForm.avatar_path" type="text" placeholder="Or enter image URL..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-mono" />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
            <input v-model="profileForm.name" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Email</label>
            <input v-model="profileForm.email" type="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
            <input v-model="profileForm.phone" type="text" placeholder="+251 9X XXX XXXX" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned Role</label>
            <div class="px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold uppercase text-slate-700">
              {{ roleLabel }}
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <button type="button" @click="emit('close')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
          <button type="submit" :disabled="profileLoading || uploadingFile" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 cursor-pointer disabled:opacity-50 transition-all">
            {{ profileLoading ? 'Saving...' : 'Save Profile Changes' }}
          </button>
        </div>
      </form>

      <!-- Tab 2: Change Password Form -->
      <form v-else-if="profileActiveTab === 'password'" @submit.prevent="emit('changePassword')" class="p-6 space-y-4">
        <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
          <AlertCircle class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
          <span>For security reasons, changing your password requires verifying your current password. Passwords must be at least 8 characters.</span>
        </div>

        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Current Password</label>
          <input v-model="passwordForm.current_password" type="password" required placeholder="Enter current password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">New Password</label>
            <input v-model="passwordForm.new_password" type="password" required minlength="8" placeholder="At least 8 characters" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" />
          </div>
          <div>
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Confirm New Password</label>
            <input v-model="passwordForm.new_password_confirmation" type="password" required minlength="8" placeholder="Repeat new password" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" />
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <button type="button" @click="emit('close')" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
          <button type="submit" :disabled="profileLoading" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 cursor-pointer disabled:opacity-50 transition-all">
            {{ profileLoading ? 'Updating...' : 'Update Password' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
