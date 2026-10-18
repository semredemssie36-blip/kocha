<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import SkeletonCard from '@/components/common/SkeletonCard.vue'
import { 
  TrendingUp, 
  Clock, 
  Eye, 
  ArrowRight, 
  X, 
  ChevronRight, 
  Share2, 
  Search, 
  ChevronLeft,
  Newspaper,
  Calendar,
  Sparkles,
  Layers,
  Filter
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const langStore = useLanguageStore()
const newsArticles = ref([])
const activeCategory = ref('all')
const searchQuery = ref('')
const loading = ref(true)

const currentPage = ref(1)
const lastPage = ref(1)
const totalArticles = ref(0)
const perPage = ref(9)

const categories = ['all', 'Development', 'Governance', 'Logistics', 'Social']

async function fetchNews(page = 1) {
  loading.value = true
  currentPage.value = page
  try {
    const params = {
      category: activeCategory.value,
      page: page,
      per_page: perPage.value
    }
    if (searchQuery.value && searchQuery.value.trim() !== '') {
      params.search = searchQuery.value.trim()
    }

    const res = await api.get('/news', { params })
    if (res.data && res.data.data) {
      newsArticles.value = res.data.data
      currentPage.value = res.data.current_page || 1
      lastPage.value = res.data.last_page || 1
      totalArticles.value = res.data.total ?? res.data.data.length
    } else if (Array.isArray(res.data)) {
      newsArticles.value = res.data
      currentPage.value = 1
      lastPage.value = 1
      totalArticles.value = res.data.length
    } else {
      newsArticles.value = []
      totalArticles.value = 0
    }
  } catch (err) {
    console.error('Failed to load news:', err)
  } finally {
    loading.value = false
  }
}

function handleSearch() {
  fetchNews(1)
}

function openArticle(article) {
  router.push(`/media/news/${article.slug || article.id}`)
}

function filterCategory(cat) {
  activeCategory.value = cat
  fetchNews(1)
}

function changePage(p) {
  if (p < 1 || p > lastPage.value || p === currentPage.value) return
  fetchNews(p)
  window.scrollTo({ top: 120, behavior: 'smooth' })
}

function getNewsTitle(news) {
  if (!news) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return news.title_am || news.title_en || ''
  if (lang === 'om') return news.title_om || news.title_en || news.title_am || ''
  return news.title_en || news.title_am || ''
}

function getNewsSummary(news) {
  if (!news) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return news.summary_am || news.summary_en || ''
  if (lang === 'om') return news.summary_om || news.summary_en || news.summary_am || ''
  return news.summary_en || news.summary_am || ''
}

onMounted(() => {
  if (route.query.category) {
    activeCategory.value = route.query.category
  }
  fetchNews(1)
})
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Prestige Civic Header Strip -->
    <div class="relative bg-gradient-to-r from-[#071324] via-[#0B1E36] to-[#071324] text-white py-12 px-4 sm:px-8 xl:px-12 border-b border-amber-500/20 shadow-xl overflow-hidden">
      <img src="/assets/earport.jpg" alt="Press & News" class="absolute inset-0 w-full h-full object-cover object-center" />
      <div class="absolute inset-0 bg-gradient-to-r from-[#071324]/90 via-[#0B1E36]/80 to-[#071324]/75"></div>
      <div class="relative z-10 w-full max-w-[1700px] mx-auto">
        <h1 class="text-3xl sm:text-5xl font-black tracking-tight text-white mb-3">
          {{ langStore.currentLang === 'am' ? 'ይፋዊ የዜናና የመረጃ ማዕከል' : 'Official Press Briefings & Municipal Gazette' }}
        </h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-3xl leading-relaxed">
          {{ langStore.currentLang === 'am'
            ? 'በኮምቦልቻ ከተማ አስተዳደርና መምሪያዎች የሚወጡ ይፋዊ የልማት ዜናዎች፣ የመንግስት ውሳኔዎች፣ የህዝብ ማስታወቂያዎችና የክንውን ሪፖርቶች።'
            : 'Official municipal press briefings, development milestones, mayoral executive orders, and community progress dispatches across Kombolcha.'
          }}
        </p>

        <!-- Gazette Analytics Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-slate-800">
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Total Published Articles</span>
            <div class="text-2xl sm:text-3xl font-black font-mono text-white">{{ totalArticles }}</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-blue-500/30">
            <span class="text-blue-400 text-xs block font-bold mb-1">Gazette Frequency</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-blue-300">Daily Updates</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-emerald-500/30">
            <span class="text-emerald-400 text-xs block font-bold mb-1">Press Coverage</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-emerald-400">4 Sub-Cities</div>
          </div>
          <div class="bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl border border-slate-800">
            <span class="text-slate-400 text-xs block font-bold mb-1">Official Verification</span>
            <div class="text-xl sm:text-2xl font-black font-mono text-slate-300">City Press Bureau</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-8">
      
      <!-- Filter & Search Toolbar -->
      <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-sm mb-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        
        <!-- Category Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
          <button
            v-for="cat in categories"
            :key="cat"
            @click="filterCategory(cat)"
            :class="[
              'px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all cursor-pointer whitespace-nowrap shadow-2xs',
              activeCategory === cat 
                ? 'bg-blue-900 text-amber-300 ring-2 ring-blue-900/20' 
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
            ]"
          >
            {{ cat === 'all' ? (langStore.currentLang === 'am' ? 'ሁሉም ዜናዎች' : 'All Gazette Articles') : cat }}
          </button>
        </div>

        <!-- Search Input -->
        <div class="relative min-w-[280px]">
          <form @submit.prevent="handleSearch" class="relative">
            <Search class="absolute left-3.5 top-3 w-4 h-4 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              :placeholder="langStore.currentLang === 'am' ? 'ዜናዎችን በርዕስ ፈልግ...' : 'Search articles by title...'" 
              class="w-full pl-10 pr-20 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-900 focus:outline-none focus:border-blue-600 focus:bg-white transition-all"
            />
            <button 
              type="submit" 
              class="absolute right-1.5 top-1.5 px-3 py-1.5 bg-blue-900 hover:bg-blue-800 text-amber-300 rounded-lg text-xs font-bold transition shadow-2xs"
            >
              Search
            </button>
          </form>
        </div>
      </div>

      <!-- Loading Skeletons -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="n in 6" :key="n" class="bg-white rounded-3xl border border-slate-200 overflow-hidden h-96 animate-pulse"></div>
      </div>

      <!-- News Grid -->
      <div v-else-if="newsArticles.length > 0" class="space-y-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article 
            v-for="article in newsArticles" 
            :key="article.id"
            @click="openArticle(article)"
            class="bg-white rounded-3xl border border-slate-200/90 hover:border-amber-400 hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between group cursor-pointer"
          >
            <div>
              <!-- Thumbnail Frame -->
              <div class="h-56 bg-slate-900 relative overflow-hidden">
                <img 
                  :src="article.thumbnail_path || '/assets/industry.jpg'" 
                  :alt="getNewsTitle(article)"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-95"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

                <!-- Category Badge -->
                <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-slate-900/80 backdrop-blur-md text-amber-300 border border-amber-400/30 text-[11px] font-black uppercase tracking-wider shadow-md">
                  {{ article.category || 'Gazette' }}
                </span>

                <!-- Views Count -->
                <div v-if="article.views_count" class="absolute bottom-4 right-4 flex items-center gap-1.5 text-xs text-white/90 font-mono font-bold drop-shadow-md">
                  <Eye class="w-3.5 h-3.5 text-amber-400" />
                  <span>{{ article.views_count }}</span>
                </div>
              </div>

              <!-- Content Body -->
              <div class="p-6 sm:p-7">
                <div class="flex items-center gap-2 text-xs text-slate-400 font-mono mb-2.5">
                  <Clock class="w-3.5 h-3.5 text-slate-400" />
                  <span>{{ new Date(article.published_at || article.created_at).toLocaleDateString() }}</span>
                </div>

                <h2 class="text-lg sm:text-xl font-black text-slate-900 group-hover:text-blue-900 transition-colors leading-snug mb-3 line-clamp-2">
                  {{ getNewsTitle(article) }}
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                  {{ getNewsSummary(article) }}
                </p>
              </div>
            </div>

            <!-- Card Bottom Bar -->
            <div class="p-6 sm:p-7 pt-0 border-t border-slate-100 mt-2 flex items-center justify-between text-xs font-black text-blue-900">
              <span class="group-hover:translate-x-1 transition-transform flex items-center gap-1.5 text-blue-900">
                <span>{{ langStore.t('common.read_more') }}</span>
                <ArrowRight class="w-4 h-4 text-amber-500" />
              </span>
              <span class="text-[10px] text-slate-400 uppercase font-mono">Press Dispatch</span>
            </div>
          </article>
        </div>

        <!-- Pagination Controls -->
        <div v-if="lastPage > 1" class="p-4 px-6 bg-white rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600 shadow-2xs">
          <div>
            Page <span class="font-bold text-slate-900">{{ currentPage }}</span> of 
            <span class="font-bold text-slate-900">{{ lastPage }}</span> 
            ({{ totalArticles }} articles total)
          </div>
          <div class="flex items-center gap-1.5">
            <button 
              :disabled="currentPage === 1" 
              @click="changePage(currentPage - 1)"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
            >
              Previous
            </button>
            <button 
              v-for="p in lastPage" 
              :key="p"
              @click="changePage(p)"
              :class="[
                'w-8 h-8 rounded-xl font-black transition-colors cursor-pointer text-xs',
                currentPage === p ? 'bg-blue-900 text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-50 text-slate-700'
              ]"
            >
              {{ p }}
            </button>
            <button 
              :disabled="currentPage === lastPage" 
              @click="changePage(currentPage + 1)"
              class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed font-bold cursor-pointer transition-colors shadow-2xs"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 shadow-xs">
        <Newspaper class="w-16 h-16 text-slate-300 mx-auto mb-4" />
        <h3 class="text-lg font-black text-slate-800">No articles found in this category</h3>
        <p class="text-xs text-slate-500 mt-1">Official dispatches will appear here once approved by the press bureau.</p>
        <button 
          @click="activeCategory = 'all'; searchQuery = ''; fetchNews(1)" 
          class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors"
        >
          View All Gazette Articles
        </button>
      </div>
    </div>
  </div>
</template>
