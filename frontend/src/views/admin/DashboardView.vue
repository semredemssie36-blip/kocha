<script setup>
import { ref, reactive, onMounted, onUnmounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import OverviewTab from './tabs/OverviewTab.vue'
import NewsTab from './tabs/NewsTab.vue'
import TendersTab from './tabs/TendersTab.vue'
import HotlinesTab from './tabs/HotlinesTab.vue'
import DestinationsTab from './tabs/DestinationsTab.vue'
import MessagesTab from './tabs/MessagesTab.vue'
import StaffTab from './tabs/StaffTab.vue'
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
  Vote
} from 'lucide-vue-next'

const router = useRouter()
const activeSection = ref('dashboard')
const activeSubTab = ref('overview')
const mobileSidebarOpen = ref(false)

function selectSection(section) {
  activeSection.value = section
  mobileSidebarOpen.value = false
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

// KPIs & Stats
const stats = ref({
  news_count: 0,
  published_news: 0,
  pending_drafts: 0,
  active_tenders: 0,
  offices_count: 0,
  unread_messages: 0,
  projects_count: 0,
  monthly_views: '24,520'
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
  createNews: true,
  editNews: true,
  deleteNews: isEditor.value,

  uploadMedia: isEditor.value,
  editMedia: isEditor.value,
  deleteMedia: isEditor.value,

  createResources: isEditor.value,
  editResources: isEditor.value,
  deleteResources: isEditor.value,

  manageHeroSlides: isEditor.value,
  manageDestinations: isEditor.value,
  manageTenders: isEditor.value,
  manageVacancies: isEditor.value,
  manageServices: isEditor.value,
  manageProjects: isEditor.value,
  viewInquiries: isEditor.value,

  manageMayor: isEditor.value,
  manageLeadership: isEditor.value,
  manageOffices: isEditor.value,
  deleteGovernance: isSuperAdmin.value,

  manageStaff: isSuperAdmin.value,
  manageSettings: isSuperAdmin.value,
  manageHotlines: isEditor.value,
  managePolls: isEditor.value,
  canDelete: isEditor.value
}))

// Section navigation guard: redirect if role has no permission
watch(activeSection, (sec) => {
  if (isAuthor.value && !['dashboard', 'news'].includes(sec)) {
    activeSection.value = 'news'
    showToast('Permission restricted: Navigated to your authorized workspace.')
  } else if (!isSuperAdmin.value && ['staff', 'settings'].includes(sec)) {
    activeSection.value = 'dashboard'
    showToast('Super Administrator privilege required.')
  }
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
const gallerySearch = ref('')
const galleryPage = ref(1)
const galleryPerPage = ref(8)
const filteredGallery = computed(() => {
  if (!gallerySearch.value.trim()) return galleryList.value
  const q = gallerySearch.value.toLowerCase().trim()
  return galleryList.value.filter(g =>
    (g.caption_en && g.caption_en.toLowerCase().includes(q)) ||
    (g.caption_am && g.caption_am.includes(q)) ||
    (g.category && g.category.toLowerCase().includes(q)) ||
    (g.media_type && g.media_type.toLowerCase().includes(q))
  )
})
const totalGalleryPages = computed(() => Math.ceil(filteredGallery.value.length / galleryPerPage.value) || 1)
const paginatedGallery = computed(() => {
  const start = (galleryPage.value - 1) * galleryPerPage.value
  return filteredGallery.value.slice(start, start + galleryPerPage.value)
})
watch(gallerySearch, () => { galleryPage.value = 1 })

const messagesList = ref([])
const staffList = ref([])
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

// Data Fetchers
async function fetchAllAdminData() {
  loading.value = true
  try {
    // 1. Base endpoints accessible to all authenticated staff (author, editor, super_admin)
    const [statsRes, newsRes, resourcesRes, galleryRes] = await Promise.all([
      api.get('/admin/stats').catch(() => ({ data: {} })),
      api.get('/admin/news').catch(() => ({ data: [] })),
      api.get('/resources').catch(() => ({ data: [] })),
      api.get('/admin/gallery').catch(() => ({ data: [] }))
    ])

    stats.value = { ...stats.value, ...statsRes.data }
    newsList.value = newsRes.data?.data || newsRes.data || []
    resourcesList.value = resourcesRes.data || []
    galleryList.value = galleryRes.data || []

    // 2. Editorial & Operational endpoints (editor, super_admin)
    if (isEditor.value) {
      const [tendersRes, vacanciesRes, projectsRes, officesRes, msgsRes, mayorRes, leadershipRes, slidesRes, servicesRes, destinationsRes, emergencyRes, pollsRes] = await Promise.all([
        api.get('/tenders').catch(() => ({ data: [] })),
        api.get('/vacancies').catch(() => ({ data: [] })),
        api.get('/projects').catch(() => ({ data: [] })),
        api.get('/offices').catch(() => ({ data: [] })),
        api.get('/admin/messages').catch(() => ({ data: [] })),
        api.get('/mayor').catch(() => ({ data: {} })),
        api.get('/leadership').catch(() => ({ data: [] })),
        api.get('/admin/hero-slides').catch(() => ({ data: [] })),
        api.get('/services').catch(() => ({ data: [] })),
        api.get('/admin/destinations').catch(() => ({ data: [] })),
        api.get('/admin/emergency-contacts').catch(() => ({ data: [] })),
        api.get('/admin/polls').catch(() => ({ data: [] }))
      ])

      tendersList.value = tendersRes.data || []
      vacanciesList.value = vacanciesRes.data || []
      projectsList.value = projectsRes.data || []
      officesList.value = officesRes.data || []
      stats.value.offices_count = officesList.value.length
      messagesList.value = msgsRes.data?.data || msgsRes.data || []
      mayorData.value = mayorRes.data || {}
      leadershipList.value = leadershipRes.data || []
      heroSlidesList.value = slidesRes.data || []
      servicesList.value = servicesRes.data || []
      destinationsList.value = destinationsRes.data || []
      emergencyContactsList.value = emergencyRes.data || []
      pollsList.value = pollsRes.data || []
    }

    // 3. Super Admin Only endpoints
    if (isSuperAdmin.value) {
      const [settingsRes, staffRes] = await Promise.all([
        api.get('/admin/settings').catch(() => ({ data: {} })),
        api.get('/admin/staff').catch(() => ({ data: [] }))
      ])

      if (settingsRes.data && Object.keys(settingsRes.data).length) {
        siteSettings.value = {
          ...siteSettings.value,
          ...settingsRes.data,
          is_maintenance: Boolean(settingsRes.data.is_maintenance)
        }
      }
      staffList.value = staffRes.data || []
    }
  } catch (err) {
    console.error('Error fetching admin data:', err)
  } finally {
    loading.value = false
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
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to toggle poll status.')
  }
}

async function resetPollVotes(poll) {
  if (!confirm(`Are you sure you want to reset all votes for "${poll.question_en || poll.question_am}"? This will clear all demo and previous votes so citizens can begin voting on real verified ballots.`)) return
  try {
    const res = await api.post(`/admin/polls/${poll.id}/reset-votes`)
    showToast(res.data?.message || 'Poll votes reset successfully! Ready for real data.')
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to reset poll votes.')
  }
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
    galleryForm.media_type = item.media_type || 'image'
    galleryForm.category = item.category || 'urban'
    galleryForm.media_path_or_url = item.media_path_or_url || ''
    galleryForm.thumbnail_url = item.thumbnail_url || ''
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
    } else if (modalType.value === 'destination') {
      if (isEdit) await api.put(`/admin/destinations/${id}`, destinationForm)
      else await api.post('/admin/destinations', destinationForm)
      showToast(isEdit ? 'Destination spotlight updated!' : 'New destination spotlight created!')
    } else if (modalType.value === 'emergency_contact') {
      if (isEdit) await api.put(`/admin/emergency-contacts/${id}`, emergencyContactForm)
      else await api.post('/admin/emergency-contacts', emergencyContactForm)
      showToast(isEdit ? 'Emergency hotline updated successfully!' : 'Emergency hotline added successfully!')
    } else if (modalType.value === 'poll') {
      if (isEdit) await api.put(`/admin/polls/${id}`, pollForm)
      else await api.post('/admin/polls', pollForm)
      showToast(isEdit ? 'Civic poll updated successfully!' : 'Civic poll published live!')
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
  if (endpoint === '/admin/news') {
    // Handled by backend check (author can delete own draft, editor can delete per policy)
  } else if (!can.value.canDelete) {
    alert('Permission Denied: Authors are not authorized to delete records. Only Editors and Administrators have delete privileges.')
    return
  }
  if (['/admin/offices', '/admin/leadership', '/admin/projects'].some(ep => endpoint.startsWith(ep)) && !isSuperAdmin.value) {
    alert('Permission Denied: Only Super Administrators can delete municipal offices, leadership profiles, or infrastructure projects.')
    return
  }
  if (!confirm(`Are you sure you want to delete "${name}"? This action removes it permanently from the database and public portal.`)) return
  try {
    await api.delete(`${endpoint}/${id}`)
    showToast(`"${name}" deleted successfully.`)
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to delete item.')
  }
}

// Editorial Governance & Approval Pipeline Actions
async function approveAndPublishNews(item) {
  try {
    const res = await api.put(`/admin/news/${item.id}`, { ...item, status: 'published' })
    item.status = 'published'
    showToast(res.data?.message || `"${item.title_en || item.title_am}" has been approved & published live!`)
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to approve article.')
  }
}

async function unpublishNews(item) {
  try {
    const res = await api.put(`/admin/news/${item.id}`, { ...item, status: 'draft' })
    item.status = 'draft'
    showToast(res.data?.message || `"${item.title_en || item.title_am}" has been moved to drafts.`)
    await fetchAllAdminData()
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to unpublish article.')
  }
}

async function submitNewsForReview(item) {
  try {
    const res = await api.put(`/admin/news/${item.id}`, { ...item, status: 'pending' })
    item.status = 'pending'
    showToast(res.data?.message || `"${item.title_en || item.title_am}" submitted for editorial review.`)
    await fetchAllAdminData()
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
      showToast(`📍 GPS Coordinates Detected: Lat ${siteSettings.value.latitude}, Lng ${siteSettings.value.longitude}`)
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

function handleNotificationClick(msg) {
  notificationDropdownOpen.value = false
  activeSection.value = 'messages'
}

async function markAllInquiriesRead() {
  const unread = messagesList.value.filter(m => m.status === 'unread')
  for (const m of unread) {
    m.status = 'read'
    api.patch(`/admin/inquiries/${m.id}/status`, { status: 'read' }).catch(() => {})
  }
  stats.value.unread_messages = 0
  showToast('All notifications marked as read')
}

function handleGlobalClick(e) {
  if (!e.target.closest('.notification-menu-container')) {
    notificationDropdownOpen.value = false
  }
}

onMounted(() => {
  const token = localStorage.getItem('kombolcha_admin_token')
  if (!token) {
    router.replace('/admin/login')
    return
  }
  fetchAllAdminData()
  window.addEventListener('click', handleGlobalClick)
})

onUnmounted(() => {
  window.removeEventListener('click', handleGlobalClick)
})
</script>

<template>
  <div class="h-screen flex flex-col overflow-hidden bg-slate-100 font-sans text-slate-800">
    <!-- Top Bar matching diagram -->
    <header class="bg-slate-900 border-b border-slate-800 text-white px-3 sm:px-6 py-2.5 sm:py-3 flex items-center justify-between shrink-0 z-30 shadow-md">
      <!-- Left: Mobile Menu Toggle, Logo & Portal Title -->
      <div class="flex items-center gap-2 sm:gap-3 min-w-0">
        <!-- Mobile Sidebar Hamburger Toggle -->
        <button 
          @click="mobileSidebarOpen = !mobileSidebarOpen" 
          class="lg:hidden p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white cursor-pointer shrink-0 transition-colors"
          aria-label="Toggle Navigation Drawer"
        >
          <Menu class="w-5 h-5" />
        </button>

        <img src="/assets/logo.png" alt="Seal" class="w-8 h-8 sm:w-10 sm:h-10 object-contain rounded-full border border-blue-400/40 bg-white p-0.5 shrink-0" />
        <div class="min-w-0">
          <h1 class="text-xs sm:text-sm font-black tracking-tight text-white flex items-center gap-1.5 sm:gap-2 truncate">
            <span class="truncate">{{ adminT('portal_title') }}</span>
            <span class="hidden sm:inline-block text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-blue-600/30 text-blue-300 border border-blue-500/30 shrink-0">
              CMS 2026
            </span>
          </h1>
          <p class="hidden sm:block text-[10px] text-slate-400 truncate">{{ adminT('portal_sub') }}</p>
        </div>
      </div>

      <!-- Center: Admin Search Bar -->
      <div class="hidden md:flex items-center relative w-72 lg:w-96 mx-4">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="adminSearch" 
          type="text" 
          :placeholder="adminT('search_placeholder')" 
          class="w-full bg-slate-800/80 border border-slate-700 rounded-xl pl-9 pr-4 py-1.5 text-xs text-white focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-slate-400"
        />
      </div>

      <!-- Right Actions: Language, Notifications, Profile -->
      <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        <!-- Live Public Site Shortcut -->
        <router-link to="/" target="_blank" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition-colors">
          <Globe class="w-3.5 h-3.5 text-emerald-400" />
          <span>{{ adminT('live_site') }}</span>
        </router-link>

        <!-- Admin Language Toggle [AM | EN] -->
        <div class="flex items-center bg-slate-800 rounded-xl p-0.5 sm:p-1 border border-slate-700 text-xs font-bold">
          <button 
            @click="switchAdminLang('en')" 
            :class="adminLang === 'en' ? 'bg-blue-600 text-white px-1.5 sm:px-2 py-0.5 rounded-lg' : 'text-slate-400 px-1.5 sm:px-2 py-0.5 cursor-pointer hover:text-white'"
          >
            EN
          </button>
          <button 
            @click="switchAdminLang('am')" 
            :class="adminLang === 'am' ? 'bg-blue-600 text-white px-1.5 sm:px-2 py-0.5 rounded-lg' : 'text-slate-400 px-1.5 sm:px-2 py-0.5 cursor-pointer hover:text-white'"
          >
            አማ
          </button>
        </div>

        <!-- Notifications Bell & Interactive Dropdown Menu -->
        <div class="relative notification-menu-container">
          <button 
            @click.stop="notificationDropdownOpen = !notificationDropdownOpen"
            class="relative p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors cursor-pointer"
            :title="adminT('notifications')"
          >
            <Bell class="w-4 h-4" />
            <span v-if="stats.unread_messages > 0" class="absolute -top-1 -right-1 px-1 min-w-4 h-4 rounded-md bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center animate-pulse">
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
                  :class="msg.status === 'unread' ? 'bg-blue-50/40' : ''"
                >
                  <div 
                    class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                    :class="msg.status === 'unread' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500'"
                  >
                    <MessageSquare class="w-4 h-4" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-1 mb-0.5">
                      <p class="text-xs font-bold text-slate-900 truncate group-hover:text-blue-600 transition-colors">
                        {{ msg.sender_name }}
                      </p>
                      <span class="text-[10px] text-slate-400 font-mono shrink-0">
                        {{ msg.target_sub_city || 'Central' }}
                      </span>
                    </div>
                    <p class="text-xs text-slate-600 truncate font-medium">
                      {{ msg.subject || msg.message }}
                    </p>
                    <p class="text-[10px] text-slate-400 truncate mt-1">
                      {{ msg.message }}
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
        <div class="flex items-center gap-3 pl-2 border-l border-slate-800">
          <button 
            @click="openProfileModal" 
            :title="adminT('my_profile')"
            class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-800 transition-colors cursor-pointer group text-left"
          >
            <div 
              class="w-8 h-8 rounded-xl text-white flex items-center justify-center font-bold text-xs shadow-xs overflow-hidden shrink-0 border border-slate-700"
              :class="isSuperAdmin ? 'bg-purple-600' : (isEditor ? 'bg-blue-600' : 'bg-emerald-600')"
            >
              <img v-if="currentUser.avatar_path" :src="currentUser.avatar_path" alt="Avatar" class="w-full h-full object-cover" />
              <span v-else>{{ (currentUser.name || 'Staff').substring(0, 2).toUpperCase() }}</span>
            </div>
            <div class="hidden lg:block">
              <div class="text-xs font-bold text-white leading-tight flex items-center gap-1.5 group-hover:text-blue-400 transition-colors">
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

          <button @click="openProfileModal" :title="adminT('my_profile')" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer transition-colors">
            <User class="w-4 h-4" />
          </button>
          <button @click="logout" :title="adminT('sign_out')" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 cursor-pointer transition-colors ml-1">
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
    <div class="flex-1 flex overflow-hidden min-h-0 relative">
      <!-- Mobile Drawer Backdrop Overlay -->
      <div 
        v-if="mobileSidebarOpen" 
        @click="mobileSidebarOpen = false" 
        class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 lg:hidden transition-opacity"
      ></div>

      <!-- Sidebar Navigation (Drawer on Mobile/Tablet, Fixed on Desktop) -->
      <aside 
        :class="[
          'w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800 overflow-y-auto select-none dark-scrollbar transition-transform duration-300 ease-in-out',
          'fixed inset-y-0 left-0 z-50 lg:static lg:translate-x-0',
          mobileSidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'
        ]"
      >
        <!-- Mobile Drawer Close Header -->
        <div class="lg:hidden p-4 pb-2 flex items-center justify-between border-b border-slate-800">
          <div class="flex items-center gap-2">
            <img src="/assets/logo.png" alt="Seal" class="w-7 h-7 object-contain rounded-full bg-white p-0.5" />
            <span class="text-xs font-bold text-white">Kombolcha Admin</span>
          </div>
          <button @click="mobileSidebarOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <nav class="p-4 space-y-1.5 text-xs font-bold">
          <button 
            @click="selectSection('dashboard')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'dashboard' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <LayoutDashboard class="w-4 h-4" />
            <span>{{ adminT('nav_dashboard') }}</span>
          </button>

          <!-- Media Management Category -->
          <div class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            {{ isAuthor ? adminT('cat_content_media') : adminT('cat_media_visuals') }}
          </div>
          <button 
            v-if="can.manageHeroSlides"
            @click="selectSection('hero_slides')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'hero_slides' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Sliders class="w-4 h-4" />
            <span>{{ adminT('nav_hero_slides') }}</span>
          </button>
          <button 
            v-if="can.uploadMedia"
            @click="selectSection('gallery')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'gallery' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Film class="w-4 h-4" />
            <span>{{ adminT('nav_gallery') }}</span>
          </button>
          <button 
            @click="selectSection('news')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'news' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FileText class="w-4 h-4" />
            <span>{{ adminT('nav_news') }}</span>
          </button>
          <button 
            v-if="can.manageTenders"
            @click="selectSection('tenders')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'tenders' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FileCheck class="w-4 h-4" />
            <span>{{ adminT('nav_tenders') }}</span>
          </button>
          <button 
            v-if="can.manageVacancies"
            @click="selectSection('vacancies')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'vacancies' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Briefcase class="w-4 h-4" />
            <span>{{ adminT('nav_vacancies') }}</span>
          </button>

          <!-- Governance Management Category -->
          <div v-if="can.manageMayor || can.manageLeadership || can.manageOffices" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            {{ adminT('cat_governance') }}
          </div>
          <button 
            v-if="can.manageMayor"
            @click="selectSection('mayor')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'mayor' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Award class="w-4 h-4" />
            <span>{{ adminT('nav_mayor') }}</span>
          </button>
          <button 
            v-if="can.manageLeadership"
            @click="selectSection('leadership')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'leadership' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Users class="w-4 h-4" />
            <span>{{ adminT('nav_leadership') }}</span>
          </button>
          <button 
            v-if="can.manageOffices"
            @click="selectSection('offices')"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'offices' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <Building2 class="w-4 h-4 shrink-0" />
              <span>{{ adminT('nav_offices') }}</span>
            </div>
            <span v-if="officesList.length" class="text-[11px] px-2 py-0.5 rounded-full bg-white/20 text-white font-mono font-bold">{{ officesList.length }}</span>
          </button>

          <!-- Civic Services Category -->
          <div v-if="can.manageServices || can.manageProjects || can.createResources || can.manageDestinations || can.manageHotlines" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            {{ adminT('cat_services_resources') }}
          </div>
          <button 
            v-if="can.manageServices"
            @click="selectSection('services')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'services' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Layers class="w-4 h-4" />
            <span>{{ adminT('nav_services') }}</span>
          </button>
          <button 
            v-if="can.manageProjects"
            @click="selectSection('projects')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'projects' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <HardHat class="w-4 h-4" />
            <span>{{ adminT('nav_projects') }}</span>
          </button>
          <button 
            v-if="can.createResources"
            @click="selectSection('resources')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'resources' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <FolderOpen class="w-4 h-4" />
            <span>{{ adminT('nav_resources') }}</span>
          </button>
          <button 
            v-if="can.manageDestinations"
            @click="selectSection('destinations')"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'destinations' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <Compass class="w-4 h-4 shrink-0" />
              <span>{{ adminT('nav_destinations') }}</span>
            </div>
            <span v-if="destinationsList.length" class="text-[11px] px-2 py-0.5 rounded-full bg-white/20 text-white font-mono font-bold">{{ destinationsList.length }}</span>
          </button>
          <button 
            v-if="can.manageHotlines"
            @click="selectSection('hotlines')"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'hotlines' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <PhoneCall class="w-4 h-4 shrink-0 text-red-400" />
              <span>{{ adminT('nav_hotlines') || '24/7 Hotlines' }}</span>
            </div>
            <span v-if="emergencyContactsList.length" class="text-[11px] px-2 py-0.5 rounded-full bg-red-500/30 text-red-200 font-mono font-bold">{{ emergencyContactsList.length }}</span>
          </button>

          <!-- Citizen Interaction Category -->
          <div v-if="can.viewInquiries" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            {{ adminT('cat_civic_feedback') }}
          </div>
          <button 
            v-if="can.viewInquiries"
            @click="selectSection('messages')"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'messages' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <MessageSquare class="w-4 h-4" />
              <span>{{ adminT('nav_messages') }}</span>
            </div>
            <span v-if="stats.unread_messages > 0" class="px-2 py-0.5 rounded-full bg-rose-500 text-white text-[10px] font-black">
              {{ stats.unread_messages }}
            </span>
          </button>

          <button 
            v-if="can.managePolls"
            @click="selectSection('polls')"
            :class="[
              'w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'polls' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <div class="flex items-center gap-3">
              <Vote class="w-4 h-4" />
              <span>{{ adminT('nav_polls') || 'Civic Opinion Polls' }}</span>
            </div>
            <span v-if="pollsList.length" class="text-[11px] px-2 py-0.5 rounded-full bg-white/20 text-white font-mono font-bold">{{ pollsList.length }}</span>
          </button>

          <!-- System Administration -->
          <div v-if="isSuperAdmin" class="pt-3 pb-1 px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">
            {{ adminT('cat_administration') }}
          </div>
          <button 
            v-if="isSuperAdmin"
            @click="selectSection('staff')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'staff' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <ShieldCheck class="w-4 h-4" />
            <span>{{ adminT('nav_staff') }}</span>
          </button>
          <button 
            v-if="isSuperAdmin"
            @click="selectSection('settings')"
            :class="[
              'w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all cursor-pointer text-left',
              activeSection === 'settings' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800'
            ]"
          >
            <Sliders class="w-4 h-4" />
            <span>{{ adminT('nav_settings') }}</span>
          </button>
        </nav>
      </aside>

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
          :newsCount="newsList.length"
          :galleryCount="galleryList.length"
          :resourcesCount="resourcesList.length"
          @navigate="selectSection"
          @openModal="openNewModal"
        />

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
                <button v-if="can.canDelete" @click="deleteItem('/admin/hero-slides', slide.id, slide.title_en || slide.title_am)" class="p-2 text-rose-500 hover:bg-rose-100 rounded-xl cursor-pointer" title="Delete Slide">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- MEDIA GALLERY MANAGEMENT -->
        <div v-else-if="activeSection === 'gallery'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                Media Gallery Manager
                <span class="text-blue-600 font-mono text-xl">({{ filteredGallery.length }})</span>
              </h2>
              <p class="text-xs text-slate-500">Manage high-resolution photography and video documentaries with category tagging and multilingual captions.</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="relative w-64">
                <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                  v-model="gallerySearch"
                  type="text"
                  placeholder="Filter gallery caption, category..."
                  class="w-full pl-9 pr-3 py-2 rounded-2xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-xs"
                />
              </div>
              <button @click="editingId = null; modalType = 'gallery'" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                <Plus class="w-4 h-4" />
                <span>Add Media</span>
              </button>
            </div>
          </div>

          <div v-if="filteredGallery.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No media items found matching "<span class="font-bold text-slate-600">{{ gallerySearch }}</span>".
          </div>
          <div v-else class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              <div v-for="item in paginatedGallery" :key="item.id" class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col justify-between">
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
                    <button v-if="can.deleteMedia" @click="deleteItem('/admin/gallery', item.id, item.caption_en || item.caption_am)" class="p-1.5 text-rose-500 hover:bg-rose-100 rounded-lg cursor-pointer" title="Delete Item">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Gallery Pagination Controls Bar -->
            <div v-if="filteredGallery.length > galleryPerPage" class="p-4 px-6 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
              <div>
                Showing <span class="font-bold text-slate-900">{{ (galleryPage - 1) * galleryPerPage + 1 }}</span> to 
                <span class="font-bold text-slate-900">{{ Math.min(galleryPage * galleryPerPage, filteredGallery.length) }}</span> of 
                <span class="font-bold text-slate-900">{{ filteredGallery.length }}</span> items
              </div>
              <div class="flex items-center gap-1.5">
                <button 
                  :disabled="galleryPage === 1" 
                  @click="galleryPage--"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Previous
                </button>
                <button 
                  v-for="p in totalGalleryPages" 
                  :key="p"
                  @click="galleryPage = p"
                  :class="[
                    'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
                    galleryPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
                  ]"
                >
                  {{ p }}
                </button>
                <button 
                  :disabled="galleryPage === totalGalleryPages" 
                  @click="galleryPage++"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>

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
          @openCreate="openNewModal('tender')"
          @openEdit="(item) => editItem('tender', item)"
          @deleteItem="(id) => deleteItem('/admin/tenders', id, 'Tender Notice')"
        />

        <!-- 4. JOB VACANCIES -->
        <div v-else-if="activeSection === 'vacancies'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                Civil Service Vacancies & Careers
                <span class="text-emerald-600 font-mono text-xl">({{ filteredVacancies.length }})</span>
              </h2>
              <p class="text-xs text-slate-500">Post employment openings, salary scales, and application guidelines.</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="relative w-64">
                <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                  v-model="vacanciesSearch"
                  type="text"
                  placeholder="Filter by code, title, type..."
                  class="w-full pl-9 pr-3 py-2 rounded-2xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none shadow-xs"
                />
              </div>
              <button @click="openNewModal('vacancy')" class="px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                <Plus class="w-4 h-4" />
                <span>Post Job Vacancy</span>
              </button>
            </div>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div v-if="filteredVacancies.length === 0" class="p-12 text-center text-slate-400 text-xs">
              No vacancies found matching "<span class="font-bold text-slate-600">{{ vacanciesSearch }}</span>".
            </div>
            <div v-else class="divide-y divide-slate-100">
              <div v-for="job in paginatedVacancies" :key="job.id" class="p-6 flex items-center justify-between gap-4 hover:bg-slate-50/50">
                <div>
                  <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase mr-2">{{ job.employment_type }}</span>
                  <span 
                    :class="[
                      'px-2 py-0.5 rounded-full text-[10px] font-black uppercase mr-2 border',
                      (job.status === 'closed' || new Date(job.deadline.includes('T') ? job.deadline : job.deadline + 'T23:59:59') <= new Date())
                        ? 'bg-rose-50 text-rose-700 border-rose-200' 
                        : 'bg-emerald-50 text-emerald-700 border-emerald-200'
                    ]"
                  >
                    {{ (job.status === 'closed' || new Date(job.deadline.includes('T') ? job.deadline : job.deadline + 'T23:59:59') <= new Date()) ? 'Closed' : 'Open' }}
                  </span>
                  <span class="text-xs font-mono text-slate-500 font-bold">Code: {{ job.position_code }}</span>
                  <h4 class="font-bold text-base text-slate-900 mt-1">{{ job.title_en || job.title_am }}</h4>
                  <div class="text-xs text-slate-500 mt-1">Scale: {{ job.salary_grade }} | Deadline: {{ job.deadline }}</div>
                </div>
                <div class="flex items-center gap-2">
                  <button @click="editItem('vacancy', job)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Vacancy">
                    <Edit class="w-4 h-4" />
                  </button>
                  <button v-if="can.canDelete" @click="deleteItem('/admin/vacancies', job.id, job.title_en || job.title_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Vacancy">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Vacancies Pagination Controls Bar -->
            <div v-if="filteredVacancies.length > vacanciesPerPage" class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
              <div>
                Showing <span class="font-bold text-slate-900">{{ (vacanciesPage - 1) * vacanciesPerPage + 1 }}</span> to 
                <span class="font-bold text-slate-900">{{ Math.min(vacanciesPage * vacanciesPerPage, filteredVacancies.length) }}</span> of 
                <span class="font-bold text-slate-900">{{ filteredVacancies.length }}</span> vacancies
              </div>
              <div class="flex items-center gap-1.5">
                <button 
                  :disabled="vacanciesPage === 1" 
                  @click="vacanciesPage--"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Previous
                </button>
                <button 
                  v-for="p in totalVacanciesPages" 
                  :key="p"
                  @click="vacanciesPage = p"
                  :class="[
                    'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
                    vacanciesPage === p ? 'bg-emerald-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
                  ]"
                >
                  {{ p }}
                </button>
                <button 
                  :disabled="vacanciesPage === totalVacanciesPages" 
                  @click="vacanciesPage++"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 5. MAYOR PROFILE & KEYNOTE -->
        <div v-else-if="activeSection === 'mayor'" class="space-y-6 max-w-4xl">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Office of the Mayor & Keynote</h2>
            <p class="text-xs text-slate-500">Update the Mayor's official portrait, welcome letter, vision, and executive biography.</p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6 pb-6 border-b border-slate-100">
              <img v-if="mayorData.portrait_path" :src="mayorData.portrait_path" alt="Mayor" class="w-28 h-28 object-cover object-top rounded-2xl border-2 border-blue-600 shadow-md shrink-0" />
              <div v-else class="w-28 h-28 rounded-2xl border-2 border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-slate-400 shrink-0">
                <Users class="w-10 h-10 opacity-30 text-blue-600" />
                <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase">No Photo</span>
              </div>
              <div class="flex-1 space-y-2">
                <h3 class="text-xl font-black text-slate-900">{{ mayorData.name_en || mayorData.name_am }}</h3>
                <p class="text-xs text-slate-500 font-mono">Current Term: {{ mayorData.term_start || '2021' }} - {{ mayorData.term_end || 'Present' }}</p>

                <!-- Mayor Photo Upload Input -->
                <div class="pt-2">
                  <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Update Mayoral Portrait</label>
                  <div class="flex items-center gap-3 max-w-md">
                    <input 
                      v-model="mayorData.portrait_path" 
                      type="text" 
                      placeholder="/uploads/... or click Upload Photo" 
                      class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono" 
                    />
                    <label class="px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold flex items-center gap-1.5 cursor-pointer shrink-0 transition-colors">
                      <Upload class="w-4 h-4" />
                      <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                      <input type="file" @change="uploadFileFor($event, mayorData, 'portrait_path')" class="hidden" accept="image/*" />
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mayor Full Name (Amharic - አማርኛ) *</label>
                <input v-model="mayorData.name_am" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Mayor Full Name (English)</label>
                <input v-model="mayorData.name_en" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Term Start Year</label>
                <input v-model="mayorData.term_start" type="text" placeholder="2021" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Term End Year</label>
                <input v-model="mayorData.term_end" type="text" placeholder="Present" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keynote Welcome Message (Amharic - የእንኳን ደህና መጡ መልእክት) *</label>
              <textarea v-model="mayorData.welcome_message_am" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keynote Welcome Message (English)</label>
              <textarea v-model="mayorData.welcome_message_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Executive Biography (Amharic)</label>
                <textarea v-model="mayorData.biography_am" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Executive Biography (English)</label>
                <textarea v-model="mayorData.biography_en" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
              </div>
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
              <h2 class="text-2xl font-black text-slate-900">Cabinet & City Council Members</h2>
              <p class="text-xs text-slate-500">Manage municipal cabinet portfolios and council committee heads.</p>
            </div>
            <button @click="openNewModal('leadership')" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Member</span>
            </button>
          </div>

          <div v-if="leadershipList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No leadership members cataloged yet.
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="m in leadershipList" :key="m.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-4 mb-3">
                  <img 
                    v-if="m.photo_path"
                    :src="m.photo_path" 
                    :alt="m.name_en || m.name_am" 
                    class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-2xs shrink-0" 
                  />
                  <div v-else class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                    <Users class="w-6 h-6 text-blue-600" />
                  </div>
                  <div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase" :class="m.type === 'cabinet' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700'">
                      {{ m.type }}
                    </span>
                    <h4 class="font-extrabold text-base text-slate-900 mt-1 leading-snug">{{ m.name_en || m.name_am }}</h4>
                  </div>
                </div>
                <p class="text-xs font-semibold text-blue-600">{{ m.position_en || m.position_am }}</p>
                <div class="text-xs text-slate-500 font-mono mt-2">{{ m.phone || 'N/A' }} | {{ m.email || 'N/A' }}</div>
              </div>
              <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button @click="editItem('leadership', m)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl cursor-pointer" title="Edit Member">
                  <Edit class="w-4 h-4" />
                </button>
                <button v-if="isSuperAdmin" @click="deleteItem('/admin/leadership', m.id, m.name_en || m.name_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Member">
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 7. 22 MUNICIPAL OFFICES -->
        <div v-else-if="activeSection === 'offices'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                Municipal Offices & Public Directory 
                <span class="text-blue-600 font-mono text-xl">({{ filteredOffices.length }})</span>
              </h2>
              <p class="text-xs text-slate-500">Maintain official directory, building locations, room numbers and direct phone lines.</p>
            </div>
            <div class="flex items-center gap-3">
              <!-- Live Filter Search Bar -->
              <div class="relative w-64">
                <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                  v-model="officeSearch"
                  type="text"
                  placeholder="Filter by name, room, phone..."
                  class="w-full pl-9 pr-3 py-2 rounded-2xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-xs"
                />
              </div>
              <button @click="openNewModal('office')" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                <Plus class="w-4 h-4" />
                <span>Add Office</span>
              </button>
            </div>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div v-if="filteredOffices.length === 0" class="p-12 text-center text-slate-400 text-xs">
              No municipal offices found matching "<span class="font-bold text-slate-600">{{ officeSearch }}</span>".
            </div>
            <div v-else class="divide-y divide-slate-100">
              <div v-for="off in paginatedOffices" :key="off.id" class="p-6 flex items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
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
                  <button v-if="isSuperAdmin" @click="deleteItem('/admin/offices', off.id, off.name_en || off.name_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Office">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Offices Pagination Controls Bar -->
            <div v-if="filteredOffices.length > officePerPage" class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
              <div>
                Showing <span class="font-bold text-slate-900">{{ (officePage - 1) * officePerPage + 1 }}</span> to 
                <span class="font-bold text-slate-900">{{ Math.min(officePage * officePerPage, filteredOffices.length) }}</span> of 
                <span class="font-bold text-slate-900">{{ filteredOffices.length }}</span> offices
              </div>
              <div class="flex items-center gap-1.5">
                <button 
                  :disabled="officePage === 1" 
                  @click="officePage--"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Previous
                </button>
                <button 
                  v-for="p in totalOfficePages" 
                  :key="p"
                  @click="officePage = p"
                  :class="[
                    'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
                    officePage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
                  ]"
                >
                  {{ p }}
                </button>
                <button 
                  :disabled="officePage === totalOfficePages" 
                  @click="officePage++"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 8. SERVICES (TEXT & STEP) -->
        <div v-else-if="activeSection === 'services'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Municipal Citizen Services</h2>
              <p class="text-xs text-slate-500">Configure text service guidelines and step-by-step civic procedural walkthroughs.</p>
            </div>
            <button @click="openNewModal('service')" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
              <Plus class="w-4 h-4" />
              <span>Add Service</span>
            </button>
          </div>

          <div v-if="servicesList.length === 0" class="bg-white p-12 text-center rounded-3xl border border-slate-200 text-slate-400 text-sm">
            No citizen services cataloged yet.
          </div>
          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="srv in servicesList" :key="srv.id" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
              <div>
                <div class="flex items-center justify-between mb-2">
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase" :class="srv.type === 'step' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700'">
                    {{ srv.type === 'step' ? 'Step-by-Step Walkthrough' : 'Direct Guide' }}
                  </span>
                  <div class="flex items-center gap-1">
                    <button @click="editItem('service', srv)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg cursor-pointer" title="Edit Service">
                      <Edit class="w-4 h-4" />
                    </button>
                    <button v-if="can.canDelete" @click="deleteItem('/admin/services', srv.id, srv.title_en || srv.title_am)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg cursor-pointer" title="Delete Service">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </div>
                <h4 class="font-extrabold text-base text-slate-900 leading-snug">{{ srv.title_en || srv.title_am }}</h4>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Amharic: {{ srv.title_am }}</p>
                <p class="text-xs text-slate-600 mt-2 line-clamp-2">{{ srv.summary_en || srv.summary_am }}</p>
              </div>

              <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-mono">
                <span>Fee: {{ srv.fee_amount ? srv.fee_amount + ' ETB' : 'Free' }}</span>
                <span>Time: {{ srv.estimated_time_am || '1-3 Days' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 9. MEGA INFRASTRUCTURE PROJECTS -->
        <div v-else-if="activeSection === 'projects'" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-2xl font-black text-slate-900">Infrastructure & Urban Development Projects</h2>
              <p class="text-xs text-slate-500">Track construction progress %, contractors, and capital allocations.</p>
            </div>
            <button @click="openNewModal('project')" class="px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md">
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
                    <button v-if="isSuperAdmin" @click="deleteItem('/admin/projects', proj.id, proj.title_en || proj.title_am)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg cursor-pointer" title="Delete Project">
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
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <h2 class="text-2xl font-black text-slate-900">
                Public Directives & Document Library
                <span class="text-blue-600 font-mono text-xl">({{ filteredResources.length }})</span>
              </h2>
              <p class="text-xs text-slate-500">Upload official bylaws, directives, annual budgets, and master plan maps.</p>
            </div>
            <div class="flex items-center gap-3">
              <div class="relative w-64">
                <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                  v-model="resourcesSearch"
                  type="text"
                  placeholder="Filter by title, year, category..."
                  class="w-full pl-9 pr-3 py-2 rounded-2xl bg-white border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none shadow-xs"
                />
              </div>
              <button @click="openNewModal('resource')" class="px-5 py-2.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                <Plus class="w-4 h-4" />
                <span>Catalog Directive</span>
              </button>
            </div>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div v-if="filteredResources.length === 0" class="p-12 text-center text-slate-400 text-xs">
              No directives or resources found matching "<span class="font-bold text-slate-600">{{ resourcesSearch }}</span>".
            </div>
            <div v-else class="divide-y divide-slate-100">
              <div v-for="res in paginatedResources" :key="res.id" class="p-6 flex items-center justify-between gap-4 hover:bg-slate-50/50">
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
                  <button v-if="can.deleteResources" @click="deleteItem('/admin/resources', res.id, res.title_en || res.title_am)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl cursor-pointer" title="Delete Directive">
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            <!-- Resources Pagination Controls Bar -->
            <div v-if="filteredResources.length > resourcesPerPage" class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600">
              <div>
                Showing <span class="font-bold text-slate-900">{{ (resourcesPage - 1) * resourcesPerPage + 1 }}</span> to 
                <span class="font-bold text-slate-900">{{ Math.min(resourcesPage * resourcesPerPage, filteredResources.length) }}</span> of 
                <span class="font-bold text-slate-900">{{ filteredResources.length }}</span> directives
              </div>
              <div class="flex items-center gap-1.5">
                <button 
                  :disabled="resourcesPage === 1" 
                  @click="resourcesPage--"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Previous
                </button>
                <button 
                  v-for="p in totalResourcesPages" 
                  :key="p"
                  @click="resourcesPage = p"
                  :class="[
                    'w-8 h-8 rounded-xl font-bold transition-colors cursor-pointer text-xs',
                    resourcesPage === p ? 'bg-blue-600 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'
                  ]"
                >
                  {{ p }}
                </button>
                <button 
                  :disabled="resourcesPage === totalResourcesPages" 
                  @click="resourcesPage++"
                  class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- DESTINATIONS & TOURISM SPOTLIGHT -->
        <DestinationsTab
          v-else-if="activeSection === 'destinations'"
          :destinationsList="destinationsList"
          :isEditor="isEditor"
          @openCreate="openNewModal('destination')"
          @openEdit="(item) => editItem('destination', item)"
          @deleteItem="(id) => deleteItem('/admin/destinations', id, 'Destination')"
        />

        <!-- 11. CITIZEN FEEDBACK INBOX -->
        <MessagesTab
          v-else-if="activeSection === 'messages'"
          :messagesList="messagesList"
          :isEditor="isEditor"
          @updateStatus="({ id, status }) => updateMessageStatus(id, status)"
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

        <!-- 13. SETTINGS & MAINTENANCE MODE -->
        <div v-else-if="activeSection === 'settings'" class="space-y-6 max-w-4xl">
          <div>
            <h2 class="text-2xl font-black text-slate-900">Portal System Settings & Maintenance</h2>
            <p class="text-xs text-slate-500">Configure public emergency notice banner and site maintenance toggle.</p>
          </div>

          <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm space-y-6">
            <!-- Hotlines Management Quick Card in Settings -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl border border-red-200 shadow-2xs">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                  <PhoneCall class="w-5 h-5" />
                </div>
                <div>
                  <h4 class="font-extrabold text-sm text-slate-900">24/7 Emergency Dispatch Hotlines</h4>
                  <p class="text-xs text-slate-500">Manage telephone numbers, dispatch lines, icons and colors for Police, Fire, Ambulance and utilities.</p>
                </div>
              </div>
              <button 
                type="button"
                @click="selectSection('hotlines')"
                class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold cursor-pointer transition-colors shadow-xs shrink-0 flex items-center gap-1.5 self-start sm:self-auto"
              >
                <span>Manage Hotlines ({{ emergencyContactsList.length }})</span>
                <ChevronRight class="w-3.5 h-3.5" />
              </button>
            </div>

            <!-- Maintenance Mode Toggle Switch -->
            <div 
              class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-2xl border transition-all"
              :class="siteSettings.is_maintenance ? 'bg-amber-500/10 border-amber-500/40 shadow-xs' : 'bg-slate-50 border-slate-200'"
            >
              <div class="space-y-1">
                <div class="flex items-center gap-2">
                  <h4 class="font-extrabold text-sm" :class="siteSettings.is_maintenance ? 'text-amber-900' : 'text-slate-900'">
                    Emergency Maintenance Mode
                  </h4>
                  <span 
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-colors"
                    :class="siteSettings.is_maintenance ? 'bg-amber-600 text-white shadow-2xs' : 'bg-slate-200 text-slate-700'"
                  >
                    {{ siteSettings.is_maintenance ? 'Active (Live on Portal)' : 'Disabled (Portal Public)' }}
                  </span>
                </div>
                <p class="text-xs max-w-xl" :class="siteSettings.is_maintenance ? 'text-amber-800' : 'text-slate-500'">
                  When enabled, public visitors are shown an emergency maintenance holding screen. The Admin CMS remains fully accessible.
                </p>
              </div>

              <!-- Interactive Toggle Switch -->
              <button 
                type="button"
                @click="siteSettings.is_maintenance = !siteSettings.is_maintenance"
                class="relative inline-flex h-8 w-16 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none shadow-xs"
                :class="siteSettings.is_maintenance ? 'bg-amber-600' : 'bg-slate-300'"
                role="switch"
                :aria-checked="siteSettings.is_maintenance"
              >
                <span 
                  class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                  :class="siteSettings.is_maintenance ? 'translate-x-8' : 'translate-x-0'"
                />
              </button>
            </div>

            <!-- 📍 Municipal Geographic Location & Interactive Map Coordinates -->
            <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-2xs space-y-5">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
                    <MapPin class="w-5 h-5" />
                  </div>
                  <div>
                    <h4 class="font-extrabold text-sm text-slate-900">Municipal Headquarters Geographic Location & Map Pin</h4>
                    <p class="text-xs text-slate-500">Manage GPS coordinates shown on the public Contact page and Footer map embed.</p>
                  </div>
                </div>

                <!-- GPS Auto-Detect Button -->
                <button 
                  type="button"
                  @click="detectLiveLocation"
                  :disabled="detectingLocation"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all cursor-pointer disabled:opacity-50 shrink-0"
                >
                  <Navigation class="w-4 h-4" :class="detectingLocation ? 'animate-spin' : ''" />
                  <span>{{ detectingLocation ? 'Detecting GPS...' : '📍 Detect Live GPS Location' }}</span>
                </button>
              </div>

              <!-- Coordinates Grid -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Latitude (° N) *
                  </label>
                  <input 
                    v-model.number="siteSettings.latitude" 
                    type="number" 
                    step="0.0000001" 
                    placeholder="11.0845000"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  />
                  <span class="text-[10px] text-slate-400 mt-0.5 block">Kombolcha default: 11.0845</span>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Longitude (° E) *
                  </label>
                  <input 
                    v-model.number="siteSettings.longitude" 
                    type="number" 
                    step="0.0000001" 
                    placeholder="39.7390000"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  />
                  <span class="text-[10px] text-slate-400 mt-0.5 block">Kombolcha default: 39.7390</span>
                </div>

                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                    Map Zoom Level
                  </label>
                  <select 
                    v-model.number="siteSettings.map_zoom"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option :value="13">13 - City Overview</option>
                    <option :value="14">14 - District Level</option>
                    <option :value="15">15 - Street & Building (Recommended)</option>
                    <option :value="16">16 - Close Block Zoom</option>
                    <option :value="17">17 - High Detail Building</option>
                  </select>
                  <span class="text-[10px] text-slate-400 mt-0.5 block">Recommended: 15</span>
                </div>
              </div>

              <!-- Physical Address in 3 Languages -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (Amharic)</label>
                  <input v-model="siteSettings.map_address_am" type="text" placeholder="ዋናው አስተዳደር ኮሪደር፣ ቀበሌ 02፣ ኮምቦልቻ" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (English)</label>
                  <input v-model="siteSettings.map_address_en" type="text" placeholder="Main Administrative Corridor, Kebele 02, Kombolcha" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Address (Afaan Oromoo)</label>
                  <input v-model="siteSettings.map_address_om" type="text" placeholder="Kooridorii Bulchiinsa Magaalaa, Ganda 02, Kombolcha" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs" />
                </div>
              </div>

              <!-- Live Interactive Admin Map Preview -->
              <div class="pt-2">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-2">
                  <div class="flex items-center gap-1.5">
                    <Eye class="w-3.5 h-3.5 text-blue-600" />
                    <span>Live GPS Map Pin Preview</span>
                  </div>
                  <a 
                    :href="'https://www.google.com/maps/search/?api=1&query=' + (siteSettings.latitude || 11.0845) + ',' + (siteSettings.longitude || 39.7390)" 
                    target="_blank"
                    class="text-blue-600 hover:text-blue-800 text-[11px] font-bold hover:underline inline-flex items-center gap-1"
                  >
                    <span>Verify in Google Maps</span>
                    <ExternalLink class="w-3 h-3" />
                  </a>
                </div>

                <div class="h-64 w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 relative">
                  <iframe 
                    v-if="siteSettings.latitude && siteSettings.longitude"
                    class="w-full h-full border-0"
                    :src="`https://www.openstreetmap.org/export/embed.html?bbox=${Number(siteSettings.longitude) - 0.008}%2C${Number(siteSettings.latitude) - 0.005}%2C${Number(siteSettings.longitude) + 0.008}%2C${Number(siteSettings.latitude) + 0.005}&layer=mapnik&marker=${siteSettings.latitude}%2C${siteSettings.longitude}`"
                    loading="lazy"
                  ></iframe>
                  <div v-else class="w-full h-full flex items-center justify-center text-xs text-slate-400">
                    No coordinates configured yet.
                  </div>
                </div>
              </div>
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

        <!-- 14. 24/7 EMERGENCY HOTLINES MANAGEMENT -->
        <HotlinesTab
          v-else-if="activeSection === 'hotlines'"
          :hotlinesList="emergencyContactsList"
          :isEditor="isEditor"
          @openCreate="openNewModal('emergency_contact')"
          @openEdit="(item) => editItem('emergency_contact', item)"
          @deleteItem="(id) => deleteItem('/admin/emergency-contacts', id, 'Emergency Hotline')"
        />

        <div v-else-if="activeSection === 'polls'" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
              <div class="flex items-center gap-2 text-blue-600 font-extrabold text-xs uppercase tracking-wider mb-1">
                <Vote class="w-4 h-4 shrink-0" />
                <span>Citizen Opinion & Priority Ballots</span>
              </div>
              <h2 class="text-2xl font-black text-slate-900">{{ adminT('nav_polls') || 'Civic Opinion Polls' }}</h2>
              <p class="text-xs text-slate-500 mt-0.5">
                Create new citizen infrastructure polls, manage expiration dates, reset test/demo data, and view real-time verified ballot counts.
              </p>
            </div>
            <button 
              @click="openNewModal('poll')" 
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
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
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
                  @click="resetPollVotes(pollsList.find(p => p.is_active))"
                  class="px-4 py-2.5 rounded-xl bg-amber-500/20 hover:bg-amber-500/30 text-amber-200 border border-amber-500/30 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                  title="Purge demo votes to start gathering 100% real verified citizen data"
                >
                  <RefreshCw class="w-3.5 h-3.5" />
                  <span>Reset Demo / Votes to 0</span>
                </button>

                <button 
                  @click="editItem('poll', pollsList.find(p => p.is_active))"
                  class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                >
                  <Edit class="w-3.5 h-3.5" />
                  <span>Edit Active Poll</span>
                </button>
              </div>
            </div>
          </div>

          <!-- All Polls Table -->
          <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
              <div>
                <h3 class="font-black text-sm text-slate-900">All Civic Polls & Ballots</h3>
                <p class="text-xs text-slate-400">Total of {{ pollsList.length }} polls registered in the municipal database.</p>
              </div>
              <button @click="fetchAllAdminData" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer hover:bg-slate-50" title="Refresh Polls">
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
                        @click="togglePollActive(item)"
                        class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider cursor-pointer transition-all border"
                        :class="item.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'"
                      >
                        {{ item.is_active ? '● Active Live' : '○ Inactive' }}
                      </button>
                    </td>
                    <td class="p-4 text-right">
                      <div class="flex items-center justify-end gap-1.5">
                        <button 
                          @click="resetPollVotes(item)" 
                          class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition-colors cursor-pointer"
                          title="Reset votes to 0 for real data"
                        >
                          <RefreshCw class="w-4 h-4" />
                        </button>
                        <button 
                          @click="editItem('poll', item)" 
                          class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors cursor-pointer"
                          title="Edit Poll"
                        >
                          <Edit class="w-4 h-4" />
                        </button>
                        <button 
                          @click="deleteItem('/admin/polls', item.id, item.question_en || item.question_am)" 
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
                  <option value="open">Open (ንቁ / ክፍት)</option>
                  <option value="closed">Closed (የተዘጋ)</option>
                  <option value="cancelled">Cancelled (የተሰረዘ)</option>
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

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="modalType = ''; editingId = null" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 cursor-pointer">Cancel</button>
            <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer disabled:opacity-50">
              {{ submitting ? 'Saving...' : (isAuthor && modalType === 'news' ? (editingId ? 'Save & Submit for Review' : 'Submit for Editorial Review') : (editingId ? 'Update & Save Changes' : 'Confirm & Publish Live')) }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- PROFILE & SECURITY SETTINGS MODAL (For All Staff: Authors, Editors, Super Admins) -->
    <div v-if="profileModalOpen" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50">
          <div>
            <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
              <User class="w-5 h-5 text-blue-600" />
              <span>Account Profile & Security</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Manage your personal credentials, contact info, avatar and password.</p>
          </div>
          <button @click="profileModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 px-6 bg-white">
          <button 
            type="button"
            @click="profileActiveTab = 'details'"
            :class="[
              'py-3.5 px-4 font-bold text-xs border-b-2 transition-colors cursor-pointer flex items-center gap-2',
              profileActiveTab === 'details' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-900'
            ]"
          >
            <User class="w-4 h-4" />
            <span>Profile Details</span>
          </button>
          <button 
            type="button"
            @click="profileActiveTab = 'password'"
            :class="[
              'py-3.5 px-4 font-bold text-xs border-b-2 transition-colors cursor-pointer flex items-center gap-2',
              profileActiveTab === 'password' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-900'
            ]"
          >
            <Lock class="w-4 h-4" />
            <span>Change Password</span>
          </button>
        </div>

        <!-- Tab 1: Profile Details Form -->
        <form v-if="profileActiveTab === 'details'" @submit.prevent="handleSaveProfile" class="p-6 space-y-4">
          <!-- Avatar Preview & Upload -->
          <div class="flex items-center gap-5 pb-4 border-b border-slate-100">
            <div class="w-20 h-20 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0 relative group">
              <img v-if="profileForm.avatar_path" :src="profileForm.avatar_path" class="w-full h-full object-cover" />
              <div v-else class="text-2xl font-black text-slate-400">
                {{ (profileForm.name || 'U').substring(0, 2).toUpperCase() }}
              </div>
            </div>
            <div class="space-y-1.5 flex-grow">
              <label class="block text-xs font-bold text-slate-700 uppercase">Profile Photo / Avatar</label>
              <div class="flex gap-2">
                <label class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold cursor-pointer transition-colors flex items-center gap-1.5">
                  <Camera class="w-3.5 h-3.5" />
                  <span>{{ uploadingFile ? 'Uploading...' : 'Upload Photo' }}</span>
                  <input type="file" @change="uploadFileFor($event, profileForm, 'avatar_path')" accept="image/*" class="hidden" />
                </label>
                <button v-if="profileForm.avatar_path" type="button" @click="profileForm.avatar_path = ''" class="px-2.5 py-1.5 rounded-xl text-rose-600 hover:bg-rose-50 text-xs font-bold">
                  Remove
                </button>
              </div>
              <input v-model="profileForm.avatar_path" type="text" placeholder="Or enter image URL..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Full Name</label>
              <input v-model="profileForm.name" type="text" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Official Email</label>
              <input v-model="profileForm.email" type="email" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
              <input v-model="profileForm.phone" type="text" placeholder="+251 9X XXX XXXX" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned Role</label>
              <div class="px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-200 text-xs font-bold uppercase text-slate-700">
                {{ roleLabel }}
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="profileModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 cursor-pointer">Cancel</button>
            <button type="submit" :disabled="profileLoading || uploadingFile" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer disabled:opacity-50">
              {{ profileLoading ? 'Saving...' : 'Save Profile Changes' }}
            </button>
          </div>
        </form>

        <!-- Tab 2: Change Password Form -->
        <form v-else-if="profileActiveTab === 'password'" @submit.prevent="handleChangePassword" class="p-6 space-y-4">
          <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
            <AlertCircle class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
            <span>For security reasons, changing your password requires verifying your current password. Passwords must be at least 8 characters.</span>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Password</label>
            <input v-model="passwordForm.current_password" type="password" required placeholder="Enter current password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">New Password</label>
              <input v-model="passwordForm.new_password" type="password" required minlength="8" placeholder="At least 8 characters" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password</label>
              <input v-model="passwordForm.new_password_confirmation" type="password" required minlength="8" placeholder="Repeat new password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="profileModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 cursor-pointer">Cancel</button>
            <button type="submit" :disabled="profileLoading" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold cursor-pointer disabled:opacity-50">
              {{ profileLoading ? 'Updating...' : 'Update Password' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- SUPER ADMIN RESET STAFF PASSWORD OVERRIDE MODAL -->
    <div v-if="resetStaffModalOpen && targetStaffUser" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-amber-50/50 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
              <KeyRound class="w-5 h-5" />
            </div>
            <div>
              <h3 class="text-base font-black text-slate-900">Reset Staff Password</h3>
              <p class="text-xs text-slate-500">Super Administrator override</p>
            </div>
          </div>
          <button @click="resetStaffModalOpen = false" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl cursor-pointer">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="handleAdminResetPassword" class="p-6 space-y-4">
          <!-- Target Staff Info Card -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs space-y-1">
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Staff Name:</span>
              <span class="font-extrabold text-slate-900">{{ targetStaffUser.name }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Email:</span>
              <span class="font-mono text-slate-700">{{ targetStaffUser.email }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Role:</span>
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-100 text-blue-700">{{ targetStaffUser.role }}</span>
            </div>
          </div>

          <div class="space-y-1">
            <div class="flex items-center justify-between">
              <label class="block text-xs font-bold text-slate-700 uppercase">New Password</label>
              <button type="button" @click="generateRandomPassword" class="text-xs text-blue-600 font-bold hover:underline flex items-center gap-1 cursor-pointer">
                <span>Generate Strong Password</span>
              </button>
            </div>
            <input v-model="resetStaffForm.password" type="text" required minlength="8" placeholder="Enter or generate password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Confirm New Password</label>
            <input v-model="resetStaffForm.password_confirmation" type="text" required minlength="8" placeholder="Repeat new password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono" />
          </div>

          <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-[11px] leading-relaxed">
            <strong>Security Notice:</strong> Performing this reset will instantly terminate all existing login sessions for this account, requiring the staff member to sign in with this new password.
          </div>

          <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
            <button type="button" @click="resetStaffModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 cursor-pointer">Cancel</button>
            <button type="submit" :disabled="resetStaffLoading" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold cursor-pointer disabled:opacity-50">
              {{ resetStaffLoading ? 'Resetting...' : 'Set New Password' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
