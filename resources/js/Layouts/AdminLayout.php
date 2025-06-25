<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-4 text-lg font-bold border-b">Admin Panel</div>
      <nav class="p-4">
        <ul class="space-y-2">
          <li><Link href="/admin/dashboard" class="text-blue-600">Dashboard</Link></li>
          <li><Link href="/admin/organisateurs">Organisateurs</Link></li>
          <li><Link href="/admin/evenements">Événements</Link></li>
          <li><Link href="/admin/participants">Participants</Link></li>
          <li><Link href="/admin/commissions">Commissions</Link></li>
        </ul>
      </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 p-6">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
