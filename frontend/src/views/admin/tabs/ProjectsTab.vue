<script setup>
import { Plus, HardHat, Edit, Trash2 } from 'lucide-vue-next'

const props = defineProps({
  projectsList: {
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

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem', 'updateProgress'])
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Infrastructure & Urban Development Projects</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ projectsList.length }} tracked
          </span>
        </h2>
      </div>
      <button @click="emit('openCreate')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-amber-500/20 transition-all shrink-0">
        <Plus class="w-4 h-4" />
        <span>Track New Project</span>
      </button>
    </div>

    <div v-if="projectsList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm shadow-xs">
      <HardHat class="w-10 h-10 mx-auto mb-3 opacity-30 text-slate-400" />
      <p class="font-bold text-slate-600 text-xs">No capital projects cataloged yet.</p>
      <p class="text-[11px] text-slate-400 mt-1">Register municipal construction and urban transformation projects.</p>
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div 
        v-for="proj in projectsList" 
        :key="proj.id" 
        class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:border-slate-300 hover:shadow-md transition-all flex flex-col justify-between relative overflow-hidden"
      >
        <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
        <div>
          <div class="flex items-center justify-between pt-1 mb-2">
            <div class="flex items-center gap-1.5 flex-wrap">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border" :class="proj.progress_percent === 100 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200'">
                {{ proj.status || (proj.progress_percent === 100 ? 'Completed' : 'Under Construction') }}
              </span>
              <span 
                :class="[
                  'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border',
                  proj.publication_status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200'
                ]"
              >
                <span class="w-1.5 h-1.5 rounded-full" :class="proj.publication_status === 'published' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                {{ proj.publication_status === 'published' ? 'Live Published' : 'Draft' }}
              </span>
            </div>
            <div class="flex items-center gap-1">
              <button 
                v-if="isEditor && proj.publication_status !== 'published'" 
                @click="emit('approveItem', proj)" 
                class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Approve & publish project"
              >
                Approve
              </button>
              <button 
                v-if="isEditor && proj.publication_status === 'published'" 
                @click="emit('unpublishItem', proj)" 
                class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold cursor-pointer transition shadow-2xs"
                title="Move to draft"
              >
                Unpublish
              </button>
              <button v-if="canEditItem(proj)" @click="emit('openEdit', proj)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-xl border border-slate-200 cursor-pointer transition" title="Edit Project">
                <Edit class="w-3.5 h-3.5" />
              </button>
              <button v-if="canDeleteItem(proj)" @click="emit('deleteItem', proj)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-xl border border-rose-200 cursor-pointer transition" title="Delete Project">
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>

          <h4 class="font-black text-base text-slate-900 leading-snug">{{ proj.title_en || proj.title_am }}</h4>
          <div class="text-xs text-slate-500 mt-1 font-mono flex items-center gap-1.5">
            <HardHat class="w-3.5 h-3.5 text-slate-400" />
            <span>Contractor: {{ proj.contractor_name || 'Kombolcha City Infrastructure Bureau' }}</span>
          </div>

          <!-- Progress Bar with Glowing Tip -->
          <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100">
            <div class="flex justify-between text-xs font-black text-slate-800 mb-1.5">
              <span class="uppercase tracking-wider text-[10px] text-slate-500">Verified Construction Completion</span>
              <span class="font-mono text-blue-700">{{ proj.progress_percent }}%</span>
            </div>
            <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden p-0.5">
              <div 
                class="h-full bg-gradient-to-r from-blue-600 via-indigo-600 to-amber-500 rounded-full transition-all duration-500 shadow-sm" 
                :style="{ width: `${Math.min(100, Math.max(0, proj.progress_percent))}%` }"
              ></div>
            </div>
          </div>
        </div>

        <!-- Quick Progress Adjustment -->
        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-slate-500 font-bold uppercase text-[10px]">Quick Milestone:</span>
          <div class="flex gap-1.5">
            <button @click="emit('updateProgress', proj, 25)" class="px-2.5 py-1 rounded-lg border font-bold text-xs cursor-pointer transition" :class="proj.progress_percent === 25 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200'">25%</button>
            <button @click="emit('updateProgress', proj, 50)" class="px-2.5 py-1 rounded-lg border font-bold text-xs cursor-pointer transition" :class="proj.progress_percent === 50 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200'">50%</button>
            <button @click="emit('updateProgress', proj, 75)" class="px-2.5 py-1 rounded-lg border font-bold text-xs cursor-pointer transition" :class="proj.progress_percent === 75 ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border-slate-200'">75%</button>
            <button @click="emit('updateProgress', proj, 100)" class="px-2.5 py-1 rounded-lg border font-bold text-xs cursor-pointer transition" :class="proj.progress_percent === 100 ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border-emerald-200'">100%</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
