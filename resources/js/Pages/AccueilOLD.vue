<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

/* const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    upcomingEvents: {
        type: Array,
        default: () => []
    },
    
}); */

const props = defineProps(['canLogin', 'canRegister', 'upcomingEvents']);

// Variables réactives pour les champs de recherche
const searchTerm = ref('');
const searchQuery = ref('');
const searchDate = ref('');
const searchLocation = ref('');

const performSearch = () => {
    console.log('Recherche complète :', {
        query: searchQuery.value,
        date: searchDate.value,
        location: searchLocation.value
    });
    // Plus tard, vous redirigerez vers une page de résultats
    //Inertia.get(route('search.results', { q: searchQuery.value, date: searchDate.value, lieu: searchLocation.value }));
};

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>
<template>
    <Head title="Accueil" />
    <div class="relative min-h-screen bg-pink-100 flex flex-col">
    <div class="absolute inset-x-0 top-0 h-1/2 bg-gradient-to-br from-indigo-600 to-purple-800 -z-10"></div>
        <div class="relative w-full max-w-7xl mx-auto px-6 lg:px-8 z-10">

                    <br>  
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <div class="absolute inset-0 z-0 overflow-hidden">
                        <Link :href="'/'">
                            <img src="/images/EVUP.gif"
                                width="80"
                                height="80"
                                class="rounded-circle img-fluid"
                                alt="EVUP-LOGO">
                        </Link>
                    </div>
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                se connecter
                            </Link>

                            <!-- <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link> -->

                            <Link
                                :href="route('register.participant')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                S'inscrire
                            </Link>
                            <Link
                                :href="route('register.organisateur')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-black dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Devenir Organisateur
                            </Link>

                        </template>
                    </nav>

                    <main class="flex flex-col items-center justify-center min-h-[calc(100vh-200px)] px-6 py-12">
                    <section id="hero-search-section" class="flex flex-col items-center justify-center text-center py-16">
                    <div class="max-w-2xl mx-auto text-center mb-12">
                        <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Vivez vos événements comme jamais auparavant !</h1>
                        <p class="text-lg text-gray-600">Concerts, spectacles, conférences... réservez en quelques clics avec notre billetterie interactive et choisissez vos sièges en direct</p>
                    </div>

                    <h2 class="text-4xl font-bold text-gray-900 mb-8 text-center">Rechercher un événement</h2>
                    <div class="w-full max-w-2xl bg-white p-8 rounded-lg shadow-xl">
                        <div class="relative mb-4">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Nom de l'événement"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657m1.414-1.414a9 9 0 1111.314 0 9 9 0 01-11.314 0z"></path></svg>
                                </span>
                                <input
                                    type="text"
                                    v-model="searchLocation"
                                    placeholder="Lieu"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                                />
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </span>
                                <input
                                    type="date"
                                    v-model="searchDate"
                                    placeholder="mm/dd/yyyy"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                                />
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </span>
                            </div>
                        </div>

                        <button
                            @click="performSearch"
                            class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-lg"
                        >
                            Rechercher
                        </button>
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
                             class="bg-white rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105 hover:shadow-xl">
                            <img v-if="event.image" :src="event.image" :alt="event.titre" class="w-full aspect-9/16 object-cover transition-transform duration-300 hover:scale-110">
                            <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                Pas d'image
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-2xl text-gray-900 mb-2">{{ event.titre }}</h3>
                                
                                <div class="flex items-center text-gray-700 mb-5">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657m1.414-1.414a9 9 0 1111.314 0 9 9 0 01-11.314 0z"></path></svg>
                                    <span>{{ event.lieu }}</span>
                                </div>
                                <div class="flex items-center text-gray-700 mb-4">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ event.date }}</span>
                                </div>
                                <div class="flex items center text-gray-700 mb-5">
                                    <span class="text-indigo-600 font-bold text-xl">{{ event.prix }} MAD</span>
                                </div>
                                <div class="flex justify-between items-center mt-4 ">
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
                            <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center
                            transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                                <div class="bg-indigo-100 p-4 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">Trouvez votre événement</h3>
                                <p class="text-gray-600">
                                    Parcourez notre catalogue d'événements ou utilisez notre moteur de recherche pour trouver l'événement parfait.
                                </p>
                            </div>

                            <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center
                            transition-transform duration-300 hover:-translate-y-2 hover:shadow-xl">
                                <div class="bg-purple-100 p-4 rounded-full mb-6">
                                    <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.007 12.007 0 002.944 12c.041 1.01.272 2.012.673 2.973m6.526-4.973c1.396 0 2.656.762 3.391 1.955m-3.391-1.955c-1.396 0-2.656.762-3.391 1.955m0 0a2 2 0 01-1.955 3.391M12 21.056c-1.01-.041-2.012-.272-2.973-.673m-2.973.673a2.002 2.002 0 01-3.391-1.955 2.002 2.002 0 013.391-1.955m0 0a2.002 2.002 0 013.391 1.955 2.002 2.002 0 01-3.391 1.955zm6.526-4.973c1.396 0 2.656.762 3.391 1.955m-3.391-1.955c-1.396 0-2.656.762-3.391 1.955m0 0a2 2 0 01-1.955 3.391 2.002 2.002 0 013.391-1.955 2.002 2.002 0 01-1.955-3.391zm-2.973.673c1.01-.041 2.012-.272 2.973-.673"></path></svg>
                                </div>
                                <h3 class="font-bold text-2xl text-gray-900 mb-3">Choisissez vos places</h3>
                                <p class="text-gray-600">
                                    Sélectionnez vos places sur notre plan de salle interactif et visualisez votre vue avant d'acheter.
                                </p>
                            </div>

                            <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center
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

                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-black/70">
                    <p>&copy {{ new Date().getFullYear() }} EVUP - Tous droits réservés</p><br>
                </footer>
             </div> </div>
</template>
 