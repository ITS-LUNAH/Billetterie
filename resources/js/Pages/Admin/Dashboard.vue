<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
  title: String,
  stats: Array,
  activities: Array
})
</script>

<template>
  <AdminLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">{{ props.title }}</h1>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div v-for="stat in props.stats" :key="stat.title" class="bg-white p-4 rounded shadow">
          <div class="text-gray-500">{{ stat.title }}</div>
          <div class="text-2xl font-bold">{{ stat.value }}</div>
          <div :class="{
            'text-green-600': stat.trendDirection === 'up',
            'text-red-600': stat.trendDirection === 'down'
          }">
            {{ stat.trend }} <span v-if="stat.trendDirection === 'up'">▲</span>
                          <span v-else>▼</span>
          </div>
        </div>
      </div>

      <!-- Activities -->
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Activités récentes</h2>
        <ul>
          <li v-for="activity in props.activities" :key="activity.id" class="border-b py-2">
            <strong>{{ activity.user }}</strong> {{ activity.action }} —
            <span class="text-gray-500">{{ activity.time }}</span>
          </li>
        </ul>
      </div>
    </div>
  </AdminLayout>
</template>
