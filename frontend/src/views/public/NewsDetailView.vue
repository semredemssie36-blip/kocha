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
  Sparkles
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
  <div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Top Breadcrumb Navigation -->
      <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-6 flex-wrap">
        <router-link to="/" class="hover:text-blue-700 transition">Home</router-link>
        <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
        <router-link to="/media/news" class="hover:text-blue-700 transition">News & Press</router-link>
        <ChevronRight class="w-3.5 h-3.5 text-slate-400 shrink-0" />
        <span class="text-slate-900 font-bold truncate max-w-xs sm:max-w-md">
          {{ article ? getTitle(article) : 'Article Detail' }}
        </span>
      </nav>

      <!-- Back to News Button -->
      <div class="mb-6">
        <router-link 
          to="/media/news" 
          class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:text-blue-700 hover:border-blue-300 shadow-2xs transition"
        >
          <ArrowLeft class="w-3.5 h-3.5" />
          <span>Back to All News</span>
        </router-link>
      </div>

      <!-- Loading State Skeleton -->
      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-pulse">
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 space-y-6">
          <div class="h-6 w-32 bg-slate-200 rounded-lg"></div>
          <div class="h-10 w-3/4 bg-slate-200 rounded-xl"></div>
          <div class="h-80 w-full bg-slate-200 rounded-2xl"></div>
          <div class="space-y-3">
            <div class="h-4 bg-slate-200 rounded"></div>
            <div class="h-4 bg-slate-200 rounded"></div>
            <div class="h-4 w-5/6 bg-slate-200 rounded"></div>
          </div>
        </div>
        <div class="lg:col-span-4 space-y-6">
          <div class="bg-white rounded-3xl p-6 border border-slate-200 h-96"></div>
        </div>
      </div>

      <!-- Main 2-Column Editorial Grid (70% Article + 30% Sidebar) -->
      <div v-else-if="article" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- ================= LEFT: MAIN ARTICLE ================= -->
        <article class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10 overflow-hidden">
          
          <!-- Meta Header -->
          <div class="flex flex-wrap items-center justify-between gap-3 pb-6 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-2">
              <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200/60 text-xs font-black uppercase tracking-wider">
                {{ article.category || 'General' }}
              </span>
              <span class="text-xs text-slate-400">•</span>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                <Calendar class="w-3.5 h-3.5 text-slate-400" />
                {{ new Date(article.published_at || article.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
              </span>
            </div>

            <div class="flex items-center gap-3 text-xs font-semibold text-slate-400">
              <span class="inline-flex items-center gap-1">
                <Eye class="w-3.5 h-3.5 text-slate-400" />
                {{ article.views_count || 1 }} views
              </span>
              <span>•</span>
              <span class="inline-flex items-center gap-1">
                <Clock class="w-3.5 h-3.5 text-slate-400" />
                {{ readingTime }} min read
              </span>
            </div>
          </div>

          <!-- Main Article Title -->
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight mt-6 mb-6">
            {{ getTitle(article) }}
          </h1>

          <!-- Featured Hero Image -->
          <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xs mb-8 bg-slate-100">
            <img 
              v-if="article.thumbnail_path || article.featured_image"
              :src="article.thumbnail_path || article.featured_image" 
              :alt="getTitle(article)" 
              class="w-full max-h-[500px] object-cover"
            />
            <div v-else class="h-64 flex flex-col items-center justify-center text-slate-400 bg-slate-100">
              <Newspaper class="w-12 h-12 mb-2 text-slate-300" />
              <span class="text-xs font-bold text-slate-400">Kombolcha Metropolitan Press Release</span>
            </div>
          </div>

          <!-- Summary / Lead Quote -->
          <div v-if="getSummary(article)" class="p-5 rounded-2xl bg-blue-50/70 border-l-4 border-blue-600 text-slate-700 text-sm sm:text-base font-semibold leading-relaxed mb-8">
            {{ getSummary(article) }}
          </div>

          <!-- Full Article Body -->
          <div class="prose max-w-none text-slate-800 text-base leading-relaxed whitespace-pre-line space-y-4">
            {{ getContent(article) }}
          </div>

          <!-- Social Share Bar & Actions -->
          <div class="mt-10 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-2">
              <span class="text-xs font-black uppercase text-slate-500 tracking-wider flex items-center gap-1.5">
                <Share2 class="w-3.5 h-3.5 text-blue-600" />
                Share Article:
              </span>
              
              <!-- Telegram -->
              <button 
                @click="shareOnTelegram" 
                class="px-3 py-1.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-bold transition cursor-pointer flex items-center gap-1"
                title="Share on Telegram"
              >
                <Send class="w-3 h-3" />
                <span>Telegram</span>
              </button>

              <!-- Facebook -->
              <button 
                @click="shareOnFacebook" 
                class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition cursor-pointer"
                title="Share on Facebook"
              >
                Facebook
              </button>

              <!-- Twitter / X -->
              <button 
                @click="shareOnTwitter" 
                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition cursor-pointer"
                title="Share on Twitter / X"
              >
                X
              </button>

              <!-- Copy Link -->
              <button 
                @click="copyShareLink" 
                class="px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer flex items-center gap-1"
              >
                <Check v-if="copied" class="w-3 h-3 text-emerald-600" />
                <span>{{ copied ? 'Copied!' : 'Copy Link' }}</span>
              </button>
            </div>

            <!-- Return Link -->
            <router-link to="/media/news" class="text-xs font-bold text-blue-700 hover:underline">
              ← Explore all news & press releases
            </router-link>
          </div>
        </article>

        <!-- ================= RIGHT: SIDEBAR WIDGETS ================= -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
          
          <!-- 1. Recent News Widget (Matching Addis Cadaster UX) -->
          <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100">
              <Newspaper class="w-4 h-4 text-blue-600 shrink-0" />
              <h3 class="text-sm font-black uppercase text-slate-900 tracking-wider">
                Recent News
              </h3>
            </div>

            <div v-if="recentNews.length > 0" class="divide-y divide-slate-100">
              <div 
                v-for="item in recentNews" 
                :key="item.id"
                @click="navigateToArticle(item)"
                class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-3.5 group cursor-pointer"
              >
                <!-- Thumbnail -->
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative">
                  <img 
                    v-if="item.thumbnail_path || item.featured_image" 
                    :src="item.thumbnail_path || item.featured_image" 
                    :alt="getTitle(item)" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                  />
                  <div v-else class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                    <TrendingUp class="w-5 h-5 opacity-40 text-blue-600" />
                  </div>
                </div>

                <!-- Text & Metadata -->
                <div class="flex-1 min-w-0">
                  <h4 class="text-xs font-bold text-slate-900 group-hover:text-blue-700 transition leading-snug line-clamp-2 mb-1.5">
                    {{ getTitle(item) }}
                  </h4>
                  <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-medium">
                    <Clock class="w-3 h-3 text-slate-400 shrink-0" />
                    <span>{{ new Date(item.published_at || item.created_at).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' }) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="py-6 text-center text-slate-400 text-xs">
              No other recent articles available.
            </div>
          </div>

          <!-- 2. News Categories Widget -->
          <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 pb-4 mb-4 border-b border-slate-100">
              <FolderOpen class="w-4 h-4 text-blue-600 shrink-0" />
              <h3 class="text-sm font-black uppercase text-slate-900 tracking-wider">
                Categories
              </h3>
            </div>

            <div class="space-y-1.5">
              <button 
                v-for="cat in (categories.length ? categories : [{ category: 'Development', count: 1 }, { category: 'Governance', count: 1 }, { category: 'General', count: 1 }])"
                :key="cat.category"
                @click="navigateToCategory(cat.category)"
                class="w-full flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 text-slate-700 hover:text-blue-700 transition text-xs font-bold cursor-pointer group"
              >
                <div class="flex items-center gap-2">
                  <Tag class="w-3.5 h-3.5 text-slate-400 group-hover:text-blue-600" />
                  <span class="capitalize">{{ cat.category }}</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-mono text-[10px]">
                  {{ cat.count || 1 }}
                </span>
              </button>
            </div>
          </div>

          <!-- 3. Official Press Desk Help Card -->
          <div class="bg-gradient-to-br from-blue-700 to-indigo-900 text-white rounded-3xl p-6 shadow-md relative overflow-hidden">
            <div class="flex items-center gap-2 text-blue-200 text-xs font-black uppercase tracking-wider mb-2">
              <Sparkles class="w-3.5 h-3.5 text-blue-300" />
              <span>Communications Bureau</span>
            </div>
            <h4 class="text-base font-black text-white leading-snug mb-2">
              Media Inquiries & Press Briefings
            </h4>
            <p class="text-xs text-blue-100 leading-relaxed mb-4">
              For accredited press inquiries, official mayoral statements, or interview requests, reach the Press Secretary.
            </p>
            <router-link 
              to="/contact" 
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-blue-900 text-xs font-black hover:bg-blue-50 transition shadow-xs"
            >
              <span>Contact Press Desk</span>
              <ChevronRight class="w-3.5 h-3.5" />
            </router-link>
          </div>

        </aside>

      </div>

      <!-- Not Found Fallback -->
      <div v-else class="text-center py-24 bg-white rounded-3xl border border-slate-200 p-8 max-w-lg mx-auto">
        <Newspaper class="w-12 h-12 text-slate-300 mx-auto mb-3" />
        <h3 class="text-lg font-black text-slate-900 mb-1">Article Not Found</h3>
        <p class="text-xs text-slate-500 mb-6">The requested news article may have been relocated or updated.</p>
        <router-link to="/media/news" class="px-5 py-2.5 rounded-xl bg-blue-700 text-white text-xs font-bold hover:bg-blue-800 transition">
          Return to News Directory
        </router-link>
      </div>

    </div>
  </div>
</template>
