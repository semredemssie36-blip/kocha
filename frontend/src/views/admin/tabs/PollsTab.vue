<script setup>
import { Plus, RefreshCw, Edit, Trash2, Vote } from 'lucide-vue-next'

const props = defineProps({
  pollsList: {
    type: Array,
    required: true
  },
  adminT: {
    type: Function,
    default: (key) => key
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'resetPollVotes', 'togglePollActive', 'refresh'])
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900">{{ adminT('nav_polls') || 'Civic Opinion Polls' }}</h2>
        <p class="text-xs text-slate-500 mt-0.5">
          Create new citizen infrastructure polls, manage expiration dates, reset test/demo data, and view real-time verified ballot counts.
        </p>
      </div>
      <button 
        @click="emit('openCreate')" 
        class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-blue-600/20 shrink-0 transition-colors"
      >
        <Plus class="w-4 h-4" />
        <span>Create New Poll</span>
      </button>
    </div>

    <!-- Active Poll Spotlight Card -->
    <div v-if="pollsList.find(p => p.is_active)" class="bg-gradient-to-br from-slate-900 via-slate-850 to-blue-950 text-white rounded-3xl p-6 shadow-xl border border-slate-700/60 relative overflow-hidden">
      <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 relative z-10">
        <div class="space-y-3 flex-1">
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
              <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
              Live Active Poll on Homepage
            </span>
            <span v-if="pollsList.find(p => p.is_active)?.is_expired" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
              Concluded / Expired
            </span>
            <span v-else-if="pollsList.find(p => p.is_active)?.expires_at" class="px-2.5 py-1 rounded-full text-[10px] font-medium text-slate-300 bg-white/10 font-mono">
              Expires: {{ pollsList.find(p => p.is_active)?.expires_at }}
            </span>
          </div>

          <h3 class="text-xl sm:text-2xl font-black text-white leading-snug">
            {{ pollsList.find(p => p.is_active)?.question_en || pollsList.find(p => p.is_active)?.question_am }}
          </h3>
          <p v-if="pollsList.find(p => p.is_active)?.question_am" class="text-xs text-blue-200/90 font-medium">
            {{ pollsList.find(p => p.is_active)?.question_am }}
          </p>

          <!-- Options Breakdown -->
          <div class="space-y-2.5 pt-2 max-w-2xl">
            <div 
              v-for="opt in pollsList.find(p => p.is_active)?.options" 
              :key="opt.id"
              class="bg-white/5 border border-white/10 rounded-xl p-3 relative overflow-hidden"
            >
              <div 
                class="absolute top-0 bottom-0 left-0 bg-blue-500/25 transition-all duration-700 ease-out" 
                :style="{ width: ((opt.votes_count / (pollsList.find(p => p.is_active)?.total_votes || 1)) * 100) + '%' }"
              ></div>
              <div class="relative z-10 flex items-center justify-between text-xs gap-3">
                <div class="min-w-0">
                  <span class="font-bold text-white block truncate">{{ opt.option_text_en || opt.option_text_am }}</span>
                  <span v-if="opt.option_text_am && opt.option_text_en" class="text-[11px] text-slate-400 block truncate">{{ opt.option_text_am }}</span>
                </div>
                <div class="text-right shrink-0">
                  <span class="font-mono font-black text-blue-300">
                    {{ pollsList.find(p => p.is_active)?.total_votes > 0 ? Math.round((opt.votes_count / pollsList.find(p => p.is_active)?.total_votes) * 100) : 0 }}%
                  </span>
                  <span class="text-[10px] text-slate-400 block font-mono">({{ opt.votes_count }} votes)</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Poll Actions -->
        <div class="flex flex-col sm:flex-row lg:flex-col gap-2.5 shrink-0 relative z-10">
          <div class="p-3 bg-white/5 border border-white/10 rounded-2xl text-center">
            <div class="text-2xl font-black text-white font-mono">{{ pollsList.find(p => p.is_active)?.total_votes || 0 }}</div>
            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Ballots Cast</div>
          </div>

          <button 
            @click="emit('resetPollVotes', pollsList.find(p => p.is_active))"
            class="px-4 py-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 border border-amber-500/30 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
            title="Reset votes to start gathering fresh citizen data"
          >
            <RefreshCw class="w-3.5 h-3.5" />
            <span>Reset Votes to 0</span>
          </button>

          <button 
            @click="emit('openEdit', pollsList.find(p => p.is_active))"
            class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
          >
            <Edit class="w-3.5 h-3.5" />
            <span>Edit Active Poll</span>
          </button>
        </div>
      </div>
    </div>

    <!-- All Polls Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden relative">
      <div class="absolute top-0 inset-x-0 h-1 bg-[#1C398E]"></div>
      <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
          <h3 class="font-black text-sm text-slate-900">All Civic Polls & Ballots</h3>
          <p class="text-xs text-slate-400">Total of {{ pollsList.length }} polls registered in the municipal database.</p>
        </div>
        <button @click="emit('refresh')" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer hover:bg-slate-50" title="Refresh Polls">
          <RefreshCw class="w-4 h-4" />
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-400 font-black uppercase text-[10px] tracking-wider border-b border-slate-100">
            <tr>
              <th class="p-4">Poll Question</th>
              <th class="p-4">Options & Results</th>
              <th class="p-4">Total Votes</th>
              <th class="p-4">Expiration Date</th>
              <th class="p-4">Status</th>
              <th class="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            <tr v-for="item in pollsList" :key="item.id" class="hover:bg-slate-50/50 transition-colors">
              <td class="p-4 max-w-sm">
                <div class="font-bold text-slate-900 text-sm mb-0.5">{{ item.question_en || item.question_am }}</div>
                <div v-if="item.question_am && item.question_en" class="text-xs text-slate-500 font-medium">{{ item.question_am }}</div>
                <div v-if="item.description_en || item.description_am" class="text-[11px] text-slate-400 mt-1 line-clamp-1">
                  {{ item.description_en || item.description_am }}
                </div>
              </td>
              <td class="p-4">
                <div class="space-y-1">
                  <div v-for="opt in item.options" :key="opt.id" class="flex items-center gap-2 text-[11px]">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span class="text-slate-700 truncate max-w-xs">{{ opt.option_text_en || opt.option_text_am }}:</span>
                    <span class="font-mono font-bold text-slate-900">{{ opt.votes_count }}</span>
                  </div>
                </div>
              </td>
              <td class="p-4">
                <span class="font-mono font-black text-sm text-slate-900">{{ item.total_votes || 0 }}</span>
                <span class="text-[10px] text-slate-400 block font-medium">real verified votes</span>
              </td>
              <td class="p-4 font-mono text-xs text-slate-600">
                <div v-if="item.expires_at">
                  <div>{{ item.expires_at }}</div>
                  <span v-if="item.is_expired" class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    Day passed (Closed)
                  </span>
                  <span v-else class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Active until deadline
                  </span>
                </div>
                <div v-else class="text-slate-400 italic">No expiration date set</div>
              </td>
              <td class="p-4">
                <button 
                  type="button"
                  @click="emit('togglePollActive', item)"
                  class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider cursor-pointer transition-all border"
                  :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'"
                >
                  {{ item.is_active ? '● Active Live' : '○ Inactive' }}
                </button>
              </td>
              <td class="p-4 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button 
                    @click="emit('resetPollVotes', item)" 
                    class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors cursor-pointer"
                    title="Reset votes to 0 for real data"
                  >
                    <RefreshCw class="w-4 h-4" />
                  </button>
                  <button 
                    @click="emit('openEdit', item)" 
                    class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors cursor-pointer"
                    title="Edit Poll"
                  >
                    <Edit class="w-4 h-4" />
                  </button>
                  <button 
                    @click="emit('deleteItem', item)" 
                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors cursor-pointer"
                    title="Delete Poll"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!pollsList.length">
              <td colspan="6" class="p-12 text-center text-slate-400">
                <Vote class="w-10 h-10 mx-auto text-slate-300 mb-2" />
                <p class="font-bold text-slate-600">No civic polls created yet.</p>
                <p class="text-xs text-slate-400 mt-1">Click the button above to launch an opinion ballot for citizens.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
