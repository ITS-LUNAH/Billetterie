<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({})
  }
})

const form = ref({
  search: props.filters.search || '',
  location: props.filters.location || '',
  date: props.filters.date || ''
})

const handleSearch = () => {
  router.get('/evenement', {//jai changé /evenements par /events
    search: form.value.search,
    location: form.value.location,
    date: form.value.date
  }, {
    preserveState: true,
    replace: true
  })
}

const handleSubmit = (e) => {
  e.preventDefault()
  handleSearch()
}
</script>

<template>
  <div class="bg-white/50 rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Rechercher un événement</h2>
    
    <form @submit="handleSubmit" class="space-y-4">
      <!-- Champ de recherche principal -->
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <input
          v-model="form.search"
          type="text"
          placeholder="Nom de l'événement, artiste..."
          class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-gray-900 placeholder-gray-500 bg-white"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Champ lieu -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
          <input
            v-model="form.location"
            type="text"
            placeholder="Lieu ou ville"
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-gray-900 placeholder-gray-500 bg-white"
          />
        </div>

        <!-- Champ date -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
            </svg>
          </div>
          <input
            v-model="form.date"
            type="date"
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-gray-900 bg-white"
          />
        </div>
      </div>

      <!-- Bouton de recherche -->
      <button
        type="submit"
        class="w-full hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        style="background-color: #6B4E71;"
      >
        Rechercher
      </button>
    </form>
  </div>
</template>
