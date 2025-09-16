<template>
  <div>
    <h3>Joueurs de la partie</h3>
    <form @submit.prevent="fetchPlayers">
      <input v-model="gameCode" type="text" placeholder="Code de la partie" required />
      <button type="submit">Afficher</button>
    </form>
    <ul>
      <li v-for="player in players" :key="player.id">
        {{ player.name }}
      </li>
    </ul>
    <div v-if="loading">Chargement...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
  </div>
</template>
<script setup>
import { ref } from 'vue';

const gameCode = ref('');
const players = ref([]);
const loading = ref(false);
const error = ref('');

async function fetchPlayers() {
  loading.value = true;
  error.value = '';
  players.value = [];
  try {
    // Récupère l'id du jeu via l'API
    const res = await fetch('/api/v1/games');
    const data = await res.json();
    const games = Array.isArray(data.data) ? data.data : [];
    const game = games.find(g => g.code === gameCode.value);
    if (!game) throw new Error('Code de partie invalide');

    // Requête pour récupérer les joueurs
    const response = await fetch(`/api/v1/games/${game.id}/players`);
    if (!response.ok) throw new Error('API error');
    const playersData = await response.json();
    players.value = Array.isArray(playersData.data) ? playersData.data : [];
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
</script>
