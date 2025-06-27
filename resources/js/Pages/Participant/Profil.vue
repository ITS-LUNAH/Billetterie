<script setup>
import ParticipantLayout from '@/Layouts/ParticipantLayout.vue';
import { useForm } from '@inertiajs/vue3';
// import { route } from 'vue-router';

const props = defineProps({
    user: Object,
    participant: Object,
    message: String,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    compte_bancaire: props.participant?.compte_bancaire || '',
    iban: props.participant?.iban || '',
    rib: props.participant?.rib || '',
});

const submit = () => {
    form.put('/participant/profil');
};
</script>

<template>
    <ParticipantLayout>
        <div class="py-6">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- En-tête -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ message }}</h1>
                    <p class="mt-2 text-gray-600">
                        Gérez vos informations personnelles et bancaires
                    </p>
                </div>

                <!-- Formulaire de profil -->
                <div class="bg-white shadow-sm rounded-lg border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Informations Personnelles
                        </h3>
                    </div>
                    
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Informations de base -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nom complet
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Informations bancaires -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">
                                Informations Bancaires (Optionnel)
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="compte_bancaire" class="block text-sm font-medium text-gray-700">
                                        Compte Bancaire
                                    </label>
                                    <input
                                        id="compte_bancaire"
                                        v-model="form.compte_bancaire"
                                        type="text"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        :class="{ 'border-red-500': form.errors.compte_bancaire }"
                                    />
                                    <p v-if="form.errors.compte_bancaire" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.compte_bancaire }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="iban" class="block text-sm font-medium text-gray-700">
                                            IBAN
                                        </label>
                                        <input
                                            id="iban"
                                            v-model="form.iban"
                                            type="text"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            :class="{ 'border-red-500': form.errors.iban }"
                                        />
                                        <p v-if="form.errors.iban" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.iban }}
                                        </p>
                                    </div>

                                    <div>
                                        <label for="rib" class="block text-sm font-medium text-gray-700">
                                            RIB
                                        </label>
                                        <input
                                            id="rib"
                                            v-model="form.rib"
                                            type="text"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                            :class="{ 'border-red-500': form.errors.rib }"
                                        />
                                        <p v-if="form.errors.rib" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.rib }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Annuler
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Informations du compte -->
                <div class="mt-8 bg-white shadow-sm rounded-lg border">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Informations du Compte
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Membre depuis</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.created_at }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span v-if="user.email_verified_at" class="text-green-600">✓ Vérifié</span>
                                    <span v-else class="text-red-600">✗ Non vérifié</span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </ParticipantLayout>
</template>
