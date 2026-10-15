<script setup>
import { ref, onMounted } from 'vue'
import { useLanguageStore } from '@/stores/language'
import { api } from '@/services/api'
import { 
  Pin, 
  MessageSquare, 
  Clock, 
  Building2, 
  ShieldCheck, 
  FileText,
  UserCheck
} from 'lucide-vue-next'

const langStore = useLanguageStore()
const loading = ref(true)
const topics = ref([])
const posts = ref([])

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
  if (lang === 'am') return t.title_am
  if (lang === 'om') return t.title_om || t.title_en
  return t.title_en
}

const getPostContent = (p) => {
  const lang = langStore.currentLang
  if (lang === 'am') return p.content_am
  if (lang === 'om') return p.content_om || p.content_en
  return p.content_en
}

onMounted(() => {
  fetchBoardData()
})
</script>

<template>
  <div class="w-full max-w-[1700px] mx-auto px-4 sm:px-8 xl:px-12 py-10">
    <!-- Header Banner: Clean White Official Style -->
    <div class="bg-white border-b border-slate-200 pb-8 mb-10">
      <div class="max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider mb-3 border border-slate-200">
          <ShieldCheck class="w-3.5 h-3.5 text-blue-700" />
          {{ langStore.currentLang === 'am' ? 'የማዘጋጃ ቤት ሰራተኞች ውስጣዊ የማስታወቂያ ሰሌዳ' : 'Municipal Staff Official Circulars & Memos' }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
          {{ langStore.currentLang === 'am' ? 'የስራ አስፈፃሚዎችና ሰራተኞች መረጃ ልውውጥ' : 'Staff Bulletin & Administrative Circulars' }}
        </h1>
        <p class="text-slate-600 text-base leading-relaxed">
          {{ langStore.currentLang === 'am' ? 'በ 22ቱ መምሪያዎች እና በ 4ቱ ክፍለ ከተሞች መካከል የሚተላለፉ ይፋዊ የስራ መመሪያዎች፣ ስብሰባዎችና ማስታወሻዎች።' : 'Inter-departmental communications, appraisal meeting schedules, and internal directives across municipal bureaus.' }}
        </p>
      </div>
    </div>

    <!-- Bulletin Posts -->
    <div v-if="loading" class="space-y-6">
      <div v-for="i in 2" :key="i" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm animate-pulse">
        <div class="h-6 w-1/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-8 w-3/4 bg-slate-200 rounded mb-4"></div>
        <div class="h-24 bg-slate-100 rounded"></div>
      </div>
    </div>

    <div v-else-if="topics.length === 0" class="text-center py-16 bg-white rounded-3xl border border-slate-200">
      <FileText class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="text-lg font-bold text-slate-800">
        {{ langStore.currentLang === 'am' ? 'በአሁኑ ወቅት ምንም አይነት ማስታወቂያ አልተለጠፈም' : 'No Circulars Active on the Board' }}
      </h3>
    </div>

    <div v-else class="space-y-8">
      <div 
        v-for="topic in topics" 
        :key="topic.id"
        class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow"
      >
        <!-- Topic Header -->
        <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span v-if="topic.is_pinned" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-500/10 text-amber-700 text-xs font-bold border border-amber-500/20">
              <Pin class="w-3.5 h-3.5 text-amber-600" />
              {{ langStore.currentLang === 'am' ? 'ተለጥፏል' : 'Pinned Circular' }}
            </span>
            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-100">
              {{ topic.category }}
            </span>
          </div>

          <div class="flex items-center gap-1.5 text-xs text-slate-500 font-mono">
            <Clock class="w-3.5 h-3.5 text-slate-400" />
            <span>{{ new Date(topic.created_at).toLocaleDateString() }}</span>
          </div>
        </div>

        <!-- Topic Title & Body Content -->
        <div class="p-8">
          <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-6">
            {{ getTopicTitle(topic) }}
          </h2>

          <!-- Connected Posts / Memos under this topic -->
          <div class="space-y-4">
            <div 
              v-for="post in posts.filter(p => p.topic_id === topic.id)" 
              :key="post.id"
              class="p-6 rounded-2xl bg-blue-50/40 border border-blue-100/60"
            >
              <div class="flex items-center justify-between mb-4 pb-3 border-b border-blue-100/50">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                    <UserCheck class="w-4 h-4" />
                  </div>
                  <div>
                    <h4 class="font-bold text-sm text-slate-900">{{ post.author_name }}</h4>
                    <p class="text-[11px] text-blue-700 font-medium">{{ post.author_department }}</p>
                  </div>
                </div>

                <span class="text-[11px] text-slate-400 font-mono">
                  {{ new Date(post.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                </span>
              </div>

              <p class="text-sm text-slate-700 leading-relaxed">
                {{ getPostContent(post) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
