<script setup>
import { ref, computed, onMounted, watch } from 'vue';

// Définir les props que ce composant recevra
const props = defineProps({
  totalSeats: { // Nombre total de sièges à afficher
    type: Number,
    default: 100
  },
  bookedSeats: { // Tableau des indices des sièges déjà réservés (ex: [5, 12, 20])
    type: Array,
    default: () => []
  },
  sections: { // Tableau des sections avec leur nom, couleur, prix, et range
    type: Array,
    default: () => []
  },
  initialPromotions: { // Tableau des promotions initiales
    type: Array,
    default: () => []
  },
  selectedEvent: { // L'objet événement passé depuis Show.vue (pour des infos additionnelles si besoin)
    type: Object,
    default: null
  }
});

// État réactif pour le plan de salle
const seats = ref([]); // Tableau pour stocker l'état de chaque siège { id, number, isBooked, section, price, isSelected }
const selectedTickets = ref([]); // Indices des sièges sélectionnés
const currentPromotion = ref(null); // Promotion actuellement appliquée

// --- Fonctions utilitaires pour le plan (adaptées de votre JS) ---

const generateSeats = () => {
  const newSeats = [];
  for (let i = 0; i < props.totalSeats; i++) {
    let section = null;
    let seatPrice = 0;

    // Trouver la section et le prix du siège
    const sectionIndex = props.sections.findIndex(s => i >= s.range[0] && i <= s.range[1]);
    if (sectionIndex !== -1) {
      section = props.sections[sectionIndex];
      seatPrice = section.price;
    }

    newSeats.push({
      id: `s${i + 1}`,
      number: i + 1,
      isBooked: props.bookedSeats.includes(i + 1), // Vérifier si le siège est réservé
      section: section,
      price: seatPrice,
      isSelected: false // Gérer l'état de sélection via Vue
    });
  }
  seats.value = newSeats;
};

// Mettre à jour l'affichage du prix en fonction de la promotion
const updatePriceDisplay = () => {
  let baseTotalPrice = selectedTickets.value.reduce((sum, index) => sum + seats.value[index - 1].price, 0);
  let finalTotalPrice = baseTotalPrice;

  if (currentPromotion.value && currentPromotion.value.discount_type === 'percentage') {
    finalTotalPrice = baseTotalPrice * (1 - currentPromotion.value.discount_value / 100);
  } else if (currentPromotion.value && currentPromotion.value.discount_type === 'fixed') {
    finalTotalPrice = Math.max(0, baseTotalPrice - currentPromotion.value.discount_value);
  }
  return finalTotalPrice.toFixed(2); // Retourne le prix formaté
};

// Gérer la sélection/désélection d'un siège
const toggleSeatSelection = (seat) => {
  if (seat.isBooked) return; // Ne rien faire si le siège est réservé

  seat.isSelected = !seat.isSelected; // Inverse l'état de sélection

  if (seat.isSelected) {
    selectedTickets.value.push(seat.number);
  } else {
    selectedTickets.value = selectedTickets.value.filter(n => n !== seat.number);
  }
};

// Calcul du prix total réactif
const totalPrice = computed(() => {
  return updatePriceDisplay();
});

// Nombre de tickets sélectionnés réactif
const selectedCount = computed(() => {
  return selectedTickets.value.length;
});

// Appliquer une promotion (à déclencher si l'utilisateur entre un code, par exemple)
const applyPromotion = (promoCode) => {
  const promo = props.initialPromotions.find(p => p.code === promoCode);
  if (promo) {
    currentPromotion.value = promo;
    console.log('Promotion appliquée:', promo);
  } else {
    currentPromotion.value = null;
    console.log('Code promo invalide.');
  }
};

// Mise à jour de l'affichage de la promotion actuelle (si une promo est déjà appliquée par défaut)
const updateCurrentPromotionDisplay = () => {
  // Par exemple, si une promotion est passée dans les props ou est la première par défaut
  if (props.initialPromotions.length > 0) {
    currentPromotion.value = props.initialPromotions[0]; // Applique la première promotion par défaut
  }
};

// Émettre les sièges sélectionnés vers le parent (Show.vue)
watch(selectedTickets, (newVal) => {
  emit('update:selectedSeats', newVal);
}, { deep: true });

// Émettre un événement pour la soumission finale des sièges (vous devrez créer une route pour cela)
const emit = defineEmits(['update:selectedSeats', 'submitReservation']);

const submitReservation = () => {
  if (selectedTickets.value.length > 0) {
    emit('submitReservation', selectedTickets.value, totalPrice.value);
  } else {
    alert('Veuillez sélectionner au moins un siège.');
  }
};


// Initialisation du plan au montage du composant
onMounted(() => {
  generateSeats();
  updateCurrentPromotionDisplay();
});

</script>

<template>
  <div class="center">
    <div class="ticket-wrapper">
      <div class="screen-area">
        <h3 class="screen">Écran / Scène</h3>
      </div>
      <div class="seats-area">
        <div class="seats-row">
          <div v-for="seat in seats" :key="seat.id" :class="{
            'seat-wrapper': true,
            'is-booked': seat.isBooked,
            'is-selected': seat.isSelected,
            [`section-${seat.section?.name}`]: seat.section,
          }" :style="seat.section ? `background-color: ${seat.section.color}` : ''" @click="toggleSeatSelection(seat)">
            <input type="checkbox" :id="seat.id" :data-price="seat.price" :disabled="seat.isBooked" :checked="seat.isSelected" />
            <label :for="seat.id" class="seat">
              <div class="seat-number">{{ seat.number }}</div>
            </label>
          </div>
        </div>
      </div>
      <div class="ticket-info">
        <div class="info-item">
          <span class="label">Sièges sélectionnés:</span>
          <span class="value count">{{ selectedCount }}</span>
        </div>
        <div class="info-item">
          <span class="label">Prix total:</span>
          <span class="value price">{{ totalPrice }} MAD</span>
        </div>
        <button @click="submitReservation" class="reserve-button">
          Réserver les sièges sélectionnés
        </button>
      </div>
      <div class="sections-legend">
        <h4>Légende des sections :</h4>
        <div v-for="section in sections" :key="section.name" class="legend-item">
          <span :style="`background-color: ${section.color}; width: 20px; height: 20px; display: inline-block; border-radius: 5px; margin-right: 5px;`"></span>
          {{ section.name }} ({{ section.price }} MAD)
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Copiez ici le CSS pertinent de votre fichier test de plan.html */
/* Assurez-vous d'adapter les chemins des polices si nécessaire */
/* Par exemple, si vous avez @import url(...) pour les polices, elles peuvent rester. */
/* Les styles pour .center, .ticket-wrapper, .screen-area, .seats-area, .seats-row,
   .seat-wrapper, .seat, .seat-number, .ticket-info, .info-item, .reserve-button,
   .sections-legend, .legend-item, .seat-wrapper.is-booked, .seat-wrapper.is-selected,
   .section-X, etc., doivent être ici. */

/* Styles basiques de l'exemple original */
body {
  width: 100%;
  height: 100vh;
  margin: 0;
  padding: 0;
}
.center {
  width: 100%;
  min-height: 100%; /* Utilisez min-height si le contenu peut dépasser */
  display: flex;
  align-items: center;
  justify-content: center;
  /* background: linear-gradient(...);  Vous pouvez adapter ce background si vous le souhaitez */
}
.ticket-wrapper {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 90%; /* Ajustez la largeur */
  max-width: 800px; /* Max width pour les grands écrans */
}
.screen-area {
  margin-bottom: 20px;
}
.screen {
  background-color: #f0f0f0;
  padding: 10px;
  border-radius: 5px;
  font-weight: bold;
}
.seats-area {
  margin-bottom: 20px;
  overflow-x: auto; /* Permet le défilement horizontal si beaucoup de sièges */
}
.seats-row {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(40px, 1fr)); /* 40px par siège */
  gap: 10px; /* Espace entre les sièges */
  justify-content: center;
}
.seat-wrapper {
  position: relative;
  width: 40px; /* Taille fixe du siège */
  height: 40px; /* Taille fixe du siège */
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 5px;
  cursor: pointer;
  border: 1px solid #ccc;
  background-color: #e0e0e0;
  transition: all 0.2s ease-in-out;
}
.seat-wrapper input[type="checkbox"] {
  display: none; /* Cache la checkbox native */
}
.seat {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 500;
  color: #333;
}
.seat-wrapper.is-booked {
  background-color: #ffcccc; /* Couleur pour les sièges réservés */
  cursor: not-allowed;
  border-color: #ff9999;
}
.seat-wrapper.is-selected {
  background-color: #6200ea; /* Couleur pour les sièges sélectionnés */
  color: white;
  border-color: #6200ea;
  transform: scale(1.1); /* Petit zoom pour la sélection */
  box-shadow: 0 0 8px rgba(98, 0, 234, 0.5);
}
.seat-wrapper.is-selected .seat-number {
  color: white;
}
/* Styles pour les sections */
.section-premium { background-color: #FFD700 !important; } /* Or */
.section-vip { background-color: #ADD8E6 !important; } /* Light Blue */
.section-standard { background-color: #90EE90 !important; } /* Light Green */
/* Ajoutez plus de classes pour vos sections */

.ticket-info {
  margin-top: 20px;
  padding: 15px;
  background-color: #f9f9f9;
  border-radius: 8px;
  border: 1px solid #eee;
}
.info-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 16px;
}
.info-item .label {
  font-weight: 500;
  color: #555;
}
.info-item .value {
  font-weight: 600;
  color: #333;
}
.reserve-button {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 18px;
  font-weight: bold;
  margin-top: 15px;
  width: 100%;
  transition: background-color 0.3s ease;
}
.reserve-button:hover {
  background-color: #45a049;
}
.sections-legend {
  margin-top: 30px;
  text-align: left;
  border-top: 1px solid #eee;
  padding-top: 20px;
}
.sections-legend h4 {
  margin-bottom: 10px;
  font-size: 1.2em;
  color: #333;
}
.legend-item {
  display: flex;
  align-items: center;
  margin-bottom: 5px;
  font-size: 0.9em;
  color: #666;
}
/* Styles pour les promotions (si vous les activez) */
/* .promo-display { ... } */
</style>