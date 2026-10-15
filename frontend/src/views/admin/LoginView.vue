<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { Building2, Lock, Mail, ArrowRight, AlertCircle, Loader2 } from 'lucide-vue-next'

const router = useRouter()
const email = ref('admin@kombolchacity.gov.et')
const password = ref('Admin@2026!')
const errorMessage = ref('')
const loading = ref(false)

async function handleLogin() {
  errorMessage.value = ''
  loading.value = true

  try {
    const res = await api.post('/admin/login', {
      email: email.value,
      password: password.value
    })

    localStorage.setItem('kombolcha_admin_token', res.data.token)
    localStorage.setItem('kombolcha_admin_user', JSON.stringify(res.data.user))

    router.push('/admin')
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      errorMessage.value = err.response.data.message
    } else {
      errorMessage.value = 'Failed to connect to authentication server.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 border border-slate-100">
      <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center mx-auto mb-3 shadow-lg shadow-blue-500/30">
          <Building2 class="w-7 h-7" />
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Login</h2>
        <p class="text-xs text-slate-500 mt-1">Kombolcha Metropolitan Administration CMS</p>
      </div>

      <!-- Error Alert -->
      <div v-if="errorMessage" class="mb-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
        <AlertCircle class="w-4 h-4 shrink-0" />
        <span>{{ errorMessage }}</span>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Official Email</label>
          <div class="relative">
            <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
            <input 
              v-model="email" 
              type="email" 
              required 
              placeholder="admin@kombolchacity.gov.et" 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none" 
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
          <div class="relative">
            <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
            <input 
              v-model="password" 
              type="password" 
              required 
              placeholder="••••••••" 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none" 
            />
          </div>
        </div>

        <button 
          type="submit" 
          :disabled="loading"
          class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition-all disabled:opacity-50"
        >
          <Loader2 v-if="loading" class="w-4 h-4 animate-spin" />
          <span v-else>Sign In to Dashboard</span>
          <ArrowRight v-if="!loading" class="w-4 h-4" />
        </button>
      </form>

      <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <router-link to="/" class="text-xs text-blue-600 hover:underline">← Return to Public Website</router-link>
      </div>
    </div>
  </div>
</template>
