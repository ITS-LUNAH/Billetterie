<!-- resources/js/Components/Header.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const canLogin = page.props.canLogin
const auth = page.props.auth || {}
</script>

<template>
  <header class="relative z-10 grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3 bg-gray/90 backdrop-blur-sm">
    <div class="flex lg:justify-left lg:col-start-1">
      <Link :href="'/'">
        <img
          src="/images/EVUP.gif"
          width="80"
          height="80"
          class="rounded-circle img-fluid"
          alt="EVUP-LOGO"
        />
      </Link>
    </div>

    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end lg:col-start-3">
      <Link
        v-if="auth.user"
        :href="route('dashboard')"
        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
      >
        Dashboard
      </Link>

      <template v-else>
        <Link
          :href="route('login')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          Log in
        </Link>

        <Link
          :href="route('register.participant')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          S'inscrire
        </Link>

        <Link
          :href="route('register.organisateur')"
          class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          Devenir Organisateur
        </Link>
      </template>
    </nav>
  </header>
</template>
