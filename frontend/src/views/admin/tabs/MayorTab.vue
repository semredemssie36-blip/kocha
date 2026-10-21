<script setup>
import { Users, Upload } from 'lucide-vue-next'

const props = defineProps({
  mayorData: {
    type: Object,
    required: true
  },
  uploadingFile: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['updateProfile', 'uploadPhoto'])
</script>

<template>
  <div class="space-y-6 max-w-4xl">
    <div>
      <h2 class="text-2xl font-black text-slate-900 tracking-tight">Office of the Mayor & Executive Keynote</h2>
      <p class="text-xs text-slate-500 mt-1">Configure the official Mayoral portrait, keynote greeting, and executive biography published to citizens and diplomatic delegations.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-6 relative overflow-hidden">
      <div class="flex flex-col sm:flex-row sm:items-center gap-6 pb-6 border-b border-slate-100 pt-2">
        <img v-if="mayorData.portrait_path" :src="mayorData.portrait_path" alt="Mayor" class="w-28 h-28 object-cover object-top rounded-2xl border-2 border-amber-500 shadow-md shrink-0 bg-slate-100" />
        <div v-else class="w-28 h-28 rounded-2xl border-2 border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-400 shrink-0">
          <Users class="w-10 h-10 opacity-30 text-blue-600" />
          <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase">No Photo</span>
        </div>
        <div class="flex-1 space-y-2">
          <h3 class="text-xl font-black text-slate-900">{{ mayorData.name_en || mayorData.name_am }}</h3>
          <p class="text-xs text-slate-500 font-mono">Current Mayoral Term: {{ mayorData.term_start || '2021' }} - {{ mayorData.term_end || 'Present' }}</p>

          <!-- Mayor Photo Upload Input -->
          <div class="pt-2">
            <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1.5">Official Mayoral Portrait</label>
            <div class="flex items-center gap-3 max-w-md">
              <input 
                v-model="mayorData.portrait_path" 
                type="text" 
                placeholder="/assets/... or click Upload Photo" 
                class="flex-1 px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none" 
              />
              <label class="px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                <Upload class="w-4 h-4" />
                <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                <input type="file" @change="emit('uploadPhoto', $event)" class="hidden" accept="image/*" />
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5 flex items-center justify-between">
            <span>Mayor Full Name (Amharic) *</span>
            <span class="text-slate-400 font-normal">የከንቲባው ሙሉ ስም</span>
          </label>
          <input v-model="mayorData.name_am" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
        </div>
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Mayor Full Name (English)</label>
          <input v-model="mayorData.name_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Term Start Year</label>
          <input v-model="mayorData.term_start" type="text" placeholder="2021" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
        </div>
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Term End Year</label>
          <input v-model="mayorData.term_end" type="text" placeholder="Present" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
        </div>
      </div>

      <div>
        <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5 flex items-center justify-between">
          <span>Keynote Greeting Letter (Amharic) *</span>
          <span class="text-slate-400 font-normal">የእንኳን ደህና መጡ መልእክት</span>
        </label>
        <textarea v-model="mayorData.welcome_message_am" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition"></textarea>
      </div>

      <div>
        <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Keynote Greeting Letter (English)</label>
        <textarea v-model="mayorData.welcome_message_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition"></textarea>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Executive Biography (Amharic)</label>
          <textarea v-model="mayorData.biography_am" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition"></textarea>
        </div>
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Executive Biography (English)</label>
          <textarea v-model="mayorData.biography_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition"></textarea>
        </div>
      </div>

      <div class="pt-3">
        <button @click="emit('updateProfile')" class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs cursor-pointer shadow-md shadow-amber-500/20 transition-all">
          Save Mayoral Profile Changes
        </button>
      </div>
    </div>
  </div>
</template>
