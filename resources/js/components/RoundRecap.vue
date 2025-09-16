<template>
  <div class="recap-bg">
    <div v-if="loading" class="recap-loading">Chargement du récapitulatif...</div>
    <div v-if="error" class="recap-error">Erreur: {{ error }}</div>
    <div v-if="recap" class="recap-content">
      <div class="recap-header">
        <div>
          <div class="recap-title">Lettre <span class="recap-letter">{{ recap.letter }}</span></div>
          <div class="recap-round">Round</div>
        </div>
      </div>
      <div v-for="cat in recap.categories" :key="cat.category" class="recap-category">
        <div class="recap-theme">Thème : <span class="recap-theme-name">{{ cat.category }}</span></div>
        <div class="recap-answers">
          <div v-for="player in cat.players" :key="player.player" class="recap-row">
            <span class="recap-player">{{ player.player }}</span>
            <span class="recap-answer">{{ player.answer || '-' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, watch } from 'vue';
const props = defineProps({
  gameId: Number,
  roundId: Number,
});
const recap = ref(null);
const loading = ref(false);
const error = ref('');
async function fetchRecap() {
  loading.value = true;
  error.value = '';
  try {
    const res = await fetch(`/api/v1/games/${props.gameId}/rounds/${props.roundId}/recap`);
    if (!res.ok) throw new Error('Impossible de charger le récapitulatif');
    const data = await res.json();
    recap.value = data;
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
onMounted(fetchRecap);
watch(() => props.roundId, fetchRecap);
</script>
<style scoped>
/* Fond noir/gris texturé */
.recap-bg {
  background: #18181b;
  min-height: 80vh;
  color: #f5f5f5;
  font-family: 'Montserrat', 'Arial', sans-serif;
  padding: 2rem 0;
  border-radius: 16px;
  box-shadow: 0 0 32px #0008;
  max-width: 480px;
  margin: 2rem auto;
}
.recap-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
}
/* .recap-logo supprimé */
.recap-title {
  font-size: 2rem;
  font-weight: bold;
  letter-spacing: 2px;
  color: #fff;
}
.recap-letter {
  color: #f53003;
  font-size: 2.2rem;
  font-family: 'Montserrat', 'Arial', sans-serif;
}
.recap-round {
  color: #f53003;
  font-size: 1.1rem;
  font-weight: 500;
}
.recap-category {
  margin-bottom: 2.5rem;
}
.recap-theme {
  font-size: 1.2rem;
  font-weight: 500;
  margin-bottom: 0.7rem;
  color: #fff;
}
.recap-theme-name {
  color: #f53003;
  font-weight: bold;
}
.recap-answers {
  background: #222226;
  border-radius: 8px;
  padding: 1rem 1.5rem;
  box-shadow: 0 1px 8px #0004;
}
.recap-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #333;
}
.recap-row:last-child {
  border-bottom: none;
}
.recap-player {
  font-weight: bold;
  color: #e3e3e0;
  font-size: 1.1rem;
  letter-spacing: 1px;
}
.recap-answer {
  font-family: 'Montserrat', 'Arial', sans-serif;
  font-size: 1.1rem;
  color: #fff;
  text-transform: capitalize;
}
.recap-loading {
  color: #f53003;
  text-align: center;
  font-size: 1.2rem;
  margin: 2rem 0;
}
.recap-error {
  color: #ff4433;
  text-align: center;
  font-size: 1.2rem;
  margin: 2rem 0;
}
</style>
