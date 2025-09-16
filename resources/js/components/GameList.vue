<template>
  <div>
    <h2>Liste des parties</h2>
    <ul>
      <li v-for="game in games" :key="game.id">
        Partie code: {{ game.code }} (ID: {{ game.id }})
        <router-link :to="`/game/${game.id}`">
          <button>Voir la salle</button>
        </router-link>
      </li>
    </ul>
    <div v-if="loading">Chargement...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';

const games = ref([]);
const loading = ref(false);
const error = ref('');

onMounted(async () => {
  loading.value = true;
  try {
    const response = await fetch('/api/v1/games');
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    games.value = Array.isArray(data.data) ? data.data : [];
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
});
</script>
