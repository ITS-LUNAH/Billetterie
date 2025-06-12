<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
})
</script>

<template>
    <GuestLayout :canLogin="canLogin" :canRegister="canRegister">
        <div class="text-center text-xl text-black/50 bg-white/80 p-8 rounded-lg backdrop-blur-sm">
            Bienvenue sur la page d'accueil
        </div>
    </GuestLayout>
</template>
