<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import EventSearchBar from '@/Components/EventSearchBar.vue';
import Chatbot from '@/Components/Chatbot.vue';
import { ref } from 'vue';


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    upcomingEvents: {
        type: Array,
        required: true,
    },
});

const categories = [
  { name: 'Festivals', icon: 'fa-calendar-days', slug: 'festivals' },
  { name: 'Théâtre & Humour', icon: 'fa-masks-theater', slug: 'theatre-humour' },
  { name: 'Jeune Public', icon: 'fa-child', slug: 'jeune-public' },
  { name: 'Salon & Formation', icon: 'fa-graduation-cap', slug: 'salon-formation' },
  { name: 'Cinéma', icon: 'fa-film', slug: 'cinema' },
  { name: 'Sport', icon: 'fa-futbol', slug: 'sport' }
];

const handleCategoryClick = (categorySlug) => {
  // Logique pour filtrer par catégorie
  router.get(route('events.category', categorySlug));
};

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}

const contactForm = ref({
  name: '',
  email: '',
  message: ''
});

const searchQuery = ref('');

const performSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('events.search'), { 
      search: searchQuery.value 
    });
  }
};
</script>

<template>
    <Head title="Accueil" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
      

    <div class="bg-transparent-400 text-black/50 text-black/50">
    <div id="animated-background" class="absolute -left-20 top-0 max-w-[877px] w-full h-full animate-pulse"></div>        
    <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">

              
              <!-- Section Catégories -->
    <section class=" py-8 mt-1">
      <div class="max-w-7xl mx-auto px-4">
        <div class="bg-gray/800 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
          <h2 class="text-2xl font-bold text-white text-center mb-6">
            Explorez par catégorie
          </h2>
        <div class="flex flex-wrap justify-center gap-4">
          <button
            v-for="category in categories"
            :key="category.slug"
            @click="handleCategoryClick(category.slug)"
            class="flex items-center space-x-3 bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-full transition-all duration-300 hover:scale-105 hover:shadow-lg backdrop-blur-sm border border-white/20"
          >
            <i :class="`fa-solid ${category.icon} text-lg`"></i>
            <span class="font-medium">{{ category.name }}</span>
          </button>
        </div>
        </div>
      </div>
    </section>
                <main class="mt-1">
                    <div class="grid grid-cols-1 gap-4">

                    <!-- Section Evenements a venir -->
                    <section id="upcoming-events-section" class="py-16 mt-1">
                    
                    <h1 class="text-4xl md:text-6xl font-bold text-white text-center mb-6">
                        Événements à Venir
                    </h1>
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
                                  <span class="bg-indigo-100 text-indigo-700 font-semibold px-3 py-1 rounded-full text-lg shadow-sm">
                                    {{ event.categorie }}
                                  </span>
                                </div>
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

            <section id="hero-search-section" class="flex flex-col items-center justify-center text-center py-16">
            <div class="relative z-10 py-12">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                    Découvrez les meilleurs événements
                </h1>
                <p class="text-xl text-white/80 mb-8">
                    Trouvez et réservez vos événements préférés en quelques clics
                </p>
            </div>

            <!-- Barre de recherche -->
            <div class="mb-1">
                <EventSearchBar />
            </div>

            </div>
            </section>

                    <!-- Section comment ca marche/demo -->
                    <section id="how-it-works-section" class="py-16 bg-transparent mt-1">
                    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                        <h2 class="text-4xl font-extrabold text-white mb-4">
                            Comment ça marche
                        </h2>
                        <p class="text-xl text-white mb-12">
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

        <!-- Section Contact/feedback -->
        <section id="contact" class="py-16 bg-white/40 rounded-lg mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
          Contactez-nous
        </h2>
        <p class="mt-4 text-xl text-gray-600">
          Nous sommes à votre disposition pour toute question.
        </p>
      </div>

      <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
        <!-- Carte Email -->
        <div class="bg-white/20 p-6 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-xl">
          <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
            <i class="fa-regular fa-envelope"></i>
        </div>
          <h3 class="mt-6 text-lg font-medium text-gray-900">Email</h3>
          <p class="mt-2 text-base text-gray-500">
            contact@evup.com
          </p>
        </div>

        <!-- Carte Téléphone -->
        <div class="bg-white/20 p-6 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-xl">
          <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
            <i class="fa-solid fa-phone"></i>
          </div>
          <h3 class="mt-6 text-lg font-medium text-gray-900">Téléphone</h3>
          <p class="mt-2 text-base text-gray-500">
            +212 1 23 45 67 89
          </p>
        </div>

        <!-- Carte Adresse -->
        <div class="bg-white/20 p-6 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105 hover:shadow-xl">
          <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
            <i class="fa-solid fa-location-dot"></i>
          </div>
          <h3 class="mt-6 text-lg font-medium text-gray-900">Adresse</h3>
          <p class="mt-2 text-base text-gray-500">
            123 Rue des Événements<br>
            75000 Rabat, Maroc
          </p>
        </div>
      </div>

      <!-- Formulaire de contact -->
      <div class="mt-16 bg-white/20 p-8 rounded-lg shadow-lg max-w-3xl mx-auto hover:shadow-xl mt-9">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
          Votre feedback nous intéresse </h2>
        <p class="mt-4 text-xl text-gray-600">
          Nous sommes à votre disposition pour toute question.
        </p>
        <form @submit.prevent="submitContactForm">
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
              <input type="text" id="name" v-model="contactForm.name" class="mt-1 bg-white/40 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" id="email" v-model="contactForm.email" class="mt-1 bg-white/40 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="sm:col-span-2">
              <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
              <textarea id="message" v-model="contactForm.message" rows="4" class="mt-1 bg-white/40 text-black block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
          </div>
          <div class="mt-6">
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
              Envoyer
            </button>
          </div>
        </form>
      </div>

      <!-- Nouvelle carte Réseaux sociaux -->
  <div class="bg-white/20 p-6 rounded-lg shadow-lg hover:shadow-xl mt-9">
    
    <h3 class="mt-6 text-lg font-medium text-gray-900">Réseaux sociaux</h3>
    <div class="mt-4 flex justify-center space-x-4">
      <a href="https://facebook.com" target="_blank" class="text-blue-600 hover:text-blue-800">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
        </svg>
      </a>
      <a href="https://twitter.com" target="_blank" class="text-blue-400 hover:text-blue-600">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
        </svg>
      </a>
      <a href="https://instagram.com" target="_blank" class="text-purple-600 hover:text-purple-800">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
        </svg>
      </a>
      <a href="https://linkedin.com" target="_blank" class="text-blue-700 hover:text-blue-900">
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
        </svg>
      </a>
    </div>
    <p class="mt-2 text-sm text-gray-500">
      Suivez-nous pour les dernières actualités
    </p>
  </div>
    </div>
        </section>

                        
                    </div>
                </main>

                
            </div>
        </div>
    </div>

    <Chatbot />
    </GuestLayout>
</template>
