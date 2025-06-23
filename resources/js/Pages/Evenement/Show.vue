<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { ref } from 'vue';
import SeatPlan from '@/Components/SeatPlan.vue';

// Définir les props que le contrôleur va passer
const props = defineProps({
    evenement: {
        type: Object,
        required: true,
        default: () => ({}),
    },
    // Si vous passez déjà les données du plan depuis Laravel, vous pouvez les ajouter ici
     planData: {
         type: Object,
         default: null,
     },
     canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
});

const isDescriptionTabActive = ref(true); // Pour gérer les onglets
const selectedSeatsForPlan = ref([]); // Pour stocker les sièges sélectionnés du composant SeatPlan

// Ces données devraient venir de la base de données via votre contrôleur pour l'événement numéroté
const planData = ref({
    totalSeats: 120, // Nombre total de sièges pour cet événement
    bookedSeats: [5, 12, 20, 35, 88], // Sièges déjà réservés pour cet événement
    sections: [ // Sections de sièges avec leurs prix et couleurs
        { name: 'Standard', color: '#90EE90', price: 50, range: [0, 59] },
        { name: 'VIP', color: '#ADD8E6', price: 80, range: [60, 99] },
        { name: 'Premium', color: '#FFD700', price: 120, range: [100, 119] },
    ],
    // initialPromotions: [], // Si vous avez des promotions par défaut
});

// Gérer la soumission de la réservation, que ce soit libre ou numérotée
const handleReservationSubmission = (seats = null, total = null) => {
    // Vérifier si evenement et type_placement sont définis pour éviter les erreurs
    if (!props.evenement || !props.evenement.type_placement) {
        console.error('Erreur: Les informations de l\'événement ou le type de placement sont manquants.');
        alert('Impossible de procéder à la réservation: informations manquantes.');
        return;
    }
const eventId = props.evenement.idevenement || props.evenement.idevenement;
if (!eventId) {
    console.error('Erreur: ID de l\'événement non défini');
    alert('Impossible de procéder à la réservation: ID de l\'événement manquant.');
    return;
}
    // Déboguer pour voir l'ID de l\'événement
    console.log('ID de l\'événement:', props.evenement.idevenement);
    
    if (props.evenement.type_placement === 'Libre') {
        // Utiliser la propriété idevenement qui est passée depuis le contrôleur
        window.location.href = route('reservation.category.create', eventId); //window.location.href :Pour contourner les problèmes potentiels avec Ziggy et Inertia
    } else if (props.evenement.type_placement === 'Numéroté') {
        // Logique pour le placement numéroté
        if (seats && seats.length > 0) {
            router.post(route('reservation.store'), {
                evenement_id: props.evenement.idevenement,
                selected_seats: seats,
                total_price: total,
                type: 'Numéroté',
            });
        } else {
            alert('Veuillez sélectionner au moins un siège pour la réservation numérotée.');
        }
    } else {
        console.warn('Type de placement inconnu:', props.evenement.type_placement);
    }
};

// Fonction pour déterminer le texte du bouton principal
const getButtonText = () => {
    // S'assurer que evenement est défini avant d'accéder à type_placement
    if (!props.evenement || !props.evenement.type_placement) {
        return 'Chargement...'; // Ou un texte par défaut
    }
    return props.evenement.type_placement === 'Libre' ? 'Réserver maintenant' : 'Sélectionner des sièges';
};
</script>
<template>
    <GuestLayout> 
    <Head :title="evenement.titre" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
        <div class="container mx-auto px-4 py-8">
                <div class="mb-6">
                    <Link :href="route('accueil')" class="text-white hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                        Retour 
                    </Link>
                </div>
            
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <div class="bg-white/70 rounded-lg shadow-md overflow-hidden">
                        <img :src="evenement.image" :alt="evenement.titre" class="w-full h-full object-cover">
                    </div>

                <div class="flex flex-col justify-between h-full">

                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        {{ evenement.titre }}
                    </h1>
                    
                    <div class="text-gray-600 mb-6 grid gap-2">

                            <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-1 text-gray-500"></i>
                            <span>{{ evenement.lieu }}</span>
                            </div>
                        
                        <div class="flex items-center space-x-4">

                            <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-1 text-gray-500"></i>
                            <span>{{ evenement.date }}</span>
                            </div>

                            <div class="flex items-center">
                            <i class="far fa-clock mr-1 text-gray-500"></i>
                            <span>{{ evenement.heuredebut }}</span>
                            </div>

                        </div>
                    </div>

                    <div class="bg-white/70 rounded-lg shadow-md p-6">

                        <div class="flex border-b border-gray-200 mb-4">
                            <div
                                :class="{'border-b-2 border-indigo-600 text-indigo-600': !isDescriptionTabActive, 'text-gray-600': isDescriptionTabActive}">
                                Informations pratiques
                            </div>
                        </div>

                        <div class="prose max-w-none text-gray-700">

                            <h3 class="font-semibold text-xl mb-2">
                                Description
                            </h3>
                            <p>{{ evenement.description }}</p>

                            <h3 class="font-semibold text-xl mb-2 mt-4">
                                Accès
                            </h3>
                            <p>{{ evenement.lieu }}</p>
                            <h3 class="font-semibold text-xl mb-2 mt-4">
                                Horaires
                            </h3>
                            <p>Ouverture des portes : {{ evenement.heureouvertureportes }}</p>
                            <p>Début du concert : {{ evenement.heuredebut }}</p>

                        </div>
                    </div>
                
                    <div class="mt-8 bg-white/70 rounded-lg shadow-md p-6">
                    
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">
                            Réservation
                        </h2>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-700">Prix à partir de</span>
                            <span class="text-3xl font-bold " style="color: #6B4E71;">{{ evenement.prix }} MAD</span>
                        </div>

                        <div class="mt-6 self-end">
                        <button
                            @click="handleReservationSubmission"
                            class="w-full py-3 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors mb-4"
                            style="background-color: #6B4E71;"
                        >
                            {{ getButtonText() }}
                        </button>
                        </div>
                        
                    </div>

                    <div v-if="evenement.type_placement === 'Numéroté'" class="mt-8 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Sélectionnez vos sièges
                    </h2>
                    <SeatPlan
                            :total-seats="planData.totalSeats"
                            :booked-seats="planData.bookedSeats"
                            :sections="planData.sections"
                            @update:selectedSeats="selectedSeatsForPlan = $event"
                            @submitReservation="handleReservationSubmission($event[0], $event[1])"
                        />
                </div>
            </div>
        </div>
    </div>
        
    </GuestLayout>
</template>