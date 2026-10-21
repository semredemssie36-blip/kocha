<script setup>
import { 
  Edit, 
  Plus, 
  X, 
  Upload, 
  ImageIcon, 
  Video, 
  Play, 
  Trash2, 
  Loader2, 
  CheckCircle2 
} from 'lucide-vue-next'

const props = defineProps({
  modalType: {
    type: String,
    default: ''
  },
  editingId: {
    type: [Number, String],
    default: null
  },
  isEditor: {
    type: Boolean,
    default: false
  },
  isAuthor: {
    type: Boolean,
    default: false
  },
  isSuperAdmin: {
    type: Boolean,
    default: false
  },
  submitting: {
    type: Boolean,
    default: false
  },
  uploadingFile: {
    type: Boolean,
    default: false
  },
  newsForm: { type: Object, default: () => ({}) },
  tenderForm: { type: Object, default: () => ({}) },
  vacancyForm: { type: Object, default: () => ({}) },
  projectForm: { type: Object, default: () => ({}) },
  staffForm: { type: Object, default: () => ({}) },
  serviceForm: { type: Object, default: () => ({}) },
  officeForm: { type: Object, default: () => ({}) },
  leadershipForm: { type: Object, default: () => ({}) },
  resourceForm: { type: Object, default: () => ({}) },
  heroSlideForm: { type: Object, default: () => ({}) },
  galleryForm: { type: Object, default: () => ({}) },
  destinationForm: { type: Object, default: () => ({}) },
  emergencyContactForm: { type: Object, default: () => ({}) },
  pollForm: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['close', 'submit', 'uploadFile'])

function uploadFileFor(event, form, field) {
  emit('uploadFile', event, form, field)
}

function addPollOption() {
  if (props.pollForm && props.pollForm.options) {
    props.pollForm.options.push({ option_text_en: '', option_text_am: '', option_text_om: '' })
  }
}

function removePollOption(index) {
  if (props.pollForm && props.pollForm.options) {
    if (props.pollForm.options.length <= 2) {
      alert('A civic poll must have at least 2 options.')
      return
    }
    props.pollForm.options.splice(index, 1)
  }
}
</script>

<template>
    <div v-if="modalType" class="fixed inset-0 bg-[#071324]/80 backdrop-blur-md z-50 flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
      <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-100 overflow-hidden flex flex-col my-auto max-h-[92vh]">
        <!-- Sovereign Modal Header -->
        <div class="bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white p-5 sm:p-6 relative shrink-0">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-amber-400/15 border border-amber-400/30 flex items-center justify-center text-amber-300">
                <Edit v-if="editingId" class="w-5 h-5" />
                <Plus v-else class="w-5 h-5" />
              </div>
              <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-amber-400/90 block">
                  Kombolcha Metropolitan Administrative Dossier
                </span>
                <h3 class="text-lg sm:text-xl font-black text-white capitalize">
                  {{ editingId ? 'Edit ' + modalType.replace('_', ' ') : 'New ' + modalType.replace('_', ' ') }}
                </h3>
              </div>
            </div>
            <button 
              @click="emit('close')" 
              class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors cursor-pointer"
            >
              <X class="w-5 h-5" />
            </button>
          </div>
        </div>

        <form @submit.prevent="emit('submit')" class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-5">
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

            <!-- Publication / Editorial Review Status -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Editorial Lifecycle Status *</label>
              <select v-model="newsForm.status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold bg-white">
                <template v-if="isEditor">
                  <option value="published">Publish Live (ይፋ አድርግ / Live on Website)</option>
                  <option value="pending">Pending Editorial Review (ለግምገማ የቀረበ)</option>
                  <option value="draft">Draft (ረቂቅ / Private Draft)</option>
                </template>
                <template v-else>
                  <option value="pending">Submit for Editorial Approval (ለግምገማ ይላክ)</option>
                  <option value="draft">Save as Draft (ረቂቅ አድርገህ አስቀምጥ)</option>
                </template>
              </select>
              <p v-if="isAuthor" class="text-[11px] text-amber-700 mt-1 font-medium">
                * Note: As an Author, articles are submitted to the Communications Editor for verification and cannot be published directly to the public portal.
              </p>
            </div>
          </div>

          <!-- Tender Fields -->
          <div v-else-if="modalType === 'tender'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reference Number *</label>
                <input v-model="tenderForm.reference_number" type="text" required placeholder="KMB/NCB/10/2026" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Procurement Category *</label>
                <select v-model="tenderForm.category" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                  <option value="Goods">Goods (ዕቃዎች)</option>
                  <option value="Works">Works (ግንባታና እድሳት)</option>
                  <option value="Services">Services (አገልግሎቶች)</option>
                  <option value="Consultancy">Consultancy (የማማከር)</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bidding Status *</label>
                <select v-model="tenderForm.status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs">
                  <template v-if="isEditor">
                    <option value="open">Open Bid (ክፍት / Live Notice)</option>
                    <option value="pending">Under Review (ለግምገማ የቀረበ)</option>
                    <option value="draft">Draft (ረቂቅ)</option>
                    <option value="closed">Closed (የተዘጋ)</option>
                    <option value="cancelled">Cancelled (የተሰረዘ)</option>
                  </template>
                  <template v-else>
                    <option value="pending">Submit for Editorial Review (ለአርታኢ ግምገማ አቅርብ)</option>
                    <option value="draft">Save as Draft (ረቂቅ አስቀምጥ)</option>
                  </template>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tender Title (Amharic - አማርኛ) *</label>
              <input v-model="tenderForm.title_am" type="text" required placeholder="የጨረታ ርዕስ በአማርኛ..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tender Title (English)</label>
                <input v-model="tenderForm.title_en" type="text" placeholder="Tender title in English..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tender Title (Afaan Oromoo)</label>
                <input v-model="tenderForm.title_om" type="text" placeholder="Mata duree caalbaasii..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bid Security Bond (ETB - የጨረታ ማስከበሪያ)</label>
                <input v-model="tenderForm.bid_bond_amount" type="number" placeholder="50000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Bid Closing Date & Time * (Powers Countdown)</label>
                <input v-model="tenderForm.closing_datetime" type="datetime-local" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <!-- Bid Dossier PDF Document Upload -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Bid Dossier / RFP Document (PDF)</label>
              <div class="flex items-center gap-3">
                <input 
                  v-model="tenderForm.document_pdf_path" 
                  type="text" 
                  placeholder="/uploads/... or click Upload PDF" 
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" 
                />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload PDF' }}</span>
                  <input type="file" @change="uploadFileFor($event, tenderForm, 'document_pdf_path')" class="hidden" accept=".pdf,.doc,.docx" />
                </label>
              </div>
              <p class="text-[11px] text-slate-400 mt-1">This enables the "Download Bid Dossier (PDF)" button on the public portal.</p>
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

          <!-- Service Fields -->
          <div v-else-if="modalType === 'service'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Type</label>
                <select v-model="serviceForm.type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                  <option value="step">Step-by-Step Procedure Walkthrough</option>
                  <option value="text">Direct Civic Guide / Text Service</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Administrative Fee (ETB)</label>
                <input v-model.number="serviceForm.fee_amount" type="number" min="0" placeholder="0 for Free" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Title (Amharic - አማርኛ) *</label>
              <input v-model="serviceForm.title_am" type="text" required placeholder="ለምሳሌ፡ የነዋሪነት የቀበሌ መታወቂያ ካርድ አሰጣጥ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Title (English)</label>
              <input v-model="serviceForm.title_en" type="text" placeholder="e.g. Resident Kebele Identification Card Issuance" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Title (Afaan Oromoo)</label>
              <input v-model="serviceForm.title_om" type="text" placeholder="Waraqaa Eenyummaa Lammiilee Gandaa Kennuu" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Estimated Processing Time</label>
              <input v-model="serviceForm.estimated_time_am" type="text" placeholder="ለምሳሌ፡ 3 የስራ ቀናት (3 Business Days)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Summary / Citizen Instructions (Amharic) *</label>
              <textarea v-model="serviceForm.summary_am" rows="3" required placeholder="የአገልግሎት አሰጣጥ ሂደት፣ አስፈላጊ ቅድመ-ሁኔታዎችና መመሪያዎች..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Summary / Citizen Instructions (English)</label>
              <textarea v-model="serviceForm.summary_en" rows="3" placeholder="Sequential guidelines and required preconditions..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>
          </div>

          <!-- Municipal Office Fields -->
          <div v-else-if="modalType === 'office'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Office Name (Amharic - አማርኛ) *</label>
              <input v-model="officeForm.name_am" type="text" required placeholder="ለምሳሌ፡ የፋይናንስ መምሪያ ወይም የመንገዶች ባለስልጣን" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Office Name (English)</label>
              <input v-model="officeForm.name_en" type="text" placeholder="e.g. Finance Directorate / Roads Authority" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Office Name (Afaan Oromoo)</label>
              <input v-model="officeForm.name_om" type="text" placeholder="e.g. Waajjira Faayinaansii" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Municipal Direct Phone *</label>
                <input v-model="officeForm.phone" type="text" placeholder="+251 33 551 0045" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Municipal Email</label>
                <input v-model="officeForm.email" type="email" placeholder="office@kombolchacity.gov.et" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Building Location</label>
                <input v-model="officeForm.building_location_am" type="text" placeholder="ለምሳሌ፡ ዋና ማዘጋጃ ቤት፣ ህንፃ 2 ወለል 1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Room / Office Number</label>
                <input v-model="officeForm.room_number" type="text" placeholder="ለምሳሌ፡ 201 ወይም 104" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>
          </div>

          <!-- Leadership Member Fields -->
          <div v-else-if="modalType === 'leadership'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Portfolio Body / Category</label>
              <select v-model="leadershipForm.type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                <option value="cabinet">Cabinet Executive (የከንቲባ ካቢኔ አባል)</option>
                <option value="council">City Council Standing Committee (የከተማ ምክር ቤት ቋሚ ኮሚቴ)</option>
              </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name (Amharic - አማርኛ) *</label>
                <input v-model="leadershipForm.name_am" type="text" required placeholder="ለምሳሌ፡ ዶ/ር ተስፋዬ ብርሃኑ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name (English)</label>
                <input v-model="leadershipForm.name_en" type="text" placeholder="e.g. Dr. Tesfaye Berhanu" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name (Afaan Oromoo)</label>
              <input v-model="leadershipForm.name_om" type="text" placeholder="e.g. Dr. Tasfaayee Birhaanuu" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Position / Municipal Role (Amharic) *</label>
              <input v-model="leadershipForm.position_am" type="text" required placeholder="ለምሳሌ፡ ምክትል ከንቲባ እና የንግድና ኢንዱስትሪ መምሪያ ኃላፊ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Position / Municipal Role (English)</label>
              <input v-model="leadershipForm.position_en" type="text" placeholder="e.g. Deputy Mayor & Head of Trade & Industry" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Position / Municipal Role (Afaan Oromoo)</label>
              <input v-model="leadershipForm.position_om" type="text" placeholder="Itti Aanaa Kantiibaa fi Hogganaa Daldalaa fi Indaastirii" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <!-- Member Photo Upload -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Member Portrait Photograph</label>
              <div class="flex items-center gap-3">
                <input 
                  v-model="leadershipForm.photo_path" 
                  type="text" 
                  placeholder="/uploads/... or click Upload Photo" 
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" 
                />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                  <input type="file" @change="uploadFileFor($event, leadershipForm, 'photo_path')" class="hidden" accept="image/*" />
                </label>
              </div>
              <img v-if="leadershipForm.photo_path" :src="leadershipForm.photo_path" class="mt-2 h-16 w-16 object-cover rounded-xl border border-slate-200 shadow-2xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Direct Phone</label>
                <input v-model="leadershipForm.phone" type="text" placeholder="+251 33 551 0023" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Municipal Email</label>
                <input v-model="leadershipForm.email" type="email" placeholder="leadership@kombolchacity.gov.et" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>
          </div>

          <!-- Resource / Directives Fields -->
          <div v-else-if="modalType === 'resource'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Document Category</label>
                <select v-model="resourceForm.category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                  <option value="Directives">Directives (ይፋዊ መመሪያዎች)</option>
                  <option value="Master Plan">Master Plan (የከተማ ማስተር ፕላን)</option>
                  <option value="Bylaws">Bylaws & Regulations (ደንቦች)</option>
                  <option value="Forms">Citizen Forms (የዜጎች ቅጾች)</option>
                  <option value="Annual Reports">Annual Reports & Budgets (ዓመታዊ ሪፖርቶች)</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Publication Year</label>
                <input v-model.number="resourceForm.year" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Document Title (Amharic - አማርኛ) *</label>
              <input v-model="resourceForm.title_am" type="text" required placeholder="ለምሳሌ፡ የከተማ መሬት ይዞታ ምዝገባ እና የካርታ አሰጣጥ መመሪያ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Document Title (English)</label>
              <input v-model="resourceForm.title_en" type="text" placeholder="e.g. Urban Land Tenure Registration and Cadastre Directive" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Document Title (Afaan Oromoo)</label>
              <input v-model="resourceForm.title_om" type="text" placeholder="Qajeelfama Galmee Qabiyyee Lafa Magaalaa" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <!-- PDF Document File Upload -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Document File (PDF / Doc) *</label>
              <div class="flex items-center gap-3">
                <input 
                  v-model="resourceForm.file_path" 
                  type="text" 
                  required 
                  placeholder="/documents/directive.pdf or upload" 
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" 
                />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload PDF' }}</span>
                  <input type="file" @change="uploadFileFor($event, resourceForm, 'file_path')" class="hidden" accept=".pdf,.doc,.docx" />
                </label>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">File Size Label</label>
              <input v-model="resourceForm.file_size_formatted" type="text" placeholder="e.g. 2.4 MB" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
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

          <!-- Gallery Media Fields -->
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

          <!-- Destination Spotlight Fields -->
          <div v-else-if="modalType === 'destination'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Destination Category *</label>
                <select v-model="destinationForm.category" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold">
                  <option value="nature">Nature & Tourism (የተፈጥሮና ቱሪዝም መስህቦች)</option>
                  <option value="industry">Industry & Investment (ኢንዱስትሪና ኢንቨስትመንት)</option>
                  <option value="culture">Culture & Commerce (ባህልና ንግድ)</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Display Order</label>
                <input v-model.number="destinationForm.order" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <!-- Destination Photo / Image Upload -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Attraction Landscape Photo or URL</label>
              <div class="flex items-center gap-3">
                <input 
                  v-model="destinationForm.image_path" 
                  type="text" 
                  placeholder="/assets/destinations/... or upload photo" 
                  class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" 
                />
                <label class="px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                  <Upload class="w-4 h-4" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                  <input 
                    type="file" 
                    @change="uploadFileFor($event, destinationForm, 'image_path')" 
                    class="hidden" 
                    accept="image/*" 
                  />
                </label>
              </div>
            </div>

            <!-- Photo Preview -->
            <div v-if="destinationForm.image_path" class="rounded-xl overflow-hidden border border-slate-200 shadow-xs max-w-sm h-36 bg-slate-100">
              <img :src="destinationForm.image_path" alt="Destination Preview" class="w-full h-full object-cover" />
            </div>

            <!-- Titles in 3 Languages -->
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Title (የስፍራው ስም በአማርኛ) *</label>
              <input v-model="destinationForm.title_am" type="text" required placeholder="ለምሳሌ፡ የይጎፍ ተራራ የተፈጥሮ ደንና የመስህብ ስፍራ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Title</label>
                <input v-model="destinationForm.title_en" type="text" placeholder="e.g. Mount Yegof Nature Reserve & Forest" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Title</label>
                <input v-model="destinationForm.title_om" type="text" placeholder="Maqaa Afaan Oromootiin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <!-- Locations in 3 Languages -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Location (አማርኛ)</label>
                <input v-model="destinationForm.location_am" type="text" placeholder="ለምሳሌ፡ ደቡብ ኮሪደር፣ ኮምቦልቻ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Location (English)</label>
                <input v-model="destinationForm.location_en" type="text" placeholder="e.g. Bordering Kombolcha South Corridor" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Location (Afaan Oromoo)</label>
                <input v-model="destinationForm.location_om" type="text" placeholder="Bakka" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <!-- Brief Card Summaries in 3 Languages -->
            <div class="pt-2 border-t border-slate-100">
              <span class="text-[11px] font-black uppercase text-blue-700 tracking-wider">1. Brief Card Summaries (ለካርድ ማሳያ አጭር መግለጫ)</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Short Summary (አጭር ማጠቃለያ) *</label>
              <textarea v-model="destinationForm.summary_am" rows="2" placeholder="በካርዱ ላይ የሚታይ አጭር ማጠቃለያ..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Short Summary</label>
                <textarea v-model="destinationForm.summary_en" rows="2" placeholder="Brief card summary for visitors and investors..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Short Summary</label>
                <textarea v-model="destinationForm.summary_om" rows="2" placeholder="Ibsa gabaabaa kaardiirratti mul'atu..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
            </div>

            <!-- Full Detailed Inquire Description in 3 Languages -->
            <div class="pt-2 border-t border-slate-100">
              <span class="text-[11px] font-black uppercase text-blue-700 tracking-wider">2. Full Detailed Inquire Information (ሙሉ ዝርዝርና መመሪያዎች)</span>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Full Detail (ዝርዝር መረጃ፣ ታሪክና መመሪያዎች)</label>
              <textarea v-model="destinationForm.description_am" rows="3" placeholder="ተጠቃሚዎች 'Inquire Details' ሲጫኑ የሚታይ ዝርዝር መረጃ..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">English Full Detail</label>
                <textarea v-model="destinationForm.description_en" rows="3" placeholder="Comprehensive detail shown when visitors click 'Inquire Details'..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Full Detail</label>
                <textarea v-model="destinationForm.description_om" rows="3" placeholder="Ibsa guutuu yeroo 'Inquire Details' cuqaafamu mul'atu..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
            </div>

            <!-- Visitor & Inquirer Practical Information -->
            <div class="pt-2 border-t border-slate-100">
              <span class="text-[11px] font-black uppercase text-blue-700 tracking-wider">3. Practical Details & Visitor Inquiries (የጉብኝት ሰዓት፣ ክፍያና አድራሻ)</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Visiting / Operating Hours (የስራ/ጉብኝት ሰዓት)</label>
                <input v-model="destinationForm.visiting_hours" type="text" placeholder="e.g. Daily: 6:00 AM - 6:30 PM" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Admission / Ticket Fee (የመግቢያ ክፍያ)</label>
                <input v-model="destinationForm.admission_fee" type="text" placeholder="e.g. Free Entry or 50 ETB" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Inquiry Contact Phone (ስልክ)</label>
                <input v-model="destinationForm.contact_phone" type="text" placeholder="e.g. +251 33 551 0145" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Inquiry Contact Email (ኢሜይል)</label>
                <input v-model="destinationForm.contact_email" type="email" placeholder="e.g. tourism@kombolchacity.gov.et" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Key Features & Amenities (ልዩ መገለጫዎች - በነጠላ ሰረዝ ይለዩ)</label>
              <input v-model="destinationForm.features" type="text" placeholder="e.g. Hiking Trails, Bird Watching, Parking, Scenic Viewpoint, Camping" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>
          </div>

          <!-- Emergency Contact / Hotline Fields -->
          <div v-else-if="modalType === 'emergency_contact'" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Service Name (English) *</label>
                <input v-model="emergencyContactForm.name_en" type="text" required placeholder="e.g. City Police Station" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amharic Name (የአገልግሎቱ ስም በአማርኛ) *</label>
                <input v-model="emergencyContactForm.name_am" type="text" required placeholder="ለምሳሌ፡ የከተማ ፖሊስ መምሪያ" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Afaan Oromoo Name</label>
                <input v-model="emergencyContactForm.name_om" type="text" placeholder="Fkn: Waajjira Poolisii Magaalaa" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Hotline / Phone Number *</label>
                <input v-model="emergencyContactForm.phone_number" type="text" required placeholder="e.g. 991 or +251 33 551 0145" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold text-blue-700" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Icon Style</label>
                <select v-model="emergencyContactForm.icon_name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold">
                  <option value="ShieldAlert">ShieldAlert (Police / Security)</option>
                  <option value="Flame">Flame (Fire & Rescue)</option>
                  <option value="Activity">Activity (Ambulance / Medical)</option>
                  <option value="Droplets">Droplets (Water & Sanitation)</option>
                  <option value="Zap">Zap (Power Grid & Electric)</option>
                  <option value="PhoneCall">PhoneCall (Dispatch Hotline)</option>
                  <option value="Phone">Phone (General Telephone)</option>
                  <option value="Building2">Building2 (Municipal HQ)</option>
                  <option value="ShieldCheck">ShieldCheck (Public Safety)</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Badge Color</label>
                <select v-model="emergencyContactForm.badge_color" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold">
                  <option value="red">Red (Urgent / Police)</option>
                  <option value="orange">Orange (Fire / Warning)</option>
                  <option value="rose">Rose (Medical / Ambulance)</option>
                  <option value="blue">Blue (Water / Utility)</option>
                  <option value="amber">Amber (Electric / Power)</option>
                  <option value="emerald">Emerald (Health / Rescue)</option>
                  <option value="indigo">Indigo (Corporate / Civil)</option>
                  <option value="purple">Purple (Support / Info)</option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Display Order</label>
                <input v-model.number="emergencyContactForm.order" type="number" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status on Public Navbar</label>
              <select v-model.number="emergencyContactForm.is_active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold">
                <option :value="1">Active (Displayed on Top Bar)</option>
                <option :value="0">Disabled (Hidden from Top Bar)</option>
              </select>
            </div>
          </div>

          <!-- Civic Opinion Poll Form -->
          <div v-else-if="modalType === 'poll'" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Poll Question (English) *</label>
              <input v-model="pollForm.question_en" type="text" required placeholder="e.g. Which municipal infrastructure sector should receive highest investment priority?" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Poll Question (Amharic - አማርኛ) *</label>
              <input v-model="pollForm.question_am" type="text" required placeholder="ለምሳሌ፡ ለመጪው የበጀት ዓመት የትኛው የማዘጋጃ ቤት መሠረተ ልማት ቅድሚያ ሊሰጠው ይገባል?" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Poll Question (Afaan Oromoo)</label>
              <input v-model="pollForm.question_om" type="text" placeholder="Gaaffii sagalee ummataa Afaan Oromootiin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description / Subtitle (English)</label>
                <textarea v-model="pollForm.description_en" rows="2" placeholder="Cast your vote anonymously. Total verified votes shape municipal capital planning." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description / Subtitle (Amharic)</label>
                <textarea v-model="pollForm.description_am" rows="2" placeholder="ድምጽዎን ያለማንም ተጽዕኖ በነፃነት ይስጡ።" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expiration Date (When the day passes, poll closes)</label>
                <input v-model="pollForm.expires_at" type="date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
                <span class="text-[10px] text-slate-400 mt-1 block">When this date passes, balloting is automatically concluded and final real results are displayed.</span>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Poll Status</label>
                <select v-model.number="pollForm.is_active" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold">
                  <option :value="1">Active (Published as current live poll)</option>
                  <option :value="0">Inactive / Draft</option>
                </select>
              </div>
            </div>

            <!-- Dynamic Options List -->
            <div class="pt-3 border-t border-slate-100">
              <div class="flex items-center justify-between mb-3">
                <label class="block text-xs font-black text-slate-900 uppercase">Poll Voting Choices / Options (At least 2 required)</label>
                <button 
                  type="button" 
                  @click="addPollOption" 
                  class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold flex items-center gap-1 cursor-pointer transition-colors"
                >
                  <Plus class="w-3.5 h-3.5" />
                  <span>Add Choice</span>
                </button>
              </div>

              <div class="space-y-3">
                <div 
                  v-for="(opt, idx) in pollForm.options" 
                  :key="idx"
                  class="p-3 bg-slate-50 border border-slate-200 rounded-2xl relative space-y-2"
                >
                  <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase text-slate-500">Choice #{{ idx + 1 }}</span>
                    <button 
                      type="button" 
                      v-if="pollForm.options.length > 2"
                      @click="removePollOption(idx)"
                      class="p-1 text-slate-400 hover:text-red-600 rounded-lg cursor-pointer"
                      title="Remove Choice"
                    >
                      <Trash2 class="w-3.5 h-3.5" />
                    </button>
                  </div>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <input 
                      v-model="opt.option_text_en" 
                      type="text" 
                      required 
                      placeholder="Choice in English (e.g. Asphalt Road Upgrades)" 
                      class="w-full px-3 py-2 bg-white rounded-xl border border-slate-200 text-xs font-medium" 
                    />
                    <input 
                      v-model="opt.option_text_am" 
                      type="text" 
                      required 
                      placeholder="ምርጫ በአማርኛ (ለምሳሌ፡ የአስፋልት መንገድና ፍሳሽ ማስወገጃ)" 
                      class="w-full px-3 py-2 bg-white rounded-xl border border-slate-200 text-xs font-medium" 
                    />
                  </div>
                  <input 
                    v-model="opt.option_text_om" 
                    type="text" 
                    placeholder="Filannoo Afaan Oromootiin (Optional)" 
                    class="w-full px-3 py-2 bg-white rounded-xl border border-slate-200 text-xs" 
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-5 mt-6 border-t border-slate-100">
            <button 
              type="button" 
              @click="emit('close')" 
              class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer transition-colors"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="submitting" 
              class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-md shadow-amber-500/20 cursor-pointer disabled:opacity-50 transition-all flex items-center gap-2"
            >
              <Loader2 v-if="submitting" class="w-3.5 h-3.5 animate-spin" />
              <CheckCircle2 v-else class="w-3.5 h-3.5" />
              <span>{{ submitting ? 'Saving...' : (isAuthor ? (editingId ? 'Save & Submit for Review' : 'Submit for Editorial Review') : (editingId ? 'Update & Save Changes' : 'Confirm & Publish Live')) }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
</template>
