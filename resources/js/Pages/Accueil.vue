<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import EventSearchBar from '@/Components/EventSearchBar.vue';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    upcomingEvents: {
        type: Array,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Accueil" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
        
    <div class="bg-transparent-400 text-black/50 text-white/50">
<div id="animated-background" class="absolute -left-20 top-0 max-w-[877px] w-full h-full bg-[url('/image2.jpg')] animate-pulse"></div>        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                

                <main class="mt-6">
                    <div class="grid grid-cols-1 gap-4">

                    <section id="hero-search-section" class="flex flex-col items-center justify-center text-center py-16">
                    <div class="relative z-10 py-12">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-bold text-black mb-4">
                    Découvrez les meilleurs événements
                </h1>
                <p class="text-xl text-black/80 mb-8">
                    Trouvez et réservez vos événements préférés en quelques clics
                </p>
            </div>

            <!-- Barre de recherche -->
            <div class="mb-16">
                <EventSearchBar />
            </div>

        </div>
                    </section>

                    <section id="upcoming-events-section" class="py-16 mt-12">
                    <h2 class="text-4xl font-extrabold text-gray-800 mb-10 text-center">
                        Événements à Venir
                    </h2>
                    <p v-if="upcomingEvents.length === 0" class="text-center text-gray-600 text-lg">
                        Aucun événement à venir pour le moment. Revenez bientôt !
                    </p>
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="event in upcomingEvents" :key="event.id"
                             class="bg-white/40 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                            <img v-if="event.image" :src="event.image" :alt="event.titre" class="w-full aspect-9/16 object-cover transition-transform duration-300 hover:scale-110">
                            <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                Pas d'image
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">{{ event.titre }}</h3>
                                
                                <div class="flex items-center text-gray-700 mb-5">
                                    <i class="fa-solid fa-location-dot w-5 h-5 mr-2 text-indigo-500"></i>
                                    <span>{{ event.lieu }}</span>
                                </div>
                                <div class="flex items-center text-gray-700 mb-4">
                                    <i class="fa-regular fa-calendar w-5 h-5 mr-2 text-indigo-500"></i>
                                    <span>{{ event.date }}</span>
                                </div>
                                <div class="flex items center text-gray-700 mb-5">
                                    <span class="text-indigo-600 font-bold text-xl">{{ event.prix }} MAD</span>
                                </div>
                                <div class="flex justify-between items-center mt-9 ">
                                    <Link 
                                    :href="route('evenement.show', event.id)" 
                                    class="px-10 py-4 bg-indigo-600 text-white rounded-full hover:bg-indigo-400 transition-colors">
                                        Réserver
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    </section>

                    <section id="how-it-works-section" class="py-16 bg-transparent mt-12">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                        <h2 class="text-4xl font-extrabold text-gray-800 mb-4">
                            Comment ça marche
                        </h2>
                        <p class="text-xl text-gray-600 mb-12">
                            Notre plateforme simplifie la réservation d'événements et la gestion de billetterie.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-white/40 rounded-xl shadow-lg p-8 flex flex-col items-center text-center
                            transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                                <div class="bg-indigo-100 p-4 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">Trouvez votre événement</h3>
                                <p class="text-gray-600">
                                    Parcourez notre catalogue d'événements ou utilisez notre moteur de recherche pour trouver l'événement parfait.
                                </p>
                            </div>

                            <div class="bg-white/40 rounded-xl shadow-lg p-8 flex flex-col items-center text-center
                            transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                                <div class="bg-purple-100 p-4 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.007 12.007 0 002.944 12c.041 1.01.272 2.012.673 2.973m6.526-4.973c1.396 0 2.656.762 3.391 1.955m-3.391-1.955c-1.396 0-2.656.762-3.391 1.955m0 0a2 2 0 01-1.955 3.391M12 21.056c-1.01-.041-2.012-.272-2.973-.673m-2.973.673a2.002 2.002 0 01-3.391-1.955 2.002 2.002 0 013.391-1.955m0 0a2.002 2.002 0 013.391 1.955 2.002 2.002 0 01-3.391 1.955zm6.526-4.973c1.396 0 2.656.762 3.391 1.955m-3.391-1.955c-1.396 0-2.656.762-3.391 1.955m0 0a2 2 0 01-1.955 3.391 2.002 2.002 0 013.391-1.955 2.002 2.002 0 01-1.955-3.391zm-2.973.673c1.01-.041 2.012-.272 2.973-.673"></path></svg>
                                </div>
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">Choisissez vos places</h3>
                                <p class="text-gray-600">
                                    Sélectionnez vos places sur notre plan de salle interactif et visualisez votre vue avant d'acheter.
                                </p>
                            </div>

                            <div class="bg-white/40 rounded-xl shadow-lg p-8 flex flex-col items-center text-center
                            transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                                <div class="bg-green-100 p-4 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M3 19l6 3V6m0 0L3 6m6 0v13m0-13l6-3"></path></svg>
                                </div>
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">Organisez vos événements</h3>
                                <p class="text-gray-600">
                                    Créez et gérez vos propres événements, définissez des zones tarifaires et suivez vos ventes en temps réel.
                                </p>
                            </div>
                        </div>
                    </div>
                    </section>
                        
                    </div>
                </main>

                
            </div>
        </div>
    </div>
    </GuestLayout>
</template>
