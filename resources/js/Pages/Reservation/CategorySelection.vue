<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { computed, ref } from 'vue';

// Définir les props que le contrôleur va passer
const props = defineProps({
    evenement: {
        type: Object,
        required: true,
    },
     zones: {
        type: Array,
        default: () => [], 
    },
    canLogin: Boolean,
    canRegister: Boolean,
});

// Formulaire Inertia pour envoyer les données
const form = useForm({
    evenement_id: props.evenement.id,
    date_id: props.evenement.date_id, 
    tickets: props.zones.map(zone => ({ 
        zone_id: zone.idzone,          
        zone_name: zone.nomzone,       
        price_per_ticket: zone.tarif,  
        quantity: 0, // Initialisation de la quantité à 0
    })),
    type: 'Libre', // Pour indiquer le type de réservation
});

// Calcul du prix total réactif
const totalPrice = computed(() => {
    return form.tickets.reduce((sum, ticket) => {
        return sum + (ticket.quantity * ticket.price_per_ticket);
    }, 0).toFixed(2);
});

// Calcul du nombre total de tickets sélectionnés
const totalQuantity = computed(() => {
    return form.tickets.reduce((sum, ticket) => sum + ticket.quantity, 0);
});

// Soumission du formulaire
const submit = () => {
    if (totalQuantity.value === 0) {
        alert('Veuillez sélectionner au moins un ticket.');
        return;
    }
    form.post(route('reservation.store'), { // Cette route 'reservation.store' est la même pour les deux types
        onSuccess: () => {
            alert('Réservation soumise avec succès !');
            // Vous pouvez rediriger vers une page de payment ou de confirmation
            // router.visit(route('payment')); // Par exemple, si vous avez une page de paiement
            // router.visit(route('dashboard'));
        },
        onError: (errors) => {
            console.error('Erreur de soumission:', errors);
            alert('Une erreur est survenue lors de la réservation.');
        },
    });
};
</script>

<template>
    <Head :title="`Réserver pour ${evenement.titre}`" />

    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <Link :href="route('evenement.show', evenement.id)" class="text-blue-600 hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'événement
                </Link>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-6">
                Réserver pour "{{ evenement.titre }}" (Placement libre)
            </h1>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Choisissez vos tickets par zone tarifaire</h2>

                <form @submit.prevent="submit">
                    <div class="space-y-6 mb-8">
                        <div v-for="(ticket, index) in form.tickets" :key="index"
                             class="flex items-center justify-between border-b pb-4 last:border-b-0 last:pb-0">
                            <div>
                                <div class="flex items-center">
                                    <span 
                                        :style="`background-color: ${props.zones[index]?.couleur || '#ccc'}; display: inline-block; width: 15px; height: 15px; border-radius: 3px; margin-right: 8px;`"
                                    ></span>
                                    <h3 class="text-xl font-medium text-gray-800">{{ ticket.zone_name }}</h3>
                                </div>
                                <p class="text-lg font-semibold text-blue-600 mt-1">{{ ticket.price_per_ticket.toFixed(2) }} MAD / ticket</p>
                                <p class="text-sm text-gray-500">
                                    Places disponibles: {{ props.zones[index]?.places_disponibles || 'Non spécifié' }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button 
                                    type="button" 
                                    @click="ticket.quantity = Math.max(0, ticket.quantity - 1)"
                                    class="px-3 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors"
                                >
                                    -
                                </button>
                                <span class="text-xl font-bold w-8 text-center">{{ ticket.quantity }}</span>
                                <button 
                                    type="button" 
                                    @click="ticket.quantity = Math.min(props.zones[index]?.places_disponibles || 10, ticket.quantity + 1)"
                                    class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-800">Total :</span>
                        <span class="text-3xl font-bold text-blue-700">{{ totalPrice }} MAD</span>
                    </div>

                    <button 
                        type="submit"
                        :disabled="totalQuantity === 0"
                        :class="[
                            'mt-8 w-full py-3 font-semibold rounded-md transition-colors',
                            totalQuantity > 0 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        Confirmer la réservation ({{ totalQuantity }} ticket<span v-if="totalQuantity !== 1">s</span>)
                    </button>
                </form>
            </div>
        </div>
    </GuestLayout>
</template>
