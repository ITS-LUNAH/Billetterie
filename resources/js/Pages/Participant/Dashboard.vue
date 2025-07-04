<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ParticipantLayout from '@/Layouts/ParticipantLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    stats: Object,
    recent_tickets: Array,
    error: String,
});

// --- Fonction pour envoyer les billets par mail ---
const sendTicketsByEmail = async () => {
    if (!props.reservation || !props.reservation.idreservation) {
        alert('Erreur: ID de réservation manquant.');
        return;
    }

    try {
        const response = await axios.post(route('reservation.send-tickets', { reservation: props.reservation.idreservation }));
        alert(response.data.message); // Affiche le message de succès/échec du backend
    } catch (error) {
        console.error('Erreur lors de l\'envoi des billets par email:', error);
        alert('Une erreur est survenue lors de l\'envoi des billets par email. Veuillez réessayer.');
    }
};

// ---fonction pour télécharger les billets (PDF) ---
const downloadTicketsPdf = () => {
    if (!props.reservation || !props.reservation.idreservation) {
        alert('Erreur: ID de réservation manquant.');
        return;
    }
    window.open(route('reservation.download-tickets', { reservation: props.reservation.idreservation }), '_blank');
};

</script>

<template>
    <Head title="participant" />
    <AppLayout>
        <ParticipantLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de Bord Participant
            </h2>
        </template>

        <div class="min-h-screen bg-gray-100 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Message de bienvenue -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Bienvenue, {{ user?.name || 'Utilisateur' }} ! 👋
                    </h1>
                    <p class="text-gray-600">
                        Voici un aperçu de votre activité
                    </p>
                </div>

                <!-- Gestion des erreurs -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">{{ error }}</p>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.total_reservations }}</h3>
                        <p class="text-gray-600">Réservations</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ stats.total_billets }}</h3>
                        <p class="text-gray-600">Billets</p>
                    </div>
                </div>

                <!-- Derniers tickets -->
                <div class="bg-white rounded-lg shadow-sm border mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            🎫 Mes derniers billets
                        </h2>
                    </div>
                    
                    <div v-if="recent_tickets.length === 0" class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun billet</h3>
                        <p class="text-gray-500">Vous n'avez pas encore de billets</p>
                    </div>

                    <div v-else class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div 
                                v-for="ticket in recent_tickets" 
                                :key="ticket.id"
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                            >
                                <!-- En-tête du billet -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-900">
                                        Billet #{{ ticket.id }}
                                    </span>
                                    <span 
                                        :class="ticket.est_scanne ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                    >
                                        {{ ticket.est_scanne ? '✓ Scanné' : 'Non Scanné' }}
                                    </span>
                                </div>
                                
                                <!-- Informations de l'événement -->
                                <h4 class="font-medium text-gray-900 mb-1 text-sm">
                                    📝 {{ ticket.evenement.titre }}
                                </h4>
                                <p class="text-xs text-gray-600 mb-2">
                                    📍 {{ ticket.evenement.lieu }}
                                </p>
                                <p class="text-xs text-gray-500 mb-3">
                                    📅 {{ ticket.reservation.numero }} • {{ ticket.reservation.date }}
                                </p>
                                
                                <!-- QR Code Image (NEW) -->
                                <div v-if="ticket.qr_code_image" class="w-24 h-24 mx-auto mb-3">
                                    <img :src="ticket.qr_code_image" alt="QR Code du billet" class="w-full h-full object-contain">
                                </div>
                                <div v-else class="bg-gray-100 h-24 w-24 mx-auto rounded flex items-center justify-center mb-3">
                                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                </div>
                                <p class="text-[10px] text-gray-400 text-center">(Code : {{ ticket.qr_code_data }})</p>

                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Link 
                        href="/participant/reservations"
                        class="bg-white border border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors group"
                    >
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gray-200">
                            <svg class="h-6 w-6 text-grey-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">Mes Réservations</h3>
                        <p class="text-sm text-gray-500">Voir tous mes réservations</p>
                    </Link>

                    <Link 
                        href="/participant/billets"
                        class="bg-white border border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors group"
                    >
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-200">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">Mes Billets</h3>
                        <p class="text-sm text-gray-500">Voir tous mes billets</p>
                    </Link>

                </div>
            </div>
        </div>
        
        </ParticipantLayout>
    </AppLayout>
</template>