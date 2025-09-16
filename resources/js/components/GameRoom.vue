<template>
  <div>
    <h2>Salle de jeu</h2>
    <form @submit.prevent="fetchGame">
      <input v-model="gameCode" type="text" placeholder="Code de la partie" required />
      <button type="submit">Afficher la salle</button>
    </form>
    <div v-if="loading">Chargement...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
    <div v-if="game">
      <h3>Partie: {{ game.code }} (ID: {{ game.id }})</h3>
      <h4>Statut: {{ game.status }}</h4>
      <h4>Joueurs:</h4>
      <ul>
        <li v-for="player in players" :key="player.id">{{ player.name }}</li>
      </ul>
      <button @click="startRound" :disabled="startingRound || !game">Démarrer</button>
      <div v-if="startingRound">Démarrage du round...</div>
      <div v-if="round">
        <h4>Round courant: {{ round.id }}</h4>
        <div>Catégorie: {{ round.category_id }}</div>
      </div>
      <Scoreboard :gameId="game.id" />
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import Scoreboard from './Scoreboard.vue';

const gameCode = ref('');
const game = ref(null);
const players = ref([]);
const round = ref(null);
const loading = ref(false);
const error = ref('');
const startingRound = ref(false);
async function startRound() {
  if (!game.value) return;
  startingRound.value = true;
  try {
    const response = await fetch(`/api/v1/games/${game.value.id}/rounds/start`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
    });
    if (!response.ok) throw new Error('Erreur lors du démarrage du round');
    // Recharge le round courant
    const resRound = await fetch(`/api/v1/games/${game.value.id}/rounds/current`);
    if (resRound.ok) {
      const dataRound = await resRound.json();
      round.value = dataRound.data || null;
    }
  } catch (e) {
    error.value = e.message;
  } finally {
    startingRound.value = false;
  }
}

async function fetchGame() {
  loading.value = true;
  error.value = '';
  game.value = null;
  players.value = [];
  round.value = null;
  try {
    // Récupère l'id du jeu via l'API
    const res = await fetch('/api/v1/games');
    const data = await res.json();
    const games = Array.isArray(data.data) ? data.data : [];
    const foundGame = games.find(g => g.code === gameCode.value);
    if (!foundGame) throw new Error('Code de partie invalide');
    game.value = foundGame;

    // Récupère les joueurs
    const resPlayers = await fetch(`/api/v1/games/${game.value.id}/players`);
    const dataPlayers = await resPlayers.json();
    players.value = Array.isArray(dataPlayers.data) ? dataPlayers.data : [];

    // Récupère le round courant
    const resRound = await fetch(`/api/v1/games/${game.value.id}/rounds/current`);
    if (resRound.ok) {
      const dataRound = await resRound.json();
      round.value = dataRound.data || null;
    }
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
</script>
