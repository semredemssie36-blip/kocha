import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import am from '../locales/am'
import en from '../locales/en'
import om from '../locales/om'

export const useLanguageStore = defineStore('language', () => {
  const currentLang = ref(localStorage.getItem('kombolcha_lang') || 'am')

  const dictionaries = {
    am,
    en,
    om
  }

  const t = computed(() => {
    return (key) => {
      const keys = key.split('.')
      let result = dictionaries[currentLang.value]
      for (const k of keys) {
        if (!result || result[k] === undefined) {
          // Fallback to Amharic if key not found
          let fallback = dictionaries['am']
          for (const fb of keys) {
            if (!fallback || fallback[fb] === undefined) return key
            fallback = fallback[fb]
          }
          return fallback
        }
        result = result[k]
      }
      return result
    }
  })

  function setLanguage(lang) {
    if (['am', 'en', 'om'].includes(lang)) {
      currentLang.value = lang
      localStorage.setItem('kombolcha_lang', lang)
      document.documentElement.lang = lang
    }
  }

  return {
    currentLang,
    t,
    setLanguage
  }
})
