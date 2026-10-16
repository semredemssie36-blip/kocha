<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import SkeletonCard from '@/components/common/SkeletonCard.vue'
import { TrendingUp, Clock, Eye, ArrowRight, X, ChevronRight, Share2, Search, ChevronLeft } from 'lucide-vue-next'

const langStore = useLanguageStore()
const newsArticles = ref([])
const activeCategory = ref('all')
const searchQuery = ref('')
const selectedArticle = ref(null)
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
  selectedArticle.value = article
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

onMounted(() => {
  fetchNews(1)
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-6">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
            Communications & Press Bureau
          </div>
          <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-2">
            {{ langStore.t('nav.news') }}
          </h1>
          <p class="text-slate-600 text-base max-w-2xl">
            Official press briefings, municipal announcements, community reports, and development milestones in Kombolcha City.
          </p>
        </div>

        <!-- Search Bar -->
        <div class="w-full md:w-80">
          <form @submit.prevent="handleSearch" class="relative">
            <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
            <input 
              v-model="searchQuery" 
              type="text" 
              placeholder="Search news & reports..." 
              class="w-full pl-10 pr-20 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
            />
            <button 
              type="submit" 
              class="absolute right-1.5 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-bold transition shadow-xs"
            >
              Search
            </button>
          </form>
        </div>
      </div>

      <!-- Category Filter Pills & Result Stats -->
      <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
        <div class="flex flex-wrap items-center gap-2">
          <button
            v-for="cat in categories"
            :key="cat"
            @click="filterCategory(cat)"
            :class="[
              'px-3.5 py-1.5 rounded-lg text-xs font-bold capitalize transition-all cursor-pointer',
              activeCategory === cat 
                ? 'bg-blue-700 text-white shadow-2xs' 
                : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200'
            ]"
          >
            {{ cat }}
          </button>
        </div>

        <div v-if="!loading" class="text-xs font-semibold text-slate-500">
          Found <span class="text-slate-900 font-bold">{{ totalArticles }}</span> articles
        </div>
      </div>
    </div>

    <!-- Loading Skeletons -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <SkeletonCard v-for="n in 6" :key="n" />
    </div>

    <!-- News Grid -->
    <div v-else-if="newsArticles.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <article
        v-for="news in newsArticles"
        :key="news.id"
        @click="openArticle(news)"
        class="bg-white rounded-2xl border border-slate-200 shadow-xs hover:border-slate-300 transition-all overflow-hidden flex flex-col justify-between cursor-pointer group"
      >
        <div>
          <div class="h-48 bg-slate-100 relative flex items-center justify-center text-slate-500 overflow-hidden border-b border-slate-100">
            <img 
              v-if="news.thumbnail_path || news.featured_image"
              :src="news.thumbnail_path || news.featured_image" 
              :alt="getNewsTitle(news)" 
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
              <TrendingUp class="w-8 h-8 mb-1 opacity-40 text-blue-600" />
              <span class="text-[11px] font-semibold text-slate-400">Kombolcha Press</span>
            </div>
            <span class="absolute top-3.5 left-3.5 px-2.5 py-1 rounded bg-blue-700 text-white text-[11px] font-bold shadow-2xs">
              {{ news.category }}
            </span>
          </div>

          <div class="p-6">
            <div class="flex items-center gap-4 text-xs text-slate-400 mb-3">
              <span class="flex items-center gap-1.5">
                <Clock class="w-3.5 h-3.5" />
                {{ new Date(news.published_at || news.created_at).toLocaleDateString() }}
              </span>
              <span class="flex items-center gap-1">
                <Eye class="w-3.5 h-3.5" />
                {{ news.views_count }} views
              </span>
            </div>

            <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug">
              {{ getNewsTitle(news) }}
            </h3>
          </div>
        </div>

        <div class="px-6 pb-6 pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-blue-600 group-hover:underline">
          <span>Read Full Article</span>
          <ArrowRight class="w-4 h-4" />
        </div>
      </article>
    </div>

    <!-- Pagination Controls -->
    <div v-if="!loading && totalArticles > perPage" class="mt-12 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
      <div class="text-xs font-semibold text-slate-500">
        Showing <span class="text-slate-900 font-bold">{{ ((currentPage - 1) * perPage) + 1 }}</span> to <span class="text-slate-900 font-bold">{{ Math.min(currentPage * perPage, totalArticles) }}</span> of <span class="text-slate-900 font-bold">{{ totalArticles }}</span> articles
      </div>

      <div class="flex items-center gap-1.5">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer"
        >
          <ChevronLeft class="w-4 h-4" />
          Previous
        </button>

        <div class="flex items-center gap-1">
          <button
            v-for="p in lastPage"
            :key="p"
            @click="changePage(p)"
            :class="[
              'w-8 h-8 rounded-lg text-xs font-bold transition flex items-center justify-center cursor-pointer',
              currentPage === p
                ? 'bg-blue-700 text-white shadow-2xs font-black'
                : 'bg-white hover:bg-slate-100 text-slate-700 border border-slate-200'
            ]"
          >
            {{ p }}
          </button>
        </div>

        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === lastPage"
          class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer"
        >
          Next
          <ChevronRight class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Empty News -->
    <div v-else-if="!loading && newsArticles.length === 0" class="text-center py-20 bg-white rounded-2xl border border-slate-200">
      <TrendingUp class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h4 class="text-base font-bold text-slate-700">No news articles found</h4>
      <p class="text-xs text-slate-400 mt-1">Try adjusting your search query or select another category filter.</p>
    </div>

    <!-- Article Reader Lightbox Modal -->
    <div v-if="selectedArticle" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6 sm:p-8 shadow-2xl relative">
        <button @click="selectedArticle = null" class="absolute top-4 right-4 p-2 rounded-full hover:bg-slate-100 text-slate-500">
          <X class="w-5 h-5" />
        </button>

        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider">
          {{ selectedArticle.category }}
        </span>

        <h2 class="text-2xl font-extrabold text-slate-900 mt-4 leading-tight">
          {{ langStore.currentLang === 'am' ? selectedArticle.title_am : (langStore.currentLang === 'om' ? selectedArticle.title_om : selectedArticle.title_en) }}
        </h2>

        <img 
          v-if="selectedArticle.thumbnail_path || selectedArticle.featured_image" 
          :src="selectedArticle.thumbnail_path || selectedArticle.featured_image" 
          :alt="selectedArticle.title_en || selectedArticle.title_am" 
          class="w-full h-56 sm:h-72 object-cover rounded-xl mt-4 border border-slate-200 shadow-xs" 
        />

        <div class="flex items-center gap-4 text-xs text-slate-400 py-3 border-b border-slate-100 mt-2 mb-6">
          <span>Published on: {{ new Date(selectedArticle.published_at || selectedArticle.created_at).toLocaleDateString() }}</span>
          <span>•</span>
          <span>{{ selectedArticle.views_count }} reads</span>
        </div>

        <div class="prose text-slate-700 text-sm leading-relaxed whitespace-pre-line">
          {{ langStore.currentLang === 'am' ? selectedArticle.content_am : (langStore.currentLang === 'om' ? (selectedArticle.content_om || selectedArticle.content_am) : (selectedArticle.content_en || selectedArticle.content_am)) }}
        </div>
      </div>
    </div>
  </div>
</template>
