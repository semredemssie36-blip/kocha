<script setup>
import { ref } from 'vue'
import { 
  Users, 
  Plus, 
  ShieldCheck, 
  Lock, 
  KeyRound, 
  UserCheck, 
  UserX, 
  Phone, 
  Mail 
} from 'lucide-vue-next'

const props = defineProps({
  staffList: {
    type: Array,
    required: true
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['openCreate', 'toggleStatus', 'openResetPassword'])

function roleBadge(role) {
  if (role === 'super_admin') return { label: 'Super Admin', class: 'bg-purple-50 text-purple-700 border-purple-200' }
  if (role === 'editor') return { label: 'Editor', class: 'bg-blue-50 text-blue-700 border-blue-200' }
  return { label: 'Author', class: 'bg-emerald-50 text-emerald-700 border-emerald-200' }
}
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Security & Administration</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Staff Access Matrix</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Municipal Staff & RBAC Accounts</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ staffList.length }} users
          </span>
        </h2>
      </div>

      <button 
        v-if="isSuperAdmin"
        @click="emit('openCreate')" 
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer shrink-0"
      >
        <Plus class="w-4 h-4" />
        <span>Create Staff Account</span>
      </button>
    </div>

    <!-- Staff Table (AWS Cloudscape Style) -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4">Staff Member</th>
              <th class="py-3 px-4">Role</th>
              <th class="py-3 px-4">Contact</th>
              <th class="py-3 px-4">Status</th>
              <th class="py-3 px-4 text-right" v-if="isSuperAdmin">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="user in staffList" :key="user.id" class="hover:bg-slate-50/70 transition-colors">
              <td class="py-3 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-xs">
                    {{ user.name?.charAt(0) || 'U' }}
                  </div>
                  <div>
                    <div class="font-bold text-slate-900 text-xs">{{ user.name }}</div>
                    <div class="text-[11px] text-slate-500 font-mono">{{ user.email }}</div>
                  </div>
                </div>
              </td>

              <td class="py-3 px-4">
                <span 
                  :class="[
                    'px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border',
                    roleBadge(user.role).class
                  ]"
                >
                  {{ roleBadge(user.role).label }}
                </span>
              </td>

              <td class="py-3 px-4 text-slate-500 text-[11px]">
                <div v-if="user.phone" class="font-mono">{{ user.phone }}</div>
                <div v-else class="text-slate-400 italic">No phone set</div>
              </td>

              <td class="py-3 px-4">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase',
                    user.is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="user.is_active ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                  {{ user.is_active ? 'Active' : 'Disabled' }}
                </span>
              </td>

              <td class="py-3 px-4 text-right" v-if="isSuperAdmin">
                <div class="inline-flex items-center gap-1.5">
                  <button 
                    @click="emit('openResetPassword', user)"
                    class="px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 text-[11px] font-bold transition cursor-pointer"
                    title="Reset Password"
                  >
                    Reset Password
                  </button>
                  <button 
                    @click="emit('toggleStatus', user.id)"
                    :class="[
                      'px-2.5 py-1 rounded-lg text-[11px] font-bold transition cursor-pointer',
                      user.is_active ? 'border border-amber-200 text-amber-700 hover:bg-amber-50' : 'bg-emerald-600 text-white hover:bg-emerald-700'
                    ]"
                  >
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
