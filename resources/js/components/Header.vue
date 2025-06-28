<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const canLogin = page.props.canLogin
const auth = page.props.auth || {}

const smoothScroll = (e) => {
  e.preventDefault()
  const target = document.getElementById(e.currentTarget.getAttribute('href').substring(1))
  if (target) {
    target.scrollIntoView({
      behavior: 'smooth'
    })
    window.history.pushState(null, null, e.currentTarget.getAttribute('href'))
  }
}
</script>

<template>
<header class="relative z-10 grid grid-cols-2 items-center gap-2 py-2 lg:grid-cols-3 bg-white/20 backdrop-blur-sm px-4 md:px-8">
<div class="flex lg:justify-left lg:col-start-1">
      <Link :href="'/'">
        <img
          src="/images/EVUP.gif"
          width="150"
          height="150"
          class="rounded-circle img-fluid"
          alt="EVUP-LOGO"
        />
      </Link>
    </div>

     <nav class="hidden lg:flex justify-center items-center space-x-6">
      <Link 
        href="#about" 
        class="text-white hover:text-white/70 transition"
        @click="smoothScroll"
      >
        À propos
      </Link>
      <Link 
        href="#categories" 
        class="text-white hover:text-white/70 transition"
      >
        Catégories
      </Link>
      <Link 
        href="#contact" 
        class="text-white hover:text-white/70 transition"
        @click="smoothScroll"

      >
        Contact
      </Link>
    </nav>
    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end lg:col-start-3">
      <Link
        v-if="auth.user"
        :href="route('dashboard')"
        class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
      >
        Dashboard
      </Link>

      <template v-else>
        <Link
          :href="route('login')"
          class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          Se connecter
        </Link>

        <Link
          :href="route('register.participant')"
          class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          S'inscrire
        </Link>

        <Link
          :href="route('register.organisateur')"
          class="rounded-md px-4 py-2 text-white ring-1 ring-transparent transition hover:text-white/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
          Devenir Organisateur
        </Link>
      </template>
    </nav>
  </header>
</template>
