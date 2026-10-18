<script setup>
import { ref, computed, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Pin, 
  MessageSquare, 
  Clock, 
  Building2, 
  ShieldCheck, 
  FileText,
  UserCheck,
  Search,
  Filter,
  Landmark,
  AlertCircle,
  Lock,
  ChevronRight,
  Info
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const topics = ref([])
const posts = ref([])
const searchQuery = ref('')
const selectedCategory = ref('all')

const fetchBoardData = async () => {
  loading.value = true
  try {
    const res = await api.getInternalBoard()
    topics.value = res.data?.topics || []
    posts.value = res.data?.posts || []
  } catch (err) {
    console.error('Failed to load internal board:', err)
  } finally {
    loading.value = false
  }
}

const getTopicTitle = (t) => {
  const lang = langStore.currentLang
  if (lang === 'am') return t.title_am || t.title_en
  if (lang === 'om') return t.title_om || t.title_en
  return t.title_en || t.title_am
}

const getPostContent = (p) => {
  const lang = langStore.currentLang
  if (lang === 'am') return p.content_am || p.content_en
  if (lang === 'om') return p.content_om || p.content_en
  return p.content_en || p.content_am
}

const categories = computed(() => {
  const set = new Set(topics.value.map(t => t.category).filter(Boolean))
  return ['all', ...Array.from(set)]
})

const filteredTopics = computed(() => {
  return topics.value.filter(t => {
    // Category filter
    if (selectedCategory.value !== 'all' && t.category !== selectedCategory.value) {
      return false
    }

    // Search query filter
    const q = searchQuery.value.toLowerCase().trim()
    if (q) {
      const title = (getTopicTitle(t) || '').toLowerCase()
      const cat = (t.category || '').toLowerCase()
      const hasMatchingPost = posts.value.some(p => 
        p.topic_id === t.id && 
        ((p.author_name || '').toLowerCase().includes(q) || 
         (p.author_department || '').toLowerCase().includes(q) || 
         (getPostContent(p) || '').toLowerCase().includes(q))
      )
      if (!title.includes(q) && !cat.includes(q) && !hasMatchingPost) return false
    }

    return true
  })
})

onMounted(() => {
  fetchBoardData()
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl">
      <div class="w-full max-w-[1700px] mx-auto">
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/40 text-xs font-black uppercase tracking-widest">
            <Lock class="w-3.5 h-3.5 text-amber-400" />
            Internal Municipal Staff Portal
          </span>
          <span class="text-xs text-slate-400 font-mono">Official Circulars & Executive Directives</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'የከተማው ሰራተኞች ውስጣዊ የማስታወቂያ ሰሌዳ' : 'Administrative Circulars & Staff Bulletin' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ ከንቲባ ጽ/ቤት፣ በ 22ቱ መምሪያዎች እና በ 4ቱ ክፍለ ከተሞች መካከል የሚተላለፉ ይፋዊ የስራ መመሪያዎች፣ የአመራር ግምገማ ስብሰባዎችና ማስታወሻዎች።'
            : 'Official inter-departmental communications, appraisal meeting notices, and administrative directives across municipal bureaus and sub-cities.'
          }}
        </p>

        <!-- Board Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Active Circular Topics</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ topics.length }} Directives</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Connected Administrative Memos</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-blue-300">{{ posts.length }} Memos</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Issuing Authorities</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">Cabinet & Bureaus</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Security Standard</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">Official Municipal Record</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        
        <!-- Search Input -->
        <div class="relative flex-1 min-w-[280px]">
          <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="langStore.currentLang === 'am' ? 'በማስታወቂያ ርዕስ፣ በመምሪያ ወይም በፀሐፊ ፈልግ...' : 'Search circulars by title, bureau, or author...'" 
            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
          />
        </div>

        <!-- Category Select -->
        <div class="flex items-center gap-2">
          <select 
            v-model="selectedCategory" 
            class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-blue-600 cursor-pointer"
          >
            <option value="all">All Directive Categories</option>
            <option v-for="cat in categories.filter(c => c !== 'all')" :key="cat" :value="cat">
              {{ cat }}
            </option>
          </select>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="space-y-6">
        <div v-for="i in 3" :key="i" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs animate-pulse h-48"></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredTopics.length === 0" class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <FileText class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No active circulars on the board</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
          Official departmental memos will appear here once issued by municipal authorities.
        </p>
      </div>

      <!-- Real Database Topics List -->
      <div v-else class="space-y-8">
        <div 
          v-for="topic in filteredTopics" 
          :key="topic.id"
          class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-xs hover:border-amber-400 hover:shadow-xl transition-all duration-300"
        >
          <!-- Topic Header Bar -->
          <div class="bg-gradient-to-r from-slate-900 to-[#071324] text-white px-6 sm:px-8 py-5 border-b border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
              <span 
                v-if="topic.is_pinned" 
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400 text-slate-950 text-xs font-black uppercase tracking-wider shadow-xs"
              >
                <Pin class="w-3.5 h-3.5" />
                <span>{{ langStore.currentLang === 'am' ? 'ተለጥፏል' : 'Pinned Directive' }}</span>
              </span>
              <span class="px-3 py-1 rounded-full bg-white/10 text-slate-200 text-xs font-bold uppercase tracking-wider border border-white/20">
                {{ topic.category }}
              </span>
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-400 font-mono">
              <Clock class="w-3.5 h-3.5 text-amber-400" />
              <span>Published: {{ new Date(topic.created_at).toLocaleDateString() }}</span>
            </div>
          </div>

          <!-- Topic Content & Connected Posts -->
          <div class="p-6 sm:p-8 space-y-6">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 leading-snug">
              {{ getTopicTitle(topic) }}
            </h2>

            <!-- Memos under this topic -->
            <div class="space-y-4">
              <div 
                v-for="post in posts.filter(p => p.topic_id === topic.id)" 
                :key="post.id"
                class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3"
              >
                <!-- Memo Author Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-200/80 flex-wrap gap-2">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-900 text-amber-400 flex items-center justify-center font-black text-xs shrink-0 shadow-2xs">
                      <UserCheck class="w-4 h-4" />
                    </div>
                    <div>
                      <h4 class="font-black text-sm text-slate-900">{{ post.author_name }}</h4>
                      <p class="text-[11px] text-blue-700 font-bold uppercase tracking-wider">{{ post.author_department }}</p>
                    </div>
                  </div>

                  <span class="text-xs text-slate-400 font-mono">
                    {{ new Date(post.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                  </span>
                </div>

                <!-- Memo Text -->
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal whitespace-pre-line">
                  {{ getPostContent(post) }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Confidentiality & Protocol Notice -->
      <div class="mt-12 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm">
        <div class="flex items-center gap-2 text-xs font-black text-slate-800 uppercase tracking-wider mb-2">
          <ShieldCheck class="w-4 h-4 text-blue-700" />
          <span>Municipal Secretariat Communication Protocol</span>
        </div>
        <p class="text-xs text-slate-600 leading-relaxed">
          Circulars published on this board constitute official municipal notices. Bureau heads and sub-city chief executives are required to ensure timely compliance with all directives issued through this portal.
        </p>
      </div>
    </div>
  </div>
</template>
