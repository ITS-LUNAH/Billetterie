<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

const Policy = `<div class="prose max-w-4xl mx-auto p-6">
  <h1 class="text-3xl font-bold mb-6">Politique de Confidentialité</h1>
  
  <p><em>Dernière mise à jour : [Date]</em></p>

  <h2 class="text-2xl font-semibold mt-6">1. Collecte des données</h2>
  <p>
    [Nom de votre société] collecte les données suivantes lorsque vous utilisez notre site [URL] :
  </p>
  <ul>
    <li><strong>Données personnelles</strong> : Nom, prénom, adresse e-mail, numéro de téléphone, adresse de facturation.</li>
    <li><strong>Données de paiement</strong> : Informations de carte bancaire (traitées de manière sécurisée par notre prestataire de paiement [ex: Stripe]).</li>
    <li><strong>Données de navigation</strong> : Adresse IP, cookies, pages visitées.</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">2. Finalités du traitement</h2>
  <p>Vos données sont utilisées pour :</p>
  <ul>
    <li>Traiter vos commandes de billets.</li>
    <li>Vous envoyer des confirmations et informations relatives aux événements.</li>
    <li>Améliorer l'expérience utilisateur (analyse via Google Analytics).</li>
    <li>Envoyer des offres promotionnelles (si vous y consentez).</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">3. Partage des données</h2>
  <p>
    Vos données ne sont partagées qu'avec :
  </p>
  <ul>
    <li>Les organisateurs d'événements (pour la validation des billets).</li>
    <li>Nos prestataires techniques (hébergeur, solution de paiement).</li>
    <li>Les autorités légales si requis par la loi.</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">4. Cookies</h2>
  <p>
    Nous utilisons des cookies pour :
  </p>
  <ul>
    <li>Fonctionnement du panier d'achat.</li>
    <li>Analyse d'audience (Google Analytics).</li>
  </ul>
  <p>
    Vous pouvez les désactiver via les paramètres de votre navigateur.
  </p>

  <h2 class="text-2xl font-semibold mt-6">5. Durée de conservation</h2>
  <p>
    Vos données sont conservées :
  </p>
  <ul>
    <li>Données de commande : 5 ans (obligation légale).</li>
    <li>Données de contact marketing : Jusqu'à votre désinscription.</li>
  </ul>

  <h2 class="text-2xl font-semibold mt-6">6. Vos droits</h2>
  <p>
    Conformément au [RGPD/Loi locale], vous pouvez :
  </p>
  <ul>
    <li>Accéder à vos données ou les rectifier.</li>
    <li>Demander leur suppression.</li>
    <li>Vous opposer à leur utilisation.</li>
  </ul>
  <p>
    Pour exercer ces droits, contactez-nous à : <strong>[email de contact DPO]</strong>.
  </p>

  <h2 class="text-2xl font-semibold mt-6">7. Sécurité</h2>
  <p>
    Nous utilisons le protocole HTTPS et chiffrons les données sensibles.
  </p>

  <h2 class="text-2xl font-semibold mt-6">8. Modifications</h2>
  <p>
    Cette politique peut être mise à jour. Les changements seront notifiés par e-mail ou sur notre site.
  </p>
</div>`

</script>

<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <AuthenticationCardLogo />
                </div>

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose" v-html="Policy" />
            </div>
        </div>
    </div>
</template>
