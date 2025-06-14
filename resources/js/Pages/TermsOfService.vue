<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

const cgvContent = `<div class="prose max-w-4xl mx-auto p-6">
  <h1 class="text-3xl font-bold mb-6">Conditions Générales d'Utilisation</h1>

  <h2 class="text-2xl font-semibold mt-6">1. Acceptation des conditions</h2>
  <p>
    En utilisant [Nom de votre site/application], vous acceptez pleinement et sans réserve les présentes Conditions Générales d'Utilisation (CGU). 
  </p>

  <h2 class="text-2xl font-semibold mt-6">2. Description du service</h2>
  <p>
    [Nom de votre société] propose une plateforme de billetterie en ligne permettant :
  </p>
  <ul>
    <li>L'achat de billets pour des événements</li>
    <li>La gestion des réservations</li>
    <li>La réception de confirmations électroniques</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">3. Compte utilisateur</h2>
  <p>
    Pour effectuer des achats, vous devez :
  </p>
  <ul>
    <li>Créer un compte avec des informations exactes</li>
    <li>Maintenir la confidentialité de vos identifiants</li>
    <li>Être âgé d'au moins [âge requis] ans</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">4. Achats et paiements</h2>
  <p>Les règles applicables :</p>
  <ul>
    <li>Prix en [devise] TTC</li>
    <li>Paiement sécurisé via [solutions de paiement]</li>
    <li>Aucun droit de rétractation pour les billets (article L221-28 du Code de la consommation)</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">5. Utilisation des billets</h2>
  <p>
    Chaque billet est :
  </p>
  <ul>
    <li>Personnel et nominatif</li>
    <li>Non remboursable sauf annulation de l'événement</li>
    <li>Non cessible à titre commercial</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">6. Propriété intellectuelle</h2>
  <p>
    Le contenu du site (logos, textes, graphismes) est la propriété exclusive de [Nom de votre société]. Toute reproduction est interdite.
  </p>

  <h2 class="text-2xl font-semibold mt-6">7. Responsabilités</h2>
  <p>
    [Nom de votre société] décline toute responsabilité concernant :
  </p>
  <ul>
    <li>Le déroulement des événements</li>
    <li>Les modifications d'horaire/lieu par les organisateurs</li>
    <li>L'utilisation frauduleuse des billets</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">8. Modifications des CGU</h2>
  <p>
    Nous pouvons modifier ces conditions. La version applicable est celle en ligne à la date de votre commande.
  </p>

  <h2 class="text-2xl font-semibold mt-6">9. Loi applicable et juridiction</h2>
  <p>
    Les présentes CGU sont régies par le droit [pays]. Tout litige relève des tribunaux de [ville].
  </p>

  <h2 class="text-2xl font-semibold mt-6">10. Contact</h2>
  <p>
    Pour toute question : <strong>[email/contact]</strong>
  </p>
</div>`
;
</script>

<template>
    <Head title="Terms Of Service" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <AuthenticationCardLogo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose" v-html="cgvContent" />
            </div>
        </div>
    </div>
</template>
