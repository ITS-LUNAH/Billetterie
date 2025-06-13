<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

const props = defineProps({
    reservation: {
        type: Object,
        required: true,
    },
    evenement: {
        type: Object,
        required: true,
    },
    billets_by_zone: {
        type: Array,
        required: true,
    },
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Paiement" />

    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <Link :href="route('evenement.show', evenement.idevenement)" class="text-blue-600 hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'événement
                </Link>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Paiement</h1>
                
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Récapitulatif de votre réservation</h2>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h3 class="font-medium text-lg mb-2">{{ evenement.titre }}</h3>
                        <p class="text-gray-600 mb-2">Réservation #{{ reservation.idreservation }}</p>
                        <p class="text-gray-600">Date de réservation: {{ reservation.datereservation }}</p>
                    </div>
                    
                    <div class="border-t border-b py-4">
                        <h3 class="font-medium mb-3">Billets sélectionnés:</h3>
                        <div v-for="(billet, index) in billets_by_zone" :key="index" class="flex justify-between mb-2">
                            <span>{{ billet.zone_name }} ({{ billet.quantity }} x {{ billet.price_per_ticket.toFixed(2) }} MAD)</span>
                            <span class="font-medium">{{ billet.total.toFixed(2) }} MAD</span>
                        </div>
                        
                        <div class="flex justify-between mt-4 pt-2 border-t">
                            <span class="font-bold">Total:</span>
                            <span class="font-bold text-blue-700">{{ reservation.montant_total.toFixed(2) }} MAD</span>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Choisissez votre méthode de paiement</h3>
                    
                    <div class="space-y-3">
                        <div class="border rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-50">
                            <input type="radio" id="card" name="payment_method" class="mr-3" checked />
                            <label for="card" class="flex-1 cursor-pointer">
                                <div class="font-medium">Carte bancaire</div>
                                <div class="text-sm text-gray-500">Visa, Mastercard, etc.</div>
                            </label>
                            <div class="flex space-x-1">
                                <div class="w-10 h-6 bg-blue-600 rounded"></div>
                                <div class="w-10 h-6 bg-red-500 rounded"></div>
                            </div>
                        </div>
                        
                        <div class="border rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-50">
                            <input type="radio" id="paypal" name="payment_method" class="mr-3" />
                            <label for="paypal" class="flex-1 cursor-pointer">
                                <div class="font-medium">PayPal</div>
                                <div class="text-sm text-gray-500">Paiement sécurisé en ligne</div>
                            </label>
                            <div class="w-10 h-6 bg-blue-400 rounded"></div>
                        </div>
                    </div>
                </div>

                <form :action="route('payment.store', reservation.idreservation)" method="POST">
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors">
                        Procéder au paiement
                    </button>
                </form>
                
                <p class="mt-4 text-sm text-gray-500 text-center">
                    Cette page est une démonstration. Le paiement réel sera implémenté ultérieurement.
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
