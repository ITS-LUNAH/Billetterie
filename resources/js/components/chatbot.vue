<script setup>
import { ref, nextTick, onMounted } from 'vue'

// État du chatbot
const isOpen = ref(false)
const messages = ref([])
const isTyping = ref(false)
const messagesContainer = ref(null)

// Questions prédéfinies et réponses
const predefinedResponses = {
  'Comment réserver un événement ?': 'Pour réserver un événement cliquez sur l\'événement qui vous intéresse, puis suivez les étapes..',
  'Quels sont les prix ?': 'Les prix varient selon l\'événement. Vous pouvez voir le prix de chaque événement sur sa page de détails.',
  'Voir les événements ': 'vous pouvez voir les événements à venir dans la page d\'accueil.',
  'Comment vous contacter ?': 'Vous pouvez nous contacter à contact@evup.com ou par téléphone au +212 1 23 45 67 89.',
}

// Questions suggérées (toujours disponibles)
const suggestedQuestions = [
  'Comment réserver un événement ?',
  'Quels sont les prix ?',
  'Comment vous contacter ?',
  'Voir les événements disponibles'
]

// Ouvrir/fermer le chatbot
const toggleChat = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value && messages.value.length === 0) {
    // Message de bienvenue plus court
    addBotMessage('Bonjour ! Je suis votre assistant EVUP.')
  }
}

// Ajouter un message du bot
const addBotMessage = (text) => {
  messages.value.push({
    id: Date.now(),
    text,
    isBot: true,
    timestamp: new Date()
  })
  scrollToBottom()
}

// Ajouter un message de l'utilisateur
const addUserMessage = (text) => {
  messages.value.push({
    id: Date.now(),
    text,
    isBot: false,
    timestamp: new Date()
  })
  scrollToBottom()
}

// Faire défiler vers le bas
const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

// Simuler la frappe du bot
const simulateTyping = () => {
  isTyping.value = true
  return new Promise(resolve => {
    setTimeout(() => {
      isTyping.value = false
      resolve()
    }, 1000 + Math.random() * 1000) // 1-2 secondes
  })
}

// Envoyer une question suggérée
const sendSuggestedQuestion = async (question) => {
  addUserMessage(question)
  await simulateTyping()
  
  const response = predefinedResponses[question]
  addBotMessage(response)
}

// Formater l'heure
const formatTime = (date) => {
  return date.toLocaleTimeString('fr-FR', { 
    hour: '2-digit', 
    minute: '2-digit' 
  })
}
</script>

<template>
  <!-- Bouton flottant du chatbot -->
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Fenêtre du chat -->
    <div 
      v-if="isOpen"
      class="mb-4 w-80 h-96 bg-white rounded-lg shadow-2xl border border-gray-200 flex flex-col"
    >
      <!-- En-tête -->
      <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
        <div class="flex items-center">
          <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center mr-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div>
            <h3 class="font-semibold">Assistant EVUP</h3>
            <p class="text-xs opacity-90">En ligne</p>
          </div>
        </div>
        <button 
          @click="toggleChat"
          class="text-white hover:text-gray-200 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Messages -->
      <div 
        ref="messagesContainer"
        class="flex-1 p-4 overflow-y-auto space-y-3"
      >
        <div
          v-for="message in messages"
          :key="message.id"
          :class="[
            'flex',
            message.isBot ? 'justify-start' : 'justify-end'
          ]"
        >
          <div
            :class="[
              'max-w-xs px-3 py-2 rounded-lg text-sm',
              message.isBot 
                ? 'bg-gray-100 text-gray-800' 
                : 'bg-indigo-600 text-white'
            ]"
          >
            <p>{{ message.text }}</p>
            <p 
              :class="[
                'text-xs mt-1',
                message.isBot ? 'text-gray-500' : 'text-indigo-100'
              ]"
            >
              {{ formatTime(message.timestamp) }}
            </p>
          </div>
        </div>

        <!-- Indicateur de frappe -->
        <div v-if="isTyping" class="flex justify-start">
          <div class="bg-gray-100 px-3 py-2 rounded-lg">
            <div class="flex space-x-1">
              <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
              <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
              <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
          </div>
        </div>

        <!-- Questions suggérées (toujours visibles après chaque échange) -->
        <div v-if="!isTyping" class="space-y-2 mt-4">
          <p class="text-xs text-gray-500 text-center">Choisissez une question :</p>
          <div class="space-y-1">
            <button
              v-for="question in suggestedQuestions"
              :key="question"
              @click="sendSuggestedQuestion(question)"
              class="w-full text-left text-xs bg-gray-50 hover:bg-indigo-50 hover:border-indigo-300 p-2 rounded border border-gray-200 transition-all duration-200"
            >
              <span class="text-gray-800">{{ question }}</span>
              <svg class="w-3 h-3 float-right mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div> 

    <!-- Bouton d'ouverture -->
    <button
      @click="toggleChat"
      class="bg-indigo-600 hover:bg-indigo-700 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110"
    >
      <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>
  <!-- Balise de fermeture ajoutée -->
</template>
