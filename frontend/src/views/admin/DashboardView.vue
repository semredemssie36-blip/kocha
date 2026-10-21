<script setup>
import { ref, reactive, onMounted, onUnmounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import AdminSidebar from './components/AdminSidebar.vue'
import OverviewTab from './tabs/OverviewTab.vue'
import HeroSlidesTab from './tabs/HeroSlidesTab.vue'
import GalleryTab from './tabs/GalleryTab.vue'
import NewsTab from './tabs/NewsTab.vue'
import TendersTab from './tabs/TendersTab.vue'
import VacanciesTab from './tabs/VacanciesTab.vue'
import MayorTab from './tabs/MayorTab.vue'
import LeadershipTab from './tabs/LeadershipTab.vue'
import OfficesTab from './tabs/OfficesTab.vue'
import ServicesTab from './tabs/ServicesTab.vue'
import ProjectsTab from './tabs/ProjectsTab.vue'
import ResourcesTab from './tabs/ResourcesTab.vue'
import DestinationsTab from './tabs/DestinationsTab.vue'
import MessagesTab from './tabs/MessagesTab.vue'
import StaffTab from './tabs/StaffTab.vue'
import AuditLogsTab from './tabs/AuditLogsTab.vue'
import SettingsTab from './tabs/SettingsTab.vue'
import HotlinesTab from './tabs/HotlinesTab.vue'
import PollsTab from './tabs/PollsTab.vue'
import UniversalItemModal from './modals/UniversalItemModal.vue'
import ProfileSettingsModal from './modals/ProfileSettingsModal.vue'
import ResetStaffPasswordModal from './modals/ResetStaffPasswordModal.vue'
import ConfirmActionModal from './modals/ConfirmActionModal.vue'
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
  Navigation,
  Plus,
  CheckCircle2,
  AlertCircle,
  X,
  Mail,
  Phone,
  Clock,
  ShieldCheck,
  Shield,
  Lock,
  PenTool,
  BookOpen,
  User,
  KeyRound,
  Camera,
  Key,
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
  Play,
  Compass,
  Menu,
  PhoneCall,
  ShieldAlert,
  Flame,
  Droplets,
  Vote,
  Loader2,
  ExternalLink,
  LayoutGrid,
  List,
  Download
} from 'lucide-vue-next'

const router = useRouter()
const activeSection = ref('dashboard')
const activeSubTab = ref('overview')
const mobileSidebarOpen = ref(false)

async function selectSection(section) {
  activeSection.value = section
  mobileSidebarOpen.value = false
  await fetchSectionData(section)
}

const adminLang = ref(localStorage.getItem('kombolcha_admin_lang') || 'en')

function switchAdminLang(lang) {
  adminLang.value = lang
  localStorage.setItem('kombolcha_admin_lang', lang)
}

const adminTranslations = {
  en: {
    portal_title: 'Kombolcha Admin Portal',
    portal_sub: 'Kombolcha Metropolitan Administration',
    search_placeholder: 'Search articles, tenders, offices, citizen messages...',
    live_site: 'Live Site',
    notifications: 'Notifications',
    new: 'New',
    mark_all_read: 'Mark all read',
    no_notifications: 'No new notifications',
    all_caught_up: "You're all caught up with portal inquiries and alerts.",
    go_to_inbox: 'Go to Citizen Inquiries Inbox',
    sign_out: 'Sign Out',
    my_profile: 'Profile & Password Settings',
    
    // Sidebar categories
    cat_media_visuals: 'Media & Visuals',
    cat_content_media: 'Content & Media',
    cat_governance: 'Governance',
    cat_services_resources: 'Services & Resources',
    cat_civic_feedback: 'Civic Feedback',
    cat_administration: 'Administration',

    // Sidebar items
    nav_dashboard: 'Dashboard Overview',
    nav_hero_slides: 'Hero Slider Manager',
    nav_gallery: 'Media Gallery',
    nav_news: 'News & Press Releases',
    nav_tenders: 'Public Tenders',
    nav_vacancies: 'Job Vacancies',
    nav_mayor: 'Mayor Profile & Keynote',
    nav_leadership: 'Cabinet & Council',
    nav_offices: 'Offices & Directory',
    nav_services: 'Text & Step Services',
    nav_projects: 'Mega Infrastructure',
    nav_resources: 'Resources & Directives',
    nav_destinations: 'Tourism & Destinations',
    nav_hotlines: '24/7 Hotlines & Dispatch',
    nav_messages: 'Citizen Inquiries',
    nav_polls: 'Civic Opinion Polls',
    nav_staff: 'Staff Accounts',
    nav_audit: 'Audit & System Logs',
    nav_settings: 'Site Settings & Maintenance',
    hotlines_title: '24/7 Emergency Dispatch Hotlines',
    add_hotline: 'Add Emergency Hotline',

    // Dashboard Overview
    overview_title: 'Dashboard Overview',
    overview_sub: 'Live municipal key performance metrics and quick operational actions.',
    stat_views: 'Total Views This Month',
    stat_traffic: '↑ 14.8% Citizen traffic',
    stat_articles: 'Published Articles',
    stat_articles_sub: 'Live across 3 languages',
    stat_tenders: 'Active Tenders',
    stat_tenders_sub: 'Live procurement bidding',
    stat_hotlines: 'Emergency Hotlines Status',
    stat_hotlines_status: 'Active 24/7 Desk',
    stat_hotlines_sub: 'Police (991), Fire (939), Ambulance',

    // Author Overview stats
    author_news: 'News & Press Releases',
    author_news_sub: 'Articles in municipal registry',
    author_media: 'Media Assets',
    author_media_sub: 'Photos & gallery collections',
    author_resources: 'Directives & Documents',
    author_resources_sub: 'Cataloged citizen publications',
    author_privileges: 'Publishing Privileges',
    author_privileges_status: 'Author Active',
    author_privileges_sub: 'Full drafting & editing rights',

    // Action center
    quick_action_center: 'Quick Action Center',
    rapid_shortcuts: 'Rapid Management Shortcuts',
    quick_create: 'Quick Create',
    action_new_news: '+ New Press Release',
    action_post_tender: '+ Post Public Tender',
    action_post_vacancy: '+ Post Job Opening',
    action_track_project: '+ Track New Project',
    action_upload_photo: '+ Upload Photo Asset',
    action_catalog_directive: '+ Catalog Directive',
    
    // Inquiries Card
    recent_inquiries: 'Recent Inquiries',
    unread: 'unread',
    view_all_inbox: 'View All Inbox →',
    no_inquiries: 'No inquiries received.',

    // Audit Card
    recent_activity: 'Recent Activity & Audit Feed',
    db_active: 'MySQL Database Active',
    dict_synced: 'Trilingual Dictionaries Synced (Amharic, English, Afaan Oromoo)',
    pinia_verified: 'Pinia Store Verified',
    seeded_offices: 'Seeded 22 Municipal Offices & 4 Sub-Cities'
  },
  am: {
    portal_title: 'የኮምቦልቻ አስተዳደር ፖርታል',
    portal_sub: 'የኮምቦልቻ ከተማ አስተዳደር',
    search_placeholder: 'ዜናዎች፣ ጨረታዎች፣ ቢሮዎች፣ የዜጎች መልዕክቶች ይፈልጉ...',
    live_site: 'ቀጥታ ድረ-ገጽ',
    notifications: 'ማሳወቂያዎች',
    new: 'አዲስ',
    mark_all_read: 'ሁሉንም አንብቤያለሁ',
    no_notifications: 'ምንም አዲስ ማሳወቂያ የለም',
    all_caught_up: 'ሁሉም የዜጎች ጥያቄዎች እና የስርዓት ማሳወቂያዎች ታይተዋል።',
    go_to_inbox: 'ወደ ዜጎች ጥያቄዎች ሳጥን ሂድ',
    sign_out: 'ውጣ',
    my_profile: 'የመገለጫ እና የይለፍ ቃል ቅንብሮች',

    // Sidebar categories
    cat_media_visuals: 'ሚዲያ እና ምስላዊ ይዘቶች',
    cat_content_media: 'ይዘት እና ሚዲያ',
    cat_governance: 'አስተዳደር እና አመራር',
    cat_services_resources: 'አገልግሎቶችና ግብዓቶች',
    cat_civic_feedback: 'የዜጎች ግብረ-መልስ',
    cat_administration: 'አስተዳደራዊ ቁጥጥር',

    // Sidebar items
    nav_dashboard: 'ዳሽቦርድ አጠቃላይ እይታ',
    nav_hero_slides: 'የስላይደር ማስተዳደሪያ',
    nav_gallery: 'የሚዲያ ማህደር',
    nav_news: 'ዜናዎች እና መግለጫዎች',
    nav_tenders: 'የመንግስት ጨረታዎች',
    nav_vacancies: 'ክፍት የሥራ ቦታዎች',
    nav_mayor: 'የከንቲባ መገለጫና መልዕክት',
    nav_leadership: 'ካቢኔ እና ም/ቤት አባላት',
    nav_offices: 'የመምሪያዎች ማውጫ',
    nav_services: 'የዜጎች አገልግሎቶች',
    nav_projects: 'ግዙፍ የመሰረተ ልማት ፕሮጀክቶች',
    nav_resources: 'መመሪያዎችና ሰነዶች',
    nav_destinations: 'ቱሪዝም እና መዳረሻዎች',
    nav_hotlines: '24/7 ድንገተኛ ጥሪዎች',
    nav_messages: 'የዜጎች ጥያቄዎችና አስተያየቶች',
    nav_polls: 'የህዝብ አስተያየት መስጫ (Polls)',
    nav_staff: 'የሠራተኞች መለያዎች',
    nav_audit: 'የኦዲትና የሥርዓት ምዝግብ',
    nav_settings: 'የስርዓት ቅንብሮች እና ጥገና',
    hotlines_title: 'የ 24/7 ድንገተኛ ጥሪዎችና መረጃ መስመሮች',
    add_hotline: 'አዲስ የድንገተኛ ስልክ አክል',

    // Dashboard Overview
    overview_title: 'ዳሽቦርድ አጠቃላይ እይታ',
    overview_sub: 'የከተማው ቁልፍ የአፈጻጸም መረጃዎችና ፈጣን የሥራ ክንውኖች።',
    stat_views: 'የዚህ ወር ጠቅላላ ጎብኝዎች',
    stat_traffic: '↑ 14.8% የዜጎች ተሳትፎ',
    stat_articles: 'የታተሙ ዜናዎች',
    stat_articles_sub: 'በ3 ቋንቋዎች የቀረበ',
    stat_tenders: 'ንቁ ጨረታዎች',
    stat_tenders_sub: 'በሂደት ላይ ያሉ የመንግስት ግዥዎች',
    stat_hotlines: 'የአስቸኳይ ጥሪዎች ሁኔታ',
    stat_hotlines_status: 'የ24/7 ዝግጁ ጠረጴዛ',
    stat_hotlines_sub: 'ፖሊስ (991)፣ እሳት አደጋ (939)፣ አምቡላንስ',

    // Author Overview stats
    author_news: 'ዜናዎች እና መግለጫዎች',
    author_news_sub: 'በማዘጋጃ ቤቱ መዝገብ የተካተቱ',
    author_media: 'የሚዲያ ሀብቶች',
    author_media_sub: 'የፎቶዎችና ጋለሪ ስብስቦች',
    author_resources: 'መመሪያዎችና ሰነዶች',
    author_resources_sub: 'ለዜጎች የቀረቡ ህትመቶች',
    author_privileges: 'የማዘጋጀት ፈቃዶች',
    author_privileges_status: 'ጸሐፊ ንቁ ነው',
    author_privileges_sub: 'ሙሉ የማዘጋጀትና የማረም መብት',

    // Action center
    quick_action_center: 'ፈጣን የሥራ ማዕከል',
    rapid_shortcuts: 'ፈጣን የአስተዳደር አቋራጮች',
    quick_create: 'ፈጣን ፍጠር',
    action_new_news: '+ አዲስ ዜና አውጣ',
    action_post_tender: '+ አዲስ ጨረታ ለጥፍ',
    action_post_vacancy: '+ ክፍት የስራ ቦታ ለጥፍ',
    action_track_project: '+ አዲስ ፕሮጀክት መዝግብ',
    action_upload_photo: '+ ፎቶ ወደ ማህደር ጫን',
    action_catalog_directive: '+ መመሪያ/ሰነድ መዝግብ',

    // Inquiries Card
    recent_inquiries: 'የቅርብ ጊዜ ጥያቄዎች',
    unread: 'ያልተነበበ',
    view_all_inbox: 'ሁሉንም ሳጥን ይመልከቱ →',
    no_inquiries: 'ምንም ጥያቄ አልደረሰም።',

    // Audit Card
    recent_activity: 'የቅርብ ጊዜ እንቅስቃሴዎችና ኦዲት',
    db_active: 'የMySQL ውሂብ ጎታ ንቁ ነው',
    dict_synced: 'የሶስት ቋንቋዎች መዝገበ-ቃላት ተመሳስለዋል (አማርኛ፣ እንግሊዝኛ፣ አፋን ኦሮሞ)',
    pinia_verified: 'የስቴት ማከማቻ (Pinia) ተረጋግጧል',
    seeded_offices: '22 የመንግስት መስሪያ ቤቶችና 4 ክፍለ ከተሞች ተመዝግበዋል'
  },
  om: {
    portal_title: 'Poortaalii Bulchiinsa Kombolchaa',
    portal_sub: 'Bulchiinsa Magaalaa Kombolchaa',
    search_placeholder: 'Oduu, caalbaasii, waajjiraalee, ergaa lammiilee barbaadaa...',
    live_site: 'Marsariitii Kallattii',
    notifications: 'Beeksisoota',
    new: 'Haaraa',
    mark_all_read: 'Hunda dubbiseera',
    no_notifications: 'Beeksisni haaraa hin jiru',
    all_caught_up: 'Gaaffiileen lammiilee fi beeksisoonni sirnaa hundi ilaalamaniiru.',
    go_to_inbox: 'Gara Saanduqa Lammiilee Dhaqi',
    sign_out: 'Ba\'i',
    my_profile: 'Qindaa\'ina Eenyummaa fi Jecha Icchiitii',
    cat_media_visuals: 'Miidiyaa fi Suuraa',
    cat_content_media: 'Qabiyyee fi Miidiyaa',
    cat_governance: 'Bulchiinsa fi Hoggansa',
    cat_services_resources: 'Tajaajiloota fi Qabeenya',
    cat_civic_feedback: 'Yaada Lammiilee',
    cat_administration: 'Bulchiinsa Sirnaa',
    nav_dashboard: 'Ilaalcha Waliigalaa',
    nav_hero_slides: 'Qindeessaa Islaayidii',
    nav_gallery: 'Kuusaa Suuraa fi Viidiyoo',
    nav_news: 'Oduu fi Ibsa',
    nav_tenders: 'Caalbaasii Mootummaa',
    nav_vacancies: 'Banaa Hojii',
    nav_mayor: 'Eenyummaa fi Ergaa Kantiibaa',
    nav_leadership: 'Kaabinee fi Koree Magaalaa',
    nav_offices: 'Baafata Waajjiraalee',
    nav_services: 'Tajaajila Lammiilee',
    nav_projects: 'Pirojektoota Gurguddoo',
    nav_resources: 'Qajeelfamoota fi Sanadoota',
    nav_destinations: 'Daawwannaa fi Bakkeewwan',
    nav_hotlines: 'Sarara Balaa 24/7',
    nav_messages: 'Gaaffiilee Lammiilee',
    nav_polls: 'Yaada Ummataa (Polls)',
    nav_staff: 'Hojjattoota Sirnaa',
    nav_audit: 'Galmee Odiitii fi Sirnaa',
    nav_settings: 'Qindaa\'ina fi Suphaa',
    hotlines_title: 'Sarara Balaa fi Tajaajila Tasaa (24/7)',
    add_hotline: 'Lakkoofsa Balaa Haaraa Dabali'
  }
}

const adminT = computed(() => {
  const dict = adminTranslations[adminLang.value] || adminTranslations['en']
  return (key) => dict[key] || key
})

// Global Search in Admin
const adminSearch = ref('')

// KPIs & Stats bound strictly to real database endpoints
const stats = ref({
  news_count: 0,
  published_news: 0,
  pending_drafts: 0,
  active_tenders: 0,
  offices_count: 0,
  total_offices: 0,
  unread_messages: 0,
  projects_count: 0,
  destinations_count: 0,
  staff_count: 0
})

const currentUser = ref(JSON.parse(localStorage.getItem('kombolcha_admin_user') || '{}'))

// RBAC Roles & Capabilities
const role = computed(() => currentUser.value?.role || 'author')
const isSuperAdmin = computed(() => role.value === 'super_admin')
const isEditor = computed(() => role.value === 'editor' || isSuperAdmin.value)
const isAuthor = computed(() => role.value === 'author')

const roleLabel = computed(() => {
  if (adminLang.value === 'am') {
    if (role.value === 'super_admin') return 'ዋና አስተዳዳሪ'
    if (role.value === 'editor') return 'የኮሙኒኬሽን አዘጋጅ'
    if (role.value === 'author') return 'የዜና ጸሐፊ / ጋዜጠኛ'
    return role.value
  }
  if (role.value === 'super_admin') return 'Super Administrator'
  if (role.value === 'editor') return 'Communications Editor'
  if (role.value === 'author') return 'Content Author / Journalist'
  return role.value
})

const roleBadgeClass = computed(() => {
  if (role.value === 'super_admin') return 'bg-purple-500/20 text-purple-300 border border-purple-500/30'
  if (role.value === 'editor') return 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
  return 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'
})

const can = computed(() => ({
  // Content Modules (News, Gallery, Resources, Tenders, Vacancies, Services, Projects, Destinations)
  // Super Admin: Yes, Editor: Yes, Author: Yes (Create content)
  createContent: true,
  createNews: true,
  createGallery: true,
  createResources: true,
  createTenders: true,
  createVacancies: true,
  createServices: true,
  createProjects: true,
  createDestinations: true,

  // Publishing & Review: Super Admin & Editor only (Row 6 & Row 7)
  publishContent: isEditor.value,
  unpublishContent: isEditor.value,
  reviewContent: isEditor.value,

  // Navigation visibility for content modules
  uploadMedia: true,
  editMedia: true,
  deleteMedia: isEditor.value,

  editResources: true,
  deleteResources: isEditor.value,

  manageDestinations: true,
  manageTenders: true,
  manageVacancies: true,
  manageServices: true,
  manageProjects: true,

  // Menus and homepage (Row 9): Super Admin & Editor only (assigned CMS responsibility)
  manageHeroSlides: isEditor.value,

  // Governance & Civic Feedback: Super Admin & Editor only
  manageMayor: isEditor.value,
  manageLeadership: isEditor.value,
  manageOffices: isEditor.value,
  deleteGovernance: isSuperAdmin.value,
  viewInquiries: isEditor.value,
  manageHotlines: isEditor.value,
  managePolls: isEditor.value,

  // Users, Roles & Settings: Super Admin only (Rows 1 & 2)
  manageStaff: isSuperAdmin.value,
  manageAudit: isSuperAdmin.value,
  manageSettings: isSuperAdmin.value,
  canDelete: isEditor.value
}))

// Ownership & Draft Permissions Helpers for Content Items
function canEditItem(item) {
  if (isSuperAdmin.value || isEditor.value) return true
  if (isAuthor.value) {
    const isOwner = !item.author_id || item.author_id === currentUser.value?.id
    const isDraft = !item.status || item.status === 'draft' || item.status === 'pending' || item.publication_status === 'draft' || item.publication_status === 'pending'
    return isOwner && isDraft
  }
  return false
}

function canDeleteItem(item) {
  if (isSuperAdmin.value || isEditor.value) return true
  if (isAuthor.value) {
    const isOwner = !item.author_id || item.author_id === currentUser.value?.id
    const isDraft = item.status === 'draft' || item.publication_status === 'draft'
    return isOwner && isDraft
  }
  return false
}

// Section navigation guard & on-demand data fetcher
watch(activeSection, async (sec) => {
  const authorAllowed = ['dashboard', 'news', 'gallery', 'resources', 'tenders', 'vacancies', 'services', 'projects', 'destinations']
  if (isAuthor.value && !authorAllowed.includes(sec)) {
    activeSection.value = 'dashboard'
    showToast('Permission restricted: Navigated to your authorized workspace.')
    return
  } else if (!isSuperAdmin.value && ['staff', 'settings', 'audit'].includes(sec)) {
    activeSection.value = 'dashboard'
    showToast('Super Administrator privilege required.')
    return
  }
  await fetchSectionData(sec)
})

// Data Collections
const newsList = ref([])
const tendersList = ref([])
const vacanciesList = ref([])
const servicesList = ref([])
const projectsList = ref([])
const resourcesList = ref([])
const officesList = ref([])

// Offices Search & Pagination Controls
const officesViewMode = ref('grid') // 'grid' | 'table'
const officeSearch = ref('')
const officePage = ref(1)
const officePerPage = ref(8)

const filteredOffices = computed(() => {
  if (!officeSearch.value.trim()) return officesList.value
  const q = officeSearch.value.toLowerCase().trim()
  return officesList.value.filter(off => 
    (off.name_en && off.name_en.toLowerCase().includes(q)) ||
    (off.name_am && off.name_am.includes(q)) ||
    (off.phone && off.phone.includes(q)) ||
    (off.room_number && off.room_number.toLowerCase().includes(q)) ||
    (off.building_location_am && off.building_location_am.includes(q))
  )
})

const totalOfficePages = computed(() => Math.ceil(filteredOffices.value.length / officePerPage.value) || 1)

const paginatedOffices = computed(() => {
  const start = (officePage.value - 1) * officePerPage.value
  return filteredOffices.value.slice(start, start + officePerPage.value)
})

watch(officeSearch, () => {
  officePage.value = 1
})

// News Search & Pagination
const newsSearch = ref('')
const newsPage = ref(1)
const newsPerPage = ref(8)
const filteredNews = computed(() => {
  if (!newsSearch.value.trim()) return newsList.value
  const q = newsSearch.value.toLowerCase().trim()
  return newsList.value.filter(n =>
    (n.title_en && n.title_en.toLowerCase().includes(q)) ||
    (n.title_am && n.title_am.includes(q)) ||
    (n.category && n.category.toLowerCase().includes(q)) ||
    (n.status && n.status.toLowerCase().includes(q))
  )
})
const totalNewsPages = computed(() => Math.ceil(filteredNews.value.length / newsPerPage.value) || 1)
const paginatedNews = computed(() => {
  const start = (newsPage.value - 1) * newsPerPage.value
  return filteredNews.value.slice(start, start + newsPerPage.value)
})
watch(newsSearch, () => { newsPage.value = 1 })

// Citizen Messages Search & Pagination
const messagesSearch = ref('')
const messagesPage = ref(1)
const messagesPerPage = ref(6)
const filteredMessages = computed(() => {
  if (!messagesSearch.value.trim()) return messagesList.value
  const q = messagesSearch.value.toLowerCase().trim()
  return messagesList.value.filter(m =>
    (m.sender_name && m.sender_name.toLowerCase().includes(q)) ||
    (m.sender_phone && m.sender_phone.includes(q)) ||
    (m.sender_email && m.sender_email.toLowerCase().includes(q)) ||
    (m.subject && m.subject.toLowerCase().includes(q)) ||
    (m.message && m.message.toLowerCase().includes(q)) ||
    (m.status && m.status.toLowerCase().includes(q))
  )
})
const totalMessagesPages = computed(() => Math.ceil(filteredMessages.value.length / messagesPerPage.value) || 1)
const paginatedMessages = computed(() => {
  const start = (messagesPage.value - 1) * messagesPerPage.value
  return filteredMessages.value.slice(start, start + messagesPerPage.value)
})
watch(messagesSearch, () => { messagesPage.value = 1 })

// Tenders Search & Pagination
const tendersSearch = ref('')
const tendersPage = ref(1)
const tendersPerPage = ref(8)
const filteredTenders = computed(() => {
  if (!tendersSearch.value.trim()) return tendersList.value
  const q = tendersSearch.value.toLowerCase().trim()
  return tendersList.value.filter(t =>
    (t.reference_number && t.reference_number.toLowerCase().includes(q)) ||
    (t.title_en && t.title_en.toLowerCase().includes(q)) ||
    (t.title_am && t.title_am.includes(q)) ||
    (t.category && t.category.toLowerCase().includes(q))
  )
})
const totalTendersPages = computed(() => Math.ceil(filteredTenders.value.length / tendersPerPage.value) || 1)
const paginatedTenders = computed(() => {
  const start = (tendersPage.value - 1) * tendersPerPage.value
  return filteredTenders.value.slice(start, start + tendersPerPage.value)
})
watch(tendersSearch, () => { tendersPage.value = 1 })

// Vacancies Search & Pagination
const vacanciesViewMode = ref('table') // 'table' | 'grid'
const vacanciesSearch = ref('')
const vacanciesPage = ref(1)
const vacanciesPerPage = ref(8)
const filteredVacancies = computed(() => {
  if (!vacanciesSearch.value.trim()) return vacanciesList.value
  const q = vacanciesSearch.value.toLowerCase().trim()
  return vacanciesList.value.filter(v =>
    (v.position_code && v.position_code.toLowerCase().includes(q)) ||
    (v.title_en && v.title_en.toLowerCase().includes(q)) ||
    (v.title_am && v.title_am.includes(q)) ||
    (v.employment_type && v.employment_type.toLowerCase().includes(q))
  )
})
const totalVacanciesPages = computed(() => Math.ceil(filteredVacancies.value.length / vacanciesPerPage.value) || 1)
const paginatedVacancies = computed(() => {
  const start = (vacanciesPage.value - 1) * vacanciesPerPage.value
  return filteredVacancies.value.slice(start, start + vacanciesPerPage.value)
})
watch(vacanciesSearch, () => { vacanciesPage.value = 1 })

// Resources Search & Pagination
const resourcesViewMode = ref('table') // 'table' | 'grid'
const resourcesSearch = ref('')
const resourcesPage = ref(1)
const resourcesPerPage = ref(8)
const filteredResources = computed(() => {
  if (!resourcesSearch.value.trim()) return resourcesList.value
  const q = resourcesSearch.value.toLowerCase().trim()
  return resourcesList.value.filter(r =>
    (r.title_en && r.title_en.toLowerCase().includes(q)) ||
    (r.title_am && r.title_am.includes(q)) ||
    (r.category && r.category.toLowerCase().includes(q)) ||
    (r.year && r.year.toString().includes(q))
  )
})
const totalResourcesPages = computed(() => Math.ceil(filteredResources.value.length / resourcesPerPage.value) || 1)
const paginatedResources = computed(() => {
  const start = (resourcesPage.value - 1) * resourcesPerPage.value
  return filteredResources.value.slice(start, start + resourcesPerPage.value)
})
watch(resourcesSearch, () => { resourcesPage.value = 1 })

// Media Gallery Search & Pagination
const galleryViewMode = ref('grid') // 'grid' | 'table'
const gallerySearch = ref('')
const galleryCategoryFilter = ref('all')
const galleryPage = ref(1)
const galleryPerPage = ref(8)
const filteredGallery = computed(() => {
  return galleryList.value.filter(g => {
    const q = gallerySearch.value.toLowerCase().trim()
    const matchesSearch = !q ||
      (g.caption_en && g.caption_en.toLowerCase().includes(q)) ||
      (g.caption_am && g.caption_am.includes(q)) ||
      (g.category && g.category.toLowerCase().includes(q)) ||
      (g.media_type && g.media_type.toLowerCase().includes(q))
    const matchesCat = galleryCategoryFilter.value === 'all' || g.category === galleryCategoryFilter.value
    return matchesSearch && matchesCat
  })
})
const totalGalleryPages = computed(() => Math.ceil(filteredGallery.value.length / galleryPerPage.value) || 1)
const paginatedGallery = computed(() => {
  const start = (galleryPage.value - 1) * galleryPerPage.value
  return filteredGallery.value.slice(start, start + galleryPerPage.value)
})
watch(gallerySearch, () => { galleryPage.value = 1 })
watch(galleryCategoryFilter, () => { galleryPage.value = 1 })

// Hero Slides Search & ViewMode
const heroSlidesViewMode = ref('cards') // 'cards' | 'table'
const heroSlidesSearch = ref('')
const filteredHeroSlides = computed(() => {
  if (!heroSlidesSearch.value.trim()) return heroSlidesList.value
  const q = heroSlidesSearch.value.toLowerCase().trim()
  return heroSlidesList.value.filter(s =>
    (s.title_en && s.title_en.toLowerCase().includes(q)) ||
    (s.title_am && s.title_am.includes(q)) ||
    (s.tag_en && s.tag_en.toLowerCase().includes(q)) ||
    (s.tag_am && s.tag_am.includes(q)) ||
    (s.subtitle_en && s.subtitle_en.toLowerCase().includes(q))
  )
})

// Leadership Search & ViewMode
const leadershipViewMode = ref('cards') // 'cards' | 'table'
const leadershipSearch = ref('')
const leadershipTypeFilter = ref('all') // 'all' | 'cabinet' | 'council'
const filteredLeadership = computed(() => {
  return leadershipList.value.filter(m => {
    const q = leadershipSearch.value.toLowerCase().trim()
    const matchesSearch = !q ||
      (m.name_en && m.name_en.toLowerCase().includes(q)) ||
      (m.name_am && m.name_am.includes(q)) ||
      (m.position_en && m.position_en.toLowerCase().includes(q)) ||
      (m.position_am && m.position_am.includes(q)) ||
      (m.email && m.email.toLowerCase().includes(q)) ||
      (m.phone && m.phone.includes(q))
    const matchesType = leadershipTypeFilter.value === 'all' || m.type === leadershipTypeFilter.value
    return matchesSearch && matchesType
  })
})

const messagesList = ref([])
const staffList = ref([])
const auditLogsList = ref([])
const auditLogsTotal = ref(0)
const auditLogsCurrentPage = ref(1)
const auditLogsLastPage = ref(1)
const auditLogsLoading = ref(false)
const auditLogsFilters = ref({
  search: '',
  action: 'all',
  module: 'all',
  user_role: 'all',
  date_from: '',
  date_to: ''
})

async function fetchAuditLogs(page = 1) {
  if (!isSuperAdmin.value) return
  auditLogsLoading.value = true
  try {
    const params = {
      page,
      per_page: 25,
      ...auditLogsFilters.value
    }
    const res = await api.get('/admin/audit-logs', { params })
    auditLogsList.value = res.data.data || []
    auditLogsTotal.value = res.data.total || 0
    auditLogsCurrentPage.value = res.data.current_page || 1
    auditLogsLastPage.value = res.data.last_page || 1
  } catch (err) {
    console.error('Failed to fetch audit logs:', err)
  } finally {
    auditLogsLoading.value = false
  }
}

function updateAuditFilter(newFilters) {
  auditLogsFilters.value = { ...auditLogsFilters.value, ...newFilters }
  fetchAuditLogs(1)
}

async function purgeOldAuditLogs() {
  if (!isSuperAdmin.value) return
  const days = prompt('Enter the retention threshold in days. Logs older than this will be permanently purged (default 90):', '90')
  if (!days || isNaN(days)) return
  try {
    const res = await api.post('/admin/audit-logs/clear', { days: parseInt(days) })
    showToast(res.data?.message || 'Old audit logs purged successfully.')
    await fetchAuditLogs(1)
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to purge audit logs.')
  }
}

const mayorData = ref({})
const leadershipList = ref([])
const galleryList = ref([])
const heroSlidesList = ref([])
const destinationsList = ref([])
const emergencyContactsList = ref([])
const pollsList = ref([])
const siteSettings = ref({
  site_name_en: 'Kombolcha City Administration',
  site_name_am: 'የኮምቦልቻ ከተማ አስተዳደር',
  site_name_om: 'Bulchiinsa Magaalaa Kombolchaa',
  contact_phone: '+251-33-551-0024',
  contact_email: 'info@kombolcha.gov.et',
  emergency_notice_en: '',
  emergency_notice_am: '',
  emergency_notice_om: '',
  is_maintenance: 0,
  latitude: 11.0845000,
  longitude: 39.7390000,
  map_address_en: 'Main Administrative Corridor, Kebele 02, Kombolcha, Amhara Region, Ethiopia',
  map_address_am: 'ዋናው አስተዳደር ኮሪደር፣ ቀበሌ 02፣ ኮምቦልቻ፣ አማራ ክልል፣ ኢትዮጵያ',
  map_address_om: 'Kooridorii Bulchiinsa Magaalaa, Ganda 02, Kombolcha, Naannoo Amaaraa, Itoophiyaa',
  map_zoom: 15
})

// Global Search & Command Bar State
const adminSearchFocused = ref(false)
const adminSearchInputRef = ref(null)

const adminSearchResults = computed(() => {
  const q = adminSearch.value.toLowerCase().trim()
  if (!q) return []
  
  const results = []

  // 1. Navigation sections matching
  const navSections = [
    { name: 'Dashboard Overview', am: 'ዳሽቦርድ አጠቃላይ እይታ', section: 'dashboard', icon: LayoutDashboard },
    { name: 'News & Press Bureau', am: 'ዜናዎች እና መግለጫዎች', section: 'news', icon: FileText },
    { name: 'Public Tenders & Procurement', am: 'የመንግስት ጨረታዎች', section: 'tenders', icon: FileCheck },
    { name: 'Civil Service Vacancies', am: 'ክፍት የሥራ ቦታዎች', section: 'vacancies', icon: Briefcase },
    { name: 'Public Citizen Services', am: 'የዜጎች አገልግሎቶች', section: 'services', icon: Layers },
    { name: 'Mega Infrastructure Projects', am: 'ግዙፍ የመሰረተ ልማት ፕሮጀክቶች', section: 'projects', icon: HardHat },
    { name: 'Municipal Offices & Directory', am: 'የመምሪያዎች ማውጫ', section: 'offices', icon: Building2 },
    { name: 'Directives & Document Library', am: 'መመሪያዎችና ሰነዶች', section: 'resources', icon: FolderOpen },
    { name: 'Citizen Inquiries & Feedback', am: 'የዜጎች ጥያቄዎችና አስተያየቶች', section: 'messages', icon: MessageSquare },
    { name: '24/7 Emergency Hotlines', am: '24/7 ድንገተኛ ጥሪዎች', section: 'hotlines', icon: Phone },
    { name: 'Investment & Tourism Destinations', am: 'ቱሪዝም እና መዳረሻዎች', section: 'destinations', icon: Globe },
    { name: 'Media Gallery Archive', am: 'የሚዲያ ማህደር', section: 'gallery', icon: Camera },
    { name: 'Hero Slideshow Manager', am: 'የስላይደር ማስተዳደሪያ', section: 'hero_slides', icon: Zap },
    { name: 'Mayor Profile & Executive Keynote', am: 'የከንቲባ መገለጫና መልዕክት', section: 'mayor', icon: Award },
    { name: 'Cabinet & Council Members', am: 'ካቢኔ እና ም/ቤት አባላት', section: 'leadership', icon: Users },
    { name: 'Civic Opinion Polls', am: 'የህዝብ አስተያየት መስጫ', section: 'polls', icon: Activity },
    { name: 'Municipal Staff Accounts', am: 'የሠራተኞች መለያዎች', section: 'staff', icon: Shield },
    { name: 'System Audit Logs', am: 'የኦዲትና የሥርዓት ምዝግብ', section: 'audit', icon: ShieldCheck },
    { name: 'System Settings & Maintenance', am: 'የስርዓት ቅንብሮች እና ጥገና', section: 'settings', icon: Settings }
  ]

  for (const s of navSections) {
    if (s.name.toLowerCase().includes(q) || s.am.includes(q)) {
      results.push({
        id: 'nav-' + s.section,
        type: 'navigation',
        title: s.name,
        sub: s.am + ' • Quick Navigation',
        section: s.section,
        icon: s.icon
      })
    }
  }

  // 2. News Articles
  for (const item of newsList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    const cat = item.category || ''
    if (title.toLowerCase().includes(q) || cat.toLowerCase().includes(q)) {
      results.push({
        id: 'news-' + item.id,
        type: 'news',
        title: item.title_en || item.title_am,
        sub: `${item.category || 'Gazette'} • ${item.status || 'draft'} • ${new Date(item.published_at || item.created_at).toLocaleDateString()}`,
        section: 'news',
        item
      })
      if (results.length > 20) break
    }
  }

  // 3. Tenders
  for (const item of tendersList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    const ref = item.reference_number || ''
    if (title.toLowerCase().includes(q) || ref.toLowerCase().includes(q)) {
      results.push({
        id: 'tender-' + item.id,
        type: 'tender',
        title: item.title_en || item.title_am,
        sub: `Ref: ${ref} • ${item.category || 'Procurement'} • ${item.status || 'open'}`,
        section: 'tenders',
        item
      })
      if (results.length > 25) break
    }
  }

  // 4. Vacancies
  for (const item of vacanciesList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    const code = item.position_code || ''
    if (title.toLowerCase().includes(q) || code.toLowerCase().includes(q)) {
      results.push({
        id: 'vacancy-' + item.id,
        type: 'vacancy',
        title: item.title_en || item.title_am,
        sub: `Code: ${code} • ${item.employment_type || 'Full Time'} • Deadline: ${item.deadline || 'Open'}`,
        section: 'vacancies',
        item
      })
      if (results.length > 30) break
    }
  }

  // 5. Municipal Offices
  for (const item of officesList.value) {
    const name = (item.name_en || '') + ' ' + (item.name_am || '')
    const room = item.room_number || ''
    const phone = item.phone || ''
    if (name.toLowerCase().includes(q) || room.toLowerCase().includes(q) || phone.includes(q)) {
      results.push({
        id: 'office-' + item.id,
        type: 'office',
        title: item.name_en || item.name_am,
        sub: `Room: ${room || 'Hall'} • Phone: ${phone || 'N/A'} • ${item.head_name_en || ''}`,
        section: 'offices',
        item
      })
      if (results.length > 35) break
    }
  }

  // 6. Citizen Messages / Inquiries
  for (const item of messagesList.value) {
    const searchTarget = `${item.sender_name || ''} ${item.subject || ''} ${item.message || ''} ${item.sender_phone || ''}`
    if (searchTarget.toLowerCase().includes(q)) {
      results.push({
        id: 'msg-' + item.id,
        type: 'message',
        title: item.sender_name + (item.subject ? ` — ${item.subject}` : ''),
        sub: `${item.sender_phone || item.sender_email || 'Citizen'} • ${item.status || 'unread'} • "${(item.message || '').substring(0, 50)}..."`,
        section: 'messages',
        item
      })
      if (results.length > 40) break
    }
  }

  // 7. Services
  for (const item of servicesList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    if (title.toLowerCase().includes(q) || (item.category && item.category.toLowerCase().includes(q))) {
      results.push({
        id: 'srv-' + item.id,
        type: 'service',
        title: item.title_en || item.title_am,
        sub: `${item.type === 'step' ? 'Step Guide' : 'Direct Service'} • ${item.category || 'Civil'}`,
        section: 'services',
        item
      })
      if (results.length > 45) break
    }
  }

  // 8. Projects
  for (const item of projectsList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    if (title.toLowerCase().includes(q) || (item.sector && item.sector.toLowerCase().includes(q))) {
      results.push({
        id: 'proj-' + item.id,
        type: 'project',
        title: item.title_en || item.title_am,
        sub: `Sector: ${item.sector || 'Urban'} • ${item.progress_percent || 0}% Complete`,
        section: 'projects',
        item
      })
      if (results.length > 50) break
    }
  }

  // 9. Resources & Directives
  for (const item of resourcesList.value) {
    const title = (item.title_en || '') + ' ' + (item.title_am || '')
    if (title.toLowerCase().includes(q) || (item.category && item.category.toLowerCase().includes(q))) {
      results.push({
        id: 'res-' + item.id,
        type: 'resource',
        title: item.title_en || item.title_am,
        sub: `${item.category || 'Document'} • Year: ${item.year || '2026'}`,
        section: 'resources',
        item
      })
      if (results.length > 55) break
    }
  }

  // 10. Hotlines
  for (const item of emergencyContactsList.value) {
    const name = (item.name_en || '') + ' ' + (item.name_am || '')
    const phone = item.phone_number || ''
    if (name.toLowerCase().includes(q) || phone.includes(q)) {
      results.push({
        id: 'hotline-' + item.id,
        type: 'hotline',
        title: item.name_en || item.name_am,
        sub: `Dial: ${phone} • Category: ${item.category || 'Emergency'}`,
        section: 'hotlines',
        item
      })
      if (results.length > 60) break
    }
  }

  return results
})

function getSearchResultBadge(type) {
  switch (type) {
    case 'navigation':
      return { label: 'Navigate', bg: 'bg-slate-100 text-slate-700', pill: 'bg-slate-100 text-slate-700', icon: LayoutDashboard }
    case 'news':
      return { label: 'News', bg: 'bg-blue-100 text-blue-700', pill: 'bg-blue-50 text-blue-700 border border-blue-200', icon: FileText }
    case 'tender':
      return { label: 'Tender', bg: 'bg-amber-100 text-amber-800', pill: 'bg-amber-50 text-amber-800 border border-amber-200', icon: FileCheck }
    case 'vacancy':
      return { label: 'Job', bg: 'bg-emerald-100 text-emerald-800', pill: 'bg-emerald-50 text-emerald-800 border border-emerald-200', icon: Briefcase }
    case 'office':
      return { label: 'Office', bg: 'bg-indigo-100 text-indigo-700', pill: 'bg-indigo-50 text-indigo-700 border border-indigo-200', icon: Building2 }
    case 'message':
      return { label: 'Inquiry', bg: 'bg-rose-100 text-rose-700', pill: 'bg-rose-50 text-rose-700 border border-rose-200', icon: MessageSquare }
    case 'service':
      return { label: 'Service', bg: 'bg-teal-100 text-teal-700', pill: 'bg-teal-50 text-teal-700 border border-teal-200', icon: Layers }
    case 'project':
      return { label: 'Project', bg: 'bg-orange-100 text-orange-700', pill: 'bg-orange-50 text-orange-700 border border-orange-200', icon: HardHat }
    case 'resource':
      return { label: 'Document', bg: 'bg-cyan-100 text-cyan-700', pill: 'bg-cyan-50 text-cyan-700 border border-cyan-200', icon: FolderOpen }
    case 'hotline':
      return { label: 'Hotline', bg: 'bg-red-100 text-red-700', pill: 'bg-red-50 text-red-700 border border-red-200', icon: Phone }
    default:
      return { label: 'Record', bg: 'bg-slate-100 text-slate-700', pill: 'bg-slate-100 text-slate-700', icon: Search }
  }
}

function handleSearchResultClick(result) {
  activeSection.value = result.section
  
  // Set the search filter within the target section so the user lands directly on that record
  if (result.type === 'news' && result.item) {
    newsSearch.value = result.item.title_en || result.item.title_am || ''
  } else if (result.type === 'tender' && result.item) {
    tendersSearch.value = result.item.reference_number || result.item.title_en || ''
  } else if (result.type === 'vacancy' && result.item) {
    vacanciesSearch.value = result.item.position_code || result.item.title_en || ''
  } else if (result.type === 'office' && result.item) {
    officeSearch.value = result.item.name_en || result.item.name_am || ''
  } else if (result.type === 'resource' && result.item) {
    resourcesSearch.value = result.item.title_en || ''
  } else if (result.type === 'message' && result.item) {
    messagesSearch.value = result.item.sender_name || result.item.subject || ''
  }

  adminSearch.value = ''
  adminSearchFocused.value = false
}

function handleGlobalKeydown(e) {
  if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
    e.preventDefault()
    adminSearchInputRef.value?.focus()
    adminSearchFocused.value = true
  }
  if (e.key === 'Escape') {
    adminSearchFocused.value = false
  }
}

// Loading & Modal States
const loading = ref(false)
const uploadingFile = ref(false)
const modalType = ref('') // 'news', 'tender', 'vacancy', 'service', 'project', 'resource', 'staff', 'office', 'leadership', 'hero_slide', 'gallery', 'destination'
const submitting = ref(false)
const alertToast = ref('')

// Form Models
const newsForm = reactive({
  title_am: '', title_en: '', title_om: '', category: 'General',
  content_am: '', content_en: '', content_om: '', status: 'published',
  thumbnail_path: ''
})

const destinationForm = reactive({
  category: 'nature',
  title_am: '',
  title_en: '',
  title_om: '',
  location_am: '',
  location_en: '',
  location_om: '',
  summary_am: '',
  summary_en: '',
  summary_om: '',
  description_am: '',
  description_en: '',
  description_om: '',
  visiting_hours: '',
  contact_phone: '',
  contact_email: '',
  admission_fee: '',
  features: '',
  image_path: '',
  order: 0
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
  media_paths: [],
  thumbnail_url: '',
  caption_am: '',
  caption_en: '',
  caption_om: '',
  order: 0
})

async function uploadFileFor(event, targetObj, fieldName) {
  const files = event.target.files
  if (!files || files.length === 0) return

  // Client-side guard: maximum 500MB per file
  const maxSizeBytes = 500 * 1024 * 1024
  for (let i = 0; i < files.length; i++) {
    if (files[i].size > maxSizeBytes) {
      const mb = (files[i].size / (1024 * 1024)).toFixed(1)
      alert(`File "${files[i].name}" (${mb}MB) exceeds the 500MB upload limit. Please compress your video or enter a YouTube/external link instead.`)
      try { event.target.value = '' } catch (e) {}
      return
    }
  }

  uploadingFile.value = true
  try {
    if (files.length > 1 || fieldName === 'media_paths') {
      const formData = new FormData()
      for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i])
      }
      const res = await api.post('/admin/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        timeout: 600000 // 10 minutes for multi-file/video transfers
      })
      const uploadedPaths = (res.data.files || []).map(f => f.path)
      if (!Array.isArray(targetObj.media_paths)) {
        targetObj.media_paths = []
      }
      targetObj.media_paths.push(...uploadedPaths)
      if (!targetObj.media_path_or_url && uploadedPaths.length > 0) {
        targetObj.media_path_or_url = uploadedPaths[0]
      }
      showToast(`${uploadedPaths.length} file(s) uploaded successfully!`)
    } else {
      const file = files[0]
      const formData = new FormData()
      formData.append('file', file)
      const res = await api.post('/admin/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
        timeout: 600000 // 10 minutes for single video/large file
      })
      const path = res.data.path || res.data.url
      targetObj[fieldName] = path
      const isVideo = res.data.media_type === 'video' || /\.(mp4|webm|ogg|mov|mkv|avi|flv|wmv|3gp)$/i.test(path)
      if (isVideo && 'media_type' in targetObj) {
        targetObj.media_type = 'video'
      }
      if (fieldName === 'media_path_or_url' || fieldName === 'media_paths') {
        if (!Array.isArray(targetObj.media_paths)) targetObj.media_paths = []
        if (!targetObj.media_paths.includes(path)) {
          targetObj.media_paths.push(path)
        }
      }
      showToast(isVideo ? 'Video file uploaded successfully!' : 'File uploaded successfully!')
    }
  } catch (err) {
    let errorMsg = err.response?.data?.errors?.file?.[0] || 
                   err.response?.data?.message
    if (!errorMsg && typeof err.response?.data === 'string') {
      if (err.response.data.includes('POST data is too large') || err.response.data.includes('exceeds the limit')) {
        errorMsg = 'File exceeds the server upload limit (500MB). Please compress the video or enter an external video link.'
      }
    }
    if (!errorMsg) {
      if (err.code === 'ECONNABORTED') {
        errorMsg = 'The upload timed out. The file may be very large or your connection is slow. Please try a smaller video or enter a YouTube link.'
      } else {
        errorMsg = err.message || 'File upload failed. Please verify the file format and size.'
      }
    }
    alert(errorMsg)
  } finally {
    uploadingFile.value = false
    try { event.target.value = '' } catch (e) {}
  }
}

const tenderForm = reactive({
  reference_number: '', title_am: '', title_en: '', title_om: '',
  category: 'Works', bid_bond_amount: 50000, closing_datetime: '', status: 'open',
  document_pdf_path: ''
})

const vacancyForm = reactive({
  title_am: '', title_en: '', title_om: '', position_code: '',
  employment_type: 'Full-Time', salary_grade: 'PS-09', requirements_am: '', deadline: ''
})

const serviceForm = reactive({
  title_am: '', title_en: '', title_om: '', type: 'step',
  summary_am: '', summary_en: '', fee_amount: 50, estimated_time_am: '3 Business Days'
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

const emergencyContactForm = reactive({
  name_en: '',
  name_am: '',
  name_om: '',
  phone_number: '',
  icon_name: 'PhoneCall',
  badge_color: 'red',
  order: 0,
  is_active: 1
})

const hotlineIconMap = {
  ShieldAlert,
  Flame,
  Activity,
  Droplets,
  Zap,
  PhoneCall,
  Phone,
  Building2,
  ShieldCheck
}

function getHotlineIcon(name) {
  return hotlineIconMap[name] || PhoneCall
}

const hotlineColorClasses = {
  red: 'bg-red-50 text-red-700 border-red-200',
  orange: 'bg-orange-50 text-orange-700 border-orange-200',
  rose: 'bg-rose-50 text-rose-700 border-rose-200',
  blue: 'bg-blue-50 text-blue-700 border-blue-200',
  amber: 'bg-amber-50 text-amber-700 border-amber-200',
  emerald: 'bg-emerald-50 text-emerald-700 border-emerald-200',
  indigo: 'bg-indigo-50 text-indigo-700 border-indigo-200',
  purple: 'bg-purple-50 text-purple-700 border-purple-200'
}

function getHotlineBadgeClass(color) {
  return hotlineColorClasses[color] || hotlineColorClasses.red
}

const leadershipForm = reactive({
  type: 'cabinet', name_am: '', name_en: '', name_om: '', position_am: '', position_en: '', position_om: '', photo_path: '', phone: '', email: ''
})

const pollForm = reactive({
  question_en: '',
  question_am: '',
  question_om: '',
  description_en: '',
  description_am: '',
  description_om: '',
  expires_at: '',
  is_active: 1,
  options: [
    { option_text_en: '', option_text_am: '', option_text_om: '' },
    { option_text_en: '', option_text_am: '', option_text_om: '' }
  ]
})

function addPollOption() {
  pollForm.options.push({ option_text_en: '', option_text_am: '', option_text_om: '' })
}

function removePollOption(index) {
  if (pollForm.options.length <= 2) {
    alert('A civic poll must have at least 2 options.')
    return
  }
  pollForm.options.splice(index, 1)
}

function showToast(msg) {
  alertToast.value = msg
  setTimeout(() => { alertToast.value = '' }, 4000)
}

// Universal Confirmation Card Modal State
const confirmModal = reactive({
  open: false,
  title: 'Confirm Action',
  message: 'Are you sure you want to proceed?',
  itemName: '',
  confirmText: 'Confirm',
  cancelText: 'Cancel',
  variant: 'danger', // 'danger' | 'warning' | 'alert'
  loading: false,
  onConfirm: null
})

function openConfirmModal({
  title = 'Confirm Deletion',
  message = 'Are you sure you want to proceed? This action cannot be undone.',
  itemName = '',
  confirmText = 'Delete Permanently',
  cancelText = 'Cancel',
  variant = 'danger',
  action = null
}) {
  confirmModal.title = title
  confirmModal.message = message
  confirmModal.itemName = itemName
  confirmModal.confirmText = confirmText
  confirmModal.cancelText = cancelText
  confirmModal.variant = variant
  confirmModal.loading = false
  confirmModal.onConfirm = action
  confirmModal.open = true
}

function closeConfirmModal() {
  if (confirmModal.loading) return
  confirmModal.open = false
  confirmModal.onConfirm = null
}

async function handleConfirmModalAction() {
  if (typeof confirmModal.onConfirm === 'function') {
    confirmModal.loading = true
    try {
      await confirmModal.onConfirm()
      confirmModal.open = false
    } catch (err) {
      console.error(err)
    } finally {
      confirmModal.loading = false
    }
  } else {
    confirmModal.open = false
  }
}

// ==========================================
// HIGH-EFFICIENCY LAZY DATA FETCHERS (ON-DEMAND)
// ==========================================
const loadedSections = reactive(new Set())
const sectionLoading = ref(false)

async function fetchStats() {
  try {
    const res = await api.get('/admin/stats')
    stats.value = { ...stats.value, ...res.data }
    return res.data
  } catch (err) {
    console.error('Error fetching stats:', err)
    return {}
  }
}

async function fetchNews() {
  try {
    const res = await api.get('/admin/news')
    newsList.value = res.data?.data || res.data || []
  } catch (err) {
    console.error('Error fetching news:', err)
  }
}

async function fetchResources() {
  try {
    const res = await api.get('/admin/resources')
    resourcesList.value = res.data || []
  } catch (err) {
    console.error('Error fetching resources:', err)
  }
}

async function fetchGallery() {
  try {
    const res = await api.get('/admin/gallery')
    galleryList.value = res.data || []
  } catch (err) {
    console.error('Error fetching gallery:', err)
  }
}

async function fetchTenders() {
  try {
    const res = await api.get('/admin/tenders')
    tendersList.value = res.data || []
  } catch (err) {
    console.error('Error fetching tenders:', err)
  }
}

async function fetchVacancies() {
  try {
    const res = await api.get('/admin/vacancies')
    vacanciesList.value = res.data || []
  } catch (err) {
    console.error('Error fetching vacancies:', err)
  }
}

async function fetchProjects() {
  try {
    const res = await api.get('/admin/projects')
    projectsList.value = res.data || []
  } catch (err) {
    console.error('Error fetching projects:', err)
  }
}

async function fetchServices() {
  try {
    const res = await api.get('/admin/services')
    servicesList.value = res.data || []
  } catch (err) {
    console.error('Error fetching services:', err)
  }
}

async function fetchDestinations() {
  try {
    const res = await api.get('/admin/destinations')
    destinationsList.value = res.data || []
  } catch (err) {
    console.error('Error fetching destinations:', err)
  }
}

async function fetchOffices() {
  try {
    const res = await api.get('/admin/offices')
    officesList.value = res.data || []
    stats.value.offices_count = officesList.value.length
    stats.value.total_offices = officesList.value.length
  } catch (err) {
    console.error('Error fetching offices:', err)
  }
}

async function fetchMessages() {
  try {
    const res = await api.get('/admin/messages')
    messagesList.value = res.data?.data || res.data || []
    stats.value.unread_messages = messagesList.value.filter(m => m.status === 'unread').length
  } catch (err) {
    console.error('Error fetching messages:', err)
  }
}

async function fetchMayor() {
  try {
    const res = await api.get('/mayor')
    mayorData.value = res.data || {}
  } catch (err) {
    console.error('Error fetching mayor:', err)
  }
}

async function fetchLeadership() {
  try {
    const res = await api.get('/leadership')
    leadershipList.value = res.data || []
  } catch (err) {
    console.error('Error fetching leadership:', err)
  }
}

async function fetchHeroSlides() {
  try {
    const res = await api.get('/admin/hero-slides')
    heroSlidesList.value = res.data || []
  } catch (err) {
    console.error('Error fetching hero slides:', err)
  }
}

async function fetchEmergencyContacts() {
  try {
    const res = await api.get('/admin/emergency-contacts')
    emergencyContactsList.value = res.data || []
  } catch (err) {
    console.error('Error fetching emergency contacts:', err)
  }
}

async function fetchPolls() {
  try {
    const res = await api.get('/admin/polls')
    pollsList.value = res.data || []
  } catch (err) {
    console.error('Error fetching polls:', err)
  }
}

async function fetchSettings() {
  try {
    const res = await api.get('/admin/settings')
    if (res.data && Object.keys(res.data).length) {
      siteSettings.value = {
        ...siteSettings.value,
        ...res.data,
        is_maintenance: Boolean(res.data.is_maintenance)
      }
    }
  } catch (err) {
    console.error('Error fetching settings:', err)
  }
}

async function fetchStaff() {
  try {
    const res = await api.get('/admin/staff')
    staffList.value = res.data || []
  } catch (err) {
    console.error('Error fetching staff:', err)
  }
}

const sectionFetchers = {
  dashboard: fetchStats,
  hero_slides: fetchHeroSlides,
  gallery: fetchGallery,
  news: fetchNews,
  tenders: fetchTenders,
  vacancies: fetchVacancies,
  mayor: fetchMayor,
  leadership: fetchLeadership,
  offices: fetchOffices,
  services: fetchServices,
  projects: fetchProjects,
  resources: fetchResources,
  destinations: fetchDestinations,
  hotlines: fetchEmergencyContacts,
  messages: fetchMessages,
  polls: fetchPolls,
  staff: fetchStaff,
  audit: () => fetchAuditLogs(1),
  settings: fetchSettings
}

async function fetchSectionData(section, forceRefresh = false) {
  if (!forceRefresh && loadedSections.has(section)) {
    return
  }
  const fetcher = sectionFetchers[section]
  if (fetcher) {
    sectionLoading.value = true
    try {
      await fetcher()
      loadedSections.add(section)
    } catch (err) {
      console.error(`Error loading section ${section}:`, err)
    } finally {
      sectionLoading.value = false
    }
  }
}

// Master refresh helper: refreshes stats and currently active section
async function fetchAllAdminData(forceRefresh = true) {
  loading.value = true
  try {
    await Promise.all([
      fetchStats(),
      fetchSectionData(activeSection.value, forceRefresh)
    ])
  } finally {
    loading.value = false
  }
}

async function toggleHotlineStatus(contact) {
  try {
    const newStatus = contact.is_active ? 0 : 1
    await api.put(`/admin/emergency-contacts/${contact.id}`, {
      ...contact,
      is_active: newStatus
    })
    contact.is_active = newStatus
    showToast(`Hotline "${contact.name_en}" ${newStatus ? 'activated on live portal' : 'deactivated'}.`)
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to toggle hotline status.')
  }
}

async function togglePollActive(poll) {
  try {
    const res = await api.patch(`/admin/polls/${poll.id}/toggle-active`)
    showToast(res.data?.message || 'Poll status toggled!')
    await Promise.all([
      fetchStats(),
      fetchSectionData('polls', true)
    ])
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to toggle poll status.')
  }
}

async function resetPollVotes(poll) {
  const pollTitle = poll.question_en || poll.question_am || 'Civic Poll'
  openConfirmModal({
    title: adminLang.value === 'am' ? 'የድምፅ መስጫ ውጤት አጽዳ' : 'Reset All Poll Ballots',
    message: adminLang.value === 'am'
      ? 'ይህ ሁሉንም የቀደሙ እና የሙከራ ድምጾች በማፅዳት ዜጎች በእውነተኛ ድምጾች ድጋሚ እንዲመርጡ ያደርጋል።'
      : 'This will permanently clear all demo and previous votes so citizens can begin voting on real verified ballots.',
    itemName: pollTitle,
    confirmText: adminLang.value === 'am' ? 'ድምጾችን አጽዳ' : 'Reset Ballots',
    cancelText: adminLang.value === 'am' ? 'ተወው' : 'Cancel',
    variant: 'warning',
    action: async () => {
      try {
        const res = await api.post(`/admin/polls/${poll.id}/reset-votes`)
        showToast(res.data?.message || 'Poll votes reset successfully! Ready for real data.')
        await Promise.all([
          fetchStats(),
          fetchSectionData('polls', true)
        ])
      } catch (err) {
        showToast(err.response?.data?.message || 'Failed to reset poll votes.')
      }
    }
  })
}

const editingId = ref(null)

function openNewModal(type) {
  editingId.value = null
  modalType.value = type
  if (type === 'news') {
    newsForm.title_am = ''
    newsForm.title_en = ''
    newsForm.title_om = ''
    newsForm.category = 'General'
    newsForm.content_am = ''
    newsForm.content_en = ''
    newsForm.content_om = ''
    newsForm.status = 'published'
    newsForm.thumbnail_path = ''
  } else if (type === 'tender') {
    tenderForm.reference_number = ''
    tenderForm.title_am = ''
    tenderForm.title_en = ''
    tenderForm.title_om = ''
    tenderForm.category = 'Works'
    tenderForm.bid_bond_amount = 50000
    tenderForm.closing_datetime = ''
    tenderForm.status = 'open'
    tenderForm.document_pdf_path = ''
  } else if (type === 'vacancy') {
    vacancyForm.title_am = ''
    vacancyForm.title_en = ''
    vacancyForm.title_om = ''
    vacancyForm.position_code = ''
    vacancyForm.employment_type = 'Full-Time'
    vacancyForm.salary_grade = 'PS-09'
    vacancyForm.requirements_am = ''
    vacancyForm.deadline = ''
  } else if (type === 'service') {
    serviceForm.title_am = ''
    serviceForm.title_en = ''
    serviceForm.title_om = ''
    serviceForm.type = 'step'
    serviceForm.summary_am = ''
    serviceForm.summary_en = ''
    serviceForm.fee_amount = 50
    serviceForm.estimated_time_am = '3 Business Days'
  } else if (type === 'project') {
    projectForm.title_am = ''
    projectForm.title_en = ''
    projectForm.title_om = ''
    projectForm.budget_etb = 10000000
    projectForm.contractor_name = ''
    projectForm.progress_percent = 10
    projectForm.status = 'in_progress'
  } else if (type === 'resource') {
    resourceForm.title_am = ''
    resourceForm.title_en = ''
    resourceForm.title_om = ''
    resourceForm.category = 'Directives'
    resourceForm.year = 2026
    resourceForm.file_path = ''
    resourceForm.file_size_formatted = '2.4 MB'
  } else if (type === 'office') {
    officeForm.name_am = ''
    officeForm.name_en = ''
    officeForm.name_om = ''
    officeForm.phone = ''
    officeForm.email = ''
    officeForm.building_location_am = ''
    officeForm.room_number = ''
  } else if (type === 'leadership') {
    leadershipForm.type = 'cabinet'
    leadershipForm.name_am = ''
    leadershipForm.name_en = ''
    leadershipForm.name_om = ''
    leadershipForm.position_am = ''
    leadershipForm.position_en = ''
    leadershipForm.position_om = ''
    leadershipForm.photo_path = ''
    leadershipForm.phone = ''
    leadershipForm.email = ''
  } else if (type === 'gallery') {
    galleryForm.media_type = 'image'
    galleryForm.category = 'urban'
    galleryForm.media_path_or_url = ''
    galleryForm.media_paths = []
    galleryForm.thumbnail_url = ''
    galleryForm.caption_am = ''
    galleryForm.caption_en = ''
    galleryForm.caption_om = ''
    galleryForm.order = 0
  } else if (type === 'destination') {
    destinationForm.category = 'nature'
    destinationForm.title_am = ''
    destinationForm.title_en = ''
    destinationForm.title_om = ''
    destinationForm.location_am = ''
    destinationForm.location_en = ''
    destinationForm.location_om = ''
    destinationForm.summary_am = ''
    destinationForm.summary_en = ''
    destinationForm.summary_om = ''
    destinationForm.description_am = ''
    destinationForm.description_en = ''
    destinationForm.description_om = ''
    destinationForm.visiting_hours = ''
    destinationForm.contact_phone = ''
    destinationForm.contact_email = ''
    destinationForm.admission_fee = ''
    destinationForm.features = ''
    destinationForm.image_path = ''
    destinationForm.order = 0
  } else if (type === 'emergency_contact') {
    emergencyContactForm.name_en = ''
    emergencyContactForm.name_am = ''
    emergencyContactForm.name_om = ''
    emergencyContactForm.phone_number = ''
    emergencyContactForm.icon_name = 'PhoneCall'
    emergencyContactForm.badge_color = 'red'
    emergencyContactForm.order = emergencyContactsList.value.length + 1
    emergencyContactForm.is_active = 1
  } else if (type === 'poll') {
    pollForm.question_en = ''
    pollForm.question_am = ''
    pollForm.question_om = ''
    pollForm.description_en = ''
    pollForm.description_am = ''
    pollForm.description_om = ''
    pollForm.expires_at = ''
    pollForm.is_active = 1
    pollForm.options = [
      { option_text_en: '', option_text_am: '', option_text_om: '' },
      { option_text_en: '', option_text_am: '', option_text_om: '' }
    ]
  }
}

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
    newsForm.thumbnail_path = item.thumbnail_path || ''
  } else if (type === 'tender') {
    tenderForm.reference_number = item.reference_number || ''
    tenderForm.title_am = item.title_am || ''
    tenderForm.title_en = item.title_en || ''
    tenderForm.title_om = item.title_om || ''
    tenderForm.category = item.category || 'Goods'
    tenderForm.bid_bond_amount = item.bid_bond_amount || 0
    tenderForm.closing_datetime = item.closing_datetime ? item.closing_datetime.replace(' ', 'T').substring(0, 16) : ''
    tenderForm.status = item.status || 'open'
    tenderForm.document_pdf_path = item.document_pdf_path || ''
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
    serviceForm.summary_en = item.summary_en || ''
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
    galleryForm.media_type = 'image'
    galleryForm.category = item.category || 'urban'
    galleryForm.media_path_or_url = item.media_path_or_url || ''
    galleryForm.media_paths = item.media_path_or_url ? [item.media_path_or_url] : []
    galleryForm.thumbnail_url = item.thumbnail_url || item.media_path_or_url || ''
    galleryForm.caption_am = item.caption_am || ''
    galleryForm.caption_en = item.caption_en || ''
    galleryForm.caption_om = item.caption_om || ''
    galleryForm.order = item.order || 0
  } else if (type === 'destination') {
    destinationForm.category = item.category || 'nature'
    destinationForm.title_am = item.title_am || ''
    destinationForm.title_en = item.title_en || ''
    destinationForm.title_om = item.title_om || ''
    destinationForm.location_am = item.location_am || ''
    destinationForm.location_en = item.location_en || ''
    destinationForm.location_om = item.location_om || ''
    destinationForm.summary_am = item.summary_am || ''
    destinationForm.summary_en = item.summary_en || ''
    destinationForm.summary_om = item.summary_om || ''
    destinationForm.description_am = item.description_am || ''
    destinationForm.description_en = item.description_en || ''
    destinationForm.description_om = item.description_om || ''
    destinationForm.visiting_hours = item.visiting_hours || ''
    destinationForm.contact_phone = item.contact_phone || ''
    destinationForm.contact_email = item.contact_email || ''
    destinationForm.admission_fee = item.admission_fee || ''
    destinationForm.features = item.features || ''
    destinationForm.image_path = item.image_path || ''
    destinationForm.order = item.order || 0
  } else if (type === 'emergency_contact') {
    emergencyContactForm.name_en = item.name_en || ''
    emergencyContactForm.name_am = item.name_am || ''
    emergencyContactForm.name_om = item.name_om || ''
    emergencyContactForm.phone_number = item.phone_number || ''
    emergencyContactForm.icon_name = item.icon_name || 'PhoneCall'
    emergencyContactForm.badge_color = item.badge_color || 'red'
    emergencyContactForm.order = item.order || 0
    emergencyContactForm.is_active = item.is_active !== undefined ? (item.is_active ? 1 : 0) : 1
  } else if (type === 'poll') {
    pollForm.question_en = item.question_en || ''
    pollForm.question_am = item.question_am || ''
    pollForm.question_om = item.question_om || ''
    pollForm.description_en = item.description_en || ''
    pollForm.description_am = item.description_am || ''
    pollForm.description_om = item.description_om || ''
    pollForm.expires_at = item.expires_at ? item.expires_at.substring(0, 10) : ''
    pollForm.is_active = item.is_active !== undefined ? (item.is_active ? 1 : 0) : 1
    if (item.options && item.options.length) {
      pollForm.options = item.options.map(opt => ({
        id: opt.id,
        option_text_en: opt.option_text_en || '',
        option_text_am: opt.option_text_am || '',
        option_text_om: opt.option_text_om || '',
        votes_count: opt.votes_count || 0
      }))
    } else {
      pollForm.options = [
        { option_text_en: '', option_text_am: '', option_text_om: '' },
        { option_text_en: '', option_text_am: '', option_text_om: '' }
      ]
    }
  }
}

// Generic Submit Handler for Create & Edit
async function submitActiveModal() {
  submitting.value = true
  try {
    const isEdit = !!editingId.value
    const id = editingId.value
    if (modalType.value === 'news') {
      if (isAuthor.value && !isEditor.value) {
        newsForm.status = 'pending' // Route to editorial review
      }
      if (isEdit) {
        const res = await api.put(`/admin/news/${id}`, newsForm)
        showToast(res.data?.message || 'Article updated successfully!')
      } else {
        const res = await api.post('/admin/news', newsForm)
        showToast(res.data?.message || (isAuthor.value ? 'Article submitted for editorial approval!' : 'News article published!'))
      }
    } else if (modalType.value === 'tender') {
      if (isAuthor.value && !isEditor.value) {
        tenderForm.status = 'pending'
      }
      if (isEdit) await api.put(`/admin/tenders/${id}`, tenderForm)
      else await api.post('/admin/tenders', tenderForm)
      showToast(isEdit ? 'Tender updated successfully!' : (isAuthor.value ? 'Tender submitted for editorial review!' : 'Procurement tender published!'))
    } else if (modalType.value === 'vacancy') {
      if (isAuthor.value && !isEditor.value) {
        vacancyForm.status = 'pending'
      }
      if (isEdit) await api.put(`/admin/vacancies/${id}`, vacancyForm)
      else await api.post('/admin/vacancies', vacancyForm)
      showToast(isEdit ? 'Vacancy updated successfully!' : (isAuthor.value ? 'Vacancy submitted for editorial review!' : 'Civil service vacancy posted!'))
    } else if (modalType.value === 'service') {
      if (isAuthor.value && !isEditor.value) {
        serviceForm.status = 'pending'
      }
      if (isEdit) await api.put(`/admin/services/${id}`, serviceForm)
      else await api.post('/admin/services', serviceForm)
      showToast(isEdit ? 'Service updated successfully!' : (isAuthor.value ? 'Service submitted for editorial review!' : 'Municipal service registered!'))
    } else if (modalType.value === 'project') {
      if (isAuthor.value && !isEditor.value) {
        projectForm.publication_status = 'pending'
      }
      if (isEdit) await api.put(`/admin/projects/${id}`, projectForm)
      else await api.post('/admin/projects', projectForm)
      showToast(isEdit ? 'Project updated successfully!' : (isAuthor.value ? 'Project submitted for editorial review!' : 'Mega infrastructure project cataloged!'))
    } else if (modalType.value === 'resource') {
      if (isAuthor.value && !isEditor.value) {
        resourceForm.status = 'pending'
      }
      if (isEdit) await api.put(`/admin/resources/${id}`, resourceForm)
      else await api.post('/admin/resources', resourceForm)
      showToast(isEdit ? 'Directive updated successfully!' : (isAuthor.value ? 'Directive submitted for editorial review!' : 'Official directive document uploaded!'))
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
      if (!galleryForm.media_type) {
        const isVid = /\.(mp4|webm|ogg|mov|mkv|avi)$/i.test(galleryForm.media_path_or_url || '') ||
                      /youtube\.com|youtu\.be|vimeo\.com/i.test(galleryForm.media_path_or_url || '')
        galleryForm.media_type = isVid ? 'video' : 'image'
      }
      if (isAuthor.value && !isEditor.value) {
        galleryForm.status = 'pending'
      }
      if (!isEdit && (!galleryForm.media_paths || galleryForm.media_paths.length === 0) && galleryForm.media_path_or_url) {
        galleryForm.media_paths = [galleryForm.media_path_or_url]
      }
      if (galleryForm.media_paths && galleryForm.media_paths.length > 0 && !galleryForm.media_path_or_url) {
        galleryForm.media_path_or_url = galleryForm.media_paths[0]
      }
      if (isEdit) {
        await api.put(`/admin/gallery/${id}`, galleryForm)
        showToast('Gallery item updated successfully!')
      } else {
        const res = await api.post('/admin/gallery', galleryForm)
        const count = res.data?.count || 1
        showToast(isAuthor.value 
          ? (count > 1 ? `${count} items submitted for editorial review!` : 'Item submitted for editorial review!') 
          : (count > 1 ? `${count} items added to gallery successfully!` : 'Gallery item added successfully!'))
      }
      await fetchGallery()
    } else if (modalType.value === 'destination') {
      if (isAuthor.value && !isEditor.value) {
        destinationForm.status = 'pending'
      }
      if (isEdit) await api.put(`/admin/destinations/${id}`, destinationForm)
      else await api.post('/admin/destinations', destinationForm)
      showToast(isEdit ? 'Destination spotlight updated!' : (isAuthor.value ? 'Destination submitted for editorial review!' : 'New destination spotlight created!'))
    } else if (modalType.value === 'emergency_contact') {
      if (isEdit) await api.put(`/admin/emergency-contacts/${id}`, emergencyContactForm)
      else await api.post('/admin/emergency-contacts', emergencyContactForm)
      showToast(isEdit ? 'Emergency hotline updated successfully!' : 'Emergency hotline added successfully!')
    } else if (modalType.value === 'poll') {
      if (isEdit) await api.put(`/admin/polls/${id}`, pollForm)
      else await api.post('/admin/polls', pollForm)
      showToast(isEdit ? 'Civic poll updated successfully!' : 'Civic poll published live!')
    }
    const modalTypeToSection = {
      news: 'news',
      tender: 'tenders',
      vacancy: 'vacancies',
      service: 'services',
      project: 'projects',
      resource: 'resources',
      staff: 'staff',
      office: 'offices',
      leadership: 'leadership',
      hero_slide: 'hero_slides',
      gallery: 'gallery',
      destination: 'destinations',
      emergency_contact: 'hotlines',
      poll: 'polls'
    }
    const savedType = modalType.value
    modalType.value = ''
    editingId.value = null
    const targetSec = modalTypeToSection[savedType] || activeSection.value
    await Promise.all([
      fetchStats(),
      fetchSectionData(targetSec, true)
    ])
  } catch (err) {
    alert(err.response?.data?.message || 'Error executing admin action.')
  } finally {
    submitting.value = false
  }
}

// Delete Helpers with automatic live refresh
async function deleteItem(endpoint, id, name) {
  if (['/admin/offices', '/admin/leadership'].some(ep => endpoint.startsWith(ep)) && !isSuperAdmin.value) {
    showToast('Permission Denied: Only Super Administrators can delete municipal offices or leadership profiles.')
    return
  }
  openConfirmModal({
    title: adminLang.value === 'am' ? 'ማረጋገጫ ይሰርዙ' : 'Confirm Permanent Deletion',
    message: adminLang.value === 'am'
      ? 'ይህ እርምጃ ይህንን መረጃ ከዳታቤዝ እና ከህዝብ ፖርታል ሙሉ በሙሉ እስከመጨረሻው ያጠፋዋል።'
      : 'Are you sure you want to delete this record? This action removes it permanently from the database and public portal.',
    itemName: name || 'Selected Item',
    confirmText: adminLang.value === 'am' ? 'በቋሚነት ሰርዝ' : 'Yes, Delete Record',
    cancelText: adminLang.value === 'am' ? 'ተወው' : 'Cancel',
    variant: 'danger',
    action: async () => {
      try {
        await api.delete(`${endpoint}/${id}`)
        showToast(`"${name}" deleted successfully.`)
        await Promise.all([
          fetchStats(),
          fetchSectionData(activeSection.value, true)
        ])
      } catch (err) {
        showToast(err.response?.data?.message || 'Failed to delete item.')
      }
    }
  })
}

// Editorial Governance & Approval Pipeline Actions
async function approveAndPublishItem(moduleType, item) {
  try {
    const targetStatus = (moduleType === 'tender' || moduleType === 'vacancy') ? 'open' : 'published'
    const res = await api.patch(`/admin/content-status/${moduleType}/${item.id}`, { status: targetStatus })
    item.status = targetStatus
    if (moduleType === 'project') item.publication_status = targetStatus
    if (moduleType === 'service' || moduleType === 'destination') item.is_active = 1
    showToast(res.data?.message || 'Item approved and published live!')
    await Promise.all([
      fetchStats(),
      fetchSectionData(activeSection.value, true)
    ])
  } catch (err) {
    showToast(err.response?.data?.message || 'Failed to approve item.')
  }
}

async function unpublishItem(moduleType, item) {
  try {
    const res = await api.patch(`/admin/content-status/${moduleType}/${item.id}`, { status: 'draft' })
    item.status = 'draft'
    if (moduleType === 'project') item.publication_status = 'draft'
    if (moduleType === 'service' || moduleType === 'destination') item.is_active = 0
    showToast(res.data?.message || 'Item unpublished and moved back to draft.')
    await Promise.all([
      fetchStats(),
      fetchSectionData(activeSection.value, true)
    ])
  } catch (err) {
    showToast(err.response?.data?.message || 'Failed to unpublish item.')
  }
}

async function approveAndPublishNews(item) {
  return approveAndPublishItem('news', item)
}

async function unpublishNews(item) {
  return unpublishItem('news', item)
}

async function submitNewsForReview(item) {
  try {
    const res = await api.put(`/admin/news/${item.id}`, { ...item, status: 'pending' })
    item.status = 'pending'
    showToast(res.data?.message || `"${item.title_en || item.title_am}" submitted for editorial review.`)
    await Promise.all([
      fetchStats(),
      fetchSectionData('news', true)
    ])
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to submit article for review.')
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

const detectingLocation = ref(false)
const detectLiveLocation = () => {
  if (!navigator.geolocation) {
    showToast('Geolocation is not supported by your browser.')
    return
  }
  detectingLocation.value = true
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      siteSettings.value.latitude = Number(pos.coords.latitude.toFixed(7))
      siteSettings.value.longitude = Number(pos.coords.longitude.toFixed(7))
      detectingLocation.value = false
      showToast(`GPS Coordinates Detected: Lat ${siteSettings.value.latitude}, Lng ${siteSettings.value.longitude}`)
    },
    (err) => {
      detectingLocation.value = false
      showToast('Could not retrieve live GPS coordinates: ' + err.message)
    },
    { enableHighAccuracy: true, timeout: 10000 }
  )
}

async function updateSettings() {
  try {
    const isMaint = Boolean(siteSettings.value.is_maintenance)
    const res = await api.post('/admin/settings', {
      site_name_en: siteSettings.value.site_name_en,
      site_name_am: siteSettings.value.site_name_am,
      site_name_om: siteSettings.value.site_name_om,
      contact_phone: siteSettings.value.contact_phone,
      contact_email: siteSettings.value.contact_email,
      emergency_notice_en: siteSettings.value.emergency_notice_en,
      emergency_notice_am: siteSettings.value.emergency_notice_am,
      emergency_notice_om: siteSettings.value.emergency_notice_om,
      latitude: Number(siteSettings.value.latitude) || 11.0845,
      longitude: Number(siteSettings.value.longitude) || 39.7390,
      map_address_en: siteSettings.value.map_address_en,
      map_address_am: siteSettings.value.map_address_am,
      map_address_om: siteSettings.value.map_address_om,
      map_zoom: Number(siteSettings.value.map_zoom) || 15,
      is_maintenance: isMaint
    })
    if (res.data?.settings) {
      siteSettings.value = {
        ...siteSettings.value,
        ...res.data.settings,
        is_maintenance: Boolean(res.data.settings.is_maintenance)
      }
    }
    showToast('Municipal settings & geo-location coordinates saved successfully!')
  } catch (err) {
    console.error('Settings update error:', err)
    const msg = err.response?.data?.message || 'Error updating settings.'
    alert(msg)
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

// Profile & Self-Security Management (Authors, Editors, Super Admins)
const profileModalOpen = ref(false)
const profileActiveTab = ref('details') // 'details' | 'password'
const profileLoading = ref(false)
const profileForm = reactive({
  name: '',
  email: '',
  phone: '',
  avatar_path: '',
})
const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
})

function openProfileModal() {
  profileForm.name = currentUser.value?.name || ''
  profileForm.email = currentUser.value?.email || ''
  profileForm.phone = currentUser.value?.phone || ''
  profileForm.avatar_path = currentUser.value?.avatar_path || ''
  passwordForm.current_password = ''
  passwordForm.new_password = ''
  passwordForm.new_password_confirmation = ''
  profileActiveTab.value = 'details'
  profileModalOpen.value = true
}

async function handleSaveProfile() {
  profileLoading.value = true
  try {
    const res = await api.put('/admin/profile', profileForm)
    currentUser.value = { ...currentUser.value, ...res.data.user }
    localStorage.setItem('kombolcha_admin_user', JSON.stringify(currentUser.value))
    showToast('Profile information updated successfully!')
    profileModalOpen.value = false
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to update profile.')
  } finally {
    profileLoading.value = false
  }
}

async function handleChangePassword() {
  if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
    alert('New password and confirmation do not match.')
    return
  }
  profileLoading.value = true
  try {
    const res = await api.put('/admin/password', passwordForm)
    showToast(res.data.message || 'Password updated successfully!')
    passwordForm.current_password = ''
    passwordForm.new_password = ''
    passwordForm.new_password_confirmation = ''
    profileModalOpen.value = false
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to update password.')
  } finally {
    profileLoading.value = false
  }
}

// Staff Password Reset Management (Super Admin Override)
const resetStaffModalOpen = ref(false)
const targetStaffUser = ref(null)
const resetStaffForm = reactive({
  password: '',
  password_confirmation: '',
})
const resetStaffLoading = ref(false)

function openResetStaffPasswordModal(user) {
  targetStaffUser.value = user
  resetStaffForm.password = ''
  resetStaffForm.password_confirmation = ''
  resetStaffModalOpen.value = true
}

function generateRandomPassword() {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%&*'
  let pw = 'Kmb@'
  for (let i = 0; i < 8; i++) {
    pw += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  resetStaffForm.password = pw
  resetStaffForm.password_confirmation = pw
}

async function handleAdminResetPassword() {
  if (!targetStaffUser.value) return
  if (resetStaffForm.password !== resetStaffForm.password_confirmation) {
    alert('Password and confirmation do not match.')
    return
  }
  resetStaffLoading.value = true
  try {
    const res = await api.patch(`/admin/staff/${targetStaffUser.value.id}/reset-password`, resetStaffForm)
    showToast(res.data.message || `Password for ${targetStaffUser.value.name} reset successfully!`)
    resetStaffModalOpen.value = false
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to reset staff password.')
  } finally {
    resetStaffLoading.value = false
  }
}

async function logout() {
  try { await api.post('/admin/logout') } catch (e) {}
  localStorage.removeItem('kombolcha_admin_token')
  localStorage.removeItem('kombolcha_admin_user')
  router.replace('/admin/login')
}

// Notification Dropdown State & Actions
const notificationDropdownOpen = ref(false)

function toggleNotificationDropdown() {
  notificationDropdownOpen.value = !notificationDropdownOpen.value
  if (notificationDropdownOpen.value && messagesList.value.length === 0) {
    fetchMessages().catch(() => {})
  }
}

function handleNotificationClick(msg) {
  notificationDropdownOpen.value = false
  if (msg.status === 'unread') {
    msg.status = 'read'
    api.patch(`/admin/messages/${msg.id}`, { status: 'read' }).catch(() => {})
    stats.value.unread_messages = Math.max(0, (stats.value.unread_messages || 1) - 1)
  }

  // Detect whether this notification is an Author Editorial Submission
  let isEditorial = false
  let targetModule = 'messages'

  if (msg.target_sub_city && msg.target_sub_city.startsWith('Editorial:')) {
    isEditorial = true
    const parsed = msg.target_sub_city.replace('Editorial:', '').trim().toLowerCase()
    const map = {
      news: 'news',
      tender: 'tenders',
      tenders: 'tenders',
      vacancy: 'vacancies',
      vacancies: 'vacancies',
      service: 'services',
      services: 'services',
      project: 'projects',
      projects: 'projects',
      resource: 'resources',
      resources: 'resources',
      gallery: 'gallery',
      destination: 'destinations',
      destinations: 'destinations'
    }
    targetModule = map[parsed] || parsed
  } else if (msg.admin_notes) {
    try {
      const meta = typeof msg.admin_notes === 'string' ? JSON.parse(msg.admin_notes) : msg.admin_notes
      if (meta && meta.type === 'editorial_submission') {
        isEditorial = true
        const map = {
          news: 'news',
          tender: 'tenders',
          tenders: 'tenders',
          vacancy: 'vacancies',
          vacancies: 'vacancies',
          service: 'services',
          services: 'services',
          project: 'projects',
          projects: 'projects',
          resource: 'resources',
          resources: 'resources',
          gallery: 'gallery',
          destination: 'destinations',
          destinations: 'destinations'
        }
        targetModule = map[meta.module] || meta.module
      }
    } catch (e) {}
  }

  if (isEditorial && targetModule) {
    activeSection.value = targetModule
    fetchSectionData(targetModule, true)
    showToast(`Navigated to ${targetModule.toUpperCase()} for editorial review & approval`)
  } else {
    activeSection.value = 'messages'
  }
}

function navigateToEditorialModule(mod) {
  const map = {
    news: 'news',
    tender: 'tenders',
    tenders: 'tenders',
    vacancy: 'vacancies',
    vacancies: 'vacancies',
    service: 'services',
    services: 'services',
    project: 'projects',
    projects: 'projects',
    resource: 'resources',
    resources: 'resources',
    gallery: 'gallery',
    destination: 'destinations',
    destinations: 'destinations'
  }
  const target = map[mod] || mod
  activeSection.value = target
  fetchSectionData(target, true)
  showToast(`Navigated to ${target.toUpperCase()} review`)
}

async function markAllInquiriesRead() {
  const unread = messagesList.value.filter(m => m.status === 'unread')
  for (const m of unread) {
    m.status = 'read'
    api.patch(`/admin/messages/${m.id}`, { status: 'read' }).catch(() => {})
  }
  stats.value.unread_messages = 0
  showToast('All notifications marked as read')
}

function handleGlobalClick(e) {
  if (!e.target.closest('.notification-menu-container')) {
    notificationDropdownOpen.value = false
  }
  if (!e.target.closest('.search-palette-container')) {
    adminSearchFocused.value = false
  }
}

onMounted(async () => {
  const token = localStorage.getItem('kombolcha_admin_token')
  if (!token) {
    router.replace('/admin/login')
    return
  }
  window.addEventListener('click', handleGlobalClick)
  window.addEventListener('keydown', handleGlobalKeydown)

  // Fast initial page load: only stats and the active section (Overview)
  loading.value = true
  try {
    await Promise.all([
      fetchStats(),
      fetchSectionData(activeSection.value)
    ])
  } finally {
    loading.value = false
  }

  // Non-blocking background prefetch for sidebar notification counts
  setTimeout(() => {
    if (isEditor.value) {
      if (!loadedSections.has('hotlines')) fetchEmergencyContacts().catch(() => {})
      if (!loadedSections.has('polls')) fetchPolls().catch(() => {})
    }
  }, 1200)
})

onUnmounted(() => {
  window.removeEventListener('click', handleGlobalClick)
  window.removeEventListener('keydown', handleGlobalKeydown)
})
</script>

<template>
  <div class="h-screen flex flex-col overflow-hidden bg-slate-100 font-sans text-slate-800">
    <!-- Top Sovereign Command Bar -->
    <header class="bg-[#0B1E36] border-b border-[#102A4C] text-white px-3 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between shrink-0 z-30 shadow-xl">
      <!-- Left: Mobile Menu Toggle, Logo & Portal Title -->
      <div class="flex items-center gap-2 sm:gap-3 min-w-0">
        <!-- Mobile Sidebar Hamburger Toggle -->
        <button 
          @click="mobileSidebarOpen = !mobileSidebarOpen" 
          class="lg:hidden p-1.5 rounded-lg bg-white/10 hover:bg-white/15 text-slate-300 hover:text-white cursor-pointer shrink-0 transition-colors"
          aria-label="Toggle Navigation Drawer"
        >
          <Menu class="w-5 h-5" />
        </button>

        <div class="relative p-0.5 rounded-full bg-gradient-to-r from-amber-400 via-amber-200 to-amber-500 shadow-md shrink-0">
          <img src="/assets/logo.png" alt="Kombolcha City Seal" class="w-8 h-8 sm:w-9 sm:h-9 object-contain rounded-full bg-white p-0.5" />
        </div>
        <div class="min-w-0">
          <h1 class="text-xs sm:text-sm font-black tracking-tight text-white flex items-center gap-1.5 sm:gap-2 truncate">
            <span class="truncate">{{ adminT('portal_title') }}</span>
          </h1>
          <p class="hidden sm:block text-[10px] text-slate-400 truncate">{{ adminT('portal_sub') }}</p>
        </div>
      </div>

      <!-- Center: Admin Search Bar & Command Palette -->
      <div class="hidden md:block relative w-72 lg:w-96 mx-4 search-palette-container">
        <div class="relative">
          <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input 
            ref="adminSearchInputRef"
            v-model="adminSearch" 
            type="text" 
            @focus="adminSearchFocused = true"
            @keydown.esc="adminSearch = ''; adminSearchFocused = false"
            :placeholder="adminT('search_placeholder')" 
            class="w-full bg-white/5 border border-white/10 hover:border-white/20 focus:bg-white/10 rounded-xl pl-9 pr-14 py-2 text-xs text-white focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500/50 placeholder-slate-400 transition-all"
          />
          <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
            <button 
              v-if="adminSearch" 
              type="button"
              @click="adminSearch = ''; adminSearchFocused = false" 
              class="p-1 rounded-md text-slate-400 hover:text-white hover:bg-white/10 cursor-pointer"
              title="Clear search"
            >
              <X class="w-3.5 h-3.5" />
            </button>
            <span v-else class="text-[10px] font-mono text-slate-400 bg-white/10 px-1.5 py-0.5 rounded pointer-events-none">⌘K</span>
          </div>
        </div>

        <!-- Dynamic Live Search Results Overlay Dropdown -->
        <div 
          v-if="adminSearchFocused && adminSearch.trim().length > 0" 
          class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden text-slate-800 animate-fadeIn"
          style="max-height: 480px; display: flex; flex-direction: column;"
        >
          <!-- Results Header Strip -->
          <div class="p-3 px-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-xs shrink-0">
            <span class="font-bold text-slate-600">
              Found <strong class="text-blue-900 font-mono">{{ adminSearchResults.length }}</strong> match{{ adminSearchResults.length === 1 ? '' : 'es' }}
            </span>
            <span class="text-[10px] text-slate-400 font-mono">Press ESC to close</span>
          </div>

          <!-- Results Scroll Area -->
          <div class="overflow-y-auto divide-y divide-slate-100 flex-1">
            <div 
              v-if="adminSearchResults.length === 0" 
              class="p-8 text-center text-slate-400 text-xs"
            >
              <Search class="w-8 h-8 mx-auto mb-2 opacity-30 text-slate-400" />
              <p class="font-bold text-slate-600">No matching records found</p>
              <p class="text-[11px] text-slate-400 mt-0.5">Try searching for keywords like news title, tender reference, vacancy code, or citizen names.</p>
            </div>

            <div 
              v-for="item in adminSearchResults" 
              :key="item.id"
              @click="handleSearchResultClick(item)"
              class="p-3 px-4 hover:bg-blue-50/60 transition-colors cursor-pointer flex items-center gap-3 group text-left"
            >
              <div 
                class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-bold"
                :class="getSearchResultBadge(item.type).bg"
              >
                <component :is="getSearchResultBadge(item.type).icon || Search" class="w-4 h-4" />
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                  <span 
                    class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-wider"
                    :class="getSearchResultBadge(item.type).pill"
                  >
                    {{ getSearchResultBadge(item.type).label }}
                  </span>
                  <span class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition-colors truncate">
                    {{ item.title }}
                  </span>
                </div>
                <p class="text-[11px] text-slate-500 truncate font-mono">
                  {{ item.sub }}
                </p>
              </div>
              <ChevronRight class="w-4 h-4 text-slate-300 group-hover:text-blue-600 transition-colors shrink-0" />
            </div>
          </div>
        </div>
      </div>

      <!-- Right Actions: Language, Notifications, Profile -->
      <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        <!-- Live Public Site Shortcut -->
        <router-link to="/" target="_blank" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-white text-xs font-bold border border-white/10 transition-colors">
          <Globe class="w-3.5 h-3.5 text-amber-400" />
          <span>{{ adminT('live_site') }}</span>
          <ExternalLink class="w-3 h-3 text-slate-400" />
        </router-link>

        <!-- Admin Language Toggle [AM | EN] -->
        <div class="flex items-center bg-white/10 rounded-xl p-0.5 sm:p-1 border border-white/10 text-xs font-bold">
          <button 
            @click="switchAdminLang('en')" 
            :class="adminLang === 'en' ? 'bg-amber-500 text-slate-950 font-black px-1.5 sm:px-2 py-0.5 rounded-lg shadow-xs' : 'text-slate-300 px-1.5 sm:px-2 py-0.5 cursor-pointer hover:text-white'"
          >
            EN
          </button>
          <button 
            @click="switchAdminLang('am')" 
            :class="adminLang === 'am' ? 'bg-amber-500 text-slate-950 font-black px-1.5 sm:px-2 py-0.5 rounded-lg shadow-xs' : 'text-slate-300 px-1.5 sm:px-2 py-0.5 cursor-pointer hover:text-white'"
          >
            አማ
          </button>
        </div>

        <!-- Notifications Bell & Interactive Dropdown Menu -->
        <div class="relative notification-menu-container">
          <button 
            @click.stop="toggleNotificationDropdown"
            class="relative p-2 rounded-xl bg-white/10 hover:bg-white/15 text-slate-300 transition-colors cursor-pointer"
            :title="adminT('notifications')"
          >
            <Bell class="w-4 h-4" />
            <span v-if="stats.unread_messages > 0" class="absolute -top-1 -right-1 px-1 min-w-4 h-4 rounded-md bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center">
              {{ stats.unread_messages }}
            </span>
          </button>

          <!-- Notification Dropdown Panel -->
          <div 
            v-if="notificationDropdownOpen" 
            class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden text-slate-800"
          >
            <!-- Dropdown Header -->
            <div class="p-3.5 px-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <Bell class="w-4 h-4 text-blue-600" />
                <h4 class="font-extrabold text-xs text-slate-900 uppercase tracking-wider">{{ adminT('notifications') }}</h4>
                <span v-if="stats.unread_messages > 0" class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold">
                  {{ stats.unread_messages }} {{ adminT('new') }}
                </span>
              </div>
              <button 
                v-if="stats.unread_messages > 0"
                @click="markAllInquiriesRead"
                class="text-[11px] font-bold text-blue-600 hover:text-blue-700 hover:underline cursor-pointer"
              >
                {{ adminT('mark_all_read') }}
              </button>
            </div>

            <!-- Notifications Content List -->
            <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
              <template v-if="messagesList.length > 0">
                <div 
                  v-for="msg in messagesList.slice(0, 5)" 
                  :key="msg.id"
                  @click="handleNotificationClick(msg)"
                  class="p-3.5 hover:bg-slate-50 transition-colors cursor-pointer flex items-start gap-3 group"
                  :class="[
                    msg.status === 'unread' 
                      ? (msg.target_sub_city?.startsWith('Editorial:') ? 'bg-amber-50/50 border-l-3 border-amber-500' : 'bg-blue-50/40 border-l-3 border-blue-600') 
                      : ''
                  ]"
                >
                  <div 
                    class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                    :class="[
                      msg.target_sub_city?.startsWith('Editorial:')
                        ? 'bg-amber-100 text-amber-800'
                        : (msg.status === 'unread' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500')
                    ]"
                  >
                    <FileCheck v-if="msg.target_sub_city?.startsWith('Editorial:')" class="w-4 h-4 text-amber-700" />
                    <MessageSquare v-else class="w-4 h-4" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1 mb-0.5">
                      <p class="text-xs font-bold text-slate-900 truncate group-hover:text-blue-600 transition-colors flex items-center gap-1.5">
                        <span>{{ msg.sender_name }}</span>
                      </p>
                      <span 
                        class="text-[9px] font-bold px-1.5 py-0.5 rounded-md font-mono shrink-0"
                        :class="msg.target_sub_city?.startsWith('Editorial:') ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-500'"
                      >
                        {{ msg.target_sub_city || 'Central' }}
                      </span>
                    </div>
                    <p class="text-xs text-slate-800 truncate font-semibold">
                      {{ msg.subject || msg.message }}
                    </p>
                    <p class="text-[10px] text-slate-500 truncate mt-0.5">
                      {{ msg.message }}
                    </p>
                    <p v-if="msg.target_sub_city?.startsWith('Editorial:')" class="text-[10px] text-amber-700 font-bold mt-1 flex items-center gap-1">
                      <span>Click to review and approve live &rarr;</span>
                    </p>
                  </div>
                </div>
              </template>

              <!-- No Notifications Empty State -->
              <div v-else class="p-8 text-center text-slate-400">
                <Bell class="w-8 h-8 mx-auto mb-2 opacity-30 text-slate-400" />
                <p class="text-xs font-bold text-slate-600">{{ adminT('no_notifications') }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">{{ adminT('all_caught_up') }}</p>
              </div>
            </div>

            <!-- Dropdown Footer -->
            <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
              <button 
                @click="notificationDropdownOpen = false; activeSection = 'messages'"
                class="w-full py-1.5 px-3 rounded-xl bg-white hover:bg-slate-100 text-blue-700 text-xs font-bold border border-slate-200 transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer"
              >
                <span>{{ adminT('go_to_inbox') }}</span>
                <ChevronRight class="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>

        <!-- Staff Profile & Logout Dropdown -->
        <div class="flex items-center gap-3 pl-2 border-l border-white/10">
          <button 
            @click="openProfileModal" 
            :title="adminT('my_profile')"
            class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-white/10 transition-colors cursor-pointer group text-left"
          >
            <div 
              class="w-8 h-8 rounded-xl text-white flex items-center justify-center font-bold text-xs shadow-xs overflow-hidden shrink-0 border border-white/20"
              :class="isSuperAdmin ? 'bg-purple-600' : (isEditor ? 'bg-blue-600' : 'bg-emerald-600')"
            >
              <img v-if="currentUser.avatar_path" :src="currentUser.avatar_path" alt="Avatar" class="w-full h-full object-cover" />
              <span v-else>{{ (currentUser.name || 'Staff').substring(0, 2).toUpperCase() }}</span>
            </div>
            <div class="hidden lg:block">
              <div class="text-xs font-bold text-white leading-tight flex items-center gap-1.5 group-hover:text-amber-300 transition-colors">
                <span>{{ currentUser.name || 'Staff User' }}</span>
                <Edit class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity text-slate-400" />
              </div>
              <span 
                class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold tracking-tight uppercase mt-0.5"
                :class="roleBadgeClass"
              >
                <Shield v-if="isSuperAdmin" class="w-2.5 h-2.5" />
                <Award v-else-if="isEditor" class="w-2.5 h-2.5" />
                <PenTool v-else class="w-2.5 h-2.5" />
                {{ roleLabel }}
              </span>
            </div>
          </button>

          <button @click="openProfileModal" :title="adminT('my_profile')" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 cursor-pointer transition-colors">
            <User class="w-4 h-4" />
          </button>
          <button @click="logout" :title="adminT('sign_out')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 cursor-pointer transition-colors ml-1">
            <LogOut class="w-4 h-4" />
          </button>
        </div>
      </div>
    </header>

    <!-- Success Toast Notification -->
    <div v-if="alertToast" class="fixed bottom-6 right-6 z-50 bg-[#071324] text-white px-5 py-3 rounded-2xl shadow-2xl border border-emerald-500 flex items-center gap-3 text-xs font-bold">
      <CheckCircle2 class="w-4 h-4 text-emerald-400" />
      <span>{{ alertToast }}</span>
    </div>

    <!-- Workspace Container (Sidebar + Dynamic Workspace Area) -->
    <div class="flex-1 flex overflow-hidden min-h-0 relative">
      <!-- Sidebar Navigation Component -->
      <AdminSidebar
        :activeSection="activeSection"
        :mobileSidebarOpen="mobileSidebarOpen"
        :can="can"
        :isAuthor="isAuthor"
        :isSuperAdmin="isSuperAdmin"
        :adminT="adminT"
        :officesCount="stats.total_offices || stats.offices_count || officesList.length"
        :destinationsCount="stats.destinations_count || destinationsList.length"
        :hotlinesCount="emergencyContactsList.length"
        :unreadMessagesCount="stats.unread_messages || 0"
        :pollsCount="pollsList.length"
        :auditLogsTotal="auditLogsTotal"
        @selectSection="selectSection"
        @closeMobile="mobileSidebarOpen = false"
      />

      <!-- Dynamic Workspace Area (Independent Scrollable Container) -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto space-y-6 sm:space-y-8 bg-slate-100 min-w-0">
        <!-- 1. DASHBOARD OVERVIEW (Modularized Enterprise Tab) -->
        <OverviewTab
          v-if="activeSection === 'dashboard'"
          :stats="stats"
          :currentUser="currentUser"
          :isAuthor="isAuthor"
          :isEditor="isEditor"
          :isSuperAdmin="isSuperAdmin"
          :adminT="adminT"
          :newsCount="stats.news_count || newsList.length"
          :galleryCount="galleryList.length"
          :resourcesCount="resourcesList.length"
          @navigate="selectSection"
          @openModal="openNewModal"
        />

        <!-- 2. HERO SLIDESHOW MANAGER -->
        <HeroSlidesTab
          v-else-if="activeSection === 'hero_slides'"
          :heroSlidesList="heroSlidesList"
          :can="can"
          @openCreate="openNewModal('hero_slide')"
          @openEdit="(slide) => editItem('hero_slide', slide)"
          @deleteItem="(slide) => deleteItem('/admin/hero-slides', slide.id, slide.title_en || slide.title_am)"
        />

        <!-- 3. MEDIA GALLERY ARCHIVE -->
        <GalleryTab
          v-else-if="activeSection === 'gallery'"
          :galleryList="galleryList"
          :isEditor="isEditor"
          :canEditItem="canEditItem"
          :canDeleteItem="canDeleteItem"
          @openCreate="openNewModal('gallery')"
          @openEdit="(item) => editItem('gallery', item)"
          @deleteItem="(item) => deleteItem('/admin/gallery', item.id, item.caption_en || item.caption_am)"
          @approveItem="(item) => approveAndPublishItem('gallery', item)"
          @unpublishItem="(item) => unpublishItem('gallery', item)"
        />

        <!-- 2. NEWS & PRESS RELEASES -->
        <NewsTab
          v-else-if="activeSection === 'news'"
          :newsList="newsList"
          :isEditor="isEditor"
          :isAuthor="isAuthor"
          :currentUserId="currentUser?.id"
          @openCreate="openNewModal('news')"
          @openEdit="(item) => editItem('news', item)"
          @deleteItem="(id) => deleteItem('/admin/news', id, 'News Article')"
          @approveItem="approveAndPublishNews"
          @unpublishItem="unpublishNews"
        />

        <!-- 3. PUBLIC TENDERS -->
        <TendersTab
          v-else-if="activeSection === 'tenders'"
          :tendersList="tendersList"
          :isEditor="isEditor"
          :isAuthor="isAuthor"
          :currentUserId="currentUser?.id"
          @openCreate="openNewModal('tender')"
          @openEdit="(item) => editItem('tender', item)"
          @deleteItem="(id) => deleteItem('/admin/tenders', id, 'Tender Notice')"
          @approveItem="(item) => approveAndPublishItem('tender', item)"
          @unpublishItem="(item) => unpublishItem('tender', item)"
        />

        <!-- 4. CIVIL SERVICE VACANCIES -->
        <VacanciesTab
          v-else-if="activeSection === 'vacancies'"
          :vacanciesList="vacanciesList"
          :isEditor="isEditor"
          :canEditItem="canEditItem"
          :canDeleteItem="canDeleteItem"
          @openCreate="openNewModal('vacancy')"
          @openEdit="(item) => editItem('vacancy', item)"
          @deleteItem="(item) => deleteItem('/admin/vacancies', item.id, item.position_code || item.title_en)"
          @approveItem="(item) => approveAndPublishItem('vacancy', item)"
          @unpublishItem="(item) => unpublishItem('vacancy', item)"
        />

        <!-- 5. MAYOR PROFILE & KEYNOTE -->
        <MayorTab
          v-else-if="activeSection === 'mayor'"
          :mayorData="mayorData"
          :uploadingFile="uploadingFile"
          :isSuperAdmin="isSuperAdmin"
          @updateProfile="updateMayorProfile"
          @uploadPhoto="(event) => uploadFileFor(event, mayorData, 'portrait_path')"
        />

        <!-- 6. CABINET & COUNCIL LEADERSHIP -->
        <LeadershipTab
          v-else-if="activeSection === 'leadership'"
          :leadershipList="leadershipList"
          :isSuperAdmin="isSuperAdmin"
          @openCreate="openNewModal('leadership')"
          @openEdit="(m) => editItem('leadership', m)"
          @deleteItem="(m) => deleteItem('/admin/leadership', m.id, m.name_en || m.name_am)"
        />

        <!-- 7. 22 MUNICIPAL OFFICES -->
        <OfficesTab
          v-else-if="activeSection === 'offices'"
          :officesList="officesList"
          :isSuperAdmin="isSuperAdmin"
          @openCreate="openNewModal('office')"
          @openEdit="(off) => editItem('office', off)"
          @deleteItem="(off) => deleteItem('/admin/offices', off.id, off.name_en || off.name_am)"
        />

        <!-- 8. SERVICES (TEXT & STEP) -->
        <ServicesTab
          v-else-if="activeSection === 'services'"
          :servicesList="servicesList"
          :isEditor="isEditor"
          :canEditItem="canEditItem"
          :canDeleteItem="canDeleteItem"
          @openCreate="openNewModal('service')"
          @openEdit="(item) => editItem('service', item)"
          @deleteItem="(item) => deleteItem('/admin/services', item.id, item.title_en || item.title_am)"
          @approveItem="(item) => approveAndPublishItem('service', item)"
          @unpublishItem="(item) => unpublishItem('service', item)"
        />

        <!-- 9. MEGA INFRASTRUCTURE PROJECTS -->
        <ProjectsTab
          v-else-if="activeSection === 'projects'"
          :projectsList="projectsList"
          :isEditor="isEditor"
          :canEditItem="canEditItem"
          :canDeleteItem="canDeleteItem"
          @openCreate="openNewModal('project')"
          @openEdit="(item) => editItem('project', item)"
          @deleteItem="(item) => deleteItem('/admin/projects', item.id, item.title_en || item.title_am)"
          @approveItem="(item) => approveAndPublishItem('project', item)"
          @unpublishItem="(item) => unpublishItem('project', item)"
          @updateProgress="(proj, pct) => updateProgress(proj, pct)"
        />

        <!-- 10. RESOURCES & DIRECTIVES -->
        <ResourcesTab
          v-else-if="activeSection === 'resources'"
          :resourcesList="resourcesList"
          :isEditor="isEditor"
          :canEditItem="canEditItem"
          :canDeleteItem="canDeleteItem"
          @openCreate="openNewModal('resource')"
          @openEdit="(item) => editItem('resource', item)"
          @deleteItem="(item) => deleteItem('/admin/resources', item.id, item.title_en || item.title_am)"
          @approveItem="(item) => approveAndPublishItem('resource', item)"
          @unpublishItem="(item) => unpublishItem('resource', item)"
        />

        <!-- DESTINATIONS & TOURISM SPOTLIGHT -->
        <DestinationsTab
          v-else-if="activeSection === 'destinations'"
          :destinationsList="destinationsList"
          :isEditor="isEditor"
          :isAuthor="isAuthor"
          :currentUserId="currentUser?.id"
          @openCreate="openNewModal('destination')"
          @openEdit="(item) => editItem('destination', item)"
          @deleteItem="(id) => deleteItem('/admin/destinations', id, 'Destination')"
          @approveItem="(item) => approveAndPublishItem('destination', item)"
          @unpublishItem="(item) => unpublishItem('destination', item)"
        />

        <!-- 11. CITIZEN FEEDBACK INBOX & EDITORIAL NOTIFICATIONS -->
        <MessagesTab
          v-else-if="activeSection === 'messages'"
          :messagesList="messagesList"
          :isEditor="isEditor"
          @updateStatus="({ id, status }) => updateMessageStatus(id, status)"
          @navigateToModule="(mod) => navigateToEditorialModule(mod)"
          @refresh="fetchAllAdminData"
        />

        <StaffTab
          v-else-if="activeSection === 'staff'"
          :staffList="staffList"
          :isSuperAdmin="isSuperAdmin"
          @openCreate="openNewModal('staff')"
          @toggleStatus="(id) => api.patch('/admin/staff/' + id + '/toggle').then(fetchAllAdminData)"
          @openResetPassword="openResetStaffPasswordModal"
        />

        <!-- 12. AUDIT & SYSTEM ACTIVITY LOGS (SUPER ADMIN ONLY) -->
        <AuditLogsTab
          v-else-if="activeSection === 'audit' && isSuperAdmin"
          :logsList="auditLogsList"
          :totalLogs="auditLogsTotal"
          :currentPage="auditLogsCurrentPage"
          :lastPage="auditLogsLastPage"
          :perPage="25"
          :loading="auditLogsLoading"
          :filters="auditLogsFilters"
          @updateFilter="updateAuditFilter"
          @changePage="fetchAuditLogs"
          @refresh="fetchAuditLogs(auditLogsCurrentPage)"
          @purgeOld="purgeOldAuditLogs"
        />

        <!-- 13. SETTINGS & MAINTENANCE MODE -->
        <SettingsTab
          v-else-if="activeSection === 'settings'"
          :siteSettings="siteSettings"
          :emergencyContactsList="emergencyContactsList"
          :detectingLocation="detectingLocation"
          @updateSettings="updateSettings"
          @detectLiveLocation="detectLiveLocation"
          @selectSection="selectSection"
        />

        <!-- 14. 24/7 EMERGENCY HOTLINES MANAGEMENT -->
        <HotlinesTab
          v-else-if="activeSection === 'hotlines'"
          :hotlinesList="emergencyContactsList"
          :isEditor="isEditor"
          @openCreate="openNewModal('emergency_contact')"
          @openEdit="(item) => editItem('emergency_contact', item)"
          @deleteItem="(id) => deleteItem('/admin/emergency-contacts', id, 'Emergency Hotline')"
        />

        <!-- 15. CIVIC OPINION POLLS -->
        <PollsTab
          v-else-if="activeSection === 'polls'"
          :pollsList="pollsList"
          :adminT="adminT"
          @openCreate="openNewModal('poll')"
          @openEdit="(item) => editItem('poll', item)"
          @deleteItem="(item) => deleteItem('/admin/polls', item.id, item.question_en || item.question_am)"
          @resetPollVotes="(item) => resetPollVotes(item)"
          @togglePollActive="(item) => togglePollActive(item)"
          @refresh="fetchAllAdminData"
        />
      </main>
    </div>

    <!-- 1. UNIVERSAL DYNAMIC CREATION & EDIT MODAL -->
    <UniversalItemModal
      :modalType="modalType"
      :editingId="editingId"
      :isEditor="isEditor"
      :isAuthor="isAuthor"
      :isSuperAdmin="isSuperAdmin"
      :submitting="submitting"
      :uploadingFile="uploadingFile"
      :newsForm="newsForm"
      :tenderForm="tenderForm"
      :vacancyForm="vacancyForm"
      :projectForm="projectForm"
      :staffForm="staffForm"
      :serviceForm="serviceForm"
      :officeForm="officeForm"
      :leadershipForm="leadershipForm"
      :resourceForm="resourceForm"
      :heroSlideForm="heroSlideForm"
      :galleryForm="galleryForm"
      :destinationForm="destinationForm"
      :emergencyContactForm="emergencyContactForm"
      :pollForm="pollForm"
      @close="() => { modalType = ''; editingId = null }"
      @submit="submitActiveModal"
      @uploadFile="uploadFileFor"
    />

    <!-- 2. SECURITY & PROFILE SETTINGS MODAL -->
    <ProfileSettingsModal
      :isOpen="profileModalOpen"
      :profileForm="profileForm"
      :passwordForm="passwordForm"
      :roleLabel="roleLabel"
      :profileLoading="profileLoading"
      :uploadingFile="uploadingFile"
      @close="profileModalOpen = false"
      @saveProfile="handleSaveProfile"
      @changePassword="handleChangePassword"
      @uploadAvatar="(event) => uploadFileFor(event, profileForm, 'avatar_path')"
    />

    <!-- 3. SUPER ADMIN RESET STAFF PASSWORD OVERRIDE MODAL -->
    <ResetStaffPasswordModal
      :isOpen="resetStaffModalOpen"
      :targetStaffUser="targetStaffUser"
      :resetStaffForm="resetStaffForm"
      :resetStaffLoading="resetStaffLoading"
      @close="resetStaffModalOpen = false"
      @submit="handleAdminResetPassword"
      @generatePassword="generateRandomPassword"
    />

    <!-- 4. UNIVERSAL ACTION & DELETE CONFIRMATION CARD MODAL -->
    <ConfirmActionModal
      :confirmModal="confirmModal"
      @close="closeConfirmModal"
      @confirm="handleConfirmModalAction"
    />
  </div>
</template>
