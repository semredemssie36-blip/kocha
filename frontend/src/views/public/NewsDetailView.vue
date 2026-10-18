<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useLanguageStore } from '@/stores/language'
import api from '@/services/api'
import { 
  Clock, 
  Eye, 
  Share2, 
  ChevronRight, 
  ArrowLeft, 
  Calendar, 
  Tag, 
  Newspaper, 
  Check, 
  TrendingUp,
  FolderOpen,
  Send,
  Sparkles,
  Bookmark,
  Building2,
  Copy
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const langStore = useLanguageStore()

const loading = ref(true)
const article = ref(null)
const recentNews = ref([])
const categories = ref([])
const copied = ref(false)

async function fetchArticleDetail(slugOrId) {
  loading.value = true
  try {
    const res = await api.get(`/news/${slugOrId}`)
    if (res.data) {
      if (res.data.article) {
        article.value = res.data.article
        recentNews.value = res.data.recent || []
        categories.value = res.data.categories || []
      } else {
        article.value = res.data
      }
    }
  } catch (err) {
    console.error('Failed to load article:', err)
  } finally {
    loading.value = false
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

watch(() => route.params.slug, (newSlug) => {
  if (newSlug) {
    fetchArticleDetail(newSlug)
  }
})

onMounted(() => {
  if (route.params.slug) {
    fetchArticleDetail(route.params.slug)
  }
})

function getTitle(item) {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.title_am || item.title_en || ''
  if (lang === 'om') return item.title_om || item.title_en || item.title_am || ''
  return item.title_en || item.title_am || ''
}

function getContent(item) {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.content_am || item.content_en || ''
  if (lang === 'om') return item.content_om || item.content_en || item.content_am || ''
  return item.content_en || item.content_am || ''
}

function getSummary(item) {
  if (!item) return ''
  const lang = langStore.currentLang
  if (lang === 'am') return item.summary_am || item.summary_en || ''
  if (lang === 'om') return item.summary_om || item.summary_en || item.summary_am || ''
  return item.summary_en || item.summary_am || ''
}

const readingTime = computed(() => {
  if (!article.value) return 2
  const text = getContent(article.value)
  const words = text ? text.split(/\s+/).length : 0
  return Math.max(1, Math.ceil(words / 150))
})

const currentUrl = computed(() => {
  return window.location.href
})

function copyShareLink() {
  navigator.clipboard.writeText(currentUrl.value)
  copied.value = true
  setTimeout(() => { copied.value = false }, 2500)
}

function shareOnTelegram() {
  const url = encodeURIComponent(currentUrl.value)
  const text = encodeURIComponent(getTitle(article.value))
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank')
}

function shareOnFacebook() {
  const url = encodeURIComponent(currentUrl.value)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
}

function shareOnTwitter() {
  const url = encodeURIComponent(currentUrl.value)
  const text = encodeURIComponent(getTitle(article.value))
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
}

function navigateToCategory(cat) {
  router.push({ path: '/media/news', query: { category: cat } })
}

function navigateToArticle(item) {
  router.push(`/media/news/${item.slug || item.id}`)
}
</script>

<template>
  <div class="bg-slate-50 min-h-screen text-slate-900 font-sans pb-16">
    
    <!-- Top Breadcrumb & Return Bar -->
    <div class="bg-white border-b border-slate-200 py-4 px-4 sm:px-8 xl:px-12 shadow-2xs">
      <div class="w-full max-w-[1700px] mx-auto flex items-center justify-between gap-4 flex-wrap">
        <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium truncate">
          <router-link to="/" class="hover:text-blue-900 transition">Home</router-link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
          <router-link to="/media/news" class="hover:text-blue-900 transition">Press Bureau</router-link>
          <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
          <span class="text-slate-900 font-bold truncate max-w-xs sm:max-w-md">
            {{ article ? getTitle(article) : 'Gazette Article' }}
          </span>
        </nav>

        <router-link 
          to="/media/news" 
          class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition shadow-2xs shrink-0"
        >
          <ArrowLeft class="w-3.5 h-3.5" />
          <span>Back to Press Room</span>
        </router-link>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
      
      <!-- Loading Skeleton -->
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-pulse">
        <div class="lg:col-span-8 bg-white rounded-3xl p-8 border border-slate-200 space-y-6">
          <div class="h-8 bg-slate-200 rounded w-1/3"></div>
          <div class="h-12 bg-slate-200 rounded w-full"></div>
          <div class="h-64 bg-slate-100 rounded-2xl"></div>
          <div class="space-y-3">
            <div class="h-4 bg-slate-200 rounded w-full"></div>
            <div class="h-4 bg-slate-200 rounded w-5/6"></div>
            <div class="h-4 bg-slate-200 rounded w-3/4"></div>
          </div>
        </div>
        <div class="lg:col-span-4 space-y-6">
          <div class="h-64 bg-white rounded-3xl border border-slate-200 p-6"></div>
        </div>
      </div>

      <!-- Article Reading Chamber -->
      <div v-else-if="article" class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <!-- Left Article Main Column (8 cols) -->
        <article class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-10 shadow-sm relative overflow-hidden">
          
          <!-- Category & Metadata Header Strip -->
          <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="px-3.5 py-1 rounded-full bg-blue-900 text-amber-300 text-xs font-black uppercase tracking-wider shadow-2xs">
              {{ article.category || 'Gazette Dispatch' }}
            </span>
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500 font-semibold">
              <span class="flex items-center gap-1.5">
                <Calendar class="w-3.5 h-3.5 text-blue-700" />
                {{ new Date(article.published_at || article.created_at).toLocaleDateString() }}
              </span>
              <span class="flex items-center gap-1.5">
                <Clock class="w-3.5 h-3.5 text-amber-600" />
                {{ readingTime }} min read
              </span>
              <span v-if="article.views_count" class="flex items-center gap-1.5">
                <Eye class="w-3.5 h-3.5 text-slate-400" />
                {{ article.views_count }} views
              </span>
            </div>
          </div>

          <!-- Headline -->
          <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight mb-6">
            {{ getTitle(article) }}
          </h1>

          <!-- Summary Blockquote Box -->
          <div v-if="getSummary(article)" class="p-5 sm:p-6 rounded-2xl bg-slate-50 border-l-4 border-amber-500 mb-8 text-sm sm:text-base text-slate-700 leading-relaxed font-medium">
            {{ getSummary(article) }}
          </div>

          <!-- Featured Photography Frame -->
          <div v-if="article.thumbnail_path" class="w-full h-72 sm:h-96 rounded-2xl overflow-hidden mb-8 bg-slate-900 shadow-md">
            <img 
              :src="article.thumbnail_path" 
              :alt="getTitle(article)" 
              class="w-full h-full object-cover"
            />
          </div>

          <!-- Article Prose Body -->
          <div 
            class="prose max-w-none text-slate-800 text-base leading-relaxed space-y-4 pt-2 border-t border-slate-100"
            v-html="getContent(article) || getSummary(article)"
          ></div>

          <!-- Social Sharing & Verification Footer -->
          <div class="mt-10 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
              <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Share Dispatch:</span>
              <button 
                @click="shareOnTelegram" 
                class="p-2 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 transition cursor-pointer"
                title="Share on Telegram"
              >
                <Send class="w-4 h-4" />
              </button>
              <button 
                @click="shareOnFacebook" 
                class="p-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition cursor-pointer"
                title="Share on Facebook"
              >
                <Share2 class="w-4 h-4" />
              </button>
              <button 
                @click="copyShareLink" 
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
              >
                <Check v-if="copied" class="w-4 h-4 text-emerald-600" />
                <Copy v-else class="w-4 h-4" />
                <span>{{ copied ? 'Link Copied!' : 'Copy Link' }}</span>
              </button>
            </div>

            <div class="text-xs text-slate-400 font-mono">
              Kombolcha Municipal Communications Directorate
            </div>
          </div>
        </article>

        <!-- Right Sidebar (4 cols) -->
        <aside class="lg:col-span-4 space-y-6">
          
          <!-- Recent Gazette Dispatches Widget -->
          <div v-if="recentNews.length > 0" class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
              <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                <TrendingUp class="w-4 h-4 text-blue-700" />
                <span>Recent Press Dispatches</span>
              </h3>
            </div>

            <div class="space-y-4">
              <div 
                v-for="recent in recentNews" 
                :key="recent.id" 
                @click="navigateToArticle(recent)"
                class="group cursor-pointer pb-3 border-b border-slate-100 last:border-0 last:pb-0"
              >
                <span class="text-[10px] text-amber-700 font-bold uppercase block mb-1">
                  {{ recent.category || 'Gazette' }}
                </span>
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-900 leading-snug transition-colors line-clamp-2">
                  {{ getTitle(recent) }}
                </h4>
                <span class="text-[10px] text-slate-400 font-mono mt-1 block">
                  {{ new Date(recent.published_at || recent.created_at).toLocaleDateString() }}
                </span>
              </div>
            </div>
          </div>

          <!-- Gazette Categories Widget -->
          <div v-if="categories.length > 0" class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
              <FolderOpen class="w-4 h-4 text-blue-700" />
              <span>Press Categories</span>
            </h3>
            <div class="space-y-1.5">
              <button 
                v-for="cat in categories" 
                :key="cat.category"
                @click="navigateToCategory(cat.category)"
                class="w-full flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 text-xs font-bold text-slate-700 transition cursor-pointer"
              >
                <span>{{ cat.category }}</span>
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-mono font-bold text-[10px]">
                  {{ cat.count }}
                </span>
              </button>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>
