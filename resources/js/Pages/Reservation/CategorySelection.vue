<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref } from 'vue';

// Définir les props que le contrôleur va passer
const props = defineProps({
    evenement: {
        type: Object,
        required: true,
    },
    // Ces catégories viendront de Laravel (par exemple de la BDD ou un tableau configuré)
     zones: {
        type: Array,
        default: () => [], // S'attend à un tableau d'objets ZoneTarifaire
    },
});

// Formulaire Inertia pour envoyer les données
const form = useForm({
    evenement_id: props.evenement.id,
    tickets: props.zones.map(zone => ({ // <-- Correction ici: props.categories.map -> props.zones.map
        zone_id: zone.idzone,          // <-- Correction ici: cat.id -> zone.idZone
        zone_name: zone.nomzone,       // <-- Correction ici: cat.name -> zone.nomZone
        price_per_ticket: zone.tarif,  // <-- Correction ici: cat.price -> zone.tarif
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
    form.post(route('reservation.store'), { // Cette route 'reservation.store' sera la même pour les deux types
        onSuccess: () => {
            alert('Réservation soumise avec succès !');
            // Vous pouvez rediriger vers une page de confirmation ou le tableau de bord
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

    <MainLayout>
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">
                Réserver pour "{{ evenement.titre }}" (Placement libre)
            </h1>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Choisissez vos tickets par zone tarifaire</h2>

                <form @submit.prevent="submit">
                    <div class="space-y-6 mb-8">
                        <div v-for="zone in form.tickets" :key="zone.zone_id"
                             class="flex items-center justify-between border-b pb-4 last:border-b-0 last:pb-0">
                            <div>
                                <h3 class="text-xl font-medium text-gray-800">{{ zone.zone_name }}</h3>
                                <span :style="`background-color: ${zones.find(z => z.idzone === zone.zone_id)?.couleur}; display: inline-block; width: 15px; height: 15px; border-radius: 3px; margin-right: 5px;`"></span>
                                <p class="text-lg font-semibold text-indigo-600">{{ zone.price_per_ticket.toFixed(2) }} MAD / ticket</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" @click="zone.quantity = Math.max(0, zone.quantity - 1)"
                                        class="px-3 py-1 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                    -
                                </button>
                                <span class="text-xl font-bold">{{ zone.quantity }}</span>
                                <button type="button" @click="zone.quantity++"
                                        class="px-3 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4 mt-4 flex justify-between items-center">
                        <span class="text-2xl font-bold text-gray-800">Total :</span>
                        <span class="text-3xl font-bold text-indigo-700">{{ totalPrice }} MAD</span>
                    </div>

                    <button type="submit"
                            class="mt-8 w-full py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors">
                        Confirmer la réservation ({{ totalQuantity }} ticket<span v-if="totalQuantity > 1">s</span>)
                    </button>
                </form>
            </div>
        </div>
    </MainLayout>
</template>