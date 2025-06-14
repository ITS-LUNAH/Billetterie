<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import EventSearchBar from '@/Components/EventSearchBar.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  events: Object,
  filters: Object,
  canLogin: Boolean,
  canRegister: Boolean,
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatPrice = (price) => {
  return price ? `${price}€` : 'Gratuit'
}
</script>

<template>
    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="relative z-10 py-8">
            <!-- Barre de recherche -->
            <div class="mb-8">
                <EventSearchBar :filters="filters" />
            </div>

            <!-- Résultats -->
            <div class="bg-white/90 backdrop-blur-sm rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">
                    {{ events.total }} événement(s) trouvé(s)
                </h2>

                <!-- Liste des événements -->
                <div v-if="events.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="event in events.data" 
                        :key="event.idevenement"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <div v-if="event.bandeannonce" class="h-48 bg-gray-200">
                            <img :src="event.bandeannonce" :alt="event.titre" class="w-full h-full object-cover">
                        </div>
                        <div v-else class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <span class="text-white text-lg font-semibold">{{ event.titre.charAt(0) }}</span>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2">{{ event.titre }}</h3>
                            <p class="text-gray-600 mb-2 line-clamp-2">{{ event.description }}</p>
                            
                            <div class="flex items-center text-gray-500 mb-2">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                {{ event.lieu }}
                            </div>
                            
                            <div v-if="event.prochaine_date" class="flex items-center text-gray-500 mb-2">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                </svg>
                                {{ formatDate(event.prochaine_date.datedebut) }}
                            </div>
                            
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-lg font-bold text-blue-600">{{ formatPrice(event.prixbase) }}</span>
                                <Link 
                                    :href="route('evenement.show', event.idevenement)"
                                    class="hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
                                    style="background-color: #6B4E71;"
                                >
                                    Voir détails
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun résultat -->
                <div v-else class="text-center py-12">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 6.306a7.962 7.962 0 00-6 0M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun événement trouvé</h3>
                    <p class="text-gray-500">Essayez de modifier vos critères de recherche.</p>
                </div>

                <!-- Pagination -->
                <div v-if="events.links && events.links.length > 3" class="mt-8 flex justify-center">
                    <nav class="flex space-x-2">
                        <Link
                            v-for="link in events.links"
                            :key="link.label"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                                link.active 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-white text-gray-700 hover:bg-gray-50'
                            ]"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
