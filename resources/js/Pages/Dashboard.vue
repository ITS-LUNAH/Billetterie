<script setup>
import ParticipantLayout from '@/Layouts/ParticipantLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    message: String,
    reservations: Array,
    participant: Object,
    stats: Object,
    error: String,
});

const getStatusColor = (etat) => {
    const colors = {
        'confirmee': 'bg-green-100 text-green-800 border-green-200',
        'en_attente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'annulee': 'bg-red-100 text-red-800 border-red-200',
        'payee': 'bg-blue-100 text-blue-800 border-blue-200',
    };
    return colors[etat] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusText = (etat) => {
    const texts = {
        'confirmee': 'Confirmée',
        'en_attente': 'En attente',
        'annulee': 'Annulée',
        'payee': 'Payée',
    };
    return texts[etat] || etat;
};
</script>

<template>
    <ParticipantLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- En-tête avec message d'accueil -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ message }}</h1>
                    <p class="mt-2 text-gray-600">
                        Consultez vos réservations et gérez vos billets d'événements.
                    </p>
                </div>

                <!-- Gestion des erreurs -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-800 font-medium">{{ error }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div v-if="stats" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Réservations</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.total_reservations }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
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
                                    <p class="text-sm font-medium text-gray-600">Confirmées</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.reservations_confirmees }}</p>
                                </div>
                            </div>
                        </div>
                    </div> -->

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
                                    <p class="text-2xl font-bold text-gray-900">{{ stats.billets_total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des réservations -->
                <div class="bg-white shadow-sm rounded-lg border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Mes Réservations
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Historique de vos réservations d'événements
                        </p>
                    </div>
                    
                    <div v-if="reservations.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune réservation</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Vous n'avez pas encore effectué de réservation.
                        </p>
                    </div>

                    <div v-else class="divide-y divide-gray-200">
                        <div v-for="reservation in reservations" :key="reservation.id" class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ reservation.evenement.titre }}
                                            </h4>
                                            <span 
                                                :class="getStatusColor(reservation.etat)"
                                                class="ml-2 inline-flex px-2 py-1 text-xs font-medium rounded-full border"
                                            >
                                                {{ getStatusText(reservation.etat) }}
                                            </span>
                                        </div>
                                        <div class="mt-1">
                                            <p class="text-sm text-gray-600">
                                                📍 {{ reservation.evenement.lieu }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                🗓️ {{ reservation.date_reservation }} • {{ reservation.numero }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold text-gray-900">
                                        {{ reservation.prix_total }} {{ reservation.devise }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ reservation.total_billets }} billet(s)
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Détails des billets -->
                            <div v-if="reservation.billets.length > 0" class="mt-4 ml-16">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-xs font-medium text-gray-700 mb-3">Mes Billets</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        <div 
                                            v-for="billet in reservation.billets" 
                                            :key="billet.id"
                                            class="flex items-center justify-between bg-white rounded-md p-3 border"
                                        >
                                            <div class="flex items-center">
                                                <div class="w-2 h-2 rounded-full mr-2" 
                                                     :class="billet.est_scanne ? 'bg-green-400' : 'bg-gray-300'">
                                                </div>
                                                <span class="text-sm text-gray-700">Billet #{{ billet.id }}</span>
                                            </div>
                                            <span class="text-xs font-medium"
                                                  :class="billet.est_scanne ? 'text-green-600' : 'text-gray-500'">
                                                {{ billet.est_scanne ? '✓ Scanné' : 'Non scanné' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ParticipantLayout>
</template>
