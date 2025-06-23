<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

// Définir les props que le contrôleur (PaymentController@create) va passer
const props = defineProps({
    sessionId: { // L'ID de la session Stripe Checkout créée par le backend
        type: String,
        required: true,
    },
    stripePublishableKey: { // Votre clé publiable Stripe
        type: String,
        required: true,
    },
});

// onMounted est un hook de cycle de vie Vue.js qui s'exécute une fois que le composant est monté dans le DOM.
onMounted(async () => {
    try {
        // La ligne `const stripe = Stripe(props.stripePublishableKey);`
        // et la ligne `await new Promise(...)` seront supprimées.
        // On s'attend à ce que 'Stripe' soit déjà disponible globalement.

        // --- Initialiser Stripe avec votre clé publiable ---
        // Assurez-vous que 'Stripe' est globalement disponible (chargé par le layout principal)
        const stripe = Stripe(props.stripePublishableKey); // Cette ligne reste

        // --- Rediriger l'utilisateur vers Stripe Checkout ---
        const { error } = await stripe.redirectToCheckout({
            sessionId: props.sessionId,
        });

        if (error) {
            console.error('Erreur lors de la redirection vers Stripe Checkout:', error);
            // Utilisez window.location.href pour une redirection complète en cas d'échec critique
            // Assurez-vous que 'route' est disponible ou utilisez une URL hardcodée si besoin.
            window.location.href = route('payment.cancel', { reservation: props.sessionId });
        }
    } catch (e) {
        console.error('Erreur générale dans PaymentPage onMounted:', e);
        window.location.href = route('payment.cancel', { reservation: props.sessionId });
    }
});
</script>

<template>
    <Head title="Redirection vers le paiement" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center p-6 bg-white rounded-lg shadow-xl">
            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 text-lg font-semibold mb-2">Redirection vers la page de paiement...</p>
            <p class="text-gray-500 text-sm">Veuillez ne pas fermer cette fenêtre.</p>
        </div>
    </div>
</template>

<style scoped>
/* Les styles Tailwind CSS sont suffisants ici. */
/* Si vous avez besoin de styles plus complexes, ajoutez-les ici. */
</style>
