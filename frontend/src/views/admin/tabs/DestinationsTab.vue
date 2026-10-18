<script setup>
import { ref } from 'vue'
import { 
  Compass, 
  Plus, 
  MapPin, 
  ExternalLink, 
  Edit, 
  Trash2, 
  Layers 
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
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
          <span>Investment & Tourism Directorate</span>
          <span>/</span>
          <span class="text-blue-600 font-bold">Spotlight Locations</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
          <span>Tourism, Heritage & Industrial Hubs</span>
          <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-100 font-mono font-bold text-slate-600">
            {{ destinationsList.length }} destinations
          </span>
        </h2>
      </div>

      <button 
        @click="emit('openCreate')" 
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition-all cursor-pointer shrink-0"
      >
        <Plus class="w-4 h-4" />
        <span>{{ isAuthor ? 'Submit Destination' : 'Add Destination' }}</span>
      </button>
    </div>

    <!-- Destinations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      <div 
        v-for="item in destinationsList" 
        :key="item.id"
        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-slate-300 transition-all flex flex-col justify-between"
      >
        <div>
          <!-- Thumbnail Image -->
          <div class="h-44 bg-slate-100 relative overflow-hidden">
            <img 
              :src="item.image_path || item.image_url || '/assets/hero_industry.jpg'" 
              :alt="item.title_en || item.title_am"
              class="w-full h-full object-cover"
              @error="$event.target.src = '/assets/hero_industry.jpg'"
            />
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-bold uppercase tracking-wider">
              {{ item.category }}
            </span>
          </div>

          <div class="p-5">
            <h3 class="font-bold text-base text-slate-900 leading-snug">
              {{ item.title_en || item.title_am }}
            </h3>
            <p class="text-xs text-slate-500 font-mono mt-0.5">
              AM: {{ item.title_am }}
            </p>

            <div class="flex items-center gap-1.5 text-xs text-slate-600 mt-3">
              <MapPin class="w-3.5 h-3.5 text-blue-600 shrink-0" />
              <span class="truncate">{{ item.location_en || item.location_am || 'Kombolcha City' }}</span>
            </div>

            <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
              {{ item.description_en || item.description_am }}
            </p>
          </div>
        </div>

        <div class="px-5 pb-5 pt-2 border-t border-slate-100 flex items-center justify-between">
          <router-link 
            :to="'/explore/' + (item.slug || item.id)" 
            target="_blank"
            class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700"
          >
            <span>View Public Page</span>
            <ExternalLink class="w-3.5 h-3.5" />
          </router-link>

          <div class="flex items-center gap-1.5">
            <!-- Editorial Approve / Unpublish Buttons -->
            <button 
              v-if="isEditor && item.status !== 'published'"
              @click="emit('approveItem', item)"
              class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
              title="Approve and publish spotlight live"
            >
              Approve
            </button>
            <button 
              v-if="isEditor && item.status === 'published'"
              @click="emit('unpublishItem', item)"
              class="px-2.5 py-1 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-[11px] font-bold transition shadow-2xs cursor-pointer"
              title="Move back to draft"
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
        </div>
      </div>
    </div>
  </div>
</template>
