<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { 
  Building2, 
  LayoutDashboard, 
  FileText, 
  FileCheck, 
  Users, 
  Settings, 
  LogOut, 
  MessageSquare,
  Globe,
  HardHat,
  MapPin,
  Plus,
  CheckCircle2,
  AlertCircle,
  X,
  Mail,
  Phone,
  Clock,
  ShieldCheck,
  Send,
  Eye,
  RefreshCw,
  Search,
  Bell,
  ChevronDown,
  ChevronRight,
  Briefcase,
  Layers,
  Award,
  Calendar,
  DollarSign,
  Trash2,
  Edit,
  FolderOpen,
  Sliders,
  Check,
  Activity,
  Zap,
  Upload,
  Image as ImageIcon,
  Video,
  Film,
  Play
} from 'lucide-vue-next'

const router = useRouter()
const activeSection = ref('dashboard')
const activeSubTab = ref('overview')
const adminLang = ref('en')

// Global Search in Admin
const adminSearch = ref('')

// KPIs & Stats
const stats = ref({
  news_count: 0,
  published_news: 0,
  pending_drafts: 0,
  active_tenders: 0,
  offices_count: 22,
  unread_messages: 0,
  projects_count: 0,
  monthly_views: '24,520'
})

const currentUser = ref(JSON.parse(localStorage.getItem('kombolcha_admin_user') || '{}'))

// Data Collections
const newsList = ref([])
const tendersList = ref([])
const vacanciesList = ref([])
const servicesList = ref([])
const projectsList = ref([])
const resourcesList = ref([])
const officesList = ref([])
const messagesList = ref([])
const staffList = ref([])
const mayorData = ref({})
const leadershipList = ref([])
const galleryList = ref([])
const heroSlidesList = ref([])
const siteSettings = ref({
  site_name_en: 'Kombolcha City Administration',
  site_name_am: 'የኮምቦልቻ ከተማ አስተዳደር',
  emergency_notice_en: '',
  is_maintenance: 0
})

// Loading & Modal States
const loading = ref(false)
const uploadingFile = ref(false)
const modalType = ref('') // 'news', 'tender', 'vacancy', 'service', 'project', 'resource', 'staff', 'office', 'leadership', 'hero_slide', 'gallery'
const submitting = ref(false)
const alertToast = ref('')

// Form Models
const newsForm = reactive({
  title_am: '', title_en: '', title_om: '', category: 'General',
  content_am: '', content_en: '', content_om: '', status: 'published',
  thumbnail_path: ''
})

const heroSlideForm = reactive({
  title_am: '', title_en: '', title_om: '',
  subtitle_am: '', subtitle_en: '', subtitle_om: '',
  tag_am: '', tag_en: '', tag_om: '',
  button_text_am: '', button_text_en: '', button_text_om: '',
  button_url: '/explore', image_path: '', order: 0, is_active: 1
})

const galleryForm = reactive({
  media_type: 'image',
  category: 'urban',
  media_path_or_url: '',
  thumbnail_url: '',
  caption_am: '',
  caption_en: '',
  caption_om: '',
  order: 0
})

async function uploadFileFor(event, targetObj, fieldName) {
  const file = event.target.files[0]
  if (!file) return
  uploadingFile.value = true
  try {
    const formData = new FormData()
    formData.append('file', file)
    const res = await api.post('/admin/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    targetObj[fieldName] = res.data.path || res.data.url
    showToast('Media file uploaded successfully!')
  } catch (err) {
    alert(err.response?.data?.message || 'File upload failed.')
  } finally {
    uploadingFile.value = false
  }
}

const tenderForm = reactive({
  reference_number: '', title_am: '', title_en: '', title_om: '',
  category: 'Works', bid_bond_amount: 50000, closing_datetime: '', status: 'open'
})

const vacancyForm = reactive({
  title_am: '', title_en: '', title_om: '', position_code: '',
  employment_type: 'Full-Time', salary_grade: 'PS-09', requirements_am: '', deadline: ''
})

const serviceForm = reactive({
  title_am: '', title_en: '', title_om: '', type: 'step',
  summary_am: '', fee_amount: 50, estimated_time_am: '3 Business Days'
})

const projectForm = reactive({
  title_am: '', title_en: '', title_om: '', budget_etb: 10000000,
  contractor_name: '', progress_percent: 10, status: 'in_progress'
})

const resourceForm = reactive({
  title_am: '', title_en: '', title_om: '', category: 'Directives', year: 2026, file_path: '', file_size_formatted: '2.4 MB'
})

const staffForm = reactive({
  name: '', email: '', password: '', role: 'editor', phone: ''
})

const officeForm = reactive({
  name_am: '', name_en: '', name_om: '', phone: '', email: '', building_location_am: '', room_number: ''
})

const leadershipForm = reactive({
  type: 'cabinet', name_am: '', name_en: '', name_om: '', position_am: '', position_en: '', position_om: '', photo_path: '', phone: '', email: ''
})

function showToast(msg) {
  alertToast.value = msg
  setTimeout(() => { alertToast.value = '' }, 4000)
}

// Data Fetchers
async function fetchAllAdminData() {
  loading.value = true
  try {
    const [statsRes, newsRes, tendersRes, vacanciesRes, projectsRes, resourcesRes, officesRes, msgsRes, mayorRes, leadershipRes, settingsRes, galleryRes, slidesRes, servicesRes] = await Promise.all([
      api.get('/admin/stats'),
      api.get('/news'),
      api.get('/tenders'),
      api.get('/vacancies'),
      api.get('/projects'),
      api.get('/resources'),
      api.get('/offices'),
      api.get('/admin/messages'),
      api.get('/mayor'),
      api.get('/leadership'),
      api.get('/admin/settings').catch(() => ({ data: {} })),
      api.get('/admin/gallery').catch(() => ({ data: [] })),
      api.get('/admin/hero-slides').catch(() => ({ data: [] })),
      api.get('/services').catch(() => ({ data: [] }))
    ])
    stats.value = { ...stats.value, ...statsRes.data }
    newsList.value = newsRes.data?.data || newsRes.data || []
    tendersList.value = tendersRes.data || []
    vacanciesList.value = vacanciesRes.data || []
    projectsList.value = projectsRes.data || []
    resourcesList.value = resourcesRes.data || []
    officesList.value = officesRes.data || []
    servicesList.value = servicesRes.data || []
    messagesList.value = msgsRes.data?.data || msgsRes.data || []
    mayorData.value = mayorRes.data || {}
    leadershipList.value = leadershipRes.data || []
    galleryList.value = galleryRes.data || []
    heroSlidesList.value = slidesRes.data || []
    if (settingsRes.data && Object.keys(settingsRes.data).length) {
      siteSettings.value = { ...siteSettings.value, ...settingsRes.data }
    }
    if (currentUser.value.role === 'super_admin') {
      const staffRes = await api.get('/admin/staff')
      staffList.value = staffRes.data || []
    }
  } catch (err) {
    console.error('Error fetching admin data:', err)
  } finally {
    loading.value = false
  }
}

const editingId = ref(null)

function editItem(type, item) {
  editingId.value = item.id
  modalType.value = type
  if (type === 'news') {
    newsForm.title_am = item.title_am || ''
    newsForm.title_en = item.title_en || ''
    newsForm.title_om = item.title_om || ''
    newsForm.category = item.category || 'General'
    newsForm.content_am = item.content_am || ''
    newsForm.content_en = item.content_en || ''
    newsForm.content_om = item.content_om || ''
    newsForm.status = item.status || 'published'
  } else if (type === 'tender') {
    tenderForm.reference_number = item.reference_number || ''
    tenderForm.title_am = item.title_am || ''
    tenderForm.title_en = item.title_en || ''
    tenderForm.title_om = item.title_om || ''
    tenderForm.category = item.category || 'Goods'
    tenderForm.bid_bond_amount = item.bid_bond_amount || 0
    tenderForm.closing_datetime = item.closing_datetime ? item.closing_datetime.replace(' ', 'T').substring(0, 16) : ''
    tenderForm.status = item.status || 'open'
  } else if (type === 'vacancy') {
    vacancyForm.title_am = item.title_am || ''
    vacancyForm.title_en = item.title_en || ''
    vacancyForm.title_om = item.title_om || ''
    vacancyForm.position_code = item.position_code || ''
    vacancyForm.employment_type = item.employment_type || 'Permanent'
    vacancyForm.salary_grade = item.salary_grade || ''
    vacancyForm.requirements_am = item.requirements_am || ''
    vacancyForm.deadline = item.deadline ? item.deadline.substring(0, 10) : ''
  } else if (type === 'service') {
    serviceForm.title_am = item.title_am || ''
    serviceForm.title_en = item.title_en || ''
    serviceForm.title_om = item.title_om || ''
    serviceForm.type = item.type || 'text'
    serviceForm.summary_am = item.summary_am || ''
    serviceForm.fee_amount = item.fee_amount || 0
    serviceForm.estimated_time_am = item.estimated_time_am || ''
  } else if (type === 'project') {
    projectForm.title_am = item.title_am || ''
    projectForm.title_en = item.title_en || ''
    projectForm.title_om = item.title_om || ''
    projectForm.budget_etb = item.budget_etb || 0
    projectForm.contractor_name = item.contractor_name || ''
    projectForm.progress_percent = item.progress_percent || 0
    projectForm.status = item.status || 'in_progress'
  } else if (type === 'resource') {
    resourceForm.title_am = item.title_am || ''
    resourceForm.title_en = item.title_en || ''
    resourceForm.title_om = item.title_om || ''
    resourceForm.category = item.category || 'Directives'
    resourceForm.year = item.year || 2026
    resourceForm.file_path = item.file_path || ''
    resourceForm.file_size_formatted = item.file_size_formatted || '2.1 MB'
  } else if (type === 'office') {
    officeForm.name_am = item.name_am || ''
    officeForm.name_en = item.name_en || ''
    officeForm.name_om = item.name_om || ''
    officeForm.phone = item.phone || ''
    officeForm.email = item.email || ''
    officeForm.building_location_am = item.building_location_am || ''
    officeForm.room_number = item.room_number || ''
  } else if (type === 'leadership') {
    leadershipForm.type = item.type || 'cabinet'
    leadershipForm.name_am = item.name_am || ''
    leadershipForm.name_en = item.name_en || ''
    leadershipForm.name_om = item.name_om || ''
    leadershipForm.position_am = item.position_am || ''
    leadershipForm.position_en = item.position_en || ''
    leadershipForm.position_om = item.position_om || ''
    leadershipForm.photo_path = item.photo_path || ''
    leadershipForm.phone = item.phone || ''
    leadershipForm.email = item.email || ''
  } else if (type === 'hero_slide') {
    heroSlideForm.title_am = item.title_am || ''
    heroSlideForm.title_en = item.title_en || ''
    heroSlideForm.title_om = item.title_om || ''
    heroSlideForm.subtitle_am = item.subtitle_am || ''
    heroSlideForm.subtitle_en = item.subtitle_en || ''
    heroSlideForm.subtitle_om = item.subtitle_om || ''
    heroSlideForm.tag_am = item.tag_am || ''
    heroSlideForm.tag_en = item.tag_en || ''
    heroSlideForm.tag_om = item.tag_om || ''
    heroSlideForm.button_text_am = item.button_text_am || ''
    heroSlideForm.button_text_en = item.button_text_en || ''
    heroSlideForm.button_text_om = item.button_text_om || ''
    heroSlideForm.button_url = item.button_url || '/explore'
    heroSlideForm.image_path = item.image_path || ''
    heroSlideForm.order = item.order || 0
    heroSlideForm.is_active = item.is_active !== undefined ? item.is_active : 1
  } else if (type === 'gallery') {
    galleryForm.media_type = item.media_type || 'image'
    galleryForm.category = item.category || 'urban'
    galleryForm.media_path_or_url = item.media_path_or_url || ''
    galleryForm.thumbnail_url = item.thumbnail_url || ''
    galleryForm.caption_am = item.caption_am || ''
    galleryForm.caption_en = item.caption_en || ''
    galleryForm.caption_om = item.caption_om || ''
    galleryForm.order = item.order || 0
  }
}

// Generic Submit Handler for Create & Edit
async function submitActiveModal() {
  submitting.value = true
  try {
    const isEdit = !!editingId.value
    const id = editingId.value
    if (modalType.value === 'news') {
      if (isEdit) await api.put(`/admin/news/${id}`, newsForm)
      else await api.post('/admin/news', newsForm)
      showToast(isEdit ? 'Article updated successfully!' : 'News article published successfully!')
    } else if (modalType.value === 'tender') {
      if (isEdit) await api.put(`/admin/tenders/${id}`, tenderForm)
      else await api.post('/admin/tenders', tenderForm)
      showToast(isEdit ? 'Tender updated successfully!' : 'Procurement tender published!')
    } else if (modalType.value === 'vacancy') {
      if (isEdit) await api.put(`/admin/vacancies/${id}`, vacancyForm)
      else await api.post('/admin/vacancies', vacancyForm)
      showToast(isEdit ? 'Vacancy updated successfully!' : 'Civil service vacancy posted!')
    } else if (modalType.value === 'service') {
      if (isEdit) await api.put(`/admin/services/${id}`, serviceForm)
      else await api.post('/admin/services', serviceForm)
      showToast(isEdit ? 'Service updated successfully!' : 'Municipal service registered!')
    } else if (modalType.value === 'project') {
      if (isEdit) await api.put(`/admin/projects/${id}`, projectForm)
      else await api.post('/admin/projects', projectForm)
      showToast(isEdit ? 'Project updated successfully!' : 'Mega infrastructure project cataloged!')
    } else if (modalType.value === 'resource') {
      if (isEdit) await api.put(`/admin/resources/${id}`, resourceForm)
      else await api.post('/admin/resources', resourceForm)
      showToast(isEdit ? 'Directive updated successfully!' : 'Official directive document uploaded!')
    } else if (modalType.value === 'staff') {
      await api.post('/admin/staff', staffForm)
      showToast('Municipal staff account created!')
    } else if (modalType.value === 'office') {
      if (isEdit) await api.put(`/admin/offices/${id}`, officeForm)
      else await api.post('/admin/offices', officeForm)
      showToast(isEdit ? 'Office updated successfully!' : 'Municipal office directory updated!')
    } else if (modalType.value === 'leadership') {
      if (isEdit) await api.put(`/admin/leadership/${id}`, leadershipForm)
      else await api.post('/admin/leadership', leadershipForm)
      showToast(isEdit ? 'Member updated successfully!' : 'Leadership member registered!')
    } else if (modalType.value === 'hero_slide') {
      if (isEdit) await api.put(`/admin/hero-slides/${id}`, heroSlideForm)
      else await api.post('/admin/hero-slides', heroSlideForm)
      showToast(isEdit ? 'Hero slide updated successfully!' : 'Hero slide added successfully!')
    } else if (modalType.value === 'gallery') {
      if (isEdit) await api.put(`/admin/gallery/${id}`, galleryForm)
      else await api.post('/admin/gallery', galleryForm)
      showToast(isEdit ? 'Gallery media updated successfully!' : 'Gallery media added successfully!')
    }
    modalType.value = ''
    editingId.value = null
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Error executing admin action.')
  } finally {
    submitting.value = false
  }
}

// Delete Helpers with automatic live refresh
async function deleteItem(endpoint, id, name) {
  if (!confirm(`Are you sure you want to delete "${name}"? This action removes it permanently from the database and public portal.`)) return
  try {
    await api.delete(`${endpoint}/${id}`)
    showToast(`"${name}" deleted successfully.`)
    await fetchAllAdminData()
  } catch (err) {
    alert('Failed to delete item.')
  }
}

async function updateMessageStatus(id, newStatus) {
  try {
    await api.patch(`/admin/messages/${id}`, { status: newStatus })
    showToast(`Inquiry marked as ${newStatus}`)
    const msg = messagesList.value.find(m => m.id === id)
    if (msg) msg.status = newStatus
    stats.value.unread_messages = messagesList.value.filter(m => m.status === 'unread').length
  } catch (err) {
    console.error(err)
  }
}

async function updateMayorProfile() {
  try {
    await api.put('/admin/mayor', mayorData.value)
    showToast('Mayor profile and keynote saved!')
  } catch (err) {
    alert('Error saving mayor profile.')
  }
}

async function updateSettings() {
  try {
    await api.post('/admin/settings', siteSettings.value)
    showToast('Municipal portal settings & maintenance mode updated!')
  } catch (err) {
    alert('Error updating settings.')
  }
}

async function updateProgress(project, newPct) {
  try {
    await api.patch(`/admin/projects/${project.id}/progress`, { progress_percent: newPct })
    project.progress_percent = newPct
    showToast(`Project progress updated to ${newPct}%`)
  } catch (err) {
    console.error(err)
  }
}

async function logout() {
  try { await api.post('/admin/logout') } catch (e) {}
  localStorage.removeItem('kombolcha_admin_token')
  localStorage.removeItem('kombolcha_admin_user')
  router.push('/admin/login')
}

onMounted(() => {
  fetchAllAdminData()
})
</script>

<template>
  <div class="min-h-screen bg-slate-100 flex flex-col font-sans text-slate-800">
    <!-- Top Bar matching diagram -->
    <header class="bg-slate-900 border-b border-slate-800 text-white px-6 py-3 flex items-center justify-between sticky top-0 z-30 shadow-md">
      <!-- Left: Logo & Portal Title -->
      <div class="flex items-center gap-3">
        <img src="/assets/city_logo.png" alt="Seal" class="w-10 h-10 object-contain rounded-full border border-blue-400/40" />
        <div>
          <h1 class="text-sm font-black tracking-tight text-white flex items-center gap-2">
            Kombolcha Admin Portal
            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-blue-600/30 text-blue-300 border border-blue-500/30">
              CMS 2026
            </span>
          </h1>
          <p class="text-[10px] text-slate-400">Kombolcha Metropolitan Administration</p>
        </div>
      </div>

      <!-- Center: Admin Search Bar -->
      <div class="hidden md:flex items-center relative w-96">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="adminSearch" 
          type="text" 
          placeholder="Search articles, tenders, offices, citizen messages..." 
          class="w-full bg-slate-800/80 border border-slate-700 rounded-xl pl-9 pr-4 py-1.5 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400"
        />
      </div>

      <!-- Right Actions: Language, Notifications, Profile -->
      <div class="flex items-center gap-4">
        <!-- Live Public Site Shortcut -->
        <router-link to="/" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
          <Globe class="w-3.5 h-3.5 text-emerald-400" />
          <span>Live Site</span>
        </router-link>

        <!-- Admin Language Toggle [AM | EN] -->
        <div class="flex items-center bg-slate-800 rounded-xl p-1 border border-slate-700 text-xs font-bold">
          <button 
            @click="adminLang = 'en'" 
            :class="adminLang === 'en' ? 'bg-blue-600 text-white px-2 py-0.5 rounded-lg' : 'text-slate-400 px-2 py-0.5'"
          >
            EN
          </button>
          <button 
            @click="adminLang = 'am'" 
            :class="adminLang === 'am' ? 'bg-blue-600 text-white px-2 py-0.5 rounded-lg' : 'text-slate-400 px-2 py-0.5'"
          >
            አማ
          </button>
        </div>

        <!-- Notifications Bell -->
        <button class="relative p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors cursor-pointer">
          <Bell class="w-4 h-4" />
          <span v-if="stats.unread_messages > 0" class="absolute -top-1 -right-1 px-1 min-w-4 h-4 rounded-md bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center">
            {{ stats.unread_messages }}
          </span>
        </button>

        <!-- Staff Profile & Logout Dropdown -->
        <div class="flex items-center gap-3 pl-2 border-l border-slate-800">
          <div class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
            {{ (currentUser.name || 'Admin').substring(0, 2).toUpperCase() }}
          </div>
          <div class="hidden lg:block text-left">
            <div class="text-xs font-bold text-white leading-tight">{{ currentUser.name || 'Municipal Admin' }}</div>
            <div class="text-[10px] text-blue-400 font-mono capitalize">{{ currentUser.role || 'Staff' }}</div>
          </div>
          <button @click="logout" title="Sign Out" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 cursor-pointer transition-colors">
            <LogOut class="w-4 h-4" />
          </button>
        </div>
      </div>
    </header>

    <!-- Success Toast Notification -->
    <div v-if="alertToast" class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-emerald-500 flex items-center gap-3 text-xs font-bold">
      <CheckCircle2 class="w-4 h-4 text-emerald-400" />
      <span>{{ alertToast }}</span>
    </div>

    <!-- Workspace Container (Sidebar + Dynamic Workspace Area) -->
    <div class="flex flex-grow overflow-hidden">
      <!-- Sidebar Navigation -->
      <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800 overflow-y-auto">
        <nav class="p-4 space-y-1.5 text-xs font-bold">
          <button 
            @click="activeSection = 'dashboard'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <LayoutDashboard class="w-4 h-4" />
            <span>Dashboard Overview</span>
          </button>

          <!-- Media Management Category -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            Media & Visuals
          </div>
          <button 
            @click="activeSection = 'hero_slides'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'hero_slides' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Sliders class="w-4 h-4" />
            <span>Hero Slider Manager</span>
          </button>
          <button 
            @click="activeSection = 'gallery'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'gallery' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Film class="w-4 h-4" />
            <span>Media Gallery (3.10)</span>
          </button>
          <button 
            @click="activeSection = 'news'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'news' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FileText class="w-4 h-4" />
            <span>News & Press Releases</span>
          </button>
          <button 
            @click="activeSection = 'tenders'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'tenders' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FileCheck class="w-4 h-4" />
            <span>Public Tenders</span>
          </button>
          <button 
            @click="activeSection = 'vacancies'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'vacancies' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Briefcase class="w-4 h-4" />
            <span>Job Vacancies</span>
          </button>

          <!-- Governance Management Category -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            Governance
          </div>
          <button 
            @click="activeSection = 'mayor'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'mayor' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Award class="w-4 h-4" />
            <span>Mayor Profile & Keynote</span>
          </button>
          <button 
            @click="activeSection = 'leadership'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'leadership' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Users class="w-4 h-4" />
            <span>Cabinet & Council</span>
          </button>
          <button 
            @click="activeSection = 'offices'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'offices' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Building2 class="w-4 h-4" />
            <span>22 Offices & Directory</span>
          </button>

          <!-- Civic Services Category -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            Services & Projects
          </div>
          <button 
            @click="activeSection = 'services'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'services' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Layers class="w-4 h-4" />
            <span>Text & Step Services</span>
          </button>
          <button 
            @click="activeSection = 'projects'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'projects' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <HardHat class="w-4 h-4" />
            <span>Mega Infrastructure</span>
          </button>
          <button 
            @click="activeSection = 'resources'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'resources' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FolderOpen class="w-4 h-4" />
            <span>Resources & Directives</span>
          </button>

          <!-- Citizen Interaction Category -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            Civic Feedback
          </div>
          <button 
            @click="activeSection = 'messages'"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'messages' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <MessageSquare class="w-4 h-4" />
              <span>Citizen Inquiries</span>
            </div>
            <span v-if="stats.unread_messages > 0" class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black">
              {{ stats.unread_messages }}
            </span>
          </button>

          <!-- System Administration -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            Administration
          </div>
          <button 
            v-if="currentUser.role === 'super_admin'"
            @click="activeSection = 'staff'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'staff' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <ShieldCheck class="w-4 h-4" />
            <span>Staff Accounts</span>
          </button>
          <button 
            @click="activeSection = 'settings'"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'settings' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Sliders class="w-4 h-4" />
            <span>Site Settings & Maintenance</span>
          </button>
        </nav>
      </aside>

      <!-- Dynamic Workspace Area -->
      <main class="flex-grow p-8 overflow-y-auto space-y-8">
        <!-- 1. DASHBOARD OVERVIEW (Matching User's ASCII Diagram) -->
        <div v-if="activeSection === 'dashboard'" class="space-y-8">
          <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
              <LayoutDashboard class="w-6 h-6 text-blue-600" />
              <span>Dashboard Overview</span>
            </h2>
            <p class="text-xs text-slate-500">Live municipal key performance metrics and quick operational actions.</p>
          </div>

          <!-- The 4 Stat Cards from ASCII diagram -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Views This Month</span>
              <div class="text-3xl font-black text-slate-900 mt-2 font-mono">{{ stats.monthly_views }}</div>
              <span class="text-xs text-emerald-600 font-bold mt-1 block">↑ 14.8% Citizen traffic</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Published Articles</span>
              <div class="text-3xl font-black text-blue-600 mt-2 font-mono">{{ stats.news_count }}</div>
              <span class="text-xs text-slate-500 font-medium mt-1 block">Live across 3 languages</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Tenders</span>
              <div class="text-3xl font-black text-amber-600 mt-2 font-mono">{{ stats.active_tenders }}</div>
              <span class="text-xs text-amber-600 font-medium mt-1 block">Live procurement bidding</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Emergency Hotlines Status</span>
              <div class="text-sm font-black text-emerald-700 mt-2 uppercase tracking-wide">
                Active 24/7 Desk
              </div>
              <span class="text-xs text-slate-500 font-medium mt-1 block">Police (991), Fire (939), Ambulance</span>
            </div>
          </div>

          <!-- Quick Action Center from ASCII diagram -->
          <div>
            <h3 class="text-lg font-black text-slate-900 mb-4 flex items-center gap-2">
              <Zap class="w-5 h-5 text-blue-600" />
              <span>Quick Action Center</span>
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Pending Approvals & Operations Card -->
              <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                  <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                    <span>Rapid Management Shortcuts</span>
                  </h4>
                  <span class="text-xs font-bold text-blue-600">Quick Create</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                  <button @click="modalType = 'news'" class="p-3.5 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-bold text-left transition-colors cursor-pointer flex items-center justify-between">
                    <span>+ New Press Release</span>
                    <Plus class="w-4 h-4" />
                  </button>
                  <button @click="modalType = 'tender'" class="p-3.5 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold text-left transition-colors cursor-pointer flex items-center justify-between">
                    <span>+ Post Public Tender</span>
                    <Plus class="w-4 h-4" />
                  </button>
                  <button @click="modalType = 'vacancy'" class="p-3.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold text-left transition-colors cursor-pointer flex items-center justify-between">
                    <span>+ Post Job Opening</span>
                    <Plus class="w-4 h-4" />
                  </button>
                  <button @click="modalType = 'project'" class="p-3.5 rounded-2xl bg-indigo-50 hover:bg-indigo-100 text-indigo-800 text-xs font-bold text-left transition-colors cursor-pointer flex items-center justify-between">
                    <span>+ Track New Project</span>
                    <Plus class="w-4 h-4" />
                  </button>
                </div>
              </div>

              <!-- Recent Inquiries from ASCII diagram -->
              <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                  <h4 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                    <span>Recent Inquiries</span>
                    <span v-if="stats.unread_messages > 0" class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black">
                      {{ stats.unread_messages }} unread
                    </span>
                  </h4>
                  <button @click="activeSection = 'messages'" class="text-xs font-bold text-blue-600 hover:underline">View All Inbox →</button>
                </div>

                <div v-if="messagesList.length === 0" class="text-xs text-slate-400 py-6 text-center">
                  No inquiries received.
                </div>
                <div v-else class="space-y-3">
                  <div 
                    v-for="msg in messagesList.slice(0, 2)" 
                    :key="msg.id"
                    class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs flex items-center justify-between"
                  >
                    <div>
                      <span class="font-bold text-slate-900 block">{{ msg.sender_name }} ({{ msg.target_sub_city || 'Central' }})</span>
                      <span class="text-slate-500 truncate block max-w-xs">{{ msg.subject }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                      :class="msg.status === 'unread' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700'"
                    >
                      {{ msg.status }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Audit Activity Feed from ASCII diagram -->
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h4 class="font-extrabold text-slate-900 text-sm mb-4 flex items-center gap-2">
              <Activity class="w-4 h-4 text-slate-700" />
              <span>Recent Activity & Audit Feed</span>
            </h4>
            <div class="space-y-3 text-xs text-slate-600">
              <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="font-mono text-slate-400">[DB]</span>
                <span class="font-bold text-slate-800">Seeded 22 Municipal Offices & 4 Sub-Cities</span>
                <span class="text-slate-400 font-mono ml-auto">MySQL Database Active</span>
              </div>
              <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="font-mono text-slate-400">[I18N]</span>
                <span class="font-bold text-slate-800">Trilingual Dictionaries Synced (Amharic, English, Afaan Oromoo)</span>
                <span class="text-slate-400 font-mono ml-auto">Pinia Store Verified</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="font-mono text-slate-400">[POLL]</span>
                <span class="font-bold text-slate-800">Civic Opinion Poll Live & Anti-Spam Protected</span>
                <span class="text-slate-400 font-mono ml-auto">SHA-256 Voter Hashing</span>
              </div>
            </div>
          </div>
        </div>

        <!-- HERO SLIDER MANAGEMENT -->
        <div v-else-if="activeSection === 'hero_slides'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Hero Slider Manager</h2>
              <p class="text-xs text-slate-500">Configure public homepage carousel slides, high-res photography, and bilingual call-to-actions.</p>
            </div>
            <button @click="editingId = null; modalType = 'hero_slide'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add New Hero Slide</span>
            </button>
          </div>

          <div v-if="heroSlidesList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No hero slides created yet.
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="slide in heroSlidesList" :key="slide.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between">
              <div>
                <div class="h-48 bg-slate-900 relative overflow-hidden">
                  <img :src="slide.image_path" :alt="slide.title_en || slide.title_am" class="w-full h-full object-cover" />
                  <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/70 text-white text-[11px] font-bold">
                    Order: {{ slide.order }}
                  </span>
                  <span :class="['absolute top-3 right-3 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase', slide.is_active ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-slate-300']">
                    {{ slide.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
                <div class="p-6">
                  <div class="text-[11px] font-bold uppercase text-blue-700 mb-1">{{ slide.tag_en || slide.tag_am || 'General Tag' }}</div>
                  <h4 class="font-extrabold text-base text-slate-900 mb-1 leading-snug">{{ slide.title_en || slide.title_am }}</h4>
                  <p class="text-xs text-slate-500 font-mono mb-2">Amharic: {{ slide.title_am }}</p>
                  <p class="text-xs text-slate-600 line-clamp-2">{{ slide.subtitle_en || slide.subtitle_am }}</p>
                  <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-mono">
                    <span>CTA: {{ slide.button_text_en || slide.button_text_am }}</span>
                    <span>Link: {{ slide.button_url }}</span>
                  </div>
                </div>
              </div>
              <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <button @click="editItem('hero_slide', slide)" class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl cursor-pointer" title="Edit Slide">
                  <Edit class="w-4 h-4" />
                </button>
                <button @click="deleteItem('/admin/hero-slides', slide.id, slide.title_en || slide.title_am)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl cursor-pointer" title="Delete Slide">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- MEDIA GALLERY MANAGEMENT (3.10) -->
        <div v-else-if="activeSection === 'gallery'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Media Gallery Manager (3.10)</h2>
              <p class="text-xs text-slate-500">3.10.1 Adding Images & Videos, category assignment, and multilingual captions.</p>
            </div>
            <button @click="editingId = null; modalType = 'gallery'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Image or Video</span>
            </button>
          </div>

          <div v-if="galleryList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No media items in gallery yet.
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="item in galleryList" :key="item.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between">
              <div>
                <div class="h-44 bg-slate-950 relative overflow-hidden flex items-center justify-center">
                  <img :src="item.thumbnail_url || item.media_path_or_url" :alt="item.caption_en || item.caption_am" class="w-full h-full object-cover" />
                  <span :class="['absolute top-3 left-3 px-2 py-0.5 rounded text-[10px] font-bold uppercase flex items-center gap-1', item.media_type === 'video' ? 'bg-red-600 text-white' : 'bg-slate-900/80 text-white']">
                    <Video v-if="item.media_type === 'video'" class="w-3 h-3" />
                    <ImageIcon v-else class="w-3 h-3" />
                    <span>{{ item.media_type }}</span>
                  </span>
                  <span class="absolute top-3 right-3 px-2 py-0.5 rounded bg-white/90 text-slate-800 text-[10px] font-bold uppercase">
                    {{ item.category }}
                  </span>
                </div>
                <div class="p-4">
                  <h4 class="font-bold text-sm text-slate-900 line-clamp-2 leading-snug mb-1">{{ item.caption_en || item.caption_am }}</h4>
                  <p class="text-xs text-slate-500 font-mono mt-0.5">Amharic: {{ item.caption_am }}</p>
                  <p class="text-[11px] text-slate-400 truncate mt-2 font-mono">Src: {{ item.media_path_or_url }}</p>
                </div>
              </div>
              <div class="p-3 px-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-mono">Order: {{ item.order }}</span>
                <div class="flex items-center gap-1">
                  <button @click="editItem('gallery', item)" class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg cursor-pointer" title="Edit Item">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/gallery', item.id, item.caption_en || item.caption_am)" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg cursor-pointer" title="Delete Item">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. NEWS & PRESS RELEASES -->
        <div v-else-if="activeSection === 'news'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">News Articles & Press Releases (3.5.1)</h2>
              <p class="text-xs text-slate-500">Publish and manage trilingual municipal announcements.</p>
            </div>
            <button @click="modalType = 'news'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shadow-blue-600/20">
              <Plus class="w-4 h-4" />
              <span>Create Press Release</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-100">
              <div v-for="item in newsList" :key="item.id" class="p-6 flex items-center justify-between gap-4 hover:bg-slate-50/50">
                <div>
                  <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold uppercase">{{ item.category }}</span>
                    <span class="text-xs text-slate-400 font-mono">{{ new Date(item.created_at).toLocaleDateString() }}</span>
                  </div>
                  <h4 class="font-bold text-base text-slate-900">{{ item.title_en || item.title_am }}</h4>
                  <p class="text-xs text-slate-500 font-mono mt-0.5">Amharic: {{ item.title_am }}</p>
                </div>
                <div class="flex items-center gap-2">
                  <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-50 text-emerald-700">{{ item.status }}</span>
                  <button @click="editItem('news', item)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Article">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/news', item.id, item.title_en || item.title_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Article">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. PUBLIC TENDERS -->
        <div v-else-if="activeSection === 'tenders'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Procurement & Tenders Management (3.5.4)</h2>
              <p class="text-xs text-slate-500">Create public bids with live countdowns and bid bond requirements.</p>
            </div>
            <button @click="editingId = null; modalType = 'tender'" class="px-5 py-2.5 rounded-2xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Post New Tender</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-100">
              <div v-for="tender in tendersList" :key="tender.id" class="p-6 flex items-center justify-between gap-4">
                <div>
                  <span class="px-2.5 py-0.5 rounded bg-slate-100 text-slate-700 text-xs font-mono font-bold mr-2">{{ tender.reference_number }}</span>
                  <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase">{{ tender.category }}</span>
                  <h4 class="font-bold text-base text-slate-900 mt-2">{{ tender.title_en || tender.title_am }}</h4>
                  <div class="text-xs text-slate-500 mt-1 font-mono">Closes: {{ tender.closing_datetime }} | Bond: {{ tender.bid_bond_amount ? `${tender.bid_bond_amount} ETB` : 'Exempt' }}</div>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="editItem('tender', tender)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Tender">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/tenders', tender.id, tender.reference_number)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Tender">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 4. JOB VACANCIES -->
        <div v-else-if="activeSection === 'vacancies'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Civil Service Vacancies (3.5.3)</h2>
              <p class="text-xs text-slate-500">Post employment openings, salary scales, and application guidelines.</p>
            </div>
            <button @click="editingId = null; modalType = 'vacancy'" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Post Job Vacancy</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-100">
              <div v-for="job in vacanciesList" :key="job.id" class="p-6 flex items-center justify-between gap-4">
                <div>
                  <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase mr-2">{{ job.employment_type }}</span>
                  <span class="text-xs font-mono text-slate-500 font-bold">Code: {{ job.position_code }}</span>
                  <h4 class="font-bold text-base text-slate-900 mt-1">{{ job.title_en || job.title_am }}</h4>
                  <div class="text-xs text-slate-500 mt-1">Scale: {{ job.salary_grade }} | Deadline: {{ job.deadline }}</div>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="editItem('vacancy', job)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Vacancy">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/vacancies', job.id, job.title_en || job.title_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Vacancy">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 5. MAYOR PROFILE & KEYNOTE -->
        <div v-else-if="activeSection === 'mayor'" class="space-y-6 max-w-4xl">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Mayor Profile & Keynote Message (3.4.1)</h2>
            <p class="text-xs text-slate-500">Update the Mayor's welcome letter, vision, and executive biography.</p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
              <img src="/assets/mayor_portrait.jpg" alt="Mayor" class="w-24 h-24 object-cover object-top rounded-2xl border-2 border-blue-600" />
              <div>
                <h3 class="text-lg font-black text-slate-900">{{ mayorData.name_en || mayorData.name_am }}</h3>
                <p class="text-xs text-slate-500">Current Term: {{ mayorData.term_start }} - {{ mayorData.term_end }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mayor Name (Amharic)</label>
                <input v-model="mayorData.name_am" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mayor Name (English)</label>
                <input v-model="mayorData.name_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keynote Welcome Message (Amharic)</label>
              <textarea v-model="mayorData.welcome_message_am" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keynote Welcome Message (English)</label>
              <textarea v-model="mayorData.welcome_message_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <button @click="updateMayorProfile" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs cursor-pointer shadow-md">
              Save Mayor Profile Changes
            </button>
          </div>
        </div>

        <!-- 6. CABINET & COUNCIL LEADERSHIP -->
        <div v-else-if="activeSection === 'leadership'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Cabinet & City Council Members (3.4.2 & 3.4.4)</h2>
              <p class="text-xs text-slate-500">Manage municipal cabinet portfolios and council committee heads.</p>
            </div>
            <button @click="modalType = 'leadership'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Member</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="m in leadershipList" :key="m.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
              <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase" :class="m.type === 'cabinet' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700'">
                  {{ m.type }}
                </span>
                <h4 class="font-extrabold text-base text-slate-900 mt-2">{{ m.name_en || m.name_am }}</h4>
                <p class="text-xs font-semibold text-blue-600 mt-0.5">{{ m.position_en || m.position_am }}</p>
                <div class="text-xs text-slate-500 font-mono mt-3">{{ m.phone }} | {{ m.email }}</div>
              </div>
              <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button @click="editItem('leadership', m)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Member">
                  <Edit class="w-4 h-4" />
                </button>
                <button @click="deleteItem('/admin/leadership', m.id, m.name_en || m.name_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Member">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 7. 22 MUNICIPAL OFFICES -->
        <div v-else-if="activeSection === 'offices'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">22 Municipal Offices & Directory (3.4.4)</h2>
              <p class="text-xs text-slate-500">Maintain official directory, building locations, room numbers and direct phone lines.</p>
            </div>
            <button @click="editingId = null; modalType = 'office'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Office</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-100">
              <div v-for="off in officesList" :key="off.id" class="p-6 flex items-center justify-between gap-4">
                <div>
                  <h4 class="font-bold text-base text-slate-900">{{ off.name_en || off.name_am }}</h4>
                  <div class="text-xs text-slate-500 font-mono mt-1">
                    Amharic: {{ off.name_am }} | Phone: {{ off.phone }} | Room: {{ off.room_number || 'Main' }}
                  </div>
                  <div class="text-xs text-slate-400 mt-0.5">{{ off.building_location_am }}</div>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="editItem('office', off)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Office">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/offices', off.id, off.name_en || off.name_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Office">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 8. SERVICES (TEXT & STEP) -->
        <div v-else-if="activeSection === 'services'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Service Management (3.6)</h2>
              <p class="text-xs text-slate-500">Configure text service guidelines and step-by-step civic procedural walkthroughs.</p>
            </div>
            <button @click="editingId = null; modalType = 'service'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Service</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-6">
              <h4 class="font-bold text-sm text-slate-800 mb-2">Step-by-Step Kebele ID Card Issuance (Active)</h4>
              <p class="text-xs text-slate-500 mb-4">4 Steps: Document preparation, 50 ETB payment, Biometric capture, Card collection.</p>
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">Step Service</span>
                <span class="text-xs font-mono text-slate-500">Estimated: 3 Business Days | Fee: 50.00 ETB</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 9. MEGA INFRASTRUCTURE PROJECTS -->
        <div v-else-if="activeSection === 'projects'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Mega Infrastructure Projects (3.8)</h2>
              <p class="text-xs text-slate-500">Track construction progress %, contractors, and capital allocations.</p>
            </div>
            <button @click="editingId = null; modalType = 'project'" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Project</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="proj in projectsList" :key="proj.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase" :class="proj.progress_percent === 100 ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'">
                    {{ proj.status }}
                  </span>
                  <div class="flex items-center gap-1">
                    <button @click="editItem('project', proj)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg cursor-pointer" title="Edit Project">
                      <Edit class="w-4 h-4" />
                    </button>
                    <button @click="deleteItem('/admin/projects', proj.id, proj.title_en || proj.title_am)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg cursor-pointer" title="Delete Project">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </div>
                <h4 class="font-extrabold text-base text-slate-900 mt-2">{{ proj.title_en || proj.title_am }}</h4>
                <div class="text-xs text-slate-500 mt-1 font-mono">Contractor: {{ proj.contractor_name || 'Municipal Agency' }}</div>

                <!-- Progress Bar -->
                <div class="mt-4">
                  <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                    <span>Construction Progress</span>
                    <span>{{ proj.progress_percent }}%</span>
                  </div>
                  <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full transition-all" :style="{ width: `${proj.progress_percent}%` }"></div>
                  </div>
                </div>
              </div>

              <!-- Quick Progress Adjustment -->
              <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-bold">Quick Set %:</span>
                <div class="flex gap-1.5">
                  <button @click="updateProgress(proj, 25)" class="px-2 py-1 rounded bg-slate-100 hover:bg-blue-50 font-bold">25%</button>
                  <button @click="updateProgress(proj, 50)" class="px-2 py-1 rounded bg-slate-100 hover:bg-blue-50 font-bold">50%</button>
                  <button @click="updateProgress(proj, 75)" class="px-2 py-1 rounded bg-slate-100 hover:bg-blue-50 font-bold">75%</button>
                  <button @click="updateProgress(proj, 100)" class="px-2 py-1 rounded bg-emerald-50 text-emerald-700 font-bold">100%</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 10. RESOURCES & DIRECTIVES -->
        <div v-else-if="activeSection === 'resources'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Resource Management & Directives (3.9)</h2>
              <p class="text-xs text-slate-500">Upload official bylaws, directives, annual budgets, and master plan maps.</p>
            </div>
            <button @click="editingId = null; modalType = 'resource'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Catalog Directive</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="divide-y divide-slate-100">
              <div v-for="res in resourcesList" :key="res.id" class="p-6 flex items-center justify-between gap-4">
                <div>
                  <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase mr-2">{{ res.category }}</span>
                  <span class="text-xs font-mono text-slate-500">{{ res.year }}</span>
                  <h4 class="font-bold text-base text-slate-900 mt-1">{{ res.title_en || res.title_am }}</h4>
                  <div class="text-xs text-slate-500 mt-1 font-mono">File: {{ res.file_size_formatted }} | Downloads: {{ res.downloads_count }}</div>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="editItem('resource', res)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Directive">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button @click="deleteItem('/admin/resources', res.id, res.title_en || res.title_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Directive">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 11. CITIZEN FEEDBACK INBOX -->
        <div v-else-if="activeSection === 'messages'" class="space-y-6">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Citizen Feedback & Grievances Desk (3.11)</h2>
            <p class="text-xs text-slate-500">Live submissions received via the public Contact Us portal.</p>
          </div>

          <div v-if="messagesList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No inquiries received.
          </div>
          <div v-else class="space-y-4">
            <div v-for="msg in messagesList" :key="msg.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
              <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                <div class="flex items-center gap-3">
                  <span class="font-extrabold text-slate-900 text-sm">{{ msg.sender_name }}</span>
                  <span class="text-xs text-slate-500 font-mono">{{ msg.sender_phone }}</span>
                  <span v-if="msg.sender_email" class="text-xs text-slate-500 font-mono">{{ msg.sender_email }}</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                  :class="msg.status === 'unread' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'"
                >
                  {{ msg.status }}
                </span>
              </div>
              <h4 class="font-bold text-slate-900 text-sm mb-1">Subject: {{ msg.subject }}</h4>
              <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl mb-4">{{ msg.message }}</p>
              <div class="flex items-center justify-between pt-2">
                <span class="text-xs text-blue-700 font-semibold">Assigned Desk: {{ msg.target_sub_city || 'Central Municipality' }}</span>
                <div class="flex gap-2">
                  <button v-if="msg.status === 'unread'" @click="updateMessageStatus(msg.id, 'read')" class="px-3 py-1 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold cursor-pointer">Mark Read</button>
                  <button v-if="msg.status !== 'resolved'" @click="updateMessageStatus(msg.id, 'resolved')" class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold cursor-pointer">Mark Resolved</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 12. STAFF ACCOUNTS (Super Admin only) -->
        <div v-else-if="activeSection === 'staff'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Municipal Staff Accounts (4. Roles & Permissions)</h2>
              <p class="text-xs text-slate-500">Super Admin access control for editors and communications officers.</p>
            </div>
            <button @click="modalType = 'staff'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Create Staff Account</span>
            </button>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase">
                <tr>
                  <th class="p-4">Name</th>
                  <th class="p-4">Email</th>
                  <th class="p-4">Role</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="user in staffList" :key="user.id" class="hover:bg-slate-50/50">
                  <td class="p-4 font-bold text-slate-900">{{ user.name }}</td>
                  <td class="p-4 text-slate-600 font-mono">{{ user.email }}</td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700">{{ user.role }}</span>
                  </td>
                  <td class="p-4">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase" :class="user.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'">
                      {{ user.is_active ? 'Active' : 'Disabled' }}
                    </span>
                  </td>
                  <td class="p-4 text-right">
                    <button @click="api.patch(`/admin/staff/${user.id}/toggle`).then(fetchAllAdminData)" class="px-3 py-1 rounded-lg border text-xs font-bold cursor-pointer hover:bg-slate-50">
                      {{ user.is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 13. SETTINGS & MAINTENANCE MODE -->
        <div v-else-if="activeSection === 'settings'" class="space-y-6 max-w-4xl">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Portal Configuration & Maintenance Mode (3.17)</h2>
            <p class="text-xs text-slate-500">Configure public emergency notice banner and site maintenance toggle.</p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
            <!-- Maintenance Mode Toggle -->
            <div class="flex items-center justify-between p-4 rounded-2xl bg-amber-50 border border-amber-200">
              <div>
                <h4 class="font-extrabold text-amber-900 text-sm">Maintenance Mode (3.17)</h4>
                <p class="text-xs text-amber-700">When enabled, the public portal displays an emergency maintenance holding screen while admin CMS remains accessible.</p>
              </div>
              <input type="checkbox" v-model="siteSettings.is_maintenance" class="w-6 h-6 text-amber-600 rounded cursor-pointer" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Emergency Ribbon Notice (English)</label>
              <input v-model="siteSettings.emergency_notice_en" type="text" placeholder="e.g. 24/7 Rapid Emergency Response Active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <button @click="updateSettings" class="px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs cursor-pointer shadow-md">
              Save System Settings
            </button>
          </div>
        </div>
      </main>
    </div>

    <!-- UNIVERSAL DYNAMIC CREATION MODAL -->
    <div v-if="modalType" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-2xl w-full p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
          <h3 class="text-xl font-black text-slate-900 capitalize">{{ editingId ? 'Edit ' + modalType : 'New ' + modalType }}</h3>
          <button @click="modalType = ''; editingId = null" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="submitActiveModal" class="space-y-4">
          <!-- News Fields -->
          <div v-if="modalType === 'news'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category</label>
              <select v-model="newsForm.category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                <option value="General">General</option>
                <option value="Investment">Investment</option>
                <option value="Infrastructure">Infrastructure</option>
                <option value="Governance">Governance</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Title (ርዕስ) *</label>
              <input v-model="newsForm.title_am" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Title</label>
              <input v-model="newsForm.title_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Title</label>
              <input v-model="newsForm.title_om" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Body (ዝርዝር ዜና) *</label>
              <textarea v-model="newsForm.content_am" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Body</label>
              <textarea v-model="newsForm.content_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Article Thumbnail Image</label>
              <div class="flex items-center gap-3">
                <input v-model="newsForm.thumbnail_path" type="text" placeholder="/assets/hero_industry.jpg or upload" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload' }}</span>
                  <input type="file" @change="uploadFileFor($event, newsForm, 'thumbnail_path')" class="hidden" accept="image/*" />
                </label>
              </div>
              <img v-if="newsForm.thumbnail_path" :src="newsForm.thumbnail_path" class="mt-2 h-20 w-32 object-cover rounded-lg border border-slate-200 shadow-2xs" />
            </div>
          </div>

          <!-- Tender Fields -->
          <div v-else-if="modalType === 'tender'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reference Number *</label>
              <input v-model="tenderForm.reference_number" type="text" required placeholder="KMB/NCB/10/2026" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Title (Amharic) *</label>
              <input v-model="tenderForm.title_am" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Title (English)</label>
              <input v-model="tenderForm.title_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bid Bond (ETB)</label>
                <input v-model="tenderForm.bid_bond_amount" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Closing Date & Time *</label>
                <input v-model="tenderForm.closing_datetime" type="datetime-local" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>
          </div>

          <!-- Vacancy Fields -->
          <div v-else-if="modalType === 'vacancy'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Job Title (Amharic) *</label>
              <input v-model="vacancyForm.title_am" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Job Title (English)</label>
              <input v-model="vacancyForm.title_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Salary Grade</label>
                <input v-model="vacancyForm.salary_grade" type="text" placeholder="PS-10" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deadline Date *</label>
                <input v-model="vacancyForm.deadline" type="date" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Requirements (Amharic) *</label>
              <textarea v-model="vacancyForm.requirements_am" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
          </div>

          <!-- Project Fields -->
          <div v-else-if="modalType === 'project'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Project Name (Amharic) *</label>
              <input v-model="projectForm.title_am" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Project Name (English)</label>
              <input v-model="projectForm.title_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Budget (ETB)</label>
                <input v-model="projectForm.budget_etb" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Progress (%)</label>
                <input v-model="projectForm.progress_percent" type="number" min="0" max="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Contractor / Agency</label>
              <input v-model="projectForm.contractor_name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
          </div>

          <!-- Staff Account Fields -->
          <div v-else-if="modalType === 'staff'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Staff Full Name *</label>
              <input v-model="staffForm.name" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Municipal Email *</label>
              <input v-model="staffForm.email" type="email" required placeholder="user@kombolchacity.gov.et" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password *</label>
              <input v-model="staffForm.password" type="password" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned Role</label>
              <select v-model="staffForm.role" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                <option value="editor">Editor</option>
                <option value="author">Author</option>
                <option value="super_admin">Super Administrator</option>
              </select>
            </div>
          </div>

          <!-- Hero Slide Fields -->
          <div v-else-if="modalType === 'hero_slide'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category Tag (English)</label>
                <input v-model="heroSlideForm.tag_en" type="text" placeholder="e.g. National Manufacturing & Logistics Hub" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category Tag (Amharic - አማርኛ)</label>
                <input v-model="heroSlideForm.tag_am" type="text" placeholder="ለምሳሌ፡ የማኑፋክቸሪንግና የወጪ ንግድ ማዕከል" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Main Title (ዋና ርዕስ) *</label>
              <input v-model="heroSlideForm.title_am" type="text" required placeholder="የኮምቦልቻ ኢንዱስትሪ ፓርክ..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Main Title</label>
              <input v-model="heroSlideForm.title_en" type="text" placeholder="Kombolcha Industrial Park & Strategic Hub" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Main Title</label>
              <input v-model="heroSlideForm.title_om" type="text" placeholder="Paarkii Indaastirii Kombolchaa..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Subtitle / Narrative</label>
              <textarea v-model="heroSlideForm.subtitle_am" rows="2" placeholder="ለአገር ውስጥ እና ለዓለም አቀፍ ባለሀብቶች ምቹ..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Subtitle / Narrative</label>
              <textarea v-model="heroSlideForm.subtitle_en" rows="2" placeholder="A premier eco-industrial park and freight corridor..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <!-- Slide Image Upload & Preview -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">High-Res Hero Slide Image *</label>
              <div class="flex items-center gap-3">
                <input v-model="heroSlideForm.image_path" type="text" required placeholder="/assets/hero_industry.jpg or upload" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload Image' }}</span>
                  <input type="file" @change="uploadFileFor($event, heroSlideForm, 'image_path')" class="hidden" accept="image/*" />
                </label>
              </div>
              <div v-if="heroSlideForm.image_path" class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 shadow-sm max-w-sm">
                <img :src="heroSlideForm.image_path" alt="Hero Preview" class="w-full h-36 object-cover" />
                <span class="absolute bottom-2 left-2 bg-black/75 text-white text-[10px] px-2 py-0.5 rounded font-mono">Live Slide Preview</span>
              </div>
            </div>

            <!-- CTA Button & Order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Button Text (Amharic)</label>
                <input v-model="heroSlideForm.button_text_am" type="text" placeholder="ተጨማሪ ይመልከቱ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Button Text (English)</label>
                <input v-model="heroSlideForm.button_text_en" type="text" placeholder="Explore More" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Button Target URL</label>
                <input v-model="heroSlideForm.button_url" type="text" placeholder="/explore or /services" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Slide Order</label>
                <input v-model.number="heroSlideForm.order" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status</label>
                <select v-model.number="heroSlideForm.is_active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                  <option :value="1">Active (Visible Live)</option>
                  <option :value="0">Inactive (Draft / Hidden)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Gallery Media Fields (3.10.1 Adding Images & Videos) -->
          <div v-else-if="modalType === 'gallery'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Media Format</label>
                <div class="flex items-center gap-2">
                  <button 
                    type="button" 
                    @click="galleryForm.media_type = 'image'"
                    :class="['flex-1 py-2 px-3 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer transition-colors', galleryForm.media_type === 'image' ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 text-slate-600 border-slate-200']"
                  >
                    <ImageIcon class="w-4 h-4" />
                    <span>Image (ፎቶ)</span>
                  </button>
                  <button 
                    type="button" 
                    @click="galleryForm.media_type = 'video'"
                    :class="['flex-1 py-2 px-3 rounded-xl border text-xs font-bold flex items-center justify-center gap-1.5 cursor-pointer transition-colors', galleryForm.media_type === 'video' ? 'bg-red-600 text-white border-red-600' : 'bg-slate-50 text-slate-600 border-slate-200']"
                  >
                    <Video class="w-4 h-4" />
                    <span>Video (ቪዲዮ)</span>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gallery Category</label>
                <select v-model="galleryForm.category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                  <option value="urban">City Landmarks & Aerial (የከተማ ገጽታ)</option>
                  <option value="industry">Industry & Logistics (ኢንዱስትሪና ሎጂስቲክስ)</option>
                  <option value="nature">Mount Yegof & Nature (ይጎፍ ተራራና ተፈጥሮ)</option>
                  <option value="events">Civic Events & Culture (ክስተቶችና ባህል)</option>
                </select>
              </div>
            </div>

            <!-- Media Source / File Upload -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                {{ galleryForm.media_type === 'video' ? 'Video File or YouTube URL *' : 'High-Res Photo File or URL *' }}
              </label>
              <div class="flex items-center gap-3">
                <input 
                  v-model="galleryForm.media_path_or_url" 
                  type="text" 
                  required 
                  :placeholder="galleryForm.media_type === 'video' ? 'https://www.youtube.com/watch?v=... or upload mp4' : '/assets/... or upload image'" 
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" 
                />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload File' }}</span>
                  <input 
                    type="file" 
                    @change="uploadFileFor($event, galleryForm, 'media_path_or_url')" 
                    class="hidden" 
                    :accept="galleryForm.media_type === 'video' ? 'video/mp4,video/*' : 'image/*'" 
                  />
                </label>
              </div>
            </div>

            <!-- Video Thumbnail Upload (if video) -->
            <div v-if="galleryForm.media_type === 'video'">
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Video Thumbnail / Poster Image</label>
              <div class="flex items-center gap-3">
                <input v-model="galleryForm.thumbnail_url" type="text" placeholder="/assets/hero_industry.jpg or upload thumbnail" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
                <label class="px-4 py-2.5 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload Poster' }}</span>
                  <input type="file" @change="uploadFileFor($event, galleryForm, 'thumbnail_url')" class="hidden" accept="image/*" />
                </label>
              </div>
            </div>

            <!-- Preview box -->
            <div v-if="galleryForm.media_path_or_url || galleryForm.thumbnail_url" class="rounded-xl overflow-hidden border border-slate-200 shadow-sm max-w-sm">
              <div v-if="galleryForm.media_type === 'image'" class="h-36 bg-slate-900">
                <img :src="galleryForm.media_path_or_url" alt="Gallery Preview" class="w-full h-full object-cover" />
              </div>
              <div v-else class="h-36 bg-slate-900 relative flex items-center justify-center">
                <img v-if="galleryForm.thumbnail_url" :src="galleryForm.thumbnail_url" alt="Video Poster" class="w-full h-full object-cover opacity-70" />
                <Play class="w-10 h-10 text-white drop-shadow-md z-10" />
              </div>
            </div>

            <!-- Captions in 3 languages -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Caption (መግለጫ) *</label>
              <input v-model="galleryForm.caption_am" type="text" required placeholder="ለምሳሌ፡ የኮምቦልቻ ኢንዱስትሪ ፓርክ የመሠረተ ልማት ዝርጋታ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Caption</label>
              <input v-model="galleryForm.caption_en" type="text" placeholder="e.g. Modern Logistics Hub and Freight Terminal" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Caption</label>
              <input v-model="galleryForm.caption_om" type="text" placeholder="Ibsa Afaan Oromootiin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Display Order</label>
              <input v-model.number="galleryForm.order" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="modalType = ''; editingId = null" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 cursor-pointer">Cancel</button>
            <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer disabled:opacity-50">
              {{ submitting ? 'Saving...' : (editingId ? 'Update & Save Changes' : 'Confirm & Publish Live') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
