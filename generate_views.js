const fs = require('fs');
const path = require('path');

const publicViewsDir = path.join(__dirname, 'frontend', 'src', 'views', 'public');
const adminViewsDir = path.join(__dirname, 'frontend', 'src', 'views', 'admin');

fs.mkdirSync(publicViewsDir, { recursive: true });
fs.mkdirSync(adminViewsDir, { recursive: true });

const views = [
  { name: 'AboutView.vue', title: 'About City', icon: 'Building2', desc: 'History, vision, and demographics of Kombolcha City.' },
  { name: 'MayorView.vue', title: "Mayor's Office", icon: 'Award', desc: 'Leadership message, biography, and executive mandate.' },
  { name: 'SubCitiesView.vue', title: 'Sub-Cities Administration', icon: 'MapPin', desc: 'Abishager, Borkena, Hasenager, and Biraro sub-cities.' },
  { name: 'OfficesView.vue', title: '22 Municipal Offices Directory', icon: 'Layers', desc: 'Contact desks, office locations, and leadership directory.' },
  { name: 'ServicesView.vue', title: 'Citizen E-Services', icon: 'FileText', desc: 'Step-by-step guides and text requirements for civic services.' },
  { name: 'NewsView.vue', title: 'News & Announcements', icon: 'TrendingUp', desc: 'Press releases, municipal reports, and civic updates.' },
  { name: 'TendersView.vue', title: 'Procurement & Tenders', icon: 'FileCheck', desc: 'Open public bids with live closing countdown and downloadable dossiers.' },
  { name: 'VacanciesView.vue', title: 'Job Vacancies', icon: 'Briefcase', desc: 'Public sector and industrial park employment opportunities.' },
  { name: 'ProjectsView.vue', title: 'Mega Projects & Infrastructure', icon: 'HardHat', desc: 'Public tracker for roads, drainage, and urban development projects.' },
  { name: 'ResourcesView.vue', title: 'Document & Resource Center', icon: 'Download', desc: 'Official bylaws, directives, annual budgets, and master plan documents.' },
  { name: 'ExploreView.vue', title: 'Explore Kombolcha', icon: 'Compass', desc: 'Tourism, Wollo heritage, hotels, dining, and Industrial Park spotlight.' },
  { name: 'ContactView.vue', title: 'Contact Us', icon: 'Phone', desc: 'Municipal office address, phone lines, and feedback form.' },
  { name: 'InternalBoardView.vue', title: 'Internal Board / Staff Bulletin', icon: 'MessageSquare', desc: 'Publicly readable municipal notices, internal memos, and staff discussion.' },
];

for (const v of views) {
  const filePath = path.join(publicViewsDir, v.name);
  if (!fs.existsSync(filePath)) {
    const content = `<script setup>
import { useLanguageStore } from '@/stores/language'
const langStore = useLanguageStore()
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-slate-900 rounded-2xl p-8 sm:p-12 text-white mb-10 shadow-lg">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold mb-3">
        Kombolcha Metropolitan Administration
      </div>
      <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">${v.title}</h2>
      <p class="text-slate-300 text-base max-w-2xl">${v.desc}</p>
    </div>

    <!-- Content Placeholder / Skeleton -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
        <div class="h-40 bg-slate-100 rounded-xl"></div>
        <div class="h-40 bg-slate-100 rounded-xl"></div>
        <div class="h-40 bg-slate-100 rounded-xl"></div>
      </div>
    </div>
  </div>
</template>
`;
    fs.writeFileSync(filePath, content, 'utf8');
  }
}

// Admin Login
fs.writeFileSync(path.join(adminViewsDir, 'LoginView.vue'), `<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Building2, Lock, Mail, ArrowRight } from 'lucide-vue-next'

const router = useRouter()
const email = ref('')
const password = ref('')

function handleLogin() {
  // Demo redirect to admin dashboard
  router.push('/admin')
}
</script>

<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 border border-slate-100">
      <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center mx-auto mb-3 shadow-lg shadow-blue-500/30">
          <Building2 class="w-7 h-7" />
        </div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Staff Portal Login</h2>
        <p class="text-xs text-slate-500 mt-1">Kombolcha Metropolitan Administration CMS</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Official Email</label>
          <div class="relative">
            <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
            <input v-model="email" type="email" required placeholder="admin@kombolchacity.gov.et" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none" />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password</label>
          <div class="relative">
            <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" />
            <input v-model="password" type="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none" />
          </div>
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition-all">
          <span>Sign In to Dashboard</span>
          <ArrowRight class="w-4 h-4" />
        </button>
      </form>

      <div class="mt-6 pt-6 border-t border-slate-100 text-center">
        <router-link to="/" class="text-xs text-blue-600 hover:underline">← Return to Public Website</router-link>
      </div>
    </div>
  </div>
</template>
`, 'utf8');

// Admin Dashboard
fs.writeFileSync(path.join(adminViewsDir, 'DashboardView.vue'), `<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Building2, 
  LayoutDashboard, 
  FileText, 
  FileCheck, 
  Users, 
  Settings, 
  LogOut, 
  AlertTriangle,
  Bell,
  Globe
} from 'lucide-vue-next'

const router = useRouter()
const activeTab = ref('overview')

function logout() {
  router.push('/admin/login')
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex">
    <!-- Admin Sidebar -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0">
      <div>
        <div class="p-6 border-b border-slate-800 flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
            <Building2 class="w-5 h-5" />
          </div>
          <div>
            <h3 class="text-sm font-bold text-white tracking-tight">Kombolcha CMS</h3>
            <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded bg-blue-900 text-blue-300">Super Admin</span>
          </div>
        </div>

        <nav class="p-4 space-y-1 text-sm font-medium">
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white">
            <LayoutDashboard class="w-4 h-4" />
            <span>Dashboard</span>
          </a>
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <FileText class="w-4 h-4" />
            <span>News & Media</span>
          </a>
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <FileCheck class="w-4 h-4" />
            <span>Tenders (Procurement)</span>
          </a>
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <Building2 class="w-4 h-4" />
            <span>22 Offices & Sub-Cities</span>
          </a>
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <Users class="w-4 h-4" />
            <span>Staff Roles & Accounts</span>
          </a>
          <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <Settings class="w-4 h-4" />
            <span>System Settings</span>
          </a>
        </nav>
      </div>

      <div class="p-4 border-t border-slate-800 space-y-2">
        <router-link to="/" class="flex items-center gap-2 text-xs text-slate-400 hover:text-white px-3 py-2">
          <Globe class="w-4 h-4" />
          <span>View Live Public Site</span>
        </router-link>
        <button @click="logout" class="flex items-center gap-2 text-xs text-red-400 hover:text-red-300 px-3 py-2 w-full text-left">
          <LogOut class="w-4 h-4" />
          <span>Sign Out</span>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-grow flex flex-col">
      <!-- Top Bar -->
      <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-900">Dashboard Overview</h2>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            System Live & Healthy
          </div>
        </div>
      </header>

      <!-- Dashboard Cards -->
      <main class="p-8 space-y-8 flex-grow">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total News Articles</span>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">18</div>
            <span class="text-xs text-emerald-600 font-medium mt-1 block">↑ 3 published this week</span>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase">Active Tenders</span>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">2</div>
            <span class="text-xs text-amber-600 font-medium mt-1 block">Closing in 6 days</span>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase">Registered Offices</span>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">22</div>
            <span class="text-xs text-slate-500 font-medium mt-1 block">4 Sub-Cities active</span>
          </div>

          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase">Emergency Alerts</span>
            <div class="text-3xl font-extrabold text-emerald-600 mt-2">Normal</div>
            <span class="text-xs text-slate-500 font-medium mt-1 block">No active danger alert</span>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
`, 'utf8');

console.log('All public and admin views generated successfully!');
