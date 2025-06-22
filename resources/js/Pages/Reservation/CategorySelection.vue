<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { computed, ref ,watch  } from 'vue';
import axios from 'axios';

// Définir les props que le contrôleur va passer
const props = defineProps({
    evenement: {
        type: Object,
        required: true,
    },
    // Les zones initiales (pour la date par défaut) sont passées ici.
    // Elles ne sont utilisées que pour l'initialisation.
     zones: {
        type: Array,
        default: () => [], 
    },
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
    datesDisponibles: {
        type: Array,
        default: () => [],
    },

});

// --- VARIABLES ET FONCTIONS POUR LA BOÎTE DE DIALOGUE PERSONNALISÉE (REMPLACE alert()) ---
const showModal = ref(false);
const modalTitle = ref('');
const modalMessage = ref('');

const displayModal = (title, message) => {
    modalTitle.value = title;
    modalMessage.value = message;
    showModal.value = true;
};

// Variable pour stocker l'ID de la date actuellement sélectionnée par l'utilisateur
// Initialisée avec la date_id par défaut reçue du contrôleur
const selectedDateId = ref(props.evenement.date_id);

// Variable réactive pour stocker les zones ACTIVES (qui peuvent changer) 
// On initialise avec les zones passées par props (celles de la date par défaut)
const currentZones = ref(props.zones);

// Fonction pour initialiser/réinitialiser les tickets du formulaire
const initializeFormTickets = (zonesData) => {
    if (!Array.isArray(zonesData)) {
        console.error("initializeFormTickets a reçu des données invalides:", zonesData);
        return [];
    }
    return zonesData.map(zone => ({
        zone_id: zone.idzone,
        zone_name: zone.nomzone,
        price_per_ticket: zone.tarif,
        quantity: 0,
        // La propriété places_disponibles n'est pas stockée dans le formulaire
        // car elle est dynamique et sera toujours lue depuis currentZones.
        // On la garde dans 'zone' pour la limite max du bouton '+'.
    }));
};

// Initialisation du formulaire Inertia
const form = useForm({
    evenement_id: props.evenement.idevenement,
    date_id: props.evenement.date_id,
    tickets: initializeFormTickets(currentZones.value), // Initialise avec les zones par défaut
    type: 'Libre',
});

// Watcher pour détecter les changements sur selectedDateId
// Quand l'utilisateur choisit une nouvelle date, on fait un appel AJAX.
watch(selectedDateId, async (newDateId) => {
    form.date_id = newDateId; // Mettre à jour l'ID de la date dans le formulaire pour la soumission finale

    if (newDateId) {
        try {
            // Appel AJAX avec Axios ---
            // On appelle notre route API pour obtenir les zones pour cette nouvelle date
            const response = await axios.get(route('api.zones_for_date', {
                evenement: props.evenement.idevenement,
                date: newDateId
            }));

            // Mettre à jour les zones affichées dans le composant
            currentZones.value = response.data;

            // Réinitialiser les tickets du formulaire avec les nouvelles zones
            form.tickets = initializeFormTickets(currentZones.value);
                // On peut aussi passer la disponibilité directement ici si on veut la stocker dans le formulaire
                // places_disponibles: zone.places_disponibles
            

        } catch (error) {
            console.error('Erreur lors de la récupération des zones pour la nouvelle date:', error);
            alert('Impossible de charger les zones pour cette date. Veuillez réessayer.');
            // Revenir à l'ancienne date ou gérer l'erreur
        }
    }
}, { immediate: true }); // 'immediate: true' pour qu'il s'exécute au premier chargement

// Calcul du prix total réactif
const totalPrice = computed(() => {
    // Il faut s'assurer que form.tickets est un tableau d'objets valides
    if (!form.tickets || form.tickets.length === 0) return 0;

    return form.tickets.reduce((sum, ticket) => {
        // S'assurer que ticket.quantity et ticket.price_per_ticket sont des nombres
        const quantity = Number(ticket.quantity) || 0;
        const price = Number(ticket.price_per_ticket) || 0;
        return sum + (quantity * price);
    }, 0).toFixed(2);
});

// Calcul du nombre total de tickets sélectionnés
const totalQuantity = computed(() => {
    // S'assurer que form.tickets est un tableau d'objets valides
    if (!form.tickets || form.tickets.length === 0) return 0;

    return form.tickets.reduce((sum, ticket) => {
        // S'assurer que ticket.quantity est un nombre
        const quantity = Number(ticket.quantity) || 0;
        return sum + quantity;
    }, 0);
});

// Fonction pour obtenir le ticket correspondant dans form.tickets
const getTicketForZone = (zoneId) => {
    return form.tickets.find(t => t.zone_id === zoneId);
};

// Fonctions pour les boutons + et -
const decrementQuantity = (zone) => {
    const ticket = getTicketForZone(zone.idzone);
    if (ticket) {
        ticket.quantity = Math.max(0, ticket.quantity - 1);
    }
};

const incrementQuantity = (zone) => {
    const ticket = getTicketForZone(zone.idzone);
    if (ticket) {
        // La limite max est la disponibilité actuelle de la zone
        ticket.quantity = Math.min(zone.places_disponibles, ticket.quantity + 1);
    }
};

// Soumission du formulaire
const submit = () => {
    if (totalQuantity.value === 0) {
        displayModal('Sélection de tickets', 'Veuillez sélectionner au moins un ticket.');
        return;
    }

    form.post(route('reservation.store'), {
        onSuccess: () => {
            // !!! TRÈS IMPORTANT : LE CONTRÔLEUR LARAVEL GÈRE LA REDIRECTION VERS LA PAGE DE PAIEMENT.
            // Inertia intercepte cette redirection et navigue côté client.
            // Il n'y a PAS besoin de router.visit() ou de alert() ici.
            // Toute alerte ici serait affichée AVANT que la navigation ne commence.
            // Si vous voulez un message de succès, il est préférable de l'afficher sur la page de paiement.
        },
        onError: (errors) => {
            console.error('Erreur de soumission:', errors);
            let errorMessage = 'Une erreur est survenue lors de la réservation.';
            if (errors && errors.error) {
                errorMessage = errors.error;
            } else if (Object.keys(errors).length > 0) {
                errorMessage = Object.values(errors).flat().join('\n');
            }
            displayModal('Erreur de réservation', errorMessage);
        },
    });
};
</script>

<template>
    <GuestLayout>
    <Head :title="evenement.titre + ' - Réservation'" />

        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <Link :href="route('evenement.show', {event: evenement.idevenement})" class="text-indigo-100 hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour 
                </Link>
            </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white/10 rounded-lg shadow-md overflow-hidden">
                <img :src="evenement.image" :alt="evenement.titre" class="w-full h-full object-cover">
            </div>

        <div class="flex flex-col justify-between h-full">

            <h1 class="text-3xl font-bold text-white mb-6">
                Réserver pour "{{ evenement.titre }}" (Placement libre)
            </h1>

            <div class="bg-white/70 rounded-lg shadow-md p-6">

                <div class="mb-6" v-if="datesDisponibles.length > 0">
                    <label for="event_date" class="text-xl block text-black text-sm font-bold mb-2">
                        Choisissez une date :
                    </label>

                <div v-if="datesDisponibles.length > 1" class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        v-for="date in datesDisponibles"
                        :key="date.iddate"
                        @click="selectedDateId = date.iddate"
                        :class="[
                            'px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200',
                            selectedDateId === date.iddate
                                ? 'bg-indigo-600 text-white shadow-md'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                        ]"
                    >
                        {{ date.datedebut }}
                        <span v-if="date.nomlieu" class="text-xs opacity-80 ml-1">({{ date.nomlieu }})</span>
                    </button>
                </div>

                <div v-else-if="datesDisponibles.length === 1">
                    <p class="text-lg font-medium text-gray-700">
                        {{ datesDisponibles[0].datedebut }}
                        <span v-if="datesDisponibles[0].nomlieu" class="text-base opacity-80 ml-1">({{ datesDisponibles[0].nomlieu }})</span>
                    </p>
                </div>

                 <div v-else class="text-center py-4 text-red-600">
                    Aucune date future disponible pour cet événement.
                </div>
            </div>

            <h2 class="text-2xl font-semibold mb-4">
                Choisissez vos tickets :
            </h2>

            <form @submit.prevent="submit">

                <div v-if="currentZones.length === 0" class="text-center py-4 text-red-600">
                    Aucune zone tarifaire disponible pour la date sélectionnée.
                </div>

                <div v-else class="space-y-6 mb-8">
                    <div v-for="zone in currentZones" :key="zone.idzone" class="flex items-center justify-between border-b pb-4 last:border-b-0 last:pb-0">
                        <div>
                            <div class="flex items-center">
                                <span
                                    :style="`background-color: ${zone.couleur || '#ccc'}; display: inline-block; width: 15px; height: 15px; border-radius: 3px; margin-right: 8px;`"
                                ></span>
                                <h3 class="text-xl font-medium text-gray-800">{{ zone.nomzone }}</h3>
                            </div>

                            <p class="text-lg font-semibold text-indigo-600 mt-1">{{ zone.tarif }} MAD / ticket</p>
                            <p class="text-sm text-gray-500">
                                Places disponibles: {{ zone.places_disponibles }}
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button
                                type="button"
                                @click="decrementQuantity(zone)"
                                :disabled="getTicketForZone(zone.idzone).quantity === 0"
                                class="px-3 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                -
                            </button>
                            <span class="text-xl font-bold w-8 text-center">{{ getTicketForZone(zone.idzone).quantity }}</span>
                            <button
                                type="button"
                                @click="incrementQuantity(zone)"
                                :disabled="getTicketForZone(zone.idzone).quantity >= zone.places_disponibles"
                                class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 mt-4 flex justify-between items-center">
                    <span class="text-2xl font-bold text-gray-800">Total :</span>
                    <span class="text-3xl font-bold text-indigo-700">{{ totalPrice }} MAD</span>
                </div>

                <button
                    type="submit"
                    :disabled="totalQuantity === 0"
                    :class="[
                        'mt-8 w-full py-3 font-semibold rounded-md transition-colors',
                        totalQuantity > 0
                            ? 'bg-indigo-600 hover:bg-indigo-700 text-white'
                            : 'bg-gray-300 text-gray-500 cursor-not-allowed']">
                    Confirmer la réservation ({{ totalQuantity }} ticket<span v-if="totalQuantity !== 1">s</span>)
                </button>
            </form>
        
            </div>
        </div>
        </div>
        </div>
    </GuestLayout>
</template>
