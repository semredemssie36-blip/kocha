<script setup>
import { ref, computed, onMounted } from 'vue'
import { 
  Users, 
  Upload, 
  Plus, 
  Edit, 
  Trash2, 
  Search, 
  Award, 
  Calendar, 
  Phone, 
  LayoutGrid, 
  List, 
  Camera, 
  CheckCircle, 
  X,
  History,
  ShieldCheck,
  Sparkles
} from 'lucide-vue-next'
import api from '@/services/api'

const props = defineProps({
  mayorData: {
    type: Object,
    required: true
  },
  uploadingFile: {
    type: Boolean,
    default: false
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['updateProfile', 'uploadPhoto', 'refreshMayors'])

// Tab view inside Mayor Section: 'executive' vs 'historical'
const subSection = ref('all') // 'all' | 'executive' | 'history'

// Historical Mayors Local State
const mayorsList = ref([])
const loadingMayors = ref(false)
const mayorSearch = ref('')
const viewMode = ref('cards') // 'cards' | 'table'
const uploadingMayorId = ref(null)

// Modal for Adding / Editing a Historical Mayor
const modalOpen = ref(false)
const modalMode = ref('create') // 'create' | 'edit'
const savingMayor = ref(false)
const mayorForm = ref({
  id: null,
  name_am: '',
  name_en: '',
  name_om: '',
  portrait_path: '',
  phone: '',
  term_start: '',
  term_end: '',
  is_current: 0,
  order: 1,
  biography_am: '',
  biography_en: '',
  biography_om: ''
})

const fetchHistoricalMayors = async () => {
  loadingMayors.value = true
  try {
    const res = await api.get('/admin/mayors')
    mayorsList.value = Array.isArray(res.data) ? res.data : []
  } catch (err) {
    console.error('Failed to load historical mayors:', err)
  } finally {
    loadingMayors.value = false
  }
}

onMounted(() => {
  fetchHistoricalMayors()
})

const filteredMayors = computed(() => {
  if (!mayorSearch.value.trim()) return mayorsList.value
  const q = mayorSearch.value.toLowerCase().trim()
  return mayorsList.value.filter(m => 
    (m.name_am && m.name_am.includes(q)) ||
    (m.name_en && m.name_en.toLowerCase().includes(q)) ||
    (m.term_start && m.term_start.includes(q)) ||
    (m.term_end && m.term_end.includes(q)) ||
    (m.phone && m.phone.includes(q))
  )
})

// Quick direct portrait upload for any specific mayor
const triggerPhotoUpload = (mayorId) => {
  const fileInput = document.getElementById(`mayor-photo-input-${mayorId}`)
  if (fileInput) fileInput.click()
}

const handlePhotoUpload = async (event, mayor) => {
  const file = event.target.files?.[0]
  if (!file) return

  uploadingMayorId.value = mayor.id
  try {
    const formData = new FormData()
    formData.append('file', file)
    const res = await api.post('/admin/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    const uploadedPath = res.data.path || res.data.url

    // Save directly to mayor record
    await api.put(`/admin/mayors/${mayor.id}`, {
      ...mayor,
      portrait_path: uploadedPath
    })

    mayor.portrait_path = uploadedPath
    // If it's the current mayor, sync parent data too
    if (mayor.is_current) {
      props.mayorData.portrait_path = uploadedPath
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to upload photo for mayor.')
  } finally {
    uploadingMayorId.value = null
    try { event.target.value = '' } catch (e) {}
  }
}

const openCreateModal = () => {
  modalMode.value = 'create'
  mayorForm.value = {
    id: null,
    name_am: '',
    name_en: '',
    name_om: '',
    portrait_path: '',
    phone: '',
    term_start: '',
    term_end: '',
    is_current: 0,
    order: (mayorsList.value.length || 0) + 1,
    biography_am: '',
    biography_en: '',
    biography_om: ''
  }
  modalOpen.value = true
}

const openEditModal = (mayor) => {
  modalMode.value = 'edit'
  mayorForm.value = {
    id: mayor.id,
    name_am: mayor.name_am || '',
    name_en: mayor.name_en || '',
    name_om: mayor.name_om || '',
    portrait_path: mayor.portrait_path || '',
    phone: mayor.phone || '',
    term_start: mayor.term_start || '',
    term_end: mayor.term_end || '',
    is_current: mayor.is_current ? 1 : 0,
    order: mayor.order || 1,
    biography_am: mayor.biography_am || '',
    biography_en: mayor.biography_en || '',
    biography_om: mayor.biography_om || ''
  }
  modalOpen.value = true
}

const handleFormPhotoUpload = async (event) => {
  const file = event.target.files?.[0]
  if (!file) return
  try {
    const formData = new FormData()
    formData.append('file', file)
    const res = await api.post('/admin/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    mayorForm.value.portrait_path = res.data.path || res.data.url
  } catch (err) {
    alert('Failed to upload portrait image.')
  } finally {
    try { event.target.value = '' } catch (e) {}
  }
}

const saveMayorRecord = async () => {
  if (!mayorForm.value.name_am) {
    alert('Please enter the Amharic name of the mayor.')
    return
  }

  savingMayor.value = true
  try {
    if (modalMode.value === 'edit' && mayorForm.value.id) {
      await api.put(`/admin/mayors/${mayorForm.value.id}`, mayorForm.value)
    } else {
      await api.post('/admin/mayors', mayorForm.value)
    }
    modalOpen.value = false
    await fetchHistoricalMayors()
    emit('refreshMayors')
  } catch (err) {
    alert(err.response?.data?.message || 'Error saving mayor record.')
  } finally {
    savingMayor.value = false
  }
}

const deleteMayorRecord = async (mayor) => {
  if (mayor.is_current) {
    alert('Cannot delete the incumbent Mayor profile.')
    return
  }
  if (!confirm(`Are you sure you want to remove "${mayor.name_en || mayor.name_am}" from the mayoral registry?`)) {
    return
  }
  try {
    await api.delete(`/admin/mayors/${mayor.id}`)
    await fetchHistoricalMayors()
    emit('refreshMayors')
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to delete mayor record.')
  }
}
</script>

<template>
  <div class="space-y-10 max-w-6xl">
    
    <!-- Header Strip -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
      <div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Office of the Mayor & Mayoral Heritage</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-100 font-mono font-bold text-blue-800">
            {{ mayorsList.length }} Administrations
          </span>
        </h2>
        <p class="text-xs text-slate-500 mt-1">
          Manage the incumbent Executive Mayor profile, keynote greetings, and the complete historical timeline of Kombolcha mayors since 1998 E.C.
        </p>
      </div>

      <!-- Quick Nav Jump -->
      <div class="flex items-center gap-2">
        <a 
          href="#historical-mayors-section" 
          class="px-4 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold flex items-center gap-2 transition"
        >
          <History class="w-4 h-4" />
          <span>Jump to Historical Mayors</span>
        </a>
      </div>
    </div>

    <!-- SECTION 1: INCUMBENT EXECUTIVE MAYOR (CURRENT) -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6 relative overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div class="flex items-center gap-2">
          <ShieldCheck class="w-5 h-5 text-emerald-600" />
          <h3 class="text-lg font-black text-slate-900 uppercase tracking-wide">
            1. Incumbent Executive Mayor (የወቅቱ ከንቲባ)
          </h3>
        </div>
        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-black uppercase tracking-wider">
          Active Municipal Mandate
        </span>
      </div>

      <div class="flex flex-col sm:flex-row sm:items-center gap-6 pb-6 border-b border-slate-100 pt-2">
        <div class="relative group shrink-0">
          <img 
            v-if="mayorData.portrait_path" 
            :src="mayorData.portrait_path" 
            alt="Mayor" 
            class="w-32 h-32 object-cover object-top rounded-2xl border-2 border-amber-500 shadow-md bg-slate-100" 
          />
          <div v-else class="w-32 h-32 rounded-2xl border-2 border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-400">
            <Users class="w-10 h-10 opacity-30 text-blue-600" />
            <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase">No Photo</span>
          </div>

          <label class="absolute inset-0 bg-black/50 text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-xs font-bold gap-1">
            <Camera class="w-5 h-5" />
            <span>{{ uploadingFile ? 'Uploading...' : 'Change Photo' }}</span>
            <input type="file" @change="emit('uploadPhoto', $event)" class="hidden" accept="image/*" />
          </label>
        </div>

        <div class="flex-1 space-y-2">
          <h3 class="text-2xl font-black text-slate-900">{{ mayorData.name_en || mayorData.name_am }}</h3>
          <p class="text-xs text-slate-500 font-mono">Current Mayoral Term: {{ mayorData.term_start || '27/06/2014 ዓ.ም' }} - {{ mayorData.term_end || 'Present' }}</p>

          <!-- Mayor Photo Upload Input -->
          <div class="pt-2">
            <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider mb-1.5">Official Mayoral Portrait</label>
            <div class="flex items-center gap-3 max-w-md">
              <input 
                v-model="mayorData.portrait_path" 
                type="text" 
                placeholder="/uploads/... or click Upload Photo" 
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
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Term Start Date / Year</label>
          <input v-model="mayorData.term_start" type="text" placeholder="27/06/2014 ዓ.ም" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
        </div>
        <div>
          <label class="block text-xs font-black text-slate-800 uppercase tracking-wider mb-1.5">Term End Date / Year</label>
          <input v-model="mayorData.term_end" type="text" placeholder="እስከ አሁን በስራ ላይ / Present" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-xs text-slate-900 font-mono font-medium focus:ring-2 focus:ring-amber-500/40 focus:border-amber-500 outline-none transition" />
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

      <div class="pt-3 flex items-center justify-between">
        <button @click="emit('updateProfile')" class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs cursor-pointer shadow-md shadow-amber-500/20 transition-all">
          Save Incumbent Mayor Profile
        </button>
      </div>
    </div>


    <!-- SECTION 2: HISTORICAL MAYORS REGISTRY & PHOTO MANAGEMENT -->
    <div id="historical-mayors-section" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
      
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <Award class="w-5 h-5 text-blue-700" />
            <h3 class="text-xl font-black text-slate-900">
              2. Historical Mayors Registry (የቀድሞና የወቅቱ ከንቲባዎች ማውጫ)
            </h3>
          </div>
          <p class="text-xs text-slate-500">
            Upload mayoral portraits, update service tenures, and edit biographies for all 9 administrations spanning from 1998 E.C. to present.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <!-- View Toggle -->
          <div class="flex items-center bg-slate-100 p-1 rounded-xl">
            <button 
              type="button"
              @click="viewMode = 'cards'"
              class="p-1.5 rounded-lg transition-all cursor-pointer"
              :class="viewMode === 'cards' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-900'"
              title="Card Grid View"
            >
              <LayoutGrid class="w-4 h-4" />
            </button>
            <button 
              type="button"
              @click="viewMode = 'table'"
              class="p-1.5 rounded-lg transition-all cursor-pointer"
              :class="viewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-500 hover:text-slate-900'"
              title="Table View"
            >
              <List class="w-4 h-4" />
            </button>
          </div>

          <!-- Add Mayor Button -->
          <button 
            @click="openCreateModal" 
            class="px-4 py-2 rounded-xl bg-gradient-to-r from-blue-700 to-blue-800 hover:from-blue-600 hover:to-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-sm transition"
          >
            <Plus class="w-4 h-4" />
            <span>Add Historical Mayor</span>
          </button>
        </div>
      </div>

      <!-- Search Toolbar -->
      <div class="flex items-center justify-between gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200">
        <div class="relative w-full sm:w-80">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            v-model="mayorSearch" 
            type="text" 
            placeholder="Search by name, term year, or phone..." 
            class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-white border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition" 
          />
        </div>
        <div class="text-xs font-bold text-slate-500 shrink-0">
          Total: <span class="text-slate-900 font-mono font-black">{{ filteredMayors.length }}</span> Registered
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingMayors" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div v-for="n in 6" :key="n" class="h-44 bg-slate-100 rounded-2xl animate-pulse"></div>
      </div>

      <!-- 1. CARD GRID VIEW -->
      <div v-else-if="viewMode === 'cards'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div 
          v-for="m in filteredMayors" 
          :key="m.id"
          class="rounded-2xl border p-5 transition-all duration-200 flex flex-col justify-between group hover:shadow-md"
          :class="m.is_current ? 'bg-blue-50/40 border-blue-300' : 'bg-white border-slate-200'"
        >
          <div>
            <!-- Order & Status -->
            <div class="flex items-center justify-between mb-3">
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-slate-100 font-mono text-[11px] font-bold text-slate-800">
                #{{ m.order }} Mayor
              </span>
              <span 
                v-if="m.is_current"
                class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-black text-[10px] uppercase"
              >
                Incumbent
              </span>
              <span 
                v-else
                class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px] uppercase"
              >
                Former
              </span>
            </div>

            <!-- Portrait & Basic Info -->
            <div class="flex items-start gap-4 mb-3">
              <!-- Portrait thumbnail with 1-click change -->
              <div class="relative group/pic shrink-0">
                <img 
                  v-if="m.portrait_path" 
                  :src="m.portrait_path" 
                  :alt="m.name_en || m.name_am" 
                  class="w-16 h-16 object-cover object-top rounded-xl border border-slate-200 shadow-2xs bg-slate-100" 
                />
                <div v-else class="w-16 h-16 rounded-xl border border-dashed border-slate-300 bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                  <Users class="w-6 h-6 opacity-40 text-blue-700" />
                  <span class="text-[8px] font-bold mt-0.5 uppercase">No Photo</span>
                </div>

                <!-- Hover Overlay for Instant Photo Upload -->
                <button 
                  type="button"
                  @click="triggerPhotoUpload(m.id)"
                  class="absolute inset-0 bg-blue-950/70 text-white rounded-xl opacity-0 group-hover/pic:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-[9px] font-bold gap-0.5"
                  title="Upload Portrait Photo"
                >
                  <Camera class="w-4 h-4 text-amber-300" />
                  <span>{{ uploadingMayorId === m.id ? 'Uploading...' : 'Upload' }}</span>
                </button>

                <!-- Hidden file input for this specific mayor -->
                <input 
                  :id="`mayor-photo-input-${m.id}`"
                  type="file" 
                  accept="image/*" 
                  class="hidden" 
                  @change="handlePhotoUpload($event, m)" 
                />
              </div>

              <!-- Name & Term -->
              <div class="min-w-0 flex-1">
                <h4 class="font-black text-sm text-slate-900 group-hover:text-blue-900 truncate">
                  {{ m.name_am }}
                </h4>
                <p class="text-xs text-slate-600 truncate font-medium">
                  {{ m.name_en || 'Mayor of Kombolcha' }}
                </p>
                <div class="flex items-center gap-1.5 text-[11px] font-mono font-bold text-slate-500 mt-1">
                  <Calendar class="w-3.5 h-3.5 text-blue-700 shrink-0" />
                  <span>{{ m.term_start }} - {{ m.term_end || 'Present' }}</span>
                </div>
              </div>
            </div>

            <!-- Phone badge if exists -->
            <div v-if="m.phone" class="flex items-center gap-1.5 text-[11px] font-mono text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg w-fit mb-3">
              <Phone class="w-3 h-3" />
              <span>{{ m.phone }}</span>
            </div>

            <!-- Bio snippet -->
            <p v-if="m.biography_am || m.biography_en" class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">
              {{ m.biography_am || m.biography_en }}
            </p>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
            <!-- Upload Photo Button -->
            <button 
              type="button"
              @click="triggerPhotoUpload(m.id)"
              class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold flex items-center gap-1.5 cursor-pointer transition"
            >
              <Upload class="w-3 h-3 text-blue-700" />
              <span>{{ uploadingMayorId === m.id ? 'Saving...' : 'Upload Photo' }}</span>
            </button>

            <div class="flex items-center gap-1">
              <button 
                type="button"
                @click="openEditModal(m)"
                class="p-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold cursor-pointer transition"
                title="Edit Details"
              >
                <Edit class="w-3.5 h-3.5" />
              </button>

              <button 
                v-if="isSuperAdmin && !m.is_current"
                type="button"
                @click="deleteMayorRecord(m)"
                class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold cursor-pointer transition"
                title="Delete Mayor"
              >
                <Trash2 class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. TABLE VIEW -->
      <div v-else class="overflow-x-auto rounded-2xl border border-slate-200">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase tracking-wider font-mono text-[10px]">
            <tr>
              <th class="p-3">Order</th>
              <th class="p-3">Portrait</th>
              <th class="p-3">Name (Amharic / English)</th>
              <th class="p-3">Tenure / Service Term</th>
              <th class="p-3">Phone</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr v-for="m in filteredMayors" :key="m.id" class="hover:bg-slate-50/80 transition">
              <td class="p-3 font-mono font-bold text-slate-700">#{{ m.order }}</td>
              <td class="p-3">
                <div class="flex items-center gap-2">
                  <img v-if="m.portrait_path" :src="m.portrait_path" class="w-9 h-9 object-cover rounded-lg border border-slate-200" />
                  <div v-else class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                    <Users class="w-4 h-4" />
                  </div>
                  <button 
                    type="button" 
                    @click="triggerPhotoUpload(m.id)" 
                    class="text-[10px] font-bold text-blue-700 hover:underline cursor-pointer"
                  >
                    Upload
                  </button>
                  <input 
                    :id="`mayor-photo-input-${m.id}`"
                    type="file" 
                    accept="image/*" 
                    class="hidden" 
                    @change="handlePhotoUpload($event, m)" 
                  />
                </div>
              </td>
              <td class="p-3">
                <div class="font-bold text-slate-900">{{ m.name_am }}</div>
                <div class="text-[11px] text-slate-500 font-medium">{{ m.name_en }}</div>
              </td>
              <td class="p-3 font-mono font-medium text-slate-600">
                {{ m.term_start }} - {{ m.term_end || 'Present' }}
              </td>
              <td class="p-3 font-mono text-slate-600">
                {{ m.phone || '—' }}
              </td>
              <td class="p-3 text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <button 
                    type="button"
                    @click="openEditModal(m)"
                    class="p-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition"
                    title="Edit"
                  >
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button 
                    v-if="isSuperAdmin && !m.is_current"
                    type="button"
                    @click="deleteMayorRecord(m)"
                    class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                    title="Delete"
                  >
                    <Trash2 class="w-3.5 h-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>


    <!-- MODAL: ADD / EDIT HISTORICAL MAYOR -->
    <div 
      v-if="modalOpen" 
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto"
      @click.self="modalOpen = false"
    >
      <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl overflow-hidden my-8 animate-fadeIn">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
          <div class="flex items-center gap-2">
            <Award class="w-5 h-5 text-amber-400" />
            <h3 class="text-base font-black">
              {{ modalMode === 'edit' ? 'Edit Mayor Record' : 'Register Historical Mayor' }}
            </h3>
          </div>
          <button 
            type="button" 
            @click="modalOpen = false" 
            class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Modal Form Body -->
        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
          
          <!-- Portrait Upload Preview -->
          <div class="flex items-center gap-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <img 
              v-if="mayorForm.portrait_path" 
              :src="mayorForm.portrait_path" 
              class="w-20 h-20 object-cover rounded-xl border border-slate-300 shadow-xs" 
            />
            <div v-else class="w-20 h-20 rounded-xl border border-dashed border-slate-300 bg-white flex flex-col items-center justify-center text-slate-400">
              <Users class="w-8 h-8 opacity-40 text-blue-600" />
              <span class="text-[8px] font-bold uppercase mt-1">No Image</span>
            </div>

            <div class="flex-1 space-y-1.5">
              <label class="block text-xs font-bold text-slate-700 uppercase">Mayoral Portrait Photo</label>
              <div class="flex items-center gap-2">
                <input 
                  v-model="mayorForm.portrait_path" 
                  type="text" 
                  placeholder="/uploads/... or upload from device" 
                  class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-mono" 
                />
                <label class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 cursor-pointer transition">
                  <Upload class="w-3.5 h-3.5" />
                  <span>Upload</span>
                  <input type="file" accept="image/*" class="hidden" @change="handleFormPhotoUpload" />
                </label>
              </div>
            </div>
          </div>

          <!-- Names in 3 languages -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Name * (የከንቲባው ስም)</label>
              <input v-model="mayorForm.name_am" type="text" required placeholder="ለምሳሌ፡ አቶ ያሲን ሁሴን" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:border-blue-600 outline-none" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Name</label>
              <input v-model="mayorForm.name_en" type="text" placeholder="e.g. Ato Yasin Husen" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:border-blue-600 outline-none" />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Name</label>
            <input v-model="mayorForm.name_om" type="text" placeholder="Maqaa Afaan Oromootiin" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-medium focus:border-blue-600 outline-none" />
          </div>

          <!-- Tenure Dates & Order -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Term Start Date / Year</label>
              <input v-model="mayorForm.term_start" type="text" placeholder="01/01/1998 ዓ.ም" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-blue-600 outline-none" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Term End Date / Year</label>
              <input v-model="mayorForm.term_end" type="text" placeholder="08/01/1999 ዓ.ም" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-blue-600 outline-none" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Order Sequence (#)</label>
              <input v-model.number="mayorForm.order" type="number" min="1" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-blue-600 outline-none" />
            </div>
          </div>

          <!-- Phone extension & Incumbent toggle -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number (Extension / Direct)</label>
              <input v-model="mayorForm.phone" type="text" placeholder="0335514062 or 09..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:border-blue-600 outline-none" />
            </div>
            <div class="pt-5">
              <label class="inline-flex items-center gap-2 cursor-pointer">
                <input v-model="mayorForm.is_current" type="checkbox" :true-value="1" :false-value="0" class="w-4 h-4 rounded text-blue-600" />
                <span class="text-xs font-bold text-slate-800">Is Current / Incumbent Mayor</span>
              </label>
            </div>
          </div>

          <!-- Biographies -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biography / Career Summary (Amharic)</label>
            <textarea v-model="mayorForm.biography_am" rows="2" placeholder="የከንቲባው የስራ ዘመንና አስተዋጽኦ..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:border-blue-600 outline-none"></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Biography / Career Summary (English)</label>
            <textarea v-model="mayorForm.biography_en" rows="2" placeholder="Mayoral tenure summary and achievements..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:border-blue-600 outline-none"></textarea>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
          <button 
            type="button" 
            @click="modalOpen = false" 
            class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-100 transition"
          >
            Cancel
          </button>
          <button 
            type="button" 
            @click="saveMayorRecord" 
            :disabled="savingMayor"
            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs cursor-pointer shadow-sm transition flex items-center gap-2"
          >
            <CheckCircle class="w-4 h-4" />
            <span>{{ savingMayor ? 'Saving...' : 'Save Mayor Record' }}</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>
