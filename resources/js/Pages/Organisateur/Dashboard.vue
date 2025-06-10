<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'; // Ou AppLayout si vous n'avez pas de layout Admin spécifique
import Welcome from '@/Components/Welcome.vue'; // Un composant d'exemple de Jetstream

// Définissez les props que ce composant peut recevoir du contrôleur Laravel
defineProps({
    message: String,
    // Ajoutez d'autres props si vous les passez depuis le contrôleur
});
</script>

<template>
    <AppLayout> <!-- Utilise un layout spécifique pour l'admin, ou AppLayout -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tableau de Bord Organisateur
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-lg font-medium text-gray-900">{{ message }}</p>
                        <p class="mt-4 text-gray-700">C'est votre espace pour créer et gérer des événements.</p>
                        <Welcome class="mt-8" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>