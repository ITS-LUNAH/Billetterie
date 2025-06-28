<script setup>
// import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, onUnmounted } from 'vue';
import { Html5QrcodeScanner, Html5QrcodeSupportedFormats } from 'html5-qrcode';
import { router } from '@inertiajs/vue3'

const logout = () => {
    const confirmLogout = confirm('Êtes-vous sûr de vouloir vous déconnecter ?');
    if (confirmLogout) {
        router.post('/logout');
    }
};
// Définissez les props que ce composant peut recevoir du contrôleur Laravel
defineProps({
    message: String,
    user: Object,
    statsData: {
        type: Object,
        default: () => ({
            active_events: 0,
            total_participants: 0,
            monthly_revenue: 0,
            average_rating: 0
        })
    },
    recentActivity: {
        type: Array,
        default: () => []
    }
});

// État pour le scanner QR
const showQrScanner = ref(false);
const qrScanResult = ref('');
const isScanning = ref(false);
const scanError = ref('');
const qrScanner = ref(null);

// Variables pour html5-qrcode
let html5QrcodeScanner = null;

// Fonction pour obtenir la classe CSS selon la couleur
const getActivityColor = (color) => {
    const colors = {
        indigo: 'border-indigo-500 bg-indigo-50 hover:bg-indigo-100',
        purple: 'border-purple-500 bg-purple-50 hover:bg-purple-100',
        pink: 'border-pink-500 bg-pink-50 hover:bg-pink-100',
        red: 'border-red-500 bg-red-50 hover:bg-red-100',
        green: 'border-green-500 bg-green-50 hover:bg-green-100',
        blue: 'border-blue-500 bg-blue-50 hover:bg-blue-100'
    };
    return colors[color] || colors.indigo;
};

// Fonction pour démarrer le scanner QR
const startQrScanner = async () => {
    try {
        showQrScanner.value = true;
        isScanning.value = true;
        scanError.value = '';
        qrScanResult.value = '';

        // Attendre que l'élément DOM soit disponible
        await new Promise(resolve => setTimeout(resolve, 100));

        // Configuration du scanner
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
            disableFlip: false,
            formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
        };

        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            config,
            /* verbose= */ false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    } catch (error) {
        console.error('Erreur lors du démarrage du scanner:', error);
        scanError.value = 'Impossible de démarrer le scanner QR';
        isScanning.value = false;
    }
};

// Fonction appelée lors d'un scan réussi
const onScanSuccess = (decodedText, decodedResult) => {
    console.log(`QR Code scanné: ${decodedText}`, decodedResult);
    qrScanResult.value = decodedText;
    
    // Arrêter le scanner après un scan réussi
    stopQrScanner();
    
    // Traiter le résultat du scan
    processQrResult(decodedText);
};

// Fonction appelée lors d'une erreur de scan
const onScanFailure = (error) => {
    // Ne pas afficher les erreurs de scan normales (pas de QR code détecté)
    // console.warn(`Erreur de scan QR: ${error}`);
};

// Fonction pour arrêter le scanner QR
const stopQrScanner = () => {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().then(() => {
            console.log('Scanner QR arrêté');
        }).catch(error => {
            console.error('Erreur lors de l\'arrêt du scanner:', error);
        });
        html5QrcodeScanner = null;
    }
    isScanning.value = false;
    showQrScanner.value = false;
};

// Fonction pour traiter le résultat du scan QR
const processQrResult = (qrText) => {
    try {
        // Essayer de parser le QR code comme JSON (pour les billets d'événement)
        const qrData = JSON.parse(qrText);
        
        if (qrData.type === 'event_ticket') {
            // Traitement pour un billet d'événement
            handleEventTicket(qrData);
        } else if (qrData.type === 'participant_checkin') {
            // Traitement pour un check-in de participant
            handleParticipantCheckin(qrData);
        } else {
            // QR code générique
            handleGenericQr(qrText);
        }
    } catch (error) {
        // Si ce n'est pas du JSON, traiter comme un QR code simple
        handleGenericQr(qrText);
    }
};

// Gérer un billet d'événement
const handleEventTicket = (ticketData) => {
    console.log('Billet d\'événement scanné:', ticketData);
    // Ici vous pouvez ajouter la logique pour valider le billet
    // Par exemple, faire un appel API pour vérifier la validité
    alert(`Billet scanné pour l'événement: ${ticketData.eventName}\nParticipant: ${ticketData.participantName}`);
};

// Gérer un check-in de participant
const handleParticipantCheckin = (checkinData) => {
    console.log('Check-in participant:', checkinData);
    // Logique pour enregistrer la présence du participant
    alert(`Check-in enregistré pour: ${checkinData.participantName}`);
};

// Gérer un QR code générique
const handleGenericQr = (qrText) => {
    console.log('QR code générique scanné:', qrText);
    alert(`QR Code scanné: ${qrText}`);
};

// Nettoyer le scanner lors de la destruction du composant
onUnmounted(() => {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
});
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Organisateur
            </h2>
        </template>

        <div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
            <!-- Header personnalisé -->
            <header class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-white/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                <i class="fas fa-calendar-alt mr-2"></i>EVUP
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Bouton Scanner QR -->
                            <button 
                                @click="startQrScanner"
                                :disabled="isScanning"
                                class="bg-gradient-to-r from-green-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-qrcode"></i>
                                <span>{{ isScanning ? 'Scan en cours...' : 'Scanner QR' }}</span>
                            </button>
                            
                            <!-- Bouton de déconnexion  -->
                            <button 
                                @click="logout"
                                class="group bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 text-white px-4 py-2 rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2 relative overflow-hidden"
                                title="Se déconnecter"
                            >
                                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                                <i class="fas fa-sign-out-alt text-sm transform group-hover:rotate-12 transition-transform duration-300"></i>
                                <span class="text-sm font-medium hidden sm:inline">Déconnexion</span>
                                <div class="absolute -top-1 -right-1 w-2 h-2 bg-yellow-300 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-pulse"></div>
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <div class="relative">
                                <img 
                                    :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user?.firstName || 'Utilisateur')}&color=7F9CF5&background=EBF4FF`" 
                                    :alt="user?.firstName || 'Profile'" 
                                    class="w-10 h-10 rounded-full border-3 border-indigo-500"
                                >
                            </div>
                            <div class="text-right">
                                <h3 class="font-semibold text-gray-800">{{ user?.firstName || 'Utilisateur' }}</h3>
                                <p class="text-sm text-gray-600">Organisateur</p>
                            </div>
                            <div class="relative">
                                <i class="fas fa-bell text-indigo-600 text-xl"></i>
                                <span v-if="recentActivity.length > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    {{ Math.min(recentActivity.length, 9) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Modal Scanner QR -->
            <div v-if="showQrScanner" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Scanner QR Code</h3>
                        <button 
                            @click="stopQrScanner"
                            class="text-gray-500 hover:text-gray-700 text-2xl"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Zone de scan -->
                    <div class="mb-4">
                        <div id="qr-reader" class="w-full"></div>
                    </div>
                    
                    <!-- Messages d'erreur -->
                    <div v-if="scanError" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ scanError }}
                    </div>
                    
                    <!-- Résultat du scan -->
                    <div v-if="qrScanResult" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        <strong>QR Code scanné:</strong> {{ qrScanResult }}
                    </div>
                    
                    <!-- Instructions -->
                    <div class="text-sm text-gray-600 text-center">
                        <p class="mb-2">
                            <i class="fas fa-camera mr-2"></i>
                            Pointez votre caméra vers le QR code
                        </p>
                        <p>
                            <i class="fas fa-info-circle mr-2"></i>
                            Le scan se fera automatiquement
                        </p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                <!-- Section de bienvenue -->
                <section class="bg-white/95 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-white/20 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 animate-pulse"></div>
                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-4">
                            <i class="fas fa-sparkles mr-3"></i>Bienvenue, {{ user?.firstName || 'Utilisateur' }} !
                        </h1>
                        <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                            Vous êtes connecté en tant qu'organisateur. Gérez vos événements, suivez vos statistiques et créez des expériences mémorables.
                        </p>
                        <!-- Affichage du dernier QR scanné -->
                        <div v-if="qrScanResult" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">
                                <i class="fas fa-qrcode mr-2"></i>Dernier QR Code scanné
                            </h3>
                            <p class="text-green-700 text-sm break-all">{{ qrScanResult }}</p>
                        </div>
                    </div>
                </section>

                <!-- Grille des statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                        <div class="text-center">
                            <div class="text-4xl text-indigo-600 mb-4">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">{{ statsData.active_events }}</div>
                            <div class="text-sm text-gray-600 uppercase tracking-wide">Événements Actifs</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                        <div class="text-center">
                            <div class="text-4xl text-purple-600 mb-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">{{ statsData.total_participants.toLocaleString() }}</div>
                            <div class="text-sm text-gray-600 uppercase tracking-wide">Participants Totaux</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-pink-500 to-red-500"></div>
                        <div class="text-center">
                            <div class="text-4xl text-pink-600 mb-4">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">€{{ statsData.monthly_revenue.toLocaleString() }}</div>
                            <div class="text-sm text-gray-600 uppercase tracking-wide">Revenus ce Mois</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-6 shadow-lg border border-white/20 hover:transform hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-500 to-orange-500"></div>
                        <div class="text-center">
                            <div class="text-4xl text-yellow-600 mb-4">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">{{ statsData.average_rating || 'N/A' }}</div>
                            <div class="text-sm text-gray-600 uppercase tracking-wide">Note Moyenne</div>
                        </div>
                    </div>
                </div>

                <!-- Section des actions rapides -->
                <section class="space-y-6">
                    <h2 class="text-2xl font-bold text-white text-center relative">
                        Actions Rapides
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-white rounded-full"></div>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <a href="/organisateur/evenements/create" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 text-center">
                            <i class="fas fa-plus-circle text-2xl"></i>
                            <span class="font-semibold">Créer un Événement</span>
                        </a>
                        <a href="/organisateur/evenements" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 text-center">
                            <i class="fas fa-list text-2xl"></i>
                            <span class="font-semibold">Gérer mes Événements</span>
                        </a>
                        <a href="/admin/statistiques" class="bg-gradient-to-r from-pink-600 to-red-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 text-center">
                            <i class="fas fa-chart-bar text-2xl"></i>
                            <span class="font-semibold">Voir Statistiques</span>
                        </a>
                        <a href="/user/profile" class="bg-gradient-to-r from-red-600 to-orange-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 text-center">
                            <i class="fas fa-user-cog text-2xl"></i>
                            <span class="font-semibold">Paramètres Profil</span>
                        </a>
                        <!-- Nouvelle action pour scanner QR -->
                        <button 
                            @click="startQrScanner"
                            :disabled="isScanning"
                            class="bg-gradient-to-r from-green-600 to-teal-600 text-white p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 text-center disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <i class="fas fa-qrcode text-2xl"></i>
                            <span class="font-semibold">{{ isScanning ? 'Scan en cours...' : 'Scanner QR Code' }}</span>
                        </button>
                    </div>
                </section>

                <!-- Activité récente -->
                <section class="bg-white/90 backdrop-blur-md rounded-3xl p-8 shadow-xl border border-white/20">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center relative">
                        Activité Récente
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                    </h2>
                    
                    <!-- Affichage dynamique de l'activité récente -->
                    <div v-if="recentActivity.length > 0" class="space-y-4">
                        <div 
                            v-for="(activity, index) in recentActivity" 
                            :key="index"
                            :class="`border-l-4 p-4 rounded-r-lg transition-all duration-300 transform hover:translate-x-2 ${getActivityColor(activity.color)}`"
                        >
                            <div class="text-sm text-gray-600 mb-2">{{ activity.date }}</div>
                            <div class="font-medium text-gray-800">{{ activity.message }}</div>
                        </div>
                    </div>
                    
                    <!-- Message si aucune activité -->
                    <div v-else class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Aucune activité récente</h3>
                        <p class="text-gray-500">Commencez par créer votre premier événement pour voir l'activité ici.</p>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Animations personnalisées */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.animate-pulse {
    animation: pulse 3s ease-in-out infinite;
}

/* Scrollbar personnalisée */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, #764ba2, #667eea);
}

/* Styles pour le scanner QR */
#qr-reader {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
}

#qr-reader > div:first-child {
    padding: 0 !important;
}

#qr-reader video {
    border-radius: 8px;
}

/* Personnalisation des boutons du scanner */
#qr-reader button {
    background: linear-gradient(45deg, #667eea, #764ba2) !important;
    border: none !important;
    border-radius: 6px !important;
    color: white !important;
    font-weight: 500 !important;
    padding: 8px 16px !important;
    margin: 4px !important;
}

#qr-reader button:hover {
    background: linear-gradient(45deg, #764ba2, #667eea) !important;
    transform: translateY(-1px);
}
</style>