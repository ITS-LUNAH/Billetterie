<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import SeatPlan from '@/components/SeatPlan.vue';


// Définir les props que le contrôleur va passer
const props = defineProps({
    evenement: {
        type: Object,
        required: true,
        default: () => ({}),
    },
    // Si vous passez déjà les données du plan depuis Laravel, vous pouvez les ajouter ici
    // planData: {
    //     type: Object,
    //     default: null,
    // }
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
    console.log('Bouton cliqué!');
    console.log('Type de placement:', props.evenement.type_placement);
    if (props.evenement.type_placement === 'Libre') {
        // Logique de réservation pour placement libre (par exemple, un formulaire de quantité de tickets)
        // Redirection vers une page de confirmation/formulaire de réservation libre
        router.visit(route('reservation.create', { evenement: props.evenement.id, type: 'Libre' }));
    } else if (props.evenement.type_placement === 'numéroté') {
        // Logique de soumission des sièges sélectionnés
        if (seats && seats.length > 0) {
            console.log('Réservation de sièges numérotés:', seats, 'Prix total:', total);
            // Redirection vers une page de confirmation avec les sièges sélectionnés
            // Vous devrez créer cette route dans Laravel et le contrôleur correspondant
            router.post(route('reservation.store'), {
                evenement_id: props.evenement.id,
                selected_seats: seats,
                total_price: total,
                type: 'numéroté'
            });
        } else {
            alert('Veuillez sélectionner au moins un siège pour la réservation numérotée.');
        }
    }
};

// Fonction pour déterminer le texte du bouton principal (dans Show.vue)
const getButtonText = () => {
const text = props.evenement.type_placement === 'Libre' ? 'Réserver maintenant' : 'Sélectionner des sièges';
    console.log('Texte du bouton:', text);
    return text;};

// Si vous avez besoin d'utiliser `route()` dans votre script, importez le routeur d'Inertia
// import { router } from '@inertiajs/vue3';

</script>
<template>
    <Head :title="evenement.titre" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <MainLayout> 
        
        <div class="container mx-auto px-4 py-8">
            
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img :src="evenement.image" :alt="evenement.titre" class="w-full h-[800px] object-cover">
                    </div>

                <div class="flex flex-col justify-between h-full">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ evenement.titre }}</h1>
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

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex border-b border-gray-200 mb-4">
                            <div
                                :class="{'border-b-2 border-indigo-600 text-indigo-600': !isDescriptionTabActive, 'text-gray-600': isDescriptionTabActive}"
                                class="py-2 px-4 text-lg font-semibold focus:outline-none"
                            >
                                Informations pratiques
                            </div>
                        </div>

                        <div class="prose max-w-none text-gray-700">
                            <h3 class="font-semibold text-xl mb-2">Description</h3>
                            <p>{{ evenement.description }}</p>

                            <h3 class="font-semibold text-xl mb-2 mt-4">Accès</h3>
                            <p>{{ evenement.lieu }}</p>
                            <h3 class="font-semibold text-xl mb-2 mt-4">Horaires</h3>
                            <p>Ouverture des portes : {{ evenement.heureouvertureportes }}</p>
                            <p>Début du concert : {{ evenement.heuredebut }}</p>

                        </div>
                    </div>

                <div>
                <div v-if="evenement.type_placement === 'numéroté'" class="mt-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Sélectionnez vos sièges</h2>
                <SeatPlan
                            :total-seats="planData.totalSeats"
                            :booked-seats="planData.bookedSeats"
                            :sections="planData.sections"
                            @update:selectedSeats="selectedSeatsForPlan = $event"
                            @submitReservation="handleReservationSubmission($event[0], $event[1])"
                        />
                </div>
                </div>
                
                    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
                    
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Réservation</h2>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-700">Prix à partir de</span>
                            <span class="text-3xl font-bold text-indigo-600">{{ evenement.prix }} MAD</span>
                        </div>
                        <div class="mt-6 self-end">
                       <button
                            @click="handleReservationSubmission"
                            class="w-full py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors mb-4"
                        >
                            {{ getButtonText() }}
                        </button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
        </div>
        
    </MainLayout>
</template>