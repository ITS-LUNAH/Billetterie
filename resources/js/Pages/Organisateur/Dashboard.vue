<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
</style>