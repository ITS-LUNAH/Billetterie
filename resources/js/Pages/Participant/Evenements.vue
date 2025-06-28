<script setup>
import ParticipantLayout from '@/Layouts/ParticipantLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
// import { route } from 'vue-router';

const props = defineProps({
    evenements: Object,
    message: String,
    filters: Object,
    error: String,
});

const search = ref(props.filters.search);

const searchEvenements = () => {
    router.get('/participant/evenements'), { search: search.value }, {
        preserveState: true,
        replace: true,
    };
};
</script>

<template>
    <ParticipantLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ message }}</h1>
                    <p class="mt-2 text-gray-600">
                        Découvrez les événements disponibles
                    </p>
                </div>

                <!-- Barre de recherche -->
                <div class="mb-6">
                    <div class="max-w-md">
                        <div class="relative">
                            <input
                                v-model="search"
                                @keyup.enter="searchEvenements"
                                type="text"
                                placeholder="Rechercher un événement..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <button
                                @click="searchEvenements"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <span class="text-sm text-indigo-600 hover:text-indigo-500">Rechercher</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gestion des erreurs -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">{{ error }}</p>
                </div>

                <!-- Grille des événements -->
                <div v-if="evenements.data.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v10a2 2 0 002 2h4a2 2 0 002-2V7m-8 0H4a2 2 0 00-2 2v10a2 2 0 002 2h4m4-10v10a2 2 0 002 2h4a2 2 0 002-2V9a2 2 0 00-2-2h-4z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun événement trouvé</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aucun événement ne correspond à votre recherche.
                    </p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div 
                        v-for="evenement in evenements.data" 
                        :key="evenement.id" 
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <!-- Image placeholder -->
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                {{ evenement.titre }}
                            </h3>
                            
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ evenement.description }}
                            </p>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ evenement.lieu }}
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-indigo-600">
                                    {{ evenement.prix_base }} MAD
                                </span>
                                <Link 
                                    :href="`/participant/evenements/${evenement.id}`"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700"
                                >
                                    Voir détails
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </ParticipantLayout>
</template>
