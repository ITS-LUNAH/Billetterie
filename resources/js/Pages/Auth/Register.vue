<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed } from 'vue'; // Importe 'computed' pour la réactivité

// Définit les props que ce composant peut recevoir
const props = defineProps({
    userTypeProp: { // C'est la prop qui contiendra 'Participant' ou 'Organisateur'
        type: String,
        default: null, // Si aucune prop n'est passée, la valeur par défaut est null
    },
});

const form = useForm({
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,

 // Initialise 'typeUser' avec la valeur de la prop, sinon 'Participant' par défaut
    typeUser: props.userTypeProp || 'Participant',

    // Champs spécifiques pour l'Organisateur
    nomGerant: '',
    prenomGerant: '',
    societe: '',
    secteurActivite: '',
    ICE: '',

    // Champs spécifiques pour le Participant
    compteBancaire: '',
    IBAN: '',
    RIB: '',
});

// Propriété calculée pour décider si le sélecteur de type d'utilisateur doit être affiché
/* const showTypeUserSelect = computed(() => {
    return !props.userTypeProp; // Il est affiché si 'userTypeProp' est null (pas de type pré-défini)
}); */

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="firstName" value="Prénom" />
                <TextInput
                    id="firtName"
                    v-model="form.firstName"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="given-name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <br>
            <div>
                <InputLabel for="lastName" value="nom" />
                <TextInput
                    id="lastName"
                    v-model="form.lastName"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="family-name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Mot de passe" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirmer mot de Passe" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

             <input type="hidden" name="typeUser" :value="form.typeUser">
            <div v-if="form.typeUser === 'Organisateur'">
                <div class="mt-4">
                    <InputLabel for="nomGerant" value="Nom du gérant" />
                    <TextInput id="nomGerant" v-model="form.nomGerant" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.nomGerant" />
                </div>
                <div class="mt-4">
                    <InputLabel for="prenomGerant" value="Prénom du gérant" />
                    <TextInput id="prenomGerant" v-model="form.prenomGerant" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.prenomGerant" />
                </div>
                <div class="mt-4">
                    <InputLabel for="societe" value="Nom de la Société" />
                    <TextInput id="societe" v-model="form.societe" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.societe" />
                </div>
                <div class="mt-4">
                    <InputLabel for="secteurActivite" value="Secteur d'activité" />
                    <TextInput id="secteurActivite" v-model="form.secteurActivite" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.secteurActivite" />
                </div>
                <div class="mt-4">
                    <InputLabel for="ICE" value="ICE" />
                    <TextInput id="ICE" v-model="form.ICE" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.ICE" />
                </div>
            </div>

            
            <div v-else-if="form.typeUser === 'Participant'">
                <small class="text-gray-500">
                    Fournir vos informations bancaires (compte bancaire, IBAN, RIB) permet un remboursement rapide en cas d’annulation ou de report de l’événement.
                    Ces données sont sécurisées et ne seront utilisées qu’à cette fin.
                </small>
                <div class="mt-4">
                    <InputLabel for="compteBancaire" value="Nom du titulaire du compte bancaire" />
                    <TextInput id="compteBancaire" v-model="form.compteBancaire" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.compteBancaire" />
                </div>
                <div class="mt-4">
                    <InputLabel for="IBAN" value="IBAN" />
                    <TextInput id="IBAN" v-model="form.IBAN" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.IBAN" />
                </div>
                <div class="mt-4">
                    <InputLabel for="RIB" value="RIB" />
                    <TextInput id="RIB" v-model="form.RIB" type="text" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="form.errors.RIB" />
                </div>
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
