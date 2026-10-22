import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useSiteStore = defineStore('site', () => {
  const siteSettings = ref(null)
  const emergencyContacts = ref([])
  const heroSlides = ref([])
  const subCities = ref([])
  const isLoaded = ref(false)
  const loading = ref(false)
  let fetchPromise = null

  async function fetchSiteData(force = false) {
    if (isLoaded.value && !force) {
      return {
        settings: siteSettings.value,
        emergency_contacts: emergencyContacts.value,
        hero_slides: heroSlides.value,
        sub_cities: subCities.value
      }
    }

    if (loading.value && fetchPromise) {
      return fetchPromise
    }

    loading.value = true
    fetchPromise = (async () => {
      try {
        const res = await api.get('/site-data')
        const data = res.data || {}
        if (data.settings) {
          siteSettings.value = data.settings
        }
        if (Array.isArray(data.emergency_contacts)) {
          emergencyContacts.value = data.emergency_contacts
        }
        if (Array.isArray(data.hero_slides)) {
          heroSlides.value = data.hero_slides
        }
        if (Array.isArray(data.sub_cities)) {
          subCities.value = data.sub_cities
        }
        isLoaded.value = true
        return data
      } catch (err) {
        console.error('Failed to load global site data:', err)
        return null
      } finally {
        loading.value = false
        fetchPromise = null
      }
    })()

    return fetchPromise
  }

  function updateSettingsLocally(newSettings) {
    siteSettings.value = { ...siteSettings.value, ...newSettings }
  }

  return {
    siteSettings,
    emergencyContacts,
    heroSlides,
    subCities,
    isLoaded,
    loading,
    fetchSiteData,
    updateSettingsLocally
  }
})
