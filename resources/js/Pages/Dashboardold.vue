<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    message: String,
    reservations: {
        type: Array,
        default: () => [],
    },
    debug: Object,
});

// Fonction pour envoyer les billets par email
const sendTicketsByEmail = async (reservationId) => {
    if (!confirm('Confirmez-vous l\'envoi des billets par email pour cette réservation ?')) {
        return;
    }
    try {
        // Utiliser l'URL directement ou la fonction route globale
        const url = window.route ? window.route('reservation.send-tickets', { reservation: reservationId }) : `/reservation/${reservationId}/send-tickets`;
        const response = await axios.post(url);
        alert(response.data.message);
    } catch (error) {
        console.error('Erreur lors de l\'envoi des billets par email:', error);
        alert('Une erreur est survenue lors de l\'envoi des billets. Veuillez réessayer.');
    }
};

// Fonction pour télécharger les billets PDF
const downloadTicketsPdf = (reservationId) => {
    const url = window.route ? window.route('reservation.download-tickets', { reservation: reservationId }) : `/reservation/${reservationId}/download-tickets`;
    window.open(url, '_blank');
};

</script>

<template>
    <Head title="participant" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de Bord Participant
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-lg font-medium text-gray-900">{{ message }}</p>
                        
                        <!-- Section Debug détaillée -->
                        <div v-if="debug" class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-3">🔍 Informations de Debug</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p><strong>User ID:</strong> {{ debug.user_id }}</p>
                                    <p><strong>Email:</strong> {{ debug.user_email }}</p>
                                    <p><strong>Type utilisateur:</strong> {{ debug.user_type }}</p>
                                    <p><strong>Participant trouvé:</strong> {{ debug.participant_found ? 'Oui' : 'Non' }}</p>
                                    <p v-if="debug.participant_found"><strong>ID Participant:</strong> {{ debug.participant_id }}</p>
                                </div>
                                <div>
                                    <p><strong>Réservations (méthode 1):</strong> {{ debug.reservations_count_method1 }}</p>
                                    <p><strong>Réservations (méthode 2):</strong> {{ debug.reservations_count_method2 }}</p>
                                    <p><strong>Réservations (méthode 3):</strong> {{ debug.reservations_count_method3 }}</p>
                                    <p><strong>Réservations avec JOIN:</strong> {{ debug.reservations_with_user_join }}</p>
                                    <p v-if="debug.working_column"><strong>Colonne qui fonctionne:</strong> {{ debug.working_column }}</p>
                                </div>
                            </div>
                            
                            <!-- Colonnes de la table reservations -->
                            <div v-if="debug.reservation_table_columns" class="mt-4">
                                <h5 class="font-semibold text-blue-700">Colonnes table reservations:</h5>
                                <p class="text-xs bg-gray-100 p-2 rounded">{{ debug.reservation_table_columns.join(', ') }}</p>
                            </div>
                            
                            <!-- Échantillon de réservations -->
                            <div v-if="debug.sample_reservations && debug.sample_reservations.length > 0" class="mt-4">
                                <h5 class="font-semibold text-blue-700">Échantillon de réservations dans la DB:</h5>
                                <div class="max-h-32 overflow-y-auto bg-gray-100 p-2 rounded text-xs">
                                    <pre>{{ JSON.stringify(debug.sample_reservations, null, 2) }}</pre>
                                </div>
                            </div>
                            
                            <!-- Tous les participants -->
                            <div v-if="debug.all_participants && debug.all_participants.length > 0" class="mt-4">
                                <h5 class="font-semibold text-blue-700">Tous les participants:</h5>
                                <div class="max-h-32 overflow-y-auto bg-gray-100 p-2 rounded text-xs">
                                    <pre>{{ JSON.stringify(debug.all_participants, null, 2) }}</pre>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-semibold text-gray-800 mt-8 mb-4">Mes Réservations</h3>

                        <div v-if="reservations.length === 0" class="text-gray-600">
                            <p class="mb-4">Vous n'avez pas encore effectué de réservations.</p>
                            <Link href="/" class="text-blue-600 hover:underline">
                                Parcourez nos événements !
                            </Link>
                        </div>

                        <div v-else class="space-y-6">
                            <div v-for="reservation in reservations" :key="reservation.idreservation" 
                                 class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="text-xl font-bold text-gray-800">
                                        Réservation #{{ reservation.idreservation }}
                                    </h4>
                                    <span :class="[
                                        'px-3 py-1 rounded-full text-sm font-semibold',
                                        reservation.etat === 'payée' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                    ]">
                                        {{ reservation.etat.charAt(0).toUpperCase() + reservation.etat.slice(1) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <p class="text-gray-700 mb-2">
                                            <strong>Date de réservation :</strong> {{ reservation.datereservation }}
                                        </p>
                                        <p class="text-gray-700 mb-2">
                                            <strong>Événement :</strong> {{ reservation.evenement.titre }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-700 mb-2">
                                            <strong>Total billets :</strong> {{ reservation.total_billets }}
                                        </p>
                                        <p class="text-gray-700 mb-2">
                                            <strong>Prix total :</strong> {{ reservation.prix_total }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Détails des billets -->
                                <div v-if="reservation.billets && reservation.billets.length > 0" 
                                     class="mt-4 border-t border-gray-200 pt-3">
                                    <h5 class="text-lg font-semibold text-gray-700 mb-2">Détails des billets :</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        <div v-for="billet in reservation.billets" :key="billet.idbillet"
                                             class="bg-white p-3 rounded border">
                                            <p class="text-sm"><strong>Billet #{{ billet.idbillet }}</strong></p>
                                            <p class="text-sm text-gray-600">Zone: {{ billet.zone_nom }}</p>
                                            <p class="text-sm text-gray-600">Date: {{ billet.date_evenement }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div v-if="reservation.etat === 'payée'" class="mt-6 flex flex-wrap gap-3 justify-end">
                                    <button @click="sendTicketsByEmail(reservation.raw_id)" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 13h9a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Envoyer par email
                                    </button>
                                    <button @click="downloadTicketsPdf(reservation.raw_id)" 
                                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition duration-200 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Télécharger PDF
                                    </button>
                                </div>
                                <div v-if="reservation.etat !== 'payée'" class="mt-4 text-red-600 text-sm text-right">
                                    Cette réservation est en attente de paiement.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
