<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { 
  Building2, 
  Lock, 
  Mail, 
  ArrowRight, 
  AlertCircle, 
  Loader2, 
  Eye, 
  EyeOff, 
  KeyRound, 
  ArrowLeft,
  CheckCircle2
} from 'lucide-vue-next'

const router = useRouter()
const email = ref('admin@kombolchacity.gov.et')
const password = ref('Admin@2026!')
const showPassword = ref(false)
const errorMessage = ref('')
const loading = ref(false)

// If user already has an active session, redirect immediately to dashboard
onMounted(() => {
  const token = localStorage.getItem('kombolcha_admin_token')
  if (token) {
    router.replace('/admin')
  }
})

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

    // Use router.replace so the login page is not in the browser history stack
    router.replace('/admin')
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      errorMessage.value = err.response.data.message
    } else {
      errorMessage.value = 'Failed to connect to authentication server. Please check your credentials.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#071324] relative flex items-center justify-center p-4 sm:p-6 overflow-hidden">
    <!-- Ambient Civic Glow & Grid Overlay -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-20%,rgba(14,165,233,0.15),transparent)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_50%_120%,rgba(217,119,6,0.12),transparent)]"></div>
    <div class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px]"></div>

    <!-- Main Card Container -->
    <div class="w-full max-w-lg relative z-10">
      <div class="bg-white rounded-3xl shadow-2xl shadow-black/50 border border-slate-100 overflow-hidden">
        <!-- Card Header with Seal & Regal Authority -->
        <div class="bg-gradient-to-b from-[#0B1E36] to-[#071324] px-8 pt-10 pb-8 text-center text-white relative">
          <!-- Official Seal Halo -->
          <div class="relative inline-block mb-4">
            <div class="absolute -inset-2 bg-gradient-to-r from-amber-500/30 via-yellow-400/20 to-amber-600/30 rounded-full blur-md"></div>
            <div class="relative w-20 h-20 rounded-full p-1.5 bg-gradient-to-b from-amber-400 via-amber-200 to-amber-600 shadow-xl mx-auto flex items-center justify-center">
              <img 
                src="/assets/logo.png" 
                alt="Kombolcha Metropolitan Administration Seal" 
                class="w-full h-full object-contain rounded-full bg-white p-1"
              />
            </div>
          </div>

          <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">
            የኮምቦልቻ ከተማ አስተዳደር
          </h1>
          <p class="text-xs sm:text-sm font-bold text-amber-400/90 tracking-wide mt-1">
            Metropolitan Command & Content Portal
          </p>
        </div>

        <!-- Form Body -->
        <div class="p-8 sm:p-10">
          <!-- Error Alert Banner -->
          <div v-if="errorMessage" class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-start gap-3 shadow-sm animate-shake">
            <AlertCircle class="w-4 h-4 shrink-0 text-red-600 mt-0.5" />
            <div class="flex-1">
              <span class="font-bold block">Authentication Notice</span>
              <span>{{ errorMessage }}</span>
            </div>
          </div>

          <form @submit.prevent="handleLogin" class="space-y-5">
            <!-- Email Field -->
            <div>
              <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                Email
              </label>
              <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center absolute left-1.5 top-1.5 text-slate-400">
                  <Mail class="w-4 h-4 text-slate-500" />
                </div>
                <input 
                  v-model="email" 
                  type="email" 
                  required 
                  placeholder="example@gmail.com" 
                  class="w-full pl-14 pr-4 py-3 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-900 bg-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition-all shadow-xs" 
                />
              </div>
            </div>

            <!-- Password Field -->
            <div>
              <label class="block text-xs font-black uppercase tracking-wider text-slate-700 mb-2">
                Password
              </label>
              <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center absolute left-1.5 top-1.5 text-slate-400">
                  <Lock class="w-4 h-4 text-slate-500" />
                </div>
                <input 
                  v-model="password" 
                  :type="showPassword ? 'text' : 'password'" 
                  required 
                  placeholder="*********" 
                  class="w-full pl-14 pr-12 py-3 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-900 bg-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition-all shadow-xs" 
                />
                <button 
                  type="button" 
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 transition-colors p-1"
                  tabindex="-1"
                >
                  <EyeOff v-if="showPassword" class="w-4 h-4" />
                  <Eye v-else class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="loading"
              class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#0B1E36] via-[#102A4C] to-[#0B1E36] hover:from-[#102A4C] hover:to-[#0E223D] text-white font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-blue-950/20 hover:shadow-xl flex items-center justify-center gap-2.5 transition-all border border-amber-500/30 cursor-pointer disabled:opacity-50"
            >
              <Loader2 v-if="loading" class="w-4 h-4 animate-spin text-amber-400" />
              <KeyRound v-else class="w-4 h-4 text-amber-400" />
              <span>{{ loading ? 'Logging in...' : 'Login' }}</span>
              <ArrowRight v-if="!loading" class="w-4 h-4 text-slate-300" />
            </button>
          </form>

          <!-- Back to Public Site Link -->
          <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
            <router-link to="/" class="inline-flex items-center gap-1.5 text-blue-700 hover:text-blue-900 transition-colors">
              <ArrowLeft class="w-3.5 h-3.5" />
              <span>ወደ ዋናው ህዝባዊ ገጽ ተመለስ</span>
            </router-link>

          </div>
        </div>
      </div>

      <!-- Legal / Sovereign Notice -->
      <p class="text-center text-[11px] text-slate-500 mt-6 font-medium">
        © 2026 Kombolcha Metropolitan City Administration. All Rights Reserved.      
      </p>
    </div>
  </div>
</template>
