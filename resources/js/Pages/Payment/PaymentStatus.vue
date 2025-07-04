<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    status: { // 'success' ou 'cancelled'
        type: String,
        required: true,
    },
    message: { // Message à afficher à l'utilisateur
        type: String,
        required: true,
    },
    reservation: { // Les détails de la réservation
        type: Object,
        default: null,
    },
});

// Propriétés calculées pour le style et le texte
const isSuccess = computed(() => props.status === 'success');
const title = computed(() => isSuccess.value ? 'Paiement Réussi !' : 'Paiement Annulé');
const iconClass = computed(() => isSuccess.value ? 'text-green-500' : 'text-red-500');
const buttonClass = computed(() => isSuccess.value ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700');
const iconPath = computed(() => isSuccess.value
    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' // Checkmark circle
    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />' // X mark circle
);

// ---La fonction pour envoyer les billets par mail ---
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

// --- Lafonction pour télécharger les billets (PDF) ---
const downloadTicketsPdf = () => {
    if (!props.reservation || !props.reservation.idreservation) {
        alert('Erreur: ID de réservation manquant.');
        return;
    }
    window.open(route('reservation.download-tickets', { reservation: props.reservation.idreservation }), '_blank');
};

</script>

<template>
    <Head :title="title" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
        <div class="bg-white rounded-lg shadow-xl p-8 max-w-lg w-full text-center">
            <div class="mb-6">
                <svg :class="['w-20 h-20 mx-auto', iconClass]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" v-html="iconPath"></svg>
            </div>
            <h1 :class="['text-3xl font-bold mb-4', iconClass]">{{ title }}</h1>
            <p class="text-gray-700 text-lg mb-6">{{ message }}</p>

            <div v-if="isSuccess && reservation" class="border-t border-gray-200 pt-4 mt-6 text-left">
                <h2 class="text-xl font-semibold mb-3">Détails de votre réservation :</h2>
                <p class="text-gray-600"><strong>ID Réservation:</strong> {{ reservation.idreservation }}</p>
                <p class="text-gray-600"><strong>Statut:</strong> <span class="font-bold">{{ reservation.etat }}</span></p>
                <p class="text-gray-600"><strong>Date:</strong> {{ new Date(reservation.datereservation).toLocaleDateString() }}</p>
                 <div class="mt-6 flex flex-col space-y-3">
                    <button @click="sendTicketsByEmail" class="w-full px-6 py-3 font-semibold rounded-md text-white bg-indigo-600 hover:bg-blue-700 transition-colors duration-200">
                        Envoyer mes billets par email
                    </button>
                    <button @click="downloadTicketsPdf" class="w-full px-6 py-3 font-semibold rounded-md text-blue-800 bg-indigo-100 hover:bg-blue-200 transition-colors duration-200 border border-blue-300">
                        Télécharger mes billets (PDF)
                    </button>
                </div>
            </div>

            <div class="mt-8">
                <Link :href="route('accueil')" :class="['inline-block px-8 py-3 font-semibold text-white rounded-md transition-colors duration-200', buttonClass]">
                    Retour à l'accueil
                </Link>
            </div>
        </div>
    </div>
</template>