<script setup>
import { ref, computed } from 'vue'
import { 
  Compass, 
  Plus, 
  MapPin, 
  ExternalLink, 
  Edit, 
  Trash2, 
  Layers,
  Search,
  LayoutGrid,
  List,
  Filter,
  CheckCircle2,
  Clock,
  Eye
} from 'lucide-vue-next'

const props = defineProps({
  destinationsList: {
    type: Array,
    required: true
  },
  isEditor: {
    type: Boolean,
    default: false
  },
  isAuthor: {
    type: Boolean,
    default: false
  },
  currentUserId: {
    type: [Number, String],
    default: null
  }
})

const emit = defineEmits(['openCreate', 'openEdit', 'deleteItem', 'approveItem', 'unpublishItem'])

const viewMode = ref('cards') // 'cards' | 'table'
const searchQuery = ref('')
const categoryFilter = ref('all')
const statusFilter = ref('all')

const categories = computed(() => {
  const set = new Set(props.destinationsList.map(d => d.category).filter(Boolean))
  return Array.from(set)
})

const filteredDestinations = computed(() => {
  return props.destinationsList.filter(item => {
    const q = searchQuery.value.toLowerCase().trim()
    const matchesSearch = !q ||
      (item.title_en && item.title_en.toLowerCase().includes(q)) ||
      (item.title_am && item.title_am.includes(q)) ||
      (item.location_en && item.location_en.toLowerCase().includes(q)) ||
      (item.category && item.category.toLowerCase().includes(q))

    const matchesCat = categoryFilter.value === 'all' || item.category === categoryFilter.value

    const matchesStatus = statusFilter.value === 'all' || item.status === statusFilter.value

    return matchesSearch && matchesCat && matchesStatus
  })
})

function canEdit(item) {
  if (props.isEditor) return true
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status !== 'published'
}

function canDelete(item) {
  if (props.isEditor) return true
  return props.isAuthor && (item.author_id === props.currentUserId || !item.author_id) && item.status === 'draft'
}
</script>

<template>
  <div class="space-y-6 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
      <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Tourism, Heritage & Industrial Hubs</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ destinationsList.length }} destinations
          </span>
        </h2>
      </div>

      <div class="flex items-center gap-3">
        <!-- View Toggle -->
        <div class="flex items-center bg-slate-200/80 p-1 rounded-xl shadow-inner">
          <button 
            type="button"
            @click="viewMode = 'cards'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'cards' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Card Grid View"
          >
            <LayoutGrid class="w-4 h-4" />
          </button>
          <button 
            type="button"
            @click="viewMode = 'table'"
            class="p-1.5 rounded-lg transition-all cursor-pointer"
            :class="viewMode === 'table' ? 'bg-white text-blue-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900'"
            title="Sovereign Table View"
          >
            <List class="w-4 h-4" />
          </button>
        </div>

        <button 
          @click="emit('openCreate')" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition-all cursor-pointer shrink-0"
        >
          <Plus class="w-4 h-4" />
          <span>{{ isAuthor ? 'Submit Destination' : 'Add Destination' }}</span>
        </button>
      </div>
    </div>

    <!-- Filter & Search Controls Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
      <div class="relative w-full md:w-80">
        <Search class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          v-model="searchQuery"
          type="text"
          placeholder="Filter by title, corridor, category..."
          class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition"
        />
      </div>

      <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
        <!-- Category Dropdown / Filters -->
        <select 
          v-model="categoryFilter" 
          class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-700 outline-none focus:border-blue-600 cursor-pointer"
        >
          <option value="all">All Categories</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>

        <!-- Status Filter -->
        <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs">
          <button 
            type="button"
            @click="statusFilter = 'all'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            All
          </button>
          <button 
            type="button"
            @click="statusFilter = 'published'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'published' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            Published
          </button>
          <button 
            type="button"
            @click="statusFilter = 'pending'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'pending' ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            Pending
          </button>
          <button 
            type="button"
            @click="statusFilter = 'draft'"
            class="px-2.5 py-1 rounded-lg font-bold transition cursor-pointer"
            :class="statusFilter === 'draft' ? 'bg-white text-slate-700 shadow-xs' : 'text-slate-500 hover:text-slate-900'"
          >
            Draft
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredDestinations.length === 0" class="bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-500">
      <Compass class="w-12 h-12 text-slate-300 mx-auto mb-3" />
      <h3 class="font-bold text-base text-slate-800">No destination spotlight locations found</h3>
      <p class="text-xs text-slate-500 mt-1">Try refining your search terms or filters.</p>
    </div>

    <!-- 1. SOVEREIGN TABLE VIEW -->
    <div v-else-if="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider text-[11px] font-black">
              <th class="py-3.5 px-4">Destination Spotlight</th>
              <th class="py-3.5 px-4">Category</th>
              <th class="py-3.5 px-4">Location / Corridor</th>
              <th class="py-3.5 px-4">Status</th>
              <th class="py-3.5 px-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-sans">
            <tr 
              v-for="item in filteredDestinations" 
              :key="item.id" 
              class="hover:bg-blue-50/40 transition-colors"
            >
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <div class="w-14 h-12 rounded-xl bg-slate-900 overflow-hidden shrink-0 border border-slate-200 shadow-2xs">
                    <img 
                      :src="item.image_path || item.image_url || '/assets/city_image.jpg'" 
                      :alt="item.title_en || item.title_am"
                      class="w-full h-full object-cover"
                      @error="$event.target.src = '/assets/city_image.jpg'"
                    />
                  </div>
                  <div>
                    <span class="font-bold text-slate-900 text-sm block leading-snug">{{ item.title_en || item.title_am }}</span>
                    <span class="text-xs text-slate-500 font-mono">AM: {{ item.title_am }}</span>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                  {{ item.category }}
                </span>
              </td>
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                  <MapPin class="w-3.5 h-3.5 text-blue-600 shrink-0" />
                  <span>{{ item.location_en || item.location_am || 'Kombolcha City' }}</span>
                </div>
              </td>
              <td class="py-3.5 px-4">
                <span 
                  :class="[
                    'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wide',
                    item.status === 'published' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' :
                    item.status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                    'bg-slate-100 text-slate-600 border border-slate-200'
                  ]"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'published' ? 'bg-emerald-500 animate-pulse' : item.status === 'pending' ? 'bg-amber-500' : 'bg-slate-400'"></span>
                  {{ item.status || 'draft' }}
                </span>
              </td>
              <td class="py-3.5 px-4 text-right">
                <div class="inline-flex items-center gap-1.5 justify-end">
                  <router-link 
                    :to="'/explore/' + (item.slug || item.id)" 
                    target="_blank"
                    class="p-1.5 rounded-lg border border-slate-200 text-blue-600 hover:bg-blue-50 transition"
                    title="View Public Page"
                  >
                    <ExternalLink class="w-3.5 h-3.5" />
                  </router-link>

                  <button 
                    v-if="isEditor && item.status !== 'published'"
                    @click="emit('approveItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider transition cursor-pointer"
                  >
                    Approve
                  </button>
                  <button 
                    v-if="isEditor && item.status === 'published'"
                    @click="emit('unpublishItem', item)"
                    class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-black uppercase tracking-wider transition cursor-pointer"
                  >
                    Unpublish
                  </button>

                  <button 
                    v-if="canEdit(item)"
                    @click="emit('openEdit', item)"
                    class="p-1.5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    title="Edit destination"
                  >
                    <Edit class="w-3.5 h-3.5" />
                  </button>
                  <button 
                    v-if="canDelete(item)"
                    @click="emit('deleteItem', item.id)"
                    class="p-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    title="Delete destination"
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

    <!-- 2. CARD GRID VIEW -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="item in filteredDestinations" 
        :key="item.id" 
        class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 hover:shadow-lg transition-all flex flex-col justify-between relative"
      >
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-amber-500 z-10"></div>
        <div>
          <!-- Thumbnail Image -->
          <div class="h-48 bg-slate-900 relative overflow-hidden group">
            <img 
              :src="item.image_path || item.image_url || '/assets/city_image.jpg'" 
              :alt="item.title_en || item.title_am"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              @error="$event.target.src = '/assets/city_image.jpg'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
            
            <div class="absolute top-3 left-3 flex items-center gap-1.5">
              <span class="px-2.5 py-1 rounded-lg bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-black uppercase tracking-wider border border-white/20">
                {{ item.category }}
              </span>
            </div>

            <span 
              :class="[
                'absolute top-3 right-3 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider backdrop-blur-xs border',
                item.status === 'published' ? 'bg-emerald-600/90 text-white border-emerald-400/40' :
                item.status === 'pending' ? 'bg-amber-600/90 text-white border-amber-400/40' :
                'bg-slate-800/90 text-slate-300 border-white/20'
              ]"
            >
              {{ item.status || 'draft' }}
            </span>
          </div>

          <div class="p-6">
            <h3 class="font-black text-base text-slate-900 leading-snug">
              {{ item.title_en || item.title_am }}
            </h3>
            <p class="text-xs text-slate-500 font-mono mt-1">
              AM: {{ item.title_am }}
            </p>

            <div class="flex items-center gap-1.5 text-xs text-slate-600 mt-3 font-medium">
              <MapPin class="w-3.5 h-3.5 text-blue-600 shrink-0" />
              <span class="truncate">{{ item.location_en || item.location_am || 'Kombolcha City' }}</span>
            </div>

            <p class="text-xs text-slate-600 mt-2.5 line-clamp-2 leading-relaxed">
              {{ item.description_en || item.description_am }}
            </p>
          </div>
        </div>

        <div class="px-6 pb-6 pt-3 border-t border-slate-100 flex items-center justify-between">
          <router-link 
            :to="'/explore/' + (item.slug || item.id)" 
            target="_blank"
            class="inline-flex items-center gap-1 text-xs font-black text-blue-700 hover:text-blue-900 transition"
          >
            <span>View Public Page</span>
            <ExternalLink class="w-3.5 h-3.5" />
          </router-link>

          <div class="flex items-center gap-1.5">
            <button 
              v-if="isEditor && item.status !== 'published'"
              @click="emit('approveItem', item)"
              class="px-2.5 py-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black uppercase tracking-wider transition shadow-2xs cursor-pointer"
            >
              Approve
            </button>
            <button 
              v-if="isEditor && item.status === 'published'"
              @click="emit('unpublishItem', item)"
              class="px-2.5 py-1 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-[10px] font-black uppercase tracking-wider transition shadow-2xs cursor-pointer"
            >
              Unpublish
            </button>

            <button 
              v-if="canEdit(item)"
              @click="emit('openEdit', item)"
              class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 transition cursor-pointer"
              title="Edit destination"
            >
              <Edit class="w-3.5 h-3.5" />
            </button>
            <button 
              v-if="canDelete(item)"
              @click="emit('deleteItem', item.id)"
              class="p-2 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
              title="Delete destination"
            >
              <Trash2 class="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
