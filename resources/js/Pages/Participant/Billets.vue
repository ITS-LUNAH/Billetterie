<script setup>
import ParticipantLayout from '@/Layouts/ParticipantLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    billets: Object,
    message: String,
    stats: Object,
    error: String,
});
</script>

<template>
    <ParticipantLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ message }}</h1>
                    <p class="mt-2 text-gray-600">
                        Tous vos billets d'événements avec QR codes
                    </p>
                </div>

                <!-- Gestion des erreurs -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">{{ error }}</p>
                </div>

                <!-- Statistiques -->
                <div v-if="stats" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Billets</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Scannés</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.scannes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Non scannés</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.non_scannes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Validés</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.valides }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grille des billets -->
                <div class="bg-white shadow-sm rounded-lg border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Mes Billets
                        </h3>
                    </div>
                    
                    <div v-if="billets.data.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun billet</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Vous n'avez pas encore de billets.
                        </p>
                    </div>

                    <div v-else class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="billet in billets.data" 
                                :key="billet.id" 
                                class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-900">
                                        Billet #{{ billet.id }}
                                    </span>
                                    <span 
                                        :class="billet.est_scanne ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                    >
                                        {{ billet.est_scanne ? '✓ Scanné' : 'Non scanné' }}
                                    </span>
                                </div>
                                
                                <h4 class="font-medium text-gray-900 mb-2">
                                    {{ billet.evenement.titre }}
                                </h4>
                                
                                <p class="text-sm text-gray-600 mb-2">
                                    📍 {{ billet.evenement.lieu }}
                                </p>
                                
                                <p class="text-xs text-gray-500 mb-3">
                                    Réservation: {{ billet.reservation.numero }}
                                </p>
                                
                                <!-- QR Code placeholder -->
                                <div class="bg-gray-100 h-24 w-24 mx-auto mb-3 rounded flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </ParticipantLayout>
</template>