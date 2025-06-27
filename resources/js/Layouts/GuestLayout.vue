<script setup>
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
import { onMounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

// Fonction pour créer les éléments dynamiques (tickets et bulles)
onMounted(() => {
    createElements();
    
    // Parallax effect on scroll
    document.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        const tickets = document.querySelectorAll('.ticket');
        const bubbles = document.querySelectorAll('.bubble');
        
        tickets.forEach(ticket => {
            const speed = 0.05;
            ticket.style.transform = `translateY(${scrollY * speed}px)`;
        });
        
        bubbles.forEach(bubble => {
            const speed = 0.03;
            bubble.style.transform = `translateY(${scrollY * speed}px)`;
        });
    });

      // Variables pour le curseur
  let mouseX = window.innerWidth / 2;
  let mouseY = window.innerHeight / 2;

  // Éléments du curseur
  const cursor = document.querySelector('.cursor');
  const cursorGlow = document.querySelector('.cursor-glow');

  if (cursor && cursorGlow) {
    // Initialiser le curseur au centre
    cursor.style.left = `${mouseX}px`;
    cursor.style.top = `${mouseY}px`;
    cursorGlow.style.left = `${mouseX}px`;
    cursorGlow.style.top = `${mouseY}px`;

    // Suivi du curseur
    document.addEventListener('mousemove', (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
      
      // Déplacer le curseur lumineux
      cursor.style.left = `${mouseX}px`;
      cursor.style.top = `${mouseY}px`;
      
      cursorGlow.style.left = `${mouseX}px`;
      cursorGlow.style.top = `${mouseY}px`;
    });

    // Effet de clic
    document.addEventListener('mousedown', () => {
      cursor.style.width = '15px';
      cursor.style.height = '15px';
      
      // Effet d'onde lors du clic
      const clickWave = document.createElement('div');
      clickWave.style.position = 'fixed';
      clickWave.style.left = `${mouseX}px`;
      clickWave.style.top = `${mouseY}px`;
      clickWave.style.width = '10px';
      clickWave.style.height = '10px';
      clickWave.style.borderRadius = '50%';
      clickWave.style.backgroundColor = '#F5DDDD';
      clickWave.style.transform = 'translate(-50%, -50%)';
      clickWave.style.zIndex = '9997';
      clickWave.style.pointerEvents = 'none';
      clickWave.style.transition = 'all 0.5s ease-out';
      
      document.body.appendChild(clickWave);
      
      setTimeout(() => {
        clickWave.style.width = '200px';
        clickWave.style.height = '200px';
        clickWave.style.opacity = '0';
      }, 10);
      
      setTimeout(() => {
        clickWave.remove();
      }, 500);
    });

    document.addEventListener('mouseup', () => {
      cursor.style.width = '20px';
      cursor.style.height = '20px';
    });

    // Effet sur les éléments interactifs
    const interactiveElements = document.querySelectorAll('button, a');
    interactiveElements.forEach(element => {
      element.addEventListener('mouseenter', () => {
        cursor.style.width = '30px';
        cursor.style.height = '30px';
        cursor.style.backgroundColor = '#F5DDDD';
      });
      
      element.addEventListener('mouseleave', () => {
        cursor.style.width = '20px';
        cursor.style.height = '20px';
        cursor.style.backgroundColor = '#F5DDDD';
      });
    });
  }

});

// Fonction pour créer les tickets et bulles dynamiquement
function createElements() {
    const backgroundContainer = document.querySelector('.background-container');
    if (!backgroundContainer) return;
    
    // Create more tickets
    for (let i = 0; i < 10; i++) {
        const ticket = document.createElement('svg');
        ticket.classList.add('ticket');
        ticket.setAttribute('viewBox', '0 0 40 20');
        
        const offset = Math.random() * 100;
        const drift = (Math.random() - 0.5) * 30;
        const rotation = (Math.random() - 0.5) * 60;
        const spin = (Math.random() - 0.5) * 120;
        const delay = Math.random() * 5;
        
        ticket.style.setProperty('--i', delay);
        ticket.style.setProperty('--offset', offset);
        ticket.style.setProperty('--drift', drift);
        ticket.style.setProperty('--rotation', rotation);
        ticket.style.setProperty('--spin', spin);
        
        ticket.innerHTML = `
            <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
            <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
        `;
        
        backgroundContainer.appendChild(ticket);
    }
    
    // Create more bubbles
    for (let i = 0; i < 15; i++) {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');
        
        const size = 5 + Math.random() * 15;
        const offset = Math.random() * 100;
        const drift = (Math.random() - 0.5) * 20;
        const opacity = 0.1 + Math.random() * 0.2;
        const delay = Math.random() * 5;
        
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        bubble.style.setProperty('--i', delay);
        bubble.style.setProperty('--offset', offset);
        bubble.style.setProperty('--drift', drift);
        bubble.style.setProperty('--opacity', opacity);
        
        backgroundContainer.appendChild(bubble);
    }
}
</script>

<template>
    <Head title="Accueil" />

    <div class="min-h-screen flex flex-col justify-between relative">
        <!-- Header avec z-index élevé -->
        <Header />
        
        <!-- Background SVG animé avec z-index bas -->
        <div id="background" class="fixed inset-0 z-0">
            <div class="background-container">
                <!-- SVG Tickets -->
                <svg class="ticket" style="--i: 0; --offset: 10; --drift: 5; --rotation: 15; --spin: 45;" viewBox="0 0 40 20">
                    <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
                    <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
                </svg>
                <svg class="ticket" style="--i: 1; --offset: 30; --drift: -10; --rotation: -20; --spin: 60;" viewBox="0 0 40 20">
                    <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
                    <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
                </svg>
                <svg class="ticket" style="--i: 2; --offset: 50; --drift: 15; --rotation: 10; --spin: -30;" viewBox="0 0 40 20">
                    <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
                    <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
                </svg>
                <svg class="ticket" style="--i: 3; --offset: 70; --drift: -5; --rotation: -15; --spin: 20;" viewBox="0 0 40 20">
                    <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
                    <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
                </svg>
                <svg class="ticket" style="--i: 4; --offset: 85; --drift: 10; --rotation: 25; --spin: -45;" viewBox="0 0 40 20">
                    <path d="M0,0 L40,0 L40,15 C35,15 35,20 30,20 L10,20 C5,20 5,15 0,15 Z"></path>
                    <line x1="30" y1="0" x2="30" y2="20" stroke="rgba(255,255,255,0.3)" stroke-dasharray="2,2"/>
                </svg>
                
                <!-- Bubbles -->
                <div class="bubble" style="--i: 0; --offset: 20; --drift: 5; --opacity: 0.2; width: 15px; height: 15px;"></div>
                <div class="bubble" style="--i: 1; --offset: 40; --drift: -10; --opacity: 0.15; width: 10px; height: 10px;"></div>
                <div class="bubble" style="--i: 2; --offset: 60; --drift: 15; --opacity: 0.25; width: 20px; height: 20px;"></div>
                <div class="bubble" style="--i: 3; --offset: 80; --drift: -5; --opacity: 0.1; width: 8px; height: 8px;"></div>
                <div class="bubble" style="--i: 4; --offset: 10; --drift: 8; --opacity: 0.2; width: 12px; height: 12px;"></div>
                
                <!-- Wave SVG -->
                <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                    <path fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,202.7C672,203,768,181,864,181.3C960,181,1056,203,1152,208C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Main content avec z-index moyen -->
        <main class="relative z-5 flex-1 p-4">
            <slot />
        </main>

        <!-- Footer avec z-index élevé -->
        <div class="relative z-10">
            <Footer />
        </div>
    </div>
    <!--Curseur lumineux-->
    <div class="cursor"></div>
    <div class="cursor-glow"></div>
</template>

<style>
/* Styles pour le curseur */
/* Curseur lumineux */
body {
  cursor: default; /* Cache le curseur par défaut */
}

.cursor {
  position: fixed;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #F5DDDD;
  transform: translate(-50%, -50%);
  pointer-events: none;
  z-index: 9999;
  mix-blend-mode: screen;
  box-shadow: 0 0 20px 10px #dbb5b5;
  transition: width 0.2s, height 0.2s;
}

.cursor-glow {
  position: fixed;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  background: radial-gradient(circle, #F5DDDD 0%, rgba(0, 255, 255, 0) 70%);
  transform: translate(-50%, -50%);
  pointer-events: none;
  z-index: 9998;
  animation: pulse 2s infinite alternate;
}

@keyframes pulse {
  0% { transform: translate(-50%, -50%) scale(1); }
  100% { transform: translate(-50%, -50%) scale(1.2); }
}

/* Style pour les éléments interactifs */
button, a {
  cursor: none;
}
/* Styles pour le fond animé */
#background {
    background: linear-gradient(135deg, #3F51B5 , #000000, #FFC107);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.background-container {
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
}

.ticket {
    position: absolute;
    opacity: 0.4;
    fill: rgba(255, 255, 255, 0.7);
    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.3));
    animation-name: float;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
    animation-duration: calc(10s + (var(--i) * 5s));
    width: 60px;
    height: 30px;
}

@keyframes float {
    0% {
        transform: translateY(100vh) translateX(calc(var(--offset) * 1vw)) rotate(calc(var(--rotation) * 1deg));
        opacity: 0;
    }
    10% {
        opacity: 0.4;
    }
    90% {
        opacity: 0.4;
    }
    100% {
        transform: translateY(-100px) translateX(calc((var(--offset) + var(--drift)) * 1vw)) rotate(calc((var(--rotation) + var(--spin)) * 1deg));
        opacity: 0;
    }
}

.wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 15vh;
    opacity: 0.3;
}

.wave path {
    fill: rgba(255, 255, 255, 0.5);
}

.bubble {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.1));
    animation-name: bubbleFloat;
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
    animation-duration: calc(15s + (var(--i) * 3s));
}

@keyframes bubbleFloat {
    0% {
        transform: translateY(100vh) translateX(calc(var(--offset) * 1vw));
        opacity: 0;
    }
    10% {
        opacity: var(--opacity);
    }
    90% {
        opacity: var(--opacity);
    }
    100% {
        transform: translateY(-100px) translateX(calc((var(--offset) + var(--drift)) * 1vw));
        opacity: 0;
    }
}
</style>